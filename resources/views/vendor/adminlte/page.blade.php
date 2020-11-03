@extends('adminlte::master')

@section('adminlte_css')
    <link rel="stylesheet"
          href="{{ asset('vendor/adminlte/dist/css/skins/skin-' . config('adminlte.skin', 'blue') . '.min.css')}} ">
    @stack('css')
    @yield('css')
@stop

@section('body_class', 'skin-' . config('adminlte.skin', 'blue') . ' sidebar-mini ' . (config('adminlte.layout') ? [
    'boxed' => 'layout-boxed',
    'fixed' => 'fixed',
    'top-nav' => 'layout-top-nav'
][config('adminlte.layout')] : '') . (config('adminlte.collapse_sidebar') ? ' sidebar-collapse ' : ''))

@section('body')

    <div class="wrapper">

        <!-- Main Header -->
        <header class="main-header">
            @if(config('adminlte.layout') == 'top-nav')
            <nav class="navbar navbar-static-top">
                <div class="container">
                    <div class="navbar-header">
                        <a href="{{ url(config('adminlte.dashboard_url', 'home')) }}" class="navbar-brand">
                            {!! config('adminlte.logo', '<b>Admin</b>LTE') !!}
                        </a>
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                            <i class="fa fa-bars"></i>
                        </button>
                    </div>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
                        <ul class="nav navbar-nav">
                            @each('adminlte::partials.menu-item-top-nav', $adminlte->menu(), 'item')
                        </ul>
                    </div>
            @else
            <!-- Logo -->
            <a href="{{ url(config('adminlte.dashboard_url', 'home')) }}" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini">{!! config('adminlte.logo_mini', '<b>A</b>LT') !!}</span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg">{!! config('adminlte.logo', '<b>Admin</b>LTE') !!}</span>
            </a>

            <!-- Header Navbar -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                 
                    <span class="sr-only">{{ trans('adminlte::adminlte.toggle_navigation') }}</span>
                </a>
            @endif
                <!-- Navbar Right Menu -->
                @php 
                    $count_alert = Auth::user()->unreadNotifications('alert')->count();  $has = $count_alert > 0 ? 'has' : ''; 
                    $count_payment = Auth::user()->unreadNotifications('payment')->count();  $has_pay = $count_payment > 0 ? 'has' : '';
                    $count_comment = Auth::user()->unreadNotifications('comment')->count();  $has_com = $count_comment > 0 ? 'has' : ''; 
                @endphp
                
                <div class="navbar-custom-menu notification-clear">
                    <ul class="nav navbar-nav nav-sem-cor">
                       {{-- {{ dd(Auth::user()) }} --}}

                        @can('recebe-notificacao-pagamento')
                            {{-- new payments --}}
                            <li class="dropdown messages-menu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                                    {{-- {{ $has_pay == '' ? '-o' : ''}} --}}
                                    <i class="fa fa-paypal pos-rel">
                                        <span class="notifications-count label label-danger {{ $has_pay }}">
                                            {{$count_payment}}
                                        </span>
                                    </i>
                                </a>
                                <ul class="dropdown-menu notification-list">
                                    <li class="user-notification-header">
                                        <p class="texto-preto paddingL5 paddingT10 paddingB10" >{{ $count_payment > 0 ? __('messages.Você tem') . ' ' . $count_payment . ' ' . __('messages.notificações') : __('messages.Sem novas Notificações') }}</p>
                                    </li>
                                    <hr class="semMargem paddingB20">
                                    <li>
                                        <!-- inner menu: contains the actual data -->
                                        <ul class="menu">
                                            @forelse(Auth::user()->unreadNotifications('payment')->get() as $not)
                                                <li class="user-notification {{ $loop->last ? 'sem-borda' : '' }}" data-route="{{route('user.mark.read.notification', encrypt($not->id))}}">
                                                    <a href="{{$not->data['rota']}}" class="texto-preto paddingT10 paddingB10">
                                                        {{utf8_decode($not->data['titulo'])}}
                                                        <br>
                                                        {{utf8_decode($not->data['message'])}}
                                                    </a>
                                                </li>
                                            @empty
                                                <li class="no-notification">
                                                    <p class="texto-preto paddingL10">
                                                        {{ __('messages.Sem novas Notificações de Pagamento') }}.
                                                    </p>
                                                </li>
                                            @endforelse
                                        </ul>
                                    </li>
                                    <li class="footer displayFlex flexSpaceAround cyan ">
                                        <a href="{{ route('notifications.index') }}" class="texto-branco paddingT5 paddingB5 larguraMetade texto-centralizado">
                                            {{ __('messages.Ver Todas') }}
                                        </a>
                                        <a href="{{route('user.mark.read.all.notification','payment')}}" class="texto-branco paddingT5 paddingB5 larguraMetade texto-centralizado">
                                            {{ __('messages.Todas Lidas') }}
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endcan
                        @can('recebe-notificacao-comentario')
                            {{-- COMMENTS --}}
                            <li class="dropdown messages-menu ">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                                    <i class="fa fa-envelope{{$has_com == '' ? '-o' : ''}} pos-rel">
                                        <span class="notifications-count label label-danger {{ $has_com }}">
                                            {{$count_comment}}
                                        </span>
                                    </i>
                                </a>
                                <ul class="dropdown-menu notification-list">
                                    <li class="user-notification-header">
                                        <p class="texto-preto paddingL5 paddingT10 paddingB10" >{{ $count_payment > 0 ? __('messages.Você tem') . ' ' . $count_payment . ' ' . __('messages.notificações') : __('messages.Sem novas Notificações') }}</p>
                                    </li>
                                    <hr class="semMargem paddingB20">
                                    <li>
                                        <!-- inner menu: contains the actual data -->
                                        <ul class="menu">
                                            @php $notification_comment = Auth::user()->unreadNotifications('comment')->get() @endphp
                                            {{-- {{ dd($notification_comment) }} --}}
                                            @forelse($notification_comment as $not)
                                                <li class="user-notification {{ $loop->last ? 'sem-borda' : '' }}" data-route="{{route('user.mark.read.notification', encrypt($not->id))}}">
                                                    <a href="{{$not->data['rota']}}" class="texto-preto paddingT10 paddingB10">
                                                        {{utf8_decode($not->data['titulo'])}}
                                                        <br>
                                                        {{utf8_decode($not->data['message'])}}
                                                    </a>
                                                </li>
                                            @empty
                                                <li class="no-notification">
                                                    <p class="texto-preto paddingL10">
                                                        {{ __('messages.Sem novas Notificações de Comentário') }}.
                                                    </p>
                                                </li>
                                            @endforelse
                                        </ul>
                                    </li>
                                    <li class="footer displayFlex flexSpaceAround cyan ">
                                        <a href="{{ route('notifications.index') }}" class="texto-branco paddingT5 paddingB5 larguraMetade texto-centralizado">
                                            {{ __('messages.Ver Todas') }}
                                        </a>
                                        <a href="{{route('user.mark.read.all.notification','comment')}}" class="texto-branco paddingT5 paddingB5 larguraMetade texto-centralizado">
                                            {{ __('messages.Todas Lidas') }}
                                        </a>
                                    </li>
                                    
                                </ul>
                            </li>
                        @endcan

                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-bell{{$has == '' ? '-o' : ''}} pos-rel">
                                    <span class="notifications-count label label-danger {{ $has }}">
                                        {{$count_alert}}
                                    </span>
                                </i> 
                            </a>
                            <ul class="dropdown-menu notification-list">
                                <li class="user-notification-header ">
                                    <p class="texto-preto paddingL5 paddingT10 paddingB10" >{{ $count_alert > 0 ? __('messages.Você tem') . ' ' . $count_alert . ' ' . __('messages.notificações') : __('messages.Sem novas Notificações') }}</p>
                                    {{-- <h4 class="texto-branco texto-centralizado semMargem paddingT10 paddingB10">
                                        {{ __('messages.Notificações') }}
                                    </h4> --}}
                                </li>
                                <hr class="semMargem paddingB20">
                                <li>
                                    <ul class="menu">
                                        @php $notification_alert = Auth::user()->unreadNotifications('alert')->get() @endphp
                                       
                                        @forelse($notification_alert as $not)
                                            <li class="user-notification {{ $loop->last ? 'sem-borda' : '' }}" data-route="{{route('user.mark.read.notification', encrypt($not->id))}}">
                                                <a href="{{$not->data['rota']}}" class="texto-preto paddingT10 paddingB10">
                                                    {{utf8_decode($not->data['titulo'])}}
                                                    <br>
                                                    {{utf8_decode($not->data['message'])}}
                                                </a>
                                            </li>
                                        @empty
                                            <li class="no-notification">
                                                <p class="texto-preto paddingL10">
                                                    {{ __('messages.Sem novas Notificações') }}.
                                                </p>
                                            </li>
                                        @endforelse
                                    </ul>
                                </li>
                                {{-- <hr class="semMargem paddingB20 fundo-branco"> --}}
                                <li class="footer displayFlex flexSpaceAround cyan ">
                                    <a href="{{ route('notifications.index') }}" class="texto-branco paddingT5 paddingB5 larguraMetade texto-centralizado">
                                        {{ __('messages.Ver Todas') }}
                                    </a>
                                    <a href="{{route('user.mark.read.all.notification','alert')}}" class="texto-branco paddingT5 paddingB5 larguraMetade texto-centralizado">
                                        {{ __('messages.Todas Lidas') }}
                                    </a>
                                </li>
                            </ul>
                        </li>


                        <!-- User Account: style can be found in dropdown.less -->
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                @if(!empty(Auth::user()->image))
                                <img src="{{ Auth::user()->image ? URL::asset(Auth::user()->image) : URL::asset("storage/images/user/avatar-default.png")  }}" class="user-image" alt="User Image">
                                @endif
                                <span class="hidden-xs">{{ Auth::user() ? Auth::user()->name : 'Usuário'}}</span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- User image -->
                                <li class="user-header">
                                    <img src="{{ Auth::user()->image ? URL::asset(Auth::user()->image) : URL::asset("storage/images/user/avatar-default.png") }}" class="img-circle" alt="User Image">
                                    <p class="texto-branco">
                                        {{ Auth::user() ? Auth::user()->name : 'Usuário'}}
                                        @if(!empty(Auth::user()->created_at) )
                                            <small>{{ __('messages.Membro desde') }}  {{ \Carbon\Carbon::parse(Auth::user()->created_at)->format('m.Y')  }}</small>
                                        @endif
                                    </p>
                                </li>
                                <!-- Menu Body -->
                                {{-- <li class="user-body"> --}}
                                    {{-- <div class="row"> --}}
                                        {{-- <div class="col-xs-6 text-center"> --}}
                                            {{-- <a href="{{ route('jobs.todos') }}">Todos os Jobs</a> --}}
                                        {{-- </div> --}}
                                        {{--<div class="col-xs-4 text-center">--}}
                                            {{--<a href="#">Serviços</a>--}}
                                        {{--</div>--}}
                                        {{-- @can(['']) --}}
                                        {{-- <div class="col-xs-5 text-center"> --}}
                                            {{-- <a href="{{ route('users.show', encrypt(Auth::user()->id)) }}">Minha Conta</a> --}}
                                        {{-- </div> --}}
                                    {{-- </div> --}}
                                    <!-- /.row -->
                                {{-- </li> --}}
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a href="{{ route('jobs.todos') }}" class="btn btn-default btn-flat">{{ __('messages.Jobs') }}</a>
                                    </div>
                                    <div class="pull-right"> <a href="{{ route('users.show', encrypt(Auth::user()->id)) }}" class="btn btn-default btn-flat">{{ __('messages.Perfil') }}</a>
                                        {{--<a href="#" class="btn btn-default btn-flat" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">--}}
                                            {{--Sair--}}
                                        {{--</a>--}}
                                        {{--<form id="logout-form" action="{{ url(config('adminlte.logout_url', 'auth/logout')) }}" method="POST" style="display: none;">--}}
                                            {{--@if(config('adminlte.logout_method'))--}}
                                                {{--{{ method_field(config('adminlte.logout_method')) }}--}}
                                            {{--@endif--}}
                                            {{--{{ csrf_field() }}--}}
                                        {{--</form>--}}
                                    </div>
                                </li>
                            </ul>
                        </li>
                        <!-- Control Sidebar Toggle Button -->

                        {{-- Logout --}}
                        <li>
                            @if(config('adminlte.logout_method') == 'GET' || !config('adminlte.logout_method') && version_compare(\Illuminate\Foundation\Application::VERSION, '5.3.0', '<'))
                                <a href="{{ url(config('adminlte.logout_url', 'auth/logout')) }}">
                                    <i class="fa fa-fw fa-power-off"></i> {{-- trans('adminlte::adminlte.log_out') --}}
                                </a>
                            @else
                                <a href="#"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                >
                                    <i class="fa fa-fw fa-power-off"></i> {{-- trans('adminlte::adminlte.log_out') --}}
                                </a>
                                <form id="logout-form" action="{{ url(config('adminlte.logout_url', 'auth/logout')) }}" method="POST" style="display: none;">
                                    @if(config('adminlte.logout_method'))
                                        {{ method_field(config('adminlte.logout_method')) }}
                                    @endif
                                    {{ csrf_field() }}
                                </form>
                            @endif
                        </li>
                        {{-- Logout --}}

                    </ul>
                </div>
                @if(config('adminlte.layout') == 'top-nav')
                </div>
                @endif
            </nav>
        </header>

        @if(config('adminlte.layout') != 'top-nav')
        <!-- Left side column. contains the logo and sidebar -->
        <aside class="main-sidebar">

            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">

                <!-- Sidebar user panel -->
                <div class="user-panel">
                    <div class="pull-left image">
                        <img src="{{ Auth::user()->image ? URL::asset(Auth::user()->image) : URL::asset("storage/images/user/avatar-default.png") }}" class="img-circle" alt="User Image">
                    </div>
                    <div class="pull-left info">
                        <p class="texto-branco">{{ Auth::user() ? Auth::user()->name : 'Usuário'}}</p>
                        <a href="{{ route('users.show', encrypt(Auth::user()->id)) }}"><i class="fa fa-circle text-success"></i>{{ __('messages.Perfil Completo') }}</a>
                    </div>
                </div>


                <!-- Sidebar Menu -->
                <ul class="sidebar-menu" data-widget="tree">
                    @each('adminlte::partials.menu-item', $adminlte->menu(), 'item')
                </ul>
                <!-- /.sidebar-menu -->
            </section>
            <!-- /.sidebar -->
        </aside>
        @endif

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            @if(config('adminlte.layout') == 'top-nav')
            <div class="container">
            @endif

            <!-- Content Header (Page header) -->
            <section class="content-header">
                @yield('content_header')
            </section>

            <!-- Main content -->
            <section class="content paddingB50">

                @yield('content')

            </section>
            <!-- /.content -->
            @if(config('adminlte.layout') == 'top-nav')
            </div>
            <!-- /.container -->
            @endif
        </div>
        <!-- /.content-wrapper -->

    </div>
    <!-- ./wrapper -->
@stop

@section('adminlte_js')
    <script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>
    @stack('js')
    @yield('js')
@stop
