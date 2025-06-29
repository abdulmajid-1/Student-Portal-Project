<?php
class EnrollmentModel
{
    private $enrollment_id;
    private $student_id;
    private $course_id;

    public function __construct($enrollment_id = null, $student_id = null, $course_id = null)
    {
        $this->setEnrollmentId($enrollment_id);
        $this->setStudentId($student_id);
        $this->setCourseId($course_id);
    }

    public function setEnrollmentId($enrollment_id)
    {
        $this->enrollment_id = $enrollment_id;
    }

    public function getEnrollmentId()
    {
        return $this->enrollment_id;
    }

    public function setStudentId($student_id)
    {
        $this->student_id = $student_id;
    }

    public function getStudentId()
    {
        return $this->student_id;
    }

    public function setCourseId($course_id)
    {
        $this->course_id = $course_id;
    }

    public function getCourseId()
    {
        return $this->course_id;
    }
}
