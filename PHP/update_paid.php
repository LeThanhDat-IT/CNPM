<?php
header('Content-Type: application/json');
require_once 'connect.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require __DIR__ . '/../PHPMailer/src/Exception.php';
require __DIR__ . '/../PHPMailer/src/PHPMailer.php';
require __DIR__ . '/../PHPMailer/src/SMTP.php';

$bookingCode = trim($_POST['bookingCode'] ?? '');

if ($bookingCode === '') {
    echo json_encode(['success' => false, 'message' => 'Thiếu mã đặt phòng!']);
    exit;
}

$stmt = $conn->prepare("UPDATE bookings SET TrangThaiThanhToan = 1 WHERE bookingCode = ?");
$stmt->bind_param('s', $bookingCode);
$stmt->execute();

$payMethod = $_POST['payMethod'] ?? '';

if ($stmt->affected_rows > 0) {
    // Lấy thông tin booking để gửi mail
    $stmt2 = $conn->prepare("SELECT room, fullname, phone, email, checkin, checkout, total FROM bookings WHERE bookingCode = ?");
    $stmt2->bind_param('s', $bookingCode);
    $stmt2->execute();
    $stmt2->bind_result($room, $fullname, $phone, $email, $checkin, $checkout, $total);
    if ($stmt2->fetch()) {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'thanhdatle.it@gmail.com';
            $mail->Password = 'jlup cwie mmje ujde';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('thanhdatle.it@gmail.com', 'THE COW HOTEL');
            $mail->addAddress($email, $fullname);

            $mail->isHTML(true);
            if ($payMethod === 'pay_at_hotel') {
                $mail->Subject = 'Xác nhận đặt phòng tại THE COW HOTEL';
                $mail->Body = "
                    <div style='font-family:Arial,sans-serif;max-width:500px;margin:auto;border:1px solid #eee;padding:24px;background:#fafbfc'>
                        <h2 style='color:#1a8f3c;text-align:center;margin-bottom:16px'>
                            <span style='color:#d10000;'>THE COW HOTEL</span> - Xác nhận đặt phòng
                        </h2>
                        <p>Kính chào <b>$fullname</b>,<br>
                        Cảm ơn bạn đã đặt phòng tại <b>THE COW HOTEL</b>.</p>
                        <table style='width:100%;border-collapse:collapse;margin:16px 0'>
                            <tr><td style='padding:6px 0'>Phòng:</td><td><b>$room</b></td></tr>
                            <tr><td style='padding:6px 0'>Mã đặt phòng:</td><td><span style='color:#d10000;font-weight:bold;font-size:1.1em'>$bookingCode</span></td></tr>
                            <tr><td style='padding:6px 0'>Nhận phòng:</td><td>$checkin</td></tr>
                            <tr><td style='padding:6px 0'>Trả phòng:</td><td>$checkout</td></tr>
                            <tr><td style='padding:6px 0'>Tổng cộng:</td><td><b style='color:#1a8f3c'>" . number_format($total, 0, ',', '.') . " VND</b></td></tr>
                            <tr><td style='padding:6px 0'>Hình thức thanh toán:</td><td><b>Thanh toán tại khách sạn</b></td></tr>
                        </table>
                        <div style='margin:12px 0 18px 0;color:#d10000'>
                            <b>Vui lòng lưu lại mã đặt phòng để xuất trình khi đến khách sạn check-in.</b>
                        </div>
                        <div style='font-size:12px;color:#888;text-align:center'>
                            Đây là email tự động, vui lòng không trả lời.
                        </div>
                    </div>
                ";
            } else {
                $mail->Subject = 'Xác nhận thanh toán tại THE COW HOTEL';
                $mail->Body = "
                    <div style='font-family:Arial,sans-serif;max-width:500px;margin:auto;border:1px solid #eee;padding:24px;background:#fafbfc'>
                        <h2 style='color:#1a8f3c;text-align:center;margin-bottom:16px'>
                            <span style='color:#d10000;'>THE COW HOTEL</span> - Thanh toán thành công
                        </h2>
                        <p>Kính chào <b>$fullname</b>,<br>
                        Bạn đã <b>thanh toán thành công</b> tại <b>THE COW HOTEL</b>.</p>
                        <table style='width:100%;border-collapse:collapse;margin:16px 0'>
                            <tr><td style='padding:6px 0'>Phòng:</td><td><b>$room</b></td></tr>
                            <tr><td style='padding:6px 0'>Mã đặt phòng:</td><td><span style='color:#d10000;font-weight:bold;font-size:1.1em'>$bookingCode</span></td></tr>
                            <tr><td style='padding:6px 0'>Nhận phòng:</td><td>$checkin</td></tr>
                            <tr><td style='padding:6px 0'>Trả phòng:</td><td>$checkout</td></tr>
                            <tr><td style='padding:6px 0'>Tổng cộng:</td><td><b style='color:#1a8f3c'>" . number_format($total, 0, ',', '.') . " VND</b></td></tr>
                            <tr><td style='padding:6px 0'>Hình thức thanh toán:</td><td><b>Chuyển khoản/Thẻ</b></td></tr>
                        </table>
                        <div style='margin:12px 0 18px 0;color:#d10000'>
                            <b>Vui lòng lưu lại mã đặt phòng để xuất trình khi đến khách sạn check-in.</b>
                        </div>
                        <div style='font-size:12px;color:#888;text-align:center'>
                            Đây là email tự động, vui lòng không trả lời.
                        </div>
                    </div>
                ";
            }
            $mail->send();
        } catch (Exception $e) {
            // Ghi log nếu cần
        }
    }
    $stmt2->close();
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Không tìm thấy mã đặt phòng hoặc đã thanh toán!']);
}
$stmt->close();
$conn->close();