<?php
require_once 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $gender = isset($_POST['gender']) ? trim($_POST['gender']) : '';
    $dob = isset($_POST['dob']) ? trim($_POST['dob']) : '';
    $address = isset($_POST['address']) ? trim($_POST['address']) : '';
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    $confirm_password = isset($_POST['confirm-password']) ? trim($_POST['confirm-password']) : '';
    $agree = isset($_POST['agree']);

    if ($name && $phone && $email && $gender && $dob && $address && $username && $password && $confirm_password && $agree) {
        if ($password !== $confirm_password) {
            echo "Mật khẩu và xác nhận mật khẩu không khớp!";
        } else {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (name, phone, email, gender, dob, address, username, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param('ssssssss', $name, $phone, $email, $gender, $dob, $address, $username, $password_hash);
            if ($stmt->execute()) {
                // Đăng ký thành công, chuyển hướng về trang đăng nhập
                header("Location: ../dangnhap.html");
                exit();
            } else {
                echo "Lỗi: " . $stmt->error;
            }
            $stmt->close();
        }
    } else {
        echo "Vui lòng nhập đầy đủ thông tin!";
    }
}
$conn->close();
?>
