@extends('layouts.app')


@section('content')



    <div class="page-title">
        <div class="title_left">
            <h3>Account gegevens</h3>
        </div>
    </div>
    <div class="clearfix"></div>



    <div class="row">
            <div class="x_panel">
                <div class="x_title">
                    <h2><small>Gebruiker</small></h2>

                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="well">
                        <b>Naam</b> : {{$user->name}}
                    </div>
                    <div class="well">
                        <b>E-mail</b> : {{$user->email}}
                    </div>
                </div>
            </div>
    </div>


    <div class="row">
        <div class="x_panel">
            <div class="x_title">
                <h2><small>Verwerkte retouren</small></h2>

                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <ul>
                    @foreach ($records as $rec)

                            <div class="well well-sm">
                                <div class="container-fluid">
                                    <div class="col-md-4">
                                        <b>Naam: </b>{{$rec->customer_name}}
                                    </div>
                                    <div class="col-md-4">
                                        <b>Factuur: </b>{{$rec->invoice_id}}
                                    </div>
                                    <div class="col-md-4">
                                        <b>Verwerkt op: </b>{{$rec->arrival_date}}
                                    </div>
                                </div>
                            </div>


                    @endforeach

                    {{ $records->render() }}

                </ul>
            </div>
        </div>
    </div>
@endsection


@section('scripts')


    <script>
        $(window).on('hashchange', function() {
            if (window.location.hash) {
                var page = window.location.hash.replace('#', '');
                if (page == Number.NaN || page <= 0) {
                    return false;
                } else {
                    getPosts(page);
                }
            }
        });
        $(document).ready(function() {
            $(document).on('click', '.pagination a', function (e) {
                getPosts($(this).attr('href').split('page=')[1]);
                e.preventDefault();
            });
        });
        function getPosts(page) {
        .fetch({
                url : '?page=' + page,
                dataType: 'json',
            }).done(function (data) {
                $('.posts').html(data);
                location.hash = page;
            }).fail(function () {
                alert('Posts could not be loaded.');
            });
        }
    </script>

@endsection
