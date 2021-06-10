<?php
include_once("../functions.php");
include_once("../class/language.php");
$db = connect();

if (isset($_POST['id'])) {

    $language_id = (int)$_POST['id'];
    $lang = new Language($db);

    if (!$stmt = $lang->getLanguageById($language_id)) {
        echo json_encode(array('status' => 0, 'message' => 'Greška pri brisanju programskog jezika!'));
    } else {
        $lang->set_id($language_id);

        if (!$lang->deleteLanguage()) {
            echo json_encode(array('status' => 0, 'message' => 'Greška pri brisanju programskog jezika!'));
        }
    }
} else {
    echo json_encode(array('status' => 0, 'message' => 'Greška pri brisanju programskog jezika!'));
}

    if (isset($errors)) {

        if (sizeof($errors) > 0) {
            echo json_encode(array('status' => 0, 'message' => $errors));
        }
    } else {
        echo json_encode(array('status' => 1, 'message' => 'Programski jezik uspješno obrisan!'));
    }
?>