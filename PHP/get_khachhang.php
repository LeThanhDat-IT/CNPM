<?php
// PHP/get_khachhang.php
require_once 'connect.php';
header('Content-Type: application/json; charset=utf-8');

$sql = "SELECT maKH, ten, gioiTinh, sdt, ngaySinh, email, diaChi FROM QuanLyKhachhang ORDER BY maKH ASC";
$result = $conn->query($sql);

$khachhang = array();
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $khachhang[] = $row;
    }
}

echo json_encode($khachhang, JSON_UNESCAPED_UNICODE);
$conn->close();
