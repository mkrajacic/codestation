<?php
$title = "Novi odgovor";
include_once("header.php");
include_once("../class/question.php");
include_once("../class/question_type.php");
include_once("../class/answer.php");
include_once("../class/user.php");
include_once("../class/coding_answer.php");
$db = connect();
session_start();

if (isset($_GET['qid'])) {
    $question_id = (int)$_GET['qid'];
} else {
    $_SESSION['redirect_message'] = "Greška pri učitavanju sadržaja!";
    header_redirect();
}

$auth = isAuthorized();
if (($auth == 0) || ($auth == 3)) {
    $_SESSION['redirect_message'] = "Nije vam dopušten pristup sadržaju!";
    header_redirect();
}

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    $user = new User($db);
    if ($stmt = $user->getUserById($user_id)) {
        $user_row = $stmt->fetch(PDO::FETCH_ASSOC);
        extract($user_row);
        $user_name = $username;
        $avi = $image;
    }
    if (check_user_status() == 1) {
        $menu_items['main'] = array('Jezici', 'Odgovori', 'Korisnici');
        $menu_links['main'] = array('languages.php', 'answers.php?qid=' . $question_id, 'users.php');
    }else if (check_user_status() == 2) {
        $menu_items['main'] = array('Jezici', 'Odgovori');
        $menu_links['main'] = array('languages.php', 'answers.php?qid=' . $question_id);
    }
}else {
    $_SESSION['redirect_message'] = "Nije vam dopušten pristup sadržaju!";
    header_redirect();
}

$quest = new Question($db);
$quest_stmt = $quest->getQuestionById($question_id);

$hasCorrect = false;

if ($quest_stmt) {
    $quest_row = $quest_stmt->fetch(PDO::FETCH_ASSOC);
    extract($quest_row);
    $type = $question_type;

    if(($question_type==1) || ($question_type==2)) {
        $answer = new Answer($db);
        $answer->set_question_id($id);
        $answer_stmt = $answer->getAnswers(true);
        $answers_numrows = $answer_stmt->rowCount();

        if($question_type==1) {
            $answersQ = new Answer($db);
            $answersQ->set_question_id($id);
            $answersQ_stmt = $answersQ->getAnswersByCorrect(true);
            $answersQ_numrows = $answersQ_stmt->rowCount();
        
            if ($answersQ_numrows > 0) {
                $hasCorrect=true;
            }
        }
    }

} else {
    $_SESSION['redirect_message'] = "Greška pri učitavanju sadržaja!";
    header_redirect();
}

