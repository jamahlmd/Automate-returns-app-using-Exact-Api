<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Retourivit</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

</head>
<style>
    footer{
        margin: 0;
    }
</style>
<body>
<div id="app">
    <nav class="my-navbar navbar " role="navigation" >
        <div class="this-navbar container-fluid" style="color:#004d4d">
		

		<a>Dorivit</a>

            <div class="navbar-header navbar-right" style="background-color:transparent;">
                <ul class="nav navbar-nav">
                    @guest
                    <li><a href="{{ route('login') }}">Login</a></li>
                    <li><a href="{{ route('register') }}">Register</a></li>
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                @admin
                                <li>
                                    <a href="rechten">
                                        Rechten beheren
                                    </a>
                                </li>
                                @endadmin
                                @both
                                <li>
                                    <a href="import">
                                        Importeren Agents
                                    </a>
                                </li>
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
                                @endboth
                                <li>
                                    <a href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>
                            </ul>
                        </li>
                        @endguest
                </ul>
            </div>

        </div>

    </nav>
    @if($flash = session('succes'))
        <div class="alert alert-success">
            {{$flash}}
        </div>
    @endif
    @if($flash = session('danger'))
        <div class="alert alert-danger">
            {{$flash}}
        </div>
    @endif


    @if(count($errors) )
        <div class="alert alert-danger">
            <ul>

                @foreach($errors->all() as $error)

                    <li>
                        {{$error}}
                    </li>
                @endforeach
            </ul>
        </div>
    @endif



    @yield('content')
</div>
<footer style="width:100%;">
    &copy; Created by Jamahl Mac-Donald & Ahasan Rajaratnam 2017-2018  <strong>Retourivit</strong>
</footer>
<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/theme.js') }}"></script>

@yield('scripts')
{{--<script src="https://use.fontawesome.com/383e979c07.js"></script>--}}
</body>
</html>
