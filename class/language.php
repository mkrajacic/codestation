<?php
class Language
{
    private $conn;
    private $table = "language";

    private $id;
    private $name;
    private $image;
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

    public function get_image()
    {
        return $this->image;
    }

    public function set_image($image)
    {
        $this->image = $image;
    }

    public function get_description()
    {
        return $this->description;
    }

    public function set_description($desc)
    {
        $this->description = $desc;
    }

    public function isUniqueName()
    {
        $query = "SELECT l.id,l.name FROM " . $this->table . " l WHERE name=?";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->name);

        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {

                $lang_id = $this->id;

                $language_row = $stmt->fetch(PDO::FETCH_ASSOC);
                extract($language_row);

                if (!empty($lang_id)) {
                    if ($lang_id == $id) {
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

    public function getLanguages()
    {

        $query = "SELECT l.id, l.name, l.description, l.image FROM " . $this->table . " l ORDER BY l.id";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    public function getLanguageById($id)
    {

        $query = "SELECT l.id, l.name, l.description, l.image FROM " . $this->table . " l WHERE l.id=?";

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

    public function getImageById()
    {

        $query = "SELECT l.image FROM " . $this->table . " l WHERE l.id=?";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);

        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                return $stmt;
            } else {
                return false;
            }
        }
    }

    public function createLanguage()
    {

        $query = "INSERT INTO " . $this->table . " (name,description,image) VALUES (?,?,?)";

        $stmt = $this->conn->prepare($query);

        $this->name = trim(htmlspecialchars(strip_tags($this->name)));
        $this->description = trim(htmlspecialchars(strip_tags($this->description)));
        $this->image = trim(htmlspecialchars(strip_tags($this->image)));

        $stmt->bindParam(1, $this->name);
        $stmt->bindParam(2, $this->description);
        $stmt->bindParam(3, $this->image);

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

    public function editLanguageImage()
    {

        $this->id = htmlspecialchars(strip_tags($this->id));

        $check_query = "SELECT l.id FROM " . $this->table . " l WHERE id=?";
        $check = $this->conn->prepare($check_query);
        $check->bindParam(1, $this->id);

        if ($check->execute()) {

            if ($check->rowCount() > 0) {
                $query = "UPDATE " . $this->table . " SET image=? WHERE id=?";

                $stmt = $this->conn->prepare($query);

                $this->image = trim(htmlspecialchars(strip_tags($this->image)));

                $stmt->bindParam(1, $this->image);
                $stmt->bindParam(2, $this->id);

                if ($stmt->execute()) {
                    return true;
                }
            }
        }

        return false;
    }

    public function deleteLanguageImage()
    {

        $this->id = htmlspecialchars(strip_tags($this->id));

        $check_query = "SELECT l.id FROM " . $this->table . " l WHERE id=?";
        $check = $this->conn->prepare($check_query);
        $check->bindParam(1, $this->id);

        if ($check->execute()) {

            if ($check->rowCount() > 0) {
                $query = "UPDATE " . $this->table . " SET image=NULL WHERE id=?";

                $stmt = $this->conn->prepare($query);

                $stmt->bindParam(1, $this->id);

                if ($stmt->execute()) {
                    return true;
                }
            }
        }

        return false;
    }

    public function deleteLanguage()
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
