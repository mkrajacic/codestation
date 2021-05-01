<?php
$title = "Administracija";
include_once("header.php");
include_once("class/user.php");
$db = connect();
session_start();
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    if (check_user_status() == 1) {
        $menu_items['main'] = array('Jezici', 'Korisnici', 'Ovlasti');
        $menu_links['main'] = array('languages.php', 'users.php', 'roles.php');
    } else if (check_user_status() == 2) {
        $menu_items['main'] = array('Jezici');
        $menu_links['main'] = array('languages.php');
    } else {
        $menu_items['main'] = array('');
        $menu_links['main'] = array('');
    }
} else {
    $menu_items['main'] = array('Prijava','Registracija');
    $menu_links['main'] = array('login.php','register.php');
}
sidemenu($menu_items, $menu_links);
?>

<div id="page-content-wrapper">
<?php user_header($user_id, $db); ?>
    <div class="container-fluid">
        <h1 class="mt-4">Casa mia</h1>
        <p>Su questo app potete imparare le lingue di programming. Ci saranno anche i compiti in cui scrivete il vostro <code>code</code> e potete interpretelo.</p>
    </div>

    <?php
    insert_redirect_modal();
    ?>

    <?php
    include_once("footer.php");
    ?>