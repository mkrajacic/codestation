<?php
class Language
{
    private $conn;
    private $table = "language";

    private $id;
    private $name;
    private $description;

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

    public function getLanguages() {

        $query = "SELECT l.id, l.name, l.description FROM " . $this->table . " l ORDER BY l.id";
            
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;

    }

    public function createLanguage()
    {

        $query = "INSERT INTO " . $this->table . " (name,description) VALUES (?,?)";

        $stmt = $this->conn->prepare($query);

        $this->name = trim(htmlspecialchars(strip_tags($this->name)));
        $this->description = trim(htmlspecialchars(strip_tags($this->description)));

        $stmt->bindParam(1, $this->name);
        $stmt->bindParam(2, $this->description);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function editLanguage()
    {

        $this->id = htmlspecialchars(strip_tags($this->id));

        $check_query = "SELECT l.id FROM " . $this->table . " l WHERE id=?";
        $check = $this->conn->prepare($check_query);
        $check->bindParam(1, $this->id);

        if ($check->execute()) {

            if ($check->rowCount() > 0) {
                $query = "UPDATE " . $this->table . " SET name=?,description=? WHERE id=?";

                $stmt = $this->conn->prepare($query);

                $this->name = trim(htmlspecialchars(strip_tags($this->name)));
                $this->description = trim(htmlspecialchars(strip_tags($this->description)));

                $stmt->bindParam(1, $this->name);
                $stmt->bindParam(2, $this->description);
                $stmt->bindParam(3, $this->id);

                if ($stmt->execute()) {
                    return true;
                }
            }
        }

        return false;
    }
}
