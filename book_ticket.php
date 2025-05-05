<?php
session_start();
header('Content-Type: application/json');
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['event_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
    exit;
}

$event_id = intval($_POST['event_id']);

// Start transaction to avoid race conditions
try {
    $pdo->beginTransaction();

    // Lock the row for update to prevent overselling
    $stmt = $pdo->prepare("SELECT available_seats FROM events WHERE id = ? FOR UPDATE");
    $stmt->execute([$event_id]);
    $event = $stmt->fetch();

    if (!$event) {
        $pdo->rollBack();
        echo json_encode(['status' => 'error', 'message' => 'Event not found']);
        exit;
    }

    if ($event['available_seats'] < 1) {
        $pdo->rollBack();
        echo json_encode(['status' => 'error', 'message' => 'No available seats']);
        exit;
    }

    // Reduce seat count
    $stmt = $pdo->prepare("UPDATE events SET available_seats = available_seats - 1 WHERE id = ?");
    $stmt->execute([$event_id]);

    // Insert booking
    $stmt = $pdo->prepare("INSERT INTO bookings (user_id, event_id) VALUES (?, ?)");
    $stmt->execute([$user_id, $event_id]);

    $pdo->commit();

    echo json_encode(['status' => 'success', 'message' => 'Ticket booked successfully']);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['status' => 'error', 'message' => 'Booking failed: ' . $e->getMessage()]);
}
?>
