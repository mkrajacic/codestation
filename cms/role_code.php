<?php
$title = "Ovlasti";
include_once("header.php");
include_once("../class/user.php");
$db = connect();
session_start();

$auth = isAuthorized();
if (($auth == 0) || ($auth == 3)) {
    $_SESSION['redirect_message'] = "Nije vam dopušten pristup sadržaju!";
    header_redirect();
}

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $user = new User($db);
    if ($stmt = $user->getUserById($user_id)) {
        $user_row = $stmt->fetch(PDO::FETCH_ASSOC);
        extract($user_row);
        $user_name = $username;
        $avi = $image;
    }
    if (check_user_status() == 1) {
        $menu_items['main'] = array('Jezici', 'Korisnici');
        $menu_links['main'] = array('languages.php', 'users.php');
    }
} else {
    $_SESSION['redirect_message'] = "Nije vam dopušten pristup sadržaju!";
        header_redirect();
}

$role = "";
if (isset($_GET['rc'])) {
    $rc = $_GET['rc'];

    if ($rc == "AD") {
        $role = "administrator";
    } else if ($rc == "MOD") {
        $role = "moderator";
    } else if ($rc == "USR") {
        $role = "korisnik";
    }
}

$menu_items['sub'] = array();
$menu_links['sub'] = array();
$category = "Korisnici";
sidemenu($menu_items, $menu_links, $category, $user_id, $user_name, $avi);

$user = new User($db);
$user->set_role_code($rc);
$number_of_results = $user->CountUsers();
?>


<div id="page-content-wrapper">
    <div class="container-fluid">
        <h1 class="mt-4">Popis korisnika sa ovlasti <?php echo $role; ?> (<?php echo $number_of_results; ?>)</h1>
        <?php
        if (!isset($_GET['page'])) {
            $page = 1;
        } else {
            $page = $_GET['page'];
        }

        $limitStart = ($page - 1) * 10;
        $pagehref = "role_code.php?rc=" . $rc . "&";

        if (isset($_GET['sortBy']) && isset($_GET['order'])) {
            $sortBy = $_GET['sortBy'];
            $order = $_GET['order'];

            $allowed_orders = array('desc', 'asc');
            $allowed_sorts = array('id', 'username');

            if (!in_array($sortBy, $allowed_sorts)) {
                $sortBy = "id";
            }

            if (!in_array($order, $allowed_orders)) {
                $order = "asc";
            }

            $stmt = $user->getUsersByRoleSorted($sortBy, $order, $limitStart);
            $pagehref .= "sortBy=" . $sortBy . "&order=" . $order . "&";
        } else {
            $stmt = $user->getUsersByRole($limitStart);
        }

        $numrows = $stmt->rowCount();

        if ($numrows > 0) {
        ?>
            <div class="table-responsive">
<table class="table table-dark table-hover table-striped">
                <tr>
                    <th scope="col" class="th-sm"><a class="text-warning" href='role_code.php?rc=<?php echo $rc ?>&sortBy=id&order=<?php if (!(isset($_GET['sortBy'])) || !($_GET['sortBy'] == 'id') || !(isset($_GET['order'])) || (($_GET['sortBy'] == 'id') && ($_GET['order'] == 'asc'))) {
                                                                                                echo 'desc';
                                                                                            } else if (((isset($_GET['sortBy'])) || ($_GET['sortBy'] == 'id'))) {
                                                                                                echo 'asc';
                                                                                            } ?>&page=<?php echo $page; ?>'>Id</a></th>
                    <th scope="col" class="th-sm"><a class="text-warning" href='role_code.php?rc=<?php echo $rc ?>&sortBy=username&order=<?php if (!(isset($_GET['sortBy'])) || !($_GET['sortBy'] == 'username') || !(isset($_GET['order'])) || (($_GET['sortBy'] == 'username') && ($_GET['order'] == 'asc'))) {
                                                                                                        echo 'desc';
                                                                                                    } else if (((isset($_GET['sortBy'])) || ($_GET['sortBy'] == 'username'))) {
                                                                                                        echo 'asc';
                                                                                                    } ?>&page=<?php echo $page; ?>'>Korisničko ime</a></th>
                    <th scope="col" class="th-sm">Slika</th>
                    <th scope="col" class="th-sm">Akcije</th>
                </tr>
                <?php
                while ($user_row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($user_row);
                ?>
                    <tr>
                        <th scope="row"><?php echo $id; ?></th>
                        <td><?php echo htmlspecialchars($username); ?></td>
                        <td><img style='width:80px; height:auto;' src="img/<?php if (!empty($image)) {
                                                                                echo "user/" . $image;
                                                                            } else {
                                                                                echo "default.jpg";
                                                                            } ?>"></td>
                        <td class="actions">
                            <a class="btn btn-y uerMButton bg-info" data-toggle="modal" data-name="uerM-<?php echo $id; ?>"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg></a>
                            <a class="btn btn-y ueMButton bg-info" data-toggle="modal" data-name="ueM-<?php echo $id; ?>"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2">
                                    <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                                </svg></a>
                            <a class="btn btn-y uiMButton bg-info" data-toggle="modal" data-name="uiM-<?php echo $id; ?>"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-image">
                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                    <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                    <polyline points="21 15 16 10 5 21"></polyline>
                                </svg></a>
                            <a class="btn btn-y udMButton bg-danger" data-toggle="modal" data-name="udM-<?php echo $id; ?>"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-minus"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="23" y1="11" x2="17" y2="11"></line></svg></a>
                        </td>
                    </tr>
                    <?php
                    ?>
                <?php
                }   ?>
            </table>
