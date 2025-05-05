-- Database: ticket_booking

CREATE DATABASE IF NOT EXISTS ticket_booking;
USE ticket_booking;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- Events table with 4 sample events
CREATE TABLE IF NOT EXISTS events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    event_date DATETIME NOT NULL,
    venue VARCHAR(100) NOT NULL,
    available_seats INT NOT NULL
);

INSERT INTO events (name, event_date, venue, available_seats) VALUES
('Concert A', '2024-07-15 19:00:00', 'Auditorium X', 50),
('Theatre B', '2024-07-20 20:00:00', 'Theatre Hall Y', 30),
('Seminar C',  '2024-08-01 10:00:00', 'Conference Room Z', 20),
('Festival D', '2024-08-10 18:00:00', 'Open Ground W', 100);

-- Bookings table
CREATE TABLE IF NOT EXISTS bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    event_id INT NOT NULL,
    booking_time DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
);
