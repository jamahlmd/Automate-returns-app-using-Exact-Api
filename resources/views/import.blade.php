@extends('layouts.app')


@section('content')

    <div class="page-title">
        <div class="title_left">
            <h3>Importeren agents</h3>
        </div>
    </div>
    <div class="clearfix"></div>

    <div class="x_panel">
        <div class="container">
            <div class="row">

            </div>
            <form style="border: 4px solid #a1a1a1;margin-top: 15px;padding: 10px;" action="{{ URL::to('import') }}" class="form-horizontal" method="post" enctype="multipart/form-data">

                {{csrf_field()}}

                <input type="file" name="import_file" />
                <button class="btn btn-primary">Import File</button>
            </form>
        </div>
    </div>



    @endsection
