<?php
include_once("../functions.php");
include_once("../class/lesson.php");
include_once("../class/answer.php");
include_once("../class/coding_answer.php");
include_once("../class/question.php");
include_once("../class/question_type.php");
include_once("../class/user_progress_question.php");
include_once("../class/user_progress_lesson.php");
include_once("../class/user_progress_language.php");

$db = connect();
session_start();

$auth = isAuthorized();
if ($auth == 0) {
    $_SESSION['redirect_message'] = "Nemate pravo pristupa sadržaju!";
    encode_error();
}

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
}


$qids = array();

if (isset($_POST['id'])) {
    $lesson_id = (int)$_POST['id'];
    $lesson = new Lesson($db);
    $check_lesson = $lesson->getLessonById($lesson_id);

    if ($check_lesson) {
        $less_row = $check_lesson->fetch(PDO::FETCH_ASSOC);
        $language_id = $less_row['language_id'];

        $quest = new Question($db);
        $quest->set_lesson_id($lesson_id);
        $get_questions_stmt = $quest->getQuestions(true);
        $numrows = $get_questions_stmt->rowCount();

        if ($numrows > 0) {
            while ($questions = $get_questions_stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($questions);
                if($question_type==4) {
                    $answer = new CodingAnswer($db);
                    $answer->set_question_id($id);
                    $answer_stmt = $answer->getAnswers(true);
        
                    $answer_numrows = $answer_stmt->rowCount();
                }else{
                    $answer = new Answer($db);
                    $answer->set_question_id($id);
                    $answer_stmt = $answer->getAnswers(true);
        
                    $answer_numrows = $answer_stmt->rowCount();
                }

                if($answer_numrows>0) {
                    array_push($qids, $id);
                }
            }
        }

        if (sizeof($qids) > 0) {
            shuffle($qids);
            $_SESSION['index'] = 0;
            $_SESSION['question_ids'] = $qids;
            $_SESSION['correct'] = 0;
            $_SESSION['incorrect'] = 0;
            $_SESSION['lesson'] = 1;
            $_SESSION['lesson_id'] = $lesson_id;
            $_SESSION['language_id'] = $language_id;
            $_SESSION['lifes'] = 3;
            echo json_encode(array('status' => 1, 'message' => 'Pitanja prikupljena!', 'nmbr' => sizeof($_SESSION['question_ids'])));
        } else {
            $_SESSION['redirect_message'] = "Greška pri prikupljanju pitanja!";
            encode_error();
        }
    }
} else {
    $_SESSION['redirect_message'] = "Greška pri prikupljanju pitanja!";
    encode_error();
}
