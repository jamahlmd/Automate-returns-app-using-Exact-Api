<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Role;



class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index(){

        $admins = User::whereHas('roles', function ($query) {
            $query->where('role_id', 'like', 1);
        })->get();

        $managers = User::whereHas('roles', function ($query) {
            $query->where('role_id', 'like', 2);
        })->get();

        $guests = User::doesntHave('roles')->get();

        $noadmins = User::whereDoesntHave('roles', function ($query) {
            $query->where('role_id', 'like', 1);
        })->get();


        return view('rechten',compact(['admins','managers','guests','noadmins']));
    }


    public function change(Request $request){

        $validatedData = $request->validate([
            'user' => 'numeric'
        ]);


        $id = $request->input('user');
        $rights = \request('rights');

        $person = User::find($id);



        if ($rights == 0){
            $person->roles()->detach(2);

        } else {

            $role = $person->roles()->where('role_id',2)->first();

            if(null != $role){


            } else{
                $person->roles()->attach(2);


            }




        }

        $admins = User::whereHas('roles', function ($query) {
            $query->where('role_id', 'like', 1);
        })->get();

        $managers = User::whereHas('roles', function ($query) {
            $query->where('role_id', 'like', 2);
        })->get();

        $guests = User::doesntHave('roles')->get();

        $noadmins = User::whereDoesntHave('roles', function ($query) {
            $query->where('role_id', 'like', 1);
        })->get();


        return view('rechten',compact(['admins','managers','guests','noadmins']));
    }
}
