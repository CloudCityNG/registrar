<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Course.php";
    require_once "src/Student.php";

    $server = 'mysql:host=localhost;dbsubject=registrar_test';
    $usersubject = 'root';
    $password = 'root';
    $DB = new PDO($server, $usersubject, $password);


    class CourseTest extends PHPUnit_Framework_TestCase
    {

        protected function tearDown()
        {
          Course::deleteAll();
          Student::deleteAll();
        }

        function testGetSubject()
        {
            //Arrange
            $subject = "Math";
            $course_number = "MTH101";
            $test_course = new Course($subject, $course_number);

            //Act
            $result = $test_course->getSubject();

            //Assert
            $this->assertEquals($subject, $result);

        }

        function testGetCourseNumber()
        {
            //Arrange
            $subject = "Math";
            $course_number = "MTH101";
            $test_course = new Course($subject, $course_number);

            //Act
            $result = $test_course->getCourseNumber();

            //Assert
            $this->assertEquals($course_number, $result);

        }

        function testGetId()
        {
            //Arrange
            $subject = "Math";
            $course_number = "MTH101";
            $id = 1;
            $test_course = new Course($subject, $course_number, $id);

            //Act
            $result = $test_course->getId();

            //Assert
            $this->assertEquals(1, $result);
        }

        function testSave()
        {
            //Arrange
            $subject = "Math";
            $course_number = "MTH101";
            $id = 1;
            $test_course = new Course($subject, $course_number, $id);
            $test_course->save();

            //Act
            $result = Course::getAll();

            //Assert
            $this->assertEquals($test_course, $result[0]);
        }

        function testDeleteCourse()
        {
            //Arrange
            $subject = "Math";
            $course_number = "MTH101";
            $id = 1;
            $test_course = new Course($subject, $course_number, $id);
            $test_course->save();

            $subject2 = "Chem";
            $course_number2 = "CHEM101";
            $id2 = 2;
            $test_course2 = new Course($subject2, $course_number2, $id2);
            $test_course2->save();

            //Act
            $test_course->delete();

            //Assert
            $this->assertEquals([$test_course2], Course::getAll());
        }

        function testGetAll()
        {
            //Arrange
            $subject = "Math";
            $course_number = "MTH101";
            $id = 1;
            $test_course = new Course($subject, $course_number, $id);
            $test_course->save();

            $subject2 = "Chem";
            $course_number2 = "CHEM101";
            $id2 = 2;
            $test_course2 = new Course($subject2, $course_number2, $id2);
            $test_course2->save();

            //Act
            $result = Course::getAll();

            //Assert
            $this->assertEquals([$test_course, $test_course2], $result);
        }

        function testDeleteAll()
        {
            //Arrange
            $subject = "Math";
            $course_number = "MTH101";
            $id = 1;
            $test_course = new Course($subject, $course_number, $id);
            $test_course->save();

            $subject2 = "Chem";
            $course_number2 = "CHEM101";
            $id2 = 2;
            $test_course2 = new Course($subject2, $course_number2, $id2);
            $test_course2->save();

            //Act
            Course::deleteAll();

            //Assert
            $result = Course::getAll();
            $this->assertEquals([], $result);
        }

        function testFind()
        {
            //Arrange
            $subject = "Math";
            $course_number = "MTH101";
            $id = 1;
            $test_course = new Course($subject, $course_number, $id);
            $test_course->save();

            $subject2 = "Chem";
            $course_number2 = "CHEM101";
            $id2 = 2;
            $test_course2 = new Course($subject2, $course_number2, $id2);
            $test_course2->save();

            //Act
            $result = Course::find($test_course->getId());

            //Assert
            $this->assertEquals($test_course, $result);
        }

        function testAddStudent()
        {
            //Arrange
            $subject = "Math";
            $course_number = "MTH101";
            $id = 1;
            $test_course = new Course($subject, $course_number, $id);
            $test_course->save();

            $name = "Harry Houdini";
            $enrollment_date = '2016-03-01';
            $id = 1;
            $test_student = new Student($name, $enrollment_date, $id);
            $test_student->save();

            //Act
            $test_course->addStudent($test_student);

            //Assert
            $this->assertEquals([$test_student], $test_course->getStudents());
        }

        function testGetStudents()
        {
            //Arrange
            $subject = "Math";
            $course_number = "MTH101";
            $id = 1;
            $test_course = new Course($subject, $course_number, $id);
            $test_course->save();

            $name = "Harry Houdini";
            $enrollment_date = '2016-03-01';
            $id = 1;
            $test_student = new Student($name, $enrollment_date, $id);
            $test_student->save();

            $name2 = "Maggie Pie";
            $enrollment_date2 = '2016-03-05';
            $id2 = 2;
            $test_student2 = new Student($name2, $enrollment_date2, $id2);
            $test_student2->save();

            //Act
            $test_course->addStudent($test_student);
            $test_course->addStudent($test_student2);

            //Assert
            $this->assertEquals([$test_student, $test_student2], $test_course->getStudents());
        }

        function testDelete()
        {
            //Arrange
            $subject = "Math";
            $course_number = "MTH101";
            $id = 1;
            $test_course = new Course($subject, $course_number, $id);
            $test_course->save();

            $name = "Maggie Pie";
            $enrollment_date = '2016-03-05';
            $id2 = 2;
            $test_student = new Student($name, $enrollment_date, $id2);
            $test_student->save();

            //Act
            $test_course->addStudent($test_student);
            $test_course->delete();

            //Assert
            $this->assertEquals([], $test_student->getCourses());
        }
    }

?>t
