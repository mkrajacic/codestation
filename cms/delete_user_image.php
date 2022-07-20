<?php
include_once("../functions.php");
include_once("../class/user.php");
$db = connect();
session_start();

$allowed = admin_or_user("user-id");

if (!$allowed) {
    encode_error('Nije vam dopušteno izvršavanje akcije!');
}else{
    if (!empty($_POST['ct'])) {

        if (hash_equals($_SESSION['ct'], $_POST['ct'])) {
    
            if (checkTokenTime() == 0) {
                encode_error('Token je istekao, molimo osvježite stranicu!');
            }
    
            if (isset($_POST['user-id'])) {
    
                $user_id = (int)$_POST['user-id'];
                $user = new User($db);
    
                if (!$stmt = $user->getUserById($user_id)) {
                    encode_error();
                } else {
                    $user->set_id($user_id);
                    $stmt = $user->getImageById();
    
                    if ($stmt) {
                        $image_row = $stmt->fetch(PDO::FETCH_ASSOC);
                        extract($image_row);
                        $img = $image;
                    } else {
                        encode_error('Greška kod dohvaćanja stare slike profila!');
                    }
    
                    $img_path = "img/user/" . $img;
    
                    if (is_dir($img_path)) {
                        encode_error('Nemate postavljenu sliku profila!');
                    } else {
                        if (!unlink($img_path)) {
                            encode_error('Greška pri brisanju slike profila!');
                        }
                    }
    
                    if (!$user->deleteUserImage()) {
                        encode_error('Greška pri brisanju slike profila!');
                    } else {
                        unset($_SESSION['ct']);
                        unset($_SESSION['expireIn']);
                        echo json_encode(array('status' => 1, 'message' => 'Slika profila uspješno obrisana!'));
                    }
                }
            } else {
                encode_error();
            }
        } else {
            encode_error('Nije vam dopušteno izvršavanje akcije!');
        }
    } else {
        encode_error('Nije vam dopušteno izvršavanje akcije!');
    }
}