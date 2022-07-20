<?php
$title = "Kviz";
include_once("../functions.php");
include_once("../class/language.php");
include_once("../class/lesson.php");
include_once("../class/question.php");
include_once("../class/answer.php");
include_once("../class/coding_answer.php");
include_once("../class/user.php");
include_once("../class/user_progress_lesson.php");
include_once("../class/user_progress_question.php");
include_once("../class/user_progress_language.php");
$db = connect();
session_start();

if (isset($_SESSION['user_id'])) {

    $user_id = $_SESSION['user_id'];
    $user = new User($db);
    if ($stmt = $user->getUserById($user_id)) {

        $lesson_progress = new LessonProgress($db);
        $lesson_progress->set_user_id($user_id);

        $language_progress = new LanguageProgress($db);
        $language_progress->set_user_id($user_id);

        $question_progress = new QuestionProgress($db);
        $question_progress->set_user_id($user_id);

    }
} else {
    $_SESSION['redirect_message'] = "Nemate pravo pristupa sadržaju!";
    header_redirect();
}

if (!isset($_SESSION['question_ids'])) {
    $_SESSION['redirect_message'] = "Greška pri učitavanju sadržaja!";
    header_redirect();
} else {
    $index = $_SESSION['index'];

    if(isset($_SESSION['question_ids'][$index])) {
        $question_id = $_SESSION['question_ids'][$index];
    }else{
        $question_id=0;
    }
}

if ($index == (sizeof($_SESSION['question_ids']))) {
    require_once('quiz_result.php');
}else{
    if (!($_SESSION['lifes'] >= 1)) {
        $_SESSION['redirect_message'] = "Potrošili ste sve živote! Pokušajte ponovo!";
        header_redirect("failed.php");
    }
}

$question = new Question($db);
$question->set_id($question_id);

$question_stmt = $question->getQuestionById($question_id);

if ($question_stmt) {
    $question_row = $question_stmt->fetch(PDO::FETCH_ASSOC);
    extract($question_row);
    $type = $question_type;
    $q = $question;
    $l = $lesson_id;
}

$lesson = new Lesson($db);
$lesson->set_id($l);

$lesson_stmt = $lesson->getLessonById($l);

if ($lesson_stmt) {
    $lesson_row = $lesson_stmt->fetch(PDO::FETCH_ASSOC);
    $language_id = $lesson_row['language_id'];
}

$nmbrofQuestions = sizeof($_SESSION['question_ids']);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Web application for code learning">
    <meta name="author" content="MK">
    <title><?php echo $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <link href="css/style.css" rel="stylesheet">
