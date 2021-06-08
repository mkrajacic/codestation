<?php
include_once("functions.php");
include_once("class/language.php");
include_once("class/lesson.php");
$db = connect();

if (isset($_POST['id'])) {

    $lesson_id = (int)$_POST['id'];
    $old_less = new Lesson($db);

    if (!$stmt = $old_less->getLessonById($lesson_id)) {
        echo json_encode(array('status' => 0, 'message' => 'Dogodila se pogreška1!'));
    }

    if (isset($_POST['submitted'])) {
        $form_fields = array('less-name', 'less-desc');
        $form_names = array('Naziv lekcije', 'Opis lekcije');

        $errors = validate($form_fields, $form_names, $db, $lesson_id,"Lesson");
    }
} else {
    echo json_encode(array('status' => 0, 'message' => 'Dogodila se pogreška2!'));
}

if (isset($errors)) {

    if (sizeof($errors) > 0) {
        echo json_encode(array('status' => 0, 'message' => $errors));
    } else {
        $less_name = $_POST['less-name'];
        $less_desc = $_POST['less-desc'];

        if(!isset($_POST['less-lang']) || $_POST['less-lang']==0) {
            echo json_encode(array('status' => 0, 'message' => 'Dogodila se pogreška3!'));
        }else{
            $lang_id = $_POST['less-lang'];
        }

        $less = new Lesson($db);

        $less->set_id($lesson_id);
        $less->set_name($less_name);
        $less->set_description($less_desc);
        $less->set_language_id($lang_id);

        if ($less->editLesson()) {
            echo json_encode(array('status' => 1, 'message' => 'Lekcija uspješno uređena!'));
        }
    }
}