</div>
        <?php
            pagination($page, $number_of_results, $pagehref);
        } else {
            echo "Nema rezultata";
        }
        ?>
        <!-- edit user role modal -->
        <div class="modal fade" id="list-userRoleModal" tabindex="-1" role="dialog" aria-labelledby="list-userROleModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-m" id="list-userRoleModalLabel">Promijeni ovlast</h5>
                        <button type="button"  class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="list-user-role-message" class='text-success'>
                            <p class="val-msg" id='val-msg-list-user-role'></p>
                        </div>
                        <form method="post" action="" id="list-user-role-form" enctype="multipart/form-data">
                            <input type="hidden" id="list-user-role-submitted">
                            <input type="hidden" value="" id="list-user-role-id">
                            <input type="hidden" value="<?php echo generateToken(); ?>" id="list-user-role-ct">
                            <div class="form-group">
                            <label class="text-m" for="list-user-role">Ovlast</label><br>
                                <select class="form-select custom-select custom-select-sm" id="list-user-role">
                                    <option id="rc1" value=""></option>
                                    <option id="rc2" value=""></option>
                                    <option id="rc3" value=""></option>
                                </select>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <input type="button" id="list-userRoleSubmit" class="btn btn-x" value="U redu">
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- user image edit modal -->
        <div class="modal fade" id="list-userimgModal" tabindex="-1" role="dialog" aria-labelledby="list-userimgModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-m" id="list-userimgModalLabel">Uredi sliku profila</h5>
                        <button type="button"  class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="list-ui-message" class='text-success'>
                            <p class="val-msg" id='val-msg-list-ui'></p>
                        </div>
                        <img width="250px" height="auto" id="list-userimgPreview">
                        <form method="post" action="" enctype="multipart/form-data" id="list-userImg">
                            <input type="hidden" id="list-user-image-submitted">
                            <input type="hidden" value="" id="list-user-img-edit-id">
                            <input type="hidden" value="<?php echo generateToken(); ?>" id="list-user-img-edit-ct">
                            <div class="form-group">
                                <label class="text-m" for="list-user-img">Slika profila</label>
                                <input type="file" class="form-control-file" id="list-user-img">
                                <small id="list-userImgHelp" class="form-text text-z">Datoteka ne smije biti veća od 2MB. Dozvoljeni formati datoteke su png, jpg i jpeg.</small>
                            </div>
                            <input type="button" id="list-userImgSubmit" class="btn btn-x" value="Uredi sliku">
                        </form>
                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn btn-z" id="list-userImgDelSubmit" value="Obriši sliku">
                    </div>
                </div>
            </div>
        </div>

        <!-- edit user modal -->
        <div class="modal fade" id="list-userModal" tabindex="-1" role="dialog" aria-labelledby="list-userModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-m" id="list-usernameModalLabel">Uredi korisnika</h5>
                        <button type="button"  class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="list-user-message" class='text-success'>
                            <p class="val-msg" id='val-msg-list-user'></p>
                        </div>
                        <form method="post" action="" id="list-user" enctype="multipart/form-data">
                            <input type="hidden" id="list-user-name-submitted">
                            <input type="hidden" value="" id="list-user-name-id">
                            <input type="hidden" value="<?php echo generateToken(); ?>" id="list-user-name-ct">
                            <div class="form-group">
                                <label class="text-m" for="usr-list-username">Korisničko ime</label>
                                <input type="text" class="form-control" id="usr-list-username" aria-describedby="list-usernameHelp" placeholder="Upišite korisničko ime" value="">
                                <small id="list-usernameHelp" class="form-text text-z">Korisničko ime ne smije sadržavati manje od 3 ili više od 15 znakova. Dozvoljeni su samo znakovi engleske abecede, brojevi te znak "_". Korisničko ime mora sadržavati barem 1 slovo.</small>
                            </div>
                            <div class="form-group">
                                <label class="text-m" for="usr-list-password">Nova lozinka</label>
                                <input type="password" class="form-control" id="usr-list-password" aria-describedby="list-passwordHelp" placeholder="Upišite novu lozinku">
                                <small id="list-passwordHelp" class="form-text text-z">Lozinka mora sadržavati barem jednu znamenku, jedno veliko slovo te jedno malo slovo. Minimalan broj znakova je 6.</small>
                            </div>
                            <div class="form-group">
                                <label class="text-m" for="usr-list-password2">Ponovite novu lozinku</label>
                                <input type="password" class="form-control" id="usr-list-password2" placeholder="Ponovite novu lozinku">
                            </div>
                    </div>
                    <div class="modal-footer">
                        <input type="button" id="list-userSubmit" class="btn btn-x" value="U redu">
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- delete user modal -->
        <div class="modal fade" id="list-deactivateModal" tabindex="-1" role="dialog" aria-labelledby="list-deactivateModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-m" id="list-deactivateModalLabel">Obriši profil</h5>
                        <button type="button"  class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                    <div id="list-deactivate-message" class='text-success'>
                            <p class="val-msg" id='val-msg-list-deactivate'></p>
                        </div>
                        <h5 id="list-deactivate-confirm" class="text-m d-warning"></h5>
                        <form method="post" action="" id="list-deactivate" enctype="multipart/form-data">
                            <input type="hidden" id="user-list-deactivate-submitted">
                            <input type="hidden" value="" id="user-list-deactivate-id">
                            <input type="hidden" value="<?php echo generateToken(); ?>" id="user-list-deactivate-ct">
                    </div>
                    <div class="modal-footer">
                        <input type="button" id="list-deactivateSubmit" class="btn btn-x" value="Obriši">
                        <button style="display: none;" data-dismiss="modal" id="list-deactivateClose" class="btn btn-x">Zatvori</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    include_once("footer.php");
    show_modal(array('redirectModal'));
    ?>

    <script type="text/javascript" src="../vendor/tinymce/tinymce.min.js"></script>
    <script type="text/javascript" src="../vendor/tinymce/init-tinymce.js"></script>
    <script src="js/users.js"></script>