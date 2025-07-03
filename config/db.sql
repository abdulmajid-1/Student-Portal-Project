CREATE DATABASE IF NOT EXISTS student_portal;
USE student_portal;

-- Users table (for login)
CREATE TABLE users (
    U_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    role ENUM('user', 'admin', 'teacher') DEFAULT 'user'
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
    CONSTRAINT fk_student FOREIGN KEY (student_id) REFERENCES students(S_id) ON DELETE CASCADE,
    CONSTRAINT fk_course FOREIGN KEY (course_id) REFERENCES courses(C_id) ON DELETE CASCADE,
    CONSTRAINT unique_enrollment UNIQUE (student_id, course_id)
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



CREATE TABLE teachers (
    T_id INT AUTO_INCREMENT PRIMARY KEY,
    phone VARCHAR(15),
    designation VARCHAR(50),
    hire_date DATE,
    salary int 
);

ALTER TABLE teachers
ADD COLUMN user_id INT AFTER T_id;

ALTER TABLE teachers
ADD CONSTRAINT fk_user_teacher
FOREIGN KEY (user_id) REFERENCES users(U_id)
ON DELETE SET NULL
ON UPDATE CASCADE;


ALTER TABLE courses
ADD COLUMN teacher_id INT,
ADD CONSTRAINT fk_teacher
    FOREIGN KEY (teacher_id) REFERENCES teachers(T_id)
    ON DELETE SET NULL
    ON UPDATE CASCADE;

CREATE TABLE attendance (
    A_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    course_id INT NOT NULL,
    teacher_id INT NOT NULL,
    date DATE NOT NULL,
    status ENUM('Present', 'Absent') NOT NULL,

    UNIQUE (student_id, course_id, date),

    FOREIGN KEY (student_id) REFERENCES students(S_id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(C_id) ON DELETE CASCADE,
    FOREIGN KEY (teacher_id) REFERENCES teachers(T_id) ON DELETE CASCADE
);


-- for admin as it can only be entered using database

INSERT INTO users (U_id, name, email, password, role)
VALUES (1, 'Majid', 'majidkhan@gmail.com', '$2y$10$/rJVG2QFV3BUPb05m7aRa.WXM5RR1yVtCqbPDG/MAd9YSsKsBiir2', 'admin');





-- USE student_portal;


-- SELECT * FROM users WHERE email = :email
-- DELIMITER //

-- CREATE PROCEDURE insert_user(
--     IN p_name VARCHAR(100),
--     IN p_email VARCHAR(100),
--     IN p_password VARCHAR(255),
--     IN p_role ENUM('student', 'admin')
-- )
-- BEGIN
--     INSERT INTO users(name, email, password, role)
--     VALUES (p_name, p_email, p_password, p_role);
-- END //

-- DELIMITER ;


-- DELIMITER //

-- CREATE PROCEDURE add_course(
--     IN p_code VARCHAR(50),
--     IN p_C_id INT,
--     IN p_department VARCHAR(100),
--     IN p_name VARCHAR(100),
--     IN p_teacher_id INT
-- )
-- BEGIN
--     INSERT INTO courses (code, C_id, department, name, teacher_id)
--     VALUES (p_code, p_C_id, p_department, p_name, p_teacher_id);
-- END //

-- DELIMITER ;



-- DELIMITER //

-- CREATE PROCEDURE show_all_courses()
-- BEGIN
--     SELECT * FROM courses;
-- END //

-- DELIMITER ;

-- DELIMITER //

-- CREATE PROCEDURE delete_course (
--     IN p_C_id INT
-- )
-- BEGIN
--     DELETE FROM courses
--     WHERE C_id = p_C_id;
-- END //

-- DELIMITER ;

-- DELIMITER //

-- CREATE PROCEDURE enroll_student (
--     IN p_student_id INT,
--     IN p_course_id INT
-- )
-- BEGIN
--     INSERT INTO enrollments (student_id, course_id)
--     VALUES (p_student_id, p_course_id);
-- END //

-- DELIMITER ;

-- DELIMITER //

-- CREATE PROCEDURE delete_enrollment_by_id (
--     IN p_en_id INT
-- )
-- BEGIN
--     DELETE FROM enrollments
--     WHERE EN_id = p_en_id;
-- END //

-- DELIMITER ;



-- DELIMITER //
-- CREATE PROCEDURE getStudentByStudentId(IN p_student_id INT)
-- BEGIN
--     SELECT 
--         s.*, 
--         u.name AS Student_name
--     FROM 
--         students s
--     INNER JOIN 
--         users u ON s.user_id = u.U_id
--     WHERE 
--         s.S_id = p_student_id;
-- END; //
-- DELIMITER ;


-- DELIMITER //

-- CREATE PROCEDURE GetUserByEmail(IN user_email VARCHAR(100))
-- BEGIN
--     SELECT * FROM users WHERE email = user_email;
-- END //
-- DELIMITER ;
