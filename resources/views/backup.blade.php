{{--APICONTROLLERBACKUP--}}
<?php

namespace App\Http\Controllers;

use App\Division;
use App\User;
use Illuminate\Http\Request;
use App\RefreshToken;
use App\AccessToken;
use Illuminate\Support\Facades\DB;




class ApiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function login()
    {

        //authorization log in
        $params = array("response_type" => "code",
            "client_id" => "{8a52d96e-d719-4129-8b40-c0751569ceac}",
            "redirect_uri" => "http://localhost/eol/dorivit_retouren_index",
        );
        $url = "https://start.exactonline.nl/api/oauth2/auth" . '?' . http_build_query($params);

        redirect()->to($url)->send();


    }

    public function getTokens()
    {

        if (isset($_GET['code'])) {


            $code = $_GET['code'];

            $url = 'https://start.exactonline.nl/api/oauth2/token';
            $params = array("code" => $code,
                "client_id" => "{8a52d96e-d719-4129-8b40-c0751569ceac}",
                "client_secret" => "628BAKWEXnJM",
                "redirect_uri" => "http://localhost/eol/dorivit_retouren_index",
            );
            "grant_type" => "authorization_code"
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($curl, CURLOPT_URL, $url);
            $json_response = curl_exec($curl);
            if (!empty(curl_error($curl))) echo "CURL_ERROR: " . curl_error($curl) . "<br/>";
            curl_close($curl);
            $result = json_decode($json_response);

            //var_dump($result);

            $accesstoken = $result->access_token;
            $refreshtoken = $result->refresh_token;


            //Refresh token in Database zetten en/of overschrijven
            $flight = RefreshToken::find(1);

            if($flight === null){

                RefreshToken::create([
                    'token' => $refreshtoken
                ]);

            } else {

                $flight->token = $refreshtoken;

                $flight->save();

            }


            //Access token in Database zetten en/of overschrijven
            $at = AccessToken::find(1);

            if($at === null){

                AccessToken::create([
                    'token' => $accesstoken
                ]);

            } else {

                $at->token = $accesstoken;

                $at->save();

            }

            //databases naar voren halen
            $tokenxmlheader[1] = "Authorization: Bearer $accesstoken";
            $tokenxmlheader[2] = "Content-Type: application/xml";
            $tokenxmlheader[3] = "Accept: application/xml";
            $tokenxmlheader[4] = "Cache-Control: private";
            $tokenxmlheader[5] = "Connection: Keep-Alive";
            $url = "https://start.exactonline.nl/docs/XMLDivisions.aspx";
            $curl = curl_init();
            curl_setopt($curl,CURLOPT_HTTPGET,TRUE);
            curl_setopt($curl,CURLOPT_RETURNTRANSFER,TRUE);
            curl_setopt($curl,CURLOPT_HTTPHEADER,$tokenxmlheader);
            curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,FALSE);
            curl_setopt($curl,CURLOPT_URL,$url);
            $result = curl_exec($curl);
            if (!empty(curl_error($curl))) echo "CURL_ERROR: " . curl_error($curl) . "<br/>";
            curl_close($curl);
            $xml = simplexml_load_string($result);



            return view('verification', compact(['accesstoken','code','refreshtoken','xml']));



        }
    }

    public function Verify(){


        //api/v1/Me request

        $accesstoken = \App\AccessToken::find(1);

        $tokenxmlheader[1] = "Authorization: Bearer $accesstoken->token";
        $tokenxmlheader[2] = "Content-Type: application/json";
        $tokenxmlheader[3] = "Accept: application/json";
        $tokenxmlheader[4] = "Cache-Control: private";
        $tokenxmlheader[5] = "Connection: Keep-Alive";
        $url = "https://start.exactonline.nl/api/v1/current/Me";
        $curl = curl_init();
        curl_setopt($curl,CURLOPT_HTTPGET,TRUE);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,TRUE);
        curl_setopt($curl,CURLOPT_HTTPHEADER,$tokenxmlheader);
        curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($curl,CURLOPT_URL,$url);
        $result = curl_exec($curl);
        if (!empty(curl_error($curl))) echo "CURL_ERROR: " . curl_error($curl) . "<br/>";
        curl_close($curl);

        $result = json_decode($result);


        //var_dump($result);



        //getting the division
        if(isset($result)) {
            $division = $result->d->results[0]->CurrentDivision;

//        echo $division;

            //Division in database zetten
            $d = Division::find(1);

            if ($d === null) {

                Division::create([
                    'division' => $division
                ]);

            } else {

                $d->division = $division;

                $d->save();

            }
        }

        $results = \App\Retour::get()->where('geretourd','==',false);


        return view('index',compact('results'));

    }


    public function SearchID()
    {


        $id = $_GET['id'];


        $at = \App\AccessToken::find(1);
        $division = \App\Division::find(1);

        $select = '$select';
        $filter = '$filter';


        //VOORBEELD ID
//        $id = "17700019";
        //VOORBEELD ID

        $invoicenumber = intval($id);

        $tokenxmlheader[1] = "Authorization: Bearer $at->token";
        $tokenxmlheader[2] = "Content-Type: application/json";
        $tokenxmlheader[3] = "Accept: application/json";
        $tokenxmlheader[4] = "Cache-Control: private";
        $tokenxmlheader[5] = "Connection: Keep-Alive";


        $url = "https://start.exactonline.nl/api/v1/$division->division/salesinvoice/SalesInvoices?$filter=InvoiceNumber+eq+$invoicenumber";
        //InvoiceToName = Klant naam
        //InvoiceDate = Factuurdatum
        //AmountFC= totaalbedrag
        //URI = Uri naar salesinvoice
        //InvoiceTO = KLantnummer key

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HTTPGET, TRUE);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $tokenxmlheader);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_URL, $url);
        $result = curl_exec($curl);
        if (!empty(curl_error($curl))) echo "CURL_ERROR: " . curl_error($curl) . "<br/>";
        curl_close($curl);

        $result = json_decode($result);

        if (isset($result->d->results[0])) {

            $InvoiceToName = $result->d->results[0]->InvoiceToName;
            $InvoiceTo = $result->d->results[0]->InvoiceTo;
            $AmountFC = $result->d->results[0]->AmountFC;
            $InvoiceDate = $result->d->results[0]->InvoiceDate;
            $uri = $result->d->results[0]->SalesInvoiceLines->__deferred->uri;


            $uri = "$uri?$select=NetPrice,ItemDescription,Quantity";
            //NetPrice
            //ItemDescription
            //Quantity


            $curl = curl_init();
            curl_setopt($curl, CURLOPT_HTTPGET, TRUE);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $tokenxmlheader);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($curl, CURLOPT_URL, $uri);
            $resultinvoices = curl_exec($curl);
            if (!empty(curl_error($curl))) echo "CURL_ERROR: " . curl_error($curl) . "<br/>";
            curl_close($curl);

            $resultinvoices = json_decode($resultinvoices);


            $resultinvoices = $resultinvoices->d->results;



            $url = "https://start.exactonline.nl/api/v1/$division->division/crm/Accounts(guid'$InvoiceTo')?$select=Country,Code,Email,CustomerSince";
            //CountryName
            //Email
            //CustomerSince
            //Code

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_HTTPGET, TRUE);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $tokenxmlheader);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($curl, CURLOPT_URL, $url);
            $accountresults = curl_exec($curl);
            if (!empty(curl_error($curl))) echo "CURL_ERROR: " . curl_error($curl) . "<br/>";
            curl_close($curl);

            $accountresults = json_decode($accountresults);


            $accountresults = $accountresults->d;

            $countryname = $accountresults->Country;
            $email = $accountresults->Email;
            $customersince = $accountresults->CustomerSince;
            $klantnr = $accountresults->Code;





            echo '<table id="table1" class="table1 table table-striped table-hover">';
            echo '<thead class="thead-inverse">';
            echo '<tr>';
            echo '<td><b>Naam</b></td>';
            echo '<td><b>FactuurDatum</b></td>';
            echo '<td><b>Artikel</b></td>';
            echo '<td><b>Aantal</b></td>';
            echo '<td><b>Prijs</b></td>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';


            //array maken
            $alles = [];



            foreach ($resultinvoices as $item) {

                $namen = array();


                echo '<tr>';

                echo "<td>$InvoiceToName</td>";


                $krijg = substr_replace($InvoiceDate, "", 0, 6);

                $epoch = substr_replace($krijg, "", -5);

                $Invoicedatum = date('Y-m-d', intval($epoch));


                $krijg = substr_replace($customersince, "", 0, 6);

                $epoch = substr_replace($krijg, "", -5);

                $customersinds = date('Y/m/d', intval($epoch));


                echo "<td>$Invoicedatum</td>";
                echo "<td>$item->ItemDescription</td>";
                echo "<td>$item->Quantity</td>";
                echo "<td>$item->NetPrice</td>";

                echo '</tr>';


                $namen[] = $InvoiceToName;
                $namen[] = $Invoicedatum;
                $namen[] = $item->ItemDescription;
                $namen[] = $item->Quantity;
                $namen[] = $item->NetPrice;
                $namen[] = $customersinds;
                $namen[] = $countryname;
                $namen[] = $email;
                $namen[] = $klantnr;
                $namen[] = $AmountFC;


                $alles[] = $namen;

            }

            //zoo kan ik met de data omgaan
