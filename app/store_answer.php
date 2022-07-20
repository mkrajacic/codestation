<?php
include_once("../functions.php");
include_once("../class/lesson.php");
include_once("../class/answer.php");
include_once("../class/coding_answer.php");
include_once("../class/question.php");
include_once("../class/question_type.php");
include_once("../class/user_progress_question.php");

$db = connect();
session_start();

if (isset($_POST['index'])) {

    if (isset($_SESSION['lifes'])) {

        if (isset($_POST['answer'])) {

            if (!empty($_POST['qid'])) {

                $question_id = $_POST['qid'];
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
            }

            if (isset($_POST['type'])) {
                if ($_POST['type'] == 1) {

                    $answered = $_POST['answer'];

                    $answer = new Answer($db);
                    $check_answer = $answer->getAnswerById($answered);
                    $answer->set_question_id($question_id);

                    $cor_answr = "";
                    if ($correct_answer = $answer->getAnswersByCorrect(true)) {
                        $cor_answer_row = $correct_answer->fetch(PDO::FETCH_ASSOC);
                        $clean_cor = htmlentities($cor_answer_row['answer']);
                        $cor_answr = $clean_cor;
                    }

                    if ($check_answer) {
                        $_SESSION['index']++;
                        while ($answr = $check_answer->fetch(PDO::FETCH_ASSOC)) {
                            if ($answr['correct'] == 1) {
                                $_SESSION['correct']++;
                                delete_question_progress($is_in_table, $question_progress);
                                echo json_encode(array('status' => 1, 'message' => 'Odgovor pohranjen!', 'correct' => 1, 'incorrect' => 0));
                            } else {
                                $_SESSION['lifes']--;
                                $_SESSION['incorrect']++;
                                create_question_progress($is_in_table, $question_progress);
                                echo json_encode(array('status' => 1, 'message' => 'Odgovor pohranjen!', 'correct' => 0, 'incorrect' => 1, 'correct_answer' => $cor_answr));
                            }
                        }
                    } else {
                        encode_error();
                    }
                } else if ($_POST['type'] == 2) {

                    if (isset($_POST['qid'])) {

                        $question_id = $_POST['qid'];
                        $correct_answers_ids = array();
                        $correct_answers = array();

                        $ans = new Answer($db);
                        $ans->set_question_id($question_id);
                        $find_correct_answers = $ans->getAnswersByCorrect(true);

                        if ($find_correct_answers) {

                            while ($cor_ans = $find_correct_answers->fetch(PDO::FETCH_ASSOC)) {
                                array_push($correct_answers_ids, $cor_ans['id']);
                                $clean_cor = htmlentities($cor_ans['answer']);
                                array_push($correct_answers, $clean_cor);
                            }
                        } else {
                            encode_error();
                        }

                        if (!empty($_POST['answer'])) {

                            $answered = explode("<#>", $_POST['answer']);
                            $correct = array();
                            $incorrect = array();

                            $_SESSION['index']++;

                            foreach ($answered as $ans) {
                                $answer = new Answer($db);
                                $check_answer = $answer->getAnswerById($ans);

                                if ($check_answer) {

                                    while ($answr = $check_answer->fetch(PDO::FETCH_ASSOC)) {
                                        if ($answr['correct'] == 1) {
                                            array_push($correct, $answr['answer']);
                                        } else {
                                            array_push($incorrect, $answr['answer']);
                                        }
                                    }
                                } else {
                                    encode_error();
                                }
                            }
                            if (sizeof($correct_answers_ids) == sizeof($correct)) {
                                $_SESSION['correct']++;
                                delete_question_progress($is_in_table, $question_progress);
                                echo json_encode(array('status' => 1, 'message' => 'Odgovor pohranjen!', 'correct' => $correct_answers, 'answerStatus' => 1));
                            } else if (sizeof($correct_answers_ids) != sizeof($correct) && sizeof($correct) > 0) {

                                $_SESSION['lifes']--;
                                $_SESSION['incorrect']++;
                                create_question_progress($is_in_table, $question_progress);
                                echo json_encode(array('status' => 1, 'message' => 'Odgovor pohranjen!', 'correct' => $correct_answers, 'answerStatus' => 2));
                            } else if (sizeof($correct) == 0 && sizeof($incorrect) > 0) {

                                $_SESSION['lifes']--;
                                $_SESSION['incorrect']++;
                                create_question_progress($is_in_table, $question_progress);
                                echo json_encode(array('status' => 1, 'message' => 'Odgovor pohranjen!', 'correct' => $correct_answers, 'answerStatus' => 3));
                            } else if (sizeof($correct) == 0 && sizeof($incorrect) == 0) {
                                encode_error();
                            }
                        } else {
                            encode_error();
                        }
                    }
                } else if ($_POST['type'] == 3) {

                    if (isset($_POST['qid'])) {
                        $question_id = $_POST['qid'];
                        $found_answer = array();
                        $correct_answers = array();

                        $question = new Question($db);
                        $find_question = $question->getQuestionById($question_id);

                        if (!empty($_POST['answer'])) {
                            $answered = $_POST['answer'];
                            if ($find_question) {

                                $answer = new Answer($db);
                                $answer->set_question_id($question_id);
                                $find_answers = $answer->getAnswersByCorrect(true);

                                if ($find_answers) {
                                    $_SESSION['index']++;
                                    while ($answr = $find_answers->fetch(PDO::FETCH_ASSOC)) {

                                            similar_text(trim(strtoupper($answered)), trim(strtoupper($answr['answer'])), $answer_similarity);

                                            if ($answer_similarity >= 89) {
                                                store_correct($found_answer);
                                            } else {
                                                store_incorrect($found_answer);
                                            }

                                            $clean_answr = htmlentities($answr['answer'],ENT_NOQUOTES);
                                            array_push($correct_answers, $clean_answr);
                                    }
                                } else {
                                    encode_error();
                                }
                            } else {
                                encode_error();
                            }

                            if (in_array("1", $found_answer)) {
                                $_SESSION['correct']++;
                                delete_question_progress($is_in_table, $question_progress);
                                echo json_encode(array('status' => 1, 'message' => 'Odgovor pohranjen!', 'correct' => 1));
                                die();
                            } else {
                                $_SESSION['lifes']--;
                                $_SESSION['incorrect']++;
                                create_question_progress($is_in_table, $question_progress);
                                echo json_encode(array('status' => 1, 'message' => 'Odgovor pohranjen!', 'correct' => 0, 'correct_answers' => array_slice($correct_answers, 0, 3)));
                                die();
                            }
                        } else {
                            encode_error();
                        }
                    } else {
                        encode_error();
                    }
                } else if ($_POST['type'] == 4) {

                    if (isset($_POST['qid'])) {
                        $question_id = $_POST['qid'];
                        $found_answer = array();
                        $correct_code = array();

                        $question = new Question($db);
                        $find_question = $question->getQuestionById($question_id);

                        if (!empty($_POST['answer'])) {
                            $answered = $_POST['answer'];
                            $output = $_POST['output'];

                            if ($find_question) {

                                $answer = new CodingAnswer($db);
                                $answer->set_question_id($question_id);
                                $find_answers = $answer->getAnswers(true);

                                if ($find_answers) {
                                    $_SESSION['index']++;

                                    while ($answr = $find_answers->fetch(PDO::FETCH_ASSOC)) {

                                        $answered = remove_code_whitespace($answered);
                                        similar_text(trim(strtoupper($answered)), trim(strtoupper($answr['code'])), $code_similarity);

                                        if (!(is_null($answr['display'])) && !(empty($answr['display']))) {

                                            similar_text(trim(strtoupper($output)), trim(strtoupper($answr['display'])), $output_similarity);

                                            if ($output_similarity >= 89) {
                                                if ($code_similarity >= 85) {
                                                    store_correct($found_answer);
                                                } else if (($code_similarity >= 69) && ($code_similarity < 85)) {
                                                    store_partially_correct($found_answer);
                                                } else {
                                                    store_incorrect($found_answer);
                                                }
                                            } else if (($output_similarity >= 69) && ($output_similarity < 85)) {
                                                store_partially_correct($found_answer);
                                            } else {
                                                store_incorrect($found_answer);
                                            }
                                        } else {
                                            if ($code_similarity >= 88) {
                                                store_correct($found_answer);
                                            } else if (($code_similarity >= 69) && ($code_similarity < 88)) {
                                                store_partially_correct($found_answer);
                                            } else {
                                                store_incorrect($found_answer);
                                            }
                                        }
                                        $clean_answr = htmlentities($answr['code'],ENT_NOQUOTES);
                                        array_push($correct_code, $clean_answr);
                                    }
                                } else {
                                    encode_error();
                                }

                                if (in_array("1", $found_answer)) {

                                    $_SESSION['correct']++;
                                    $found_pos = array_search("1", $found_answer);
                                    delete_question_progress($is_in_table, $question_progress);

                                    if (isset($os[$found_pos])) {
                                        echo json_encode(array('status' => 1, 'message' => 'Odgovor pohranjen!', 'correct' => 1,  'correct_code' => $correct_code[$found_pos]));
                                    } else {
                                        echo json_encode(array('status' => 1, 'message' => 'Odgovor pohranjen!', 'correct' => 1, 'correct_code' => $correct_code[$found_pos]));
                                    }
                                    die();
                                } else if (in_array("0.5", $found_answer)) {

                                    $_SESSION['lifes']--;
                                    $_SESSION['incorrect']++;
                                    $found_partial = array_search("0.5", $found_answer);
                                    create_question_progress($is_in_table, $question_progress);

                                    if (isset($os[$found_partial])) {
                                        echo json_encode(array('status' => 1, 'message' => 'Odgovor pohranjen!', 'correct' => 0.5,  'correct_code' => $correct_code[$found_partial]));
                                    } else {
                                        echo json_encode(array('status' => 1, 'message' => 'Odgovor pohranjen!', 'correct' => 0.5,  'correct_code' => $correct_code[$found_partial]));
                                    }
                                    die();
                                } else {
                                    $_SESSION['lifes']--;
                                    $_SESSION['incorrect']++;
                                    create_question_progress($is_in_table, $question_progress);
                                    echo json_encode(array('status' => 1, 'message' => 'Odgovor pohranjen!', 'correct' => 0, 'correct_code' => $correct_code[0]));
                                    die();
                                }
                            } else {
                                encode_error();
                            }
                        } else {
                            encode_error();
                        }
                    } else {
                        encode_error();
                    }
                }
            }
        } else {
            encode_error();
        }
    } else {
        encode_error();
    }
} else {
    encode_error();
}
