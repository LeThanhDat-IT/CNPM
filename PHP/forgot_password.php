<?php
header('Content-Type: application/json');
require_once 'connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Yêu cầu không hợp lệ']);
    exit;
}

$username = trim($_POST['username'] ?? '');

if (!$username) {
    echo json_encode(['success' => false, 'message' => 'Vui lòng nhập tên đăng nhập hoặc email.']);
    exit;
}

// Tìm người dùng theo username hoặc email
$stmt = $conn->prepare("SELECT id, email FROM users WHERE username = ? OR email = ?");
$stmt->bind_param("ss", $username, $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    echo json_encode(['success' => true, 'message' => 'Nếu tài khoản tồn tại, email hướng dẫn đã được gửi.']);
    exit;
}

$stmt->bind_result($user_id, $email);
$stmt->fetch();

$token = bin2hex(random_bytes(32));
$expires = date("Y-m-d H:i:s", time() + 3600);

// Cập nhật token vào user
$stmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_expires = ? WHERE id = ?");
$stmt->bind_param("ssi", $token, $expires, $user_id);
$stmt->execute();

// Link đặt lại mật khẩu
$link = "http://localhost/CNPM_Project/reset_password.html?token=$token";
$message = "Nhấn vào liên kết sau để đặt lại mật khẩu:\n$link";

// Gửi email: Tạm thời chỉ echo để kiểm tra
echo json_encode(['success' => true, 'message' => 'Nếu tài khoản tồn tại, liên kết đặt lại mật khẩu đã được gửi.', 'debug_link' => $link]);
?>
