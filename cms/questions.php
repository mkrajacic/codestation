<?php
$title = "Pitanja";
include_once("header.php");
include_once("class/language.php");
include_once("class/lesson.php");
include_once("class/question.php");
include_once("class/user.php");
$db = connect();
session_start();

if (isset($_GET['lid'])) {
    $lesson_id = (int)$_GET['lid'];
    $lesson = new Lesson($db);
    $lang_id_stmt = $lesson->getLessonById($lesson_id);

    if($lang_id_stmt) {
        $lesson_row = $lang_id_stmt->fetch(PDO::FETCH_ASSOC);
        extract($lesson_row);
        $lang_id = $language_id;
    }
}else{
    $_SESSION['redirect_message'] = "Dogodila se pogreška!";
    $_SESSION['show_modal'] = "redirectModal";
    header("Location: languages.php");
}

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    if (check_user_status() == 1) {
        $menu_items['main'] = array('Jezici', 'Lekcije', 'Korisnici', 'Ovlasti');
        $menu_links['main'] = array('languages.php','lessons.php?lid=' . $lang_id, 'users.php', 'roles.php');
    } else if (check_user_status() == 2) {
        $menu_items['main'] = array('Jezici','Lekcije');
        $menu_links['main'] = array('languages.php','lessons.php?lid=' . $lang_id);
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

$menu_items['sub'] = array('Novo pitanje');
$menu_links['sub'] = array('new_question.php');
sidemenu($menu_items, $menu_links, "Jezici");
?>

<div id="page-content-wrapper">
    <?php user_header($user_id, $db); ?>
    <div class="container-fluid">
        <h1 class="mt-4">Popis pitanja za lekciju</h1>
        <?php
        $question = new Question($db); 
        $question->set_lesson_id($lesson_id);

        $stmt = $question->getQuestions(true,$db);
        $numrows = $stmt->rowCount();

        if ($numrows > 0) {
            $c = 0;
            while ($question_row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($question_row);
                echo "<br>";
                echo $question;
                echo "<br>";
                echo $lesson_id;
                echo "<br>";
                echo $question_type;
                echo "<br><br>";
        ?>
                <a class="btn btn-outline-light" href="answers.php?qid=<?php echo $id ?>" role="button">Odgovori</a><br>
                <a class="btn btn-outline-light-pink" href="edit_question.php?id=<?php echo $id ?>" role="button">Uredi</a>
                <a class="btn btn-outline-strong-pink" href="delete_question_confirmation.php?id=<?php echo $id ?>" role="button">Obriši</a>

                
        <?php
                $c++;
            }
        }else{
            echo "Nema rezultata";
        }
        ?>
    </div>

    <?php
    include_once("footer.php");
    ?>

    <script>

    </script>