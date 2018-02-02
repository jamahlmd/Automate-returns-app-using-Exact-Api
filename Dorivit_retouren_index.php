<html>
<head>
  <title>Business Software Haaglanden</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>

</head>
<body>
<?php

use Symfony\Component\HttpFoundation\Response;

#########################################################################################################################
if (isset($_GET['code']))
#########################################################################################################################
{
  $code = $_GET['code'];

  $url = 'https://start.exactonline.nl/api/oauth2/token';
  $params = array("code" => $code,
                  "client_id" => "{8a52d96e-d719-4129-8b40-c0751569ceac}",
                  "client_secret" => "628BAKWEXnJM",
                  "redirect_uri" => "http://localhost/eol/dorivit_retouren_index.php",
                  "grant_type" => "authorization_code"
                 );
  $curl = curl_init();
  curl_setopt($curl,CURLOPT_POSTFIELDS,$params);
  curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,FALSE);
  curl_setopt($curl,CURLOPT_RETURNTRANSFER,TRUE);
  curl_setopt($curl,CURLOPT_URL,$url);
  $json_response = curl_exec($curl);
  if (!empty(curl_error($curl))) echo "CURL_ERROR: " . curl_error($curl) . "<br/>";
  curl_close($curl);
  $result = json_decode($json_response);
  $accesstoken = $result->access_token;
  $refreshtoken = $result->refresh_token;

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
  echo "<form action='dorivit_retouren_index' method='POST'>";

  ?>

    {{ csrf_field() }}


<?php
  echo "<select name='administratiecode'>";
  foreach ($xml->children() as $administraties)
  {
    $administratiecode = $administraties['Code'];
    $huidigeadministratie = $administraties['Current'];
    foreach ($administraties->children() as $administratieomschrijving)
    {
      $selected="";
      if ($huidigeadministratie) $selected=" selected";
      echo "<option value='$administratiecode'$selected>$administratieomschrijving</option>";
    }
  }
  echo "</select><br/>";
  echo "<input type='hidden' name='accesstoken' value=$accesstoken>";
  echo "<input type='hidden' name='refreshtoken' value=$refreshtoken>";
  echo "<input type='submit' name='administratiefilter' value='OK'>";
  echo "</form>";
}
#########################################################################################################################
if (isset($_POST['administratiefilter']))
#########################################################################################################################
{
  $administratiecode = $_POST['administratiecode'];
  $accesstoken = $_POST['accesstoken'];
  $refreshtoken = $_POST['refreshtoken'];

  echo "<form action='$_SERVER[PHP_SELF]' method='POST'>";
  ?>
{{ csrf_field() }}
<?php
  echo "<input type='hidden' name='administratiecode' value=$administratiecode>";
  echo "<input type='hidden' name='accesstoken' value=$accesstoken>";
  echo "<input type='hidden' name='refreshtoken' value=$refreshtoken>";
  echo "</form>";
}
//redirect()->to('http://localhost/eol/auth')->send();
//return Redirect::to('views.index');

//if (isset($_POST['administratiefilter'])) {
//
//    return view('index')->with([
//        'code' => $code,
//        'accesstoken' => $accesstoken,
//        'refreshtoken' => $refreshtoken,
//    ]);
//
//}

//header("Location: www.google.com");
//
//$query = array(
//    'at' => $accesstoken,
//    'rt' => $refreshtoken
//);

//$query = http_build_query($query);
//header("Location: authentication.blade..php?$query");

//echo "<a href='/auth".$query."'>Wakka</a>";
//echo "<a href='resources/views/apicall.blade.php?".$query."'>Wakka</a>";


//echo "$code<br><br>";
//echo "$refreshtoken<br><br>";
//echo "$accesstoken<br><br>";
?>
<script>
//    function ShowHint(str) {
//        var xhttp;
//        if (str.length == 0) {
//            document.getElementById("ajaxtable").innerHTML = "";
//            return;
//        }
//        xhttp = new XMLHttpRequest();
//        xhttp.onreadystatechange = function () {
//            if (this.readyState == 4 && this.status == 200) {
//                document.getElementById("ajaxtable").innerHTML = this.responseText;
//            }
//        };
//        xhttp.open("GET", "eol?location=" + str, true);
//        xhttp.send();
//    }
$(document).ready(function(){



//    $(".input").keyup(function(){
//        var txt = $("#suggest").val();
//        $.get("https://start.exactonline.nl/api/v1/current/Me", function(result){
//            $("#ajaxtable").html(result);
//        });
//    });



    $(".input").keyup(function(){

        $http({
            url: 'https://start.exactonline.nl/api/v1/current/Me',
            method: 'GET'
        }).then(function(res){
            console.log("successfully");
        }, function(error){
            console.log(error);
            alert(error.data);
        });

    });



});

</script>
<div class="container">
    <div class="row">
        <div class="col-md-offset-4 col-md-4 text-center ">
            <form action="">
<!--                <input  id="suggest" type="text" name="location" onkeyup="ShowHint(this.value)">-->
                <input  id="suggest" class="input" type="text" name="location">
            </form>

        </div>
    </div>
    <div id="ajaxtable" class="row">

    </div>
</div>



</body>
</html>
