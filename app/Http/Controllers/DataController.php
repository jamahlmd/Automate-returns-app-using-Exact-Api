<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Authenticatable;
use Illuminate\Http\Request;
use App\Retour;
use Illuminate\Support\Facades\DB;
use DateTime;
use Carbon\Carbon;
use App\Customer;
use Illuminate\Support\Facades\Input;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel;
use Validator;
use Illuminate\Support\Facades\Auth;
use App\Agent;





class DataController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }


    public function insert(Request $request)

    {

        $alles = request('alles');
        $invoicenumber = intval(request('invoicenumber'));
        //Factuur nummer = $invoicenumber

        $total_orderamount = 0;

        foreach ($alles as $all) {


            $all = urldecode($all);

            $array = unserialize($all);


            for ($x = 0; $x <= (count($array) - 1); $x++) {

                $total_orderamount = $total_orderamount + $array[$x][3];
                //IN TOTAAL BESTELDE ARTIKELEN  = $total_orderamount

            }
            foreach ($array as $row){

                $name = $row[0];
                $invoicedate = $row[1];
                $customersince = $row[5];
                $countryname = $row[6];
                $email = $row[7];
                $klantnr = $row[8];
                $amountfc = $row[9];


                $now = DB::raw(now());

                $datetime2 = new DateTime($row[1]);
                $datetime1 = new DateTime($now);
                $interval = $datetime1->diff($datetime2);

                $interval = $interval->format('%a');




            }


            foreach ($array as $s) {


                if (Retour::get()->where('customer_id', '==', $s[8])
                        ->where('invoice_id', '==', $invoicenumber)
                        ->where('invoice_name', '==', $s[2])
                        ->where('invoice_price', '==', $s[4])
                        ->where('invoice_quantity', '==', $s[3])->count() > 0
                ) {

                    session()->flash('danger', 'Record bestaat al!');


                    $results = \App\Retour::orderBy('created_at', 'DESC')->get()->where('geretourd','==',false);


                    return view('index', compact('results'));


                } else {
                    $now = DB::raw(now());


                    $datetime2 = new DateTime($s[1]);
                    $datetime1 = new DateTime($now);
                    $interval = $datetime1->diff($datetime2);

                    $interval = $interval->format('%a');

                    if($interval > 35 ){
                        $if_credited = "Niet";
                    } else {
                        $if_credited = "Wel";
                    }

                    $user = Auth::user();


                    Retour::create([
                        'employee' => $user->id,
                        'arrival_date' => DB::raw('now()'),
                        'customer_id' => $s[8],
                        'invoice_id' => $invoicenumber,
                        'invoice_date' => $s[1],
                        'customer_name' => $s[0],
                        'invoice_price' => $s[4],
                        'invoice_quantity' => $s[3],
                        'if_credited' => $if_credited,
//                        'customer_since' => $s[5],
                        'contact' => 'Nee',
                        'claim' => 0,
                        'invoice_name' => $s[2],
                        'invoice_total' => $s[9],
                        'total_orderamount' => $total_orderamount,
                        'country_code' => $s[6],
                        'emailadress' => $s[7],
                        'date_difference' => $interval,
                    ]);

//                    session()->flash('succes', 'Record toegevoegd!');

                }
//                $naam = $s[0];
//                $factuurdatum = $s[1];
//                $artikel = $s[2];
//                $aantal = $s[3];
//                $prijs = $s[4];
//                $customersince = $s[5];
//                $countryname = $s[6];
//                $email = $s[7];
//                $klantnr = $s[8];
//                $AmountFC = $s[9];
            }


        }


        return view('retour', compact(['array',
            'invoicenumber',
            'total_orderamount',
            'name',
            'invoicedate',
            'customersince',
            'countryname',
            'email',
            'klantnr',
            'amountfc',
            'now',
            'interval'
        ]));
    }

    public function sheet(){

            $keys = \App\Retour::get()->where('geretourd', "==", false);


            $data = \App\Retour::where('exported', "==", false)->get([
                'arrival_date',
                'customer_id',
                'invoice_id',
                'invoice_date',
                'customer_name',
                'invoice_name',
                'product_quantity',
                'open_products',
                'credit_amount',
                'reason',
                'comment',
                'if_credited',
                'carrier',
                'country_code',
                'emailadress',
                'contact',
                'nlcall_id',
                'agent_name',
                'agent_id',
                'claim'
            ]);

            foreach ($keys as $key) {

                $id = $key->id;

                $record = \App\Retour::find($id);

                $record->exported = true;
                $record->geretourd = true;

                $record->save();

            }

            //Crediteren met Kleur
            //Wanneer kleur
            //Soms hele regel Kleur
            //prevent default
            //align left

        //Datum van vandaag pakken
        $datum = Date('d/m/Y');



        Excel\Facades\Excel::create('Retour Gegevens van '.$datum, function ($excel) use ($data,$datum) {

                // Set the title
                $excel->setTitle('Retourivit');

                // Chain the setters
                $excel->setCreator('Ahasan & Jamahl')
                    ->setCompany('Dorivit');

                // Call them separately
                $excel->setDescription('Retour Gegevens van '.$datum);

                $excel->getDefaultStyle()
                    ->getAlignment()
                    ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);


                $excel->sheet('Sheetname', function ($sheet) use ($data) {

                    $sheet->fromArray($data);


                    // Set multiple column formats
                    $sheet->setColumnFormat(array(
                        'B' => '0.00',
                    ));

                    //credit check
                    for ($i = 1; $i <= 400; $i ++){
                        $cellvalue = $sheet->getCell('L'.$i);

                        if($cellvalue == 'Niet'){

                            // Set black background
                            $sheet->row($i, function($row) {

                                // call cell manipulation methods
                                $row->setBackground('#FF0000');
                                $row->setFontColor('#ffffff');

                            });


                        }
                    }
                    for ($i = 1; $i <= 400; $i ++){
                        $cellvalue = $sheet->getCell('P'.$i);

                        if($cellvalue == 'Amazon' OR $cellvalue == 'Bol.com'){

                            $sheet->cell('P'.$i, function($cell) {

                                // manipulate the cell
                                $cell->setBackground('#FFFF00');
                                $cell->setFontColor('#000000');

                            });


                        }
                    }



                    $sheet->cell('A1', function($cell) {
                        // manipulate the cell
                        $cell->setFontWeight('bold');
                        $cell->setFontSize(16);
                        $cell->setValue('Aangifte datum');
                        $cell->setAlignment('left');
                    });
                    $sheet->cell('B1', function($cell) {
                        // manipulate the cell
                        $cell->setFontWeight('bold');
                        $cell->setFontSize(16);
                        $cell->setValue('KlantNr');
                        $cell->setAlignment('left');
                    });
                    $sheet->cell('C1', function($cell) {
                        // manipulate the cell
                        $cell->setFontWeight('bold');
                        $cell->setFontSize(16);
                        $cell->setValue('Factuur nr');
                        $cell->setAlignment('left');
                    });
                    $sheet->cell('D1', function($cell) {
                        // manipulate the cell
                        $cell->setFontWeight('bold');
                        $cell->setFontSize(16);
                        $cell->setValue('factuur d.d.');
                        $cell->setAlignment('left');
                    });
                    $sheet->cell('E1', function($cell) {
                        // manipulate the cell
                        $cell->setFontWeight('bold');
                        $cell->setFontSize(16);
                        $cell->setValue('Naam');
                        $cell->setAlignment('left');
                    });
                    $sheet->cell('F1', function($cell) {
                        // manipulate the cell
                        $cell->setFontWeight('bold');
                        $cell->setFontSize(16);
                        $cell->setValue('Producten');
                        $cell->setAlignment('left');
                    });
                    $sheet->cell('G1', function($cell) {
                        // manipulate the cell
                        $cell->setFontWeight('bold');
                        $cell->setFontSize(16);
                        $cell->setValue('Aantal');
                        $cell->setAlignment('left');
                    });
                    $sheet->cell('H1', function($cell) {
                        // manipulate the cell
                        $cell->setFontWeight('bold');
                        $cell->setFontSize(16);
                        $cell->setValue('Open producten');
                        $cell->setAlignment('left');
                    });
                    $sheet->cell('I1', function($cell) {
                        // manipulate the cell
                        $cell->setFontWeight('bold');
                        $cell->setFontSize(16);
                        $cell->setValue('Te crediteren');
                        $cell->setAlignment('left');
                    });
                    $sheet->cell('J1', function($cell) {
                        // manipulate the cell
                        $cell->setFontWeight('bold');
                        $cell->setFontSize(16);
                        $cell->setValue('Reden');
                        $cell->setAlignment('left');
                    });
                    $sheet->cell('K1', function($cell) {
                        // manipulate the cell
                        $cell->setFontWeight('bold');
                        $cell->setFontSize(16);
                        $cell->setValue('Extra');
                        $cell->setAlignment('left');
                    });
                    $sheet->cell('L1', function($cell) {
                        // manipulate the cell
                        $cell->setFontWeight('bold');
                        $cell->setFontSize(16);
                        $cell->setValue('Crediteren wel of niet?');
                        $cell->setAlignment('left');
                    });
                    $sheet->cell('M1', function($cell) {
                        // manipulate the cell
                        $cell->setFontWeight('bold');
                        $cell->setFontSize(16);
                        $cell->setValue('UPS/POST.nl');
                        $cell->setAlignment('left');
                    });
                    $sheet->cell('N1', function($cell) {
                        // manipulate the cell
                        $cell->setFontWeight('bold');
                        $cell->setFontSize(16);
                        $cell->setValue('Landcode');
                        $cell->setAlignment('left');
                    });
                    $sheet->cell('O1', function($cell) {
                        // manipulate the cell
                        $cell->setFontWeight('bold');
                        $cell->setFontSize(16);
                        $cell->setValue('Email');
                        $cell->setAlignment('left');
                    });
                    $sheet->cell('P1', function($cell) {
                        // manipulate the cell
                        $cell->setFontWeight('bold');
                        $cell->setFontSize(16);
                        $cell->setValue('Verkoper');
                        $cell->setAlignment('left');
                    });
                    $sheet->cell('Q1', function($cell) {
                        // manipulate the cell
                        $cell->setFontWeight('bold');
                        $cell->setFontSize(16);
                        $cell->setValue('nl-call nr.');
                        $cell->setAlignment('left');
                    });
                    $sheet->cell('R1', function($cell) {
                        // manipulate the cell
                        $cell->setFontWeight('bold');
                        $cell->setFontSize(16);
                        $cell->setValue('nl-call naam');
                        $cell->setAlignment('left');
                    });
                    $sheet->cell('S1', function($cell) {
                        // manipulate the cell
                        $cell->setFontWeight('bold');
                        $cell->setFontSize(16);
                        $cell->setValue('nr.');
                        $cell->setAlignment('left');
                    });
                    $sheet->cell('T1', function($cell) {
                        // manipulate the cell
                        $cell->setFontWeight('bold');
                        $cell->setFontSize(16);
                        $cell->setValue('claim');
                        $cell->setAlignment('left');
                    });


                });

            })->download('xls');


            $results = \App\Retour::orderBy('created_at', 'DESC')->get()->where('geretourd','==',false);


            return view('index', compact('results'));

    }

    public function export(Request $request)
    {

        if(Input::get('db')) {

            $keys = \App\Retour::get()->where('geretourd', "==", false);


            foreach ($keys as $key) {

                $id = $key->id;



                if(is_numeric($request->input('total' . $id))) {


                    $credit_amount = $request->input('total' . $id);

                    $record = \App\Retour::find($id);

                    $record->credit_amount = $credit_amount;

                    $record->save();




                } else{

                    session()->flash('danger', 'Het te crediteren bedrag mag alleen cijfers bevatten');

                }



                    $product_quantity = $request->input('aantalterug' . $id);
                    $open_products = $request->input('aantalopen' . $id);
                    $reason = $request->input('reden' . $id);
                    $comment = $request->input('opmerking' . $id);
                    $if_credited = $request->input('gecrediteerd' . $id);
                    $carrier = $request->input('bezorger' . $id);
                    $claim = $request->input('claim' . $id);
                    $contact = $request->input('contact' . $id);


                    $record = \App\Retour::find($id);

                    $record->product_quantity = $product_quantity;
                    $record->open_products = $open_products;
                    $record->reason = $reason;
                    $record->comment = $comment;
                    $record->if_credited = $if_credited;
                    $record->contact = $contact;
                    $record->carrier = $carrier;
                    $record->claim = $claim;

                    $record->save();


            }



            $results = \App\Retour::orderBy('created_at', 'DESC')->get()->where('geretourd','==',false);


            return view('index', compact('results'));


        }

        if(Input::get('product')) {

            $ids = $request->input('ids');

            $ids = urldecode($ids);
            $ids = unserialize($ids);

            foreach ($ids as $id){


                if(is_numeric($request->input('total' . $id))) {


                    $credit_amount = $request->input('total' . $id);

                    $record = \App\Retour::find($id);

                    $record->credit_amount = $credit_amount;

                    $record->save();




                } else{

                    session()->flash('danger', 'Het te crediteren bedrag mag alleen cijfers bevatten');

                }



                $product_quantity = $request->input('aantalterug' . $id);
                $open_products = $request->input('aantalopen' . $id);
                $reason = $request->input('reden' . $id);
                $comment = $request->input('opmerking' . $id);
                $if_credited = $request->input('gecrediteerd' . $id);
                $carrier = $request->input('bezorger' . $id);
                $claim = $request->input('claim' . $id);
                $contact = $request->input('contact' . $id);


                $record = \App\Retour::find($id);

                $record->product_quantity = $product_quantity;
                $record->open_products = $open_products;
                $record->reason = $reason;
                $record->comment = $comment;
                $record->if_credited = $if_credited;
                $record->contact = $contact;
                $record->carrier = $carrier;
                $record->claim = $claim;

                $record->save();

            }



            $results = \App\Retour::orderBy('created_at', 'DESC')->get()->where('geretourd','==',false);


            return view('index', compact('results'));


        }


    }


