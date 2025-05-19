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

    // Lưu thông tin đặt phòng vào cơ sở dữ liệu
    $stmt = $conn->prepare("INSERT INTO DatPhong (room, fullname, phone, email, date, time, note) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $room, $fullname, $phone, $email, $date, $time, $note);

    if ($stmt->execute()) {
        echo json_encode(['success' => 'Đặt phòng thành công.']);
    } else {
        echo json_encode(['error' => 'Đã xảy ra lỗi. Vui lòng thử lại sau.']);
    }

    $stmt->close();
}

// Lấy danh sách khách hàng
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'list') {
    $sql = "SELECT maKH, ten, gioiTinh, sdt, ngaySinh, email, diaChi FROM QuanLyKhachhang ORDER BY maKH ASC";
    $result = $conn->query($sql);
    $khachhang = array();
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $khachhang[] = $row;
        }
    }
    echo json_encode($khachhang, JSON_UNESCAPED_UNICODE);
    exit;
}

$conn->close();
