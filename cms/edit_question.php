<?php
include_once("functions.php");
include_once("class/question.php");
$db = connect();

if (isset($_POST['id'])) {

    $question_id = (int)$_POST['id'];
    $old_quest = new Question($db);

    if (!$stmt = $old_quest->getquestionById($question_id)) {
        echo json_encode(array('status' => 0, 'message' => 'Dogodila se pogreška!'));
    }

    if (isset($_POST['submitted'])) {
        $form_fields = array('question', 'quest-type','quest-less');
        $form_names = array('Pitanje', 'Vrsta pitanja','Lekcija');

        $errors = validate($form_fields, $form_names, $db, $question_id,"Question");
    }
} else {
    echo json_encode(array('status' => 0, 'message' => 'Dogodila se pogreška!'));
}

if (isset($errors)) {

    if (sizeof($errors) > 0) {
        echo json_encode(array('status' => 0, 'message' => $errors));
    } else {
        $question = $_POST['question'];
        $quest_type = $_POST['quest-type'];
        $quest_less = $_POST['quest-less'];

        $quest = new Question($db);

        $quest->set_id($question_id);
        $quest->set_question($question);
        $quest->set_question_type($quest_type);
        $quest->set_lesson_id($quest_less);

        if ($quest->editquestion()) {
            echo json_encode(array('status' => 1, 'message' => 'Pitanje uspješno uređeno!'));
        }
    }
}
?>