//            foreach ($alles as $all){
//
//            echo $alles[0][0].'<br>';
//            }

            echo '<tr>';

            echo '<td>';

            echo '<form id="myForm" method="POST" action="insert">';



            echo "<input type='hidden' name='alles[]' value='".serialize($alles)."'>";
            echo "<input type='hidden' name='invoicenumber' value='".$invoicenumber."'>";
            echo "<input type='hidden' name='_token' value='".csrf_token()."'>";
            echo '<input class="btn btn-primary" value="Voeg toe" type="submit" id="GO">';
            echo "</form>";

            echo '</td>';


            echo '</tr>';

            echo '</tbody>';
            echo '</table>';

        } else {

            echo "<h1 class=\"display-3\">Factuur ID niet gevonden!</h1>";

        }

    }

    public function refresh(){




        $refreshtoken = \App\RefreshToken::find(1);


        $url = 'https://start.exactonline.nl/api/oauth2/token';
        $params = array(
            "refresh_token" => $refreshtoken->token,
            "client_id" => "{8a52d96e-d719-4129-8b40-c0751569ceac}",
            "client_secret" => "628BAKWEXnJM",
            "grant_type" => "refresh_token"
        );
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE
        );
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_URL, $url);
        $json_response = curl_exec($curl);
        if (!empty(curl_error($curl))) echo "CURL_ERROR: " . curl_error($curl) . "<br/>";
        curl_close($curl);
        $result = json_decode($json_response);

        //var_dump($result);

        $accesstoken = $result->access_token;
        $refreshtoken = $result->refresh_token;

        $flight = RefreshToken::find(1);

        if($flight === null){

            RefreshToken::create([
                'token' => $refreshtoken
            ]);

        } else {

            $flight->token = $refreshtoken;

            $flight->save();

        }


        //Access token in Database zetten en/of overschrijven
        $at = AccessToken::find(1);

        if($at === null){

            AccessToken::create([
                'token' => $accesstoken
            ]);

        } else {

            $at->token = $accesstoken;

            $at->save();

        }


    }




}



