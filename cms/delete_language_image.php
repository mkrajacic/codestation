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
    $lang = new Language($db);

    if (!$stmt = $lang->getLanguageById($language_id)) {
encode_error();
    } else {
        $lang->set_id($language_id);
        $stmt = $lang->getImageById();

        if ($stmt) {
            $image_row = $stmt->fetch(PDO::FETCH_ASSOC);
            extract($image_row);
            $img = $image;
        } else {
            encode_error('Greška kod dohvaćanja stare fotografije jezika!');
        }

        $img_path = "img/lang/" . $img;

        if (is_dir($img_path)) {
            encode_error('Jezik nema postavljenu fotografiju!');
        } else {
            if (!unlink($img_path)) {
                encode_error('Greška pri brisanju fotografije jezika!');
            }
        }

        if (!$lang->deleteLanguageImage()) {
            encode_error('Greška pri brisanju fotografije jezika!');
        }else{
            unset($_SESSION['ct']);
            unset($_SESSION['expireIn']);
            echo json_encode(array('status'=>1,'message'=>'Fotografija jezika uspješno obrisana!'));
        }
    }
} else {
    encode_error();
}}else{
            encode_error('Nije vam dopušteno izvršavanje akcije!');
}
}else{
encode_error('Nije vam dopušteno izvršavanje akcije!');
}

    ?>