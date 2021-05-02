<?php
include_once("functions.php");
include_once("class/language.php");
$db = connect();
session_start();

if (isset($_POST['id'])) {

    $language_id = (int)$_POST['id'];
    $lang = new Language($db);

    if (!$stmt = $lang->getLanguageById($language_id)) {
        echo json_encode(array('status'=>0,'message'=>'Dogodila se pogreška!'));
    } else {
        $lang->set_id($language_id);
        $stmt = $lang->getImageById();

        if ($stmt) {
            $image_row = $stmt->fetch(PDO::FETCH_ASSOC);
            extract($image_row);
            $img = $image;
        } else {
            echo json_encode(array('status'=>0,'message'=>'Greška kod dohvaćanja stare fotografije jezika!'));
        }

        $img_path = "img/lang/" . $img;

        if (is_dir($img_path)) {
            echo json_encode(array('status'=>0,'message'=>'Jezik nema postavljenu fotografiju!'));
        } else {
            if (!unlink($img_path)) {
                echo json_encode(array('status'=>0,'message'=>'Greška pri brisanju fotografije jezika!'));
            }
        }

        if (!$lang->deleteLanguageImage()) {
            echo json_encode(array('status'=>0,'message'=>'Greška pri brisanju fotografije jezika!'));
        }
    }
} else {
    echo json_encode(array('status'=>0,'message'=>'Dogodila se pogreška!'));
}
        if (isset($errors)) {

            if (sizeof($errors) > 0) {
                echo json_encode(array('status'=>0,'message'=>$errors));
            }
        } else {
        
            echo json_encode(array('status'=>1,'message'=>'Fotografija jezika uspješno obrisana!'));

        }

    ?>