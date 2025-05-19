<?php
header('Content-Type: application/json');
require_once 'connect.php';

// Lấy bookingCode từ POST (form hoặc JSON)
$bookingCode = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Nếu là JSON
    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
    if (stripos($contentType, 'application/json') !== false) {
        $input = json_decode(file_get_contents('php://input'), true);
        $bookingCode = isset($input['bookingCode']) ? strtoupper(trim($input['bookingCode'])) : '';
    } else {
        // Form thường
        $bookingCode = isset($_POST['bookingCode']) ? strtoupper(trim($_POST['bookingCode'])) : '';
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

if (!$bookingCode) {
    echo json_encode(['success' => false, 'message' => 'Thiếu mã đặt phòng']);
    exit;
}

// Truy vấn database
$stmt = $conn->prepare('SELECT * FROM bookings WHERE UPPER(bookingCode) = ? LIMIT 1');
$stmt->bind_param('s', $bookingCode);
$stmt->execute();
$result = $stmt->get_result();
$booking = $result->fetch_assoc();

if (!$booking) {
    echo json_encode(['success' => false, 'message' => 'Không tìm thấy mã đặt phòng này!']);
    exit;
}

// Đảm bảo trường đúng tên cho JS
if (isset($booking['TrangThaiThanhToan'])) {
    $booking['TrangThaiThanhToan'] = (int)$booking['TrangThaiThanhToan'];
} elseif (isset($booking['trangthaithanhtoan'])) {
    $booking['TrangThaiThanhToan'] = (int)$booking['trangthaithanhtoan'];
}

echo json_encode(['success' => true, 'booking' => $booking]);
$stmt->close();
$conn->close();
exit;