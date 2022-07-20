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

            $answer_id = $_POST['id'];

            $old_answr = new CodingAnswer($db);

            if (!$stmt = $old_answr->getAnswerById($answer_id)) {
                encode_error();
            }

            if (isset($_POST['submitted'])) {
                $form_fields = array('answer-code', 'answer-display');
                $form_names = array('Kod odgovora', 'Prikaz odgovora');

                $errors = validate($form_fields, $form_names, $db, $answer_id, "Answer");
            }
        } else {
            encode_error();
        }

        if (isset($errors)) {

            if (sizeof($errors) > 0) {
                encode_error($errors);
            } else {
                $code = $_POST['answer-code'];
                $display = $_POST['answer-display'];

                $canswr = new CodingAnswer($db);

                $canswr->set_id($answer_id);
                $canswr->set_code($code);
                $canswr->set_display($display);

                if ($canswr->editAnswer()) {
                    unset($_SESSION['ct']);
                    unset($_SESSION['expireIn']);
                    echo json_encode(array('status' => 1, 'message' => 'Odgovor uspješno uređen!'));
                }
            }
        }}else{
            encode_error('Nije vam dopušteno izvršavanje akcije!');
        }
    }else{
        encode_error('Nije vam dopušteno izvršavanje akcije!');
    }