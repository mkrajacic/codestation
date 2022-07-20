<?php
class LessonProgress
{
    private $conn;
    private $table = "user_progress_lesson";

    private $user_id;
    private $lesson_id;

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

    public function get_lesson_id()
    {
        return $this->lesson_id;
    }

    public function set_lesson_id($lesson_id)
    {
        $this->lesson_id = $lesson_id;
    }

    public function CountPassedLessons()
    {
        $query = "SELECT lesson_id FROM " . $this->table . " WHERE user_id=?";

        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(1, $this->user_id);
        $stmt->execute();

        return $stmt->rowCount();
    }

    public function getLessonProgress()
    {

        $query = "SELECT l.user_id, l.lesson_id FROM " . $this->table . " l WHERE l.user_id=?";

        $stmt = $this->conn->prepare($query);

        $this->user_id = trim((($this->user_id)));
        $stmt->bindParam(1, $this->user_id);
        $stmt->execute();

        return $stmt;
    }

    public function compareLessonProgress($inProgress=false)
    {

        $query = "SELECT language_id,language_name,language_image,sum(passed_lessons) as passed_lessons,sum(lessons_in_language) as lessons_in_language from( (SELECT count(lesson.id) as passed_lessons,0 as lessons_in_language,language.name as language_name,language.image as language_image,language.id as language_id FROM lesson INNER JOIN language ON lesson.language_id=language.id INNER JOIN user_progress_lesson on lesson.id=user_progress_lesson.lesson_id WHERE user_progress_lesson.user_id=? group by lesson.language_id) union ALL (SELECT 0 as passed_lessons, count(ls.id) AS lessons_in_language, lg.name as language_name,lg.image as language_image,lg.id as language_id FROM lesson ls INNER JOIN language lg ON ls.language_id=lg.id group by language_id) ) t12 group by language_id";

        if($inProgress) {
            $query.= " HAVING passed_lessons>0";
        }

        $stmt = $this->conn->prepare($query);

        $this->user_id = trim((($this->user_id)));
        $stmt->bindParam(1, $this->user_id);
        $stmt->execute();

        return $stmt;
    }
    

    public function getLessonsInProgress() {
        $query = "SELECT DISTINCT(lesson.id) as lesson_id,lg.name FROM question INNER JOIN user_progress_question ON question.id=user_progress_question.question_id INNER JOIN lesson on lesson.id=question.lesson_id INNER JOIN language lg ON lesson.language_id=lg.id WHERE user_progress_question.user_id=?";

        $stmt = $this->conn->prepare($query);

        $this->user_id = trim((($this->user_id)));
        $stmt->bindParam(1, $this->user_id);
        $stmt->execute();

        return $stmt;
    }

    public function compareLessonProgressByLanguage($language_id)
    {

        $query = "SELECT (SELECT count(language.id) FROM lesson INNER JOIN language ON lesson.language_id=language.id INNER JOIN user_progress_lesson on lesson.id=user_progress_lesson.lesson_id WHERE user_progress_lesson.user_id=? AND language.id=?) AS passed_lessons, count(ls.id) AS lessons_in_language, lg.name as language_name,lg.id as language_id FROM lesson ls INNER JOIN language lg ON ls.language_id=lg.id WHERE lg.id=?";

        $stmt = $this->conn->prepare($query);

        $this->user_id = trim((($this->user_id)));
        $stmt->bindParam(1, $this->user_id);
        $stmt->bindParam(2, $language_id);
        $stmt->bindParam(3, $language_id);
        $stmt->execute();

        return $stmt;
    }


    
    public function getProgressByLesson()
    {

        $query = "SELECT l.user_id, l.lesson_id FROM " . $this->table . " l WHERE l.user_id=? AND l.lesson_id=?";

        $stmt = $this->conn->prepare($query);

        $this->user_id = $this->user_id;
        $this->lesson_id = $this->lesson_id;
        $stmt->bindParam(1, $this->user_id);
        $stmt->bindParam(2, $this->lesson_id);

        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                return $stmt;
            } else {
                return false;
            }
        }
    }

    public function getProgressByLanguage($language_id)
    {

        $query = "SELECT lesson.id as lesson_id,lg.id as language_id FROM lesson INNER JOIN user_progress_lesson ON lesson.id=user_progress_lesson.lesson_id INNER JOIN language lg ON lesson.language_id=lg.id WHERE user_progress_lesson.user_id=? AND lg.id=?";

        $stmt = $this->conn->prepare($query);

        $this->user_id = $this->user_id;
        $stmt->bindParam(1, $this->user_id);
        $stmt->bindParam(2, $language_id);

        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                return $stmt;
            } else {
                return false;
            }
        }
    }

    public function createLessonProgress()
    {

        $query = "INSERT INTO " . $this->table . " (user_id,lesson_id) VALUES (?,?)";

        $stmt = $this->conn->prepare($query);

        $this->user_id = trim((($this->user_id)));
        $this->lesson_id = trim((($this->lesson_id)));

        $stmt->bindParam(1, $this->user_id);
        $stmt->bindParam(2, $this->lesson_id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function deleteLessonProgress()
    {
        $query = "DELETE FROM " . $this->table . " WHERE user_id=? AND lesson_id=?";

        $stmt = $this->conn->prepare($query);

        $this->user_id = trim((($this->user_id)));
        $this->lesson_id = trim((($this->lesson_id)));

        $stmt->bindParam(1, $this->user_id);
        $stmt->bindParam(2, $this->lesson_id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
