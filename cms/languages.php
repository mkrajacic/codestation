<?php
$title = "Jezici";
include_once("header.php");
include_once("class/language.php");
include_once("class/language_image.php");
$db = connect();
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
    <h1 class="mt-4">Popis jezika</h1>
    <?php
    $language = new Language($db);
    $language_img = new LanguageImage($db);

    $stmt = $language->getLanguages($db);
    $numrows = $stmt->rowCount();

    $img_stmt = $language_img->getLanguageImages($db);
    $img_count = $img_stmt->rowCount();

    if ($numrows > 0) {
        while ($language_row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($language_row);
            echo $id;
            echo "<br>";
            echo $name;
            echo "<br>";
            echo $description;
            echo "<br>";
            if ($img_count > 0) {
                while ($img_row = $img_stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($img_row);
                    echo "<img src='img/lang/" . $image . "'>";
                }
            }
        }
    }


    ?>
</div>

<?php
include_once("footer.php");
?>