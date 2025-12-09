-- Create Database (Run this if you haven't created the DB yet)
CREATE DATABASE IF NOT EXISTS micro_habit_builder;
USE micro_habit_builder;

-- Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Habit Templates (Pre-defined specific habits)
CREATE TABLE IF NOT EXISTS habit_templates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category VARCHAR(50) NOT NULL,
    goal VARCHAR(100) NOT NULL,
    micro_action VARCHAR(255) NOT NULL
);

-- Habits Table (User's active habits)
CREATE TABLE IF NOT EXISTS habits (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    goal VARCHAR(100) NOT NULL,
    micro_action VARCHAR(255) NOT NULL,
    current_streak INT DEFAULT 0,
    longest_streak INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Daily Completions (Tracking logs)
CREATE TABLE IF NOT EXISTS daily_completions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    habit_id INT NOT NULL,
    user_id INT NOT NULL,
    completed_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (habit_id) REFERENCES habits(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_habit_date (user_id, habit_id, completed_date)
);

-- Seed Templates
INSERT INTO habit_templates (category, goal, micro_action) VALUES 
('Fitness', 'Run a Marathon', 'Put on running shoes and walk out the door'),
('Fitness', 'Get 6-pack abs', 'Do 1 minute of planks'),
('Studying', 'Master Python', 'Write code for 5 minutes'),
('Studying', 'Read 20 books a year', 'Read 1 page'),
('Language Learning', 'Fluent in Spanish', 'Learn 3 new words'),
('Language Learning', 'Speak French', 'Listen to a French podcast for 5 mins');
