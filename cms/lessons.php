<?php
$title = "Lekcije";
include_once("header.php");
include_once("../class/language.php");
include_once("../class/lesson.php");
include_once("../class/user.php");
$db = connect();
session_start();

if (isset($_GET['lid'])) {
    $language_id = (int)$_GET['lid'];
} else {
    $_SESSION['redirect_message'] = "Dogodila se pogreška!";
    header_redirect();
}

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

$menu_items['sub'] = array('Nova lekcija');
$menu_links['sub'] = array('new_lesson.php?lid=' . $language_id);
$category = "Jezici";
sidemenu($menu_items, $menu_links, $category, $user_id, $user_name, $avi);

require_once '../vendor/htmlpurifier/library/HTMLPurifier.auto.php';
$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

$language = new Language($db);

$lang_name_stmt = $language->getLanguageById($language_id);

if ($lang_name_stmt) {
    $lang_name_row = $lang_name_stmt->fetch(PDO::FETCH_ASSOC);
    $lang_name = $lang_name_row['name'];
    $lang_id = $language_id;
    $_SESSION['selected_language_name'] = $lang_name;
    $_SESSION['selected_language_editor_mode'] = $lang_name_row['editor_mode'];
} else {
    $lang_name = "";
}

$lesson = new Lesson($db);
$lesson->set_language_id($language_id);
$number_of_results = $lesson->CountLessons();
?>

