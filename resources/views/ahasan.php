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
                                    <div class="select-style2">

                                        <input class="total" value="{{ $row->reason }}" type="text" name="reden{{ $row->id }}">

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



