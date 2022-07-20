<?php
include_once("../functions.php");
include_once("../class/user.php");
$db = connect();
session_start();

$allowed = admin_or_user("id");

if (!$allowed) {
    encode_error('Nije vam dopušteno izvršavanje akcije!');
}else{
    if (!empty($_POST['ct'])) {

        if (hash_equals($_SESSION['ct'], $_POST['ct'])) {
    
            if (checkTokenTime() == 0) {
                encode_error('Token je istekao, molimo osvježite stranicu!');
            }
    
            if (isset($_POST['id'])) {
    
                $user_id = (int)$_POST['id'];
                $usr = new User($db);
    
                if (!$stmt = $usr->getUserById($user_id)) {
                    encode_error('Greška pri dohvaćanju korisnika!');
                } else {
    
                    if (isset($_POST['role'])) {
                        $role = $_POST['role'];
                    }
    
                    $usr->set_id($user_id);
                    $usr->set_role_code($role);
    
                    if (!$usr->changeRole()) {
                        encode_error('Greška pri promjeni korisničke ovlasti!');
                    } else {
                        unset($_SESSION['ct']);
                        unset($_SESSION['expireIn']);
                        echo json_encode(array('status' => 1, 'message' => 'Korisnička ovlast uspješno promijenjena!'));
                    }
                }
            } else {
                encode_error('Greška pri promjeni korisničke ovlasti!');
            }
        } else {
            encode_error('Nije vam dopušteno izvršavanje akcije!');
        }
    } else {
        encode_error('Nije vam dopušteno izvršavanje akcije!');
    }
}