<div id="page-content-wrapper">
    <div class="container-fluid">
        <h1 class="mt-4">Popis lekcija za jezik <?php echo $lang_name . " (" . $number_of_results . ")"; ?></h1>
        <?php
        if (!isset($_GET['page'])) {
            $page = 1;
        } else {
            $page = $_GET['page'];
        }

        $limitStart = ($page - 1) * 10;
        $pagehref = "lessons.php?lid=" . $language_id . "&";

        if (isset($_GET['sortBy']) && isset($_GET['order'])) {
            $sortBy = $_GET['sortBy'];
            $order = $_GET['order'];

            $allowed_orders = array('desc','asc');
            $allowed_sorts = array('id','name','precondition');

            if(!in_array($sortBy,$allowed_sorts)) {
                $sortBy = "id";
            }

            if(!in_array($order,$allowed_orders)) {
                $order = "asc";
            }

            $less_stmt = $lesson->getLessonsSorted(true, $sortBy, $order, $limitStart);
            $pagehref .= "sortBy=" . $sortBy . "&order=" . $order . "&";
        } else {
            $less_stmt = $lesson->getLessons(true, $limitStart);
        }

        $less_numrows = $less_stmt->rowCount();

        if ($less_numrows > 0) { ?>
            <div class="table-responsive">
<table class="table table-dark table-hover table-striped">
                <tr>
                    <th scope="col" class="th-sm"><a class="text-warning" href='lessons.php?lid=<?php echo $language_id; ?>&sortBy=id&order=<?php if (!(isset($_GET['sortBy'])) || !($_GET['sortBy'] == 'id') || !(isset($_GET['order'])) || (($_GET['sortBy'] == 'id') && ($_GET['order'] == 'asc'))) {
                                                                                                                                    echo 'desc';
                                                                                                                                } else if (((isset($_GET['sortBy'])) || ($_GET['sortBy'] == 'id'))) {
                                                                                                                                    echo 'asc';
                                                                                                                                } ?>&page=<?php echo $page; ?>'>Id</a></th>
                    <th scope="col" class="th-sm"><a class="text-warning" href='lessons.php?lid=<?php echo $language_id; ?>&sortBy=name&order=<?php if (!(isset($_GET['sortBy'])) || !($_GET['sortBy'] == 'name') || !(isset($_GET['order'])) || (($_GET['sortBy'] == 'name') && ($_GET['order'] == 'asc'))) {
                                                                                                                                    echo 'desc';
                                                                                                                                } else if (((isset($_GET['sortBy'])) || ($_GET['sortBy'] == 'name'))) {
                                                                                                                                    echo 'asc';
                                                                                                                                } ?>&page=<?php echo $page; ?>'>Naziv</a></th>
                    <th scope="col" class="th-sm">Opis</th>
                    <th scope="col" class="th-sm"><a class="text-warning" href='lessons.php?lid=<?php echo $language_id; ?>&sortBy=precondition&order=<?php if (!(isset($_GET['sortBy'])) || !($_GET['sortBy'] == 'precondition') || !(isset($_GET['order'])) || (($_GET['sortBy'] == 'precondition') && ($_GET['order'] == 'asc'))) {
                                                                                                                                            echo 'desc';
                                                                                                                                        } else if (((isset($_GET['sortBy'])) && ($_GET['sortBy'] == 'precondition'))) {
                                                                                                                                            echo 'asc';
                                                                                                                                        } ?>&page=<?php echo $page; ?>'>Preduvijet</a></th>
                    <th scope="col" class="th-sm">Akcije</th>
                </tr>
                <?php

                while ($lesson_row = $less_stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($lesson_row);

                    $prec = $precondition;

                    if ($prec != 0) {
                        $lesson = new Lesson($db);
                        $prec_stmt = $lesson->getLessonById($prec);

                        if ($prec_stmt) {
                            $prec_row = $prec_stmt->fetch(PDO::FETCH_ASSOC);
                            $prec_name = $prec_row['name'];
                        }
                    } else {
                        $prec_name = "Nema preduvijeta";
                    }

                    $clean_desc = $purifier->purify($description);
                ?>
                    <tr>
                        <th scope="row"><?php echo $id; ?></th>
                        <td><a class="text-y" href="questions.php?lid=<?php echo $id ?>"><?php echo htmlspecialchars($name); ?></a></td>
                        <td><?php echo substr($clean_desc, 0, 175); ?></td>
                        <td><?php echo htmlspecialchars($prec_name); ?> (id: <?php echo $precondition; ?>)</td>
                        <td class="actions"><a class="btn btn-y leMButton bg-info" data-toggle="modal" data-name="leM-<?php echo $id; ?>"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2">
                                    <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                                </svg></a><a class="btn btn-y ldMButton bg-danger" data-toggle="modal" data-name="ldM-<?php echo $id; ?>"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash">
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                </svg></a></td>
                    </tr>
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
        <!-- less edit modal -->
        <div class="modal fade" id="lessEditModal" tabindex="-1" role="dialog" aria-labelledby="lessEditModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-m lesseditModalLabel">Uredi lekciju</h5>
                        <button type="button" class="close close-button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="message-less-edit" class='text-success'>
                            <p class="val-msg" id='val-msg-less-edit'></p>
                        </div>
                        <form method="post" action="" id="lessEdit">
                            <input type="hidden" id="less-edit-submitted">
                            <input type="hidden" value="" id="less-edit-id">
                            <input type="hidden" value="<?php echo generateToken(); ?>" id="less-edit-ct">
                            <div class="form-group">
                                <label class="text-m" for="less-name">Naziv</label>
                                <input type="text" class="form-control" id="less-name" aria-describedby="lessnameHelp" placeholder="Upišite naziv jezika" value="">
                                <small id="lessnameHelp" class="form-text text-z">Naziv ne smije sadržavati više od 100 riječi.</small>
                            </div>
                            <div class="form-group">
                                <label class="text-m" for="less-desc">Opis</label>
                                <textarea class="form-control tinymce" id="less-desc" rows="6" aria-describedby="lessdescHelp" placeholder="Upišite opis jezika"></textarea>
                                <small id="lessdescHelp" class="form-text text-z">Opis mora sadržavati barem 100 riječi.</small>
                            </div>
                            <div class="form-group">
                                <label class="text-m" for="less-lang">Jezik</label><br>
                                <select class="form-select custom-select custom-select-sm" id="less-lang">
                                    <option value="0">Programski jezik...</option>
                                </select>
                                <small id="lesslangHelp" class="form-text text-z">Programski jezik za koji je namijenjena lekcija.</small>
                            </div>
                            <div class="form-group">
                                <label class="text-m" for="less-precondition">Lekcija preduvijet</label><br>
                                <select class="form-select custom-select custom-select-sm" id="less-precondition">
                                    <option value="0">Nema preduvijeta</option>
                                </select>
                                <small id="lessprecHelp" class="form-text text-z">Lekcija koju korisnik mora proći da bi mu ova lekcija bila dostupna.</small>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn btn-x" id="lessEditSubmit" value="Uredi lekciju">
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- less del confirmation modal -->
        <div class="modal fade" id="lessDelModal" tabindex="-1" role="dialog" aria-labelledby="lessDelModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-m lessdelModalLabel">Obriši lekciju</h5>
                        <button type="button" class="close close-button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="message-less-del" class='text-success'>
                            <p class="val-msg" id='val-msg-less-del'></p>
                        </div>
                        <form method="post" action="" id="lessDelConfirm">
                            <input type="hidden" value="" id="less-del-id">
                            <input type="hidden" value="<?php echo generateToken(); ?>" id="less-del-ct">
                            <h5 class="text-m" id="less-del-confirm-text"></h5>
                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn btn-x" id="lessDelSubmit" value="Obriši lekciju">
                        <button type="button" id="lessDelCancel" data-dismiss="modal" aria-label="Close" class="btn btn-z">Odustani</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <?php
    include_once("footer.php");
    ?>

    <script type="text/javascript" src="../vendor/tinymce/tinymce.min.js"></script>
    <script type="text/javascript" src="../vendor/tinymce/init-tinymce.js"></script>
    <script src="js/lessons.js"></script>