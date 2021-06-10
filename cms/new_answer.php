<?php
$title = "Novi odgovor";
include_once("header.php");
include_once("../class/question.php");
include_once("../class/question_type.php");
include_once("../class/answer.php");
$db = connect();

if (isset($_GET['qid'])) {
    $question_id = (int)$_GET['qid'];
} else {
    $_SESSION['redirect_message'] = "Dogodila se pogreška!";
    $_SESSION['show_modal'] = "redirectModal";
    header("Location: languages.php");
}

$quest = new Question($db);
$quest_stmt = $quest->getQuestionById($question_id);

if ($quest_stmt) {
    $quest_row = $quest_stmt->fetch(PDO::FETCH_ASSOC);
    extract($quest_row);
    $type = $question_type;
} else {
    $_SESSION['redirect_message'] = "Dogodila se pogreška!";
    $_SESSION['show_modal'] = "redirectModal";
    header("Location: languages.php");
}

if ($type == 0) {
    $_SESSION['redirect_message'] = "Dogodila se pogreška!";
    $_SESSION['show_modal'] = "redirectModal";
    header("Location: languages.php");
}

if (isset($_POST['submitted'])) {

    $form_fields = array();
    $form_names = array();

    switch ($type) {
        case 3:
            $form_fields = array('answer');
            $form_names = array('Odgovor');
            break;
        case 4:
            $form_fields = array('answer');
            $form_names = array('Odgovor');
            break;
        case 1:
            $answer_count = $_POST['answr-count'];
            for ($j = 1; $j <= $answer_count; $j++) {
                array_push($form_fields, 'answer-' . $j);
                array_push($form_names, 'Odgovor # ' . $j);
            }
            break;
        case 2:
            $answer_count = $_POST['answr-count'];
            for ($j = 1; $j <= $answer_count; $j++) {
                array_push($form_fields, 'answer-' . $j);
                array_push($form_names, 'Odgovor # ' . $j);
            }
            break;
    }

    $errors = validate($form_fields, $form_names, $db, null, "Answer");
}

