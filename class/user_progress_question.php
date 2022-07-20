<?php
class QuestionProgress
{
    private $conn;
    private $table = "user_progress_question";

    private $user_id;
    private $question_id;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function get_user_id()
    {
        return $this->user_id;
    }

    public function set_user_id($user_id)
    {
        $this->user_id = $user_id;
    }

    public function get_question_id()
    {
        return $this->question_id;
    }

    public function set_question_id($question_id)
    {
        $this->question_id = $question_id;
    }

    public function CountIncorrectQuestions()
    {
        $query = "SELECT question_id FROM " . $this->table . " WHERE user_id=?";

        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(1, $this->user_id);
        $stmt->execute();

        return $stmt->rowCount();
    }

    public function getQuestionProgress()
    {

        $query = "SELECT l.user_id, l.question_id FROM " . $this->table . " l WHERE l.user_id=? ORDER BY user_id";

        $stmt = $this->conn->prepare($query);

        $this->user_id = trim((($this->user_id)));
        $stmt->bindParam(1, $this->user_id);
        $stmt->execute();

        return $stmt;
    }

    public function compareQuestionProgress($lesson_id)
    {

        $query = "SELECT (SELECT count(question.id) FROM question INNER JOIN lesson ON question.lesson_id=lesson.id INNER JOIN user_progress_question on question.id=user_progress_question.question_id WHERE user_progress_question.user_id=? AND lesson.id=?) AS incorrect_questions, count(q.id) AS questions_in_lesson, ls.name as lesson_name FROM lesson ls INNER JOIN question q ON q.lesson_id=ls.id WHERE ls.id=?";

        $stmt = $this->conn->prepare($query);

        $this->user_id = trim((($this->user_id)));
        $stmt->bindParam(1, $this->user_id);
        $stmt->bindParam(2, $lesson_id);
        $stmt->bindParam(3, $lesson_id);
        $stmt->execute();

        return $stmt;
    }

    public function getQuestionProgressWithLesson()
    {
        $query = "SELECT question.id as question_id,lesson.id as lesson_id,lg.id as language_id FROM question INNER JOIN user_progress_question ON question.id=user_progress_question.question_id INNER JOIN lesson on lesson.id=question.lesson_id INNER JOIN language lg ON lesson.language_id=lg.id WHERE user_progress_question.user_id=?";

        $stmt = $this->conn->prepare($query);

        $this->user_id = trim((($this->user_id)));
        $stmt->bindParam(1, $this->user_id);
        $stmt->execute();

        return $stmt;
    }
    
    public function getProgressByQuestion()
    {

        $query = "SELECT l.user_id, l.question_id FROM " . $this->table . " l WHERE l.question_id=? AND l.user_id=? ORDER BY user_id";

        $stmt = $this->conn->prepare($query);

        $this->user_id = trim((($this->user_id)));
        $this->question_id = trim((($this->question_id)));
        $stmt->bindParam(1, $this->question_id);
        $stmt->bindParam(2, $this->user_id);

        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                return $stmt;
            } else {
                return false;
            }
        }
    }

    public function createQuestionProgress()
    {

        $query = "INSERT INTO " . $this->table . " (user_id,question_id) VALUES (?,?)";

        $stmt = $this->conn->prepare($query);

        $this->user_id = trim((($this->user_id)));
        $this->question_id = trim((($this->question_id)));

        $stmt->bindParam(1, $this->user_id);
        $stmt->bindParam(2, $this->question_id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function deleteQuestionProgress()
    {
        $query = "DELETE FROM " . $this->table . " WHERE user_id=? AND question_id=?";

        $stmt = $this->conn->prepare($query);

        $this->user_id = trim((($this->user_id)));
        $this->question_id = trim((($this->question_id)));

        $stmt->bindParam(1, $this->user_id);
        $stmt->bindParam(2, $this->question_id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