</head>
<div id="wrapper">
    <div id="left">
        <div id="menu">
            <!-- back -->
            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-arrow-return-left backButton" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M14.5 1.5a.5.5 0 0 1 .5.5v4.8a2.5 2.5 0 0 1-2.5 2.5H2.707l3.347 3.346a.5.5 0 0 1-.708.708l-4.2-4.2a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L2.707 8.3H12.5A1.5 1.5 0 0 0 14 6.8V2a.5.5 0 0 1 .5-.5z" />
            </svg>
            <!-- send answer -->
            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" id="answr-submit-<?php echo $index ?>" class="bi bi-check-square answr-submit" viewBox="0 0 16 16">
                <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z" />
                <path d="M10.97 4.97a.75.75 0 0 1 1.071 1.05l-3.992 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.235.235 0 0 1 .02-.022z" />
            </svg>
            <!-- next -->
            <svg style="display: none;" xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-arrow-right-square nextButton" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm4.5 5.5a.5.5 0 0 0 0 1h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H4.5z" />
            </svg>
            <!-- skip -->
            <svg xmlns="http://www.w3.org/2000/svg" id="skipButton-<?php echo $index ?>" width="16" height="16" fill="currentColor" class="bi bi-skip-forward skipButton" viewBox="0 0 16 16">
                <path d="M15.5 3.5a.5.5 0 0 1 .5.5v8a.5.5 0 0 1-1 0V8.752l-6.267 3.636c-.52.302-1.233-.043-1.233-.696v-2.94l-6.267 3.636C.713 12.69 0 12.345 0 11.692V4.308c0-.653.713-.998 1.233-.696L7.5 7.248v-2.94c0-.653.713-.998 1.233-.696L15 7.248V4a.5.5 0 0 1 .5-.5zM1 4.633v6.734L6.804 8 1 4.633zm7.5 0v6.734L14.304 8 8.5 4.633z" />
            </svg>
        </div>
    </div>
    <div id="upper">
        <div class="container-text">
            <?php echo htmlentities($q,ENT_NOQUOTES); ?>
        </div>
    </div>
    <div id="outer">
    <div id="upper-right">
        <?php
        echo $_SESSION['lifes']; ?>
    </div>
        <div id="upper-left">
            <?php echo $index + 1 ?>/<?php echo sizeof($_SESSION['question_ids']); ?>
        </div>
            <?php
            if ($type != 4) {
                $answer = new Answer($db);
                $answer->set_question_id($question_id);
                $answer_stmt = $answer->getAnswers(true);

                $numrows = $answer_stmt->rowCount();


                if ($numrows > 0) {
                    if ($type == 1 || $type == 2) {
            ?>
                <div class="answers type12 <?php if($type==1){ echo "one"; } ?>">
                        <?php
                        $i = 1;
                        while ($answers = $answer_stmt->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                            <div class="answer">
                                <div class="form-group" id="answer-<?php echo $i ?>">
                                    <div class="custom-control custom-switch">
                                        <input class="custom-control-input choice" type="checkbox" id="choice-<?php echo $i ?>" name="choice[]" value="<?php echo $answers['id'] ?>">
                                        <label class="custom-control-label" for="choice-<?php echo $i ?>"><?php echo htmlspecialchars(($answers['answer'])) ?></label>
                                    </div>
                                </div>
                            </div>
                        <?php
                            $i++;
                        }
                        ?>
                </div>
                    <?php
                    } else if ($type == 3) {
                    ?>
                    <div class="answers type3">
                        <input type="text" class="form-control" id="answer" name="answer" aria-describedby="answerHelp" placeholder="Upišite odgovor"><br>
                    </div>
                    <?php
                    } ?>
    </div>
    <div id="bottom" style="display: none;">
        <div class="container-text bottom-text">
        <p></p><br>
        <b></b>
        <em></em>
        </div>
    </div>
<?php
                } else {
                    $_SESSION['index']++;
                    echo "<script>location.replace('quiz.php');</script>";
                }
            }   ?>

<?php

if ($type == 4) {

    $coding_answer = new CodingAnswer($db);
    $coding_answer->set_question_id($question_id);
    $coding_answer_stmt = $coding_answer->getAnswers(true);

    $coding_answer_numrows = $coding_answer_stmt->rowCount();

    $language = new Language($db);
    $language->set_id($language_id);

    $language_stmt = $language->getLanguageById($language_id);

    if ($language_stmt) {
        $language_row = $language_stmt->fetch(PDO::FETCH_ASSOC);
        $compiler_mode = htmlspecialchars(strip_tags($language_row['compiler_mode']));
        $language_version = htmlspecialchars(strip_tags($language_row['language_version']));
        $editor_mode = htmlspecialchars(strip_tags($language_row['editor_mode']));
    }

    if ($coding_answer_numrows > 0) {
?>
        <!--codemirror js-->
        <script src="../vendor/codemirror/lib/codemirror.js"></script>
        <!--codemirror css-->
        <link rel="stylesheet" href="../vendor/codemirror/lib/codemirror.css">
        <!--odabir jezika-->
        <script src="../vendor/codemirror/mode/<?php echo $editor_mode ?>/<?php echo $editor_mode ?>.js"></script>
        </script>
        <!--odabir teme-->
        <link rel="stylesheet" href="../vendor/codemirror/theme/ayu-mirage.css">
        <!-- autoclose -->
        <script src="../vendor/codemirror/addon/edit/closebrackets.js"></script>
        <!-- placeholder -->
        <script src="../vendor/codemirror/addon/display/placeholder.js"></script>
<div class="answers type4">
        <textarea id="code-answer" placeholder="Upišite kod..."></textarea><br>
</div>
<style>
    #answers {
        width: 100vw;
        margin: 6vw 15vw;
    }
</style>
<!-- stvaranje editora -->
<script>
    var code_answer = CodeMirror.fromTextArea(document.getElementById("code-answer"), {
        lineNumbers: true,
        tabSize: 2,
        mode: '<?php echo $editor_mode ?>',
        theme: "ayu-mirage",
        autoCloseBrackets: true
    });
</script>
<?php
    } else {
        $_SESSION['index']++;
        echo "<script>location.replace('quiz.php');</script>";
    }
?>
</div>
<div id="bottom" style="display: none;">
    <div class="container-text bottom-text code-output">
        <p></p><br>
        <b></b>
        <em></em>
    </div>
