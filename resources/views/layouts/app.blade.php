<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Retourivit</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <style>
        #loader {
            transition: all 0.3s ease-in-out;
            opacity: 1;
            visibility: visible;
            position: fixed;
            height: 100vh;
            width: 100%;
            background: #fff;
            z-index: 90000;
        }
        #loader.fadeOut {
            opacity: 0;
            visibility: hidden;
        }
        .spinner {
            width: 40px;
            height: 40px;
            position: absolute;
            top: calc(50% - 20px);
            left: calc(50% - 20px);
            background-color: #333;
            border-radius: 100%;
            -webkit-animation: sk-scaleout 1.0s infinite ease-in-out;
            animation: sk-scaleout 1.0s infinite ease-in-out;
        }
        @-webkit-keyframes sk-scaleout {
            0% { -webkit-transform: scale(0) }
            100% {
                -webkit-transform: scale(1.0);
                opacity: 0;
            }
        }
        @keyframes sk-scaleout {
            0% {
                -webkit-transform: scale(0);
                transform: scale(0);
            } 100% {
                  -webkit-transform: scale(1.0);
                  transform: scale(1.0);
                  opacity: 0;
              }
        }
        body{
            background-color: #2A3F54;
        }
        footer{
            background-color: #fff;
        }
    </style>

</head>

<body class="nav-md app">

<!-- @TOC -->
<!-- =================================================== -->
<!--
      + @Page Loader
      + @App Content
          - #Left Sidebar
              > $Sidebar Header
              > $Sidebar Menu
          - #Main
              > $Topbar
              > $App Screen Content
    -->

<!-- @Page Loader -->
<!-- =================================================== -->
<div id='loader'>
    <div class="spinner"></div>
</div>

<script type="text/javascript">
    window.addEventListener('load', () => {
        const loader = document.getElementById('loader');
    setTimeout(() => {
        loader.classList.add('fadeOut');
    }, 300);
    });
</script>
<div class="container body">
    <div class="main_container">
        <div class="col-md-3 left_col">
            <div class="left_col scroll-view">
                <div class="navbar nav_title" style="border: 0;">
                    <a href="{{ url('menu') }}" class="site_title"><i class="fa fa-truck" aria-hidden="true"></i><span> Retourivit</span></a>
                </div>

                <div class="clearfix"></div>

                <!-- menu profile quick info -->
                <div class="profile clearfix">

                    <div class="profile_info">
                        <span>Welcome,</span>
                        <h2>
						@if(Auth::check())
							
							{{ Auth::user()->name }}
							
							@endif
						</h2>
                    </div>
                </div>
                <!-- /menu profile quick info -->

                <br />

                <!-- sidebar menu -->
                <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                    <div class="menu_section">
                        <h3>General</h3>
                        <ul class="nav side-menu">
                            <li><a><i class="fa fa-home"></i> Home <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="{{ url('menu') }}">Menu</a></li>
                                    <li><a href="{{ url('account') }}">Account</a></li>
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
                            <li><a><i class="fa fa-edit"></i> Retouren <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                
                                    <li><a href="{{ url('retouren') }}">Retouren verwerken</a></li>
                                    <li><a href="{{ url('retouren') }}">Verwerkings geschiedenis</a></li>

                                </ul>
                            </li>
                            <li><a><i class="fa fa-desktop"></i> Importeren/Exporteren <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="{{ url('import') }}">Agents Importeren</a></li>
                                    <li><a href="{{ url('/retourgegevensdownload') }}">Retour gegevens exporteren</a></li>
                                </ul>
                            </li>
                            <li><a><i class="fa fa-bar-chart-o"></i> Statistieken <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="{{ url('statistiek') }}">Statistieken dashboard</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>

                </div>
                <!-- /sidebar menu -->

                <!-- /menu footer buttons -->
                {{--<div class="sidebar-footer hidden-small">--}}
                    {{--<a data-toggle="tooltip" data-placement="top" title="Settings">--}}
                        {{--<span class="glyphicon glyphicon-cog" aria-hidden="true"></span>--}}
                    {{--</a>--}}
                    {{--<a data-toggle="tooltip" data-placement="top" title="FullScreen">--}}
                        {{--<span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>--}}
                    {{--</a>--}}
                    {{--<a data-toggle="tooltip" data-placement="top" title="Lock">--}}
                        {{--<span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>--}}
                    {{--</a>--}}
                    {{--<a data-toggle="tooltip" data-placement="top" title="Logout" href="login.html">--}}
                        {{--<span class="glyphicon glyphicon-off" aria-hidden="true"></span>--}}
                    {{--</a>--}}
                {{--</div>--}}
                <!-- /menu footer buttons -->
            </div>
        </div>

        <!-- top navigation -->
        <div class="top_nav">
            <div class="nav_menu">
                <nav>
                    <div class="nav toggle">
                        <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                    </div>

                    <ul class="nav navbar-nav navbar-right">
                        <li class="">
                            <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
						@if(Auth::check())
							
							{{ Auth::user()->name }}
							
							@endif
                                <span class=" fa fa-angle-down"></span>
                            </a>
                            <ul class="dropdown-menu dropdown-usermenu pull-right">
                                @guest
                                <li><a href="{{ route('login') }}">Login</a></li>
                                <li><a href="{{ route('register') }}">Register</a></li>
                                @else
                                    @admin
                                    <li>
                                        <a href="{{ url('rechten') }}">
                                            Rechten beheren
                                        </a>
                                    </li>
                                    @endadmin
                                    @both
                                    <li>
                                        <a href="{{ url('import') }}">
                                            Importeren Agents
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ url('retouren') }}">
                                            Retouren
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ url('statistiek') }}">
                                            Statistieken
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ url('account') }}">
                                            Account gegevens
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
                                    @endguest

                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
        <!-- /top navigation -->
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
        <!-- page content -->
        <div class="right_col" role="main">
            <!-- top tiles -->
            @yield('content')
        </div>
        <!-- /page content -->

        <!-- footer content -->
        <footer class="footer_fixed">
            &copy; Created by Jamahl Mac-Donald & Ahasan Rajaratnam 2017-2018  <strong>Retourivit</strong>
        </footer>
        <!-- /footer content -->
    </div>
</div>
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/theme.js') }}"></script>
<script src="https://use.fontawesome.com/383e979c07.js"></script>

@yield('scripts')

</body>
</html>
