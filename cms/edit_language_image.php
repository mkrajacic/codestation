<?php
include_once("../functions.php");
include_once("../class/language.php");
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

            $language_id = (int)$_POST['id'];
            $old_lang = new Language($db);

            if (!$stmt = $old_lang->getLanguageById($language_id)) {
                encode_error();
            }

            if (isset($_POST['submitted'])) {
                $errors = image_upload("lang-img");
            }
        } else {
            encode_error();
        }


        if (isset($errors)) {

            if (sizeof($errors) > 0) {
                encode_error($errors);
            } else {
                $lang = new Language($db);

                $lang->set_id($language_id);

                if (!empty($_FILES["lang-img"]["tmp_name"])) {

                    $img_path = basename($_FILES["lang-img"]["name"]);
                    $temp = explode(".", $_FILES["lang-img"]["name"]);
                    $newfilename = round(microtime(true)) . '.' . end($temp);
                    $target = "img/lang/" . $newfilename;

                    $lang->set_image($newfilename);

                    if (!move_uploaded_file($_FILES["lang-img"]["tmp_name"], $target)) {
                        encode_error('Greška pri dodavanju slike!');
                    } else {

                        if ($lang->editLanguageImage()) {
                            unset($_SESSION['ct']);
                            unset($_SESSION['expireIn']);
                            echo json_encode(array('status' => 1, 'message' => 'Fotografija jezika uspješno promijenjena!'));
                        }
                    }
                } else {
                    encode_error('Molimo odaberite fotografiju!');
                }
            }
        }
    }else{
        encode_error('Nije vam dopušteno izvršavanje akcije!');
    }
}else{
            encode_error('Nije vam dopušteno izvršavanje akcije!');
}
