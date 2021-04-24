<?php
$title = "Promijeni fotografiju jezika";
include_once("header.php");
include_once("class/language.php");
$db = connect();

if (isset($_GET['id'])) {

    $language_id = (int)$_GET['id'];
    $old_lang = new Language($db);

    if (!$stmt = $old_lang->getLanguageById($language_id)) {
        $errors = array('Dogodila se pogreška!');
    }

    if (isset($_POST['submitted'])) {
        $errors = image_upload("lang-img");
    }
} else {
    $errors = array('Dogodila se pogreška!');
}

$menu_items['main'] = array('Jezici', 'Korisnici', 'Ovlasti');
$menu_links['main'] = array('languages.php', 'users.php', 'roles.php');
$menu_items['sub'] = array('Novi jezik');
$menu_links['sub'] = array('new_language.php');
sidemenu($menu_items, $menu_links, "Jezici");
?>

<div id="page-content-wrapper">
    <div class="container-fluid">
        <h1 class="mt-4">Promijeni fotografiju jezika</h1>
        <form method="POST" action="" enctype="multipart/form-data">
            <?php
            if (isset($errors)) {

                if (sizeof($errors) > 0) {

                    foreach ($errors as $err) {
            ?>
                        <div class="invalid-feedback" style="display:block; font-size:16px">
                            <?php
                            echo $err;
                            ?>
                        </div>
                        <?php
                    }
                } else {
                    $lang = new Language($db);

                    $lang->set_id($language_id);

                    if (!empty($_FILES["lang-img"]["tmp_name"])) {
                        $img_path = basename($_FILES["lang-img"]["name"]);
                        $temp = explode(".", $_FILES["lang-img"]["name"]);
                        $newfilename = round(microtime(true)) . '.' . end($temp);
                        $target = "img/lang/" . $newfilename;

                        $lang->set_image($newfilename);

                        if (!move_uploaded_file($_FILES["lang-img"]["tmp_name"], $target)) {    ?>
                            <div class="invalid-feedback" style="display:block; font-size:16px">
                                Greška pri dodavanju slike!
                            </div>

                            <?php
                        } else {
                            if ($lang->editLanguageImage()) { ?>
                                <div class="valid-feedback" style="display:block; font-size:16px">
                                    Fotografija jezika uspješno promijenjena!
                                </div>
                        <?php
                            }
                        }
                    } else { ?>
                        <div class="invalid-feedback" style="display:block; font-size:16px">
                            Molimo odaberite fotografiju!
                        </div>
                    <?php
                    }
                    ?>
            <?php
                }
            }
            ?>
            <input type="hidden" name="submitted" id="submitted">
            <div class="form-group">
                <label for="lang-img">Slika</label>
                <input type="file" class="form-control-file" id="lang-img" name="lang-img">
                <small id="langnameHelp" class="form-text text-pink">Datoteka ne smije biti veća od 2MB. Dozvoljeni formati datoteke su png, jpg i jpeg.</small>
            </div>
            <button type="submit" class="btn btn-pink">Uredi fotografiju</button>
            <button type="button" onclick="window.history.go(-1);" class="btn btn-outline-light-pink">Odustani</button><br><br>
            <a class="btn btn-outline-light-pink" href="delete_language_image.php?id=<?php echo $language_id ?>" role="button">Obriši fotografiju</a>
        </form>
    </div>

    <?php
    include_once("footer.php");
    ?>