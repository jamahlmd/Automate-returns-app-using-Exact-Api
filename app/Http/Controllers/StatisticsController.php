<?php

namespace App\Http\Controllers;

use App\Retour;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){


        $date = date('m');


        function checkDate($number){

            $maand = 0;

            $maand = (date('m') - $number);

            switch ($maand){

                case 0:
                    return 12;
                    break;
                case -1:
                    return 11;
                    break;
                case -2:
                    return 10;
                    break;
                case -3:
                    return 9;
                    break;
                case -4:
                    return 8;
                    break;
                case -5:
                    return 7;
                    break;
                case -6:
                    return 6;
                    break;
                case -7:
                    return 5;
                    break;
                case -8:
                    return 4;
                    break;
                case -9:
                    return 3;
                    break;
                case -10:
                    return 2;
                    break;
                case -11:
                    return 1;
                    break;

                default:
                    return $maand;
            }

        }





        $maandVan0 = DB::table('retours')->select('customer_id','invoice_id')->whereMonth('created_at', checkDate(0))->whereYear('created_at', Carbon::now()->year)->distinct()->get()->count();
        $maandVan1 = DB::table('retours')->select('customer_id','invoice_id')->whereMonth('created_at', checkDate(1))->whereYear('created_at', Carbon::now()->subMonth()->year)->distinct()->get()->count();
        $maandVan2 = DB::table('retours')->select('customer_id','invoice_id')->whereMonth('created_at', checkDate(2))->whereYear('created_at', Carbon::now()->subMonth()->subMonth()->year)->distinct()->get()->count();
        $maandVan3 = DB::table('retours')->select('customer_id','invoice_id')->whereMonth('created_at', checkDate(3))->whereYear('created_at', Carbon::now()->subMonth()->subMonth()->subMonth()->year)->distinct()->get()->count();
        $maandVan4 = DB::table('retours')->select('customer_id','invoice_id')->whereMonth('created_at', checkDate(4))->whereYear('created_at', Carbon::now()->subMonth()->subMonth()->subMonth()->subMonth()->year)->distinct()->get()->count();
        $maandVan5 = DB::table('retours')->select('customer_id','invoice_id')->whereMonth('created_at', checkDate(5))->whereYear('created_at', Carbon::now()->subMonth()->subMonth()->subMonth()->subMonth()->subMonth()->year)->distinct()->get()->count();
        $maandVan6 = DB::table('retours')->select('customer_id','invoice_id')->whereMonth('created_at', checkDate(6))->whereYear('created_at', Carbon::now()->subMonth()->subMonth()->subMonth()->subMonth()->subMonth()->subMonth()->year)->distinct()->get()->count();
        $maandVan7 = DB::table('retours')->select('customer_id','invoice_id')->whereMonth('created_at', checkDate(7))->whereYear('created_at', Carbon::now()->subMonth()->subMonth()->subMonth()->subMonth()->subMonth()->subMonth()->subMonth()->year)->distinct()->get()->count();
        $maandVan8 = DB::table('retours')->select('customer_id','invoice_id')->whereMonth('created_at', checkDate(8))->whereYear('created_at', Carbon::now()->subMonth()->subMonth()->subMonth()->subMonth()->subMonth()->subMonth()->subMonth()->subMonth()->year)->distinct()->get()->count();
        $maandVan9 = DB::table('retours')->select('customer_id','invoice_id')->whereMonth('created_at', checkDate(9))->whereYear('created_at', Carbon::now()->subMonth()->subMonth()->subMonth()->subMonth()->subMonth()->subMonth()->subMonth()->subMonth()->subMonth()->year)->distinct()->get()->count();
        $maandVan10 = DB::table('retours')->select('customer_id','invoice_id')->whereMonth('created_at', checkDate(10))->whereYear('created_at', Carbon::now()->subMonth()->subMonth()->subMonth()->subMonth()->subMonth()->subMonth()->subMonth()->subMonth()->subMonth()->subMonth()->year)->distinct()->get()->count();
        $maandVan11 = DB::table('retours')->select('customer_id','invoice_id')->whereMonth('created_at', checkDate(11))->whereYear('created_at', Carbon::now()->subMonth()->subMonth()->subMonth()->subMonth()->subMonth()->subMonth()->subMonth()->subMonth()->subMonth()->subMonth()->subMonth()->year)->distinct()->get()->count();

        $tariefVan0 = DB::table('retours')->select('credit_amount')->where('if_credited','Wel')->whereMonth('created_at', checkDate(0))->whereYear('created_at', Carbon::now()->year)->sum('credit_amount');
        $tariefVan1 = DB::table('retours')->select('credit_amount')->where('if_credited','Wel')->whereMonth('created_at', checkDate(1))->whereYear('created_at', Carbon::now()->subMonth()->year)->sum('credit_amount');
        $tariefVan2 = DB::table('retours')->select('credit_amount')->where('if_credited','Wel')->whereMonth('created_at', checkDate(2))->whereYear('created_at', Carbon::now()->subMonth()->subMonth()->year)->sum('credit_amount');
        $tariefVan3 = DB::table('retours')->select('credit_amount')->where('if_credited','Wel')->whereMonth('created_at', checkDate(3))->whereYear('created_at', Carbon::now()->subMonth()->subMonth()->subMonth()->year)->sum('credit_amount');
        $tariefVan4 = DB::table('retours')->select('credit_amount')->where('if_credited','Wel')->whereMonth('created_at', checkDate(4))->whereYear('created_at', Carbon::now()->subMonth()->subMonth()->subMonth()->subMonth()->year)->sum('credit_amount');
        $tariefVan5 = DB::table('retours')->select('credit_amount')->where('if_credited','Wel')->whereMonth('created_at', checkDate(5))->whereYear('created_at', Carbon::now()->subMonth()->subMonth()->subMonth()->subMonth()->subMonth()->year)->sum('credit_amount');
        $tariefVan6 = DB::table('retours')->select('credit_amount')->where('if_credited','Wel')->whereMonth('created_at', checkDate(6))->whereYear('created_at', Carbon::now()->subMonth()->subMonth()->subMonth()->subMonth()->subMonth()->subMonth()->year)->sum('credit_amount');
        $tariefVan7 = DB::table('retours')->select('credit_amount')->where('if_credited','Wel')->whereMonth('created_at', checkDate(7))->whereYear('created_at', Carbon::now()->subMonth()->subMonth()->subMonth()->subMonth()->subMonth()->subMonth()->subMonth()->year)->sum('credit_amount');
        $tariefVan8 = DB::table('retours')->select('credit_amount')->where('if_credited','Wel')->whereMonth('created_at', checkDate(8))->whereYear('created_at', Carbon::now()->subMonth()->subMonth()->subMonth()->subMonth()->subMonth()->subMonth()->subMonth()->subMonth()->year)->sum('credit_amount');
        $tariefVan9 = DB::table('retours')->select('credit_amount')->where('if_credited','Wel')->whereMonth('created_at', checkDate(9))->whereYear('created_at', Carbon::now()->subMonth()->subMonth()->subMonth()->subMonth()->subMonth()->subMonth()->subMonth()->subMonth()->subMonth()->year)->sum('credit_amount');
        $tariefVan10 = DB::table('retours')->select('credit_amount')->where('if_credited','Wel')->whereMonth('created_at', checkDate(10))->whereYear('created_at', Carbon::now()->subMonth()->subMonth()->subMonth()->subMonth()->subMonth()->subMonth()->subMonth()->subMonth()->subMonth()->subMonth()->year)->sum('credit_amount');
        $tariefVan11 = DB::table('retours')->select('credit_amount')->where('if_credited','Wel')->whereMonth('created_at', checkDate(11))->whereYear('created_at', Carbon::now()->subMonth()->subMonth()->subMonth()->subMonth()->subMonth()->subMonth()->subMonth()->subMonth()->subMonth()->subMonth()->subMonth()->year)->sum('credit_amount');




        return view('statistics', compact(
            'maandVan0',
            'maandVan1',
            'maandVan2',
            'maandVan3',
            'maandVan4',
            'maandVan5',
            'maandVan6',
            'maandVan7',
            'maandVan8',
            'maandVan9',
            'maandVan10',
            'maandVan11',
            'tariefVan0',
            'tariefVan1',
            'tariefVan2',
            'tariefVan3',
            'tariefVan4',
            'tariefVan5',
            'tariefVan6',
            'tariefVan7',
            'tariefVan8',
            'tariefVan9',
            'tariefVan10',
            'tariefVan11'
        ));
    }
}
