@extends('layouts.app')



@section('content')

    <div class="page-title">
        <div class="title_left">
            <h3>Rechten beheren</h3>
        </div>
    </div>
    <div class="clearfix"></div>
    @admin


    <div class="x_panel">

        <div class="x_title">
            <h2><small>Admins</small></h2>

            <div class="clearfix"></div>
        </div>

        <div class="x_content">
            <ul>
                @foreach($admins as $admin)

                    <li>{{ $admin->name }}</li>

                @endforeach
            </ul>
        </div>
    </div>


<div class="row">
    <div class="col-md-6">
        <div class="x_panel">

            <div class="x_title">
                <h2><small>Managers</small></h2>

                <div class="clearfix"></div>
            </div>

            <div class="x_content">
                <ul>
                    @foreach($managers as $manager)

                        <li>{{ $manager->name }}</li>

                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="x_panel">

            <div class="x_title">
                <h2><small>Geen rechten</small></h2>

                <div class="clearfix"></div>
            </div>

            <div class="x_content">
                <ul>
                    @foreach($guests as $guest)

                        <li>{{ $guest->name }}</li>

                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>







        <div class="row">
            <div class="col-md-6 col-md-offset-3">



                <div class="x_panel">

                    <div class="x_title">
                        <h2><small>Rechten wijzigen</small></h2>

                        <div class="clearfix"></div>
                    </div>

                    <div class="x_content">
                        <form action="rechten" method="post">
                            {{csrf_field()}}


                            <select name="user" class="btn-block btn-lg btn-warning custom-select">
                                <option selected>Kies een User</option>

                                @foreach($noadmins as $noadmin)
                                    <option value="{{ $noadmin->id }}">{{ $noadmin->name }}</option>

                                @endforeach
                            </select>


                            <select  name="rights" class="btn-block btn-info btn-lg custom-select">
                                <option value="0" selected>Geen rechten</option>
                                <option value="2" >Manager</option>

                            </select>

                            <br>
                            <button type="submit" class="btn btn-lg btn-success">Aanpassen</button>

                        </form>
                    </div>
                </div>






            </div>
        </div>


    @endadmin


    @endsection