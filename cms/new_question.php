<?php
$title = "Novo pitanje";
include_once("header.php");
include_once("class/question.php");
include_once("class/question_type.php");
$db = connect();

if (isset($_GET['lid'])) {
    $lesson_id = (int)$_GET['lid'];
} else {
    $_SESSION['redirect_message'] = "Dogodila se pogreška!";
    $_SESSION['show_modal'] = "redirectModal";
    header("Location: languages.php");
}

if (isset($_POST['submitted'])) {
    $form_fields = array('quest-name', 'quest-type');
    $form_names = array('Pitanje', 'Vrsta pitanja');

    $errors = validate($form_fields, $form_names, $db, null, "Question");
}

$menu_items['main'] = array('Jezici', 'Pitanja', 'Korisnici', 'Ovlasti');
$menu_links['main'] = array('languages.php', 'questions.php?lid=' . $lesson_id, 'users.php', 'roles.php');
$menu_items['sub'] = array();
$menu_links['sub'] = array();
sidemenu($menu_items, $menu_links, "Pitanja");
?>
<div id="page-content-wrapper">
    <div class="container-fluid">
        <h1 class="mt-4">Dodaj pitanje</h1>
        <form method="POST" action="">
            <?php
            if (isset($errors)) {

                if (sizeof($errors) > 0) {

                    foreach ($errors as $err) {
            ?>
                        <div class="invalid-feedback" style="display:block; font-size:16px">
                            <?php
                            echo $err;
                            ?>
                        </div>
                    <?php
                    }
                } else {
                    $quest_name = $_POST['quest-name'];
                    $quest_type = $_POST['quest-type'];

                    $quest = new Question($db);
                    $quest->set_question($quest_name);
                    $quest->set_question_type($quest_type);
                    $quest->set_lesson_id($lesson_id);

                    if ($quest->createQuestion()) {
                    ?>
                        <div class="valid-feedback" style="display:block; font-size:16px">
                            Pitanje uspješno dodano!
                        </div>
            <?php
                    }
                }
            }
            ?>
            <input type="hidden" name="submitted" id="submitted">
            <div class="form-group">
                <label for="quest-name">Pitanje</label>
                <input type="text" class="form-control" id="quest-name" name="quest-name" aria-describedby="questnameHelp" placeholder="Upišite pitanje" <?php if (isset($_POST['quest-name'])) { ?> value="<?php echo $_POST['quest-name'] ?>" <?php } ?>>
                <small id="questnameHelp" class="form-text text-pink">Tekst pitanja ne smije sadržavati manje od 10 znakova.</small>
            </div>
            <div class="form-group">
                <label for="quest-type">Vrsta pitanja</label>
                <select class="form-select custom-select custom-select-sm" name="quest-type" id="quest-type">
                    <option value="0" selected>Vrsta pitanja...</option>
                    <?php
                    $type = new QuestionType($db);
                    $type_stmt = $type->getTypes($db);
                    $type_numrows = $type_stmt->rowCount();

                    if ($type_numrows > 0) {
                        while ($type_row = $type_stmt->fetch(PDO::FETCH_ASSOC)) {   ?>
                            <option value="<?php echo $type_row['id'] ?>"><?php echo $type_row['type'] ?></option>
                    <?php
                        }
                    }
                    ?>
                </select>
                <small id="questtypeHelp" class="form-text text-pink">Vrsta pitanja.</small>
            </div>
            <button type="submit" class="btn btn-pink">Dodaj pitanje</button>
            <button type="button" onclick="window.history.go(-1);" class="btn btn-outline-light-pink">Odustani</button>
        </form>
    </div>

    <?php
    include_once("footer.php");
    ?>

    <script>
        // $('#quest-type').on('change',function() {
        //     //alert($('#quest-type option:selected').val());
        //     var question_type = $('#quest-type option:selected').val();

        //     switch(question_type) {
        //         case 1:

        //     }
        // });
    </script>