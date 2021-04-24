<?php
$title = "Uredi jezik";
include_once("header.php");
include_once("class/language.php");
$db = connect();

if (isset($_GET['id'])) {

    $language_id = (int)$_GET['id'];
    $old_lang = new Language($db);

    if (!$stmt = $old_lang->getLanguageById($language_id)) {
        $errors = array('Dogodila se pogreška!');
    } else {
        $numrows = $stmt->rowCount();
        if ($numrows > 0) {
            while ($language_row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($language_row);
                $old_name = $name;
                $old_desc = $description;
            }
        }
    }

    if (isset($_POST['submitted'])) {
        $form_fields = array('lang-name', 'lang-desc');
        $form_names = array('Naziv jezika', 'Opis jezika');

        $errors = validateLanguage($form_fields, $form_names, $db, $language_id);
    }
} else {
    $errors = array('Dogodila se pogreška!');
}

$menu_items['sub'] = array('Početna', 'Novi jezik');
$menu_links['sub'] = array('index.php', 'new_language.php');
sidemenu($menu_items,$menu_links,"Jezici");
?>

<div id="page-content-wrapper">
<div class="container-fluid">
    <h1 class="mt-4">Uredi programski jezik</h1>
    <form method="POST" action="">
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

                $lang->set_id($language_id);
                $lang->set_name($lang_name);
                $lang->set_description($lang_desc);

                if ($lang->editLanguage()) {
                ?>
                    <div class="valid-feedback" style="display:block; font-size:16px">
                        Jezik uspješno uređen!
                    </div>
        <?php
                }
            }
        }
        ?>
        <input type="hidden" name="submitted" id="submitted">
        <div class="form-group">
            <label for="lang-name">Naziv</label>
            <input type="text" class="form-control" id="lang-name" name="lang-name" aria-describedby="langnameHelp" placeholder="Upišite naziv jezika" value="<?php if (isset($_POST['lang-name'])) { ?><?php echo $_POST['lang-name']; ?><?php } else {
                                                                                                                                                                                                                                            echo $old_name;
                                                                                                                                                                                                                                        } ?>">
            <small id="langnameHelp" class="form-text text-pink">Naziv ne smije sadržavati više od 25 znakova.</small>
        </div>
        <div class="form-group">
            <label for="lang-desc">Opis</label>
            <textarea class="form-control" id="lang-desc" name="lang-desc" rows="3" aria-describedby="langdescHelp" placeholder="Upišite opis jezika"><?php if (isset($_POST['lang-name'])) { ?> <?php echo $_POST['lang-desc'] ?> <?php } else {
                                                                                                                                                                                                                                    echo $old_desc;
                                                                                                                                                                                                                                } ?></textarea>
            <small id="langdescHelp" class="form-text text-pink">Opis mora sadržavati barem 100 znakova.</small>
        </div>
        <button type="submit" class="btn btn-pink">Uredi jezik</button>
        <button type="button" onclick="window.history.go(-1);" class="btn btn-outline-light-pink">Odustani</button>
    </form>
</div>

<?php
include_once("footer.php");
?>