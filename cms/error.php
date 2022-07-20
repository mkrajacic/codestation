<?php
$title = "Greška";
include_once("header.php");
$db = connect();

session_start();
?>

<div id="page-content-wrapper">
    <div class="container-fluid">
        <h1 class="mt-4">Greška</h1>
            <?php
            if (isset($_SESSION['redirect_message'])) {

            ?>
                        <div class="invalid-feedback" style="display:block; font-size:16px">
                            <?php
                            echo $_SESSION['redirect_message'];
                            ?>
                        </div>
                    <?php
                    }else{
                        ?>
                        <div class="invalid-feedback" style="display:block; font-size:16px">
                            <?php
                            echo "Dogodila se pogreška!";
                            ?>
                        </div>
                    <?php
                    }    
             ?>
    </div>

    <?php
    include_once("footer.php");
    ?>