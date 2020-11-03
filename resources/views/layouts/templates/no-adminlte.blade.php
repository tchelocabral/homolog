<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'FullFreela') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <link rel="stylesheet" href="{{ asset('css/criacaocia.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/no-adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/alturas-larguras.css') }}">
    <link rel="stylesheet" href="{{ asset('css/alturas-larguras-responsivo.css') }}">
    <link rel="stylesheet" href="{{ asset('css/flexDisplays.css') }}">
    <link rel="stylesheet" href="{{ asset('css/textos.css') }}">
    <link rel="stylesheet" href="{{ asset('css/cores.css') }}">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body>

    <div id="app">

        <nav class="navbar navbar-expand-md navbar-light navbar-laravel fundo-escuro">
            <div class="container">
                <a class="navbar-brand texto-branco" href="{{ url('/') }}">
                    {{ config('app.name', 'FullFreela') }}
                </a>
                <button class="navbar-toggler texto-branco" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon texto-branco"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link texto-branco" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link texto-branco " href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    
                                    <a class="dropdown-item texto-preto" href="{{ route('home') }}">{{ __('messages.Home') }}</a>
                                    <hr>
                                    <a class="dropdown-item texto-preto" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('messages.Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>


        <nav class="navbar fundo-escuro" id="nav-rodape">
            <div class="container displayFlex flexCentralizado">
                <a class="brand texto-branco" href="#"><i>fullfreela@2020</i></a>
            </div>
        </nav>

    </div>

</body>
</html>
