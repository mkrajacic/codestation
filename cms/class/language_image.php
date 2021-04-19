<?php
class LanguageImage
{
    private $conn;
    private $table = "language_image";

    private $id;
    private $language_id;
    private $image;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function get_id()
    {
        return $this->language_id;
    }

    public function set_id($id)
    {
        $this->id = $id;
    }

    public function get_language_id()
    {
        return $this->language_id;
    }

    public function set_language_id($language_id)
    {
        $this->language_id = $language_id;
    }

    public function get_image()
    {
        return $this->image;
    }

    public function set_image($image)
    {
        $this->image = $image;
    }

    public function getLanguageImages() {

        $query = "SELECT l.id, l.language_id, l.image FROM " . $this->table . " l ORDER BY l.language_id";
            
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;

    }

    public function addLanguageImage()
    {

        $query = "INSERT INTO " . $this->table . " (language_id,image) VALUES (?,?)";

        $stmt = $this->conn->prepare($query);

        $this->language_id = trim(htmlspecialchars(strip_tags($this->language_id)));
        $this->image = trim(htmlspecialchars(strip_tags($this->image)));

        $stmt->bindParam(1, $this->language_id);
        $stmt->bindParam(2, $this->image);

        if ($stmt->execute()) {
            return true;
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
                $query = "UPDATE " . $this->table . " SET language_id=?,image=? WHERE id=?";

                $stmt = $this->conn->prepare($query);

                $this->language_id = trim(htmlspecialchars(strip_tags($this->language_id)));
                $this->image = trim(htmlspecialchars(strip_tags($this->image)));

                $stmt->bindParam(1, $this->language_id);
                $stmt->bindParam(2, $this->image);
                $stmt->bindParam(3, $this->id);

                if ($stmt->execute()) {
                    return true;
                }
            }
        }

        return false;
    }
}
