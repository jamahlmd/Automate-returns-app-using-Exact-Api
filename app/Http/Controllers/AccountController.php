<?php

namespace App\Http\Controllers;

use App\Retour;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\View\View;



class AccountController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index(){
        if (Auth::check()) {
            $user = Auth::user();

            $id = $user->id;

            $posts = Retour::select('customer_name','arrival_date','invoice_id')->where('employee',$id)->distinct();
            $posts = $posts->paginate(5);

            if (request()->ajax()) {
                return Response::json(view()->make('records', array('records' => $posts))->render());
            }
            return view()->make('account', array('records' => $posts), compact('user'));

        }

    }


    public function invoice($invoice_id){

//        if (Auth::check()) {
//            $user = Auth::user();
//
//            $retour = Retour::get()->where('invoice_id',$invoice_id);
//
//
//            $id = $user->id;
//
//            $posts = Retour::select('customer_name','arrival_date','invoice_id')->where('employee',$id)->distinct();
//            $posts = $posts->paginate(5);
//
//            if (request()->ajax()) {
//                return Response::json(view()->make('records', array('records' => $posts))->render());
//            }
//            return view()->make('account', array('records' => $posts), compact(['user','retour']));
//
//        }

        $retour = Retour::get()->where('invoice_id',$invoice_id);

        return view('account',compact('retour'));







    }

}
