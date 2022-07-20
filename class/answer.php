<?php
class Answer
{
    private $conn;
    private $table = "answer";

    private $id;
    private $answer;
    private $question_id;
    private $correct;

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

    public function get_answer()
    {
        return $this->answer;
    }

    public function set_answer($answer)
    {
        $this->answer = $answer;
    }

    public function get_question_id()
    {
        return $this->question_id;
    }

    public function set_question_id($question_id)
    {
        $this->question_id = $question_id;
    }

    public function get_correct()
    {
        return $this->correct;
    }

    public function set_correct($correct)
    {
        $this->correct = $correct;
    }

    public function isUniqueAnswer()
    {
        $query = "SELECT l.id,l.answer,l.question_id,l.correct FROM " . $this->table . " l WHERE answer=? AND question_id=?";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->answer);
        $stmt->bindParam(2, $this->question_id);

        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {

                $answer_id = $this->id;

                $answer_row = $stmt->fetch(PDO::FETCH_ASSOC);
                extract($answer_row);

                if (!empty($answer_id)) {
                    if ($answer_id == $id) {
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

    public function CountAnswers()
    {
        $query = "SELECT id FROM " . $this->table;
        
        if(!empty($this->question_id)) {
            $query.= " WHERE question_id=?";
        }

        $stmt = $this->conn->prepare($query);

        if(!empty($this->question_id)) {
            $stmt->bindParam(1, $this->question_id);
        }

        $stmt->execute();

        return $stmt->rowCount();
    }

    public function getAnswers($byQuesId,$limitStart=null,$limitNmbr=10)
    {

        $query = "SELECT l.id,l.answer,l.question_id,l.correct FROM " . $this->table . " l ORDER BY l.id";

        if ($byQuesId) {
            $query = "SELECT l.id,l.answer,l.question_id,l.correct FROM " . $this->table . " l WHERE l.question_id=? ORDER BY l.id";
        }

        if(!is_null($limitStart)) {
            $query.= " LIMIT " . $limitStart . "," . $limitNmbr;
        }

        $stmt = $this->conn->prepare($query);

        if ($byQuesId) {
            $stmt->bindParam(1, $this->question_id);
        }

        $stmt->execute();

        return $stmt;
    }

    public function getAnswersSorted($byQuesId,$sortBy,$order,$limitStart=null,$limitNmbr=10)
    {

        $query = "SELECT l.id,l.answer,l.question_id,l.correct FROM " . $this->table . " l ORDER BY l." . $sortBy . " " . $order;

        if ($byQuesId) {
            $query = "SELECT l.id,l.answer,l.question_id,l.correct FROM " . $this->table . " l WHERE l.question_id=? ORDER BY l." . $sortBy . " " . $order;
        }

        if(!is_null($limitStart)) {
            $query.= " LIMIT " . $limitStart . "," . $limitNmbr;
        }

        $stmt = $this->conn->prepare($query);

        if ($byQuesId) {
            $stmt->bindParam(1, $this->question_id);
        }

        $stmt->execute();

        return $stmt;
    }

    public function getAnswersByCorrect($correct)
    {

        $query = "SELECT l.id,l.answer,l.question_id,l.correct FROM " . $this->table . " l WHERE l.question_id=? AND correct=0 ORDER BY l.id";

        if ($correct) {
            $query = "SELECT l.id,l.answer,l.question_id,l.correct FROM " . $this->table . " l WHERE l.question_id=? AND correct=1 ORDER BY l.id";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->question_id);
        $stmt->execute();

        return $stmt;
    }


    public function getAnswerById($id)
    {

        $query = "SELECT l.id,l.answer,l.question_id,l.correct FROM " . $this->table . " l WHERE l.id=? ORDER BY l.id";

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

    public function createAnswer()
    {

        $query = "INSERT INTO " . $this->table . " (answer,question_id,correct) VALUES (?,?,?)";

        $stmt = $this->conn->prepare($query);

        $this->answer = trim($this->answer);
        $this->question_id = trim($this->question_id);
        $this->correct = trim($this->correct);

        $stmt->bindParam(1, $this->answer);
        $stmt->bindParam(2, $this->question_id);
        $stmt->bindParam(3, $this->correct);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function editAnswer()
    {

        $this->id = ($this->id);

        $check_query = "SELECT l.id FROM " . $this->table . " l WHERE id=?";
        $check = $this->conn->prepare($check_query);
        $check->bindParam(1, $this->id);

        if ($check->execute()) {

            if ($check->rowCount() > 0) {
                $query = "UPDATE " . $this->table . " SET answer=?,correct=? WHERE id=?";

                $stmt = $this->conn->prepare($query);

                $this->answer = trim($this->answer);
                $this->correct = trim($this->correct);

                $stmt->bindParam(1, $this->answer);
                $stmt->bindParam(2, $this->correct);
                $stmt->bindParam(3, $this->id);

                if ($stmt->execute()) {
                    return true;
                }
            }
        }

        return false;
    }

    public function deleteAnswer()
    {

        $query = "DELETE FROM " . $this->table . " WHERE id=?";

        $stmt = $this->conn->prepare($query);

        $this->id = ($this->id);

        $stmt->bindParam(1, $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function deleteAnswersByQuestionId()
    {

        $query = "DELETE FROM " . $this->table . " WHERE question_id=?";

        $stmt = $this->conn->prepare($query);

        $this->question_id = ($this->question_id);

        $stmt->bindParam(1, $this->question_id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
