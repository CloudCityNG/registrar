<?php
    class Course
    {
        private $subject;
        private $course_number;
        private $id;

        function __construct($subject, $course_number, $id = null)
        {
            $this->subject = $subject;
            $this->course_number = $course_number;
            $this->id = $id;
        }

        function getSubject()
        {
            return $this->subject;
        }

        function getCourseNumber()
        {
            return $this->course_number;
        }

        function getId()
        {
            return $this->id;
        }

        function save()
        {
              $GLOBALS['DB']->exec("INSERT INTO courses (subject, course_number) VALUES ('{$this->getSubject()}', '{$this->getCourseNumber()}');");
              $this->id = $GLOBALS['DB']->lastInsertId();
        }

        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM courses WHERE id = {$this->getId()};");
            $GLOBALS['DB']->exec("DELETE FROM students_courses WHERE course_id = {$this->getId()};");
        }

        function deleteStudent($student)
        {
            $GLOBALS['DB']->exec("DELETE FROM students_courses WHERE student_id = {$student->getId()};");
        }

        function addStudent($student)
        {
            $GLOBALS['DB']->exec("INSERT INTO students_courses (course_id, student_id) VALUES ({$this->getId()}, {$student->getId()});");
        }

        function getStudents()
        {
            $query = $GLOBALS['DB']->query("SELECT students.* FROM courses
                JOIN students_courses ON (courses.id = students_courses.course_id)
                JOIN students ON (students_courses.student_id = students.id)
                WHERE courses.id = {$this->getId()};");
            $students = $query->fetchAll(PDO::FETCH_ASSOC);

            $students_results = array();
            foreach($students as $student) {
                // $student_id = $id['student_id'];
                // $result = $GLOBALS['DB']->query("SELECT * FROM students WHERE id = {$student_id};");
                // $returned_student = $result->fetchAll(PDO::FETCH_ASSOC);

                $name = $student['name'];
                $enrollment_date = $student['enrollment_date'];
                $id = $student['id'];
                $new_student = new Student($name, $enrollment_date, $id);
                array_push($students_results, $new_student);
            }
            return $students_results;
        }

        static function getAll()
        {
            $returned_courses = $GLOBALS['DB']->query("SELECT * FROM courses;");
            $courses = array();
            foreach($returned_courses as $course) {
                $subject = $course['subject'];
                $course_number = $course['course_number'];
                $id = $course['id'];
                $new_course = new Course($subject, $course_number, $id);
                array_push($courses, $new_course);
            }
            return $courses;
        }

        static function deleteAll()
        {
          $GLOBALS['DB']->exec("DELETE FROM courses;");
        }

        static function find($search_id)
        {
            $found_course = null;
            $courses = Course::getAll();
            foreach($courses as $course) {
                $course_id = $course->getId();
                if ($course_id == $search_id) {
                  $found_course = $course;
                }
            }
            return $found_course;
        }
    }
?>
