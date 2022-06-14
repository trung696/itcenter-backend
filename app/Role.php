<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable =['name','description'];
    public function permission(){
        return $this->belongsToMany(Permission::class,'permission_role','role_id','permission_id');
    }
}
