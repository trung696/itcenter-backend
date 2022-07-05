<?php

namespace App\Rules;

use App\Role;
use Illuminate\Contracts\Validation\Rule;

class RoleUpdateUniqueRequest implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $newName)
    {
       // attribute là name của input truyền vào, newValue là giá trị của input đó
        //name cũ trong db
        $oldName = Role::find(request()->id)->name;
        //nếu name cũ trong db = nam mới thì cho request tiếp tục
        if($newName === $oldName){
            return true;
        }

        //nếu name mới khác name cũ thì sẽ up date
        // kiểm tra trong db xem có name nào trùng với name vừa nhập hay không
        // nếu có thì trả về false, chưa có thì chả về true
        $kiemTra = Role::where('name',$newName)->count();
        if($kiemTra>0){
            return false;
        }
        return true;

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Tên vai trò đã tồn tại';
    }
}
