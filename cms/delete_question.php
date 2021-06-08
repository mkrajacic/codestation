<?php
include_once("functions.php");
include_once("class/question.php");
$db = connect();

if (isset($_POST['id'])) {

    $question_id = (int)$_POST['id'];
    $quest = new Question($db);

    if (!$stmt = $quest->getQuestionById($question_id)) {
        echo json_encode(array('status' => 0, 'message' => 'Greška pri brisanju pitanja!'));
    } else {
        $quest->set_id($question_id);

        if (!$quest->deleteQuestion()) {
            echo json_encode(array('status' => 0, 'message' => 'Greška pri brisanju pitanja!'));
        }
    }
} else {
    echo json_encode(array('status' => 0, 'message' => 'Greška pri brisanju pitanja!'));
}

    if (isset($errors)) {

        if (sizeof($errors) > 0) {
            echo json_encode(array('status' => 0, 'message' => $errors));
        }
    } else {
        echo json_encode(array('status' => 1, 'message' => 'Pitanje uspješno obrisano!'));
    }
?>