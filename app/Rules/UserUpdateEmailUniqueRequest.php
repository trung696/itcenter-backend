<?php

namespace App\Rules;

use App\User;
use Illuminate\Contracts\Validation\Rule;

class UserUpdateEmailUniqueRequest implements Rule
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
    public function passes($attribute, $newEmail)
    {
       // attribute là name của input truyền vào, newValue là giá trị của input đó
        //email cũ trong db
        $oldEmail = User::find(request()->id)->email;
        //nếu name cũ trong db = nam mới thì cho request tiếp tục
        if($newEmail === $oldEmail){
            return true;
        }
        //nếu name mới khác name cũ thì sẽ up date
        // kiểm tra trong db xem có name nào trùng với name vừa nhập hay không
        // nếu có thì trả về false, chưa có thì chả về true
        $kiemTra = User::where('email',$newEmail)->count();
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
        return 'Email đẫ tồn tại';
    }
}
