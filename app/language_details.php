<?php
$title = "Detaljnije o jeziku";
include_once("header.php");
include_once("../class/language.php");
include_once("../class/lesson.php");
include_once("../class/user.php");
include_once("../class/user_progress_lesson.php");
$db = connect();
session_start();

if (isset($_GET['lid'])) {
    $language_id = (int)$_GET['lid'];
} else {
    $_SESSION['redirect_message'] = "Greška pri učitavanju sadržaja!";
    header_redirect();
}

require_once '../vendor/htmlpurifier/library/HTMLPurifier.auto.php';
$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

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

$language = new Language($db);
$language->set_id($language_id);

$language_stmt = $language->getLanguageById($language_id);

if ($language_stmt) {
    $language_row = $language_stmt->fetch(PDO::FETCH_ASSOC);
    extract($language_row);
    $language_name = $name;
} else {
    header_redirect();
}

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
    <div id="upper-list" class="languages">
        <div id="upper-practice" class="lessonButton" data-name="l-<?php echo $language_id; ?>">
            <div class="container-text">Lekcije</div><svg class="upper-icon" width="25" height="25" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M9 1H1V9H9V6H11V20H15V23H23V15H15V18H13V6H15V9H23V1H15V4H9V1ZM21 3H17V7H21V3ZM17 17H21V21H17V17Z" />
            </svg>
        </div>
        <div class="container-text languages"><?php echo htmlspecialchars(strip_tags($language_name)); ?></div>
    </div>
    <div id="outer-details">
        <div id="cards" class="details">
            <div class="details-desc"><span><?php echo $purifier->purify($description); ?></span></div>
        </div>
    </div>
</div>

<?php
include_once("footer.php");
?>