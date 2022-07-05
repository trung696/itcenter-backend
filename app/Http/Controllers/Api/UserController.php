<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userAll = User::all();
        return response()->json($userAll);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {        
        $validated = Validator::make($request->all(), [
            'name' => 'bail|required|max:200|min:2',
            'email' => 'bail|required|email|unique:users',
            'password' => 'bail|required|max:50|string',
            'repassword' => 'bail|required|max:50|string|same:password',
            'phone' => 'bail|required|numeric|unique:users',
            'address' => 'bail|required|max:200|min:2',
        ]);
        if ($validated->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Lỗi lòi mặt rồi',
                'error' => $validated->errors()
            ], 500);
        } else {
            $userAdd = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'repassword' => Hash::make($request->repassword),
                'phone' => $request->phone,
                'address' => $request->address,
                'status' => 0,
            ];
            User::create($userAdd);
            return response()->json([
                'status' => true,
                'message' => "Thêm thành công User",
                'contact' => $request->all()
            ], 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
