<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\FormContact;
use App\Http\Requests\ApiRequest;
use App\Http\Requests\ApiRequest\FormRequestContact;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ApiContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = FormContact::all();
        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $con = [
            'name' => $request->name,
            'email' => $request->email,
            'birthday' => $request->birthday,
            'phone' => $request->phone,
            'note' => $request->note,
        ];
        FormContact::create($con);
        return response()->json([
            'status' => true,
            'heading' => "Thêm thành công contact",
            'contact' => $request->all()
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(FormContact $contact)
    {
        // FormContact::find($id);
        return $contact;
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FormContact $contact)
    {
        $edit = $contact->update($request->all());
        return response()->json([
            'status' => true,
            'message' => "Cập nhật thành công contact",
            'contact' => $edit
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(FormContact $contact)
    {
        $contact->delete();
        return response()->json([
            'status' => true,
            'message' => "Xóa thành công contact",
        ], 200);
    }
}
