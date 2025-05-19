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

document.getElementById('checkinForm').onsubmit = function(e) {
  e.preventDefault();
  const code = document.getElementById('bookingCode').value.trim();
  const msgDiv = document.getElementById('message');
  const infoDiv = document.getElementById('bookingInfo');
  msgDiv.textContent = '';
  infoDiv.innerHTML = '';

  if (!code) {
    msgDiv.textContent = 'Vui lòng nhập mã đặt phòng!';
    msgDiv.style.color = 'red';
    return;
  }

  fetch('PHP/checkin.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: 'bookingCode=' + encodeURIComponent(code)
  })
  .then(res => res.json())
  .then(data => {
    if (!data.success) {
      msgDiv.textContent = data.message || 'Không tìm thấy mã đặt phòng!';
      msgDiv.style.color = 'red';
      infoDiv.innerHTML = '';
      return;
    }
    msgDiv.textContent = '';
    const b = data.booking;
    // Xử lý trạng thái thanh toán
    let statusText = 'Chưa thanh toán';
    let statusColor = 'red';
    if (b.paid == 1 || b.TrangThaiThanhToan == 1) {
      statusText = 'Đã thanh toán';
      statusColor = 'green';
    }
    infoDiv.innerHTML = `
      <div><strong>Phòng:</strong> ${b.room}</div>
      <div><strong>Tên khách:</strong> ${b.fullname}</div>
      <div><strong>Email:</strong> ${b.email}</div>
      <div><strong>Điện thoại:</strong> ${b.phone}</div>
      <div><strong>Nhận phòng:</strong> ${b.checkin}</div>
      <div><strong>Trả phòng:</strong> ${b.checkout}</div>
      <div><strong>Trạng thái thanh toán:</strong> 
        <span style="color:${statusColor};font-weight:bold;">
          ${statusText}
        </span>
      </div>
    `;
  })
  .catch(() => {
    msgDiv.textContent = 'Có lỗi xảy ra, vui lòng thử lại!';
    msgDiv.style.color = 'red';
    infoDiv.innerHTML = '';
  });
};

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
