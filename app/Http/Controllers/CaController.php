<?php

namespace App\Http\Controllers;

use App\Ca;
use Illuminate\Http\Request;
use App\Http\Requests\CaRequest\CaAddRequest;

class CaController extends Controller
{
    public function index(){
        $listCa = Ca::latest()->paginate(5);
        return view('ca.index',compact('listCa'));
    }

    public function add(){
        $this->v['_action'] = 'Add';
        $this->v['_title'] = 'Thêm ca học';
        return view('ca.add', $this->v);
    }

    public function store(CaAddRequest $request){
        $ca = [
            'ca_hoc' =>$request->ca_hoc,
            'trang_thai' =>$request->trang_thai
        ];
        Ca::create($ca);
        return redirect()->route('route_BackEnd_ca_list');
    }
}
