<?php
include_once("../functions.php");
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

            $answer_id = (int)$_POST['id'];
            $old_answr = new Answer($db);

            if (!$stmt = $old_answr->getAnswerById($answer_id)) {
                encode_error();
            }

            if (isset($_POST['submitted'])) {
                $form_fields = array('answer');
                $form_names = array('Odgovor');

                $errors = validate($form_fields, $form_names, $db, $answer_id, "Answer");
            }
        } else {
            encode_error();
        }

        if (isset($errors)) {

            if (sizeof($errors) > 0) {
                encode_error($errors);
            } else {
                $answer = $_POST['answer'];
                $correct = $_POST['correct'];

                if (isset($_POST['hasCorrect'])) {
                    if (isset($_POST['qtype'])) {
                        $hasCorrect = $_POST['hasCorrect'];
                        $qtype = $_POST['qtype'];

                        if ($correct) {
                            if (($qtype == 1) && ($hasCorrect == 1)) {
                                encode_error('Samo jedan odgovor može biti označen kao točan!');
                            }
                        }
                    }
                }

                $answr = new Answer($db);

                $answr->set_id($answer_id);
                $answr->set_answer($answer);
                $answr->set_correct($correct);

                if ($answr->editAnswer()) {
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
?>