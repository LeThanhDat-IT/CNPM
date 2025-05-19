<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
require_once 'connect.php';

$token = $_POST['token'] ?? '';
$password = $_POST['password'] ?? '';
$confirm = $_POST['confirm_password'] ?? '';

if (!$token || !$password || !$confirm) {
    echo json_encode(['success' => false, 'message' => 'Thiếu thông tin.']);
    exit;
}

if ($password !== $confirm) {
    echo json_encode(['success' => false, 'message' => 'Mật khẩu xác nhận không khớp.']);
    exit;
}

// Kiểm tra token còn hạn không
$stmt = $conn->prepare("SELECT id FROM users WHERE reset_token = ? AND reset_expires > NOW()");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Liên kết không hợp lệ hoặc đã hết hạn.']);
    exit;
}

$user = $result->fetch_assoc();
$userId = $user['id'];
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Cập nhật mật khẩu mới và xoá token
$stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expires = NULL WHERE id = ?");
$stmt->bind_param("si", $hashedPassword, $userId);
if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Mật khẩu đã được cập nhật thành công.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra khi đặt mật khẩu mới.']);
}
?>
