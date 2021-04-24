<?php
$title = "Administracija";
include_once("header.php");
$menu_items['main'] = array('Jezici', 'Korisnici', 'Ovlasti');
$menu_links['main'] = array('languages.php', 'users.php', 'roles.php');
sidemenu($menu_items,$menu_links);
?>

<div id="page-content-wrapper">
<div class="container-fluid">
    <h1 class="mt-4">Casa mia</h1>
    <p>Su questo app potete imparare le lingue di programming. Ci saranno anche i compiti in cui scrivete il vostro <code>code</code> e potete interpretelo.</p>
</div>

<?php
include_once("footer.php");
?>