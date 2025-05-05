<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$stmt = $pdo->query("SELECT id, name, event_date, venue, available_seats FROM events ORDER BY event_date ASC");
$events = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Events - Ticket Booking System</title>
    <link rel="stylesheet" href="style.css" />
    <script src="script.js"></script>
</head>
<body>
    <nav class="navbar">
        <div>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</div>
        <div>
            <a href="booking_history.php">My Bookings</a> |
            <a href="logout.php">Logout</a>
        </div>
    </nav>
    <div class="container">
        <h1>Available Events</h1>
        <div id="events">
            <?php foreach ($events as $event): ?>
                <div class="event-card">
                    <h2><?php echo htmlspecialchars($event['name']); ?></h2>
                    <p><strong>Date:</strong> <?php echo htmlspecialchars(date('F j, Y, g:i A', strtotime($event['event_date']))); ?></p>
                    <p><strong>Venue:</strong> <?php echo htmlspecialchars($event['venue']); ?></p>
                    <p><strong>Available Seats:</strong> <span id="seats-<?php echo $event['id']; ?>"><?php echo $event['available_seats']; ?></span></p>
                    <button <?php echo ($event['available_seats'] <= 0) ? 'disabled' : ''; ?> onclick="bookTicket(<?php echo $event['id']; ?>)">Book Ticket</button>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