//DATACONTROLLERRRRR

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Retour;
use Illuminate\Support\Facades\DB;
use DateTime;
use Carbon\Carbon;
use App\Customer;
use Illuminate\Support\Facades\Input;
use Maatwebsite\Excel;





class DataController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }


    public function insert(Request $request)
    {


        $keys = \App\Retour::get()->where('geretourd', "==", false);


        foreach ($keys as $key) {

            $id = $key->id;


        }


        $alles = request('alles');
        $invoicenumber = intval(request('invoicenumber'));

        $total_orderamount = 0;
        //set variable

        foreach ($alles as $all) {


            $all = urldecode($all);

            $array = unserialize($all);


            for ($x = 0; $x <= (count($array) - 1); $x++) {

                $total_orderamount = $total_orderamount + $array[$x][3];
                //IN TOTAAL BESTELDE ARTIKELEN

//                echo $array[$x][3];
            }


            foreach ($array as $s) {


                if (Retour::get()->where('customer_id', '==', $s[8])
                        ->where('invoice_id', '==', $invoicenumber)
                        ->where('invoice_name', '==', $s[2])
                        ->where('invoice_price', '==', $s[4])
                        ->where('invoice_quantity', '==', $s[3])->count() > 0
                ) {

                    session()->flash('danger', 'Record bestaat al!');


                } else {
                    $now = DB::raw(now());


                    $datetime2 = new DateTime($s[1]);
                    $datetime1 = new DateTime($now);
                    $interval = $datetime1->diff($datetime2);

                    $interval = $interval->format('%a');


                    Retour::create([
                        'arrival_date' => DB::raw('now()'),
                        'customer_id' => $s[8],
                        'invoice_id' => $invoicenumber,
                        'invoice_date' => $s[1],
                        'customer_name' => $s[0],
                        'invoice_price' => $s[4],
                        'invoice_quantity' => $s[3],
                        'invoice_name' => $s[2],
                        'invoice_total' => $s[9],
                        'total_orderamount' => $total_orderamount,
                        'country_code' => $s[6],
                        'emailadress' => $s[7],
                        'date_difference' => $interval,
                    ]);

                    session()->flash('succes', 'Record toegevoegd!');

                }
                $naam = $s[0];
                $factuurdatum = $s[1];
                $artikel = $s[2];
                $aantal = $s[3];
                $prijs = $s[4];
                $customersince = $s[5];
                $countryname = $s[6];
                $email = $s[7];
                $klantr = $s[8];
                $AmountFC = $s[9];
            }


        }

        if (Customer::get()->where('customer_id', "==", $klantr)->count() > 0) {

            Customer::where('customer_id', "==", $klantr)->update(
                ['country_code' => $countryname],
                ['customer_name' => $naam],
                ['emailadress' => $email]
            );
        } else {

            Customer::create([
                'customer_id' => $klantr,
                'customer_name' => $naam,
                'country_code' => $countryname,
                'emailadress' => $email
            ]);
        }




        $results = \App\Retour::get()->where('geretourd', "==", false);


        return view('index', compact('results'));
    }


    public function export(Request $request)
    {

        if(Input::get('excel')) {

            $keys = \App\Retour::get()->where('geretourd', "==", false);


            foreach ($keys as $key) {

                $id = $key->id;

                $product_quantity = $request->input('aantalterug' . $id);
                $open_products = $request->input('aantalopen' . $id);
                $credit_amount = $request->input('total' . $id);
                $reason = $request->input('reden' . $id);
                $comment = $request->input('opmerking' . $id);
                $if_credited = $request->input('gecrediteerd' . $id);
                $carrier = $request->input('bezorger' . $id);
                $claim = $request->input('claim' . $id);


                $record = \App\Retour::find($id);

                $record->product_quantity = $product_quantity;
                $record->open_products = $open_products;
                $record->credit_amount = $credit_amount;
                $record->reason = $reason;
                $record->comment = $comment;
                $record->if_credited = $if_credited;
                $record->carrier = $carrier;
                $record->claim = $claim;
                $record->geretourd = true;

                $record->save();

            }




//            $exported = \App\Retour::get()->where('exported', '===', false);
//            $exported = \App\Retour::where('exported', "==", false)->get();
//
//            dd($exported);
//
//
//            foreach ($exported as $key) {
//
//                $id = $key->id;
//                $record = \App\Retour::find($id);
//                $record->exported = true;
//                $record->save();
//
//            }
            foreach ($keys as $key) {

                $id = $key->id;

                $record = \App\Retour::find($id);

                echo $id;

                $record->exported = true;

                $record->save();

            }



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
                'country_code',
                'emailadress',
                'carrier',
                'nlcall_id',
                'agent_name',
                'agent_id',
                'claim'
            ]);



            Excel\Facades\Excel::create('Retourgegevens', function ($excel) use ($data) {

                // Set the title
                $excel->setTitle('Retourivit');

                // Chain the setters
                $excel->setCreator('Jamahl')
                    ->setCompany('Dorivit');

                // Call them separately
                $excel->setDescription('Retour Gegevens');


                $excel->sheet('Sheetname', function ($sheet) use ($data) {

                    $sheet->fromArray($data);

//                $sheet->cell('A1', function($cell) {
//
//                    // manipulate the cell
//                    $cell->setFontWeight('bold');
//                    $cell->setFontSize(16);
//
//                });


                });

            })->download('xls');



            $results = \App\Retour::get()->where('geretourd', "==", false);


            return view('index', compact('results'));
        }

        if(Input::get('db')) {

            $keys = \App\Retour::get()->where('geretourd', "==", false);


            foreach ($keys as $key) {

                $id = $key->id;

                $product_quantity = $request->input('aantalterug' . $id);
                $open_products = $request->input('aantalopen' . $id);
                $credit_amount = $request->input('total' . $id);
                $reason = $request->input('reden' . $id);
                $comment = $request->input('opmerking' . $id);
                $if_credited = $request->input('gecrediteerd' . $id);
                $carrier = $request->input('bezorger' . $id);
                $claim = $request->input('claim' . $id);


                $record = \App\Retour::find($id);

                $record->product_quantity = $product_quantity;
                $record->open_products = $open_products;
                $record->credit_amount = $credit_amount;
                $record->reason = $reason;
                $record->comment = $comment;
                $record->if_credited = $if_credited;
                $record->carrier = $carrier;
                $record->claim = $claim;

                $record->save();

            }



            $results = \App\Retour::get()->where('geretourd', "==", false);


            return view('index', compact('results'));


        }


    }

}

