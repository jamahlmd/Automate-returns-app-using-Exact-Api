<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

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


//    public function isAdmin(){
//
//        $admin = User::get()->where('admin','==',true);
//
//        return $admin;
//    }



    public function roles()
    {
        return $this->belongsToMany('App\Role','role_user');
    }



    public function isAdmin()
    {
        foreach ($this->roles()->get() as $role)
        {
            if ($role->name == 'Admin')
            {
                return true;
            }
        }

        return false;
    }
    public function isBoth()
    {
        foreach ($this->roles()->get() as $role)
        {
            if ($role->name == 'Admin' OR $role->name == 'Manager')
            {
                return true;
            }
        }

        return false;
    }
}
