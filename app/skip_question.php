<?php
include_once("../functions.php");
include_once("../class/answer.php");
include_once("../class/coding_answer.php");
include_once("../class/question.php");
include_once("../class/user_progress_question.php");

$db = connect();
session_start();

$incorrect_answers = $_SESSION['incorrect'];
$number_of_questions = sizeof($_SESSION['question_ids']);
$average=$incorrect_answers/$number_of_questions;

    if (isset($_POST['index'])) {

        $index = $_SESSION['index'];
        $question_id = $_SESSION['question_ids'][$index];

        if (isset($_SESSION['lifes'])) {

            $remainingL = $_SESSION['lifes'];

            $question = new Question($db);
            $question->set_id($question_id);
    
            $question_stmt = $question->getQuestionById($question_id);

            $type = 0;
            $answr = 0;

            $question_progress = new QuestionProgress($db);
            $question_progress->set_user_id($_SESSION['user_id']);
            $question_progress->set_question_id($question_id);

            $is_in_table = false;

            if ($check_progress = $question_progress->getProgressByQuestion()) {
                $progress = $check_progress->fetch(PDO::FETCH_ASSOC);
                $is_in_table = true;
            } else {
                $is_in_table = false;
            }
    
            if ($question_stmt) {

                while ($q_row = $question_stmt->fetch(PDO::FETCH_ASSOC)) {
                    $type = $q_row['question_type'];
                }

                if($type!=4) {
                    $answer = new Answer($db);
                    $answer->set_question_id($question_id);
                    $correct_ans = $answer->getAnswersByCorrect(true);

                    if($correct_ans) {
                        while ($cor_row = $correct_ans->fetch(PDO::FETCH_ASSOC)) {
                            $answr = $cor_row['answer'];
                        }
                    }

                }else{

                    $answer = new CodingAnswer($db);
                    $answer->set_question_id($question_id);
                    $correct_ans = $answer->getAnswers(true);

                    if($correct_ans) {
                        while ($cor_row = $correct_ans->fetch(PDO::FETCH_ASSOC)) {
                            $answr = $cor_row['code'];
                        }
                    }

                }

                $_SESSION['index']++;
                $_SESSION['lifes']--;
                $_SESSION['incorrect']++;

                create_question_progress($is_in_table, $question_progress);
                $clean_answr = htmlentities($answr,ENT_NOQUOTES);
                echo json_encode(array('status' => 1,'answer'=>$clean_answr));
                die();
            }else{
                encode_error();
            }

            if (($remainingL <= 1) && ($average>=0.49)) {
                $_SESSION['index']++;
                $_SESSION['lifes']--;
                $_SESSION['incorrect']++;
                $clean_answr = htmlentities($answr,ENT_NOQUOTES);
                echo json_encode(array('status' => 1,'answer'=>$clean_answr));
                die();
            }
            
        }else{
            encode_error();
        }
    }
?>