/**
 * Created by Jams on 12/10/2017.
 */



$( ".aantalterug" ).change(function() {

    var qtyid = $(this).attr("id");

    qtyid = qtyid.replace("aantalterug", "");


        var aantalterug = parseInt($('#aantalterug'+qtyid).val());
        var aantalopen = parseInt($('#aantalopen'+qtyid).val());

        aantalterug = aantalterug - aantalopen;

        var totaalaantal = $('#totaalaantal'+qtyid).val();
        var totaalprijs = $('#totaalprijs'+qtyid).val();
        var prijs = $('#prijs'+qtyid).val();


        var ToCredit = aantalterug * prijs;

        var retour = $('#total'+qtyid).val((ToCredit).toFixed(2));


});

$( ".aantalopen" ).change(function() {

    var qtyid = $(this).attr("id");

    qtyid = qtyid.replace("aantalopen", "");


    var aantalterug = parseInt($('#aantalterug'+qtyid).val());
    var aantalopen = parseInt($('#aantalopen'+qtyid).val());

    aantalterug = aantalterug - aantalopen;

    var totaalaantal = $('#totaalaantal'+qtyid).val();
    var totaalprijs = $('#totaalprijs'+qtyid).val();
    var prijs = $('#prijs'+qtyid).val();

    var ToCredit = aantalterug * prijs;

    var retour = $('#total'+qtyid).val((ToCredit).toFixed(2));

});

function Aantalterug(arg) {

    var qtyid = arg.getAttribute('id');

         qtyid = qtyid.replace("aantalterug", "");

    // $('#aantalterug'+qtyid).on('input',function() {
    //     var qty = parseInt($('#aantalterug'+qtyid).val());
    //     var price = parseFloat($('#aantalopen'+qtyid).val());
    //     var retour = $('#total'+qtyid).val((qty * price ? qty * price : 0).toFixed(2));
    // });
    //
    // $('#aantalopen'+qtyid).on('input',function() {
    //     var qty = parseInt($('#aantalterug'+qtyid).val());
    //     var price = parseFloat($('#aantalopen'+qtyid).val());
    //     var retour = $('#total'+qtyid).val((qty * price ? qty * price : 0).toFixed(2));
    // });
    //
    // var vall = $('#totaalaantal'+qtyid).val();


    $('#aantalterug'+qtyid).on('input',function() {
        var aantalterug = parseInt($('#aantalterug'+qtyid).val());
        var aantalopen = parseInt($('#aantalopen'+qtyid).val());

         aantalterug = aantalterug - aantalopen;

        var totaalaantal = $('#totaalaantal'+qtyid).val();
        var totaalprijs = $('#totaalprijs'+qtyid).val();
        var prijs = $('#prijs'+qtyid).val();


        var ToCredit = aantalterug * prijs;


        var retour = $('#total'+qtyid).val((ToCredit).toFixed(2));


    });

    $('#aantalopen'+qtyid).on('input',function() {
        var aantalterug = parseInt($('#aantalterug'+qtyid).val());
        var aantalopen = parseInt($('#aantalopen'+qtyid).val());

        aantalterug = aantalterug - aantalopen;

        var totaalaantal = $('#totaalaantal'+qtyid).val();
        var totaalprijs = $('#totaalprijs'+qtyid).val();
        var prijs = $('#prijs'+qtyid).val();


        var ToCredit = aantalterug * prijs;


        var retour = $('#total'+qtyid).val((ToCredit).toFixed(2));
    });





}

function AantalOpen(arg) {

    var qtyid = arg.getAttribute('id');

        qtyid = qtyid.replace("aantalopen", "");

    $('#aantalterug'+qtyid).on('input',function() {
        var aantalterug = parseInt($('#aantalterug'+qtyid).val());
        var aantalopen = parseInt($('#aantalopen'+qtyid).val());

        aantalterug = aantalterug - aantalopen;

        var totaalaantal = $('#totaalaantal'+qtyid).val();
        var totaalprijs = $('#totaalprijs'+qtyid).val();
        var prijs = $('#prijs'+qtyid).val();

        var ToCredit = aantalterug * prijs;


        var retour = $('#total'+qtyid).val((ToCredit).toFixed(2));


    });

    $('#aantalopen'+qtyid).on('input',function() {
        var aantalterug = parseInt($('#aantalterug'+qtyid).val());
        var aantalopen = parseInt($('#aantalopen'+qtyid).val());

        aantalterug = aantalterug - aantalopen;

        var totaalaantal = $('#totaalaantal'+qtyid).val();
        var totaalprijs = $('#totaalprijs'+qtyid).val();
        var prijs = $('#prijs'+qtyid).val();


        var ToCredit = aantalterug * prijs;


        var retour = $('#total'+qtyid).val((ToCredit).toFixed(2));
    });

}


// $( ".redtype" ).change(function() {
//     if (isNumber($('.redtype').val())){
//
//     } else {
//         var p = $('.redtype').css("background-color", "red");
//     }
// });


$("#basic-url").focus();



//KLIKKEN OP DE SEARCH ID BAR OM DE FORM TE REFRESHEN
// $('#basic-url').click( function() {
//
//     // document.getElementById("myform").submit();
//     $('#db').click();
//
//
//
// });


setInterval(ajaxCall, 300000); //300000 MS == 5 minutes

function ajaxCall() {
    var xhttp;
    xhttp = new XMLHttpRequest();

    xhttp.open("GET", "refresh", true);
    xhttp.send();
}

function SearchID(str) {

    var xhttp;
    if (str.length !== 8) {
        document.getElementById("ShowData").innerHTML = "";
        return;
    }
    else {
        xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("ShowData").innerHTML = this.responseText;
            }
        };
        // xhttp.open("GET", "gethint.php?location="+str, true);
        xhttp.open("GET", "searchID?id=" + str, true);
        xhttp.send();
    }

}
