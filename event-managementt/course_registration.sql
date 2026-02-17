CREATE DATABASE course_registration;
USE course_registration;

-- Users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Courses table
CREATE TABLE courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    duration VARCHAR(50),
    level ENUM('Beginner', 'Intermediate', 'Advanced'),
    price DECIMAL(10,2),
    icon VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Enrollments table
CREATE TABLE enrollments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    course_id INT,
    enrolled_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('active', 'completed', 'cancelled') DEFAULT 'active',
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (course_id) REFERENCES courses(id)
);

-- Insert sample courses
INSERT INTO courses (title, description, duration, level, price, icon) VALUES
('Web Development Fundamentals', 'Learn HTML, CSS, and JavaScript to build modern websites', '8', 'Beginner', 99.99, 'fas fa-code'),
('PHP & MySQL Mastery', 'Complete guide to server-side programming with PHP and database management', '10', 'Intermediate', 149.99, 'fas fa-database'),
('Advanced JavaScript', 'Master modern JavaScript concepts including ES6+, Async/Await, and Frameworks', '12', 'Advanced', 199.99, 'fab fa-js-square'),
('CSS & Animations', 'Create stunning animations and responsive designs with advanced CSS', '6', 'Intermediate', 129.99, 'fas fa-palette'),
('SQL Database Design', 'Learn database design, optimization, and advanced query techniques', '8', 'Intermediate', 139.99, 'fas fa-server'),
('Full Stack Development', 'Complete course covering frontend and backend development', '16', 'Advanced', 299.99, 'fas fa-layer-group');

-- Insert sample user (password: user123)
INSERT INTO users (name, email, password) VALUES 
('John Doe', 'user@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');