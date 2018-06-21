@extends('layouts.app')



@section('content')

    <form action="{{url('/retourgegevensdownload')}}" method="post">

        {{ csrf_field() }}

        <div class="form-group">
            <label for="exampleInputEmail1">Kies een datum</label>
            <input readonly type="text" name="date" class="form-control datepicker" placeholder="..." id="some-id">
            <small id="emailHelp" class="form-text text-muted">Als u op Download klikt worden alle retourgegevens van de desbtreffende datum gedownload.</small>
        </div>

    <button type="submit" class="btn btn-block btn-info">Download</button>
    </form>

@endsection


@section('scripts')

    <script src="https://unpkg.com/js-datepicker"></script>
    <link rel="stylesheet" href="https://unpkg.com/js-datepicker/datepicker.css">

    <script>
        const picker = datepicker('#some-id', {
            formatter: function(el, date) {
                // This will display the date as `1/1/2017`.
                el.value = date.toLocaleDateString();

                console.log(el.value);
            }
        });


    </script>

    @endsection