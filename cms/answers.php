<?php
$title = "Odgovori";
include_once("header.php");
include_once("../class/language.php");
include_once("../class/lesson.php");
include_once("../class/question.php");
include_once("../class/answer.php");
include_once("../class/coding_answer.php");
include_once("../class/user.php");
$db = connect();
session_start();

$auth = isAuthorized();
if (($auth == 0) || ($auth == 3)) {
    $_SESSION['redirect_message'] = "Nije vam dopušten pristup sadržaju!";
    header_redirect();
}

if (isset($_GET['qid'])) {
    $question_id = (int)$_GET['qid'];
    $question = new Question($db);
    $quest_id_stmt = $question->getQuestionById($question_id);

    if ($quest_id_stmt) {
        $question_row = $quest_id_stmt->fetch(PDO::FETCH_ASSOC);
        extract($question_row);
        $les_id = $lesson_id;
        $question_name = htmlspecialchars($question);
        $question_type = $question_type;
    }
} else {
    $_SESSION['redirect_message'] = "Greška pri učitavanju sadržaja!";
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
        $menu_items['main'] = array('Jezici', 'Pitanja', 'Korisnici');
        $menu_links['main'] = array('languages.php', 'questions.php?lid=' . $les_id, 'users.php');
    } else if (check_user_status() == 2) {
        $menu_items['main'] = array('Jezici', 'Pitanja');
        $menu_links['main'] = array('languages.php', 'questions.php?lid=' . $les_id);
    }
} else {
    $_SESSION['redirect_message'] = "Nije vam dopušten pristup sadržaju!";
    header_redirect();
}

$menu_items['sub'] = array('Novi odgovor');
$menu_links['sub'] = array('new_answer.php?qid=' . $question_id);
$category = "Pitanja";
sidemenu($menu_items, $menu_links, $category, $user_id, $user_name, $avi);

$answer = new CodingAnswer($db);
$answer->set_question_id($question_id);

$number_of_results = $answer->CountAnswers();
?>

