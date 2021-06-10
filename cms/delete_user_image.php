<?php
include_once("../functions.php");
include_once("../class/user.php");
$db = connect();
session_start();

if (isset($_POST['user-id'])) {

    $user_id = (int)$_POST['user-id'];
    $user = new User($db);

    if (!$stmt = $user->getUserById($user_id)) {
        echo json_encode(array('status'=>0,'message'=>'Dogodila se pogreška!'));
    } else {
        $user->set_id($user_id);
        $stmt = $user->getImageById();

        if ($stmt) {
            $image_row = $stmt->fetch(PDO::FETCH_ASSOC);
            extract($image_row);
            $img = $image;
        } else {
            echo json_encode(array('status'=>0,'message'=>'Greška kod dohvaćanja stare slike profila!'));
        }

        $img_path = "img/user/" . $img;

        if (is_dir($img_path)) {
            echo json_encode(array('status'=>0,'message'=>'Nemate postavljenu sliku profila!'));
        } else {
            if (!unlink($img_path)) {
                echo json_encode(array('status'=>0,'message'=>'Greška pri brisanju slike profila!'));
            }
        }

        if (!$user->deleteUserImage()) {
            echo json_encode(array('status'=>0,'message'=>'Greška pri brisanju slike profila!'));
        }
    }
} else {
    echo json_encode(array('status'=>0,'message'=>'Dogodila se pogreška!'));
}
        if (isset($errors)) {

            if (sizeof($errors) > 0) {
                echo json_encode(array('status'=>0,'message'=>$errors));
            }
        } else {
        
            echo json_encode(array('status'=>1,'message'=>'Slika profila uspješno obrisana!'));

        }
?>