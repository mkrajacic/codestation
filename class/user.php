<?php
class User
{
    private $conn;
    private $table = "user_profile";

    private $id;
    private $username;
    private $password;
    private $image;
    private $role_code;

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

    public function get_username()
    {
        return $this->username;
    }

    public function set_username($username)
    {
        $this->username = $username;
    }

    public function get_password()
    {
        return $this->password;
    }

    public function set_password($password)
    {
        $this->password = $password;
    }

    public function get_image()
    {
        return $this->image;
    }

    public function set_image($image)
    {
        $this->image = $image;
    }

    public function get_role_code()
    {
        return $this->role_code;
    }

    public function set_role_code($role_code)
    {
        $this->role_code = $role_code;
    }

    public function isUniqueUsername()
    {
        $query = "SELECT l.id,l.username FROM " . $this->table . " l WHERE username=?";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->username);

        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {

                $user_id = $this->id;

                $user_row = $stmt->fetch(PDO::FETCH_ASSOC);
                extract($user_row);

                if (!empty($user_id)) {
                    if ($user_id == $id) {
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

    public function isCorrectPassword()
    {
        $query = "SELECT l.username,l.password FROM " . $this->table . " l WHERE username=?";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->username);
        $password_temp = $this->password;

        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {

                $user_row = $stmt->fetch(PDO::FETCH_ASSOC);
                extract($user_row);

                if (password_verify($password_temp, $password)) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
    }

    public function getUsers()
    {

        $query = "SELECT l.id, l.username, l.password, l.image, l.role_code FROM " . $this->table . " l ORDER BY l.id";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    public function getUserById($id)
    {

        $query = "SELECT l.id, l.username, l.password, l.image, l.role_code FROM " . $this->table . " l WHERE l.id=?";

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

    public function getIdByUsername($username)
    {

        $query = "SELECT l.id, l.username FROM " . $this->table . " l WHERE l.username=?";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $username);

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

    public function createUser()
    {

        $query = "INSERT INTO " . $this->table . " (username,password) VALUES (?,?)";

        $stmt = $this->conn->prepare($query);

        $this->username = trim(htmlspecialchars(strip_tags($this->username)));
        $this->password = $this->password;

        $stmt->bindParam(1, $this->username);
        $stmt->bindParam(2, $this->password);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function editUsername()
    {

        $this->id = htmlspecialchars(strip_tags($this->id));

        $check_query = "SELECT l.id FROM " . $this->table . " l WHERE id=?";
        $check = $this->conn->prepare($check_query);
        $check->bindParam(1, $this->id);

        if ($check->execute()) {

            if ($check->rowCount() > 0) {
                $query = "UPDATE " . $this->table . " SET username=? WHERE id=?";

                $stmt = $this->conn->prepare($query);

                $this->username = trim(htmlspecialchars(strip_tags($this->username)));

                $stmt->bindParam(1, $this->username);
                $stmt->bindParam(4, $this->id);

                if ($stmt->execute()) {
                    return true;
                }
            }
        }

        return false;
    }

    public function editUserImage()
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

    public function deleteUserImage()
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

    public function deleteUser()
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
