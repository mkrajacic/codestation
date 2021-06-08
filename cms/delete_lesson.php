<?php
include_once("functions.php");
include_once("class/lesson.php");
$db = connect();

if (isset($_POST['id'])) {

    $lesson_id = (int)$_POST['id'];
    $lang = new Lesson($db);

    if (!$stmt = $lang->getLessonById($lesson_id)) {
        echo json_encode(array('status' => 0, 'message' => 'Greška pri brisanju lekcije!'));
    } else {
        $lang->set_id($lesson_id);

        if (!$lang->deleteLesson()) {
            echo json_encode(array('status' => 0, 'message' => 'Greška pri brisanju lekcije!'));
        }
    }
} else {
    echo json_encode(array('status' => 0, 'message' => 'Greška pri brisanju lekcije!'));
}

    if (isset($errors)) {

        if (sizeof($errors) > 0) {
            echo json_encode(array('status' => 0, 'message' => $errors));
        }
    } else {
        echo json_encode(array('status' => 1, 'message' => 'Lekcija uspješno obrisana!'));
    }
?>