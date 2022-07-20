<?php
include_once("../functions.php");
include_once("../class/question.php");
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
    $quest = new Question($db);

    if (!$stmt = $quest->getQuestionById($question_id)) {
        encode_error('Greška pri brisanju pitanja!');
    } else {
        $quest->set_id($question_id);

        if (!$quest->deleteQuestion()) {
            encode_error('Greška pri brisanju pitanja!');
        }else{
            unset($_SESSION['ct']);
            unset($_SESSION['expireIn']);
            echo json_encode(array('status' => 1, 'message' => 'Pitanje uspješno obrisano!'));
        }
    }
} else {
    encode_error('Greška pri brisanju pitanja!');
}}else{
    encode_error('Nije vam dopušteno izvršavanje akcije!');
}
}else{
encode_error('Nije vam dopušteno izvršavanje akcije!');
}

?>