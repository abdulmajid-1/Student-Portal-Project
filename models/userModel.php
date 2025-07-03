<?php
    class User
    {
        private $user_id;
        private $name;
        private $email;
        private $password;
        private $role;

        public function __construct($user_id = null, $name = null, $email = null, $password = null, $role = null, $conn = null)
        {
            $this -> setUserID($user_id);
            $this -> setName($name);    
            $this -> setEmail($email);
<<<<<<< HEAD
            $this -> setPassword($password);
=======
            // $this -> setPassword($password);
>>>>>>> 244e2f4eefbebb5f44c6822d91d693c6f5f92baf
            $this -> setRole($role);

        }
        public function setUserID($user_id)
        {
            $this -> user_id = $user_id;
        }
        public function getUserID()
        {
            return $this -> user_id;
        }
        public function setName($name)
        {
            $this -> name = $name;
        }
        public function getName()
        {
            return $this -> name;
        }
        public function setEmail($email)
        {
            $this -> email = $email;
        }
        public function getEmail()
        {
            return $this -> email;
        }
        public function setPassword($password)
        {
            $this -> password = $password;
        }
        public function getPassword()
        {
            return $this -> password;
        }
        public function setRole($role)
        {
            $this -> role = $role;
        }
        public function getRole()
        {
            return $this -> role;
        }

        public function __tostring()
        {
            return "User ID: " . $this -> getUserID() . ", Name: " . $this -> getName() . ", Email: " . $this -> getEmail() . ", Role: " . $this -> getRole();
        }
    }

    
?>