
@extends('layouts.app')



@section('content')


    @both


    <div class="page-title">
        <div class="title_left">
            <h3>Statistieken</h3>
        </div>
    </div>
    <div class="clearfix"></div>


    <div class="row">
        <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2><small>Aantal retouren</small></h2>

                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <canvas id="retouren" width="400" height="200"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2><small>Gecrediteerd</small></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <canvas id="gecrediteerd" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>


    @else

        <div class="container text-center">
            <h1 class="display-2">Uw Account heeft geen rechten.</h1>

        </div>

        @endboth




@endsection


@section('scripts')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.min.js"></script>

    <script>

        var ctxO = document.getElementById("retouren");
        var ctx1 = document.getElementById("gecrediteerd");

        var month = new Array();
        month[0] = "Januari";
        month[1] = "Februari";
        month[2] = "Maart";
        month[3] = "April";
        month[4] = "Mei";
        month[5] = "Juni";
        month[6] = "Juli";
        month[7] = "Augustus";
        month[8] = "September";
        month[9] = "Oktober";
        month[10] = "November";
        month[11] = "December";


        var date = new Date;


        function checkDate(number){

            var maand = date.getMonth() - number;

            switch (maand){

                case -1:
                    return month[11];
                    break;
                case -2:
                    return month[10];
                    break;
                case -3:
                    return month[9];
                    break;
                case -4:
                    return month[8];
                    break;
                case -5:
                    return month[7];
                    break;
                case -6:
                    return month[6];
                    break;
                case -7:
                    return month[5];
                    break;
                case -8:
                    return month[4];
                    break;
                case -9:
                    return month[3];
                    break;
                case -10:
                    return month[2];
                    break;
                case -11:
                    return month[1];
                    break;
                case -12:
                    return month[0];
                    break;

                default:
                    return month[maand];
            }

        }

        var Dezemaand = checkDate(0);
        var Maandterug1 = checkDate(1);
        var Maandterug2 = checkDate(2);
        var Maandterug3 = checkDate(3);
        var Maandterug4 = checkDate(4);
        var Maandterug5 = checkDate(5);
        var Maandterug6 = checkDate(6);
        var Maandterug7 = checkDate(7);
        var Maandterug8 = checkDate(8);
        var Maandterug9 = checkDate(9);
        var Maandterug10 = checkDate(10);
        var Maandterug11 = checkDate(11);

        //Retouren
        data = {
            labels: [Maandterug11, Maandterug10, Maandterug9, Maandterug8, Maandterug7, Maandterug6,Maandterug5,Maandterug4, Maandterug3, Maandterug2, Maandterug1, Dezemaand],
            datasets: [{
                data: [<?php echo $maandVan11;?>,<?php echo $maandVan10;?>,<?php echo $maandVan9;?>,<?php echo $maandVan8;?>,<?php echo $maandVan7;?>,<?php echo $maandVan6;?>,<?php echo $maandVan5;?>,<?php echo $maandVan4;?>,<?php echo $maandVan3;?>,<?php echo $maandVan2;?>,<?php echo $maandVan1;?>,<?php echo $maandVan0;?>],
                label: 'Aantal retouren',
                borderColor: "#cd686f",
                fill: false
            }]
        };

        var stackedLine = new Chart(ctxO, {
            type: 'line',
            data: data,
            options: {
                scales: {
                    yAxes: [{
                        stacked: true,
                        scaleLabel:{
                            display: true,
                            labelString: 'Aantal'
                        }
                    }]
                }
            }
        });

        //Gecrediteerd
        data = {
            labels: [Maandterug11, Maandterug10, Maandterug9, Maandterug8, Maandterug7, Maandterug6,Maandterug5,Maandterug4, Maandterug3, Maandterug2, Maandterug1, Dezemaand],
            datasets: [{
                data: [<?php echo $tariefVan11;?>,<?php echo $tariefVan10;?>,<?php echo $tariefVan9;?>,<?php echo $tariefVan8;?>,<?php echo $tariefVan7;?>,<?php echo $tariefVan6;?>,<?php echo $tariefVan5;?>,<?php echo $tariefVan4;?>,<?php echo $tariefVan3;?>,<?php echo $tariefVan2;?>,<?php echo $tariefVan1;?>,<?php echo $tariefVan0;?>],
                label: 'Gecrediteerd',
                borderColor: "#00BFFF",
                fill: false
            }]
        };

        var stackedLine2 = new Chart(ctx1, {
            type: 'line',
            data: data,
            options: {
                scales: {
                    yAxes: [{
                        stacked: true,
                        scaleLabel:{
                            display: true,
                            labelString: 'Euro\'s'
                        }
                    }]
                }
            }
        });




    </script>


@endsection