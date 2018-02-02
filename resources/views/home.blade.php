@extends('layouts.app')

@section('content')
<script>

    function ShowHint(str) {
        var xhttp;
        if (str.length == 0) {
            document.getElementById("ajaxtable").innerHTML = "";
            return;
        }
        xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("ajaxtable").innerHTML = this.responseText;
            }
        };
        // xhttp.open("GET", "gethint.php?location="+str, true);
        xhttp.open("GET", "search?id=" + str, true);
        xhttp.send();
    }


        $(document).ready(function() {
            $("#btnExport").click(function(e) {
                e.preventDefault();

                var d = new Date();

                //getting data from our table
                var data_type = 'data:application/vnd.ms-excel';
                var table_div = document.getElementById('table_wrapper');
                var table_html = table_div.outerHTML.replace(/ /g, '%20');

                var a = document.createElement('a');
                a.href = data_type + ', ' + table_html;
//              a.download = 'Retourgegevens' + Math.floor((Math.random() * 9999999) + 1000000) + '.xls';
                a.download = 'Retourgegevens' + d.getDay() + d.getTime() + '.xls';
                a.click();
            });
        });

    function tab1_To_tab2()
    {
        var table1 = document.getElementById("table1"),
            table2 = document.getElementById("table2"),
            checkboxes = document.getElementsByName("check-tab1");
        console.log("Val1 = " + checkboxes.length);
        for(var i = 0; i < checkboxes.length; i++)
            if(checkboxes[i].checked)
            {
                // create new row and cells
                var newRow = table2.insertRow(table2.length),
                    cell1 = newRow.insertCell(0),
                    cell2 = newRow.insertCell(1),
                    cell3 = newRow.insertCell(2),
                    cell4 = newRow.insertCell(3);
                // add values to the cells
                cell1.innerHTML = table1.rows[i+1].cells[0].innerHTML;
                cell2.innerHTML = table1.rows[i+1].cells[1].innerHTML;
                cell3.innerHTML = table1.rows[i+1].cells[2].innerHTML;
                cell4.innerHTML = "<input type='checkbox' name='check-tab2'>";

                // remove the transfered rows from the first table [table1]
                var index = table1.rows[i+1].rowIndex;
                table1.deleteRow(index);
                // we have deleted some rows so the checkboxes.length have changed
                // so we have to decrement the value of i
                i--;
                console.log(checkboxes.length);
            }
    }

    function tab2_To_tab1()
    {
        var table1 = document.getElementById("table1"),
            table2 = document.getElementById("table2"),
            checkboxes = document.getElementsByName("check-tab2");
        console.log("Val1 = " + checkboxes.length);
        for(var i = 0; i < checkboxes.length; i++)
            if(checkboxes[i].checked)
            {

                // remove the transfered rows from the second table [table2]
                var index = table2.rows[i+1].rowIndex;
                table2.deleteRow(index);
                // we have deleted some rows so the checkboxes.length have changed
                // so we have to decrement the value of i
                i--;
                console.log(checkboxes.length);
            }
    }


</script>
<div class="container">
    <div class="row">
        <div class="col-md-offset-4 col-md-4 text-center ">
            <form action="">
                <input  type="text" name="location" onkeyup="ShowHint(this.value)">
            </form>

    </div>
    <div id="ajaxtable" class="row">

    </div>
    <div style="margin-top: 30px;" class="tab text-center tab-btn">
        <button class="btn btn-lg btn-info" onclick="tab1_To_tab2();"><i class="fa fa-arrow-down" aria-hidden="true"></i> Overzetten <i class="fa fa-arrow-down" aria-hidden="true"></i></button>
    </div>
</div>
    <div class="container-fluid">
        <hr>
    </div>
<div class="container" id="table_wrapper">
    <table id="table2" class="table table-striped table-hover table-responsive">
        <thead class="thead-inverse">
        <tr>
            <td><b>City</b></td>
            <td><b>Longitude</b></td>
            <td><b>Latitude</b></td>
            <td><b>Verwijderen?</b></td>
            </tr>
        </thead>

        </table>


</div>
<div class="container">
    <div class="row text-center">
        <div class="col-md-4 col-md-offset-4">
        {{--<a href="#" id="test" class="btn btn-lg btn-default" onclick="exportTable();">Download bestand</a>--}}
            <button id="btnExport" class="btn btn-lg btn-success">Download bestand <span class="glyphicon glyphicon-cloud-download"></span> </button>
        </div>

    <div class="col-md-4 text-right ">
        <button  class="btn btn-lg btn-danger" onclick="tab2_To_tab1();">Verwijderen <span class="glyphicon glyphicon-trash"></span> </button>

    </div>
    </div>
</div>







@endsection
