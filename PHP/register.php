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
            // Kiểm tra trùng email, số điện thoại, tên đăng nhập
            $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? OR phone = ? OR username = ?");
            $stmt->bind_param('sss', $email, $phone, $username);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                if ($row['email'] === $email) {
                    echo "Email đã được sử dụng!";
                } elseif ($row['phone'] === $phone) {
                    echo "Số điện thoại đã được sử dụng!";
                } elseif ($row['username'] === $username) {
                    echo "Tên đăng nhập đã được sử dụng!";
                } else {
                    echo "Thông tin đã được sử dụng!";
                }
                $stmt->close();
            } else {
                $stmt->close();
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt2 = $conn->prepare("INSERT INTO users (name, phone, email, gender, dob, address, username, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt2->bind_param('ssssssss', $name, $phone, $email, $gender, $dob, $address, $username, $password_hash);
                if ($stmt2->execute()) {
                    header("Location: ../dangnhap.html");
                    exit();
                } else {
                    echo "Lỗi: " . $stmt2->error;
                }
                $stmt2->close();
            }
        }
    } else {
        echo "Vui lòng nhập đầy đủ thông tin!";
    }
}
$conn->close();
?>
