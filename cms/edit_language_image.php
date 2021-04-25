<?php
include_once("functions.php");
include_once("class/language.php");
$db = connect();
session_start();
$_SESSION['success'] = array();
$_SESSION['errors'] = array();

if (isset($_POST['id'])) {

    $language_id = (int)$_POST['id'];
    $old_lang = new Language($db);

    if (!$stmt = $old_lang->getLanguageById($language_id)) {
        $errors = array('Dogodila se pogreška!');
        header("Location: languages.php?langimgfailure=1");
    }

    if (isset($_POST['submitted'])) {
        $errors = image_upload("lang-img");
    }
} else {
    $errors = array('Dogodila se pogreška!');
    header("Location: languages.php?langimgfailure=1");
}


if (isset($errors)) {

    if (sizeof($errors) > 0) {

        foreach ($errors as $err) {
            array_push($_SESSION['errors'], $err);
        }
        header("Location: languages.php?langimgfailure=1");
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
                array_push($_SESSION['errors'], "Greška pri dodavanju slike!");
                header("Location: languages.php?langimgfailure=1");
            } else {

                if ($lang->editLanguageImage()) {
                    array_push($_SESSION['success'], "Fotografija jezika uspješno promijenjena!");
                    header("Location: languages.php?langimgsuccess=1");
                }
            }
        } else {
            array_push($_SESSION['errors'], "Molimo odaberite fotografiju!");
            header("Location: languages.php?langimgfailure=1");
        }
    }
}
?>