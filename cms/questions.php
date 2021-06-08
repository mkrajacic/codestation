<?php
$title = "Pitanja";
include_once("header.php");
include_once("class/language.php");
include_once("class/lesson.php");
include_once("class/question.php");
include_once("class/question_type.php");
include_once("class/user.php");
$db = connect();
session_start();

if (isset($_GET['lid'])) {
    $lesson_id = (int)$_GET['lid'];
    $lesson = new Lesson($db);
    $lang_id_stmt = $lesson->getLessonById($lesson_id);

    if ($lang_id_stmt) {
        $lesson_row = $lang_id_stmt->fetch(PDO::FETCH_ASSOC);
        extract($lesson_row);
        $lang_id = $language_id;
        $lname = $name;
    }
} else {
    $_SESSION['redirect_message'] = "Dogodila se pogreška!";
    $_SESSION['show_modal'] = "redirectModal";
    header("Location: languages.php");
}

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    if (check_user_status() == 1) {
        $menu_items['main'] = array('Jezici', 'Lekcije', 'Korisnici', 'Ovlasti');
        $menu_links['main'] = array('languages.php', 'lessons.php?lid=' . $lang_id, 'users.php', 'roles.php');
    } else if (check_user_status() == 2) {
        $menu_items['main'] = array('Jezici', 'Lekcije');
        $menu_links['main'] = array('languages.php', 'lessons.php?lid=' . $lang_id);
    } else {
        $_SESSION['redirect_message'] = "Nije vam dopušten pristup sadržaju!";
        $_SESSION['show_modal'] = "redirectModal";
        header("Location: index.php");
    }
} else {
    $_SESSION['redirect_message'] = "Nije vam dopušten pristup sadržaju!";
    $_SESSION['show_modal'] = "redirectModal";
    header("Location: index.php");
}

$menu_items['sub'] = array('Novo pitanje');
$menu_links['sub'] = array('new_question.php?lid=' . $lesson_id);
sidemenu($menu_items, $menu_links, "Jezici");
?>

