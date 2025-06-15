<?php

class Course
{
    private $course_code;
    private $course_id;
    private $course_department;
    private $course_name;


    public function __construct($course_code = null, $course_id = null, $course_department = null, $course_name = null)
    {
        $this->setCourseCode($course_code);
        $this->setCourseId($course_id);
        $this->setCourseDepartment($course_department);
        $this->setCourseName($course_name);
    }
    public function setCourseCode($course_code)
    {
        $this->course_code = $course_code;
    }
    public function getCourseCode()
    {
        return $this->course_code;
    }
    public function setCourseId($course_id)
    {
        $this->course_id = $course_id;
    }
    public function getCourseId()
    {
        return $this->course_id;
    }
    public function setCourseDepartment($course_department)
    {
        $this->course_department = $course_department;
    }

    public function getCourseDepartment()
    {
        return $this->course_department;
    }
    public function setCourseName($course_name)
    {
        $this->course_name = $course_name;
    }
    public function getCourseName()
    {
        return $this->course_name;
    }
    public function __toString()
    {
        return "Course Code: " . $this->getCourseCode() . ", Course ID: " . $this->getCourseId() . ", Department: " . $this->getCourseDepartment() . ", Course Name: " . $this->getCourseName();
    }
}


?>
