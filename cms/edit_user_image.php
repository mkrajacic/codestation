<?php
include_once("../functions.php");
include_once("../class/user.php");
$db = connect();
session_start();

$allowed = admin_or_user("user-id");

if (!$allowed) {
    encode_error('Nije vam dopušteno izvršavanje akcije!');
} else {
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
                }

                if (isset($_POST['submitted'])) {
                    $errors = image_upload("user-img");
                }
            } else {
                encode_error();
            }


            if (isset($errors)) {

                if (sizeof($errors) > 0) {
                    encode_error($errors);
                } else {
                    $user->set_id($user_id);

                    if (!empty($_FILES["user-img"]["tmp_name"])) {

                        $img_path = basename($_FILES["user-img"]["name"]);
                        $temp = explode(".", $_FILES["user-img"]["name"]);
                        $newfilename = round(microtime(true)) . '.' . end($temp);
                        $target = "img/user/" . $newfilename;

                        $user->set_image($newfilename);

                        if (!move_uploaded_file($_FILES["user-img"]["tmp_name"], $target)) {
                            encode_error('Greška pri dodavanju slike!');
                        } else {

                            if ($user->editUserImage()) {
                                unset($_SESSION['ct']);
                                unset($_SESSION['expireIn']);
                                echo json_encode(array('status' => 1, 'message' => 'Slika profila uspješno promijenjena!'));
                            }
                        }
                    } else {
                        encode_error('Molimo odaberite fotografiju!');
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
