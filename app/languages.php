<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
<?php
$title = "Jezici";
include_once("header.php");
include_once("../class/language.php");
include_once("../class/user.php");
include_once("../class/user_progress_language.php");
include_once("../class/user_progress_lesson.php");
$db = connect();
session_start();

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

$language_progress = new LanguageProgress($db);
$language_progress->set_user_id($user_id);
$progress_stmt = $language_progress->getLanguageProgress();

$passed_languages = array();

if ($progress_stmt) {

    while ($lang_prog = $progress_stmt->fetch(PDO::FETCH_ASSOC)) {
        array_push($passed_languages, $lang_prog['language_id']);
    }
}

$language = new Language($db);

$stmt = $language->getLanguages();
$numrows = $stmt->rowCount();

$alphas = range('A', 'Z');
?>

<div id="wrapper-list">
    <?php if ($numrows > 0) { ?>
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
    <?php }  ?>
    <div class="profile-button">
        <?php user_header($user_id, htmlspecialchars(strip_tags($user_name)), $db); ?>
        <img id="navbarDropdown" data-toggle="dropdown" aria-expanded="false" class="center avi" width="50" height="auto" src="<?php if (!is_null($avi)) {
                                                                                                                                    echo "../cms/img/user/" . $avi;
                                                                                                                                } else {
                                                                                                                                    echo "img/default.jpg";
                                                                                                                                } ?>">
    </div>
    <div id="upper-list" class="languages">
        <div class="container-text languages">Popis programskih jezika</div>
    </div>
    <div id="outer">
        <div id="cards">
            <?php
            $c = 0;
            if ($numrows > 0) { ?>
                <div class="slick-wrapper">
                    <div id="slick2">
                        <?php
                        while ($language_row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                            extract($language_row);
                        ?>
                            <div class="slide-item">
                                <div class='card text-center mb-3 list-card' style='width: 18rem; height:15rem;'>
                                    <?php
                                    if ((in_array($id, $passed_languages))) {
                                    ?>
                                        <div class='upper-card passed languages'>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-check2-circle" viewBox="0 0 16 16">
                                                <path d="M2.5 8a5.5 5.5 0 0 1 8.25-4.764.5.5 0 0 0 .5-.866A6.5 6.5 0 1 0 14.5 8a.5.5 0 0 0-1 0 5.5 5.5 0 1 1-11 0z" />
                                                <path d="M15.354 3.354a.5.5 0 0 0-.708-.708L8 9.293 5.354 6.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l7-7z" />
                                            </svg>
                                        </div>
                                        <?php
                                    }

                                    $lesson_progress = new LessonProgress($db);
                                    $lesson_progress->set_user_id($user_id);

                                    $check_less_progress = $lesson_progress->compareLessonProgressByLanguage($id);

                                    if ($check_less_progress) {
                                        $compare = $check_less_progress->fetch(PDO::FETCH_ASSOC);
                                        if (($compare['passed_lessons'] >= 1) && ($compare['passed_lessons'] < $compare['lessons_in_language'])) { ?>
                                            <div class='upper-card in-progress languages'>
                                                <span>U tijeku</span>
                                            </div>
                                    <?php
                                        }
                                    }

                                    ?>
                                    <div class='card-body'>
                                        <h5 class='card-title'>
                                            <?php echo htmlspecialchars(strip_tags($name)); ?>
                                        </h5>
                                        <img class="card-img" src="<?php if (!is_null($image)) {
                                                                        echo "../cms/img/lang/" . $image;
                                                                    } else {
                                                                        echo "../cms/img/default.jpg";
                                                                    } ?>">
                                    </div>
                                    <div class='card-footer'>
                                        <a class="btn btn-outline-light" href="language_details.php?lid=<?php echo $id ?>" role="button">Detalji</a>
                                        <a class="btn btn-outline-light" href="lessons.php?lid=<?php echo $id ?>" role="button">Lekcije</a>
                                    </div>
                                </div>
                            </div>
                        <?php
                            $c++;
                        }   ?>
                    </div>
                </div>
            <?php
            } else {
                echo "<div class='details-desc'><span>Nema rezultata</span></div>";
            }
            ?>
        </div>
    </div>
</div>
<input type="hidden" id="lc" value="<?php echo $c; ?>">

<?php
include_once("footer.php");
?>

<script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
<script src="js/slider.js"></script>