<?php
$title = "Jezici";
include_once("header.php");
include_once("../class/language.php");
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
    } else if (check_user_status() == 2) {
        $menu_items['main'] = array('Jezici');
        $menu_links['main'] = array('languages.php');
    }
} else {
    $_SESSION['redirect_message'] = "Nije vam dopušten pristup sadržaju!";
    header_redirect();
}

$menu_items['sub'] = array('Novi jezik');
$menu_links['sub'] = array('new_language.php');
$category = "Jezici";
sidemenu($menu_items, $menu_links, $category,$user_id,$user_name,$avi);

require_once '../vendor/htmlpurifier/library/HTMLPurifier.auto.php';
$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

$language = new Language($db);+
$number_of_results=$language->CountLanguages();
?>


<div id="page-content-wrapper">
    <div class="container-fluid">
        <h1 class="mt-4">Popis jezika (<?php echo $number_of_results; ?>)</h1>
        <?php
        if(!isset($_GET['page'])) {
            $page = 1;
          }else{
            $page = $_GET['page'];
          }

        $limitStart = ($page-1)*10;
        $pagehref="languages.php?";

        if (isset($_GET['sortBy']) && isset($_GET['order'])) {
            $sortBy = $_GET['sortBy'];
            $order = $_GET['order'];

            $allowed_orders = array('desc','asc');
            $allowed_sorts = array('id','name','compiler_mode','language_version','editor_mode');

            if(!in_array($sortBy,$allowed_sorts)) {
                $sortBy = "id";
            }

            if(!in_array($order,$allowed_orders)) {
                $order = "asc";
            }

            $stmt = $language->getLanguagesSorted($sortBy, $order,$limitStart);
            $pagehref.="sortBy=" . $sortBy . "&order=" . $order . "&";
        } else {
            $stmt = $language->getLanguages($limitStart);
        }

        $numrows = $stmt->rowCount();

        if ($numrows > 0) {
            ?>
            <div class="table-responsive">
<table class="table table-dark table-hover table-striped">
                <tr>
                <th scope="col" class="th-sm"><a class="text-warning" href='languages.php?sortBy=id&order=<?php if (!(isset($_GET['sortBy'])) || !($_GET['sortBy'] == 'id') || !(isset($_GET['order'])) || (($_GET['sortBy'] == 'id') && ($_GET['order'] == 'asc'))) {
                                                                                                                echo 'desc';
                                                                                                            } else if (((isset($_GET['sortBy'])) || ($_GET['sortBy'] == 'id'))) {
                                                                                                                echo 'asc';
                                                                                                            } ?>&page=<?php echo $page; ?>'>Id</a></th>
                <th scope="col" class="th-sm"><a class="text-warning" href='languages.php?sortBy=name&order=<?php if (!(isset($_GET['sortBy'])) || !($_GET['sortBy'] == 'name') || !(isset($_GET['order'])) || (($_GET['sortBy'] == 'name') && ($_GET['order'] == 'asc'))) {
                                                                                                                echo 'desc';
                                                                                                            } else if (((isset($_GET['sortBy'])) || ($_GET['sortBy'] == 'name'))) {
                                                                                                                echo 'asc';
                                                                                                            } ?>&page=<?php echo $page; ?>'>Naziv</a></th>
                <th scope="col" class="th-sm">Slika</th>
                <th scope="col" class="th-sm">Opis</th>
                <th scope="col" class="th-sm"><a class="text-warning" href='languages.php?sortBy=compiler_mode&order=<?php if (!(isset($_GET['sortBy'])) || !($_GET['sortBy'] == 'compiler_mode') || !(isset($_GET['order'])) || (($_GET['sortBy'] == 'compiler_mode') && ($_GET['order'] == 'asc'))) {
                                                                                                                echo 'desc';
                                                                                                            } else if (((isset($_GET['sortBy'])) || ($_GET['sortBy'] == 'compiler_mode'))) {
                                                                                                                echo 'asc';
                                                                                                            } ?>&page=<?php echo $page; ?>'>Mod kompajlera</a></th>
                <th scope="col" class="th-sm"><a class="text-warning" href='languages.php?sortBy=language_version&order=<?php if (!(isset($_GET['sortBy'])) || !($_GET['sortBy'] == 'language_version') || !(isset($_GET['order'])) || (($_GET['sortBy'] == 'language_version') && ($_GET['order'] == 'asc'))) {
                                                                                                                echo 'desc';
                                                                                                            } else if (((isset($_GET['sortBy'])) || ($_GET['sortBy'] == 'language_version'))) {
                                                                                                                echo 'asc';
                                                                                                            } ?>&page=<?php echo $page; ?>'>Verzija jezika</a></th>
                <th scope="col" class="th-sm"><a class="text-warning" href='languages.php?sortBy=editor_mode&order=<?php if (!(isset($_GET['sortBy'])) || !($_GET['sortBy'] == 'editor_mode') || !(isset($_GET['order'])) || (($_GET['sortBy'] == 'editor_mode') && ($_GET['order'] == 'asc'))) {
                                                                                                                echo 'desc';
                                                                                                            } else if (((isset($_GET['sortBy'])) || ($_GET['sortBy'] == 'editor_mode'))) {
                                                                                                                echo 'asc';
                                                                                                            } ?>&page=<?php echo $page; ?>'>Mod editora</th>
                <th scope="col" class="th-sm">Akcije</th>
                </tr>
                <?php
            while ($language_row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($language_row);
                $clean_desc = $purifier->purify($description);
                ?>
                <tr>
                <th scope="row"><?php echo $id;?></th>
                <td><a class="text-y" href="lessons.php?lid=<?php echo $id ?>"><?php
                 echo htmlspecialchars($name);
                 ?></a></td>
                <td><img style='width:80px; height:auto;' src="img/<?php if (!empty($image)) { echo "lang/" . $image;}else{ echo "default.jpg"; }?>"></td>
                <td><?php echo substr($clean_desc,0,175);?></td>
                <td><?php echo htmlspecialchars($compiler_mode);?></td>
                <td><?php echo $language_version;?></td>
                <td><?php echo htmlspecialchars($editor_mode);?></td>
                <td class="actions"><a class="btn btn-y leMButton bg-info" data-toggle="modal" data-name="leM-<?php echo $id; ?>"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg></a><a class="btn btn-y liMButton bg-info" data-toggle="modal" data-name="liM-<?php echo $id; ?>"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-image"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg></a><a class="btn btn-y ldMButton bg-danger" data-toggle="modal" data-name="ldM-<?php echo $id; ?>"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg></a></td>
                </tr>
                <?php
        ?>
        <?php
            }   ?>
            </table>
