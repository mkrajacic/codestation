<?php
$title = "Pitanja";
include_once("header.php");
include_once("../class/language.php");
include_once("../class/lesson.php");
include_once("../class/question.php");
include_once("../class/question_type.php");
include_once("../class/user.php");
$db = connect();
session_start();

if (isset($_GET['lid'])) {
    $lesson_id = (int)$_GET['lid'];
    $lesson = new Lesson($db);
    $lang_id_stmt = $lesson->getLessonById($lesson_id);

    if ($lang_id_stmt) {
        $lesson_row = $lang_id_stmt->fetch(PDO::FETCH_ASSOC);
        extract($lesson_row);
        $lang_id = $language_id;
        $lname = htmlspecialchars(($name));
    }
} else {
    $_SESSION['redirect_message'] = "Greška pri učitavanju sadržaja!";
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
        $menu_items['main'] = array('Jezici', 'Lekcije', 'Korisnici');
        $menu_links['main'] = array('languages.php', 'lessons.php?lid=' . $lang_id, 'users.php');
    } else if (check_user_status() == 2) {
        $menu_items['main'] = array('Jezici', 'Lekcije');
        $menu_links['main'] = array('languages.php', 'lessons.php?lid=' . $lang_id);
    }
} else {
    $_SESSION['redirect_message'] = "Nije vam dopušten pristup sadržaju!";
        header_redirect();
}

$menu_items['sub'] = array('Novo pitanje');
$menu_links['sub'] = array('new_question.php?lid=' . $lesson_id);
$category = "Lekcije";
sidemenu($menu_items, $menu_links, $category, $user_id, $user_name, $avi);

$question = new Question($db);
$question->set_lesson_id($lesson_id);

$number_of_results = $question->CountQuestions();
?>