if ($type == 0) {
    $_SESSION['redirect_message'] = "Greška pri učitavanju sadržaja!";
    header_redirect();
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
            $form_fields = array('answer-code');
            $form_names = array('Kod odgovora');
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

$menu_items['sub'] = array();
$menu_links['sub'] = array();
$category = "Odgovori";
sidemenu($menu_items, $menu_links, $category, $user_id, $user_name, $avi);
?>
<div id="page-content-wrapper">
    <div class="container-fluid">
        <h1 class="mt-4 create-new">Dodaj odgovor</h1>
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
                if ($type == 3) {

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
                } else if ($type == 4) {

                    $code = $_POST['answer-code'];
                    $display = $_POST['answer-display'];

                    $code_answr = new CodingAnswer($db);
                    $code_answr->set_code($code);
                    $code_answr->set_display($display);
                    $code_answr->set_question_id($question_id);

                    if ($code_answr->createAnswer()) {
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
                    $single = false;

                    if($answer_count==1) {
                        $single = true;
                    }

                    for ($k = 1; $k <= $answer_count; $k++) {
                        array_push($answers, $_POST['answer-' . $k]);
                        if (isset($_POST['correct-' . $k])) {
                            array_push($correct, $_POST['correct-' . $k]);
                        } else {
                            array_push($correct, "0");
                        }
                    }

                    if (!(in_array("1", $correct)) && !($hasCorrect)) { ?>
                        <div class="invalid-feedback" style="display:block; font-size:16px">
                            Niste odabrali točan odgovor!
                        </div>
                        <?php
                    } else {
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

                        if (!in_array("0", $success)) {
                            if(!$single) {
                            ?>
                            <div class="valid-feedback" style="display:block; font-size:16px">
                                Odgovori uspješno dodani!
                            </div>
                        <?php
                            }else{
                                echo "<div class='valid-feedback' style='display:block; font-size:16px'>Odgovor uspješno dodan!</div>";
                            }
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
        // nadopunjavanje
        if ($type == 3) {
        ?>
            <form method="POST" action="">
                <input type="hidden" name="submitted" id="submitted">
                <div class="form-group">
                    <?php if ($type == 3) {  ?>
                        <label for="answer">Odgovor</label>
                        <textarea class="form-control" id="answer" name="answer" aria-describedby="answerHelp" placeholder="Upišite odgovor" <?php if (isset($_POST['answer'])) { ?> value="<?php echo $_POST['answer'] ?>" <?php } ?>></textarea>
                    <?php } else if ($type == 4) {  ?>
                        <label for="answer">Odgovor</label>
                        <textarea placeholder="Upišite odgovor" class="form-control" rows="9" id="answer" name="answer"><?php if (isset($_POST['answer'])) {
                                                                                                                            echo $_POST['answer'];
                                                                                                                        } ?></textarea>
                    <?php }  ?>
                </div>
                <button type="submit" class="btn btn-x" id="answr-submit">Dodaj odgovor</button>
                <button type="button" onclick="window.history.go(-1);" class="btn btn-y">Odustani</button>
            </form>
    </div>
<?php
        }
        // kodiranje
        if ($type == 4) {
?>
        <!--codemirror js-->
        <script src="../vendor/codemirror/lib/codemirror.js"></script>
        <!--codemirror css-->
        <link rel="stylesheet" href="../vendor/codemirror/lib/codemirror.css">
        <!--odabir jezika-->
        <script src="../vendor/codemirror/mode/<?php echo $_SESSION['selected_language_editor_mode'] ?>/<?php echo $_SESSION['selected_language_editor_mode'] ?>.js"></script>
        </script>
        <!--odabir teme-->
        <link rel="stylesheet" href="../vendor/codemirror/theme/nord.css">
        <!-- autoclose -->
        <script src="../vendor/codemirror/addon/edit/closebrackets.js"></script>
        <!-- placeholder -->
        <script src="../vendor/codemirror/addon/display/placeholder.js"></script>
    <form method="POST" action="">
        <input type="hidden" name="submitted" id="submitted">
        <div class="form-group">
            <label for="answer-code">Kod odgovora</label>
            <textarea placeholder="Upišite odgovor" class="form-control" rows="9" id="answer-code" name="answer-code"><?php if (isset($_POST['answer-code'])) {
                                                                                                                            echo $_POST['answer-code'];
                                                                                                                        } ?></textarea>
        <small class="form-text text-y answer-displayHelp">Kod točnog odgovora.</small>
        </div>
        <div class="form-group">
            <label for="answer-display">Rezultat odgovora</label>
            <textarea placeholder="Upišite prikaz" class="form-control" rows="9" id="answer-display" name="answer-display"><?php if (isset($_POST['answer-display'])) {
                                                                                                                                echo $_POST['answer-display'];
                                                                                                                            } ?></textarea>
        <small class="form-text text-y answer-displayHelp">Rezultat koji daje upisani kod kada se izvrši.</small>
        </div>
        <button type="submit" class="btn btn-x" id="answr-submit">Dodaj odgovor</button>
        <button type="button" onclick="window.history.go(-1);" class="btn btn-y">Odustani</button>
    </form>
</div>
<?php
        }
        //jedan točan ili više točnih
        if ($type == 1 || $type == 2) {

            if($answers_numrows<=8) {
?>
    <form method="POST" action="">
        <div class="form-group col-md-3">
            <label for="answr-count">Broj odgovora</label>
            <select class="form-select custom-select custom-select-sm" name="answr-count" id="answr-count">
                <option value="0" selected>Broj odgovora...</option>
                <?php
                for ($i = 1; $i <= 8-$answers_numrows; $i++) {  ?>
                    <option value="<?php echo $i ?>"><?php echo $i ?></option>
                <?php
                }
                ?>
            </select>
        </div>
        <input type="hidden" name="submitted" id="submitted">
        <?php
            for ($i = 1; $i <= 8-$answers_numrows; $i++) {
        ?>
            <div class="form-group col-md-6" id="answer-<?php echo $i ?>" style="display:none;">
                <label for="answer-<?php echo $i ?>">Odgovor #<?php echo $i ?></label>
                <input type="text" class="form-control" id="answer-<?php echo $i ?>" name="answer-<?php echo $i ?>" aria-describedby="answer<?php echo $i ?>Help" placeholder="Upišite odgovor" <?php if (isset($_POST['answer-<?php echo $i ?>'])) { ?> value="<?php echo $_POST['answer-<?php echo $i ?>'] ?>" <?php } ?>>
                <div class="custom-control custom-switch">
                    <input class="custom-control-input correct" type="checkbox" id="correct-<?php echo $i ?>" name="correct-<?php echo $i ?>" value="1" <?php if($hasCorrect) { echo "disabled"; } ?>>
                    <label class="custom-control-label" for="correct-<?php echo $i ?>">Točan</label>
                </div>
                <small class="form-text text-y questtypeHelp">Označite ako je odgovor točan.<?php if ($type == 1) {  ?> Samo jedan odgovor može biti označen kao točan.<?php } else if ($type == 2) { ?>Više odgovora može biti označeno kao točan.<?php } ?></small>
            </div>
        <?php
            }
        ?>
        <button type="submit" class="btn btn-x" id="answr-submit">Dodaj</button>
        <button type="button" onclick="window.history.go(-1);" class="btn btn-y">Odustani</button>
    </form>
<?php
        }else{  ?>
                    <div class="invalid-feedback" style="display:block; font-size:16px">
                        Pitanje ima maksimalan broj dopuštenih odgovora! Obrišite odgovor(e) da biste ponovo mogli dodati odgovor!
                    </div>
      <?php  }
    }
?>
<?php
include_once("footer.php");
?>
<script>
    var code_answer = CodeMirror.fromTextArea(document.getElementById("answer-code"), {
    lineNumbers: true,
    tabSize: 2,
    mode: '<?php echo $_SESSION['selected_language_editor_mode']; ?>',
    theme: "nord",
    autoCloseBrackets: true});

    var answer_display = CodeMirror.fromTextArea(document.getElementById("answer-display"), {
    lineNumbers: false,
    tabSize: 2,
    mode: '<?php echo $_SESSION['selected_language_editor_mode']; ?>',
    theme: "nord",
    autoCloseBrackets: true
});
</script>
<script src="js/new_answer.js"></script>