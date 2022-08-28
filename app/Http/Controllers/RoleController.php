<?php

namespace App\Http\Controllers;

use App\Permission;
use App\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\RoleRequest\RoleAddRequest;
use App\Http\Requests\RoleRequest\RoleEditRequest;
class RoleController extends Controller
{
    public function index(){
        $listRoles = Role::latest()->paginate(5);
        return view('role.index',compact('listRoles'));
    }

    public function add(){
        $listModule = Permission::where('parent_id',0)->get();
        return view('role.add',compact('listModule'));
    }
    public function store(RoleAddRequest $request){
        try{
            DB::beginTransaction();
            $roleInsert = Role::create([
                'name' => $request->name,
                'description' => $request->description
            ]);

            $roleInsert->permission()->attach($request->permission_id);
            DB::commit();
            session()->flash('success', 'Thêm thành công role.');
            return redirect()->route('route_BackEnd_role_list');
        }catch(\Exception $exception){
            DB::rollback();
            Log::error('message: '.$exception->getMessage() . 'line:'. $exception->getLine());
        }
    }

    public function edit($id){
        $listModule = Permission::where('parent_id',0)->get();
        $roleEdit =Role::find($id);
        $permissionChecked = $roleEdit->permission;
        return view('role.edit',compact('listModule','roleEdit','permissionChecked'));
    }

    public function update( RoleEditRequest $request, $id){
        try{
            DB::beginTransaction();
            $roleUpdate = Role::find($id)->update([
                'name' => $request->name,
                'description' => $request->description
            ]);
            $roleUpdate = Role::find($id);
            $roleUpdate->permission()->sync($request->permission_id);
            DB::commit();
            return redirect()->route('route_BackEnd_role_list');
        }catch(\Exception $exception){
            DB::rollback();
            Log::error('message: '.$exception->getMessage() . 'line:'. $exception->getLine());
        }
    }

    public function delete($id){
        try {
           Role::find($id)->delete();
            return response()->json([
                'code' => 200,
                'message' => 'success'
            ]);
    
        } catch (\Exception $exception) {
            Log::error('Message:' . $exception->getMessage() . '---Line: ' . $exception->getLine());
            return response()->json([
                'code' => 500,
                'message' => 'fail',
            ]);
        }
       }
    


}
