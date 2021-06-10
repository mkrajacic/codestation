<?php
$title = "Jezici";
include_once("header.php");
include_once("../class/language.php");
include_once("../class/user.php");
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
                <a class="btn btn-outline-light" href="lessons.php?lid=<?php echo $id ?>" role="button">Lekcije</a><br>
                <button type="button" class="btn btn-outline-light-pink" data-toggle="modal" data-target="#langEditModal<?php echo $c; ?>">Uredi</button>
                <button type="button" class="btn btn-outline-light-pink" data-toggle="modal" data-target="#langimgModal<?php echo $c; ?>">Uredi fotografiju</button>
                <button type="button" class="btn btn-outline-strong-pink" data-toggle="modal" data-target="#langDelModal<?php echo $c; ?>">Obriši</button>

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
                                <div id="message-lang-img<?php echo $c; ?>" class='text-success'>
                                    <p class="val-msg" id='val-msg-lang-img<?php echo $c; ?>'></p>
                                </div>
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
                            <div class="modal-footer" id="lang-img-del-footer-<?php echo $c; ?>">
                                <form action="" method="post" id="langImgDel">
                                    <input type="hidden" name="lang-img-del-id<?php echo $c; ?>" value="<?php echo $id ?>" id="lang-img-del-id<?php echo $c; ?>">
                                    <input type="button" class="btn btn-outline-danger langImgDelSubmit" id="langImgDelSubmit-<?php echo $c; ?>" value="Obriši fotografiju">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- lang edit modal -->
                <div class="modal fade" id="langEditModal<?php echo $c; ?>" tabindex="-1" role="dialog" aria-labelledby="langEditModal<?php echo $c; ?>" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title text-dark langeditModalLabel">Uredi programski jezik</h5>
                                <button type="button" class="close close-button" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div id="message-lang-edit<?php echo $c; ?>" class='text-success'>
                                    <p class="val-msg" id='val-msg-lang-edit<?php echo $c; ?>'></p>
                                </div>
                                <form method="post" action="" id="langEdit<?php echo $c; ?>">
                                    <input type="hidden" name="lang-edit-submitted<?php echo $c; ?>" id="lang-edit-submitted<?php echo $c; ?>">
                                    <input type="hidden" name="lang-edit-id<?php echo $c; ?>" value="<?php echo $id ?>" id="lang-edit-id<?php echo $c; ?>">
                                    <div class="form-group">
                                        <label class="text-dark" for="lang-name<?php echo $c; ?>">Naziv</label>
                                        <input type="text" class="form-control" id="lang-name<?php echo $c; ?>" name="lang-name<?php echo $c; ?>" aria-describedby="langnameHelp" placeholder="Upišite naziv jezika" value="<?php echo $name ?>">
                                        <small id="langnameHelp<?php echo $c; ?>" class="form-text text-muted">Naziv ne smije sadržavati više od 25 znakova.</small>
                                    </div>
                                    <div class="form-group">
                                        <label class="text-dark" for="lang-desc<?php echo $c; ?>">Opis</label>
                                        <textarea class="form-control" id="lang-desc<?php echo $c; ?>" name="lang-desc<?php echo $c; ?>" rows="6" aria-describedby="langdescHelp" placeholder="Upišite opis jezika"><?php echo $description ?></textarea>
                                        <small id="langdescHelp<?php echo $c; ?>" class="form-text text-muted">Opis mora sadržavati barem 100 znakova.</small>
                                    </div>
                            </div>
                            <div class="modal-footer">
                                <input type="button" class="btn btn-pink langEditSubmit" id="langEditSubmit-<?php echo $c; ?>" value="Uredi jezik">
                                <!--<button type="button" data-dismiss="modal" aria-label="Close" class="btn btn-outline-secondary">Odustani</button>-->
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- lang del confirmation modal -->
                <div class="modal fade" id="langDelModal<?php echo $c; ?>" tabindex="-1" role="dialog" aria-labelledby="langDelModal<?php echo $c; ?>" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title text-dark langdelModalLabel">Obriši programski jezik</h5>
                                <button type="button" class="close close-button" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div id="message-lang-del<?php echo $c; ?>" class='text-success'>
                                    <p class="val-msg" id='val-msg-lang-del<?php echo $c; ?>'></p>
                                </div>
                                <form method="post" action="" id="langEdit<?php echo $c; ?>">
                                    <input type="hidden" name="lang-del-id<?php echo $c; ?>" value="<?php echo $id ?>" id="lang-del-id<?php echo $c; ?>">
                                    <h5 class="text-dark" id="lang-del-confirm-text<?php echo $c; ?>">Jeste li sigurni da želite obrisati <?php echo $name ?>?</h5>
                            </div>
                            <div class="modal-footer">
                                <input type="button" class="btn btn-pink langDelSubmit" id="langDelSubmit-<?php echo $c; ?>" value="Obriši jezik">
                                <button type="button" id="langDelCancel<?php echo $c; ?>" data-dismiss="modal" aria-label="Close" class="btn btn-outline-danger">Odustani</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
        <?php
                $c++;
            }
        } else {
            echo "Nema rezultata";
        }
        ?>
    </div>


    <?php
    insert_redirect_modal();
    ?>

    <?php
    include_once("footer.php");
    show_modal(array('redirectModal'));
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
                            //$('#lang-img-del-footer-' + modal_id).attr('style','display:none');
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

            $('.langEditSubmit').click(function() {
                var clickedBtnID = $(this).attr('id');
                var modal_id = clickedBtnID.split("-").pop();

                var fd = new FormData();
                var submitted = $('#lang-edit-submitted' + modal_id).val();
                var id = $('#lang-edit-id' + modal_id).val();
                var name = $('#lang-name' + modal_id).val();
                var description = $('#lang-desc' + modal_id).val();

                fd.append('submitted', submitted);
                fd.append('id', id);
                fd.append('lang-name', name);
                fd.append('lang-desc', description);

                $.ajax({
                    url: 'edit_language.php',
                    type: 'post',
                    cache: false,
                    data: fd,
                    contentType: false,
                    processData: false,
                    dataType: "JSON",
                    success: function(response) {
                        if (response.status == 1) {
                            $('#message-lang-edit' + modal_id).attr('class', 'text-success');
                            $('#val-msg-lang-edit' + modal_id).html(response.message);
                        } else {
                            $('#message-lang-edit' + modal_id).attr('class', 'text-danger');
                            $('#val-msg-lang-edit' + modal_id).html(response.message);
                        }
                    },
                    error: function() {
                        $('#message-lang-edit' + modal_id).attr('class', 'text-danger');
                        $('#val-msg-lang-edit' + modal_id).html("Dogodila se pogreška!");
                    }
                });
            });

            $('.langDelSubmit').click(function() {
                var clickedBtnID = $(this).attr('id');
                var modal_id = clickedBtnID.split("-").pop();

                var fd = new FormData();
                var id = $('#lang-del-id' + modal_id).val();

                fd.append('id', id);

                $.ajax({
                    url: 'delete_language.php',
                    type: 'post',
                    cache: false,
                    data: fd,
                    contentType: false,
                    processData: false,
                    dataType: "JSON",
                    success: function(response) {
                        if (response.status == 1) {
                            $('#message-lang-del' + modal_id).attr('class', 'text-success');
                            $('#val-msg-lang-del' + modal_id).html(response.message);
                            $('#lang-del-confirm-text' + modal_id).html('');
                            $('#langDelCancel' + modal_id).attr('display','none');
                        } else {
                            $('#message-lang-del' + modal_id).attr('class', 'text-danger');
                            $('#val-msg-lang-del' + modal_id).html(response.message);
                        }
                    },
                    error: function() {
                        $('#message-lang-del' + modal_id).attr('class', 'text-danger');
                        $('#val-msg-lang-del' + modal_id).html("Dogodila se pogreška!");
                    }
                });
            });

        });
    </script>