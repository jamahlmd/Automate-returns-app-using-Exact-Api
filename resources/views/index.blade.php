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

                    <a href="export" class="btn btn-lg btn-success">Download bestand</a>
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
                    <div class="x_panel">
                        <div class="x_title">
                            <h2><small>Verwerkte retouren</small></h2>

                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <ul>
                                @if(isset($results))

                                    @foreach($results as $row)
                                        <a href="record/{{$row->invoice_id}}">
                                            <div class="well well-sm">
                                                <div class="container-fluid">
                                                    <div class="col-md-2">
                                                        <b>Naam: </b>{{$row->customer_name}}
                                                    </div>
                                                    <div class="col-md-2">
                                                        <b>Factuur: </b>{{$row->invoice_id}}
                                                    </div>
                                                    <div class="col-md-2">
                                                        <b>Product: </b>{{$row->invoice_name}}
                                                    </div>
                                                    <div class="col-md-2">
                                                        <b>Reden: </b>{{$row->reason}}
                                                    </div>
                                                    <div class="col-md-2">
                                                        <b>gecrediteerd: </b>{{$row->credit_amount}}
                                                    </div>
                                                    <div class="col-md-2">
                                                        <b>Comment: </b>{{$row->comment}}
                                                    </div>
                                                </div>
                                            </div>
                                        </a>

                                    @endforeach

                                @endif
                            </ul>
                        </div>
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



