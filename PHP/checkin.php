<?php
// filepath: c:\wamp64\www\CNPM_Project\PHP\checkin_booking.php
header('Content-Type: application/json');
require_once 'connect.php';

$code = trim($_POST['bookingCode'] ?? '');

if ($code === '') {
    echo json_encode(['success' => false, 'message' => 'Vui lòng nhập mã đặt phòng.']);
    exit;
}

$stmt = $conn->prepare("SELECT * FROM bookings WHERE bookingCode = ?");
$stmt->bind_param('s', $code);
$stmt->execute();
$result = $stmt->get_result();
$booking = $result->fetch_assoc();

if (!$booking) {
    echo json_encode(['success' => false, 'message' => 'Không tìm thấy mã đặt phòng này!']);
    exit;
}

echo json_encode(['success' => true, 'booking' => $booking]);