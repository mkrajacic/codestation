<?php
class QuestionType
{
    private $conn;
    private $table = "question_type";

    private $id;
    private $type;

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

    public function get_type()
    {
        return $this->type;
    }

    public function set_type($type)
    {
        $this->type = $type;
    }

    public function getTypes()
    {

        $query = "SELECT l.id, l.type FROM " . $this->table . " l ORDER BY l.id";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }
    
    public function getTypeById($id)
    {

        $query = "SELECT l.id, l.type FROM " . $this->table . " l WHERE l.id=? ORDER BY l.id";

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

    public function createType()
    {

        $query = "INSERT INTO " . $this->table . " (type) VALUES (?)";

        $stmt = $this->conn->prepare($query);

        $this->type = trim(htmlspecialchars(strip_tags($this->type)));
        $stmt->bindParam(1, $this->type);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function editType()
    {

        $this->id = htmlspecialchars(strip_tags($this->id));

        $check_query = "SELECT l.id FROM " . $this->table . " l WHERE id=?";
        $check = $this->conn->prepare($check_query);
        $check->bindParam(1, $this->id);

        if ($check->execute()) {

            if ($check->rowCount() > 0) {
                $query = "UPDATE " . $this->table . " SET type=? WHERE id=?";

                $stmt = $this->conn->prepare($query);
                $this->type = trim(htmlspecialchars(strip_tags($this->type)));

                $stmt->bindParam(1, $this->type);
                $stmt->bindParam(2, $this->id);

                if ($stmt->execute()) {
                    return true;
                }
            }
        }

        return false;
    }

    public function deleteType()
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
