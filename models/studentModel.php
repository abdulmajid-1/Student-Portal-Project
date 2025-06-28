<?php
//include_once 'config.php';

class Student {
    private $S_id;
    private $user_id;
    private $roll_no;
    private $department;
    private $year;

    public function __construct($S_id = null, $user_id = null, $roll_no = null, $department = null, $year = null) {
        $this->S_id = $S_id;
        $this->user_id = $user_id;
        $this->roll_no = $roll_no;
        $this->department = $department;
        $this->year = $year;
    }

    public function setS_id($S_id) {
        $this->S_id = $S_id;
    }
    public function getS_id() {
        return $this->S_id;
    }
    public function setUser_id($user_id) {
        $this->user_id = $user_id;
    }
    public function getUser_id() {
        return $this->user_id;
    }
    public function setRoll_no($roll_no) {
        $this->roll_no = $roll_no;
    }
    public function getRoll_no() {
        return $this->roll_no;
    }
    public function setDepartment($department) {
        $this->department = $department;
    }
    public function getDepartment() {
        return $this->department;
    }
    public function setYear($year) {
        $this->year = $year;
    }
    public function getYear() {
        return $this->year;
    }
    public function __toString() {
        return "Student ID: $this->S_id, User ID: $this->user_id, Roll No: $this->roll_no, Department: $this->department, Year: $this->year";
    }

}

?>