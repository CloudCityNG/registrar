<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Student.php";
    require_once "src/Course.php";

    $server = 'mysql:host=localhost;dbname=registrar_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class StudentTest extends PHPUnit_Framework_TestCase
    {

        protected function tearDown()
        {
            Student::deleteAll();
            // Course::deleteAll();
        }

        function testGetName()
        {
            //Arrange
            $name = "Harry Houdini";
            $enrollment_date = '2016-03-01';
            $test_student = new Student($name, $enrollment_date);

            //Act
            $result = $test_student->getName();

            //Assert
            $this->assertEquals($name, $result);
        }

        function testGetEnrollmentDate()
        {
            //Arrange
            $name = "Harry Houdini";
            $enrollment_date = '2016-03-01';
            $test_student = new Student($name, $enrollment_date);

            //Act
            $result = $test_student->getEnrollmentDate();

            //Assert
            $this->assertEquals($enrollment_date, $result);
        }

        function testGetId()
        {
            //Arrange
            $name = "Harry Houdini";
            $enrollment_date = '2016-03-01';
            $id = 1;
            $test_student = new Student($name, $enrollment_date, $id);


            //Act
            $result = $test_student->getId();

            //Assert
            $this->assertEquals(1, $result);
        }

        function testSave()
        {
            //Arrange
            $name = "Harry Houdini";
            $enrollment_date = '2016-03-01';
            $id = 1;
            $test_student = new Student($name, $enrollment_date, $id);

            //Act
            $test_student->save();

            //Assert
            $result = Student::getAll();
            $this->assertEquals($test_student, $result[0]);
        }

        function testSaveSetsId()
        {
            //Arrange
            $name = "Harry Houdini";
            $enrollment_date = '2016-03-01';
            $test_student = new Student($name, $enrollment_date);

            //Act
            $test_student->save();

            //Assert
            $this->assertEquals(true, is_numeric($test_student->getId()));
        }

        function testGetAll()
        {
            //Arrange
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
            $result = Student::getAll();

            //Assert
            $this->assertEquals([$test_student, $test_student2], $result);
        }

        function testDeleteAll()
        {
            //Arrange
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
            Student::deleteAll();

            //Assert
            $result = Student::getAll();
            $this->assertEquals([], $result);
        }

        function testFind()
        {
            //Arrange
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
            $result = Student::find($test_student->getId());

            //Assert
            $this->assertEquals($test_student, $result);
        }


        function testDeleteStudent()
        {
            //Arrange
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
            $test_student->delete();

            //Assert
            $this->assertEquals(Student::getAll(), [$test_student2]);
        }


        function testAddCourse()
        {
            //Arrange
            $subject = "Chemistry 101";
            $course_number = 'CHEM101';
            $id = 1;
            $test_course = new Course($subject, $course_number, $id);
            $test_course->save();

            $name = "Harry Houdini";
            $enrollment_date = '2016-03-01';
            $id = 1;
            $test_student = new Student($name, $enrollment_date, $id);
            $test_student->save();

            //Act
            $test_student->addCourse($test_course);

            //Assert
            $this->assertEquals($test_student->getCourses(), [$test_course]);
        }

        function testGetCourses()
        {
            //Arrange
            $name = "Harry Houdini";
            $enrollment_date = '2016-03-01';
            $id = 1;
            $test_student = new Student($name, $enrollment_date, $id);
            $test_student->save();


            $subject2 = "Chemistry 101";
            $course_number = 'CHEM101';
            $id2 = 1;
            $test_course = new Course($subject2, $course_number, $id2);
            $test_course->save();

            $subject3 = "Chemistry 103";
            $course_number2 = 'CHEM103';
            $id3 = 2;
            $test_course2 = new Course($subject3, $course_number, $id3);
            $test_course2->save();

            //Act
            $test_student->addCourse($test_course);
            $test_student->addCourse($test_course2);

            //Assert
            $this->assertEquals($test_student->getCourses(), [$test_course, $test_course2]);
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
            $test_student->addCourse($test_course);
            $test_student->delete();

            //Assert
            $this->assertEquals([], $test_course->getStudents());
        }

    }
?>
