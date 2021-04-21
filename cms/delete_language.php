<?php
$title = "Obriši jezik";
include_once("header.php");
include_once("class/language.php");
$db = connect();

if (isset($_GET['id'])) {

    $language_id = (int)$_GET['id'];
    $lang = new Language($db);

    if (!$stmt = $lang->getLanguageById($language_id)) {
        $errors = array('Dogodila se pogreška!');
    } else {
        $lang->set_id($language_id);

        if (!$lang->deleteLanguage()) {
            $errors = array('Greška pri brisanju programskog jezika!');
        }
    }
} else {
    $errors = array('Dogodila se pogreška!');
}

$menu_items = array('Početna', 'Novi jezik');
$menu_links = array('index.php', 'new_language.php');
nav($menu_items, $menu_links);
?>

<div class="container-fluid">
    <h1 class="mt-4">Obriši programski jezik</h1>
    <?php
    if (isset($errors)) {

        if (sizeof($errors) > 0) {

            foreach ($errors as $err) {
    ?>
                <p class="text-danger">$err</p>
        <?php
            }
        }
    } else {
        ?>
        <p class='text-light'>Programski jezik uspješno obrisan!</p>
        <a class="btn btn-outline-light-pink" href="languages.php" role="button">Povratak na programske jezike</a>
    <?php
    }
    ?>
</div>

<?php
include_once("footer.php");
?>