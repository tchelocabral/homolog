<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    {{-- Page Title --}}
    <title>
        @yield('title_prefix', config('adminlte.title_prefix', ''))
        @yield('title', config('adminlte.title', 'AdminLTE 2'))
        @yield('title_postfix', config('adminlte.title_postfix', ''))
        {{strpos('fullfreela', URL::to('/'))}}
    </title>
    
    <!-- Google Tag Manager  APENAS PARA FULLFREELA -->
    @if(strpos('fullfreela', URL::to('/')) !== false)
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-KC9LRWJ');</script>
    @endif
    <!-- End Google Tag Manager -->
    

    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    
    
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset('css/efeito-tabs.css') }}">

    <link rel="stylesheet" href="{{ asset('vendor/adminlte/vendor/bootstrap/dist/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/vendor/font-awesome/css/font-awesome.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/vendor/Ionicons/css/ionicons.min.css') }}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/iCheck/square/blue.css') }}">
    <!-- jQuery Confirm -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.css">


    @if(config('adminlte.plugins.select2'))
        <!-- Select2 -->
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.css">
    @endif

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/AdminLTE.min.css') }}">

    <!-- CriaçãoCia -->
    <link rel="stylesheet" href="{{ asset('css/carregando.css') }}">
    <link rel="stylesheet" href="{{ asset('css/criacaocia.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/alturas-larguras.css') }}">
    <link rel="stylesheet" href="{{ asset('css/alturas-larguras-responsivo.css') }}">
    <link rel="stylesheet" href="{{ asset('css/flexDisplays.css') }}">
    <link rel="stylesheet" href="{{ asset('css/forms.css') }}">
    <link rel="stylesheet" href="{{ asset('css/textos.css') }}">
    <link rel="stylesheet" href="{{ asset('css/cores.css') }}">
    <link rel="stylesheet" href="{{ asset('css/cards.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tabelas.css') }}">
    <link rel="stylesheet" href="{{ asset('css/session_notifications.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pace.css') }}">

    @if(config('adminlte.plugins.datatables'))
        <!-- DataTables with bootstrap 3 style -->
        <link rel="stylesheet" href="//cdn.datatables.net/v/bs/dt-1.10.18/datatables.min.css">
    @endif


    @yield('adminlte_css')

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <!-- Fonts Relaway-->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">


</head>
<body class="hold-transition @yield('body_class')" style="{{ config('app.bkg') ? 'background:url(' . config('app.bkg') . ')' : '' }}">

    <!-- Google Tag Manager  APENAS PARA FULLFREELA -->
    @if(strpos('fullfreela', URL::to('')))
        <!-- Google Tag Manager (noscript) -->
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KC9LRWJ"
        height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    @endif
    <!-- End Google Tag Manager -->


@yield('body')

<script src="{{ asset('vendor/adminlte/vendor/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('vendor/adminlte/vendor/jquery/dist/jquery.slimscroll.min.js') }}"></script>
<script src="https://code.jquery.com/ui/1.11.3/jquery-ui.min.js" integrity="sha256-xI/qyl9vpwWFOXz7+x/9WkG5j/SVnSw21viy8fWwbeE=" crossorigin="anonymous"></script>
<script src="{{ asset('vendor/adminlte/vendor/bootstrap/dist/js/bootstrap.min.js') }}"></script>

@if(config('adminlte.plugins.select2'))
    <!-- Select2 -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
@endif

<script src="//cdn.jsdelivr.net/npm/pace-js@1.0.2/pace.min.js"></script>


@if(config('adminlte.plugins.chartjs'))
    <!-- ChartJS -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.bundle.min.js"></script>
@endif

{{-- Global Base and Storage javascript var--}}
<script>var url_base = '{{ URL::to('') }}';var url_base_storage = url_base + '/storage/'; </script>

{{-- Datepicker --}}
{{--<script src="{{ asset('vendor/adminlte/plugins/datepicker/bootstrap-datepicker.min.js') }}"></script>--}}

<!-- iCheck -->
<script src="{{ asset('vendor/adminlte/plugins/iCheck/icheck.min.js') }}"></script>

{{-- Maks --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>

{{--jQuery kNob--}}
<script src="{{ asset('js/jquery.knob.min.js') }}"></script>

{{-- Charts Criação --}}
<script src="{{ asset('js/charts.js') }}"></script>

{{-- JQuery Confirm --}}
<script src="{{ asset('https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.js') }}"></script>

<!-- chama JS DATATABLE - em pt-br -->
<script src="{{ asset('js/jquery.dataTables.js')}}"></script>

<script type="text/javascript" src="https://cdn.datatables.net/fixedheader/3.1.6/js/dataTables.fixedHeader.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.20/sorting/datetime-moment.js"></script>

<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap.min.js"></script>



{{-- Confirms Criação --}}
<script src="{{ asset('js/confirm.js') }}"></script>

{{-- Fullfreela JS --}}
<script src="{{ asset('js/fullfreela.js?v3.3') }}"></script>

@yield('adminlte_js')

<!-- @CriaçãoCia Session Notifications -->
@include('notifications/session_messages')

{{-- Notifications JS --}}
<script src="{{ asset('js/session-notifications.js?v3.3') }}"></script>



</body>
</html>
