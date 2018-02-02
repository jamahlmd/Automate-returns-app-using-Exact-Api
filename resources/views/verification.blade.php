@extends('layouts.app')



@section('content')


<div class="container">
    <div class="row">
        <div class="col-md-12 text-center">


    <?php



    echo "<form action='dorivit_retouren_index' method='POST'>";

    echo "<div class='form-group'>";

    echo "<label for='exampleFormControlInput1'>Choose Database</label>";


            ?>

{{ csrf_field() }}

<?php
    echo "<select class='form-control' id='exampleFormControlInput1' name='administratiecode'>";
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
    echo "<input class='form-control' type='hidden' name='accesstoken' value=$accesstoken>";
    echo "<input class='form-control' type='hidden' name='refreshtoken' value=$refreshtoken>";
    echo "<input type='submit' style='margin: 20px 0;' class='btn btn-lg btn-block btn-success' name='administratiefilter' value='OK'>";
    echo "</div>";
    echo "</form>";

    #########################################################################################################################
    if (isset($_POST['administratiefilter']))
#########################################################################################################################
    {
        $administratiecode = $_POST['administratiecode'];
        $accesstoken = $_POST['accesstoken'];
        $refreshtoken = $_POST['refreshtoken'];

        echo "<form action='$_SERVER[PHP_SELF]' method='POST'>";
        echo "<input type='hidden' name='administratiecode' value=$administratiecode>";
        echo "<input type='hidden' name='accesstoken' value=$accesstoken>";
        echo "<input type='hidden' name='refreshtoken' value=$refreshtoken>";
        echo "</form>";


    }




    ?>
        </div>
    </div>
</div>
    @endsection

