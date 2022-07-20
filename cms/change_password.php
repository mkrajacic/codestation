<?php
include_once("../functions.php");
include_once("../class/user.php");
$db = connect();
session_start();

$allowed = admin_or_user("user-password-id");

if(!$allowed) {
        encode_error('Nije vam dopušteno izvršavanje akcije!');
}else{
    if (!empty($_POST['ct'])) {

        if (hash_equals($_SESSION['ct'], $_POST['ct'])) {
    
            if (checkTokenTime() == 0) {
                encode_error('Token je istekao, molimo osvježite stranicu!');
            }

            if (isset($_POST['user-password-id'])) {
                $user_id = (int)$_POST['user-password-id'];
                $user = new User($db);
                $user->set_id($user_id);
                if (!$stmt = $user->getUserById($user_id)) {
                encode_error();
                } else {
    
                    $user_row = $stmt->fetch(PDO::FETCH_ASSOC);
                    $user->set_password($_POST['usr-password-old']);
                    $user->set_username($user_row['username']);
    
                    if (!($user->isCorrectPassword())) {
                        encode_error('Krivo upisana stara lozinka!');
                    } else {
    
                        if (isset($_POST['submitted'])) {
                            $form_fields = array('usr-password-old', 'usr-password', 'usr-password2');
                            $form_names = array('Stara lozinka', 'Nova lozinka', 'Ponovljena lozinka');
                            $errors = validate($form_fields, $form_names, $db, $user_id, "User");
                        }
                    }
                }
            } else {
                encode_error('Greška pri promjeni lozinke!');
            }
            if (isset($errors)) {
                if (sizeof($errors) > 0) {
                    encode_error($errors);
                } else {
    
                    if (isset($user)) {
    
                        if (isset($_POST['usr-password'])) {
    
                            if (isset($_POST['usr-password2'])) {
                                $password = $_POST['usr-password'];
                                $password2 = $_POST['usr-password2'];
    
                                if ($password == $password2) {
    
                                    $password_encrypted = password_hash($password, PASSWORD_BCRYPT);
                                    $user->set_password($password_encrypted);
    
                                    if (!$user->changePassword()) {
                                        encode_error('Greška pri promjeni lozinke!');
                                    } else {
                                        unset($_SESSION['ct']);
                                        unset($_SESSION['expireIn']);
                                        echo json_encode(array('status' => 1, 'message' => 'Lozinka uspješno promijenjena!'));
                                    }
                                } else {
                                    encode_error('Upisane nove lozinke se ne podudaraju!');
                                }
                            }
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