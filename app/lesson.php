<?php
$title = "Detaljnije o lekciji";
include_once("header.php");
include_once("../class/language.php");
include_once("../class/lesson.php");
include_once("../class/user.php");
include_once("../class/user_progress_lesson.php");
$db = connect();
session_start();

if (isset($_GET['lid'])) {
    $lesson_id = (int)$_GET['lid'];
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
} else {
    $_SESSION['redirect_message'] = "Nemate pravo pristupa sadržaju!";
    header_redirect();
}

require_once '../vendor/htmlpurifier/library/HTMLPurifier.auto.php';
$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

$lesson = new Lesson($db);
$lesson->set_id($lesson_id);

$less_name_stmt = $lesson->getLessonById($lesson_id);

if ($less_name_stmt) {
    $less_name_row = $less_name_stmt->fetch(PDO::FETCH_ASSOC);
    extract($less_name_row);
    $less_name = $name;
    $prec = $precondition;
} else {
    header_redirect();
}

$passed = false;
$passed_precondition = false;
$lesson_progress = new LessonProgress($db);
$lesson_progress->set_lesson_id($lesson_id);
$lesson_progress->set_user_id($user_id);

if ($lesson_progress->getProgressByLesson())
    $passed = true;

$lesson_progress->set_lesson_id($prec);
if ($lesson_progress->getProgressByLesson())
    $passed_precondition = true;

if (($passed) || ($precondition == 0) || ($passed_precondition)) :
?>
    <div id="wrapper-list">
        <div class="back-button">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-short smallDivs" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M12 8a.5.5 0 0 1-.5.5H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5a.5.5 0 0 1 .5.5z" />
            </svg>
        </div>
        <div class="profile-button">
            <?php user_header($user_id, htmlspecialchars(strip_tags($user_name)), $db, $avi); ?>
        </div>
        <div id="upper-list">
            <div id="upper-practice" class="startButton" data-name="l-<?php echo $id; ?>">
                <div class="container-text">Start</div><svg class="upper-icon" width="25" height="25" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path class="start-outer" fill-rule="evenodd" clip-rule="evenodd" d="M12 21C16.9706 21 21 16.9706 21 12C21 7.02944 16.9706 3 12 3C7.02944 3 3 7.02944 3 12C3 16.9706 7.02944 21 12 21ZM12 23C18.0751 23 23 18.0751 23 12C23 5.92487 18.0751 1 12 1C5.92487 1 1 5.92487 1 12C1 18.0751 5.92487 23 12 23Z" />
                    <path d="M16 12L10 16.3301V7.66987L16 12Z" fill="currentColor" />
                </svg>
            </div>
            <div class="container-text"><?php echo htmlspecialchars(strip_tags($less_name)); ?></div>
        </div>
        <div id="outer-details">
            <div id="cards" class="details">
                <div class="details-desc"><span><?php echo $purifier->purify($description); ?></span></div>
            </div>
        </div>
    </div>

<?php
    include_once("footer.php");
else :
    header_redirect();
endif;
?>