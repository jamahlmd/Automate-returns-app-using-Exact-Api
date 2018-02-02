@extends('layouts.app')



@section('content')

@both

<div class="page-title">
    <div class="title_left">
        <h3>Menu</h3>
    </div>
</div>



<div class="x_panel">

    <div class="x_content">
        <ul>
            <li>
                <a href="retouren">
                    Retouren
                </a>
            </li>
            <li>
                <a href="statistiek">
                    Statistieken
                </a>
            </li>
            <li>
                <a href="import">
                    Importeren Agents
                </a>
            </li>
            <li>
                <a href="account">
                    Account gegevens
                </a>
            </li>
            @admin
            <li>
                <a href="rechten">
                    Rechten beheren
                </a>
            </li>
            @endadmin
        </ul>
    </div>
</div>




    @else

        <div class="container text-center">
            <h1 class="display-2">Uw Account heeft geen rechten</h1>

        </div>

        @endboth


@endsection
