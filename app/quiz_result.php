<?php
include_once("../class/user.php");
include_once("../class/lesson.php");
include_once("../class/user_progress_lesson.php");
include_once("../class/user_progress_question.php");
include_once("../class/user_progress_language.php");

$already_passed = false;
$incorrect_answers = $_SESSION['incorrect'];
$number_of_questions = sizeof($_SESSION['question_ids']);
$average = $incorrect_answers / $number_of_questions;

if (($_SESSION['lifes'] >= 1) && ($average <= 0.49)) {

    if (isset($_SESSION['lesson']) && ($_SESSION['lesson'] == 1)) {

        if (isset($_SESSION['lesson_id'])) {
            $lesson_progress = new LessonProgress($db);
            $lesson_progress->set_user_id($_SESSION['user_id']);
            $lesson_progress->set_lesson_id($_SESSION['lesson_id']);
            $lesson_progress_stmt = $lesson_progress->getProgressByLesson();
            if ($lesson_progress_stmt) {
                $already_passed = true;
            }
        }

        if (!$already_passed) {

            if ($lesson_progress->createLessonProgress()) {

                if ($_SESSION['correct'] == sizeof($_SESSION['question_ids'])) {
                    $_SESSION['redirect_message'] = "Uspješno ste prošli lekciju! Svi su odgovori točni!";
                } else {
                    $_SESSION['redirect_message'] = "Uspješno ste prošli lekciju sa " . $_SESSION['correct'] . "/" . sizeof($_SESSION['question_ids']) . " točnih odgovora!";
                }

                if (isset($_SESSION['language_id'])) {
                    $language_id = $_SESSION['language_id'];
                    $lesson_progress = new LessonProgress($db);
                    $lesson_progress->set_user_id($user_id);

                    $check_less_progress = $lesson_progress->compareLessonProgressByLanguage($language_id);

                    if ($check_less_progress) {
                        $compare = $check_less_progress->fetch(PDO::FETCH_ASSOC);
                        if ($compare['passed_lessons'] == $compare['lessons_in_language']) {

                            $language_progress->set_language_id($compare['language_id']);

                            if (!($language_progress->getProgressByLanguage())) {

                                if (!$language_progress->createLanguageProgress()) {
                                    $_SESSION['redirect_message'] = "Greška pri ažuriranju podataka!";
                                    unset_session();
                                    header_redirect();
                                } else {
                                    $_SESSION['redirect_message'] .= "\nUspješno ste prošli programski jezik " . htmlspecialchars(strip_tags($compare['language_name']));
                                    unset_session();
                                }
                            }
                        }
                    }
                }
                unset_session();
                header_redirect("passed.php");
            } else {
                $_SESSION['redirect_message'] = "Greška pri ažuriranju podataka!";
                unset_session();
                header_redirect();
            }
        } else {
            if ($_SESSION['correct'] == sizeof($_SESSION['question_ids'])) {
                $_SESSION['redirect_message'] = "Uspješno ponavljanje lekcije! Svi odgovori su točni";
            } else {
                $_SESSION['redirect_message'] = "Uspješno ste ponovili lekciju sa " . $_SESSION['correct'] . "/" . sizeof($_SESSION['question_ids']) . " točnih odgovora!";
            }
            unset_session();
            header_redirect("passed.php");
        }
    } else if (isset($_SESSION['practice']) && ($_SESSION['practice'] == 1)) {

        if(isset($_SESSION['noPassing']) && ($_SESSION['noPassing'] == 1)) {
            if ($_SESSION['correct'] == sizeof($_SESSION['question_ids'])) {
                $_SESSION['redirect_message'] = "Uspješno ponavljanje! Svi odgovori su točni";
            } else {
                $_SESSION['redirect_message'] = "Uspješno ste ponovili lekcije sa " . $_SESSION['correct'] . "/" . sizeof($_SESSION['question_ids']) . " točnih odgovora!";
            }
            unset_session();
            header_redirect("passed.php");
        }else{
            if (isset($_SESSION['lesson_ids'])) {

                $newly_passed_lessons = array();
                $lessons_of_questions = $_SESSION['lesson_ids'];
    
                $check_quest_progress = $question_progress->getQuestionProgressWithLesson();
    
                    if (!empty($lessons_of_questions)) {
                        foreach ($lessons_of_questions as $lesson_of_question) {
    
                                $compare_question_progress = $question_progress->compareQuestionProgress($lesson_of_question);
    
                                if ($compare_question_progress) {
                                    $compare = $compare_question_progress->fetch(PDO::FETCH_ASSOC);
    
                                    if ($compare['incorrect_questions'] == 0) {
                                        array_push($newly_passed_lessons, $lesson_of_question);
                                    }
                                } else {
                                    $_SESSION['redirect_message'] = "Dogodila se pogreška!";
                                    unset_session();
                                    header_redirect();
                                }
                        }
                    }
    
                $lesson_passed_success = 1;
    
                if (!empty($newly_passed_lessons)) {
    
                    if(sizeof($newly_passed_lessons)==1) {
                        $_SESSION['redirect_message'] = "Prošli ste lekciju ";
                    }else{
                        $_SESSION['redirect_message'] = "Prošli ste lekcije ";
                    }
    
                    foreach ($newly_passed_lessons as $passed) {
    
                        $lesson_progress->set_lesson_id($passed);
    
                        if (!($lesson_progress->getProgressByLesson())) {
    
                            $lesson = new Lesson($db);
                            $lesson_stmt = $lesson->getLessonById($passed);
    
                            if ($lesson_stmt) {
                                $lesson_row = $lesson_stmt->fetch(PDO::FETCH_ASSOC);
                                $passed_name = $lesson_row['name'];
                            }
    
                            if ($lesson_progress->createLessonProgress()) {
                                $_SESSION['redirect_message'] .= htmlspecialchars(strip_tags($passed_name)) .  " ";
                            } else {
                                $_SESSION['redirect_message'] = "Greška pri ažuriranju podataka!";
                                $lesson_passed_success = 0;
                            }
                        }else{
                            $lesson_passed_success=0;
                        }
                    }
    
                    if ($lesson_passed_success) {
                        if (isset($_SESSION['language_id'])) {
                            $language_id = $_SESSION['language_id'];
                            $lesson_progress = new LessonProgress($db);
                            $lesson_progress->set_user_id($user_id);
        
                            $check_less_progress = $lesson_progress->compareLessonProgressByLanguage($language_id);
        
                            if ($check_less_progress) {
                                $compare = $check_less_progress->fetch(PDO::FETCH_ASSOC);
                                if ($compare['passed_lessons'] == $compare['lessons_in_language']) {
        
                                    $language_progress->set_language_id($compare['language_id']);
        
                                    if (!($language_progress->getProgressByLanguage())) {
        
                                        if (!$language_progress->createLanguageProgress()) {
                                            $_SESSION['redirect_message'] = "Greška pri ažuriranju podataka!";
                                            unset_session();
                                            header_redirect();
                                        } else {
                                            $_SESSION['redirect_message'] .= "\nUspješno ste prošli programski jezik " . htmlspecialchars(strip_tags($compare['language_name']));
                                            unset_session();
                                        }
                                    }
                                }
                            }
                        }
                        unset_session();
                        header_redirect("passed.php");
                    } else {
                        unset_session();
                        header_redirect();
                    }
                }
            }
        }

    } else {
        $_SESSION['redirect_message'] = "Dogodila se pogreška!";
        unset_session();
        header_redirect();
    }
} else {
    unset_session();
    header_redirect("failed.php");
}
