<?php
class Teacher
{
    private $teacher_id;
    private $user_id;
    private $name;
    private $email;
    private $phone_no;
    private $designation;
    private $hire_date;
    private $salary;

    public function __construct($teacher_id = null, $user_id = null, $name = null, $email = null, $phone_no = null, $designation = null, $hire_date = null, $salary = null)
    {
        $this->setTeacherID($teacher_id);
        $this->setUserID($user_id);
        $this->setName($name);
        $this->setEmail($email);
        $this->setPhoneNo($phone_no);
        $this->setDesignation($designation);
        $this->setHireDate($hire_date);
        $this->setSalary($salary);
    }
    public function setTeacherID($teacher_id)
    {
        $this->teacher_id = $teacher_id;
    }
    public function getTeacherID()
    {
        return $this->teacher_id;
    }
    public function setUserID($user_id)
    {
        $this->user_id = $user_id;
    }
    public function getUserID()
    {
        return $this->user_id;
    }
    public function setName($name)
    {
        $this->name = $name;
    }
    public function getName()
    {
        return $this->name;
    }
    public function setEmail($email)
    {
        $this->email = $email;
    }
    public function getEmail()
    {
        return $this->email;
    }
    public function setPhoneNo($phone_no)
    {
        $this->phone_no = $phone_no;
    }
    public function getPhoneNo()
    {
        return $this->phone_no;
    }
    public function setDesignation($designation)
    {
        $this->designation = $designation;
    }
    public function getDesignation()
    {
        return $this->designation;
    }
    public function setHireDate($hire_date)
    {
        $this->hire_date = $hire_date;
    }
    public function getHireDate()
    {
        return $this->hire_date;
    }
    public function setSalary($salary)
    {
        $this->salary = $salary;
    }
    public function getSalary()
    {
        return $this->salary;
    }
    public function __toString()
    {
        return "Teacher ID: " . $this->getTeacherID() . ", Name: " . $this->getName() . ", Email: " . $this->getEmail() .
            ", Phone No: " . $this->getPhoneNo() . ", Designation: " . $this->getDesignation() .
            ", Hire Date: " . $this->getHireDate() . ", Salary: " . $this->getSalary();
    }
}
?>