//    public function import(){
//
//        return Excel\Facades\Excel::load('file.xls', function($reader) {
//
//            // reader methods
//
//        });
//    }
//
//
    public function fileimport(){

        return view('import');
    }

    public function import(){

        $path = Input::file('import_file')->getRealPath();

        $data = Excel\Facades\Excel::load($path, function($reader) {

            $results = $reader->get()->toArray();


            foreach ($results[0] as $val){
                $insert[] = ['Naam' => $val['naam'], 'Nummer' => $val['persnr']];
            }

            if(!empty($insert)){

                foreach ($insert as $item){

                    $recs = Agent::get()->where('Nummer','==',$item['Nummer'])->count();

                    if ($recs <= 0){
                        Agent::create([
                           'Naam' => $item['Naam'],
                            'Nummer' => $item['Nummer']
                        ]);
                    }

                }
                }


        });

                return back();

    }

    public function record($invoice_id) {

        $retour = Retour::get()->where('invoice_id',$invoice_id);

        foreach ($retour as $record) {
            $invoicenumber = $record->invoice_id;
            $total_orderamount = $record->total_orderamount;
            $name = $record->customer_name;
            $invoicedate = $record->invoice_date;
//            $customersince = $record->customer_since;
            $countryname = $record->country_code;
            $email = $record->emailadress;
            $klantnr = $record->customer_id;
            $amountfc = $record->invoice_total;
            $interval = $record->date_difference;
        }

        return view('edit', compact(['retour',
            'invoicenumber',
            'total_orderamount',
            'name',
            'invoicedate',
            'countryname',
            'email',
            'klantnr',
            'amountfc',
            'interval'
        ]));



    }

    public function downloadinterface(){

        return view('interface');
    }

    public function download(Request $request){

        if($request['date']){

            $inputDate = $request["date"];

            $date = DateTime::createFromFormat('d/m/Y',$inputDate);

            $year = $date->format('Y');
            $month = $date->format('m');
            $day = $date->format('d');

            $data = Retour::whereYear('created_at',$year)
                ->whereMonth('created_at',$month)
                ->whereDay('created_at',$day)
                ->get(
                    [
                        'arrival_date',
                        'customer_id',
                        'invoice_id',
                        'invoice_date',
                        'customer_name',
                        'invoice_name',
                        'product_quantity',
                        'open_products',
                        'credit_amount',
                        'reason',
                        'comment',
                        'if_credited',
                        'carrier',
                        'country_code',
                        'emailadress',
                        'contact',
                        'nlcall_id',
                        'agent_name',
                        'agent_id',
                        'claim'
                    ]
                );

            if($data) {


                Excel\Facades\Excel::create('Retour Gegevens van ' . $inputDate, function ($excel) use ($data, $inputDate) {

                    // Set the title
                    $excel->setTitle('Retourivit');

                    // Chain the setters
                    $excel->setCreator('Ahasan & Jamahl')
                        ->setCompany('Dorivit');

                    // Call them separately
                    $excel->setDescription('Retour Gegevens van' . $inputDate);

                    $excel->getDefaultStyle()
                        ->getAlignment()
                        ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);


                    $excel->sheet('Sheetname', function ($sheet) use ($data) {

                        $sheet->fromArray($data);


                        // Set multiple column formats
                        $sheet->setColumnFormat(array(
                            'B' => '0.00',
                        ));

                        //credit check
                        for ($i = 1; $i <= 400; $i++) {
                            $cellvalue = $sheet->getCell('L' . $i);

                            if ($cellvalue == 'Niet') {

                                // Set black background
                                $sheet->row($i, function ($row) {

                                    // call cell manipulation methods
                                    $row->setBackground('#FF0000');
                                    $row->setFontColor('#ffffff');

                                });


                            }
                        }
                        for ($i = 1; $i <= 400; $i++) {
                            $cellvalue = $sheet->getCell('P' . $i);

                            if ($cellvalue == 'Amazon' OR $cellvalue == 'Bol.com') {

                                $sheet->cell('P' . $i, function ($cell) {

                                    // manipulate the cell
                                    $cell->setBackground('#FFFF00');
                                    $cell->setFontColor('#000000');

                                });


                            }
                        }


                        $sheet->cell('A1', function ($cell) {
                            // manipulate the cell
                            $cell->setFontWeight('bold');
                            $cell->setFontSize(16);
                            $cell->setValue('Aangifte datum');
                            $cell->setAlignment('left');
                        });
                        $sheet->cell('B1', function ($cell) {
                            // manipulate the cell
                            $cell->setFontWeight('bold');
                            $cell->setFontSize(16);
                            $cell->setValue('KlantNr');
                            $cell->setAlignment('left');
                        });
                        $sheet->cell('C1', function ($cell) {
                            // manipulate the cell
                            $cell->setFontWeight('bold');
                            $cell->setFontSize(16);
                            $cell->setValue('Factuur nr');
                            $cell->setAlignment('left');
                        });
                        $sheet->cell('D1', function ($cell) {
                            // manipulate the cell
                            $cell->setFontWeight('bold');
                            $cell->setFontSize(16);
                            $cell->setValue('factuur d.d.');
                            $cell->setAlignment('left');
                        });
                        $sheet->cell('E1', function ($cell) {
                            // manipulate the cell
                            $cell->setFontWeight('bold');
                            $cell->setFontSize(16);
                            $cell->setValue('Naam');
                            $cell->setAlignment('left');
                        });
                        $sheet->cell('F1', function ($cell) {
                            // manipulate the cell
                            $cell->setFontWeight('bold');
                            $cell->setFontSize(16);
                            $cell->setValue('Producten');
                            $cell->setAlignment('left');
                        });
                        $sheet->cell('G1', function ($cell) {
                            // manipulate the cell
                            $cell->setFontWeight('bold');
                            $cell->setFontSize(16);
                            $cell->setValue('Aantal');
                            $cell->setAlignment('left');
                        });
                        $sheet->cell('H1', function ($cell) {
                            // manipulate the cell
                            $cell->setFontWeight('bold');
                            $cell->setFontSize(16);
                            $cell->setValue('Open producten');
                            $cell->setAlignment('left');
                        });
                        $sheet->cell('I1', function ($cell) {
                            // manipulate the cell
                            $cell->setFontWeight('bold');
                            $cell->setFontSize(16);
                            $cell->setValue('Te crediteren');
                            $cell->setAlignment('left');
                        });
                        $sheet->cell('J1', function ($cell) {
                            // manipulate the cell
                            $cell->setFontWeight('bold');
                            $cell->setFontSize(16);
                            $cell->setValue('Reden');
                            $cell->setAlignment('left');
                        });
                        $sheet->cell('K1', function ($cell) {
                            // manipulate the cell
                            $cell->setFontWeight('bold');
                            $cell->setFontSize(16);
                            $cell->setValue('Extra');
                            $cell->setAlignment('left');
                        });
                        $sheet->cell('L1', function ($cell) {
                            // manipulate the cell
                            $cell->setFontWeight('bold');
                            $cell->setFontSize(16);
                            $cell->setValue('Crediteren wel of niet?');
                            $cell->setAlignment('left');
                        });
                        $sheet->cell('M1', function ($cell) {
                            // manipulate the cell
                            $cell->setFontWeight('bold');
                            $cell->setFontSize(16);
                            $cell->setValue('UPS/POST.nl');
                            $cell->setAlignment('left');
                        });
                        $sheet->cell('N1', function ($cell) {
                            // manipulate the cell
                            $cell->setFontWeight('bold');
                            $cell->setFontSize(16);
                            $cell->setValue('Landcode');
                            $cell->setAlignment('left');
                        });
                        $sheet->cell('O1', function ($cell) {
                            // manipulate the cell
                            $cell->setFontWeight('bold');
                            $cell->setFontSize(16);
                            $cell->setValue('Email');
                            $cell->setAlignment('left');
                        });
                        $sheet->cell('P1', function ($cell) {
                            // manipulate the cell
                            $cell->setFontWeight('bold');
                            $cell->setFontSize(16);
                            $cell->setValue('Verkoper');
                            $cell->setAlignment('left');
                        });
                        $sheet->cell('Q1', function ($cell) {
                            // manipulate the cell
                            $cell->setFontWeight('bold');
                            $cell->setFontSize(16);
                            $cell->setValue('nl-call nr.');
                            $cell->setAlignment('left');
                        });
                        $sheet->cell('R1', function ($cell) {
                            // manipulate the cell
                            $cell->setFontWeight('bold');
                            $cell->setFontSize(16);
                            $cell->setValue('nl-call naam');
                            $cell->setAlignment('left');
                        });
                        $sheet->cell('S1', function ($cell) {
                            // manipulate the cell
                            $cell->setFontWeight('bold');
                            $cell->setFontSize(16);
                            $cell->setValue('nr.');
                            $cell->setAlignment('left');
                        });
                        $sheet->cell('T1', function ($cell) {
                            // manipulate the cell
                            $cell->setFontWeight('bold');
                            $cell->setFontSize(16);
                            $cell->setValue('claim');
                            $cell->setAlignment('left');
                        });


                    });

                })->download('xls');

            } else {
                session()->flash('danger','Geen retourgegevens gevonden op deze datum');

                return back();
            }


            session()->flash('success','gegevens zijn gedownload!');

            return back();



        } else {
            session()->flash('danger','Vul een datum in!');

            return back();
        }

    }

}
