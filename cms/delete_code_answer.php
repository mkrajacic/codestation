<?php
include_once("../functions.php");
include_once("../class/coding_answer.php");
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

            $answer_id = (int)$_POST['id'];
            $answr = new CodingAnswer($db);

            if (!$stmt = $answr->getAnswerById($answer_id)) {
                encode_error('Greška pri brisanju odgovora!');   
            } else {
                $answr->set_id($answer_id);

                if (!$answr->deleteAnswer()) {
                    encode_error('Greška pri brisanju odgovora!');
                } else {
                    unset($_SESSION['ct']);
                    unset($_SESSION['expireIn']);
                    echo json_encode(array('status' => 1, 'message' => 'Odgovor uspješno obrisan!'));
                }
            }
        } else {
            encode_error('Greška pri brisanju odgovora!');
        }}else{
            encode_error('Nije vam dopušteno izvršavanje akcije!');
        }
    }else{
        encode_error('Nije vam dopušteno izvršavanje akcije!');
    }
?>