</div>
            <?php
            pagination($page,$number_of_results,$pagehref);
        } else {
            echo "Nema rezultata";
        }
        ?>
        <!-- lang image edit modal -->
        <div class="modal fade" id="langImgModal" tabindex="-1" role="dialog" aria-labelledby="langImgModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-m langImgModalLabel">Promijeni fotografiju jezika</h5>
                        <button type="button" class="close close-button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="message-lang-img" class='text-success'>
                            <p class="val-msg" id='val-msg-lang-img'></p>
                        </div>
                        <img width="250px" height="auto" id="langimgPreview">
                        <form method="post" action="" enctype="multipart/form-data" id="langImgForm">
                            <input type="hidden" id="lang-img-submitted">
                            <input type="hidden" value="" id="lang-img-id">
                            <input type="hidden" value="<?php echo generateToken(); ?>" id="lang-img-ct">
                            <div class="form-group">
                                <label class="text-m" for="lang-img">Slika</label>
                                <input type="file" class="form-control-file" id="lang-img">
                                <small id="langimgHelp" class="form-text text-z">Datoteka ne smije biti veća od 2MB. Dozvoljeni formati datoteke su png, jpg i jpeg.</small>
                            </div>
                            <input type="button" class="btn btn-x langImgSubmit" id="langImgSubmit" value="Uredi fotografiju">
                        </form>
                    </div>
                    <div class="modal-footer" id="lang-img-del-footer">
                        <form action="" method="post" id="langImgDel">
                            <input type="hidden" value="" id="lang-img-del-id">
                            <input type="hidden" value="<?php echo generateToken(); ?>" id="lang-img-del-ct">
                            <input type="button" class="btn btn-z" id="langImgDelSubmit" value="Obriši fotografiju">
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- lang edit modal -->
        <div class="modal fade" id="langEditModal" tabindex="-1" role="dialog" aria-labelledby="langEditModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-m langeditModalLabel">Uredi programski jezik</h5>
                        <button type="button" class="close close-button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="message-lang-edit" class='text-success'>
                            <p class="val-msg" id='val-msg-lang-edit'></p>
                        </div>
                        <form method="post" action="" id="langEditForm">
                            <input type="hidden" id="lang-edit-submitted">
                            <input type="hidden" value="" id="lang-edit-id">
                            <input type="hidden" value="<?php echo generateToken(); ?>" id="lang-edit-ct">
                            <div class="form-group">
                                <label class="text-m" for="lang-name">Naziv</label>
                                <input type="text" class="form-control" id="lang-name" aria-describedby="langnameHelp" placeholder="Upišite naziv jezika" value="">
                                <small id="langnameHelp" class="form-text text-z">Naziv ne smije sadržavati više od 25 znakova.</small>
                            </div>
                            <div class="form-group">
                                <label class="text-m" for="lang-desc">Opis</label>
                                <textarea class="form-control tinymce" id="lang-desc" rows="6" aria-describedby="langdescHelp" placeholder="Upišite opis jezika"></textarea>
                                <small id="langdescHelp" class="form-text text-z">Opis mora sadržavati barem 100 znakova.</small>
                            </div>
                            <div class="form-group">
                                <label class="text-m" for="lang-c-mode">Mod kompajlera</label>
                                <input type="text" class="form-control" id="lang-c-mode" aria-describedby="langcmodeHelp" placeholder="Upišite mod kompajlera" value="">
                                <small id="langcmodeHelp" class="form-text text-z">Mod jezika koji će se koristiti pri pozivima na API za kompajliranje koda.</small>
                            </div>
                            <div class="form-group">
                                <label class="text-m" for="lang-l-version">Verzija jezika (kompajler)</label>
                                <input type="text" class="form-control" id="lang-l-version" aria-describedby="langlversionHelp" placeholder="Upišite verziju jezika" value="">
                                <small id="langlversionHelp" class="form-text text-z">Verzija jezika koja će se koristiti pri pozivima na API za kompajliranje koda.</small>
                            </div>
                            <div class="form-group">
                                <label class="text-m" for="lang-e-mode">Mod editora</label>
                                <input type="text" class="form-control" id="lang-e-mode" aria-describedby="langemodeHelp" placeholder="Upišite mod editora" value="">
                                <small id="langemodeHelp" class="form-text text-z">Mod jezika koji će se koristiti pri kreiranju code editora.</small>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn btn-x" id="langEditSubmit" value="Uredi jezik">
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- lang del confirmation modal -->
        <div class="modal fade" id="langDelModal" tabindex="-1" role="dialog" aria-labelledby="langDelModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-m langdelModalLabel">Obriši programski jezik</h5>
                        <button type="button" class="close close-button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="message-lang-del" class='text-success'>
                            <p class="val-msg" id='val-msg-lang-del'></p>
                        </div>
                        <form method="post" action="" id="langDel">
                            <input type="hidden" value="" id="lang-del-id">
                            <input type="hidden" value="<?php echo generateToken(); ?>" id="lang-del-ct">
                            <h5 class="text-m" id="lang-del-confirm-text"></h5>
                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn btn-x" id="langDelSubmit" value="Obriši jezik">
                        <button type="button" id="langDelCancel" data-dismiss="modal" aria-label="Close" class="btn btn-z">Odustani</button>
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
    <script src="js/languages.js"></script>