$menu_items['main'] = array('Jezici', 'Odgovori', 'Korisnici', 'Ovlasti');
$menu_links['main'] = array('languages.php', 'answers.php?qid=' . $question_id, 'users.php', 'roles.php');
$menu_items['sub'] = array();
$menu_links['sub'] = array();
sidemenu($menu_items, $menu_links, "Odgovori");
?>
<div id="page-content-wrapper">
    <div class="container-fluid">
        <h1 class="mt-4">Dodaj odgovor</h1>
        <?php
        if (isset($errors)) {

            if (sizeof($errors) > 0) {

                foreach ($errors as $err) {
        ?>
                    <div class="invalid-feedback" style="display:block; font-size:16px">
                        <?php
                        echo $err;
                        ?>
                    </div>
                    <?php
                }
            } else {
                if ($type == 3 || $type == 4) {

                    $answer = $_POST['answer'];

                    if (!isset($_POST['correct'])) {
                        $correct = 1;
                    }

                    $answr = new Answer($db);
                    $answr->set_answer($answer);
                    $answr->set_correct($correct);
                    $answr->set_question_id($question_id);

                    if ($answr->createAnswer()) {
                    ?>
                        <div class="valid-feedback" style="display:block; font-size:16px">
                            Odgovor uspješno dodan!
                        </div>
                    <?php
                    }
                } else if ($type == 1 || $type == 2) {

                    $answers = array();
                    $correct = array();
                    $success = array();

                    for ($k = 1; $k <= $answer_count; $k++) {
                        array_push($answers, $_POST['answer-' . $k]);
                        if (isset($_POST['correct-' . $k])) {
                            array_push($correct, $_POST['correct-' . $k]);
                        } else {
                            array_push($correct, "0");
                        }
                    }

                    if (!in_array("1", $correct)) { ?>
                        <div class="invalid-feedback" style="display:block; font-size:16px">
                            Niste odabrali točan odgovor!
                        </div>
                    <?php
                    }else{
                    for ($a = 0; $a <= sizeof($answers) - 1; $a++) {
                        $answr = new Answer($db);
                        $answr->set_answer($answers[$a]);
                        $answr->set_correct($correct[$a]);
                        $answr->set_question_id($question_id);

                        if ($answr->createAnswer()) {
                            array_push($success, "1");
                        } else {
                            array_push($success, "0");
                        }
                    }

                    if (!in_array("0", $success)) {   ?>
                        <div class="valid-feedback" style="display:block; font-size:16px">
                            Odgovori uspješno dodani!
                        </div>
                    <?php
                    } else {  ?>
                        <div class="invalid-feedback" style="display:block; font-size:16px">
                            Greška pri dodavanju odgovora!
                        </div>
        <?php
                    }
                }
                }
            }
        }

        ?>
        <?php
        // nadopunjavanje ili kodiranje
        if ($type == 3 || $type == 4) {
        ?>
            <form method="POST" action="">
                <input type="hidden" name="submitted" id="submitted">
                <div class="form-group">
                    <?php if ($type == 3) {  ?>
                        <label for="answer">Odgovor</label>
                        <input type="text" class="form-control" id="answer" name="answer" aria-describedby="answerHelp" placeholder="Upišite odgovor" <?php if (isset($_POST['answer'])) { ?> value="<?php echo $_POST['answer'] ?>" <?php } ?>>
                    <?php } else if ($type == 4) {  ?>
                        <label for="answer">Odgovor</label>
                        <textarea placeholder="Upišite odgovor" class="form-control" rows="9" id="answer" name="answer"><?php if (isset($_POST['answer'])) {
                                                                                                                            echo $_POST['answer'];
                                                                                                                        } ?></textarea>
                    <?php }  ?>
                </div>
                <button type="submit" class="btn btn-pink" id="answr-submit">Dodaj odgovor</button>
                <button type="button" onclick="window.history.go(-1);" class="btn btn-outline-light-pink">Odustani</button>
            </form>
    </div>
<?php
        }
        //jedan točan ili više točnih
        if ($type == 1 || $type == 2) {
?>
    <form method="POST" action="">
        <div class="form-group col-md-3">
            <label for="answr-count">Broj odgovora</label>
            <select class="form-select custom-select custom-select-sm" name="answr-count" id="answr-count">
                <option value="0" selected>Broj odgovora...</option>
                <?php
                for ($i = 2; $i <= 8; $i++) {  ?>
                    <option value="<?php echo $i ?>"><?php echo $i ?></option>
                <?php
                }
                ?>
            </select>
        </div>
        <input type="hidden" name="submitted" id="submitted">
        <?php
            for ($i = 1; $i <= 8; $i++) {
        ?>
            <div class="form-group col-md-6" id="answer-<?php echo $i ?>" style="display:none;">
                <label for="answer-<?php echo $i ?>">Odgovor #<?php echo $i ?></label>
                <input type="text" class="form-control" id="answer-<?php echo $i ?>" name="answer-<?php echo $i ?>" aria-describedby="answer<?php echo $i ?>Help" placeholder="Upišite odgovor" <?php if (isset($_POST['answer-<?php echo $i ?>'])) { ?> value="<?php echo $_POST['answer-<?php echo $i ?>'] ?>" <?php } ?>>
                <div class="custom-control custom-switch">
                    <input class="custom-control-input correct" type="checkbox" id="correct-<?php echo $i ?>" name="correct-<?php echo $i ?>" value="1">
                    <label class="custom-control-label" for="correct-<?php echo $i ?>">Točan</label>
                </div>
                <small class="form-text text-pink questtypeHelp">Označite ako je odgovor točan.<?php if ($type == 1) {  ?> Samo jedan odgovor može biti označen kao točan.<?php } else if ($type == 2) { ?>Više odgovora može biti označeno kao točan.<?php } ?></small>
            </div>
        <?php
            }
        ?>
        <button type="submit" class="btn btn-pink" id="answr-submit">Dodaj</button>
        <button type="button" onclick="window.history.go(-1);" class="btn btn-outline-light-pink">Odustani</button>
    </form>
<?php
        }
?>
<?php
include_once("footer.php");
?>

<script>
    $('#answr-count').on('change', function() {
        var count = $('#answr-count option:selected').val();

        for (var i = 1; i <= 8; i++) {
            $('#answer-' + i).css({
                'display': 'none'
            });
        }

        for (var j = 1; j <= count; j++) {
            $('#answer-' + j).css({
                'display': 'block'
            });
        }
    });

    $('.correct').on('click', function() {
        var count = $('#answr-count option:selected').val();

        var checked_boxes = $("input[type='checkbox']:checked").length;

        var one = $('.questtypeHelp:contains("Samo jedan")').length;
        //var multiple = $('.questtypeHelp:contains("Više odgovora")').length;

        if (one > 0) {
            if (checked_boxes > 0) {

                $('.correct:not(:checked)').each(function(i, obj) {
                    $(this).prop("disabled", true);
                });

                // for (var j = 1; j <= count; j++) {

                // }
            }

            if (checked_boxes == 0) {
                $('.correct').each(function(i, obj) {
                    $(this).prop("disabled", false);
                });
            }
        }
    });
</script>