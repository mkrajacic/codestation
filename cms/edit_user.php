<?php
include_once("../functions.php");
include_once("../class/user.php");
$db = connect();
session_start();

$allowed = admin_or_user("user-name-id");

if (!$allowed) {
    encode_error('Nije vam dopušteno izvršavanje akcije!');
}else{
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
                            $change_password = 0;
                            if (!(empty($_POST['usr-password'])) && !(empty($_POST['usr-password2']))) {
                                $change_password = 1;
                            }
        
                            if ($change_password == 1) {
                                $form_fields = array('usr-username', 'usr-password', 'usr-password2');
                                $form_names = array('Korisničko ime', 'Nova lozinka', 'Ponovljena lozinka');
                            } else {
                                $form_fields = array('usr-username');
                                $form_names = array('Korisničko ime');
                            }
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
        
                        $username_success = 0;
        
                        if (isset($_POST['usr-username'])) {
                            $username = $_POST['usr-username'];
                            $user->set_id($user_id);
                            $user->set_username($username);
                        }
        
                        if (isset($user)) {
                            if (!$user->editUsername()) {
                                encode_error('Greška pri promjeni korisničkog imena!');
                            } else {
                                $username_success = 1;
        
                                if($change_password == 0) {
                                    unset($_SESSION['ct']);
                                    unset($_SESSION['expireIn']);
                                    echo json_encode(array('status' => 1, 'message' => 'Korisničko ime uspješno promijenjeno!'));
                                }
                            }
        
                            if ($change_password == 1) {
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
                                        echo json_encode(array('status' => 1, 'message' => 'Korisnički podaci uspješno promijenjeni!'));
                                    }
                                }else{
                                    encode_error('Upisane nove lozinke ne podudaraju se!');
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