<?php
$title = "Lekcije";
include_once("header.php");
include_once("class/language.php");
include_once("class/lesson.php");
include_once("class/user.php");
$db = connect();
session_start();

if (isset($_GET['lid'])) {
    $language_id = (int)$_GET['lid'];
}else{
    $_SESSION['redirect_message'] = "Dogodila se pogreška!";
    $_SESSION['show_modal'] = "redirectModal";
    header("Location: languages.php");
}

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    if (check_user_status() == 1) {
        $menu_items['main'] = array('Jezici', 'Korisnici', 'Ovlasti');
        $menu_links['main'] = array('languages.php', 'users.php', 'roles.php');
    } else if (check_user_status() == 2) {
        $menu_items['main'] = array('Jezici');
        $menu_links['main'] = array('languages.php');
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

$menu_items['sub'] = array('Nova lekcija');
$menu_links['sub'] = array('new_lesson.php');
sidemenu($menu_items, $menu_links, "Jezici");
?>

<div id="page-content-wrapper">
    <?php user_header($user_id, $db); ?>
    <div class="container-fluid">
        <h1 class="mt-4">Popis lekcija za jezik</h1>
        <?php
        $lesson = new Lesson($db); 
        $lesson->set_language_id($language_id);

        $stmt = $lesson->getLessons(true,$db);
        $numrows = $stmt->rowCount();

        if ($numrows > 0) {
            $c = 0;
            while ($lesson_row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($lesson_row);
                echo "<br>";
                echo $name;
                echo "<br>";
                echo $description;
                echo "<br><br>";
        ?>
                <a class="btn btn-outline-light" href="questions.php?lid=<?php echo $id ?>" role="button">Pitanja</a><br>
                <a class="btn btn-outline-light-pink" href="edit_lesson.php?id=<?php echo $id ?>" role="button">Uredi</a>
                <a class="btn btn-outline-strong-pink" href="delete_lesson_confirmation.php?id=<?php echo $id ?>" role="button">Obriši</a>

                
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