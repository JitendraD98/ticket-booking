<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Pagination setup
$limit = 5;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $limit;

// Get total booking count
$totalStmt = $pdo->prepare("SELECT COUNT(*) FROM bookings WHERE user_id = ?");
$totalStmt->execute([$user_id]);
$totalBookings = $totalStmt->fetchColumn();
$totalPages = ceil($totalBookings / $limit);

// Fetch user bookings with event info
$stmt = $pdo->prepare("
    SELECT b.booking_time, e.name, e.event_date, e.venue
    FROM bookings b
    JOIN events e ON b.event_id = e.id
    WHERE b.user_id = ?
    ORDER BY b.booking_time DESC
    LIMIT ? OFFSET ?
");
$stmt->bindParam(1, $user_id, PDO::PARAM_INT);
$stmt->bindParam(2, $limit, PDO::PARAM_INT);
$stmt->bindParam(3, $offset, PDO::PARAM_INT);
$stmt->execute();
$bookings = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Booking History - Ticket Booking System</title>
    <link rel="stylesheet" href="style.css" />
</head>
<body>
    <nav class="navbar">
        <div>Hello, <?php echo htmlspecialchars($_SESSION['username']); ?>!</div>
        <div>
            <a href="events.php">Events</a> |
            <a href="logout.php">Logout</a>
        </div>
    </nav>
    <div class="container">
        <h1>Your Booking History</h1>

        <?php if (!$bookings): ?>
            <p>You have no bookings yet.</p>
        <?php else: ?>
            <table class="booking-table">
                <thead>
                    <tr>
                        <th>Event Name</th>
                        <th>Date</th>
                        <th>Venue</th>
                        <th>Booking Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bookings as $booking): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($booking['name']); ?></td>
                            <td><?php echo htmlspecialchars(date('F j, Y, g:i A', strtotime($booking['event_date']))); ?></td>
                            <td><?php echo htmlspecialchars($booking['venue']); ?></td>
                            <td><?php echo htmlspecialchars(date('F j, Y, g:i A', strtotime($booking['booking_time']))); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <?php if ($totalPages > 1): ?>
                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a href="?page=<?php echo $page - 1; ?>">&laquo; Prev</a>
                    <?php endif; ?>
                    Page <?php echo $page; ?> of <?php echo $totalPages; ?>
                    <?php if ($page < $totalPages): ?>
                        <a href="?page=<?php echo $page + 1; ?>">Next &raquo;</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</body>
</html>
