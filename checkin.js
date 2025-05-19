// Lấy danh sách đặt phòng từ localStorage
const bookings = JSON.parse(localStorage.getItem('bookings')) || [];

// Giá phòng (đồng bộ với các file khác)
const roomPrices = {
  'Phòng Gia Đình': 360000,
  'Phòng Đôi Cao Cấp': 1200000,
  'Phòng Mini': 200000,
  'Phòng Tình Nhân': 560000
};

// Map loại phòng sang ảnh
const roomImages = {
  'Phòng Gia Đình': 'images/family-suite-la-gi.webp',
  'Phòng Đôi Cao Cấp': 'images/phong_caocap.webp',
  'Phòng Mini': 'images/phong_don.webp',
  'Phòng Tình Nhân': 'images/a3.jpg'
};

function getTotalAmount(booking) {
  if (!booking) return 0;
  const price = roomPrices[booking.room] || 0;
  const checkin = new Date(booking.checkin);
  const checkout = new Date(booking.checkout);
  let nights = Math.ceil((checkout - checkin) / (1000 * 60 * 60 * 24));
  if (nights < 1) nights = 1;
  return price * nights;
}

document.getElementById('checkinForm').addEventListener('submit', async function(e) {
  e.preventDefault();
  const code = document.getElementById('bookingCode').value.trim().toUpperCase();
  const messageDiv = document.getElementById('message');
  const infoDiv = document.getElementById('bookingInfo');
  messageDiv.textContent = '';
  infoDiv.innerHTML = '';

  // Hiển thị spinner loading
  let spinner = document.getElementById('loadingSpinner');
  if (!spinner) {
    spinner = document.createElement('div');
    spinner.id = 'loadingSpinner';
    spinner.className = 'loading-spinner';
    infoDiv.parentNode.insertBefore(spinner, infoDiv);
  }
  spinner.style.display = 'block';

  // Gửi request đến backend PHP
  try {
    const res = await fetch('PHP/checkin_api.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ bookingCode: code })
    });
    const data = await res.json();
    spinner.style.display = 'none';
    if (!data.success) {
      messageDiv.textContent = data.message || 'Không tìm thấy mã đặt phòng này!';
      messageDiv.className = 'error';
      return;
    }
    const booking = data.booking;
    // Lấy ảnh phòng
    const imgSrc = roomImages[booking.room] || '';
    let html = '';
    if (imgSrc) {
      html += `<div style="text-align:center;margin-bottom:16px;"><img src="${imgSrc}" alt="${booking.room}" class="room-image"></div>`;
    }
    html += `
      <div class="info"><label>Phòng:</label> ${booking.room}</div>
      <div class="info"><label>Khách:</label> ${booking.fullname}</div>
      <div class="info"><label>Điện thoại:</label> ${booking.phone}</div>
      <div class="info"><label>Email:</label> ${booking.email}</div>
      <div class="info"><label>Nhận phòng:</label> ${booking.checkin}</div>
      <div class="info"><label>Trả phòng:</label> ${booking.checkout}</div>
      <div class="info"><label>Mã đặt phòng:</label> <span style="font-weight:bold;">${booking.bookingCode}</span></div>
    `;
    if (booking.paymentStatus === 'paid') {
      html += `<div class="info"><label>Trạng thái thanh toán:</label> <span class="paid">Đã thanh toán</span></div>`;
      messageDiv.textContent = 'Thông tin đặt phòng hợp lệ. Bạn có thể check-in!';
      messageDiv.className = 'success';
    } else {
      const total = getTotalAmount(booking);
      html += `
        <div class="info"><label>Trạng thái thanh toán:</label> <span class="unpaid">Chưa thanh toán</span></div>
        <div class="info"><label>Số tiền cần thanh toán:</label> <span style="color:#d10000;font-weight:bold;">${total.toLocaleString()} đ</span></div>
        <div class="info"><label>Chọn phương thức thanh toán:</label>
          <div style="margin-top:6px;">
            <label><input type="radio" name="paymethod" value="bank" checked> Chuyển khoản ngân hàng</label><br>
            <label><input type="radio" name="paymethod" value="card"> Thẻ (ATM/Visa/MasterCard)</label><br>
            <label><input type="radio" name="paymethod" value="hotel"> Thanh toán tiền mặt tại khách sạn</label>
          </div>
          <button style="margin-top:12px;" onclick="payAtCheckin('${booking.bookingCode}');return false;">Thanh toán</button>
        </div>
      `;
      messageDiv.textContent = 'Bạn cần thanh toán trước khi check-in!';
      messageDiv.className = 'error';
    }
    infoDiv.innerHTML = html;
  } catch (err) {
    spinner.style.display = 'none';
    messageDiv.textContent = 'Lỗi kết nối máy chủ!';
    messageDiv.className = 'error';
  }
});

// Hàm xử lý thanh toán tại quầy check-in
function payAtCheckin(bookingCode) {
  const method = document.querySelector('input[name="paymethod"]:checked').value;
  // Cập nhật trạng thái thanh toán cho booking
  const bookings = JSON.parse(localStorage.getItem('bookings')) || [];
  const idx = bookings.findIndex(b => (b.bookingCode || '').toUpperCase() === bookingCode.toUpperCase());
  if (idx !== -1) {
    bookings[idx].paymentStatus = 'paid';
    localStorage.setItem('bookings', JSON.stringify(bookings));
    document.getElementById('message').textContent = 'Thanh toán thành công! Bạn có thể check-in.';
    document.getElementById('message').className = 'success';
    // Hiện lại thông tin đã thanh toán
    setTimeout(() => {
      document.getElementById('bookingInfo').innerHTML += `<div class="info"><label>Trạng thái thanh toán:</label> <span class="paid">Đã thanh toán</span></div>`;
    }, 300);
  }
}
