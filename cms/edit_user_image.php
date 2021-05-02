<?php
include_once("functions.php");
include_once("class/user.php");
$db = connect();
session_start();

if (isset($_POST['user-id'])) {

    $user_id = (int)$_POST['user-id'];
    $user = new User($db);

    if (!$stmt = $user->getUserById($user_id)) {
        echo json_encode(array('status'=>0,'message'=>'Dogodila se pogreška!'));
    }

    if (isset($_POST['submitted'])) {
        $errors = image_upload("user-img");
    }
} else {
    echo json_encode(array('status'=>0,'message'=>'Dogodila se pogreška!'));
}


if (isset($errors)) {

    if (sizeof($errors) > 0) {
        echo json_encode(array('status'=>0,'message'=>$errors));
    } else {
        $user->set_id($user_id);

        if (!empty($_FILES["user-img"]["tmp_name"])) {

            $img_path = basename($_FILES["user-img"]["name"]);
            $temp = explode(".", $_FILES["user-img"]["name"]);
            $newfilename = round(microtime(true)) . '.' . end($temp);
            $target = "img/user/" . $newfilename;

            $user->set_image($newfilename);

            if (!move_uploaded_file($_FILES["user-img"]["tmp_name"], $target)) {
                echo json_encode(array('status'=>0,'message'=>'Greška pri dodavanju slike!'));
            } else {

                if ($user->editUserImage()) {
                    echo json_encode(array('status'=>1,'message'=>'Slika profila uspješno promijenjena!'));
                }
            }
        } else {
            echo json_encode(array('status'=>0,'message'=>'Molimo odaberite fotografiju!'));
        }
    }
}
?>