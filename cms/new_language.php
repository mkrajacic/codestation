<?php
$title = "Novi jezik";
include_once("header.php");
include_once("class/language.php");
include_once("class/language_image.php");
$db = connect();

if (isset($_POST['submitted'])) {
    $form_fields = array('lang-name', 'lang-desc');
    $form_names = array('Naziv jezika', 'Opis jezika');

    $errors = validate($form_fields, $form_names);
    $img_err = image_upload("lang-img");

    foreach ($img_err as $er) {
        array_push($errors, $er);
    }
}
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
    <button class="btn btn-primary" id="menu-toggle">Sakrij meni</button>

    <?php
    $menu_items = array('Početna', 'Novi jezik');
    $menu_links = array('index.php', 'new_language.php');
    submenu($menu_items, $menu_links);
    ?>

</nav>

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
                $img_path = basename($_FILES["lang-img"]["name"]);

                $lang = new Language($db);
                $lang->set_name($lang_name);
                $lang->set_description($lang_desc);

                $lang_img = new LanguageImage($db);

                $temp = explode(".", $_FILES["lang-img"]["name"]);
                $newfilename = round(microtime(true)) . '.' . end($temp);
                $target = "img/lang/" . $newfilename;

                $lang_img->set_image($newfilename);

                if (!move_uploaded_file($_FILES["lang-img"]["tmp_name"], $target)) {
                    array_push($errors, "Greška pri dodavanju slike!");
                } else {

                    if ($lang->createLanguage()) {

                        $lang_img->set_language_id($db->lastInsertId());

                        if ($lang_img->addLanguageImage()) {
                    ?>
                            <div class="valid-feedback" style="display:block; font-size:16px">
                                Jezik uspješno dodan!
                            </div>
            <?php
                        } else {
                            array_push($errors, "Greška pri dodavanju jezika!");
                        }
                    } else {
                        array_push($errors, "Greška pri dodavanju jezika!");
                    }
                }
            }
            ?>
        <?php
        }
        ?>
        <input type="hidden" name="submitted" id="submitted">
        <div class="form-group">
            <label for="lang-name">Naziv</label>
            <input type="text" class="form-control" id="lang-name" name="lang-name" aria-describedby="langnameHelp" placeholder="Upišite naziv jezika" <?php if (isset($_POST['lang-name'])) { ?> value="<?php echo $_POST['lang-name'] ?>" <?php } ?>>
            <small id="langnameHelp" class="form-text text-muted">Naziv ne smije sadržavati više od 25 znakova.</small>
        </div>
        <div class="form-group">
            <label for="lang-desc">Opis</label>
            <textarea class="form-control" id="lang-desc" name="lang-desc" rows="3" placeholder="Upišite opis jezika"><?php if (isset($_POST['lang-name'])) { ?> <?php echo $_POST['lang-desc'] ?> <?php } ?></textarea>
        </div>
        <div class="form-group">
            <label for="lang-img">Slika</label>
            <input type="file" class="form-control-file" id="lang-img" name="lang-img">
            <small id="langnameHelp" class="form-text text-muted">Datoteka ne smije biti veća od 2MB. Dozvoljeni formati datoteke su png, jpg i jpeg. Dozvoljene dimenzije su do 500x400</small>
        </div>
        <button type="submit" class="btn btn-primary">Dodaj jezik</button>
    </form>
</div>

<?php
include_once("footer.php");
?>