<?php

namespace App\Http\Controllers;

use App\Retour;
use Illuminate\Http\Request;

class ResellController extends Controller
{

    public function searchByName($name){

        return $retourData = Retour::select('invoice_name','reason','created_at')->where('customer_name',$name)->get();
    }
}
