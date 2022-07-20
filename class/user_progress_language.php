<?php
class LanguageProgress
{
    private $conn;
    private $table = "user_progress_language";

    private $user_id;
    private $language_id;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function get_user_id()
    {
        return $this->user_id;
    }

    public function set_user_id($user_id)
    {
        $this->user_id = $user_id;
    }

    public function get_language_id()
    {
        return $this->language_id;
    }

    public function set_language_id($language_id)
    {
        $this->language_id = $language_id;
    }

    public function CountPassedLanguages()
    {
        $query = "SELECT language_id FROM " . $this->table . " WHERE user_id=?";

        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(1, $this->user_id);
        $stmt->execute();

        return $stmt->rowCount();
    }

    public function CountLanguagesInProgress() {
        $query = "SELECT DISTINCT(language.id) as language_id FROM user_progress_question INNER JOIN question on user_progress_question.question_id=question.id INNER JOIN lesson on lesson.id=question.lesson_id INNER JOIN language on language.id=lesson.language_id WHERE user_progress_question.user_id=?";

        $stmt = $this->conn->prepare($query);

        $this->user_id = trim((($this->user_id)));
        $stmt->bindParam(1, $this->user_id);
        $stmt->execute();

        return $stmt->rowCount();
    }

    public function getLanguageProgress()
    {

        $query = "SELECT l.user_id, l.language_id FROM " . $this->table . " l WHERE l.user_id=? ORDER BY user_id";

        $stmt = $this->conn->prepare($query);

        $this->user_id = trim((($this->user_id)));
        $stmt->bindParam(1, $this->user_id);
        $stmt->execute();

        return $stmt;
    }
    
    public function getProgressByLanguage()
    {

        $query = "SELECT l.user_id, l.language_id FROM " . $this->table . " l WHERE l.language_id=? AND l.user_id=? ORDER BY user_id";

        $stmt = $this->conn->prepare($query);

        $this->user_id = trim((($this->user_id)));
        $this->language_id = trim((($this->language_id)));
        $stmt->bindParam(1, $this->language_id);
        $stmt->bindParam(2, $this->user_id);

        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                return $stmt;
            } else {
                return false;
            }
        }
    }

    public function createLanguageProgress()
    {

        $query = "INSERT INTO " . $this->table . " (user_id,language_id) VALUES (?,?)";

        $stmt = $this->conn->prepare($query);

        $this->user_id = trim((($this->user_id)));
        $this->language_id = trim((($this->language_id)));

        $stmt->bindParam(1, $this->user_id);
        $stmt->bindParam(2, $this->language_id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function deleteLanguageProgress()
    {
        $query = "DELETE FROM " . $this->table . " WHERE user_id=? AND language_id=?";

        $stmt = $this->conn->prepare($query);

        $this->user_id = trim((($this->user_id)));
        $this->language_id = trim((($this->language_id)));

        $stmt->bindParam(1, $this->user_id);
        $stmt->bindParam(2, $this->language_id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
