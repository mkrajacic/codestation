<?php
class Question
{
    private $conn;
    private $table = "question";

    private $id;
    private $question;
    private $lesson_id;
    private $question_type;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function get_id()
    {
        return $this->id;
    }

    public function set_id($id)
    {
        $this->id = $id;
    }

    public function get_question()
    {
        return $this->question;
    }

    public function set_question($question)
    {
        $this->question = $question;
    }

    public function get_lesson_id()
    {
        return $this->lesson_id;
    }

    public function set_lesson_id($lesson_id)
    {
        $this->lesson_id = $lesson_id;
    }

    public function get_question_type()
    {
        return $this->question_type;
    }

    public function set_question_type($question_type)
    {
        $this->question_type = $question_type;
    }

    public function CountQuestions()
    {

        $query = "SELECT id FROM " . $this->table;

        if (!empty($this->lesson_id)) {
            $query .= " WHERE lesson_id=?";
        }

        $stmt = $this->conn->prepare($query);

        if (!empty($this->lesson_id)) {
            $stmt->bindParam(1, $this->lesson_id);
        }
        $stmt->execute();

        return $stmt->rowCount();
    }

    public function getQuestions($byLessId, $limitStart = null, $limitNmbr = 10)
    {

        $query = "SELECT l.id, l.question, l.lesson_id,l.question_type FROM " . $this->table . " l ORDER BY l.id";

        if ($byLessId) {
            $query = "SELECT l.id, l.question, l.lesson_id,l.question_type FROM " . $this->table . " l WHERE l.lesson_id=? ORDER BY l.id";
        }

        if (!is_null($limitStart)) {
            $query .= " LIMIT " . $limitStart . "," . $limitNmbr;
        }

        $stmt = $this->conn->prepare($query);

        if ($byLessId) {
            $stmt->bindParam(1, $this->lesson_id);
        }

        $stmt->execute();

        return $stmt;
    }

    public function getQuestionsSorted($byLessId, $sortBy, $order, $limitStart = null, $limitNmbr = 10)
    {

        $query = "SELECT l.id, l.question, l.lesson_id,l.question_type FROM " . $this->table . " l ORDER BY l." . $sortBy . " " . $order;
        if ($byLessId) {
            $query = "SELECT l.id, l.question, l.lesson_id,l.question_type FROM " . $this->table . " l WHERE l.lesson_id=? ORDER BY l." . $sortBy . " " . $order;
        }

        if (!is_null($limitStart)) {
            $query .= " LIMIT " . $limitStart . "," . $limitNmbr;
        }

        $stmt = $this->conn->prepare($query);

        if ($byLessId) {
            $stmt->bindParam(1, $this->lesson_id);
        }

        $stmt->execute();

        return $stmt;
    }

    public function getQuestionsByLanguage($language_id)
    {

        $query = "SELECT l.id as question_id, l.question, l.lesson_id,ls.precondition,l.question_type, lg.id AS language_id FROM question l INNER JOIN lesson ls ON l.lesson_id=ls.id INNER JOIN language lg ON lg.id=ls.language_id WHERE lg.id=?";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $language_id);

        $stmt->execute();

        return $stmt;
    }

    public function getQuestionsByType($byLessId, $type)
    {
        $query = "SELECT l.id, l.question, l.lesson_id,l.question_type FROM " . $this->table . " l WHERE question_type=?";

        if ($byLessId) {
            $query = "SELECT l.id, l.question, l.lesson_id,l.question_type FROM " . $this->table . " l WHERE l.lesson_id=? AND question_type=?";
        }

        $stmt = $this->conn->prepare($query);

        if ($byLessId) {
            $stmt->bindParam(1, $this->lesson_id);
            $stmt->bindParam(2, $type);
        } else {
            $stmt->bindParam(1, $type);
        }

        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                return $stmt;
            } else {
                return false;
            }
        }
    }


    public function getQuestionById($id)
    {

        $query = "SELECT l.id, l.question, l.lesson_id,l.question_type FROM " . $this->table . " l WHERE l.id=? ORDER BY l.id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);

        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                return $stmt;
            } else {
                return false;
            }
        }
    }

    public function createQuestion()
    {

        $query = "INSERT INTO " . $this->table . " (question,lesson_id,question_type) VALUES (?,?,?)";

        $stmt = $this->conn->prepare($query);

        $this->question = trim((($this->question)));
        $this->lesson_id = trim((($this->lesson_id)));
        $this->question_type = trim((($this->question_type)));

        $stmt->bindParam(1, $this->question);
        $stmt->bindParam(2, $this->lesson_id);
        $stmt->bindParam(3, $this->question_type);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function editQuestion()
    {

        $this->id = (($this->id));

        $check_query = "SELECT l.id FROM " . $this->table . " l WHERE id=?";
        $check = $this->conn->prepare($check_query);
        $check->bindParam(1, $this->id);

        if ($check->execute()) {

            if ($check->rowCount() > 0) {
                $query = "UPDATE " . $this->table . " SET question=?,lesson_id=?,question_type=? WHERE id=?";

                $stmt = $this->conn->prepare($query);

                $this->question = trim((($this->question)));
                $this->lesson_id = trim((($this->lesson_id)));
                $this->question_type = trim((($this->question_type)));

                $stmt->bindParam(1, $this->question);
                $stmt->bindParam(2, $this->lesson_id);
                $stmt->bindParam(3, $this->question_type);
                $stmt->bindParam(4, $this->id);

                if ($stmt->execute()) {
                    return true;
                }
            }
        }

        return false;
    }

    public function deleteQuestion()
    {

        $query = "DELETE FROM " . $this->table . " WHERE id=?";

        $stmt = $this->conn->prepare($query);

        $this->id = trim((($this->id)));

        $stmt->bindParam(1, $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