<div id="page-content-wrapper">
    <div class="container-fluid">
        <h1 class="mt-4">Popis pitanja za lekciju <?php echo $lname . " (" . $number_of_results . ")"; ?></h1>
        <?php
        if (!isset($_GET['page'])) {
            $page = 1;
        } else {
            $page = $_GET['page'];
        }

        $limitStart = ($page - 1) * 10;
        $pagehref = "questions.php?lid=" . $lesson_id . "&";

        if (isset($_GET['sortBy']) && isset($_GET['order'])) {
            $sortBy = $_GET['sortBy'];
            $order = $_GET['order'];

            $allowed_orders = array('desc', 'asc');
            $allowed_sorts = array('id', 'question', 'question_type');

            if (!in_array($sortBy, $allowed_sorts)) {
                $sortBy = "id";
            }

            if (!in_array($order, $allowed_orders)) {
                $order = "asc";
            }

            $stmt = $question->getQuestionsSorted(true, $sortBy, $order, $limitStart);
            $pagehref .= "sortBy=" . $sortBy . "&order=" . $order . "&";
        } else {
            $stmt = $question->getQuestions(true, $limitStart);
        }

        $numrows = $stmt->rowCount();

        if ($numrows > 0) {
        ?>
            <div class="table-responsive">
<table class="table table-dark table-hover table-striped">
                <tr>
                    <th scope="col" class="th-sm"><a class="text-warning" href='questions.php?lid=<?php echo $lesson_id; ?>&sortBy=id&order=<?php if (!(isset($_GET['sortBy'])) || !($_GET['sortBy'] == 'id') || !(isset($_GET['order'])) || (($_GET['sortBy'] == 'id') && ($_GET['order'] == 'asc'))) {
                                                                                                                                    echo 'desc';
                                                                                                                                } else if (((isset($_GET['sortBy'])) || ($_GET['sortBy'] == 'id'))) {
                                                                                                                                    echo 'asc';
                                                                                                                                } ?>&page=<?php echo $page; ?>'>Id</a></th>
                    <th scope="col" class="th-sm"><a class="text-warning" href='questions.php?lid=<?php echo $lesson_id; ?>&sortBy=question&order=<?php if (!(isset($_GET['sortBy'])) || !($_GET['sortBy'] == 'question') || !(isset($_GET['order'])) || (($_GET['sortBy'] == 'question') && ($_GET['order'] == 'asc'))) {
                                                                                                                                        echo 'desc';
                                                                                                                                    } else if (((isset($_GET['sortBy'])) || ($_GET['sortBy'] == 'question'))) {
                                                                                                                                        echo 'asc';
                                                                                                                                    } ?>&page=<?php echo $page; ?>'>Pitanje</a></th>
                    <th scope="col" class="th-sm"><a class="text-warning" href='questions.php?lid=<?php echo $lesson_id; ?>&sortBy=question_type&order=<?php if (!(isset($_GET['sortBy'])) || !($_GET['sortBy'] == 'question_type') || !(isset($_GET['order'])) || (($_GET['sortBy'] == 'question_type') && ($_GET['order'] == 'asc'))) {
                                                                                                                                                echo 'desc';
                                                                                                                                            } else if (((isset($_GET['sortBy'])) && ($_GET['sortBy'] == 'question_type'))) {
                                                                                                                                                echo 'asc';
                                                                                                                                            } ?>&page=<?php echo $page; ?>'>Vrsta pitanja</a></th>
                    <th scope="col" class="th-sm">Akcije</th>
                </tr>
                <?php
                while ($question_row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($question_row);

                    $type = new QuestionType($db);
                    $type_stmt = $type->getTypeById($question_type);

                    if ($type_stmt) {
                        $type_row = $type_stmt->fetch(PDO::FETCH_ASSOC);
                        $type_name = $type_row['type'];
                    }
                ?>
                    <tr>
                        <th scope="row"><?php echo $id; ?></th>
                        <td><a class="text-y" href="answers.php?qid=<?php echo $id ?>"><?php
                         echo htmlspecialchars($question); 
                         ?></a></td>
                        <td><?php echo $type_name; ?> (id: <?php echo $question_type; ?>)</td>
                        <td class="actions"><a class="btn btn-y qeMButton bg-info" data-toggle="modal" data-name="qeM-<?php echo $id; ?>"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2">
                                    <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                                </svg></a><a class="btn btn-y qdMButton bg-danger" data-toggle="modal" data-name="qdM-<?php echo $id; ?>"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash">
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
        <!-- quest edit modal -->
        <div class="modal fade" id="questEditModal" tabindex="-1" role="dialog" aria-labelledby="questEditModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-m questeditModalLabel">Uredi pitanje</h5>
                        <button type="button" class="close close-button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="message-quest-edit" class='text-success'>
                            <p class="val-msg" id='val-msg-quest-edit'></p>
                        </div>
                        <form method="post" action="" id="questEdit">
                            <input type="hidden" id="quest-edit-submitted">
                            <input type="hidden" value="" id="quest-edit-id">
                            <input type="hidden" value="<?php echo generateToken(); ?>" id="quest-edit-ct">
                            <div class="form-group">
                                <label class="text-m" for="question">Pitanje</label>
                                <textarea class="form-control" id="question" aria-describedby="questnameHelp" placeholder="Upišite tekst pitanja"></textarea>
                                <small id="questnameHelp" class="form-text text-z">Tekst pitanja ne smije sadržavati manje od 10 riječi.</small>
                            </div>
                            <div class="form-group">
                                <label class="text-m" for="quest-type">Vrsta pitanja</label>
                                <select class="form-select custom-select custom-select-sm" id="quest-type">
                                    <option value="0" selected>Vrsta pitanja...</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="text-m" for="quest-less">Lekcija</label>
                                <select class="form-select custom-select custom-select-sm" id="quest-less">
                                    <option value="0" selected>Lekcija...</option>
                                </select>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn btn-x" id="questEditSubmit" value="Uredi pitanje">
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- quest del confirmation modal -->
        <div class="modal fade" id="questDelModal" tabindex="-1" role="dialog" aria-labelledby="questDelModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-m questdelModalLabel">Obriši pitanje</h5>
                        <button type="button" class="close close-button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="message-quest-del" class='text-success'>
                            <p class="val-msg" id='val-msg-quest-del'></p>
                        </div>
                        <form method="post" action="" id="questEdit">
                            <input type="hidden" value="" id="quest-del-id">
                            <input type="hidden" value="<?php echo generateToken(); ?>" id="quest-del-ct">
                            <h5 class="text-m" id="quest-del-confirm-text"></h5>
                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn btn-x" id="questDelSubmit" value="Obriši pitanje">
                        <button type="button" id="questDelCancel" data-dismiss="modal" aria-label="Close" class="btn btn-z">Odustani</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    include_once("footer.php");
    ?>

    <script src="js/questions.js"></script>