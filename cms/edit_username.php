<?php
include_once("../functions.php");
include_once("../class/user.php");
$db = connect();
session_start();

$allowed = admin_or_user("user-name-id");

if (!$allowed) {
    encode_error('Nije vam dopušteno izvršavanje akcije!');
} else {
    if (!empty($_POST['ct'])) {

        if (hash_equals($_SESSION['ct'], $_POST['ct'])) {

            if (checkTokenTime() == 0) {
                encode_error('Token je istekao, molimo osvježite stranicu!');
            }

            if (isset($_POST['user-name-id'])) {
                $user_id = (int)$_POST['user-name-id'];
                $user = new User($db);
                if (!$stmt = $user->getUserById($user_id)) {
                    encode_error();
                } else {
                    if (isset($_POST['submitted'])) {
                        $form_fields = array('usr-username');
                        $form_names = array('Korisničko ime');
                        $errors = validate($form_fields, $form_names, $db, $user_id, "User");
                    }
                }
            } else {
                encode_error('Greška pri promjeni korisničkog imena!');
            }
            if (isset($errors)) {
                if (sizeof($errors) > 0) {
                    encode_error($errors);
                } else {
                    if (isset($_POST['usr-username'])) {
                        $username = $_POST['usr-username'];
                        $user->set_id($user_id);
                        $user->set_username($username);
                    }

                    if (isset($user)) {
                        if (!$user->editUsername()) {
                            encode_error('Greška pri promjeni korisničkog imena!');
                        } else {
                            unset($_SESSION['ct']);
                            unset($_SESSION['expireIn']);
                            echo json_encode(array('status' => 1, 'message' => 'Korisničko ime uspješno promijenjeno!'));
                        }
                    }
                }
            }
        } else {
            encode_error('Nije vam dopušteno izvršavanje akcije!');
        }
    } else {
        encode_error('Nije vam dopušteno izvršavanje akcije!');
    }
}
