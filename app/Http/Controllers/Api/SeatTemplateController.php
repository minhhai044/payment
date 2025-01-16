<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SeatTemplate;
use App\Models\TypeSeat;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SeatTemplateController extends Controller
{
    public function storeTypeSeat(Request $request)
    {
        try {
            $TypeSeat = TypeSeat::query()->create($request->all());
            return response()->json([
                'messenger' => "Thao tác thành công !!!",
                'data' => $TypeSeat
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'messenger' => "Thao tác không thành công !!!"
            ]);
        }
    }
    // Thêm template ghế
    public function store(Request $request)
    {
        //Lấy tất cả các trường id của matrixIds
        $matrixIds = array_column(SeatTemplate::MATRIXS, 'id');
        //lấy dữ liệu của getMatrixById
        $maxtrix = SeatTemplate::getMatrixById($request->matrix_id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:seat_templates',
            'matrix_id' => ['required', Rule::in($matrixIds)],
            'row_regular' => 'required|integer|min:0|max:' . $maxtrix['max_row'],
            'row_vip' => 'required|integer|min:0|max:' . $maxtrix['max_row'],
            'row_double' => 'required|integer|min:0|max:' . $maxtrix['max_row'],
            'description' => 'required|string|max:255'
        ], [
            'name.required' => 'Vui lòng nhập tên mẫu.',
            'name.unique' => 'Tên mẫu đã tồn tại.',
            'name.string' => 'Tên mẫu phải là kiểu chuỗi.',
            'name.max' => 'Độ dài tên mẫu không được vượt quá 255 ký tự.',
            'row_regular.required' => 'Vui lòng nhập số lượng hàng ghế.',
            'row_regular.integer'  => 'Hàng ghế phải là một số nguyên.',
            'row_regular.min'      => 'Hàng ghế phải lớn hơn hoặc bằng 0.',
            'row_regular.max'      => 'Hàng ghế phải nhỏ hơn hoặc bằng ' . $maxtrix['max_row'] . '.',


            'row_vip.required'     => 'Vui lòng nhập số lượng hàng ghế.',
            'row_vip.integer'      => 'Hàng ghế phải là một số nguyên.',
            'row_vip.min'          => 'Hàng ghế phải lớn hơn hoặc bằng 0.',
            'row_vip.max'      => 'Hàng ghế phải nhỏ hơn hoặc bằng ' . $maxtrix['max_row'] . '.',


            'row_double.required'  => 'Vui lòng nhập số lượng hàng ghế.',
            'row_double.integer'   => 'Hàng ghế phải là một số nguyên.',
            'row_double.min'       => 'Hàng ghế phải lớn hơn hoặc bằng 0.',
            'row_double.max'      => 'Hàng ghế phải nhỏ hơn hoặc bằng ' . $maxtrix['max_row'] . '.',

            'description.required' => 'Vui lòng nhập mô tả.',
            'description.string' => 'Mô tả phải là kiểu chuỗi.',
            'description.max' => 'Độ dài mô tả không được vượt quá 255 ký tự.',
            'matrix_id.required' => "Vui lòng chọn ma trận ghế",
            'matrix_id.in' => 'Ma trận ghế không hợp lệ.'
        ]);

        $validator->after(function ($validator) use ($request, $maxtrix) {
            // lấy tổng của các hàng ghế thường ,vip , đôi
            $total = $request->row_regular + $request->row_vip + $request->row_double;

            if (
                $validator->errors()->has('row_regular') ||
                $validator->errors()->has('row_vip') ||
                $validator->errors()->has('row_double')
            ) {
                // Lấy lỗi đầu tiên của từng trường
                $error_message = $validator->errors()->first('row_regular') ?:
                    $validator->errors()->first('row_vip') ?:
                    $validator->errors()->first('row_double');

                $validator->errors()->add('rows', $error_message);
            }
            // Nếu tổng của các hàng ghế khác với $maxtrix['max_row'] 
            if ($total !== $maxtrix['max_row']) {
                $validator->errors()->add('rows', 'Tổng số hàng ghế phải bằng ' . $maxtrix['max_row'] . '.');
            }
        });

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            $formattedErrors = [];

            foreach ($errors as $key => $value) {
                $formattedErrors[$key] = ['message' => $value[0]];
            }

            return response()->json([
                'errors' => $formattedErrors,
            ], Response::HTTP_UNPROCESSABLE_ENTITY); // 422
        }
        try {
            $data = [
                'name' => $request->name,
                'description' => $request->description,
                'matrix_id' => $request->matrix_id,
                'row_regular' => $request->row_regular,
                'row_vip' => $request->row_vip,
                'row_double' => $request->row_double,
            ];
            $seatTemplate = SeatTemplate::create($data);

            return response()->json([
                'message' => "Thao tác thành công",
                'seatTemplate' => $seatTemplate,
            ], Response::HTTP_CREATED); // 201

        } catch (\Throwable $th) {
            return response()->json([
                'errors' => $th->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR); // 500
        }
    }
    public function updateSeatStructure(Request $request, string $id)
    {
        // dd($request,$seatTemplate);
        try {
            $dataSeatTemplate = [
                'is_active' => isset($request->is_active) ? 1 : 0, // Mặc định cập nhật is_active
            ];
            $seatTemplate = SeatTemplate::findOrFail($id);
            $dataSeatTemplate = array_merge($dataSeatTemplate, [
                'is_publish' => 1,
                'is_active' => 1,
                'seat_structure' => json_encode($request->seat_structure),
            ]);
            // Thực hiện cập nhật
            $seatTemplate->update($dataSeatTemplate);
            return response()->json([
                'messenger' => 'Thao tác thành công !!!',
                'data' =>  $seatTemplate,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'messenger' => 'Thao tác không thành công !!!',
                'error' =>  $e,
            ]);
        }
    }
    public function getJson(string $id)
    {
        $seatTemplate = SeatTemplate::findOrFail($id);
        $seats = json_encode($seatTemplate->seat_structure, true);
        return response()->json([
            'data' => $seats
        ]);
    }
    public function show(string $id)
    {

        $seatTemplate = SeatTemplate::findOrFail($id);

        $matrix = SeatTemplate::getMatrixById($seatTemplate->matrix_id);

        $seats = json_decode($seatTemplate->seat_structure, true);
        $seatMap = [];


        $totalSeats = 0;

        // $seatMap = [];
        // foreach ($seats as $seat) {
        //     // dd($seat);
        //     $seatMap[$seat->coordinates_y][$seat->coordinates_x] = $seat;
        // }

        if ($seats) {
            foreach ($seats as $seat) {
                $coordinates_y = $seat['coordinates_y'];
                $coordinates_x = $seat['coordinates_x'];

                if (!isset($seatMap[$coordinates_y])) {
                    $seatMap[$coordinates_y] = [];
                }

                $seatMap[$coordinates_y][$coordinates_x] = $seat;

                if ($seat['type_seat_id'] == 3) {
                    $totalSeats += 2;
                } else {
                    $totalSeats++;
                }
            }
        }
        return response()->json([
            'matrix' => $matrix,
            'seatTemplate' => $seatTemplate,
            'seatMap' => $seatMap,
            'totalSeats' => $totalSeats
        ]);
    }
    public function showtime_seat(Request $request){
        try {
            
        } catch (\Throwable $th) {
            
        }
    }
}
