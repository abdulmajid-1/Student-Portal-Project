CREATE DATABASE IF NOT EXISTS student_portal;
USE student_portal;

-- Users table (for login)
CREATE TABLE users (
    U_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100),
    password VARCHAR(255),
    role ENUM('student', 'admin') DEFAULT 'student'
);

-- Students table (more student details)
CREATE TABLE students (
    S_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    roll_no VARCHAR(50),
    department VARCHAR(100),
    year INT,
    FOREIGN KEY (user_id) REFERENCES users(U_id) ON DELETE CASCADE
);

-- Courses
CREATE TABLE courses (
    C_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    code VARCHAR(20),
    department VARCHAR(100)
);

-- Enrollment
CREATE TABLE enrollments (
    EN_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    course_id INT,
    FOREIGN KEY (student_id) REFERENCES students(S_id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(C_id) ON DELETE CASCADE
);

-- Grades
CREATE TABLE grades (
    G_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    course_id INT,
    grade VARCHAR(2),
    FOREIGN KEY (student_id) REFERENCES students(S_id),
    FOREIGN KEY (course_id) REFERENCES courses(C_id)
);

-- CREATE TABLE teachers (
--    T_id INT AUTO_INCREMENT PRIMARY KEY,
--    name VARCHAR(100) NOT NULL,
--    email VARCHAR(100) UNIQUE NOT NULL,
--    phone VARCHAR(15),
--    department VARCHAR(100),
--    designation VARCHAR(50),
--    hire_date DATE
-- );
USE student_portal;

DELIMITER //

CREATE PROCEDURE insert_user(
    IN p_name VARCHAR(100),
    IN p_email VARCHAR(100),
    IN p_password VARCHAR(255),
    IN p_role ENUM('student', 'admin')
)
BEGIN
    INSERT INTO users(name, email, password, role)
    VALUES (p_name, p_email, p_password, p_role);
END //

DELIMITER ;

DELIMITER //

CREATE PROCEDURE add_course (
    IN p_code VARCHAR(50),
    IN p_C_id INT,
    IN p_department VARCHAR(100),
    IN p_name VARCHAR(100)
)
BEGIN
    INSERT INTO courses (code, C_id, department, name)
    VALUES (p_code, p_C_id, p_department, p_name);
END //

DELIMITER ;


DELIMITER //

CREATE PROCEDURE show_all_courses()
BEGIN
    SELECT * FROM courses;
END //

DELIMITER ;

DELIMITER //

CREATE PROCEDURE delete_course (
    IN p_C_id INT
)
BEGIN
    DELETE FROM courses
    WHERE C_id = p_C_id;
END //

DELIMITER ;

DELIMITER //

CREATE PROCEDURE enroll_student (
    IN p_student_id INT,
    IN p_course_id INT
)
BEGIN
    INSERT INTO enrollments (student_id, course_id)
    VALUES (p_student_id, p_course_id);
END //

DELIMITER ;

DELIMITER //

CREATE PROCEDURE delete_enrollment_by_id (
    IN p_en_id INT
)
BEGIN
    DELETE FROM enrollments
    WHERE EN_id = p_en_id;
END //

DELIMITER ;