<div id="page-content-wrapper">
    <?php user_header($user_id, $db); ?>
    <div class="container-fluid">
        <h1 class="mt-4">Popis pitanja za lekciju <?php echo $lname ?></h1>
        <?php
        $question = new Question($db);
        $question->set_lesson_id($lesson_id);

        $stmt = $question->getQuestions(true, $db);
        $numrows = $stmt->rowCount();

        if ($numrows > 0) {
            $c = 0;
            while ($question_row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($question_row);
                echo "<br>";
                echo "Pitanje: " . $question;
                echo "<br>";
                echo "ID lekcije: " . $lesson_id;
                echo "<br>";
                echo "Vrsta pitanja: " . $question_type;
                echo "<br><br>";
        ?>
                <a class="btn btn-outline-light" href="answers.php?qid=<?php echo $id ?>" role="button">Odgovori</a><br>
                <button type="button" class="btn btn-outline-light-pink" data-toggle="modal" data-target="#questEditModal<?php echo $c; ?>">Uredi</button>
                <button type="button" class="btn btn-outline-strong-pink" data-toggle="modal" data-target="#questDelModal<?php echo $c; ?>">Obriši</button>

                <!-- quest edit modal -->
                <div class="modal fade" id="questEditModal<?php echo $c; ?>" tabindex="-1" role="dialog" aria-labelledby="questEditModal<?php echo $c; ?>" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title text-dark questeditModalLabel">Uredi pitanje</h5>
                                <button type="button" class="close close-button" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div id="message-quest-edit<?php echo $c; ?>" class='text-success'>
                                    <p class="val-msg" id='val-msg-quest-edit<?php echo $c; ?>'></p>
                                </div>
                                <form method="post" action="" id="questEdit<?php echo $c; ?>">
                                    <input type="hidden" name="quest-edit-submitted<?php echo $c; ?>" id="quest-edit-submitted<?php echo $c; ?>">
                                    <input type="hidden" name="quest-edit-id<?php echo $c; ?>" value="<?php echo $id ?>" id="quest-edit-id<?php echo $c; ?>">
                                    <div class="form-group">
                                        <label class="text-dark" for="question<?php echo $c; ?>">Pitanje</label>
                                        <input type="text" class="form-control" id="question<?php echo $c; ?>" name="question<?php echo $c; ?>" aria-describedby="questnameHelp" placeholder="Upišite naziv lekcije" value="<?php echo $question ?>">
                                        <small id="questnameHelp<?php echo $c; ?>" class="form-text text-muted">Tekst pitanja ne smije sadržavati manje od 10 znakova.</small>
                                    </div>
                                    <div class="form-group">
                                        <label class="text-muted" for="quest-type<?php echo $c; ?>">Vrsta pitanja</label>
                                        <select class="form-select custom-select custom-select-sm" name="quest-type<?php echo $c; ?>" id="quest-type<?php echo $c; ?>">
                                            <option value="0" selected>Vrsta pitanja...</option>
                                            <?php
                                            $type = new QuestionType($db);
                                            $type_stmt = $type->getTypes($db);
                                            $type_numrows = $type_stmt->rowCount();

                                            if ($type_numrows > 0) {
                                                while ($type_row = $type_stmt->fetch(PDO::FETCH_ASSOC)) {
                                                    if ($type_row['id'] == $question_type) {   ?>
                                                        <option selected value="<?php echo $type_row['id'] ?>"><?php echo $type_row['type'] ?></option>
                                                    <?php
                                                    } else {  ?>
                                                        <option value="<?php echo $type_row['id'] ?>"><?php echo $type_row['type'] ?></option>
                                            <?php
                                                    }
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="text-muted" for="quest-less<?php echo $c; ?>">Lekcija</label>
                                        <select class="form-select custom-select custom-select-sm" name="quest-less<?php echo $c; ?>" id="quest-less<?php echo $c; ?>">
                                            <option value="0" selected>Lekcija...</option>
                                            <?php
                                            $lesson = new Lesson($db);
                                            $lesson_stmt = $lesson->getLessons(false, $db);
                                            $lesson_numrows = $lesson_stmt->rowCount();

                                            if ($lesson_numrows > 0) {
                                                while ($lesson_row = $lesson_stmt->fetch(PDO::FETCH_ASSOC)) {
                                                    if ($lesson_row['id'] == $lesson_id) {   ?>
                                                        <option selected value="<?php echo $lesson_row['id'] ?>"><?php echo $lesson_row['name'] ?></option>
                                                    <?php
                                                    } else {  ?>
                                                        <option value="<?php echo $lesson_row['id'] ?>"><?php echo $lesson_row['name'] ?></option>
                                            <?php
                                                    }
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                            </div>
                            <div class="modal-footer">
                                <input type="button" class="btn btn-pink questEditSubmit" id="questEditSubmit-<?php echo $c; ?>" value="Uredi lekciju">
                                <!--<button type="button" data-dismiss="modal" aria-label="Close" class="btn btn-outline-secondary">Odustani</button>-->
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- quest del confirmation modal -->
                <div class="modal fade" id="questDelModal<?php echo $c; ?>" tabindex="-1" role="dialog" aria-labelledby="questDelModal<?php echo $c; ?>" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title text-dark questdelModalLabel">Obriši lekciju</h5>
                                <button type="button" class="close close-button" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div id="message-quest-del<?php echo $c; ?>" class='text-success'>
                                    <p class="val-msg" id='val-msg-quest-del<?php echo $c; ?>'></p>
                                </div>
                                <form method="post" action="" id="questEdit<?php echo $c; ?>">
                                    <input type="hidden" name="quest-del-id<?php echo $c; ?>" value="<?php echo $id ?>" id="quest-del-id<?php echo $c; ?>">
                                    <h5 class="text-dark" id="quest-del-confirm-text<?php echo $c; ?>">Jeste li sigurni da želite obrisati <?php echo $question ?>?</h5>
                            </div>
                            <div class="modal-footer">
                                <input type="button" class="btn btn-pink questDelSubmit" id="questDelSubmit-<?php echo $c; ?>" value="Obriši pitanje">
                                <button type="button" id="questDelCancel<?php echo $c; ?>" data-dismiss="modal" aria-label="Close" class="btn btn-outline-danger">Odustani</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

        <?php
                $c++;
            }
        } else {
            echo "Nema rezultata";
        }
        ?>
    </div>

    <?php
    include_once("footer.php");
    ?>

    <script>
        $('.questEditSubmit').click(function() {
            var clickedBtnID = $(this).attr('id');
            var modal_id = clickedBtnID.split("-").pop();

            var fd = new FormData();
            var submitted = $('#quest-edit-submitted' + modal_id).val();
            var id = $('#quest-edit-id' + modal_id).val();
            var question = $('#question' + modal_id).val();
            var question_type = $('#quest-type' + modal_id + ' option:selected').val()
            var question_lession = $('#quest-less' + modal_id +  ' option:selected').val()

            fd.append('submitted', submitted);
            fd.append('id', id);
            fd.append('question', question);
            fd.append('quest-type', question_type);
            fd.append('quest-less', question_lession);

            $.ajax({
                url: 'edit_question.php',
                type: 'post',
                cache: false,
                data: fd,
                contentType: false,
                processData: false,
                dataType: "JSON",
                success: function(response) {
                    if (response.status == 1) {
                        $('#message-quest-edit' + modal_id).attr('class', 'text-success');
                        $('#val-msg-quest-edit' + modal_id).html(response.message);
                    } else {
                        $('#message-quest-edit' + modal_id).attr('class', 'text-danger');
                        $('#val-msg-quest-edit' + modal_id).html(response.message);
                    }
                },
                error: function(xhr) {
                    $('#message-quest-edit' + modal_id).attr('class', 'text-danger');
                    $('#val-msg-quest-edit' + modal_id).html("Dogodila se pogreška!" + xhr.responseText + "odabrani valuei su question type: " + question_type + " i question_lesson: " + question_lession);
                }
            });
        });

        $('.questDelSubmit').click(function() {
            var clickedBtnID = $(this).attr('id');
            var modal_id = clickedBtnID.split("-").pop();

            var fd = new FormData();
            var id = $('#quest-del-id' + modal_id).val();

            fd.append('id', id);

            $.ajax({
                url: 'delete_question.php',
                type: 'post',
                cache: false,
                data: fd,
                contentType: false,
                processData: false,
                dataType: "JSON",
                success: function(response) {
                    if (response.status == 1) {
                        $('#message-quest-del' + modal_id).attr('class', 'text-success');
                        $('#val-msg-quest-del' + modal_id).html(response.message);
                        $('#quest-del-confirm-text' + modal_id).html('');
                        $('#questDelCancel' + modal_id).attr('display', 'none');
                    } else {
                        $('#message-quest-del' + modal_id).attr('class', 'text-danger');
                        $('#val-msg-quest-del' + modal_id).html(response.message);
                    }
                },
                error: function() {
                    $('#message-quest-del' + modal_id).attr('class', 'text-danger');
                    $('#val-msg-quest-del' + modal_id).html("Dogodila se pogreška!");
                }
            });
        });
    </script>