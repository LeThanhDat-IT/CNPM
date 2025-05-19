<?php
header('Content-Type: application/json');
require_once 'connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$bookingCode = isset($input['bookingCode']) ? strtoupper(trim($input['bookingCode'])) : '';

if (!$bookingCode) {
    echo json_encode(['success' => false, 'message' => 'Thiếu mã đặt phòng']);
    exit;
}

// Truy vấn database
$stmt = $conn->prepare('SELECT * FROM bookings WHERE UPPER(bookingCode) = ? LIMIT 1');
$stmt->bind_param('s', $bookingCode);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    echo json_encode(['success' => true, 'booking' => $row]);
} else {
    echo json_encode(['success' => false, 'message' => 'Không tìm thấy mã đặt phòng này!']);
}
$stmt->close();
$conn->close();
?>
