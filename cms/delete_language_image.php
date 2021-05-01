<?php
include_once("functions.php");
include_once("class/language.php");
$db = connect();
session_start();
$_SESSION['success'] = array();
$_SESSION['errors'] = array();
$_SESSION['show_modal'] = array('name'=>'');
$_SESSION['show_modal']['name']="langimgdelModal";
$_SESSION['status'] = 0;

if (isset($_GET['id'])) {

    $language_id = (int)$_GET['id'];
    $lang = new Language($db);

    if (!$stmt = $lang->getLanguageById($language_id)) {
        $errors = array('Dogodila se pogreška!');
        header("Location: languages.php");
    } else {
        $lang->set_id($language_id);
        $stmt = $lang->getImageById();

        if ($stmt) {
            $image_row = $stmt->fetch(PDO::FETCH_ASSOC);
            extract($image_row);
            $img = $image;
        } else {
            $errors = array('Greška kod dohvaćanja stare fotografije jezika!');
            header("Location: languages.php");
        }

        $img_path = "img/lang/" . $img;

        if (is_dir($img_path)) {
            $errors = array('Jezik nema postavljenu fotografiju!');
            header("Location: languages.php");
        } else {
            if (!unlink($img_path)) {
                $errors = array('Greška pri brisanju fotografije jezika!');
                header("Location: languages.php");
            }
        }

        if (!$lang->deleteLanguageImage()) {
            $errors = array('Greška pri brisanju fotografije jezika!');
            header("Location: languages.php");
        }
    }
} else {
    $errors = array('Dogodila se pogreška!');
}
        if (isset($errors)) {

            if (sizeof($errors) > 0) {

                foreach ($errors as $err) {
                    array_push($_SESSION['errors'], $err);
                    header("Location: languages.php");
                }
            }
        } else {
        
            array_push($_SESSION['success'], "Fotografija jezika uspješno obrisana!");
            $_SESSION['status'] = 1;
            header("Location: languages.php");

        }

    ?>