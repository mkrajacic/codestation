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

$nmbrofQuestions = 15;
$qids = array();
$lids = array();
$passed_lessons = array();
$qp_lids = array();
$qp_rows = array();

$auth = isAuthorized();
if ($auth == 0) {
    $_SESSION['redirect_message'] = "Nemate pravo pristupa sadržaju!";
    encode_error();
}

if (isset($_POST['id'])) {

    $lang_id = $_POST['id'];

    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $lp_count = 0;
        $qp_count = 0;

        $question_progress = new QuestionProgress($db);
        $question_progress->set_user_id($user_id);

        $check_progress = $question_progress->getQuestionProgressWithLesson();

        if ($check_progress) {
            $qp_count = $check_progress->rowCount();
            $qp_rows = $check_progress->fetchAll();

            if (!empty($qp_rows)) {
                foreach ($qp_rows as $qp_row) {
                    array_push($qp_lids, $qp_row['lesson_id']);
                }
            }
        }

        $question = new Question($db);
        $all_questions = $question->getQuestionsByLanguage($lang_id);

        $lesson_progress = new LessonProgress($db);
        $lesson_progress->set_user_id($user_id);
        $lesson_progress_by_language = $lesson_progress->getProgressByLanguage($lang_id);

        if ($lesson_progress_by_language) {
            $lp_count = $lesson_progress_by_language->rowCount();
            while ($lpl = $lesson_progress_by_language->fetch(PDO::FETCH_ASSOC)) {
                array_push($passed_lessons, $lpl['lesson_id']);
            }
        }

        $passedAll = 1;
        foreach ($qp_lids as $qp_lid) {
            if (!(in_array($qp_lid, $passed_lessons))) {
                $passedAll = 0;
            }
        }
    }

    if (($lp_count > 0) || ($qp_count > 0)) {

        if ((($qp_count == 0) && ($lp_count > 0)) || ($passedAll == 1)) {
            $_SESSION['noPassing'] = 1;
        }

        if (!empty($qp_rows)) {
            foreach ($qp_rows as $qp_row) {
                if ($qp_row['language_id'] == $lang_id) {
                    if (!(sizeof($qids) == $nmbrofQuestions)) {
                        if (!in_array($qp_row['question_id'], $qids)) {
                            array_push($qids, $qp_row['question_id']);
                            array_push($lids, $qp_row['lesson_id']);
                        }
                    }
                }
            }
        }

        if (sizeof($qids) < $nmbrofQuestions) {

            if ($all_questions) {
                $question_count = $all_questions->rowCount();

                $question_rows = $all_questions->fetchAll();
                $c = 0;
                shuffle($question_rows);
                foreach ($question_rows as $questions) {
                    if (!($c == $question_count)) {

                        if (!(sizeof($qids) == $nmbrofQuestions)) {

                            if ((in_array($questions['lesson_id'], $passed_lessons)) || (in_array($questions['lesson_id'], $lids))) {

                                if ($questions['question_type'] == 4) {
                                    $answer = new CodingAnswer($db);
                                    $answer->set_question_id($questions['question_id']);
                                    $answer_stmt = $answer->getAnswers(true);

                                    $answer_numrows = $answer_stmt->rowCount();
                                } else {
                                    $answer = new Answer($db);
                                    $answer->set_question_id($questions['question_id']);
                                    $answer_stmt = $answer->getAnswers(true);

                                    $answer_numrows = $answer_stmt->rowCount();
                                }

                                if ($answer_numrows > 0) {

                                    if (!in_array($questions['question_id'], $qids)) {
                                        array_push($qids, $questions['question_id']);
                                        array_push($lids, $questions['lesson_id']);
                                    }
                                }
                            }
                        }
                    }
                    $c++;
                }
            }
        }

        if (sizeof($qids) > 0) {
            shuffle($qids);
            $_SESSION['index'] = 0;
            $_SESSION['question_ids'] = $qids;
            $_SESSION['lesson_ids'] = $lids;
            $_SESSION['language_id'] = $lang_id;
            $_SESSION['correct'] = 0;
            $_SESSION['incorrect'] = 0;
            $_SESSION['practice'] = 1;
            $_SESSION['lifes'] = 3;
            echo json_encode(array('status' => 1, 'message' => 'Pitanja prikupljena!', 'nmbr' => sizeof($_SESSION['question_ids'])));
        } else {
            $_SESSION['redirect_message'] = "Greška pri prikupljanju pitanja!";
            encode_error();
        }
    } else {
        $_SESSION['redirect_message'] = "Vježba nije dostupna!";
        encode_error();
    }
} else {
    $_SESSION['redirect_message'] = "Greška pri prikupljanju pitanja!";
    encode_error();
}
