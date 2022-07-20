<?php
include_once("../functions.php");
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
    $lang = new Lesson($db);

    if (!$stmt = $lang->getLessonById($lesson_id)) {
        encode_error('Greška pri brisanju lekcije!');
    } else {
        $lang->set_id($lesson_id);

        if (!$lang->deleteLesson()) {
            encode_error('Greška pri brisanju lekcije!');
        }else{
            unset($_SESSION['ct']);
            unset($_SESSION['expireIn']);
            echo json_encode(array('status' => 1, 'message' => 'Lekcija uspješno obrisana!'));
        }
    }
} else {
    encode_error('Greška pri brisanju lekcije!');
}}else{
            encode_error('Nije vam dopušteno izvršavanje akcije!');
}
}else{
encode_error('Nije vam dopušteno izvršavanje akcije!');
}

?>