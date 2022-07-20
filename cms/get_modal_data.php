<?php
include_once("../functions.php");
include_once("../class/language.php");
include_once("../class/user.php");
include_once("../class/lesson.php");
include_once("../class/question.php");
include_once("../class/question_type.php");
include_once("../class/answer.php");
include_once("../class/coding_answer.php");
$db = connect();
session_start();

$auth = isAuthorized();
if (($auth == 0) || ($auth == 3)) {
            encode_error('Nije vam dopušteno izvršavanje akcije!');
}

        if (isset($_SESSION['user_id'])) {

            if (isset($_POST['id'])) {

                if (isset($_POST['category'])) {

                    switch ($_POST['category']) {
                        case 0:
                            $language_id = (int)$_POST['id'];
                            $lang = new Language($db);

                            if (!$stmt = $lang->getLanguageById($language_id)) {
                                encode_error();
                            } else {
                                $lang_row = $stmt->fetch(PDO::FETCH_ASSOC);
                                extract($lang_row);
                                if((is_null($image)) || (empty($image))) {
                                    echo json_encode(array('status' => 1,'disable'=>0));
                                }else{
                                    echo json_encode(array('status' => 1, 'img'=>$image,'disable'=>1));
                                }
                            }
                            break;
                        case 1:
                            $language_id = (int)$_POST['id'];
                            $lang = new Language($db);

                            if (!$stmt = $lang->getLanguageById($language_id)) {
                                encode_error();
                            } else {
                                $lang_row = $stmt->fetch(PDO::FETCH_ASSOC);
                                extract($lang_row);
                                echo json_encode(array('status' => 1, 'name' => $name, 'description' => $description, 'compiler_mode' => $compiler_mode, 'language_version' => $language_version, 'editor_mode' => $editor_mode));
                            }
                            break;
                        case 2:
                            $language_id = (int)$_POST['id'];
                            $lang = new Language($db);

                            if (!$stmt = $lang->getLanguageById($language_id)) {
                                encode_error();
                            } else {
                                $lang_row = $stmt->fetch(PDO::FETCH_ASSOC);
                                extract($lang_row);
                                echo json_encode(array('status' => 1, 'name' => htmlspecialchars($name)));
                            }
                            break;
                        case 3:
                            $languages = array('id' => '', 'name' => '');
                            $precondition_lessons = array('id' => '', 'name' => '');
                            $lesson_id = (int)$_POST['id'];
                            $less = new Lesson($db);

                            if (!$stmt = $less->getLessonById($lesson_id)) {
                                encode_error();
                            } else {
                                $less_row = $stmt->fetch(PDO::FETCH_ASSOC);
                                extract($less_row);

                                $language = new Language($db);

                                $stmt = $language->getLanguages();
                                $numrows = $stmt->rowCount();

                                if ($numrows > 0) {
                                    $c = 0;
                                    while ($language_row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                        $languages[$c]['id'] = $language_row['id'];
                                        $languages[$c]['name'] = htmlspecialchars($language_row['name']);
                                        $c++;
                                    }
                                }

                                $prec_less = new Lesson($db);
                                $prec_less->set_language_id($language_id);

                                $prec_stmt = $prec_less->getLessons(true);
                                $prec_numrows = $prec_stmt->rowCount();

                                if ($prec_numrows > 0) {
                                    $p = 0;
                                    while ($prec_row = $prec_stmt->fetch(PDO::FETCH_ASSOC)) {
                                        if (!($id == $prec_row['id'])) {
                                            $precondition_lessons[$p]['id'] = $prec_row['id'];
                                            $precondition_lessons[$p]['name'] = htmlspecialchars($prec_row['name']);
                                        }
                                        $p++;
                                    }
                                }
                                echo json_encode(array('status' => 1, 'name' => $name, 'description' => $description, 'language' => $language_id, 'precondition' => $precondition, 'languages' => $languages, 'precondition_lessons' => $precondition_lessons));
                            }
                            break;
                        case 4:
                            $lesson_id = (int)$_POST['id'];
                            $less = new Lesson($db);

                            if (!$stmt = $less->getLessonById($lesson_id)) {
                                encode_error();
                            } else {
                                $less_row = $stmt->fetch(PDO::FETCH_ASSOC);
                                extract($less_row);
                                echo json_encode(array('status' => 1, 'name' => htmlspecialchars($name)));
                            }
                            break;
                        case 5:
                            $language_id = 0;
                            $lessons = array('id' => '', 'name' => '');
                            $q_types = array('id' => '', 'type' => '');
                            $question_id = (int)$_POST['id'];
                            $quest = new Question($db);

                            if (!$stmt = $quest->getQuestionById($question_id)) {
                                encode_error();
                            } else {
                                $quest_row = $stmt->fetch(PDO::FETCH_ASSOC);
                                extract($quest_row);

                                $lesson = new Lesson($db);

                                if ($lesson_stmt = $lesson->getLessonById($lesson_id)) {
                                    $lesson_row = $lesson_stmt->fetch(PDO::FETCH_ASSOC);
                                    $language_id = $lesson_row['language_id'];
                                }

                                $lessons_from_language = new Lesson($db);
                                $lessons_from_language->set_language_id($language_id);
                                $lessons_fl_stmt = $lessons_from_language->getLessons(true);
                                $lessons_fl_numrows = $lessons_fl_stmt->rowCount();

                                if ($lessons_fl_numrows > 0) {
                                    $c = 0;
                                    while ($lessons_fl_row = $lessons_fl_stmt->fetch(PDO::FETCH_ASSOC)) {
                                        $lessons[$c]['id'] = $lessons_fl_row['id'];
                                        $lessons[$c]['name'] = htmlspecialchars($lessons_fl_row['name']);
                                        $c++;
                                    }
                                }

                                $question_types = new QuestionType($db);
                                $question_types_stmt = $question_types->getTypes();
                                $question_types_numrows = $question_types_stmt->rowCount();

                                if ($question_types_numrows > 0) {
                                    $d = 0;
                                    while ($question_types_row = $question_types_stmt->fetch(PDO::FETCH_ASSOC)) {
                                        $q_types[$d]['id'] = $question_types_row['id'];
                                        $q_types[$d]['type'] = htmlspecialchars($question_types_row['type']);
                                        $d++;
                                    }
                                }

                                echo json_encode(array('status' => 1, 'question' => $question, 'lesson_id' => $lesson_id, 'type' => $question_type, 'lessons' => $lessons, 'types' => $q_types));
                            }
                            break;
                        case 6:
                            $question_id = (int)$_POST['id'];
                            $quest = new Question($db);

                            if (!$stmt = $quest->getQuestionById($question_id)) {
                                encode_error();
                            } else {
                                $quest_row = $stmt->fetch(PDO::FETCH_ASSOC);
                                extract($quest_row);
                                echo json_encode(array('status' => 1, 'question' => htmlspecialchars($question)));
                            }
                            break;
                        case 7:
                            $answer_id = (int)$_POST['id'];
                            $answr = new Answer($db);

                            if (!$stmt = $answr->getAnswerById($answer_id)) {
                                encode_error();
                            } else {
                                $answr_row = $stmt->fetch(PDO::FETCH_ASSOC);
                                extract($answr_row);

                                echo json_encode(array('status' => 1, 'answer' => $answer, 'correct' => $correct));
                            }
                            break;
                        case 8:
                                $answer_id = (int)$_POST['id'];
                                $answr = new Answer($db);
    
                                if (!$stmt = $answr->getAnswerById($answer_id)) {
                                    encode_error();
                                } else {
                                    $answr_row = $stmt->fetch(PDO::FETCH_ASSOC);
                                    extract($answr_row);
    
                                    echo json_encode(array('status' => 1, 'answer' => htmlspecialchars($answer)));
                                }
                            break;
                        case 9:
                            $answer_id = $_POST['id'];
                            $answr = new CodingAnswer($db);

                            if (!$stmt = $answr->getAnswerById($answer_id)) {
                                encode_error();
                            } else {
                                $answr_row = $stmt->fetch(PDO::FETCH_ASSOC);
                                extract($answr_row);
                                echo json_encode(array('status' => 1, 'code' => $code, 'display' => $display));
                            }
                            break;
                        case 10:
                                $answer_id = $_POST['id'];
                                $answr = new CodingAnswer($db);
    
                                if (!$stmt = $answr->getAnswerById($answer_id)) {
                                    encode_error();
                                } else {
                                    $answr_row = $stmt->fetch(PDO::FETCH_ASSOC);
                                    extract($answr_row);
                                    echo json_encode(array('status' => 1, 'code' => htmlspecialchars($code)));
                                }
                            break;
                        case 11:
                            $allowed = admin_or_user("id");
                            if($allowed) {
                                $user_id = $_POST['id'];
                                $usr = new User($db);
    
                                if (!$stmt = $usr->getUserById($user_id)) {
                                    encode_error();
                                } else {
                                    $usr_row = $stmt->fetch(PDO::FETCH_ASSOC);
                                    extract($usr_row);
                                    if(is_null($image) || (empty($image))) {
                                        $disable =0;
                                    }else{
                                        $disable =1;
                                    }
                                    echo json_encode(array('status' => 1, 'username' => htmlspecialchars($username),'img'=>$image,'disable'=>$disable,'role'=>$role_code));
                                }
                            }else{
                                encode_error('Nije vam dopušteno izvršavanje akcije!');
                            }
                            break;
                    }
                }
            } else {
                encode_error();
            }
        } else {
            encode_error('Pristup nije dopušten!');
        }
