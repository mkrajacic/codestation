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
        $form_fields = array('lang-name', 'lang-desc','lang-version','lang-c-mode','lang-e-mode');
        $form_names = array('Naziv jezika', 'Opis jezika','Verzija jezika','Mod kompajlera','Mod editora');

        $errors = validate($form_fields, $form_names, $db, $language_id,"Language");
    }
} else {
    encode_error();
}

if (isset($errors)) {

    if (sizeof($errors) > 0) {
        encode_error($errors);
    } else {
        $lang_name = $_POST['lang-name'];
        $lang_desc = htmlspecialchars_decode($_POST['lang-desc']);
        $lang_version = $_POST['lang-version'];
        $lang_c_mode = $_POST['lang-c-mode'];
        $lang_e_mode = $_POST['lang-e-mode'];

        $lang = new Language($db);

        $lang->set_id($language_id);
        $lang->set_name($lang_name);
        $lang->set_description($lang_desc);
        $lang->set_language_version($lang_version);
        $lang->set_compiler_mode($lang_c_mode);
        $lang->set_editor_mode($lang_e_mode);

        if ($lang->editLanguage()) {
            unset($_SESSION['ct']);
            unset($_SESSION['expireIn']);
            echo json_encode(array('status' => 1, 'message' => 'Jezik uspješno uređen!'));
        }
    }
}}else{
    encode_error('Nije vam dopušteno izvršavanje akcije!');
}
}else{
encode_error('Nije vam dopušteno izvršavanje akcije!');
}
?>
