<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
<?php
$title = "Lekcije";
include_once("header.php");
include_once("../class/language.php");
include_once("../class/lesson.php");
include_once("../class/user.php");
include_once("../class/user_progress_lesson.php");
$db = connect();
session_start();

if (isset($_GET['lid']))
    $language_id = (int)$_GET['lid'];
else {
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

$language = new Language($db);
$language->set_id($language_id);

$lang_stmt = $language->getLanguages();
$lang_numrows = $lang_stmt->rowCount();

$lang_name_stmt = $language->getLanguageById($language_id);

if ($lang_name_stmt) {
    $lang_name_row = $lang_name_stmt->fetch(PDO::FETCH_ASSOC);
    $lang_name = $lang_name_row['name'];
    $lang_id = $language_id;
}

$lesson_progress = new LessonProgress($db);
$lesson_progress->set_user_id($user_id);
$progress_stmt = $lesson_progress->getLessonProgress();

$passed_lessons = array();

if ($progress_stmt) {

    while ($less_prog = $progress_stmt->fetch(PDO::FETCH_ASSOC)) {
        array_push($passed_lessons, $less_prog['lesson_id']);
    }
}

$lesson = new Lesson($db);
$lesson->set_language_id($language_id);

$stmt = $lesson->getLessons(true);
$numrows = $stmt->rowCount();
?>
<div id="wrapper-list">
    <div class="back-button">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-short smallDivs" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M12 8a.5.5 0 0 1-.5.5H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5a.5.5 0 0 1 .5.5z" />
        </svg>
    </div>
    <div id="left-button">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="smallDivs">
            <path d="M16.2426 6.34317L14.8284 4.92896L7.75739 12L14.8285 19.0711L16.2427 17.6569L10.5858 12L16.2426 6.34317Z" fill="currentColor" />
        </svg>
    </div>
    <div id="right-button">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="smallDivs">
            <path d="M10.5858 6.34317L12 4.92896L19.0711 12L12 19.0711L10.5858 17.6569L16.2427 12L10.5858 6.34317Z" fill="currentColor" />
        </svg>
    </div>
    <div class="profile-button">
        <?php user_header($user_id, htmlspecialchars(strip_tags($user_name)), $db, $avi); ?>
    </div>
    <div id="upper-list">
        <div id="upper-practice" class="practiceButton" data-name="l-<?php echo $language_id; ?>">
            <div class="container-text">Vježba</div><svg class="smallDivs" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-terminal">
                <polyline points="4 17 10 11 4 5"></polyline>
                <line x1="12" y1="19" x2="20" y2="19"></line>
            </svg>
        </div>
        <div class="container-text">Popis lekcija za jezik <?php echo (isset($lang_name)) ? htmlspecialchars(strip_tags($lang_name)) : '' ?></div>
    </div>
    <div id="outer">
        <div id="cards" class="lesson-cards">
            <?php $c = 0; ?>
            <?php if ($numrows > 0) : ?>
                <div class="slick-wrapper">
                    <div id="slick1">
                        <?php while ($lesson_row = $stmt->fetch(PDO::FETCH_ASSOC)) : ?>
                            <?php extract($lesson_row);
                            if (($precondition == 0) || (in_array($precondition, $passed_lessons))) : ?>
                                <div class="slide-item">
                                    <div class='card text-center mb-3 list-card lesson-card' style="width: 15rem; height:10rem;">
                                        <?php if ((in_array($id, $passed_lessons))) : ?>
                                            <div class='upper-card passed lesson-upper'>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-check2-circle" viewBox="0 0 16 16">
                                                    <path d="M2.5 8a5.5 5.5 0 0 1 8.25-4.764.5.5 0 0 0 .5-.866A6.5 6.5 0 1 0 14.5 8a.5.5 0 0 0-1 0 5.5 5.5 0 1 1-11 0z" />
                                                    <path d="M15.354 3.354a.5.5 0 0 0-.708-.708L8 9.293 5.354 6.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l7-7z" />
                                                </svg>
                                            </div>
                                        <?php endif; ?>
                                        <div class='card-body'>
                                            <h5 class='card-title'>
                                                <?php echo htmlspecialchars(strip_tags($name)); ?>
                                            </h5>
                                        </div>
                                        <div class='card-footer'>
                                            <a class="btn btn-outline-light" href="lesson.php?lid=<?php echo $id ?>" role="button">Detaljnije</a>
                                        </div>
                                    </div>
                                </div>
                            <?php else : ?>
                                <div class="slide-item">
                                    <div class='card text-center mb-3 list-card lesson-card' style="width: 15rem; height:10rem; opacity:0.5;">
                                        <div class='card-body'>
                                            <h5 class='card-title'>
                                                <?php echo htmlspecialchars(strip_tags($name)); ?>
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                        <?php
                            endif;
                            $c++;
                        endwhile; ?>
                    </div>
                </div>
            <?php else : ?>
                <div class='details-desc'><span>Nema rezultata</span></div>
            <?php endif; ?>
        </div>
    </div>
</div>
<input type="hidden" id="lc" value="<?php echo $c; ?>">

<?php
include_once("footer.php");
?>

<script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
<script src="js/slider.js"></script>