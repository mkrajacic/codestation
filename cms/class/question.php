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

    public function isUniqueQuestion()
    {
        $query = "SELECT l.id,l.question,l.lesson_id,l.question_type FROM " . $this->table . " l WHERE question=? AND lesson_id=? AND lesson_type=?";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->question);
        $stmt->bindParam(2, $this->lesson_id);
        $stmt->bindParam(3, $this->lesson_type);

        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {

                $question_id = $this->id;

                $question_row = $stmt->fetch(PDO::FETCH_ASSOC);
                extract($question_row);

                if (!empty($question_id)) {
                    if ($question_id == $id) {
                        return true;
                    } else {
                        return false;
                    }
                }
            } else {
                return true;
            }
        }
    }

    public function getQuestions($byLessId)
    {

        $query = "SELECT l.id, l.question, l.lesson_id,l.question_type FROM " . $this->table . " l ORDER BY l.id";

        if($byLessId) {
            $query = "SELECT l.id, l.question, l.lesson_id,l.question_type FROM " . $this->table . " l WHERE l.lesson_id=? ORDER BY l.id";
        }

        $stmt = $this->conn->prepare($query);

        if($byLessId) {
            $stmt->bindParam(1, $this->lesson_id);
        }

        $stmt->execute();

        return $stmt;
    }

    public function getQuestionsByType($byLessId,$type)
    {
        $query = "SELECT l.id, l.question, l.lesson_id,l.question_type FROM " . $this->table . " l WHERE question_type=? ORDER BY l.id";

        if($byLessId) {
            $query = "SELECT l.id, l.question, l.lesson_id,l.question_type FROM " . $this->table . " l WHERE l.lesson_id=? ORDER BY l.id";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $type);

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

        $query = "SELECT l.id, l.name, l.description, l.language_id FROM " . $this->table . " l WHERE l.id=?";

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

        $query = "INSERT INTO " . $this->table . " (name,description,language_id) VALUES (?,?,?)";

        $stmt = $this->conn->prepare($query);

        $this->name = trim(htmlspecialchars(strip_tags($this->name)));
        $this->description = trim(htmlspecialchars(strip_tags($this->description)));
        $this->language_id = trim(htmlspecialchars(strip_tags($this->language_id)));

        $stmt->bindParam(1, $this->name);
        $stmt->bindParam(2, $this->description);
        $stmt->bindParam(3, $this->language_id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function editQuestion()
    {

        $this->id = htmlspecialchars(strip_tags($this->id));

        $check_query = "SELECT l.id FROM " . $this->table . " l WHERE id=?";
        $check = $this->conn->prepare($check_query);
        $check->bindParam(1, $this->id);

        if ($check->execute()) {

            if ($check->rowCount() > 0) {
                $query = "UPDATE " . $this->table . " SET name=?,description=?,language_id=? WHERE id=?";

                $stmt = $this->conn->prepare($query);

                $this->name = trim(htmlspecialchars(strip_tags($this->name)));
                $this->description = trim(htmlspecialchars(strip_tags($this->description)));
                $this->language_id = trim(htmlspecialchars(strip_tags($this->language_id)));

                $stmt->bindParam(1, $this->name);
                $stmt->bindParam(2, $this->description);
                $stmt->bindParam(3, $this->language_id);
                $stmt->bindParam(3, $this->id);

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

        $this->id = trim(htmlspecialchars(strip_tags($this->id)));

        $stmt->bindParam(1, $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
