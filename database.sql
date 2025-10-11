-- Create the database
CREATE DATABASE IF NOT EXISTS watchdog_db;
USE watchdog_db;

-- Create users table
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create pets table
CREATE TABLE IF NOT EXISTS pets (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    species VARCHAR(50) NOT NULL,
    breed VARCHAR(100),
    age INT,
    gender ENUM('Male', 'Female'),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Create vaccinations table
CREATE TABLE IF NOT EXISTS vaccinations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    pet_id INT NOT NULL,
    vaccine_type VARCHAR(100) NOT NULL,
    vaccination_date DATE NOT NULL,
    next_due_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (pet_id) REFERENCES pets(id)
);

-- Create incidents table
CREATE TABLE IF NOT EXISTS incidents (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    pet_id INT,
    incident_type VARCHAR(50) NOT NULL,
    description TEXT,
    status ENUM('Open', 'In Progress', 'Resolved', 'Closed') DEFAULT 'Open',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (pet_id) REFERENCES pets(id)
);