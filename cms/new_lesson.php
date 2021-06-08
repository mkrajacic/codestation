<?php
$title = "Nova lekcija";
include_once("header.php");
include_once("class/lesson.php");
session_start();
$db = connect();

if (isset($_GET['lid'])) {
    $language_id = (int)$_GET['lid'];
}else{
    $_SESSION['redirect_message'] = "Dogodila se pogreška!";
    $_SESSION['show_modal'] = "redirectModal";
    header("Location: languages.php");
}

if (isset($_POST['submitted'])) {
    $form_fields = array('less-name', 'less-desc');
    $form_names = array('Naziv lekcije', 'Opis lekcije');

    $errors = validate($form_fields, $form_names, $db, $language_id,"Lesson");
}

$menu_items['main'] = array('Jezici','Lekcije', 'Korisnici', 'Ovlasti');
$menu_links['main'] = array('languages.php','lessons.php?lid=' . $language_id, 'users.php', 'roles.php');
$menu_items['sub'] = array();
$menu_links['sub'] = array();
sidemenu($menu_items,$menu_links,"Lekcije");
?>
<div id="page-content-wrapper">
<div class="container-fluid">
    <h1 class="mt-4">Dodaj lekciju</h1>
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
                $less_name = $_POST['less-name'];
                $less_desc = $_POST['less-desc'];

                $less = new Lesson($db);
                $less->set_name($less_name);
                $less->set_description($less_desc);
                $less->set_language_id($language_id);

                if ($less->createLesson()) {
                    ?>
                    <div class="valid-feedback" style="display:block; font-size:16px">
                        Lekcija uspješno dodana!
                    </div>
        <?php
                }
            }
        }
        ?>
        <input type="hidden" name="submitted" id="submitted">
        <div class="form-group">
            <label for="less-name">Naziv</label>
            <input type="text" class="form-control" id="less-name" name="less-name" aria-describedby="lessnameHelp" placeholder="Upišite naziv jezika" <?php if (isset($_POST['less-name'])) { ?> value="<?php echo $_POST['less-name'] ?>" <?php } ?>>
            <small id="lessnameHelp" class="form-text text-pink">Naziv ne smije sadržavati više od 100 znakova.</small>
        </div>
        <div class="form-group">
            <label for="less-desc">Opis</label>
            <textarea class="form-control" id="less-desc" name="less-desc" rows="3" aria-describedby="lessdescHelp" placeholder="Upišite opis jezika"><?php if (isset($_POST['less-name'])) { ?> <?php echo $_POST['less-desc'] ?> <?php } ?></textarea>
            <small id="lessdescHelp" class="form-text text-pink">Opis mora sadržavati barem 100 znakova.</small>
        </div>
        <button type="submit" class="btn btn-pink">Dodaj lekciju</button>
        <button type="button" onclick="window.history.go(-1);" class="btn btn-outline-light-pink">Odustani</button>
    </form>
</div>

<?php
include_once("footer.php");
?>