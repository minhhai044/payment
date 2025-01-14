<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Quản lý mẫu sơ đồ ghế</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">Mẫu sơ đồ ghế</li>
                        <li class="breadcrumb-item active">Cập nhật</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <form id="seatForm" method="POST">
        @csrf
        @method('PUT')
        <div class="row">

            <div class="col-lg-9">
                <div class="row">
                    {{-- <div class="card ">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Thông tin mẫu sơ đồ ghế</h4>
                        </div><!-- end card header -->
                        <div class="card-body">
                            <div class="live-preview">
                                <div class="row">
                                    <div class="col-md-8 mb-2">
                                        <label for="name" class="form-label "><span class="text-danger">*</span> Tên
                                            mẫu:</label>
                                        <input type="text" class="form-control" value="{{ $seatTemplate->name }}"
                                            name='name'>
                                    </div>

                                    <div class="col-md-4 mb-2">
                                        <label for="branchId" class="form-label">
                                            Ma trận ghế:</label>
                                        @php
                                            $matrixSeatTemplate = App\Models\SeatTemplate::getMatrixById($seatTemplate->matrix_id,);
                                        @endphp

                                        <input type="text"
                                            value="{{ $matrixSeatTemplate['name'] }}" disabled class='form-control'>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <label for="description" class="form-label">
                                            <span class="text-danger">*</span> Mô tả:</label>
                                        <textarea name="description" id="" cols="30" rows="2" class='form-control'>{{ $seatTemplate->description }}</textarea>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Sơ đồ ghế</h4>
                        </div><!-- end card header -->
                        <div class="card-body mb-3">
                            @if (!$seatTemplate->is_publish)
                                <input type="hidden" name="seat_structure" id="seatStructure">
                                <input type="hidden" name="action" id="formAction">
                                <table class="table-chart-chair table-none align-middle mx-auto text-center mb-5">
                                    <tbody>
                                        @for ($row = 0; $row < $matrix['max_row']; $row++)
                                            @php
                                                $rowClass = '';
                                                $isAllRegular = $isAllVip = $isAllDouble = false;

                                                if ($row < $seatTemplate->row_regular) {
                                                    $rowTypeSeat = 1;
                                                    $rowClass = 'light-orange'; // Ghế thường
                                                    $isAllRegular = true;
                                                } elseif ($row < $seatTemplate->row_vip + $seatTemplate->row_regular) {
                                                    $rowClass = 'light-blue'; // Ghế VIP
                                                    $isAllVip = true;
                                                    $rowTypeSeat = 2;
                                                } else {
                                                    $rowClass = 'light-pink'; // Ghế đôi
                                                    $rowTypeSeat = 3;
                                                    $isAllDouble = true;
                                                }
                                            @endphp
                                            <tr data-row-type-seat={{ $rowTypeSeat }}>
                                                <td class="box-item">{{ chr(65 + $row) }}</td>
                                                @for ($col = 0; $col < $matrix['max_col']; $col++)
                                                    @php
                                                        // Kiểm tra xem ô hiện tại có trong seatMap không
                                                        $seatType =
                                                            isset($seatMap[chr(65 + $row)]) &&
                                                            isset($seatMap[chr(65 + $row)][$col + 1])
                                                                ? $seatMap[chr(65 + $row)][$col + 1]
                                                                : null;
                                                    @endphp
                                                    @if ($seatType == 3)
                                                        <!-- Nếu là ghế đôi -->
                                                        <td class="box-item border-1 {{ $rowClass }}"
                                                            data-row="{{ chr(65 + $row) }}" data-col={{ $col + 1 }}
                                                            colspan="2">
                                                            <div class="box-item-seat" data-type-seat-id="3">
                                                                <!-- 3 cho ghế đôi -->
                                                                <img src="{{ asset('svg/seat-double.svg') }}"
                                                                    class='seat' width="90%">
                                                            </div>
                                                        </td>
                                                        <td class="box-item border-1 {{ $rowClass }}"
                                                            style="display: none;" data-row="{{ chr(65 + $row) }}"
                                                            data-col={{ $col + 2 }} data-type-seat-id="3">
                                                            <div class="box-item-seat" data-type-seat-id="3">
                                                                <img src="{{ asset('svg/seat-add.svg') }}"
                                                                    class='seat' width="60%">
                                                            </div>
                                                        </td>
                                                        @php $col++; @endphp
                                                    @else
                                                        <td class="box-item border-1 {{ $rowClass }}"
                                                            data-row="{{ chr(65 + $row) }}"
                                                            data-col={{ $col + 1 }}>
                                                            <div class="box-item-seat"
                                                                data-type-seat-id="{{ $rowTypeSeat }}">
                                                                @switch($seatType)
                                                                    @case(1)
                                                                        <img src="{{ asset('svg/seat-regular.svg') }}"
                                                                            class='seat' width="100%">
                                                                    @break

                                                                    @case(2)
                                                                        <img src="{{ asset('svg/seat-vip.svg') }}"
                                                                            class='seat' width="100%">
                                                                    @break

                                                                    @default
                                                                        <img src="{{ asset('svg/seat-add.svg') }}"
                                                                            class='seat' width="60%">
                                                                @endswitch
                                                            </div>
                                                        </td>
                                                    @endif
                                                @endfor
                                                <td class='box-item border-1'>
                                                    <button type="button" class="btn btn-info btn-select-all btn-sm "
                                                        data-row="{{ chr(65 + $row) }}"> <i
                                                            class="ri-add-line align-bottom"></i></button>
                                                </td>
                                                <td class='box-item border-1'>
                                                    <button type="button" class="btn btn-danger btn-remove-all btn-sm "
                                                        data-row="{{ chr(65 + $row) }}"> <i
                                                            class="mdi mdi-trash-can-outline "></i></button>

                                                </td>

                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            @else
                                <div class="srceen w-75 mx-auto mb-4">
                                    Màn Hình Chiếu
                                    {{ chr(65 + 10) }}

                                </div>
                                <table class="table-chart-chair table-none align-middle mx-auto text-center mb-5">
                                    <tbody>
                                        @for ($row = 0; $row < $matrix['max_row']; $row++)
                                            <tr>
                                                <td class="box-item">{{ chr(65 + $row) }}</td>
                                                @for ($col = 0; $col < $matrix['max_col']; $col++)
                                                    @php

                                                        // Kiểm tra xem ô hiện tại có trong seatMap không
                                                        $seatType =
                                                            isset($seatMap[chr(65 + $row)]) &&
                                                            isset($seatMap[chr(65 + $row)][$col + 1])
                                                                ? $seatMap[chr(65 + $row)][$col + 1]
                                                                : null;
                                                    @endphp
                                                    @if ($seatType == 3)
                                                        <!-- Nếu là ghế đôi -->
                                                        <td class="box-item" colspan="2">
                                                            <div class="seat-item">
                                                                <!-- 3 cho ghế đôi -->
                                                                <img src="{{ asset('svg/seat-double.svg') }}"
                                                                    class='seat' width="100%">
                                                                <span
                                                                    class="seat-label-double">{{ chr(65 + $row) . ($col + 1) }}
                                                                    {{ chr(65 + $row) . ($col + 2) }}</span>
                                                            </div>
                                                        </td>
                                                        <td class="box-item" style="display: none;">
                                                            <div class="seat-item">
                                                                <img src="{{ asset('svg/seat-add.svg') }}"
                                                                    class='seat' width="60%">
                                                            </div>
                                                        </td>
                                                        @php $col++; @endphp
                                                    @else
                                                        <td class="box-item">
                                                            <div class="seat-item">
                                                                @switch($seatType)
                                                                    @case(1)
                                                                        <img src="{{ asset('svg/seat-regular.svg') }}"
                                                                            class='seat' width="100%">
                                                                        <span
                                                                            class="seat-label">{{ chr(65 + $row) . $col + 1 }}</span>
                                                                    @break

                                                                    @case(2)
                                                                        <img src="{{ asset('svg/seat-vip.svg') }}"
                                                                            class='seat' width="100%">
                                                                        <span
                                                                            class="seat-label">{{ chr(65 + $row) . $col + 1 }}</span>
                                                                    @break
                                                                @endswitch

                                                            </div>
                                                        </td>
                                                    @endif
                                                @endfor
                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="row">
                    <div class="col-md-12">
                        @if ($seatTemplate->is_publish == 1)
                            <div class="card card-seat ">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">Cập nhật</h4>
                                </div><!-- end card header -->
                                <div class="card-body ">
                                    <div class="row ">
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">Trạng thái:</label>
                                            <span class="text-muted">Đã xuất bản</span>
                                        </div>
                                        <div class="col-md-12 mb-3 d-flex ">
                                            <label class="form-label">Hoạt động:</label>
                                            @can('Sửa mẫu sơ đồ ghế')
                                                <span class="text-muted mx-2">
                                                    <div class="form-check form-switch form-switch-success">
                                                        <input
                                                            class="form-check-input switch-is-active channge-is-active-room"
                                                            type="checkbox" role="switch" name='is_active'
                                                            value='1' @checked($seatTemplate->is_active)>
                                                    </div>
                                                </span>
                                            @else
                                                <span class="text-muted mx-2">
                                                    <div class="form-check form-switch form-switch-success">
                                                        <input
                                                            class="form-check-input switch-is-active channge-is-active-room"
                                                            type="checkbox" role="switch" disabled readonly
                                                            name='is_active' value='1' @checked($seatTemplate->is_active)>
                                                    </div>
                                                </span>
                                            @endcan
                                        </div>
                                    </div>
                                    <div class='text-end'>
                                        <a href="" class='btn btn-light mx-1'>Quay
                                            lại</a>
                                        @can('Sửa mẫu sơ đồ ghế')
                                            <button type="submit" id="submitFormSeatDiagram"
                                                class='btn btn-primary mx-1'>Cập
                                                nhật</button>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="card card-seat ">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">Xuất bản</h4>
                                </div><!-- end card header -->
                                <div class="card-body ">

                                    <div class="row ">
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">Trạng thái:</label>
                                            <span class="text-muted">Bản nháp</span>
                                        </div>
                                        <div class="col-md-12 mb-3 ">
                                            <label class="form-label">Hoạt động:</label>
                                            <span class="text-muted">Chưa hoạt động</span>
                                        </div>
                                    </div>
                                    <div class='text-end'>
                                        <button type='submit' name='action' value="draft"
                                            class='btn btn-light mx-1'>Lưu
                                            nháp</button>
                                        <button type="submit" name='action' value="publish"
                                            onclick="return confirm('Sau khi xuất bản không thể thay đổi vị trí ghế, bạn có chắc chắn ?')"
                                            class='btn btn-primary mx-1'>Xuất
                                            bản</button>
                                    </div>

                                </div>
                            </div>
                        @endif

                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card card-seat ">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Chú thích</h4>
                        </div><!-- end card header -->
                        <div class="card-body ">
                            <table class="table table-borderless   align-middle mb-0">
                                @if ($seatTemplate->is_publish == true)
                                    <tbody>
                                        <tr>
                                            <td>Ghế thường</td>
                                            <td class="text-center"> <img src="{{ asset('svg/seat-regular.svg') }}"
                                                    height="30px">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Ghế vip</td>
                                            <td class="text-center"> <img src="{{ asset('svg/seat-vip.svg') }}"
                                                    height="30px">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Ghế đôi</td>
                                            <td class="text-center"> <img src="{{ asset('svg/seat-double.svg') }}"
                                                    height="30px">
                                            </td>
                                        </tr>

                                        <tr class="table-active">
                                            <th colspan='2' class="text-center">Tổng
                                                {{ $totalSeats }} chỗ ngồi</th>
                                        </tr>

                                    </tbody>
                                @else
                                    <tbody>
                                        <tr>
                                            <td>Hàng ghế thường</td>
                                            <td class="text-center">
                                                <div class='box-item border light-orange'></div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Hàng ghế vip</td>
                                            <td class="text-center">
                                                <div class='box-item border light-blue'></div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Hàng ghế đôi</td>
                                            <td class="text-center">
                                                <div class='box-item border light-pink'></div>
                                            </td>

                                    </tbody>
                                @endif

                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </form>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Hàm kiểm tra ghế kế bên có đủ điều kiện làm ghế đôi không
            function canMakeDoubleSeat(td) {
                return td && td.querySelector('img') && td.querySelector('img').src.includes('seat-add.svg') && td
                    .style.display !== 'none';
            }

            document.querySelectorAll('.box-item-seat').forEach(function(seat) {
                seat.addEventListener('click', function() {
                    var img = this.querySelector('img');
                    var currentSrc = img.src;
                    var typeSeatId = parseInt(this.dataset
                        .typeSeatId); // Lấy typeSeatId từ dataset và chuyển thành số nguyên

                    // closest() để tìm phần tử td
                    var tdElement = this.closest('td');
                    // nextElementSibling để tìm phần tử tdElement tiếp theo có cùng cấp
                    var nextTd = tdElement ? tdElement.nextElementSibling : null;
                    var previousTd = tdElement ? tdElement.previousElementSibling : null;

                    if (currentSrc.includes('seat-add.svg')) {
                        if (typeSeatId === 3) { // Ghế đôi
                            // Kiểm tra ghế bên phải và bên trái
                            if (canMakeDoubleSeat(nextTd)) {
                                // Chọn ghế đôi bên phải
                                tdElement.colSpan = 2;
                                nextTd.style.display = 'none';
                                img.src = "{{ asset('svg/seat-double.svg') }}";
                                img.style.width = "90%";
                            } else if (canMakeDoubleSeat(previousTd)) {
                                // Chọn ghế đôi bên trái
                                previousTd.colSpan = 2;
                                tdElement.style.display = 'none';
                                var previousImg = previousTd.querySelector('img');
                                previousImg.src = "{{ asset('svg/seat-double.svg') }}";
                                previousImg.style.width = "90%";
                            } else {
                                alert('Không có đủ chỗ trống để đặt ghế đôi.');
                            }
                        } else {
                            // Xử lý ghế thường và ghế VIP
                            img.src = (typeSeatId === 1) ? "{{ asset('svg/seat-regular.svg') }}" :
                                "{{ asset('svg/seat-vip.svg') }}";
                            img.style.width = "100%";
                        }
                    } else {
                        // Trả ghế về trạng thái ban đầu
                        img.style.width = "60%";
                        img.src = "{{ asset('svg/seat-add.svg') }}";

                        // Nếu ghế hiện tại là ghế đôi, trả colSpan về 1 và hiển thị lại ghế kế bên
                        if (typeSeatId === 3 && tdElement.colSpan === 2) {
                            if (nextTd) {
                                tdElement.colSpan = 1;
                                nextTd.style.display = '';
                            } else if (previousTd) {
                                previousTd.colSpan = 1;
                                tdElement.style.display = '';
                            }
                        }
                    }
                });
            });

            // Lắng nghe sự kiện click trên nút "Bỏ tất cả"
            document.querySelectorAll('.btn-remove-all').forEach(function(button) {
                button.addEventListener('click', function() {
                    var row = this.dataset.row;
                    var seats = document.querySelectorAll(`td[data-row="${row}"] .box-item-seat`);

                    seats.forEach(function(seatDiv) {
                        var img = seatDiv.querySelector('img');
                        img.src =
                            "{{ asset('svg/seat-add.svg') }}"; // Đặt lại hình ảnh ghế về trạng thái "add"
                        img.style.width = "60%"; // Đặt lại kích thước hình ảnh

                        // Đảm bảo trả lại colSpan về 1 nếu cần
                        var tdElement = seatDiv.closest('td');
                        if (tdElement.colSpan == 2) {
                            var nextTd = tdElement.nextElementSibling;
                            tdElement.colSpan = 1;
                            nextTd.style.display = '';
                        }
                    });
                });
            });

            // Lắng nghe sự kiện click trên nút "Chọn tất cả"
            document.querySelectorAll('.btn-select-all').forEach(function(button) {
                button.addEventListener('click', function() {
                    var row = this.dataset.row;
                    var seats = document.querySelectorAll(`td[data-row="${row}"] .box-item-seat`);
                    var seatType = parseInt(this.closest('tr').dataset.rowTypeSeat);

                    // Reset trạng thái cho các ghế trong hàng trước khi chọn mới
                    resetAllSeats(seats);

                    if (seatType === 1) { // Ghế thường
                        seats.forEach(function(seatDiv) {
                            var img = seatDiv.querySelector('img');
                            img.src = "{{ asset('svg/seat-regular.svg') }}";
                            img.style.width = "100%";
                        });
                    } else if (seatType === 2) { // Ghế VIP
                        seats.forEach(function(seatDiv) {
                            var img = seatDiv.querySelector('img');
                            img.src = "{{ asset('svg/seat-vip.svg') }}";
                            img.style.width = "100%";
                        });
                    } else if (seatType === 3) { // Ghế đôi
                        for (let i = 0; i < seats.length; i++) {
                            var seatDiv = seats[i];
                            var img = seatDiv.querySelector('img');
                            var tdElement = seatDiv.closest('td');
                            var nextTd = tdElement ? tdElement.nextElementSibling : null;

                            // Kiểm tra có thể ghép đôi
                            if (canMakeDoubleSeat(nextTd)) {
                                // Chọn ghế đôi bên phải
                                tdElement.colSpan = 2; // Tăng colSpan
                                nextTd.style.display = 'none'; // Ẩn ghế bên phải
                                img.src =
                                    "{{ asset('svg/seat-double.svg') }}"; // Cập nhật hình ảnh ghế đôi
                                img.style.width = "90%"; // Đặt lại kích thước
                                i++; // Bỏ qua ghế bên phải vì đã ghép đôi
                            } else if (i > 0 && canMakeDoubleSeat(seats[i - 1].closest('td'))) {
                                // Chọn ghế đôi bên trái nếu có
                                var previousTd = seats[i - 1].closest('td');
                                previousTd.colSpan = 2; // Tăng colSpan
                                tdElement.style.display = 'none'; // Ẩn ghế hiện tại
                                var previousImg = previousTd.querySelector('img');
                                previousImg.src =
                                    "{{ asset('svg/seat-double.svg') }}"; // Cập nhật hình ảnh ghế đôi
                                previousImg.style.width = "90%"; // Đặt lại kích thước
                            }
                        }
                    }
                });
            });

            // Hàm để reset trạng thái cho tất cả ghế trong hàng
            function resetAllSeats(seats) {
                seats.forEach(function(seatDiv) {
                    var img = seatDiv.querySelector('img');
                    var tdElement = seatDiv.closest('td');
                    var colSpan = tdElement.colSpan;

                    // Trả ghế về trạng thái ban đầu nếu nó không được chọn
                    img.src =
                        "{{ asset('svg/seat-add.svg') }}"; // Đặt lại hình ảnh ghế về trạng thái "add"
                    img.style.width = "60%"; // Đặt lại kích thước hình ảnh

                    // Đảm bảo trả lại colSpan về 1 nếu cần
                    if (colSpan == 2) {
                        tdElement.colSpan = 1; // Trả về colSpan
                        var nextTd = tdElement.nextElementSibling;
                        if (nextTd) nextTd.style.display = ''; // Hiển thị ghế bên cạnh
                    }
                });
            }

        });
    </script>
    {{-- Lấy dữ liệu khi gửi form --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Lắng nghe sự kiện click trên tất cả các button submit
            document.querySelectorAll('button[type="submit"]').forEach(function(button) {
                button.addEventListener('click', function(event) {
                    // Gán giá trị hành động tương ứng vào input ẩn
                    document.getElementById('formAction').value = this.dataset.action;
                });
            });

            // Lắng nghe sự kiện submit của form
            document.querySelector('form#seatForm').addEventListener('submit', function(event) {
                let seatStructure = [];

                // Duyệt qua tất cả các ghế đã chọn
                document.querySelectorAll('.box-item-seat').forEach(function(seatDiv) {
                    var img = seatDiv.querySelector('img');
                    if (!img.src.includes('seat-add.svg')) { // Bỏ qua ghế chưa chọn
                        let tdElement = seatDiv.closest('td');
                        let coordinates_x = tdElement.dataset.col; // Tọa độ x (cột)
                        let coordinates_y = tdElement.dataset.row; // Tọa độ y (hàng)
                        let type_seat_id = seatDiv.dataset.typeSeatId; // Loại ghế

                        // Thêm ghế vào mảng
                        seatStructure.push({
                            coordinates_x: coordinates_x,
                            coordinates_y: coordinates_y,
                            type_seat_id: type_seat_id,
                        });
                    }
                });

                // Chuyển seatStructure thành JSON và gán vào input hidden
                document.querySelector('#seatStructure').value = JSON.stringify(seatStructure);
            });
        });
    </script>
</body>

</html>

{{-- @section('style-libs')
    <link rel="stylesheet" href="{{ asset('theme/admin/assets/css/mainstyle.css') }}">
@endsection --}}


{{-- @section('script-libs')
    @if (!$seatTemplate->is_publish)
       
    @endif
@endsection --}}
