<?php
class Language
{
    private $conn;
    private $table = "language";

    private $id;
    private $name;
    private $image;
    private $description;
    private $compiler_mode;
    private $language_version;
    private $editor_mode;

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

    public function get_compiler_mode()
    {
        return $this->compiler_mode;
    }

    public function set_compiler_mode($compiler_mode)
    {
        $this->compiler_mode = $compiler_mode;
    }

    public function get_language_version()
    {
        return $this->language_version;
    }

    public function set_language_version($language_version)
    {
        $this->language_version = $language_version;
    }

    public function get_editor_mode()
    {
        return $this->editor_mode;
    }

    public function set_editor_mode($editor_mode)
    {
        $this->editor_mode = $editor_mode;
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

    public function CountLanguages()
    {
        $query = "SELECT id FROM " . $this->table;

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->rowCount();
    }

    public function getLanguages($limitStart=null,$limitNmbr=10)
    {

        $query = "SELECT l.id, l.name, l.description, l.image, l.compiler_mode, l.language_version, l.editor_mode FROM " . $this->table . " l ORDER BY l.id";

        if(!is_null($limitStart)) {
            $query = "SELECT l.id, l.name, l.description, l.image, l.compiler_mode, l.language_version, l.editor_mode FROM " . $this->table . " l ORDER BY l.id LIMIT " . $limitStart . "," . $limitNmbr;
        }

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    public function getLanguagesSorted($sortBy,$order,$limitStart=null,$limitNmbr=10)
    {

        $query = "SELECT l.id, l.name, l.description, l.image, l.compiler_mode, l.language_version, l.editor_mode FROM " . $this->table . " l ORDER BY l." . $sortBy . " " . $order;

        if(!is_null($limitStart)) {
            $query = "SELECT l.id, l.name, l.description, l.image, l.compiler_mode, l.language_version, l.editor_mode FROM " . $this->table . " l ORDER BY l." . $sortBy . " " . $order . " LIMIT " . $limitStart . "," . $limitNmbr;
        }

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    public function getLanguageById($id)
    {

        $query = "SELECT l.id, l.name, l.description, l.image, l.compiler_mode, l.language_version, l.editor_mode FROM " . $this->table . " l WHERE l.id=?";

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

        $query = "INSERT INTO " . $this->table . " (name,description,image,compiler_mode,language_version,editor_mode) VALUES (?,?,?,?,?,?)";

        $stmt = $this->conn->prepare($query);

        $this->name = trim((($this->name)));
        $this->description = trim(($this->description));
        $this->image = trim((($this->image)));
        $this->compiler_mode = trim((($this->compiler_mode)));
        $this->language_version = trim((($this->language_version)));
        $this->editor_mode = trim((($this->editor_mode)));

        $stmt->bindParam(1, $this->name);
        $stmt->bindParam(2, $this->description);
        $stmt->bindParam(3, $this->image);
        $stmt->bindParam(4, $this->compiler_mode);
        $stmt->bindParam(5, $this->language_version);
        $stmt->bindParam(6, $this->editor_mode);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function editLanguage()
    {

        $this->id = (($this->id));

        $check_query = "SELECT l.id FROM " . $this->table . " l WHERE id=?";
        $check = $this->conn->prepare($check_query);
        $check->bindParam(1, $this->id);

        if ($check->execute()) {

            if ($check->rowCount() > 0) {
                $query = "UPDATE " . $this->table . " SET name=?,description=?,compiler_mode=?,language_version=?,editor_mode=? WHERE id=?";

                $stmt = $this->conn->prepare($query);

                $this->name = trim((($this->name)));
                $this->description = trim(($this->description));
                $this->compiler_mode = trim((($this->compiler_mode)));
                $this->language_version = trim((($this->language_version)));
                $this->editor_mode = trim((($this->editor_mode)));

                $stmt->bindParam(1, $this->name);
                $stmt->bindParam(2, $this->description);
                $stmt->bindParam(3, $this->compiler_mode);
                $stmt->bindParam(4, $this->language_version);
                $stmt->bindParam(5, $this->editor_mode);
                $stmt->bindParam(6, $this->id);

                if ($stmt->execute()) {
                    return true;
                }
            }
        }

        return false;
    }

    public function editLanguageImage()
    {

        $this->id = (($this->id));

        $check_query = "SELECT l.id FROM " . $this->table . " l WHERE id=?";
        $check = $this->conn->prepare($check_query);
        $check->bindParam(1, $this->id);

        if ($check->execute()) {

            if ($check->rowCount() > 0) {
                $query = "UPDATE " . $this->table . " SET image=? WHERE id=?";

                $stmt = $this->conn->prepare($query);

                $this->image = trim((($this->image)));

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

        $this->id = (($this->id));

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

        $this->id = trim((($this->id)));

        $stmt->bindParam(1, $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
