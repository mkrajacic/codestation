<?php
$title = "Novo pitanje";
include_once("header.php");
include_once("../class/question.php");
include_once("../class/question_type.php");
include_once("../class/user.php");
$db = connect();
session_start();

if (isset($_GET['lid'])) {
    $lesson_id = (int)$_GET['lid'];
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
        $menu_items['main'] = array('Jezici','Pitanja', 'Korisnici');
        $menu_links['main'] = array('languages.php','questions.php?lid=' . $lesson_id, 'users.php');
    }else if (check_user_status() == 2) {
        $menu_items['main'] = array('Jezici','Pitanja');
        $menu_links['main'] = array('languages.php','questions.php?lid=' . $lesson_id);
    }
}else {
    $_SESSION['redirect_message'] = "Nije vam dopušten pristup sadržaju!";
        header_redirect();
}

if (isset($_POST['submitted'])) {
    $form_fields = array('quest-name', 'quest-type');
    $form_names = array('Pitanje', 'Vrsta pitanja');
    $errors = validate($form_fields, $form_names, $db, null, "Question");
}

$menu_items['sub'] = array();
$menu_links['sub'] = array();
$category = "Pitanja";
sidemenu($menu_items, $menu_links,$category, $user_id, $user_name, $avi);
?>
<div id="page-content-wrapper">
    <div class="container-fluid">
        <h1 class="mt-4 create-new">Dodaj pitanje</h1>
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
                    $quest_name = $_POST['quest-name'];
                    $quest_type = $_POST['quest-type'];

                    $quest = new Question($db);
                    $quest->set_question($quest_name);
                    $quest->set_question_type($quest_type);
                    $quest->set_lesson_id($lesson_id);

                    if ($quest->createQuestion()) {
                    ?>
                        <div class="valid-feedback" style="display:block; font-size:16px">
                            Pitanje uspješno dodano!
                        </div>
            <?php
                    }
                }
            }
            ?>
            <input type="hidden" name="submitted" id="submitted">
            <div class="form-group">
                <label for="quest-name">Pitanje</label>
                <textarea class="form-control" id="quest-name" name="quest-name" aria-describedby="questnameHelp" placeholder="Upišite pitanje" <?php if (isset($_POST['quest-name'])) { ?> value="<?php echo $_POST['quest-name'] ?>" <?php } ?>></textarea>
                <small id="questnameHelp" class="form-text text-y">Tekst pitanja ne smije sadržavati manje od 10 znakova.</small>
            </div>
            <div class="form-group">
                <label for="quest-type">Vrsta pitanja</label>
                <select class="form-select custom-select custom-select-sm" name="quest-type" id="quest-type">
                    <option value="0" selected>Vrsta pitanja...</option>
                    <?php
                    $type = new QuestionType($db);
                    $type_stmt = $type->getTypes();
                    $type_numrows = $type_stmt->rowCount();

                    if ($type_numrows > 0) {
                        while ($type_row = $type_stmt->fetch(PDO::FETCH_ASSOC)) {   ?>
                            <option value="<?php echo $type_row['id'] ?>"><?php echo $type_row['type'] ?></option>
                    <?php
                        }
                    }
                    ?>
                </select>
                <small id="questtypeHelp" class="form-text text-y">Vrsta pitanja.</small>
            </div>
            <button type="submit" class="btn btn-x">Dodaj pitanje</button>
            <button type="button" onclick="window.history.go(-1);" class="btn btn-y">Odustani</button>
        </form>
    </div>

    <?php
    include_once("footer.php");
    ?>