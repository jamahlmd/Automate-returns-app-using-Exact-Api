@extends('layouts.app')



@section('content')


    @both

    <div class="container-fluid">
        <div class="page-title">
            <div class="title_left">
                <h3>{{$name}} <small>Retour gegevens</small></h3>
            </div>
        </div>

        <div class="clearfix"></div>
        <div class="row">
            <div class="col-md-4 col-sm-4 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2><small>Klant gegevens</small></h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <table class="table table-striped">
                            <tbody>
                            <tr>
                                <td>Naam</td>
                                <td>-</td>
                                <td>{{$name}}</td>
                            </tr>
                            <tr>
                                <td>Klant nr.</td>
                                <td>-</td>
                                <td>{{$klantnr}}</td>
                            </tr>
                            <tr>
                                <td>E-mail</td>
                                <td>-</td>
                                <td>{{$email}}</td>
                            </tr>
                            <tr>
                                <td>Landcode</td>
                                <td>-</td>
                                <td>{{$countryname}}</td>
                            </tr>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-4 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2><small>Factuur gegevens</small></h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <table class="table table-striped">
                            <tbody>
                            <tr>
                                <td>Factuur nr.</td>
                                <td>-</td>
                                <td>{{$invoicenumber}}</td>
                            </tr>
                            <tr>
                                <td>Totaal bedrag</td>
                                <td>-</td>
                                <td>&#8364; {{$amountfc}}</td>
                            </tr>
                            <tr>
                                <td>Factuur datum</td>
                                <td>-</td>
                                <td><?php
                                    $newDate = new DateTime($invoicedate);

                                    ?>

                                    {{$newDate->format('d-m-Y')}}
                                </td>
                            </tr>
                            <tr class="
                                             @if($interval > 35)
                                    red
                                  @endif
                                    ">
                                <td>Datum verschil</td>
                                <td>-</td>
                                <td>
                                    {{$interval}} dagen geleden
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Product</th>
                                <th>Aantal</th>
                                <th>Prijs</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($retour as $record)

                                <tr>
                                    <td>{{$record->invoice_name}}</td>
                                    <td>{{$record->invoice_quantity}}</td>
                                    <td>{{$record->invoice_price}}</td>
                                </tr>

                            @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-4 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2><small>Agent gegevens</small></h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <table class="table table-striped">
                            <tbody>
                            <tr>
                                <td>Naam</td>
                                <td>-</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>nlcall-ID</td>
                                <td>-</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Agent ID</td>
                                <td>-</td>
                                <td></td>
                            </tr>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>

        <div class="page-title">
            <div class="title_left">
                <h3>Geretourneerde producten</h3>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <form method="post" action="{{ url('export') }}" id="myform2">
                        {{csrf_field()}}

                        <?php
                        $ids =[];
                        ?>


                        @foreach($retour as $record)

                            <?php
                            $id = $record->id;
                            $ids[] = $id;
                            ?>

                        <div class="x_content" style="overflow-x: auto;">
                                <table class="table table-striped">
                                    <tbody>

                                    <div class="x_title">
                                        <h2><small>{{$record->invoice_name}} - &#8364; {{$record->invoice_price}}</small></h2>
                                        <div class="clearfix"></div>
                                    </div>
                                    <tr>
                                        <input type='hidden' name='totaalaantal{{ $id }}' id='totaalaantal{{ $id }}' value='{{ $total_orderamount }}'>
                                        <input type='hidden' name='totaalprijs{{ $id }}' id='totaalprijs{{ $id }}' value='{{ $amountfc }}'>
                                        <input type='hidden' name='prijs{{ $id }}' id='prijs{{ $id }}' value='{{ $record->invoice_price }}'>
                                        <td>
                                            <label for="aantalterug{{ $id }}">Aantal terug</label>
                                            <div class="select-style">
                                                <select class="aantalterug" name='aantalterug{{ $id }}' id='aantalterug{{ $id }}'>
                                                    @for ($i = 0; $i <= $total_orderamount; $i++)
                                                        @if($i === $record->invoice_quantity)
                                                            <option value="{{$record->product_quantity}}" selected>{{$record->product_quantity}}</option>
                                                        @endif
                                                        <option value="{{$i}}">{{$i}}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </td>
                                        <td class="aantalopeninput{{ $id }}">
                                            <label for="aantalopen{{ $id }}">Aantal open</label>
                                            <div class="select-style">
                                                <select class="aantalopen" name='aantalopen{{ $id }}' id='aantalopen{{ $id }}'>
                                                    @for ($i = 0; $i <= $total_orderamount; $i++)
                                                        @if($i === $record->open_products)
                                                            <option value="{{$record->open_products}}" selected>{{$record->open_products}}</option>
                                                        @endif
                                                        <option value="{{$i}}">{{$i}}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <label for="total{{ $id }}">Te crediteren</label>
                                            <div class="select-style2">
                                                @if(isset($record->credit_amount))<input class="total" type="text" value='{{$record->credit_amount}}' name='total{{ $id }}' id='total{{ $id }}'/>@else
                                                <input class="total" type="text" value='0' name='total{{ $id }}' id='total{{ $id }}'/>@endif

                                            </div>
                                        </td>

                                        <td>
                                            <label for="reden{{ $id }}">Reden</label>
                                            <div class="select-style">
                                                <select class="aantalopen" onchange="showfield(this.options[this.selectedIndex].value)" name="reden{{ $id }}">
                                                    <option value="{{$record->reason}}" selected>{{$record->reason}}</option>
                                                    <option value="Geen reden">Geen reden</option>
                                                    <option value="Niet tevreden">Niet tevreden</option>
                                                    <option value="Geweigerd">Geweigerd</option>
                                                    <option value="Te laat geleverd">Te laat geleverd</option>
                                                    <option value="Advies dokter">Advies dokter</option>
                                                    <option value="Inpakfout">Inpakfout</option>
                                                    <option value="Te duur">Te duur</option>
                                                    <option value="Persoon onbekend">Persoon onbekend</option>
                                                    <option value="Niet afgehaald">Niet afgehaald</option>
                                                    <option value="Adres klopt niet">Adres klopt niet</option>
                                                    <option value="Verkeerd huisnr.">Verkeerd huisnr.</option>
                                                    <option value="Niet besteld">Niet besteld</option>
                                                    <option value="Verkeerd aantal/product">Verkeerd aantal/product</option>
                                                    <option value="Allergisch">Allergisch</option>
                                                    <option value="Niet thuis">Niet thuis</option>
                                                    <option value="Andere reden:">Andere reden</option>
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <label for="opmerking{{ $id }}">Opmerking</label>
                                            <div class="select-style2">
                                                <input class="total" type="text" value="{{$record->comment}}" name="opmerking{{ $id }}">
                                            </div>
                                        </td>
                                        <td>
                                            <label for="gecrediteerd{{ $id }}">crediteren?</label>
                                            <div class="select-style">
                                                <select class="aantalopen" name='gecrediteerd{{ $id }}'>
                                                    <option selected value="{{$record->if_credited}}">{{$record->if_credited}}</option>
                                                    <option value="Wel">Wel</option>
                                                    <option value="Niet">Niet</option>
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <label for="bezorger{{ $id }}">Bezorger</label>
                                            <div class="select-style">
                                                <select class="aantalopen" name='bezorger{{ $id }}'>
                                                    <option value="{{$record->carrier}}" selected>{{$record->carrier}}</option>
                                                    <option value="PostNL">PostNL</option>
                                                    <option value="UPS">UPS</option>
                                                    <option value="Postbode">Postbode</option>
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <label for="contact{{ $id }}">Verkoper</label>
                                            <div class="select-style">
                                                <select class="aantalopen" name='contact{{ $id }}'>
                                                    <option @if( $record->contact == 'Bol.com')selected @endif value="Bol.com">Bol.com</option>
                                                    <option @if( $record->contact == 'Amazon')selected @endif value="Amazon">Amazon</option>
                                                    <option @if( $record->contact == 'Dorivit')selected @endif value="Dorivit">Dorivit</option>
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <label for="claim{{ $id }}">Claim</label>
                                            <div class="select-style">
                                                <select class="claim" name='claim{{ $id }}'>
                                                    <option value="{{$record->claim}}">{{$record->claim}}</option>
                                                    <option value="0">0</option>
                                                    <option value="1">1</option>
                                                </select>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        @endforeach
                        <input type='hidden' name='ids' value='{{urlencode(serialize($ids))}}'>

                    </form>
                    <button class="btn btn-lg btn-block btn-success" name="product" value="db" form="myform2">Verwerk retour</button>

                </div>
            </div>
        </div>
    </div>

    @endboth
@endsection


