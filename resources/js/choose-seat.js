import "./bootstrap";
// Lắng nghe sự kiện SeatStatusChange từ server qua WebSocket (Laravel Echo)
window.Echo.channel("showtime").listen("SeatStatusChange", function (event) {
    const { seatId, status, type_seat } = event;
    console.log(event);

    // Cập nhật trạng thái ghế dựa trên seatId và status
    updateSeatStatus(seatId, status, type_seat);
});

// Cập nhật trạng thái của ghế khi nhận được sự kiện
function updateSeatStatus(seatId, status, type_seat) {
    // Tìm ghế theo seatId
    const seat = document.getElementById(`seat-${seatId}`);
    if (!seat) return; // Nếu không tìm thấy ghế, dừng xử lý

    // Cập nhật trạng thái ghế
    seat.setAttribute("data-status", status);

    // Xử lý trạng thái ghế "hold" hoặc "release"
    if (status === "hold") {
        seat.checked = true; // Đánh dấu ghế là đã chọn (checked)
        seat.classList.add("selected");
    } else {
        seat.checked = false; // Bỏ chọn ghế
        seat.classList.remove("selected");
    }

    // Xử lý các class CSS dựa trên trạng thái ghế
    seat.classList.remove("regular", "vip", "double", "disabled");
    seat.classList.add(getSeatClass(type_seat)); // Thêm class theo loại ghế

    // Nếu ghế không còn có sẵn (unavailable), disable nó
    if (status === "unavailable") {
        seat.disabled = true;
    } else {
        seat.disabled = false;
    }
}

// Hàm để lấy class dựa trên trạng thái ghế
function getSeatClass(type_seat) {
    switch (type_seat) {
        case "1":
            return "regular";
        case "2":
            return "vip";
        case "3":
            return "double";
        default:
            return "disabled";
    }
}
