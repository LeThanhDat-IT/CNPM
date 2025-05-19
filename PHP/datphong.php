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

    // Bước 1: Chèn bản ghi chưa có bookingCode
    $stmt = $conn->prepare("INSERT INTO bookings (room, fullname, phone, email, checkin, checkout, time) VALUES (?, ?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("ssssss", $room, $fullname, $phone, $email, $date, $time);

    if ($stmt->execute()) {
        $last_id = $conn->insert_id;
        // Bước 2: Sinh bookingCode duy nhất
        $bookingCode = 'TCH' . date('Ymd') . str_pad($last_id, 3, '0', STR_PAD_LEFT);
        // Bước 3: Update bookingCode cho bản ghi vừa chèn
        $stmt2 = $conn->prepare("UPDATE bookings SET bookingCode = ? WHERE id = ?");
        $stmt2->bind_param("si", $bookingCode, $last_id);
        $stmt2->execute();
        $stmt2->close();

        echo json_encode(['success' => true, 'message' => 'Đặt phòng thành công!', 'bookingCode' => $bookingCode]);
    } else {
        echo json_encode(['error' => 'Đã xảy ra lỗi. Vui lòng thử lại sau.']);
    }

    $stmt->close();
}

$conn->close();
