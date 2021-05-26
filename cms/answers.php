<?php
$title = "Odgovori";
include_once("header.php");
include_once("class/language.php");
include_once("class/lesson.php");
include_once("class/question.php");
include_once("class/answer.php");
include_once("class/user.php");
$db = connect();
session_start();

if (isset($_GET['qid'])) {
    $question_id = (int)$_GET['qid'];
    $question = new Question($db);
    $quest_id_stmt = $question->getQuestionById($question_id);

    if($quest_id_stmt) {
        $question_row = $quest_id_stmt->fetch(PDO::FETCH_ASSOC);
        extract($question_row);
        $les_id = $lesson_id;
    }
}else{
    $_SESSION['redirect_message'] = "Dogodila se pogreška!";
    $_SESSION['show_modal'] = "redirectModal";
    header("Location: languages.php");
}

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    if (check_user_status() == 1) {
        $menu_items['main'] = array('Jezici', 'Pitanja', 'Korisnici', 'Ovlasti');
        $menu_links['main'] = array('languages.php','questions.php?lid=' . $les_id, 'users.php', 'roles.php');
    } else if (check_user_status() == 2) {
        $menu_items['main'] = array('Jezici','Pitanja');
        $menu_links['main'] = array('languages.php','questions.php?lid=' . $les_id);
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

$menu_items['sub'] = array('Novi odgovor');
$menu_links['sub'] = array('new_answer.php');
sidemenu($menu_items, $menu_links, "Jezici");
?>

<div id="page-content-wrapper">
    <?php user_header($user_id, $db); ?>
    <div class="container-fluid">
        <h1 class="mt-4">Popis odgovora za pitanje</h1>
        <?php
        $answer = new Answer($db); 
        $answer->set_question_id($question_id);

        $stmt = $answer->getAnswers(true,$db);
        $numrows = $stmt->rowCount();

        if ($numrows > 0) {
            $c = 0;
            while ($answer_row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($answer_row);
                echo "<br>";
                echo $answer;
                echo "<br>";
                echo $question_id;
                echo "<br>";
                echo $correct;
                echo "<br><br>";
        ?>
                <a class="btn btn-outline-light-pink" href="edit_answer.php?id=<?php echo $id ?>" role="button">Uredi</a>
                <a class="btn btn-outline-strong-pink" href="delete_answer_confirmation.php?id=<?php echo $id ?>" role="button">Obriši</a>

                
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