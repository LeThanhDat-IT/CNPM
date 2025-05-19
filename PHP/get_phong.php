<?php
require_once 'connect.php';
header('Content-Type: application/json; charset=utf-8');

$sql = "SELECT maPhong, tenPhong, kieuPhong, giaPhong, tinhTrang FROM QuanLyPhong ORDER BY maPhong ASC";
$result = $conn->query($sql);

$phong = array();
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $phong[] = $row;
    }
}
echo json_encode($phong, JSON_UNESCAPED_UNICODE);
$conn->close();