<div id="page-content-wrapper">
    <div class="container-fluid">
        <?php
        if ($question_type == 4) {

        ?>
            <h1 class="mt-4">Popis odgovora za pitanje <?php echo $question_name . " (" . $number_of_results . ")"; ?></h1>
            <?php
            if (!isset($_GET['page'])) {
                $page = 1;
            } else {
                $page = $_GET['page'];
            }

            $limitStart = ($page - 1) * 10;
            $pagehref = "answers.php?qid=" . $question_id . "&";

            if (isset($_GET['sortBy']) && isset($_GET['order'])) {
                $sortBy = $_GET['sortBy'];
                $order = $_GET['order'];

                $allowed_orders = array('desc', 'asc');
                $allowed_sorts = array('id', 'code', 'display');

                if (!in_array($sortBy, $allowed_sorts)) {
                    $sortBy = "id";
                }

                if (!in_array($order, $allowed_orders)) {
                    $order = "asc";
                }

                $stmt = $answer->getAnswersSorted(true, $sortBy, $order, $limitStart);
                $pagehref .= "sortBy=" . $sortBy . "&order=" . $order . "&";
            } else {
                $stmt = $answer->getAnswers(true, $limitStart);
            }

            $numrows = $stmt->rowCount();

            if ($numrows > 0) {
            ?>
                <div class="table-responsive">
<table class="table table-dark table-hover table-striped">
                    <tr>
                        <th scope="col" class="th-sm"><a class="text-warning" href='answers.php?qid=<?php echo $question_id; ?>&sortBy=id&order=<?php if (!(isset($_GET['sortBy'])) || !($_GET['sortBy'] == 'id') || !(isset($_GET['order'])) || (($_GET['sortBy'] == 'id') && ($_GET['order'] == 'asc'))) {
                                                                                                                                        echo 'desc';
                                                                                                                                    } else if (((isset($_GET['sortBy'])) || ($_GET['sortBy'] == 'id'))) {
                                                                                                                                        echo 'asc';
                                                                                                                                    } ?>&page=<?php echo $page; ?>'>Id</a></th>
                        <th scope="col" class="th-sm"><a class="text-warning" href='answers.php?qid=<?php echo $question_id; ?>&sortBy=code&order=<?php if (!(isset($_GET['sortBy'])) || !($_GET['sortBy'] == 'code') || !(isset($_GET['order'])) || (($_GET['sortBy'] == 'code') && ($_GET['order'] == 'asc'))) {
                                                                                                                                        echo 'desc';
                                                                                                                                    } else if (((isset($_GET['sortBy'])) || ($_GET['sortBy'] == 'code'))) {
                                                                                                                                        echo 'asc';
                                                                                                                                    } ?>&page=<?php echo $page; ?>'>Kod odgovora</a></th>
                        <th scope="col" class="th-sm"><a class="text-warning" href='answers.php?qid=<?php echo $question_id; ?>&sortBy=display&order=<?php if (!(isset($_GET['sortBy'])) || !($_GET['sortBy'] == 'display') || !(isset($_GET['order'])) || (($_GET['sortBy'] == 'display') && ($_GET['order'] == 'asc'))) {
                                                                                                                                            echo 'desc';
                                                                                                                                        } else if (((isset($_GET['sortBy'])) && ($_GET['sortBy'] == 'display'))) {
                                                                                                                                            echo 'asc';
                                                                                                                                        } ?>&page=<?php echo $page; ?>'>Prikaz odgovora</a></th>
                        <th scope="col" class="th-sm">Akcije</th>
                    </tr>
                    <?php
                    while ($answer_row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        extract($answer_row);
                    ?>
                        <tr>
                            <th scope="row"><?php echo $id; ?></th>
                            <td><?php echo htmlspecialchars($code); ?></td>
                            <td><?php echo htmlspecialchars($display); ?></td>
                            <td class="actions"><a class="btn btn-y caeMButton bg-info" data-toggle="modal" data-name="caeM-<?php echo $id; ?>"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2">
                                        <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                                    </svg></a><a class="btn btn-y cadMButton bg-danger" data-toggle="modal" data-name="cadM-<?php echo $id; ?>"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash">
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
        } else {
            $answer = new Answer($db);
            $answer->set_question_id($question_id);

            $number_of_results = $answer->CountAnswers();
            ?>
            <h1 class="mt-4">Popis odgovora za pitanje <?php echo $question_name . " (" . $number_of_results . ")"; ?></h1>
            <?php
            if (!isset($_GET['page'])) {
                $page = 1;
            } else {
                $page = $_GET['page'];
            }

            $limitStart = ($page - 1) * 10;
            $pagehref = "answers.php?qid=" . $question_id . "&";

            if (isset($_GET['sortBy']) && isset($_GET['order'])) {
                $sortBy = $_GET['sortBy'];
                $order = $_GET['order'];

                $allowed_orders = array('desc', 'asc');
                $allowed_sorts = array('id', 'answer', 'correct');

                if (!in_array($sortBy, $allowed_sorts)) {
                    $sortBy = "id";
                }

                if (!in_array($order, $allowed_orders)) {
                    $order = "asc";
                }

                $stmt = $answer->getAnswersSorted(true, $sortBy, $order, $limitStart);
                $pagehref .= "sortBy=" . $sortBy . "&order=" . $order . "&";
            } else {
                $stmt = $answer->getAnswers(true, $limitStart);
            }

            $numrows = $stmt->rowCount();

            if ($numrows > 0) { ?>
                <div class="table-responsive">
<table class="table table-dark table-hover table-striped">
                    <tr>
                        <th scope="col" class="th-sm"><a class="text-warning" href='answers.php?qid=<?php echo $question_id; ?>&sortBy=id&order=<?php if (!(isset($_GET['sortBy'])) || !($_GET['sortBy'] == 'id') || !(isset($_GET['order'])) || (($_GET['sortBy'] == 'id') && ($_GET['order'] == 'asc'))) {
                                                                                                                                        echo 'desc';
                                                                                                                                    } else if (((isset($_GET['sortBy'])) || ($_GET['sortBy'] == 'id'))) {
                                                                                                                                        echo 'asc';
                                                                                                                                    } ?>&page=<?php echo $page; ?>'>Id</a></th>
                        <th scope="col" class="th-sm"><a class="text-warning" href='answers.php?qid=<?php echo $question_id; ?>&sortBy=answer&order=<?php if (!(isset($_GET['sortBy'])) || !($_GET['sortBy'] == 'answer') || !(isset($_GET['order'])) || (($_GET['sortBy'] == 'answer') && ($_GET['order'] == 'asc'))) {
                                                                                                                                            echo 'desc';
                                                                                                                                        } else if (((isset($_GET['sortBy'])) || ($_GET['sortBy'] == 'answer'))) {
                                                                                                                                            echo 'asc';
                                                                                                                                        } ?>&page=<?php echo $page; ?>'>Odgovor</a></th>
                        <th scope="col" class="th-sm"><a class="text-warning" href='answers.php?qid=<?php echo $question_id; ?>&sortBy=correct&order=<?php if (!(isset($_GET['sortBy'])) || !($_GET['sortBy'] == 'correct') || !(isset($_GET['order'])) || (($_GET['sortBy'] == 'correct') && ($_GET['order'] == 'asc'))) {
                                                                                                                                            echo 'desc';
                                                                                                                                        } else if (((isset($_GET['sortBy'])) && ($_GET['sortBy'] == 'correct'))) {
                                                                                                                                            echo 'asc';
                                                                                                                                        } ?>&page=<?php echo $page; ?>'>Točan</a></th>
                        <th scope="col" class="th-sm">Akcije</th>
                    </tr>
                    <?php
                    while ($answer_row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        extract($answer_row); ?>
                        <tr>
                            <th scope="row"><?php echo $id; ?></th>
                            <td><?php
                             echo htmlspecialchars($answer); 
                             ?></td>
                            <td><?php if ($correct) {
                                    echo "Točan";
                                } else {
                                    echo "Netočan";
                                } ?></td>
                            <td class="actions"><a class="btn btn-y aeMButton bg-info" data-toggle="modal" data-name="aeM-<?php echo $id; ?>"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2">
                                        <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                                    </svg></a><a class="btn btn-y adMButton bg-danger" data-toggle="modal" data-name="adM-<?php echo $id; ?>"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash">
                                        <polyline points="3 6 5 6 21 6"></polyline>
                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                    </svg></a></td>
                        </tr>
                        <?php
                        ?>
                    <?php
                    } ?>
                </table>
