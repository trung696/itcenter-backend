<?php

namespace App\Http\Controllers\Client;

use App\FormContact;
use App\Http\Controllers\Controller;
use App\Jobs\SendEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class FormContactController extends Controller
{
    public function add(){
        return view('frontend.home.form.form');
    }

    public function store(Request $request){
        // dd($request->all());
        $con = FormContact::create([
            'name' => $request->name,
            'email' => $request->email,
            'birthday' => $request->birthday,
            'phone' => $request->phone
        ]);
        // truyền sang vieww nào , compact dữ liệu nào sang, funtion
        Mail::send('frontend.home.form.contentMail',compact('con'), function ($email){
            // mail nhận thư, tên người dùng
            $email->subject("Có người đăng kí tư vấn");
            $email->to('doanhptph10742@fpt.edu.vn','Phạm Tiến Doanh');
        });
        // $contact = FormContact::all();
        // $message = [
        //     'type' => 'Create task',
        //     'task' =>  $request->name,
        //     'content' => 'has been created!',
        // ];
        // SendEmail::dispatch($message, $contact)->delay(now()->addMinute(1));
        // return redirect()->back();

    }
}
