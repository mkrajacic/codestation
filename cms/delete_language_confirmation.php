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
        $language_row = $stmt->fetch(PDO::FETCH_ASSOC);
        extract($language_row);
        $lang_name = $name;
    }
} else {
    $errors = array('Dogodila se pogreška!');
}

$menu_items['main'] = array('Jezici', 'Korisnici', 'Ovlasti');
$menu_links['main'] = array('languages.php', 'users.php', 'roles.php');
$menu_items['sub'] = array('Novi jezik');
$menu_links['sub'] = array('new_language.php');
sidemenu($menu_items,$menu_links,"Jezici");
?>
<div id="page-content-wrapper">
<?php user_header(); ?>
<div class="container-fluid">
    <h1 class="mt-4">Obriši programski jezik</h1>
    <?php
    if (isset($errors)) {

        if (sizeof($errors) > 0) {

            foreach ($errors as $err) {
    ?>
                <p class="text-danger"><?php echo $err ?></p>
                <button type="button" onclick="window.history.go(-1);" class="btn btn-outline-light-pink">Povratak</button>
    <?php
            }
        }
    }

    $deleted_item_label = " programski jezik ";
    $deleted_item = $lang_name;
    $delete_url = "delete_language.php?id=" . $language_id;
    $delete_button = "Obriši jezik";
    delete_confirmation($deleted_item_label, $deleted_item, $delete_url, $delete_button);
    ?>
</div>

<?php
include_once("footer.php");
?>