<?php
class Lesson
{
    private $conn;
    private $table = "lesson";

    private $id;
    private $name;
    private $description;
    private $language_id;

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

    public function get_name()
    {
        return $this->name;
    }

    public function set_name($name)
    {
        $this->name = $name;
    }

    public function get_description()
    {
        return $this->description;
    }

    public function set_description($desc)
    {
        $this->description = $desc;
    }

    public function get_language_id()
    {
        return $this->language_id;
    }

    public function set_language_id($language_id)
    {
        $this->language_id = $language_id;
    }

    public function isUniqueName()
    {
        $query = "SELECT l.id,l.name FROM " . $this->table . " l WHERE name=? AND language_id=?";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->name);
        $stmt->bindParam(2, $this->language_id);

        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {

                $lesson_id = $this->id;

                $lesson_row = $stmt->fetch(PDO::FETCH_ASSOC);
                extract($lesson_row);

                if (!empty($lesson_id)) {
                    if ($lesson_id == $id) {
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

    public function getLessons($byLangId)
    {

        $query = "SELECT l.id, l.name, l.description FROM " . $this->table . " l ORDER BY l.id";

        if($byLangId) {
            $query = "SELECT l.id, l.name, l.description FROM " . $this->table . " l WHERE language_id=? ORDER BY l.id";
        }

        $stmt = $this->conn->prepare($query);

        if($byLangId) {
            $stmt->bindParam(1, $this->language_id);
        }

        $stmt->execute();

        return $stmt;
    }
    

    public function getLessonById($id)
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

    public function createLesson()
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

    public function editLesson()
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

    public function deleteLesson()
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
