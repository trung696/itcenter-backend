<?php

namespace App\Http\Controllers;

use App\Permission;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PermissionController extends Controller
{
    public function add(){
        return view('permission.add');
    }
    public function store(Request $request){
        try{
            //Them Permission cha
            // dd($request->all());
            DB::beginTransaction();
            $permission = Permission::create([
                'name'=>$request->table_module,
                'display_name'=>$request->table_module,
                'parent_id' => 0
            ]);
            //Them Permission con
            foreach($request->module_children as $value){
                Permission::create([
                    'name'=>$value,
                    'display_name'=>$value,
                    'parent_id' => $permission->id,
                    'key_code' =>$request->table_module . '_' . $value
                ]);
            }
            session()->flash('success', 'Thêm thành công Permision.');
            DB::commit();
        
            return redirect()->route('route_BackEnd_permission_add');
        }catch(Exception $exception){
            Log::error('message' . $exception->getMessage() . 'line:' . $exception->getLine());
        }
          
        // return redirect()->route('role.list');
    }
}
