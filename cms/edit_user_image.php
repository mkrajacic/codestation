<?php
include_once("functions.php");
include_once("class/user.php");
$db = connect();
session_start();
$_SESSION['success'] = array();
$_SESSION['errors'] = array();
$_SESSION['show_modal'] = array('name'=>'');
$_SESSION['show_modal']['name']="userimgModal";
$_SESSION['status'] = 0;

$page = $_SERVER['HTTP_REFERER'];

if (isset($_POST['id'])) {

    $user_id = (int)$_POST['id'];
    $user = new User($db);

    if (!$stmt = $user->getUserById($user_id)) {
        $errors = array('Dogodila se pogreška!');
        header("Location: " . $page);
        exit;
    }

    if (isset($_POST['submitted'])) {
        $errors = image_upload("user-img");
    }
} else {
    $errors = array('Dogodila se pogreška!');
    header("Location: " . $page);
    exit;
}


if (isset($errors)) {

    if (sizeof($errors) > 0) {

        foreach ($errors as $err) {
            array_push($_SESSION['errors'], $err);
        }
        header("Location: " . $page);
        exit;
    } else {
        $user->set_id($user_id);

        if (!empty($_FILES["user-img"]["tmp_name"])) {

            $img_path = basename($_FILES["user-img"]["name"]);
            $temp = explode(".", $_FILES["user-img"]["name"]);
            $newfilename = round(microtime(true)) . '.' . end($temp);
            $target = "img/user/" . $newfilename;

            $user->set_image($newfilename);

            if (!move_uploaded_file($_FILES["user-img"]["tmp_name"], $target)) {
                array_push($_SESSION['errors'], "Greška pri dodavanju slike!");
                header("Location: " . $page);
                exit;
            } else {

                if ($user->editUserImage()) {
                    array_push($_SESSION['success'], "Slika profila uspješno promijenjena!");
                    header("Location: " . $page);
                    $_SESSION['status'] = 1;
                    exit;
                }
            }
        } else {
            array_push($_SESSION['errors'], "Molimo odaberite fotografiju!");
            header("Location: " . $page);
            exit;
        }
    }
}
?>