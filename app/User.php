<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
    }

    public function checkPermissionAccess($permissionCheck)
    {
        // user login có quyền add, edit và xem menu
        // B1 lấy tất cả các quyền của user đang login hệ thống
        /*B2 so sánh giá trị đưa vào của router hiện tại xem có tồn tại trong các quyền
        mà mình lấy được không*/
        $roles = auth()->user()->roles;

        if (is_array($roles) || is_object($roles)){
            foreach ($roles as $role) {
                $permissions = $role->permissions;
                if($permissions->contains('key_code', $permissionCheck)){
                    return true;
                }
            }
            return false;
        }
    }
}