</div>
<?php
}
    quiz_mobile_nav($index);
?>

<script src="../vendor/jquery/jquery.min.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="js/selection.js"></script>

<script>
    function animateCorrect() {
        $('#quiz-mobile').css('animation','left-border-correct 1.2s forwards');
        $('#bottom, #upper-left, #upper').css('animation','border-correct 1.2s forwards');
        $('#left').css('animation','left-border-correct 1.2s forwards');
        $('#wrapper').css('animation-name','correct');
        $('#wrapper').css('animation-duration','2s');
    }

    function animateIncorrect() {
        $('#quiz-mobile').css('animation','left-border-incorrect 1.2s forwards');
        $('#bottom, #upper-left, #upper').css('animation','border-incorrect 1.2s forwards');
        $('#left').css('animation','left-border-incorrect 1.2s forwards');
        $('#wrapper').css('animation-name','loselife');
        $('#wrapper').css('animation-duration','2s');
    }

    function animatePartial() {
        $('#quiz-mobile').css('animation','left-border-partial 1.2s forwards');
        $('#bottom, #upper-left, #upper').css('animation','border-partial 1.2s forwards');
        $('#left').css('animation','left-border-partial 1.2s forwards');
        $('#wrapper').css('animation-name','partial');
        $('#wrapper').css('animation-duration','2s');
    }

    function appendCorrectAnswers(correct_answers) {
        for (var i = 0; i < correct_answers.length; i++) {
            $('.bottom-text em').append(correct_answers[i]);
                if(i+1<correct_answers.length) {
                        $('.bottom-text em').append(", ");
                }
            }
    }

    function prependOrText(elem,text,prepend) {
        $('#bottom').prop("style", "display:block;");
        if(prepend==false) {
            switch(elem) {
                case "p":
                    $('.bottom-text p').text(text);
                    break;
                case "em":
                    $('.bottom-text em').text(text);
                    break;
                case "b":
                    $('.bottom-text b').text(text);
                    break;
            }
        }else{
            switch(elem) {
                case "p":
                    $('.bottom-text p').prepend(text);
                    break;
                case "em":
                    $('.bottom-text em').prepend(text);
                    break;
                case "b":
                    $('.bottom-text b').prepend(text);
                    break;
            }
        }
    }

    function showError(xhr) {
        $('#bottom').prop("style", "display:block;");
        prependOrText("p","Dogodila se pogreška!",false);
        prependOrText("em","",false);
        animateIncorrect();
    }

    function switchButtons(response) {
        if (response.status != 0) {
            $('.bi-arrow-right-square').prop("style", "display:block;");
            $('.bi-skip-forward').prop("style", "display:none; pointer-events: none;");
        }
    }

    function nextButton(response) {
        $('.nextButton').click(function() {
                if (response.status != 0) {
                    window.location.href = "quiz.php";
                }
        });
    }

    $('.answr-submit').one('click', function() {

        var question_type = <?php echo json_encode($type, JSON_HEX_TAG); ?>;
        var clickedBtnID = $(this).attr('id');
        var index = clickedBtnID.split("-").pop();
        var question_id = <?php echo $question_id ?>;

        if (question_type == "1" || question_type == "2") {
            var answer = [];

            $('.choice:checked').each(function() {
                answer.push($(this).val());
            });

            var serial_arr = answer.join("<#>");

            var fd = new FormData();

            fd.append('index', index);
            fd.append('answer', serial_arr);
            fd.append('type', question_type);
            fd.append('qid', question_id);

            $.ajax({
                url: 'store_answer.php',
                type: 'post',
                cache: false,
                data: fd,
                contentType: false,
                processData: false,
                dataType: "JSON",
                success: function(response) {

                    switchButtons(response);
                    nextButton(response);

                    if (question_type == "1") {

                        if (response.status == 1) {

                            if (response.correct == 1) {
                                prependOrText("p","Odgovor je točan!",false);
                                animateCorrect();
                            } else if (response.correct == 0) {
                                prependOrText("p","Odgovor je netočan!",false);
                                prependOrText("b","Točan odgovor je:",false);
                                prependOrText("em",response.correct_answer,false);
                                animateIncorrect();
                            }
                        } else if (response.status == 0) {
                            prependOrText("p","Molimo odaberite odgovore!",false);
                        }

                    } else if (question_type == "2") {

                        if (response.status == 1) {

                            var correct_answers = response.correct;

                            if (response.answerStatus == 1) {
                                prependOrText("p","Odgovor je točan!",false);
                                animateCorrect();
                            } else if ((response.answerStatus == 2)) {
                                prependOrText("p","Odgovor je djelomično točan!",false);
                                prependOrText("b","Točni odgovori su:",false);
                                appendCorrectAnswers(correct_answers);
                                animatePartial();
                            }else if (response.answerStatus == 3) {
                                prependOrText("p","Odgovor je netočan!",false);
                                prependOrText("b","Točni odgovori su:",false);
                                appendCorrectAnswers(correct_answers);
                                animateIncorrect();
                            }
                        } else if (response.status == 0) {
                            prependOrText("p","Molimo odaberite odgovor(e)!",false);
                            animatePartial();
                        }
                    }
                },
                error: function(xhr) {
                    showError(xhr);
                }
            });
        } else if (question_type == "3") {
            var answer = $('#answer').val();

            var fd = new FormData();

            fd.append('index', index);
            fd.append('answer', answer);
            fd.append('type', question_type);
            fd.append('qid', question_id);

            $.ajax({
                url: 'store_answer.php',
                type: 'post',
                cache: false,
                data: fd,
                contentType: false,
                processData: false,
                dataType: "JSON",
                success: function(response) {

                    switchButtons(response);
                    nextButton(response);

                    if (response.status == 1) {

                        var correct_answers = response.correct_answers;

                        if (response.correct == 1) {
                            prependOrText("p","Odgovor je točan!",false);
                            animateCorrect();
                        } else if (response.correct == 0) {
                            prependOrText("p","Odgovor je netočan!",false);
                            prependOrText("b","Mogući točni odgovor(i) su:",false);
                            appendCorrectAnswers(correct_answers);
                            animateIncorrect();
                        }
                    } else if (response.status == 0) {
                        prependOrText("p","Molimo odaberite odgovor(e)!",false);
                        animatePartial();
                    }
                },
                error: function(xhr) {
                    showError(xhr);
                }
            });
        } else if (question_type == "4") {

            const proxy = "http://localhost:8080/";
            const url = "https://api.jdoodle.com/v1/execute/";
            var script = code_answer.getValue();

            var data = {
                clientId: "a64d2cacf27e12f7a9ae76c999c30cef",
                clientSecret: "7bbcc5f655c6c998a0156223713f402f10eeda6adcbfb3e6077d6dd4faa6952d",
                language: '<?php if (isset($compiler_mode)) {echo $compiler_mode;}  ?>',
                script: script,
                versionIndex: '<?php if (isset($language_version)) {echo $language_version;}  ?>'
            };

            var fd = new FormData();

            fd.append('index', index);
            fd.append('answer', script);
            fd.append('type', question_type);
            fd.append('qid', question_id);

            $.ajax({
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                'type': 'POST',
                'url': proxy + url,
                'data': JSON.stringify(data),
                'dataType': 'json',
                success: function(e) {
                    prependOrText("b","Rezultat koda:",false);
                    prependOrText("em",e.output,false);
                    fd.append('output', e.output);
                },
                error: function(e) {
                    prependOrText("b","Greška:",false);
                    prependOrText("em",e.memory,false);
                    fd.append('output', e.memory);
                }
            }).then(function(e) {

                setTimeout(function() {

                    $.ajax({
                        url: 'store_answer.php',
                        type: 'post',
                        cache: false,
                        data: fd,
                        contentType: false,
                        processData: false,
                        dataType: "JSON",
                        success: function(response) {

                            switchButtons(response);
                            nextButton(response);

                            if (response.status == 1) {
                                if (response.correct == 1) {
                                    prependOrText("p","Odgovor je točan!",false);
                                    animateCorrect();
                                } else if (response.correct == 0.5) {
                                    prependOrText("p","Odgovor je djelomično točan!",false);
                                    prependOrText("b","Ispravan kod:",false);
                                    prependOrText("em",response.correct_code,false);
                                    animatePartial();
                                }   else if (response.correct == 0) {
                                    prependOrText("p","Odgovor je netočan!",false);
                                    prependOrText("b","Ispravan kod:",false);
                                    prependOrText("em",response.correct_code,false);
                                    animateIncorrect();
                                }

                            } else if (response.status == 0) {
                                prependOrText("p","Molimo upišite odgovor!",false);
                            }
                        },
                        error: function(xhr) {
                            showError(xhr);
                        }
                    });

                }, 2000);

            });

        }

    });
</script>
</body>

</html>