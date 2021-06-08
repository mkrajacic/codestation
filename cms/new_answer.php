<?php
$title = "Novi odgovor";
include_once("header.php");
include_once("class/question.php");
include_once("class/question_type.php");
include_once("class/answer.php");
$db = connect();

if (isset($_GET['qid'])) {
    $question_id = (int)$_GET['qid'];
} else {
    $_SESSION['redirect_message'] = "Dogodila se pogreška!";
    $_SESSION['show_modal'] = "redirectModal";
    header("Location: languages.php");
}

if (isset($_POST['submitted'])) {
    $form_fields = array('answer');
    $form_names = array('Odgovor');

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
        <form method="POST" action="">
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
                    $answer = $_POST['answer'];

                    if (!isset($_POST['correct'])) {
                        $correct = 0;
                    } else {
                        $correct = $_POST['correct'];
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
                }
            }

            $quest = new Question($db);
            $quest_stmt = $quest->getQuestionById($question_id);

            if ($quest_stmt) {
                $quest_row = $quest_stmt->fetch(PDO::FETCH_ASSOC);
                extract($quest_row);
                $type = $question_type;
            }else{
                $_SESSION['redirect_message'] = "Dogodila se pogreška!";
                $_SESSION['show_modal'] = "redirectModal";
                header("Location: languages.php");
            }

            if($type==0) {
                $_SESSION['redirect_message'] = "Dogodila se pogreška!";
                $_SESSION['show_modal'] = "redirectModal";
                header("Location: languages.php");
            }else if($type==1) {

            }

            ?>
            <input type="hidden" name="submitted" id="submitted">
            <div class="form-group">
                <label for="answer">Odgovor</label>
                <input type="text" class="form-control" id="answer" name="answer" aria-describedby="answerHelp" placeholder="Upišite odgovor" <?php if (isset($_POST['answer'])) { ?> value="<?php echo $_POST['answer'] ?>" <?php } ?>>
            </div>
            <div class="form-group">
                <div class="custom-control custom-switch">
                    <input class="custom-control-input" type="checkbox" id="correct" name="correct" value="1">
                    <label class="custom-control-label" for="correct">Točan</label>
                </div>
                <small id="questtypeHelp" class="form-text text-pink">Označite ako je odgovor točan.</small>
            </div>
            <button type="submit" class="btn btn-pink">Dodaj odgovor</button>
            <button type="button" onclick="window.history.go(-1);" class="btn btn-outline-light-pink">Odustani</button>
        </form>
    </div>

    <?php
    include_once("footer.php");
    ?>