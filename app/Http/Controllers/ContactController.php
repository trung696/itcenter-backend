<?php

namespace App\Http\Controllers;

use App\FormContact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index(){
        $lisContacts = FormContact::paginate(15);
        return view('contact.index',compact('lisContacts'));
    }
    public function check($id){
        FormContact::where('id',$id)->update(array('status' => 1));
        return redirect()->route('route_BackEnd_contact_list');
    }
}
