<?php
$title = "Nova lekcija";
include_once("header.php");
include_once("../class/lesson.php");
include_once("../class/user.php");
session_start();
$db = connect();

if (isset($_GET['lid'])) {
    $language_id = (int)$_GET['lid'];
} else {
    $_SESSION['redirect_message'] = "Greška pri učitavanju sadržaja!";
        header_redirect();
}

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
        $menu_items['main'] = array('Jezici','Lekcije', 'Korisnici');
        $menu_links['main'] = array('languages.php','lessons.php?lid=' . $language_id, 'users.php');
    }else if (check_user_status() == 2) {
        $menu_items['main'] = array('Jezici','Lekcije');
        $menu_links['main'] = array('languages.php','lessons.php?lid=' . $language_id);
    }
}else {
    $_SESSION['redirect_message'] = "Nije vam dopušten pristup sadržaju!";
        header_redirect();
}

if (isset($_POST['submitted'])) {
    $form_fields = array('less-name', 'less-desc');
    $form_names = array('Naziv lekcije', 'Opis lekcije');

    $ids=array("",$language_id);
    $errors = validate($form_fields, $form_names, $db, $ids, "Lesson");
}

$menu_items['sub'] = array();
$menu_links['sub'] = array();
$category = "Lekcije";
sidemenu($menu_items, $menu_links, $category, $user_id, $user_name, $avi);

$lesson = new Lesson($db);
$lesson->set_language_id($language_id);
$less_stmt = $lesson->getLessons(true);
$less_numrows = $less_stmt->rowCount();

?>
<div id="page-content-wrapper">
    <div class="container-fluid">
        <h1 class="mt-4 create-new">Dodaj lekciju</h1>
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
                    $less_precondition = $_POST['less-precondition'];

                    $less = new Lesson($db);
                    $less->set_name($less_name);
                    $less->set_description($less_desc);
                    $less->set_language_id($language_id);
                    $less->set_precondition($less_precondition);

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
                <input type="text" class="form-control" id="less-name" name="less-name" aria-describedby="lessnameHelp" placeholder="Upišite naziv lekcije" <?php if (isset($_POST['less-name'])) { ?> value="<?php echo $_POST['less-name'] ?>" <?php } ?>>
                <small id="lessnameHelp" class="form-text text-y">Naziv ne smije sadržavati više od 100 riječi.</small>
            </div>
            <div class="form-group">
                <label for="less-desc">Opis</label>
                <textarea class="form-control tinymce" id="less-desc" name="less-desc" rows="3" aria-describedby="lessdescHelp" placeholder="Upišite opis lekcije"><?php if (isset($_POST['less-name'])) { ?> <?php echo $_POST['less-desc'] ?> <?php } ?></textarea>
                <small id="lessdescHelp" class="form-text text-y">Opis mora sadržavati barem 100 riječi.</small>
            </div>
            <div class="form-group">
                <label for="less-precondition">Lekcija preduvijet</label><br>
                <select class="form-select custom-select custom-select-sm" name="less-precondition" id="less-precondition">
                    <option value="0" selected>Nema preduvijeta</option>
                    <?php
                    if ($less_numrows > 0) {
                        $l = 0;
                        while ($less_row = $less_stmt->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                            <option value="<?php echo $less_row['id'] ?>"><?php echo $less_row['name'] ?></option>
                    <?php
                        }
                    }
                    ?>
                </select>
                <small id="lessprecHelp" class="form-text text-y">Lekcija koju korisnik mora proći da bi mu ova lekcija bila dostupna.</small>
            </div>
            <button type="submit" class="btn btn-x">Dodaj lekciju</button>
            <button type="button" onclick="window.history.go(-1);" class="btn btn-y">Odustani</button>
        </form>
    </div>

    <?php
    include_once("footer.php");
    ?>

<script type="text/javascript" src="../vendor/tinymce/tinymce.min.js"></script>
<script type="text/javascript" src="../vendor/tinymce/init-tinymce.js"></script>