CREATE DATABASE cc_platform;
USE cc_platform;
CREATE TABLE problems (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    difficulty ENUM('easy', 'medium', 'hard') NOT NULL,
    description TEXT NOT NULL
);

CREATE TABLE test_cases (
    id INT PRIMARY KEY AUTO_INCREMENT,
    problem_id INT,
    input_data TEXT NOT NULL,
    output_data TEXT NOT NULL,
    FOREIGN KEY (problem_id) REFERENCES problems(id)
);

CREATE TABLE templates (
    id INT PRIMARY KEY AUTO_INCREMENT,
    problem_id INT,
    language VARCHAR(50) NOT NULL,
    code TEXT NOT NULL,
    FOREIGN KEY (problem_id) REFERENCES problems(id)
);

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE,
    password VARCHAR(50),
    points INT DEFAULT 0,
    problems_solved INT DEFAULT 0

);

CREATE TABLE submissions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    problem_id INT,
    status VARCHAR(20) NOT NULL,
    code TEXT NOT NULL,
    language VARCHAR(50) NOT NULL,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ,
    FOREIGN KEY (problem_id) REFERENCES problems(id) 
);