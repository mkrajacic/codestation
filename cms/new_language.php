<?php
$title = "Novi jezik";
include_once("header.php");
include_once("class/language.php");
$db = connect();

if (isset($_POST['submitted'])) {
    $form_fields = array('lang-name', 'lang-desc');
    $form_names = array('Naziv jezika', 'Opis jezika');

    $errors = validateLanguage($form_fields, $form_names, $db);
    $img_err = image_upload("lang-img");

    foreach ($img_err as $er) {
        array_push($errors, $er);
    }
}

$menu_items = array('Početna', 'Novi jezik');
$menu_links = array('index.php', 'new_language.php');
nav($menu_items, $menu_links);
?>

<div class="container-fluid">
    <h1 class="mt-4">Dodaj programski jezik</h1>
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
                $lang_name = $_POST['lang-name'];
                $lang_desc = $_POST['lang-desc'];

                $lang = new Language($db);
                $lang->set_name($lang_name);
                $lang->set_description($lang_desc);

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
                    }
                }

                if ($lang->createLanguage()) {
                    ?>
                    <div class="valid-feedback" style="display:block; font-size:16px">
                        Jezik uspješno dodan!
                    </div>
        <?php
                }
            }
        }
        ?>
        <input type="hidden" name="submitted" id="submitted">
        <div class="form-group">
            <label for="lang-name">Naziv</label>
            <input type="text" class="form-control" id="lang-name" name="lang-name" aria-describedby="langnameHelp" placeholder="Upišite naziv jezika" <?php if (isset($_POST['lang-name'])) { ?> value="<?php echo $_POST['lang-name'] ?>" <?php } ?>>
            <small id="langnameHelp" class="form-text text-pink">Naziv ne smije sadržavati više od 25 znakova.</small>
        </div>
        <div class="form-group">
            <label for="lang-desc">Opis</label>
            <textarea class="form-control" id="lang-desc" name="lang-desc" rows="3" aria-describedby="langdescHelp" placeholder="Upišite opis jezika"><?php if (isset($_POST['lang-name'])) { ?> <?php echo $_POST['lang-desc'] ?> <?php } ?></textarea>
            <small id="langdescHelp" class="form-text text-pink">Opis mora sadržavati barem 100 znakova.</small>
        </div>
        <div class="form-group">
            <label for="lang-img">Slika</label>
            <input type="file" class="form-control-file" id="lang-img" name="lang-img">
            <small id="langnameHelp" class="form-text text-pink">Datoteka ne smije biti veća od 2MB. Dozvoljeni formati datoteke su png, jpg i jpeg.</small>
        </div>
        <button type="submit" class="btn btn-pink">Dodaj jezik</button>
        <button type="button" onclick="window.history.go(-1);" class="btn btn-outline-light-pink">Odustani</button>
    </form>
</div>

<?php
include_once("footer.php");
?>