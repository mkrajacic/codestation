<?php
$title = "Jezici";
include_once("header.php");
include_once("class/language.php");
$db = connect();
session_start();

$menu_items['main'] = array('Jezici', 'Korisnici', 'Ovlasti');
$menu_links['main'] = array('languages.php', 'users.php', 'roles.php');
$menu_items['sub'] = array('Novi jezik');
$menu_links['sub'] = array('new_language.php');
sidemenu($menu_items, $menu_links, "Jezici");

if (isset($_GET['login-success']) && $_GET['login-success'] == 1) {
    echo "Uspješan login!";
}
?>

<div id="page-content-wrapper">
    <!-- <?php user_header(); ?> -->
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
                echo "<br><br>";
                if (!empty($image)) {
                    echo "<img style='width:300px; height:300px;' src='img/lang/" . $image . "'>";
                } else {
                    echo "<img style='width:300px; height:300px;' src='img/default.jpg'>";
                }
                echo "<br><br>";
        ?>
                <a class="btn btn-outline-light-pink" href="edit_language.php?id=<?php echo $id ?>" role="button">Uredi</a>
                <button type="button" class="btn btn-outline-light-pink" data-toggle="modal" data-target="#langimgModal">Uredi fotografiju</button>
                <a class="btn btn-outline-strong-pink" href="delete_language_confirmation.php?id=<?php echo $id ?>" role="button">Obriši</a><br>

                <div class="modal fade" id="langimgModal" tabindex="-1" role="dialog" aria-labelledby="langimgModal" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title text-dark" id="exampleModalLabel">Promijeni fotografiju jezika</h5>
                                <button type="button" id="close-button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <?php
                                show_modal_messages();
                                ?>
                                <form method="post" action="edit_language_image.php" enctype="multipart/form-data" id="langImg">
                                    <input type="hidden" name="submitted" id="submitted">
                                    <input type="hidden" name="id" value="<?php echo $id ?>">
                                    <div class="form-group">
                                        <label class="text-dark" for="lang-img">Slika</label>
                                        <input type="file" class="form-control-file" id="lang-img" name="lang-img">
                                        <small id="langnameHelp" class="form-text text-muted">Datoteka ne smije biti veća od 2MB. Dozvoljeni formati datoteke su png, jpg i jpeg.</small>
                                    </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" id="langImgSubmit" class="btn btn-pink">Uredi fotografiju</button>
                                </form>
                                <a class="btn btn-outline-danger" href="delete_language_image.php?id=<?php echo $id ?>" role="button">Obriši fotografiju</a>
                            </div>
                        </div>
                    </div>
                </div>
        <?php
            }
        }
        ?>
    </div>

    <?php
    include_once("footer.php");
    show_modal(array('langimgModal'));
    ?>