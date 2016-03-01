<?php
    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Course.php";
    require_once __DIR__."/../src/Student.php";

    // create silex object with twig templating
    $app = new Silex\Application();
    $app->register(new Silex\Provider\TwigServiceProvider(), array(
        'twig.path' => __DIR__.'/../views'
    ));

    // setup server for database
    $server = 'mysql:host=localhost;dbname=registrar';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    // allow patch and delete request to be handled by browser
    use Symfony\Component\HttpFoundation\Request;
    Request::enableHttpMethodParameterOverride();

    // Homepage, lists all courses
    $app->get("/", function() use ($app) {
        return $app['twig']->render('index.html.twig', array(
            'courses' => Course::getAll(),
        ));
    });

    $app->post("/", function() use ($app) {
        $new_course = new Course($_POST['course-subject'], $_POST['course-number']);
        $new_course->save();

        return $app['twig']->render('index.html.twig', array(
            'courses' => Course::getAll(),
            'message' => array(
                'type' => 'info',
                'text' => $_POST['course-subject'] . " was added to the list of courses."
            )
        ));
    });

    $app->delete("/deleteAll", function() use ($app) {
        Course::deleteAll();

        return $app['twig']->render('index.html.twig', array(
            'courses' => Course::getAll(),
            'message' => array(
                'type' => 'danger',
                'text' => 'All courses and thier students have been deleted.'
            )
        ));
    });

    // Course pages, lists students, allows edit and deletion
    $app->get("/course/{id}", function($id) use ($app) {
        $course = Course::find($id);

        return $app['twig']->render('course.html.twig', array(
            'course' => $course,
            'students' => $course->getStudents(),
        ));
    });

    // $app->get("/course/{id}/edit", function($id) use ($app) {
    //     $course = Course::find($id);
    //
    //     return $app['twig']->render('course_edit.html.twig', array(
    //         'course' => $course
    //     ));
    // });

    $app->get("/course/{id}/delete", function($id) use ($app) {
        $course = Course::find($id);

        return $app['twig']->render('course_delete.html.twig', array(
            'course' => $course
        ));
    });

    // $app->patch("/course/{id}", function($id) use ($app) {
    //     $course = Course::find($id);
    //     $course->update($_POST['new-name']);
    //
    //     return $app['twig']->render('course.html.twig', array(
    //         'course' => $course,
    //         'students' => $course->getStudents(),
    //         'message' => array(
    //             'type' => 'info',
    //             'text' => 'The name of your course was updated to ' . $course->getName()
    //         )
    //     ));
    // });

    $app->delete("/course/{id}", function($id) use ($app) {
        $course = Course::find($id);
        $course->delete();

        return $app['twig']->render('index.html.twig', array(
            'courses' => Course::getAll(),
            'message' => array(
                'type' => 'danger',
                'text' => $course->getSubject() . " was deleted."
            )
        ));
    });

    $app->delete("/course/{id}/deleteAllStudents", function($id) use ($app) {
        $course = Course::find($id);
        $students = $course->getStudents();
        foreach ($students as $student) {
            $student->delete();
        }

        return $app['twig']->render('course.html.twig', array(
            'course' => $course,
            'message' => array(
                'type' => 'danger',
                'text' => 'All students of ' . $course->getSubject() . ' have been deleted.'
            )
        ));
    });

    $app->post("/course/{id}/addStudent", function($id) use ($app) {
        $course = Course::find($id);
        $date = '2016-03-01';
        $new_student = new Student($_POST['student-name'], $date, $id);
        $new_student->save();
        $course->addStudent($new_student);

        return $app['twig']->render('course.html.twig', array(
            'course' => $course,
            'students' => $course->getStudents(),
            'message' => array(
                'type' => 'info',
                'text' => $new_student->getName() . " was added to " . $course->getSubject() . "'s student list"
            )
        ));
    });

    // Student pages
    $app->get("/student/{student_id}/{course_id}/delete", function($student_id, $course_id) use ($app) {
        $student = Student::find($student_id);
        $course = Course::find($course_id);

        return $app['twig']->render('student_delete.html.twig', array(
            'course' => $course,
            'student' => $student
        ));
    });

    // $app->get("/student/{id}/edit", function($id) use ($app) {
    //     $student = Student::find($id);
    //
    //     return $app['twig']->render('student_edit.html.twig', array(
    //         'student' => $student
    //     ));
    // });

    $app->delete("/student/{student_id}/{course_id}", function($student_id, $course_id) use ($app) {
        $student = Student::find($student_id);
        $student->delete();
        $course = Course::find($course_id);

        return $app['twig']->render('course.html.twig', array(
            'course' => $course,
            'students' => $course->getStudents(),
            'message' => array(
                'type' => 'danger',
                'text' => $student->getName() . " was deleted."
            )
        ));
    });
    //
    // $app->patch("/student/{id}", function($id) use ($app) {
    //     $student = Student::find($id);
    //     $student->update($_POST['new-name']);
    //     $course = Course::find($student->getCourseId());
    //
    //     return $app['twig']->render('course.html.twig', array(
    //         'students' => $course->getStudents(),
    //         'course' => $course,
    //         'message' => array(
    //             'type' => 'info',
    //             'text' => 'The name of your student was updated to ' . $student->getName()
    //         )
    //     ));
    // });

    return $app;
?>
