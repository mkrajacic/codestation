<?php
$title = "Jezici";
include_once("header.php");
include_once("class/language.php");
$db = connect();

$menu_items = array('Početna', 'Novi jezik');
$menu_links = array('index.php', 'new_language.php');
nav($menu_items,$menu_links);
?>

<div class="container-fluid">
    <h1 class="mt-4">Popis jezika</h1>
    <?php
    $language = new Language($db);

    $stmt = $language->getLanguages($db);
    $numrows = $stmt->rowCount();

    if ($numrows > 0) {
        while ($language_row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($language_row);
            echo "<br>";
            echo $name;
            echo "<br>";
            echo $description;
            echo "<br>";
            if(!empty($image)) {
                echo "<img style='width:300px; height:300px;' src='img/lang/" . $image . "'>";
            }else{
                echo "<img style='width:300px; height:300px;' src='img/default.jpg'>";
            }
            echo "<br>";
            echo "<br>";
            ?>
            <a class="btn btn-outline-light-pink" href="edit_language.php?id=<?php echo $id ?>" role="button">Uredi</a>
            <a class="btn btn-outline-strong-pink" href="delete_language_confirmation.php?id=<?php echo $id ?>" role="button">Obriši</a>
       <?php
        }
    }


    ?>
</div>

<?php
include_once("footer.php");
?>