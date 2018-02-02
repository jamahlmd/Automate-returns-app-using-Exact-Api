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
            "client_id" => "{b8a9ca51-55e5-4835-b565-e3fe8eb823e4}",
            "redirect_uri" => "http://192.168.111.64/eol/dorivit_retouren_index",
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
                "client_id" => "{b8a9ca51-55e5-4835-b565-e3fe8eb823e4}",
                "client_secret" => "YydpHFDNo4YW",
                "redirect_uri" => "http://192.168.111.64/eol/dorivit_retouren_index",
                "grant_type" => "authorization_code"
            );
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


    public function index(){


        $results = \App\Retour::orderBy('created_at', 'DESC')->get()->where('geretourd','==',false);


        return view('index',compact('results'));

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




        return view('menu');

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






            echo'<div class="panel panel-default">';
            echo '<div class="panel-heading"><b>'.$InvoiceToName.' Factuur gegevens</b></div>';
            echo '<table class="table table-striped table-hover">';
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

            echo '<tr>';

        echo '<td>';

        echo '<form id="skuur" method="POST" action="insert">';



        echo "<input type='hidden' name='alles[]' value='".urlencode(serialize($alles))."'>";
        echo "<input type='hidden' name='invoicenumber' value='".$invoicenumber."'>";
        echo "<input type='hidden' name='_token' value='".csrf_token()."'>";
        echo '<button class="btn btn-primary btn-block" name="db" value="db" type="submit" id="GO">Voeg toe</button>';
        echo "</form>";

            echo '</td>';


            echo '</tr>';

            echo '</tbody>';
            echo '</table>';
            echo '</div>';

            } else {

            echo "<h1 class=\"text-center display-3\">Factuur ID niet gevonden!</h1>";

            }

        }

        public function refresh(){




        $refreshtoken = \App\RefreshToken::find(1);


        $url = 'https://start.exactonline.nl/api/oauth2/token';
        $params = array(
            "refresh_token" => $refreshtoken->token,
            "client_id" => "{b8a9ca51-55e5-4835-b565-e3fe8eb823e4}",
            "client_secret" => "YydpHFDNo4YW",
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







