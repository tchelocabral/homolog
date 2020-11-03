<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>FULLFREELA :: O Ponto dos Jobs</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
                margin-bottom: -10px;
            }
            .subtitle{
                color: #636b6f;
                margin-bottom: 80px;
                font-weight: bold;
            }
            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .position-ref {
                background: url('images/bg02.jpg') center center no-repeat;
                background-size: cover;
            }

            #verde-escuro {
                color: #1a797b;
            }
        </style>

    </head>

    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/app') }}">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>
                        {{--<a href="{{ route('registrar.vendedor') }}">Quero Oferecer um Job</a>--}}
                        {{--<a href="{{ route('registrar.freela') }}">Quero Fazer um Job</a>--}}
                    @endauth
                </div>
            @endif

            <div class="content">
                <div class="title">
                    Full<b id="verde-escuro">Freela</b>
                </div>
                <p class="subtitle">O Ponto dos Jobs</p>
                <div class="links">
                    @auth
                        <a href="{{ url('/app') }}">{{trans('Acessar meu')}} Dashboard</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>
                        <a href="#como">{{trans('Como Funciona')}}</a>
                        <a href="#contato">{{trans('Contato')}}</a>
                        <a href="#parceiros">{{trans('Parceiros')}}</a>
                        <a href="#acoes">{{trans('Ações Sociais')}}</a>
                    @endauth
                </div>
            </div>
        </div>
    </body>
</html>