//INDEXBLADE
@extends('layouts.app')



@section('content')


    @both

    <div class="container">
        <div class="row">
            <div class="col-md-2">
                <a href="home" class="btn btn-lg btn-info">Refresh token</a>
            </div>
            <div class="col-md-8 text-center">
                <label for="basic-url"><h1>Factuur nummer :</h1>
                </label>


            </div>
            <div class="col-md-2">

                <button type="submit" form="myform" name="excel" value="excel" class="btn btn-lg btn-success">Download bestand</button>
                <button type="submit" form="myform" name="db" value="db" id="db" class="btn btn-lg btn-success">TEST</button>
                {{--<button class="btn btn-lg btn-success">Download bestand</button>--}}



            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="input-group"  style="margin-bottom: 20px;">
                    {{--<input v-model="message" type="text" onkeyup="SearchID(this.value)" class="form-control" id="basic-url" aria-describedby="basic-addon3" placeholder="...">--}}





                    <span class="input-group-addon" id="basic-addon3">ID</span>
                    <input v-model="message" type="text" onkeyup="SearchID(this.value)" class="form-control" id="basic-url" aria-describedby="basic-addon3" placeholder="..." autofocus>
                </div>

            </div>
        </div>
        <div class="row">
            <div id="ShowData">



            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div id="table_wrapper">
                    <form method="post" action="export" id="myform">
                        <table id="demo-export" class="table1 table ta2ble-striped table-hover">
                            <thead class="table-head1">
                            <tr>
                                <th>Aangifte datum</th>
                                <th>Klantnr</th>
                                <th>Factuurnr</th>
                                <th>Factuurdatum</th>
                                <th>Naam</th>
                                <th>Besteld</th>
                                <th>Prijs</th>
                                <th>Aantalbesteld</th>
                                <th>Retour aantal</th>
                                <th>Open producten</th>
                                <th>Te crediteren</th>
                                <th>Reden</th>
                                <th>Extra</th>
                                <th>Crediteren wel of niet?</th>
                                <th>Contact opnemen?</th>
                                <th>Landcode</th>
                                <th>Email</th>
                                <th>Bezorger</th>
                                <th>nlcall_id</th>
                                <th>Agent naam</th>
                                <th>agent_id</th>
                                <th>claim</th>
                            </tr>
                            </thead>
                            <tbody>



                            {{csrf_field()}}

                            @if (isset($results))

                                @foreach($results as $row)

                                    <tr class="

                                @if($row->date_difference > 35)
                                            red
                                          @endif


                                            " >
                                        <td>{{ $row->arrival_date }}</td>
                                        <td>{{ $row->customer_id }}</td>
                                        <td>{{ $row->invoice_id }}</td>
                                        <td>{{ $row->invoice_date }}<br><b>{{ $row->date_difference }} dagen na factuur</b></td>
                                        <td>{{ $row->customer_name }}</td>
                                        <td>{{ $row->invoice_name }}</td>
                                        <td>{{ $row->invoice_price }}</td>
                                        <td>{{ $row->invoice_quantity }}</td>


                                        <input type='hidden' name='totaalaantal{{ $row->id }}' id='totaalaantal{{ $row->id }}' value='{{ $row->total_orderamount }}'>
                                        <input type='hidden' name='totaalprijs{{ $row->id }}' id='totaalprijs{{ $row->id }}' value='{{ $row->invoice_total }}'>
                                        <input type='hidden' name='prijs{{ $row->id }}' id='prijs{{ $row->id }}' value='{{ $row->invoice_price }}'>

                                        {{--werkt--}}
                                        {{--<td><input type='text' onkeyup="Aantalterug(this)" name='aantalterug' id='aantalterug{{ $row->id }}' value='0' />--}}




                                        <td>
                                            <div class="select-style">
                                                <select class="aantalterug" name='aantalterug{{ $row->id }}' id='aantalterug{{ $row->id }}'>
                                                    <option selected="selected" value="@if(isset($row->product_quantity)){{ $row->product_quantity }}@else 0 @endif">@if(isset($row->product_quantity)){{ $row->product_quantity }}@else 0 @endif</option>


                                                    @for ($i = 0; $i <= $row->total_orderamount; $i++)

                                                        <option value="{{$i}}">{{$i}}</option>

                                                    @endfor


                                                </select>
                                            </div>




                                        </td>




                                        {{--<td><input type='text' onkeyup="AantalOpen(this)" name='aantalopen' id='aantalopen{{ $row->id }}' value='0' />--}}


                                        <td class="aantalopeninput{{ $row->id }}">

                                            <div class="select-style">

                                                <select class="aantalopen" name='aantalopen{{ $row->id }}' id='aantalopen{{ $row->id }}'>
                                                    <option selected="selected" value="@if(isset($row->open_products)){{ $row->open_products }}@else 0 @endif">@if(isset($row->open_products)){{ $row->open_products }}@else 0 @endif</option>



                                                    @for ($i = 0; $i <= $row->total_orderamount; $i++)

                                                        <option value="{{$i}}">{{$i}}</option>

                                                    @endfor


                                                </select>

                                            </div>




                                        </td>


                                        <td>

                                            <div class="select-style2">

                                                <input class="redtype total" type='text' name='total{{ $row->id }}' id='total{{ $row->id }}' value='@if(isset($row->credit_amount)){{ $row->credit_amount }}@else 0 @endif' />

                                            </div>


                                        <td>
                                            <div class="select-style">


                                                <select class="aantalopen" onchange="showfield(this.options[this.selectedIndex].value)" name='reden{{ $row->id }}'>
                                                    <option selected="selected" value="{{ $row->reason }}">{{ $row->reason}}</option>
                                                    <option value="Geen reden">Geen reden</option>
                                                    <option value="Niet tevreden">Niet tevreden</option>
                                                    <option value="Geweigerd">Geweigerd</option>
                                                    <option value="Te laat geleverd">Te laat geleverd</option>
                                                    <option value="Advies dokter">Advies dokter</option>
                                                    <option value="Inpakfout">Inpakfout</option>
                                                    <option value="Medische redenen">Medische redenen</option>
                                                    <option value="Te duur">Te duur</option>
                                                    <option value="Persoon onbekend">Persoon onbekend</option>
                                                    <option value="Niet afgehaald">Niet afgehaald</option>
                                                    <option value="Adres klopt niet">Adres klopt niet</option>
                                                    <option value="Verkeerd huisnr.">Verkeerd huisnr.</option>
                                                    <option value="Niet besteld">Niet besteld</option>
                                                    <option value="Andere reden:">Andere reden</option>
                                                </select>



                                            </div>

                                        </td>

                                        <td>

                                            <div class="select-style2">

                                                <input class="total" value="{{ $row->comment }}" type="text" name="opmerking{{ $row->id }}">

                                            </div>

                                        </td>
                                        <td>

                                            <div class="select-style">

                                                <select class="aantalopen" name='gecrediteerd{{ $row->id }}'>
                                                    <option selected="selected" value="{{ $row->if_credited }}">{{ $row->if_credited }}</option>
                                                    <option value="Wel">Wel</option>
                                                    <option value="Niet">Niet</option>
                                                </select>

                                            </div>

                                        </td>
                                        <td>

                                            <div class="select-style">

                                                <select class="aantalopen" name='contact{{ $row->id }}'>
                                                    <option selected="selected" value="{{ $row->contact }}">{{ $row->contact }}</option>
                                                    <option value="Ja">Ja</option>
                                                    <option value="Nee">Nee</option>
                                                </select>

                                            </div>

                                        </td>

                                        <td class="countrycode">{{ $row->country_code }}</td>
                                        <td>{{ $row->emailadress }}</td>
                                        <td>

                                            <div class="select-style">

                                                <select class="aantalopen" name='bezorger{{ $row->id }}'>
                                                    <option selected="selected" value="{{ $row->carrier }}">{{ $row->carrier }}</option>
                                                    <option value="PostNL">PostNL</option>
                                                    <option value="UPS">UPS</option>
                                                    <option value="Postbode">Postbode</option>
                                                </select>

                                            </div>

                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>

                                            <div class="select-style">

                                                <select class="claim" name='claim{{ $row->id }}'>
                                                    <option selected="selected" value="{{ $row->claim }}">{{ $row->claim }}</option>
                                                    <option value="0">0</option>
                                                    <option value="1">1</option>
                                                </select>

                                            </div>

                                        </td>

                                    </tr>


                                @endforeach
                            @endif




                            </tbody>
                        </table>
                    </form>
                </div>


            </div>
        </div>
    </div>

    @else

        <div class="container text-center">
            <h1 class="display-2">Uw Account heeft geen rechten tot het maken van retouren.</h1>

        </div>

        @endboth




@endsection

@section('scripts')

    <script>
        function showfield(name){
            if(name=='Other')document.getElementById('div1').innerHTML='Other: <input type="text" name="other" />';
            else document.getElementById('div1').innerHTML='';}
    </script>
@endsection









