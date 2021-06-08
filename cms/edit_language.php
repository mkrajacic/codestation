<?php
include_once("functions.php");
include_once("class/language.php");
$db = connect();

if (isset($_POST['id'])) {

    $language_id = (int)$_POST['id'];
    $old_lang = new Language($db);

    if (!$stmt = $old_lang->getLanguageById($language_id)) {
        echo json_encode(array('status' => 0, 'message' => 'Dogodila se pogreška!'));
    }

    if (isset($_POST['submitted'])) {
        $form_fields = array('lang-name', 'lang-desc');
        $form_names = array('Naziv jezika', 'Opis jezika');

        $errors = validate($form_fields, $form_names, $db, $language_id,"Language");
    }
} else {
    echo json_encode(array('status' => 0, 'message' => 'Dogodila se pogreška!'));
}

if (isset($errors)) {

    if (sizeof($errors) > 0) {
        echo json_encode(array('status' => 0, 'message' => $errors));
    } else {
        $lang_name = $_POST['lang-name'];
        $lang_desc = $_POST['lang-desc'];

        $lang = new Language($db);

        $lang->set_id($language_id);
        $lang->set_name($lang_name);
        $lang->set_description($lang_desc);

        if ($lang->editLanguage()) {
            echo json_encode(array('status' => 1, 'message' => 'Jezik uspješno uređen!'));
        }
    }
}
?>
