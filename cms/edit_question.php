<?php
include_once("../functions.php");
include_once("../class/question.php");
include_once("../class/answer.php");
$db = connect();
session_start();

$auth = isAuthorized();
if (($auth == 0) || ($auth == 3)) {
            encode_error('Nije vam dopušteno izvršavanje akcije!');
}

if (!empty($_POST['ct'])) {

    if (hash_equals($_SESSION['ct'], $_POST['ct'])) {

        if (checkTokenTime() == 0) {
            encode_error('Token je istekao, molimo osvježite stranicu!');
        }

if (isset($_POST['id'])) {

    $question_id = (int)$_POST['id'];
    $old_quest = new Question($db);
    $old_type = 0;

    if (!$stmt = $old_quest->getQuestionById($question_id)) {
        encode_error();
    }else{
        $old_quest_row = $stmt->fetch(PDO::FETCH_ASSOC);
        $old_type = $old_quest_row['question_type'];
    }

    if (isset($_POST['submitted'])) {
        $form_fields = array('question', 'quest-type','quest-less');
        $form_names = array('Pitanje', 'Vrsta pitanja','Lekcija');
        $errors = validate($form_fields, $form_names, $db, null,"Question");
    }
} else {
    encode_error();
}

if (isset($errors)) {

    if (sizeof($errors) > 0) {
        encode_error($errors);
    } else {
        $question = $_POST['question'];
        $quest_type = $_POST['quest-type'];
        $quest_less = $_POST['quest-less'];

        $quest = new Question($db);

        $quest->set_id($question_id);
        $quest->set_question($question);
        $quest->set_question_type($quest_type);
        $quest->set_lesson_id($quest_less);

        if ($quest->editQuestion()) {

            if(!($quest_type==$old_type)) {
                $answers = new Answer($db);
                $answers->set_question_id($question_id);

                if($answers->deleteAnswersByQuestionId()) {
                    unset($_SESSION['ct']);
                    unset($_SESSION['expireIn']);
                    echo json_encode(array('status' => 1, 'message' => 'Pitanje uspješno uređeno!'));
                }else{
                    encode_error();
                }
            }else{
                unset($_SESSION['ct']);
                unset($_SESSION['expireIn']);
                echo json_encode(array('status' => 1, 'message' => 'Pitanje uspješno uređeno!'));
            }

        }
    }
}}else{
            encode_error('Nije vam dopušteno izvršavanje akcije!');
}
}else{
encode_error('Nije vam dopušteno izvršavanje akcije!');
}
?>