</div>
        <?php
                pagination($page, $number_of_results, $pagehref);
            } else {
                echo "Nema rezultata";
            }
        }
        ?>
                <!--codemirror js-->
                <script src="../vendor/codemirror/lib/codemirror.js"></script>
        <!--codemirror css-->
        <link rel="stylesheet" href="../vendor/codemirror/lib/codemirror.css">
        <!--odabir jezika-->
        <script src="../vendor/codemirror/mode/<?php echo $_SESSION['selected_language_editor_mode'] ?>/<?php echo $_SESSION['selected_language_editor_mode'] ?>.js"></script>
        </script>
        <!--odabir teme-->
        <link rel="stylesheet" href="../vendor/codemirror/theme/nord.css">
        <!-- autoclose -->
        <script src="../vendor/codemirror/addon/edit/closebrackets.js"></script>
        <!-- placeholder -->
        <script src="../vendor/codemirror/addon/display/placeholder.js"></script>

        <!-- non code answer edit modal -->
        <div class="modal fade" id="answrEditModal" tabindex="-1" role="dialog" aria-labelledby="answrEditModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-m answreditModalLabel">Uredi odgovor</h5>
                        <button type="button" class="close close-button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="message-answr-edit" class='text-success'>
                            <p class="val-msg" id='val-msg-answr-edit'></p>
                        </div>
                        <form method="post" action="" id="answrEdit">
                            <input type="hidden" id="answr-edit-submitted">
                            <input type="hidden" value="" id="answr-edit-id">
                            <input type="hidden" value="<?php echo generateToken(); ?>" id="answr-edit-ct">
                            <div class="form-group">
                                <label class="text-m" for="answer">Odgovor</label>
                                <textarea class="form-control" id="answer" aria-describedby="answrnameHelp" placeholder="Upišite tekst odgovora"></textarea>
                            </div>
                            <div class="form-group">
                                <label class="text-m" for="answr-cor">Točan</label>
                                <select class="form-select custom-select custom-select-sm" id="answr-cor">
                                    <option value="1" selected>Točan</option>
                                    <option value="0" selected>Netočan</option>
                                </select>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn btn-x" id="answrEditSubmit" value="Uredi odgovor">
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- non code answer del confirmation modal -->
        <div class="modal fade" id="answrDelModal" tabindex="-1" role="dialog" aria-labelledby="answrDelModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-m answrdelModalLabel">Obriši odgovor</h5>
                        <button type="button" class="close close-button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="message-answr-del" class='text-success'>
                            <p class="val-msg" id='val-msg-answr-del'></p>
                        </div>
                        <form method="post" action="" id="answrDel">
                            <input type="hidden" value="" id="answr-del-id">
                            <input type="hidden" value="<?php echo generateToken(); ?>" id="answr-del-ct">
                            <h5 class="text-m" id="answr-del-confirm-text"></h5>
                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn btn-x" id="answrDelSubmit" value="Obriši odgovor">
                        <button type="button" id="answrDelCancel" data-dismiss="modal" aria-label="Close" class="btn btn-z">Odustani</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- code answer edit modal -->
        <div class="modal fade" id="canswrEditModal" tabindex="-1" role="dialog" aria-labelledby="canswrEditModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-m canswreditModalLabel">Uredi odgovor</h5>
                        <button type="button" class="close close-button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="message-canswr-edit" class='text-success'>
                            <p class="val-msg" id='val-msg-canswr-edit'></p>
                        </div>
                        <form method="post" action="" id="canswrEdit">
                            <input type="hidden" id="canswr-edit-submitted">
                            <input type="hidden" value="" id="canswr-edit-id">
                            <input type="hidden" value="<?php echo generateToken(); ?>" id="canswr-edit-ct">
                            <div class="form-group">
                                <label class="text-m" for="canswer">Kod</label>
                                <textarea placeholder="Upišite kod" class="form-control" rows="9" id="canswer"></textarea>
                                <small id="canswrnameHelp" class="form-text text-z">Kod ne smije sadržavati manje od 3 znaka.</small>
                            </div>
                            <div class="form-group">
                                <label class="text-m" for="cdisplay">Prikaz</label>
                                <textarea placeholder="Upišite tekst prikaza" class="form-control" rows="9" id="cdisplay"></textarea>
                                <small id="canswrnameHelp" class="form-text text-z">Rezultat koji daje upisani kod kada se izvrši</small>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn btn-x" id="canswrEditSubmit" value="Uredi odgovor">
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- code answer del confirmation modal -->
        <div class="modal fade" id="canswrDelModal" tabindex="-1" role="dialog" aria-labelledby="canswrDelModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-m canswrdelModalLabel">Obriši odgovor</h5>
                        <button type="button" class="close close-button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="message-canswr-del" class='text-success'>
                            <p class="val-msg" id='val-msg-canswr-del'></p>
                        </div>
                        <form method="post" action="" id="canswrDel">
                            <input type="hidden" value="" id="canswr-del-id">
                            <input type="hidden" value="<?php echo generateToken(); ?>" id="canswr-del-ct">
                            <h5 class="text-m" id="canswr-del-confirm-text"></h5>
                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn btn-x" id="canswrDelSubmit" value="Obriši odgovor">
                        <button type="button" id="canswrDelCancel" data-dismiss="modal" aria-label="Close" class="btn btn-z">Odustani</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    include_once("footer.php");
    ?>
    <script>
        var code_answer = CodeMirror.fromTextArea(document.getElementById("canswer"), {
        lineNumbers: false,
        tabSize: 2,
        mode: '<?php echo $_SESSION['selected_language_editor_mode']; ?>',
        theme: "nord",
        autoCloseBrackets: true});

        var answer_display = CodeMirror.fromTextArea(document.getElementById("cdisplay"), {
        lineNumbers: false,
        tabSize: 2,
        mode: '<?php echo $_SESSION['selected_language_editor_mode']; ?>',
        theme: "nord",
        autoCloseBrackets: true
    });
    </script>
    <script src="js/answers.js"></script>