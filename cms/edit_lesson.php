<?php
include_once("../functions.php");
include_once("../class/language.php");
include_once("../class/lesson.php");
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

            $lesson_id = (int)$_POST['id'];
            $old_less = new Lesson($db);

            if (!$stmt = $old_less->getLessonById($lesson_id)) {
                encode_error();
            }

            if (isset($_POST['submitted'])) {
                $form_fields = array('less-name', 'less-desc');
                $form_names = array('Naziv lekcije', 'Opis lekcije');
                $ids=array($lesson_id,$_POST['less-lang']);
                $errors = validate($form_fields, $form_names, $db, $ids, "Lesson");
            }
        } else {
            encode_error();
        }

        if (isset($errors)) {

            if (sizeof($errors) > 0) {
                encode_error($errors);
            } else {
                $less_name = $_POST['less-name'];
                $less_desc = htmlspecialchars_decode($_POST['less-desc']);
                $less_precondition = $_POST['less-precondition'];

                if (!isset($_POST['less-lang']) || $_POST['less-lang'] == 0) {
                    encode_error();
                } else {
                    $lang_id = $_POST['less-lang'];
                }

                $less = new Lesson($db);

                $less->set_id($lesson_id);
                $less->set_name($less_name);
                $less->set_description($less_desc);
                $less->set_precondition($less_precondition);
                $less->set_language_id($lang_id);

                if ($less->editLesson()) {
                    unset($_SESSION['ct']);
                    unset($_SESSION['expireIn']);
                    echo json_encode(array('status' => 1, 'message' => 'Lekcija uspješno uređena!'));
                }
            }
        }
    } else {
        encode_error('Nije vam dopušteno izvršavanje akcije!');
    }
} else {
    encode_error('Nije vam dopušteno izvršavanje akcije!');
}
