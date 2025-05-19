<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once 'connect.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $room = trim($_POST['room'] ?? '');
    $fullname = trim($_POST['fullname'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $date = trim($_POST['date'] ?? '');
    $time = trim($_POST['time'] ?? '');
    $note = trim($_POST['note'] ?? '');

    // Kiểm tra dữ liệu đầu vào
    if (empty($room) || empty($fullname) || empty($phone) || empty($email) || empty($date) || empty($time)) {
        echo json_encode(['error' => 'Vui lòng điền đầy đủ thông tin.']);
        exit;
    }

    // Sinh mã đặt phòng tự động (ví dụ: BK20240520001)
    $prefix = 'BK' . date('Ymd');
    $sql_max = "SELECT MAX(bookingCode) AS max_code FROM bookings WHERE bookingCode LIKE '{$prefix}%'";
    $result = $conn->query($sql_max);
    $row = $result->fetch_assoc();
    if ($row && $row['max_code']) {
        $num = intval(substr($row['max_code'], -3)) + 1;
        $bookingCode = $prefix . str_pad($num, 3, '0', STR_PAD_LEFT);
    } else {
        $bookingCode = $prefix . '001';
    }

    // Lưu thông tin đặt phòng vào bảng bookings (đồng bộ với các file get)
    $stmt = $conn->prepare("INSERT INTO bookings (room, fullname, phone, email, checkin, checkout, time, bookingCode) VALUES (?, ?, ?, ?, ?, ?, NOW(), ?)");
    $stmt->bind_param("sssssss", $room, $fullname, $phone, $email, $date, $time, $bookingCode);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Đặt phòng thành công!', 'bookingCode' => $bookingCode]);
    }

    $stmt->close();
}

$conn->close();
