<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
 
 
class User extends Authenticatable
{
     use HasApiTokens, Notifiable;

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
	
/*AuthServiceProvider    $this->registerPolicies();官方已经写好，不需要下面语句
	public function owns($related)
    {
        return $this->id == $related->user_id;
    }*/
	
	public function roles()
{
    return $this->belongsToMany(Role::class);
}
// 判断用户是否具有某个角色
public function hasRole($role)
{
    if (is_string($role)) {
        return $this->roles->contains('name', $role);
    }
 
    return !! $role->intersect($this->roles)->count();
}
// 判断用户是否具有某权限
public function hasPermission($permission)
{
    return $this->hasRole($permission->roles);
}
// 给用户分配角色
public function assignRole($role)
{
    return $this->roles()->save(
        Role::whereName($role)->firstOrFail()
    );
}

}
