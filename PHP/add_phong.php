<?php
require_once 'connect.php';
header('Content-Type: application/json; charset=utf-8');

$data = json_decode(file_get_contents('php://input'), true);
$name = $data['name'];
$type = $data['type'];
$price = $data['price'];
$status = $data['status'];
$hinhAnh = isset($data['hinhAnh']) ? $data['hinhAnh'] : null;
$bedType = isset($data['bedType']) ? $data['bedType'] : null;
$amenities = isset($data['amenities']) ? $data['amenities'] : null;
$area = isset($data['area']) ? $data['area'] : null;

// Lấy mã phòng lớn nhất hiện tại
$sql = "SELECT maPhong FROM quanlyphong ORDER BY maPhong DESC LIMIT 1";
$result = $conn->query($sql);
if ($result && $row = $result->fetch_assoc()) {
    $lastCode = $row['maPhong'];
    $num = intval(preg_replace('/\D/', '', $lastCode));
    $newNum = $num + 1;
    $newCode = 'R' . str_pad($newNum, 3, '0', STR_PAD_LEFT);
} else {
    $newCode = 'R001';
}

// Thêm phòng mới với đầy đủ thông tin
$stmt = $conn->prepare("INSERT INTO quanlyphong (maPhong, tenPhong, kieuPhong, giaPhong, tinhTrang, hinhAnh, loaiGiuong, tienNghi, dienTich) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssdissss", $newCode, $name, $type, $price, $status, $hinhAnh, $bedType, $amenities, $area);
$ok = $stmt->execute();
$stmt->close();
$conn->close();

echo json_encode(['success' => $ok, 'maPhong' => $newCode]);
?>