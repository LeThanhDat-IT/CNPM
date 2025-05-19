<?php
header('Content-Type: application/json');
require_once 'connect.php';

$bookingCode = trim($_POST['bookingCode'] ?? '');

if ($bookingCode === '') {
    echo json_encode(['success' => false, 'message' => 'Thiếu mã đặt phòng!']);
    exit;
}

$stmt = $conn->prepare("UPDATE bookings SET TrangThaiThanhToan = 1 WHERE bookingCode = ?");
$stmt->bind_param('s', $bookingCode);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Không tìm thấy mã đặt phòng hoặc đã thanh toán!']);
}
$stmt->close();
$conn->close();