<?php
$title = "Lekcije";
include_once("header.php");
include_once("../class/language.php");
include_once("../class/lesson.php");
include_once("../class/user.php");
$db = connect();
session_start();

if (isset($_GET['lid'])) {
    $language_id = (int)$_GET['lid'];
} else {
    $_SESSION['redirect_message'] = "Dogodila se pogreška!";
    $_SESSION['show_modal'] = "redirectModal";
    header("Location: languages.php");
}

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    if (check_user_status() == 1) {
        $menu_items['main'] = array('Jezici', 'Korisnici', 'Ovlasti');
        $menu_links['main'] = array('languages.php', 'users.php', 'roles.php');
    } else if (check_user_status() == 2) {
        $menu_items['main'] = array('Jezici');
        $menu_links['main'] = array('languages.php');
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

$menu_items['sub'] = array('Nova lekcija');
$menu_links['sub'] = array('new_lesson.php?lid=' . $language_id);
sidemenu($menu_items, $menu_links, "Jezici");

$language = new Language($db);
$language->set_id($language_id);

$lang_stmt = $language->getLanguages($db);
$lang_numrows = $lang_stmt->rowCount();

$lang_name_stmt = $language->getLanguageById($language_id);

if ($lang_name_stmt) {
    $lang_name_row = $lang_name_stmt->fetch(PDO::FETCH_ASSOC);
    $lang_name = $lang_name_row['name'];
    $lang_id = $language_id;
}
?>

<div id="page-content-wrapper">
    <?php user_header($user_id, $db); ?>
    <div class="container-fluid">
        <h1 class="mt-4">Popis lekcija za jezik <?php echo $lang_name ?></h1>
        <?php
        $lesson = new Lesson($db);
        $lesson->set_language_id($language_id);

        $stmt = $lesson->getLessons(true, $db);
        $numrows = $stmt->rowCount();

        if ($numrows > 0) {
            $c = 0;
            while ($lesson_row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($lesson_row);
                echo "<br>";
                echo "Ime lekcije: " . $name;
                echo "<br>";
                echo "Opis lekcije: " . $description;
                echo "<br><br>";
        ?>
                <a class="btn btn-outline-light" href="questions.php?lid=<?php echo $id ?>" role="button">Pitanja</a><br>
                <button class="btn btn-outline-light-pink" data-toggle="modal" data-target="#lessEditModal<?php echo $c; ?>">Uredi</button>
                <button class="btn btn-outline-strong-pink" data-toggle="modal" data-target="#lessDelModal<?php echo $c; ?>">Obriši</button>

                <!-- less edit modal -->
                <div class="modal fade" id="lessEditModal<?php echo $c; ?>" tabindex="-1" role="dialog" aria-labelledby="lessEditModal<?php echo $c; ?>" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title text-dark lesseditModalLabel">Uredi lekciju</h5>
                                <button type="button" class="close close-button" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div id="message-less-edit<?php echo $c; ?>" class='text-success'>
                                    <p class="val-msg" id='val-msg-less-edit<?php echo $c; ?>'></p>
                                </div>
                                <form method="post" action="" id="lessEdit<?php echo $c; ?>">
                                    <input type="hidden" name="less-edit-submitted<?php echo $c; ?>" id="less-edit-submitted<?php echo $c; ?>">
                                    <input type="hidden" name="less-edit-id<?php echo $c; ?>" value="<?php echo $id ?>" id="less-edit-id<?php echo $c; ?>">
                                    <div class="form-group">
                                        <label class="text-dark" for="less-name<?php echo $c; ?>">Naziv</label>
                                        <input type="text" class="form-control" id="less-name<?php echo $c; ?>" name="less-name<?php echo $c; ?>" aria-describedby="lessnameHelp" placeholder="Upišite naziv jezika" value="<?php echo $name ?>">
                                        <small id="lessnameHelp<?php echo $c; ?>" class="form-text text-muted">Naziv ne smije sadržavati više od 100 znakova.</small>
                                    </div>
                                    <div class="form-group">
                                        <label class="text-dark" for="less-desc<?php echo $c; ?>">Opis</label>
                                        <textarea class="form-control" id="less-desc<?php echo $c; ?>" name="less-desc<?php echo $c; ?>" rows="6" aria-describedby="lessdescHelp" placeholder="Upišite opis jezika"><?php echo $description ?></textarea>
                                        <small id="lessdescHelp<?php echo $c; ?>" class="form-text text-muted">Opis mora sadržavati barem 100 znakova.</small>
                                    </div>
                                    <div class="form-group">
                                        <label class="text-dark" for="less-desc<?php echo $c; ?>">Jezik</label><br>
                                        <select class="form-select custom-select custom-select-sm" name="less-lang<?php echo $c; ?>" id="less-lang<?php echo $c; ?>">
                                            <option value="0" selected>Programski jezik...</option>
                                            <?php
                                            if ($lang_numrows > 0) {
                                                $l = 0;
                                                while ($lang_row = $lang_stmt->fetch(PDO::FETCH_ASSOC)) {
                                                    if ($lang_row['id'] == $lang_id) {
                                            ?>
                                                        <option selected value="<?php echo $lang_row['id'] ?>"><?php echo $lang_row['name'] ?></option>
                                                    <?php
                                                    } else {
                                                    ?>
                                                        <option value="<?php echo $lang_row['id'] ?>"><?php echo $lang_row['name'] ?></option>
                                            <?php    }
                                                }
                                            }
                                            ?>
                                        </select>
                                        <small id="lessdescHelp<?php echo $c; ?>" class="form-text text-muted">Programski jezik za koji je namijenjena lekcija.</small>
                                    </div>
                            </div>
                            <div class="modal-footer">
                                <input type="button" class="btn btn-pink lessEditSubmit" id="lessEditSubmit-<?php echo $c; ?>" value="Uredi lekciju">
                                <!--<button type="button" data-dismiss="modal" aria-label="Close" class="btn btn-outline-secondary">Odustani</button>-->
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- less del confirmation modal -->
                <div class="modal fade" id="lessDelModal<?php echo $c; ?>" tabindex="-1" role="dialog" aria-labelledby="lessDelModal<?php echo $c; ?>" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title text-dark lessdelModalLabel">Obriši lekciju</h5>
                                <button type="button" class="close close-button" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div id="message-less-del<?php echo $c; ?>" class='text-success'>
                                    <p class="val-msg" id='val-msg-less-del<?php echo $c; ?>'></p>
                                </div>
                                <form method="post" action="" id="lessEdit<?php echo $c; ?>">
                                    <input type="hidden" name="less-del-id<?php echo $c; ?>" value="<?php echo $id ?>" id="less-del-id<?php echo $c; ?>">
                                    <h5 class="text-dark" id="less-del-confirm-text<?php echo $c; ?>">Jeste li sigurni da želite obrisati <?php echo $name ?>?</h5>
                            </div>
                            <div class="modal-footer">
                                <input type="button" class="btn btn-pink lessDelSubmit" id="lessDelSubmit-<?php echo $c; ?>" value="Obriši lekciju">
                                <button type="button" id="lessDelCancel<?php echo $c; ?>" data-dismiss="modal" aria-label="Close" class="btn btn-outline-danger">Odustani</button>
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
        $('.lessEditSubmit').click(function() {
            var clickedBtnID = $(this).attr('id');
            var modal_id = clickedBtnID.split("-").pop();

            var fd = new FormData();
            var submitted = $('#less-edit-submitted' + modal_id).val();
            var id = $('#less-edit-id' + modal_id).val();
            var name = $('#less-name' + modal_id).val();
            var description = $('#less-desc' + modal_id).val();
            var language_id = $('#less-lang' + modal_id).val();

            fd.append('submitted', submitted);
            fd.append('id', id);
            fd.append('less-name', name);
            fd.append('less-desc', description);
            fd.append('less-lang', language_id);

            $.ajax({
                url: 'edit_lesson.php',
                type: 'post',
                cache: false,
                data: fd,
                contentType: false,
                processData: false,
                dataType: "JSON",
                success: function(response) {
                    if (response.status == 1) {
                        $('#message-less-edit' + modal_id).attr('class', 'text-success');
                        $('#val-msg-less-edit' + modal_id).html(response.message);
                    } else {
                        $('#message-less-edit' + modal_id).attr('class', 'text-danger');
                        $('#val-msg-less-edit' + modal_id).html(response.message);
                    }
                },
                error: function() {
                    $('#message-less-edit' + modal_id).attr('class', 'text-danger');
                    $('#val-msg-less-edit' + modal_id).html("Dogodila se pogreška!");
                }
            });
        });

        $('.lessDelSubmit').click(function() {
                var clickedBtnID = $(this).attr('id');
                var modal_id = clickedBtnID.split("-").pop();

                var fd = new FormData();
                var id = $('#less-del-id' + modal_id).val();

                fd.append('id', id);

                alert(id); 

                $.ajax({
                    url: 'delete_lesson.php',
                    type: 'post',
                    cache: false,
                    data: fd,
                    contentType: false,
                    processData: false,
                    dataType: "JSON",
                    success: function(response) {
                        if (response.status == 1) {
                            $('#message-less-del' + modal_id).attr('class', 'text-success');
                            $('#val-msg-less-del' + modal_id).html(response.message);
                            $('#less-del-confirm-text' + modal_id).html('');
                            $('#lessDelCancel' + modal_id).attr('display','none');
                        } else {
                            $('#message-less-del' + modal_id).attr('class', 'text-danger');
                            $('#val-msg-less-del' + modal_id).html(response.message);
                        }
                    },
                    error: function() {
                        $('#message-less-del' + modal_id).attr('class', 'text-danger');
                        $('#val-msg-less-del' + modal_id).html("Dogodila se pogreška!");
                    }
                });
            });
    </script>