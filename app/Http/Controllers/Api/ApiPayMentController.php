<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApiPayMentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'payment_method_id' => 'bail|required|numeric',
            'payment_date' => 'bail|required|date',
            'price' => 'bail|required|numeric',
            'description' => 'required',
            'status' => 'required',

        ];
        $messages = [
            'payment_method_id.required' => 'payment_method_id không để trống',
            'payment_date.numeric' => 'Nhập id phương thức thanh toán',

            'payment_date.required' => 'payment_date không để trống',
            'payment_date.date' => 'Ngày thanh toán định dạng y-m-d,giờ-phút-giây',

            'price.required' => 'price không được để trống',
            'price.numeric' => 'Định dạng số',

            'description.required' => 'Mô tả không để trống',

            'status.required' => 'Status không để trống',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'heading' => 'Chưa qua được validate',
                'error' => $validator->errors()
            ],500);
        }

        $payment = Payment::create([
            'payment_method_id'=>$request->payment_method_id,
            'payment_date'=>$request->payment_date,
            'price'=>$request->price,
            'description'=>$request->description,
            'status'=>$request->status,
        ]);
        return response()->json([
            'status' => true,
            'heading' => 'Thêm thành công vào bảng payment',
            'error' => $payment
        ],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        dd(Payment::find($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
