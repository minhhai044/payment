<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seat Layout</title>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    @vite('resources/js/choose-seat.js')
    <style>
        #seatShow {
            display: grid;
            grid-template-rows: repeat(12, 1fr);
            grid-template-columns: repeat(12, 1fr);
            gap: 5px;
            max-width: 600px;
            margin: 20px auto;
            transform-origin: left top;
        }

        .seat-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            font-size: 12px;
        }

        .seat-checkbox {
            width: 20px;
            height: 20px;
        }

        .seat-checkbox.regular {
            accent-color: #4caf50;
        }

        .seat-checkbox.vip {
            accent-color: #ff9800;
        }

        .seat-checkbox.double {
            accent-color: #2196f3;
        }
        .seat-checkbox.disabled {
            accent-color: #f44336;
        }

        .seat-label span {
            white-space: nowrap;
        }
        
    </style>
</head>

<body>
    <div id="seatShow" class="seat-selection"></div>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        const api = "http://payment.test/api/1/show";
        const seatShow = document.getElementById('seatShow');

        const show = async () => {
            try {
                const response = await axios.get(api);
                const data = response.data;

                const seatMap = data.seatMap;
                const rowLetters = Array.from({
                    length: 12
                }, (_, i) => String.fromCharCode(65 + i)); // A, B, C,..., L
                const maxRow = data.matrix.max_row;
                const maxCol = data.matrix.max_col;

                const createSeats = () => {
                    for (let row = 0; row < maxRow; row++) {
                        for (let col = 1; col <= maxCol; col++) {
                            const rowLetter = rowLetters[row];
                            const seat = seatMap[rowLetter] && seatMap[rowLetter][col];

                            if (!seat) continue; // Skip empty seats

                            const seatType = seat.type_seat_id;
                            const label = document.createElement('label');
                            label.className = 'seat-label';

                            const checkbox = document.createElement('input');
                            checkbox.type = "checkbox";
                            checkbox.className = `seat-checkbox ${getSeatClass(seatType)} ${seat.status}`;
                            checkbox.setAttribute('data-type', seatType);
                            checkbox.setAttribute('data-seat-price', seat.price);
                            checkbox.setAttribute('data-id', seat.id);
                            checkbox.setAttribute('id', `seat-${seat.id}`);
                            checkbox.setAttribute('data-status', seat.status);
                            checkbox.disabled = seat.status === 'unavailable';

                            // Đánh dấu ghế "hold" là checked
                            if (seat.status === 'hold') {
                                checkbox.checked = true;
                                checkbox.classList.add('selected'); // Thêm class selected
                            }

                            label.appendChild(checkbox);

                            const seatLabel = `${rowLetter}${col}`;
                            const text = document.createElement('span');
                            text.innerText = seatLabel;
                            label.appendChild(text);

                            seatShow.appendChild(label);
                        }
                    }
                };

                const getSeatClass = (typeId) => {
                    switch (typeId) {
                        case "1":
                            return 'regular';
                        case "2":
                            return 'vip';
                        case "3":
                            return 'double';
                        default:
                            return 'regular';
                    }
                };

                createSeats();
            } catch (error) {
                console.error("Error fetching seat data:", error);
            }
        };

        // Xử lý sự kiện click vào checkbox
        document.querySelector('.seat-selection').addEventListener('click', async (event) => {
            const seat = event.target.closest('.seat-checkbox'); // Lấy đúng checkbox
            if (!seat) return;
            console.log(seat);
            
            handleSeatSelection(seat);
        });

        async function handleSeatSelection(seat) {
            const seatId = seat.getAttribute('data-id');

            if (seat.checked) {
                // Chọn ghế
                seat.classList.add('selected'); // Đánh dấu ghế được chọn
                selectSeat(seat, seatId);
            } else {
                // Bỏ chọn ghế
                seat.classList.remove('selected'); // Xóa dấu ghế được chọn
                releaseSeat(seat, seatId);
            }
        }

        function selectSeat(seat, seatId) {
            let type_seat = seat.getAttribute('data-type');
            

            updateSeatOnServer(seatId, 'hold',type_seat);
        }

        function releaseSeat(seat, seatId) {
            let type_seat = seat.getAttribute('data-type');

            updateSeatOnServer(seatId, 'release',type_seat);
        }

        async function updateSeatOnServer(seatId, action,type_seat) {
            try {
                await axios.post('api/updateSeat', {
                    seat_id: seatId,
                    action,
                    type_seat
                });
            } catch (error) {
                if (error.response) {
                    const errorMessage = error.response.data.message || 'Đã xảy ra lỗi. Vui lòng thử lại.';
                    if (error.response.status === 409) {
                        alert(errorMessage);
                        location.reload();
                    }
                }
            }
        }

        show();
    </script>
</body>

</html>
