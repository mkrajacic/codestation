<?php
$title = "Jezici";
include_once("header.php");
include_once("class/language.php");
$db = connect();
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
    <button class="btn btn-primary" id="menu-toggle">Sakrij meni</button>

    <?php
    $menu_items = array('Početna','Novi jezik');
    $menu_links = array('index.php','new_language.php');
    submenu($menu_items,$menu_links);
    ?>

</nav>

<div class="container-fluid">
    <h1 class="mt-4">Popis jezika</h1>
    <?php
        // $py = new Language($db,2,"madame","voce");

        // if($py->createLanguage()) {
        //     echo "dodano";
        // }else {
        //     echo "nedodano";
        // }

        $irama = new Language($db,1,"a che serve resistere","mai smetterai");
        $irama->editLanguage();

        $language = new Language($db);

        $stmt = $language->getLanguages($db);
        $numrows = $stmt->rowCount();
    
        if($numrows>0) {
            while ($language_row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($language_row);
                echo $id;
                echo "<br>";
                echo $name;
                echo "<br>";
                echo $description;
                echo "<br>";
            }
        }


    ?>
</div>

<?php
include_once("footer.php");
?>