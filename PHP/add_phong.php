<?php
require_once 'connect.php';
header('Content-Type: application/json; charset=utf-8');

$data = json_decode(file_get_contents('php://input'), true);
$name = $data['name'] ?? '';
$type = $data['type'] ?? '';
$price = $data['price'] ?? '';
$status = $data['status'] ?? '';

if (!$name || !$type || !$price || $status === '') {
    echo json_encode(['success' => false, 'message' => 'Thiếu thông tin']);
    exit;
}

// Lấy mã phòng lớn nhất hiện tại
$sql = "SELECT maPhong FROM QuanLyPhong ORDER BY maPhong DESC LIMIT 1";
$result = $conn->query($sql);
if ($result && $row = $result->fetch_assoc()) {
    $lastCode = $row['maPhong'];
    // Tách số từ mã phòng, ví dụ R007 -> 7
    $num = intval(preg_replace('/\D/', '', $lastCode));
    $newNum = $num + 1;
    $newCode = 'R' . str_pad($newNum, 3, '0', STR_PAD_LEFT);
} else {
    $newCode = 'R001';
}

// Thêm phòng với mã phòng mới
$stmt = $conn->prepare("INSERT INTO QuanLyPhong (maPhong, tenPhong, kieuPhong, giaPhong, tinhTrang) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssdi", $newCode, $name, $type, $price, $status);
$ok = $stmt->execute();
$stmt->close();
$conn->close();

echo json_encode(['success' => $ok, 'maPhong' => $newCode]);