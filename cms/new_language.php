<?php
$title = "Novi jezik";
include_once("header.php");
include_once("../class/language.php");
include_once("../class/user.php");
$db = connect();
session_start();

$auth = isAuthorized();
if (($auth == 0) || ($auth == 3)) {
    $_SESSION['redirect_message'] = "Nije vam dopušten pristup sadržaju!";
    header_redirect();
}

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    $user = new User($db);
    if ($stmt = $user->getUserById($user_id)) {
        $user_row = $stmt->fetch(PDO::FETCH_ASSOC);
        extract($user_row);
        $user_name = $username;
        $avi = $image;
    }
    if (check_user_status() == 1) {
        $menu_items['main'] = array('Jezici', 'Korisnici');
        $menu_links['main'] = array('languages.php', 'users.php');
    }else if (check_user_status() == 2) {
        $menu_items['main'] = array('Jezici');
        $menu_links['main'] = array('languages.php');
    }
}else {
    $_SESSION['redirect_message'] = "Nije vam dopušten pristup sadržaju!";
        header_redirect();
}

if (isset($_POST['submitted'])) {
    $form_fields = array('lang-name', 'lang-desc','lang-c-mode','lang-version','lang-e-mode');
    $form_names = array('Naziv jezika', 'Opis jezika','Mod kompajlera','Verzija jezika','Mod editora');

    $errors = validate($form_fields, $form_names, $db,null,"Language");
    $img_err = image_upload("lang-img");

    foreach ($img_err as $er) {
        array_push($errors, $er);
    }
}

$menu_items['sub'] = array('Novi jezik');
$menu_links['sub'] = array('new_language.php');
$category = "Jezici";
sidemenu($menu_items,$menu_links,$category, $user_id, $user_name, $avi);
?>
<div id="page-content-wrapper">
<div class="container-fluid">
    <h1 class="mt-4 create-new">Dodaj programski jezik</h1>
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
                $lang_c_mode = $_POST['lang-c-mode'];
                $lang_version = $_POST['lang-version'];
                $lang_e_mode = $_POST['lang-e-mode'];

                $lang = new Language($db);
                $lang->set_name($lang_name);
                $lang->set_description($lang_desc);
                $lang->set_compiler_mode($lang_c_mode);
                $lang->set_language_version($lang_version);
                $lang->set_editor_mode($lang_e_mode);

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
            <small id="langnameHelp" class="form-text text-y">Naziv ne smije sadržavati više od 25 riječi.</small>
        </div>
        <div class="form-group">
            <label for="lang-desc">Opis</label>
            <textarea class="form-control tinymce" id="lang-desc" name="lang-desc" rows="3" aria-describedby="langdescHelp" placeholder="Upišite opis jezika"><?php if (isset($_POST['lang-name'])) { ?> <?php echo $_POST['lang-desc'] ?> <?php } ?></textarea>
            <small id="langdescHelp" class="form-text text-y">Opis mora sadržavati barem 100 riječi.</small>
        </div>
        <div class="form-group">
            <label for="lang-img">Slika</label>
            <input type="file" class="form-control-file" id="lang-img" name="lang-img">
            <small id="langnameHelp" class="form-text text-y">Datoteka ne smije biti veća od 2MB. Dozvoljeni formati datoteke su png, jpg i jpeg.</small>
        </div>
        <div class="form-group">
            <label for="lang-c-mode">Mod kompajlera</label>
            <input type="text" class="form-control" id="lang-c-mode" name="lang-c-mode" aria-describedby="langcmodeHelp" placeholder="Upišite mod kompajlera" <?php if (isset($_POST['lang-c-mode'])) { ?> value="<?php echo $_POST['lang-c-mode'] ?>" <?php } ?>>
            <small id="langcmodeHelp" class="form-text text-y">Mod jezika koji će se koristiti pri pozivima na API za kompajliranje koda.</small>
        </div>
        <div class="form-group">
            <label for="lang-version">Verzija jezika (kompajler)</label>
            <input type="text" class="form-control" id="lang-version" name="lang-version" aria-describedby="langversionHelp" placeholder="Upišite verziju" <?php if (isset($_POST['lang-version'])) { ?> value="<?php echo $_POST['lang-version'] ?>" <?php } ?>>
            <small id="langversionHelp" class="form-text text-y">Verzija jezika koja će se koristiti pri pozivima na API za kompajliranje koda.</small>
        </div>
        <div class="form-group">
            <label for="lang-e-mode">Mod editora</label>
            <input type="text" class="form-control" id="lang-e-mode" name="lang-e-mode" aria-describedby="langemodeHelp" placeholder="Upišite mod editora" <?php if (isset($_POST['lang-e-mode'])) { ?> value="<?php echo $_POST['lang-e-mode'] ?>" <?php } ?>>
            <small id="langemodeHelp" class="form-text text-y">Mod jezika koji će se koristiti pri kreiranju code editora.</small>
        </div>
        <button type="submit" id="addLanguage" class="btn btn-x">Dodaj jezik</button>
        <button type="button" onclick="window.history.go(-1);" class="btn btn-y">Odustani</button>
    </form>
</div>

<?php
include_once("footer.php");
?>
<script type="text/javascript" src="../vendor/tinymce/tinymce.min.js"></script>
<script type="text/javascript" src="../vendor/tinymce/init-tinymce.js"></script>