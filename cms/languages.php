<?php
$title = "Jezici";
include_once("header.php");
include_once("class/language.php");
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
        $_SESSION['redirect_message'] = "Nije vam dopušten pristup sadržaju!";
        $_SESSION['show_modal'] = "redirectModal";
        header("Location: index.php");
    }
} else {
    $_SESSION['redirect_message'] = "Nije vam dopušten pristup sadržaju!";
    $_SESSION['show_modal'] = "redirectModal";
    header("Location: index.php");
}

$menu_items['sub'] = array('Novi jezik');
$menu_links['sub'] = array('new_language.php');
sidemenu($menu_items, $menu_links, "Jezici");
?>

<div id="page-content-wrapper">
    <?php user_header($user_id, $db); ?>
    <div class="container-fluid">
        <h1 class="mt-4">Popis jezika</h1>
        <?php
        $language = new Language($db);

        $stmt = $language->getLanguages($db);
        $numrows = $stmt->rowCount();

        if ($numrows > 0) {
            $c = 0;
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
                <a class="btn btn-outline-light" href="lessons.php?id=<?php echo $id ?>" role="button">Lekcije</a><br>
                <a class="btn btn-outline-light-pink" href="edit_language.php?id=<?php echo $id ?>" role="button">Uredi</a>
                <button type="button" class="btn btn-outline-light-pink" data-toggle="modal" data-target="#langimgModal<?php echo $c; ?>">Uredi fotografiju</button>
                <a class="btn btn-outline-strong-pink" href="delete_language_confirmation.php?id=<?php echo $id ?>" role="button">Obriši</a>

                <!-- lang image edit modal -->
                <div class="modal fade" id="langimgModal<?php echo $c; ?>" tabindex="-1" role="dialog" aria-labelledby="langimgModal<?php echo $c; ?>" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title text-dark langimgModalLabel">Promijeni fotografiju jezika</h5>
                                <button type="button" class="close close-button" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                    <div id="message-lang-img<?php echo $c; ?>" class='text-success'><p class="val-msg" id='val-msg-lang-img<?php echo $c; ?>'></p></div>
                                <form method="post" action="" enctype="multipart/form-data" id="langImg<?php echo $c; ?>">
                                    <input type="hidden" name="lang-img-submitted<?php echo $c; ?>" id="lang-img-submitted<?php echo $c; ?>">
                                    <input type="hidden" name="lang-img-id<?php echo $c; ?>" value="<?php echo $id ?>" id="lang-img-id<?php echo $c; ?>">
                                    <div class="form-group">
                                        <label class="text-dark" for="lang-img<?php echo $c; ?>">Slika</label>
                                        <input type="file" class="form-control-file" id="lang-img<?php echo $c; ?>" name="lang-img<?php echo $c; ?>">
                                        <small id="langimgHelp<?php echo $c; ?>" class="form-text text-muted">Datoteka ne smije biti veća od 2MB. Dozvoljeni formati datoteke su png, jpg i jpeg.</small>
                                    </div>
                                    <input type="button" class="btn btn-pink langImgSubmit" id="langImgSubmit-<?php echo $c; ?>" value="Uredi fotografiju">
                                </form>
                            </div>
                            <div class="modal-footer">
                                <form action="" method="post" id="langImgDel">
                                    <input type="hidden" name="lang-img-del-id<?php echo $c; ?>" value="<?php echo $id ?>" id="lang-img-del-id<?php echo $c; ?>">  
                                    <input type="button" class="btn btn-outline-danger langImgDelSubmit" id="langImgDelSubmit-<?php echo $c; ?>" value="Obriši fotografiju">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
        <?php
                $c++;
            }
        }
        ?>
    </div>

    <?php
    include_once("footer.php");
    ?>

    <script>
        $(document).ready(function() {
            $('.langImgSubmit').click(function() {
                var clickedBtnID = $(this).attr('id');
                var modal_id = clickedBtnID.split("-").pop();

                var fd = new FormData();
                var submitted = $('#lang-img-submitted' + modal_id).val();
                var id = $('#lang-img-id' + modal_id).val();
                var file = $('#lang-img' + modal_id)[0].files[0];

                fd.append('submitted', submitted);
                fd.append('id', id);
                fd.append('lang-img', file);

                $.ajax({
                    url: 'edit_language_image.php',
                    type: 'post',
                    cache: false,
                    data: fd,
                    contentType: false,
                    processData: false,
                    dataType: "JSON",
                    success: function(response) {
                        if (response.status == 1) {
                            $('#message-lang-img' + modal_id).attr('class', 'text-success');
                            $('#val-msg-lang-img' + modal_id).html(response.message);
                        } else {
                            $('#message-lang-img' + modal_id).attr('class', 'text-danger');
                            $('#val-msg-lang-img' + modal_id).html(response.message);
                        }
                    },
                    error: function() {
                        $('#message-lang-img' + modal_id).attr('class', 'text-danger');
                        $('#val-msg-lang-img' + modal_id).html("Molimo odaberite datoteku!");
                    }
                });
            });

            $('.langImgDelSubmit').click(function() {
                var clickedBtnID = $(this).attr('id');
                var modal_id = clickedBtnID.split("-").pop();

                var fd = new FormData();
                var id = $('#lang-img-del-id' + modal_id).val();

                fd.append('id', id);

                $.ajax({
                    url: 'delete_language_image.php',
                    type: 'post',
                    cache: false,
                    data: fd,
                    contentType: false,
                    processData: false,
                    dataType: "JSON",
                    success: function(response) {
                        if (response.status == 1) {
                            $('#message-lang-img' + modal_id).attr('class', 'text-success');
                            $('#val-msg-lang-img' + modal_id).html(response.message);
                        } else {
                            $('#message-lang-img' + modal_id).attr('class', 'text-danger');
                            $('#val-msg-lang-img' + modal_id).html(response.message);
                        }
                    },
                    error: function() {
                        $('#message-lang-img' + modal_id).attr('class', 'text-danger');
                        $('#val-msg-lang-img' + modal_id).html("Dogodila se pogreška!");
                    }
                });
            });
        });
    </script>