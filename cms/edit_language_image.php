<?php
include_once("../functions.php");
include_once("../class/language.php");
$db = connect();
session_start();

if (isset($_POST['id'])) {

    $language_id = (int)$_POST['id'];
    $old_lang = new Language($db);

    if (!$stmt = $old_lang->getLanguageById($language_id)) {
        echo json_encode(array('status'=>0,'message'=>'Dogodila se pogreška!'));
    }

    if (isset($_POST['submitted'])) {
        $errors = image_upload("lang-img");
    }
} else {
    echo json_encode(array('status'=>0,'message'=>'Dogodila se pogreška!'));
}


if (isset($errors)) {

    if (sizeof($errors) > 0) {
        echo json_encode(array('status'=>0,'message'=>$errors));
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
                echo json_encode(array('status'=>0,'message'=>'Greška pri dodavanju slike!'));
            } else {

                if ($lang->editLanguageImage()) {
                    echo json_encode(array('status'=>1,'message'=>'Fotografija jezika uspješno promijenjena!'));
                }
            }
        } else {
            echo json_encode(array('status'=>0,'message'=>'Molimo odaberite fotografiju!'));
        }
    }
}
?>