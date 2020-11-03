@extends('adminlte::page')

@section('title',  __('messages.Dashboard'))

@section('content_header')
   {{ Breadcrumbs::render('relatorios.dashboard') }} 
   <h1 class="margemT20">{{ __('messages.Dashboard')}}</h1>
@stop

@section('content')
   
    <div class="largura80 centralizado" id="wrap-rel-dash">
        <div id="wrap-rel-dash-head" class="displayFlex flexCentralizado flexSpaceBetween">
            <div class="item-rel-dash largura25"></div>
            <div class="item-rel-dash largura20"><h2>Total</h2></div>
            <div class="item-rel-dash largura20"><h2>Mês</h2></div>
            <div class="item-rel-dash largura20"><h2>Semana</h2></div>
            <div class="item-rel-dash largura20"><h2>Dia</h2></div>
        </div>

        <div id="wrap-rel-dash-nps-media" class="displayFlex flexCentralizado flexSpaceBetween margemT10">
            <div class="item-rel-dash largura25"><h2>NPS Media</h2></div>
            <div class="item-rel-dash largura20 displayFlex flexCentralizado flexStart"><h3>{{ $rel['nps-media-total'] ?? '00' }}</h3></div>
            <div class="item-rel-dash largura20 displayFlex flexCentralizado flexStart"><h3>{{ $rel['nps-media-mes'] ?? '00' }}</h3></div>
            <div class="item-rel-dash largura20 displayFlex flexCentralizado flexStart"><h3>{{ $rel['nps-media-semana'] ?? '00' }}</h3></div>
            <div class="item-rel-dash largura20 displayFlex flexCentralizado flexStart"><h3>{{ $rel['nps-media-dia'] ?? '00' }}</h3></div>
        </div>
        <div id="wrap-rel-dash-nps-freela" class="displayFlex flexCentralizado flexSpaceBetween">
            <div class="item-rel-dash largura25"><h2>NPS Freela</h2></div>
            <div class="item-rel-dash largura20 displayFlex flexCentralizado flexStart"><h3>{{ $rel['nps-freela-total'] ?? '00' }}</h3></div>
            <div class="item-rel-dash largura20 displayFlex flexCentralizado flexStart"><h3>{{ $rel['nps-freela-mes'] ?? '00' }}</h3></div>
            <div class="item-rel-dash largura20 displayFlex flexCentralizado flexStart"><h3>{{ $rel['nps-freela-semana'] ?? '00' }}</h3></div>
            <div class="item-rel-dash largura20 displayFlex flexCentralizado flexStart"><h3>{{ $rel['nps-freela-dia'] ?? '00' }}</h3></div>
        </div>
        <div id="wrap-rel-dash-nps-pub" class="displayFlex flexCentralizado flexSpaceBetween">
            <div class="item-rel-dash largura25"><h2>NPS Publisher</h2></div>
            <div class="item-rel-dash largura20 displayFlex flexCentralizado flexStart"><h3>{{ $rel['nps-publisher-total'] ?? '00' }}</h3></div>
            <div class="item-rel-dash largura20 displayFlex flexCentralizado flexStart"><h3>{{ $rel['nps-publisher-mes'] ?? '00' }}</h3></div>
            <div class="item-rel-dash largura20 displayFlex flexCentralizado flexStart"><h3>{{ $rel['nps-publisher-semana'] ?? '00' }}</h3></div>
            <div class="item-rel-dash largura20 displayFlex flexCentralizado flexStart"><h3>{{ $rel['nps-publisher-dia'] ?? '00' }}</h3></div>
        </div>


        <div id="wrap-rel-dash-gmv" class="displayFlex flexCentralizado flexSpaceBetween margemT20">
            <div class="item-rel-dash largura25"><h2>GMV</h2></div>
            <div class="item-rel-dash largura20 displayFlex flexCentralizado flexStart"><h3>R$ @php $money = $rel['gmv-total']; @endphp @convert_money($money) </h3></div>
            <div class="item-rel-dash largura20 displayFlex flexCentralizado flexStart"><h3>R$ @php $money = $rel['gmv-mes']; @endphp @convert_money($money) </h3></div>
            <div class="item-rel-dash largura20 displayFlex flexCentralizado flexStart"><h3>R$ @php $money = $rel['gmv-semana']; @endphp @convert_money($money) </h3></div>
            <div class="item-rel-dash largura20 displayFlex flexCentralizado flexStart"><h3>R$ @php $money = $rel['gmv-dia']; @endphp @convert_money($money) </h3></div>
        </div>

        <div id="wrap-rel-dash-receita" class="displayFlex flexCentralizado flexSpaceBetween">
            <div class="item-rel-dash largura25"><h2>Previsão de Receita</h2></div>
            <div class="item-rel-dash largura20 displayFlex flexCentralizado flexStart"><h3>R$ @php $money = $rel['prev-receita-total'] ?? '00'; @endphp @convert_money($money) </h3></div>
            <div class="item-rel-dash largura20 displayFlex flexCentralizado flexStart"><h3>R$ @php $money = $rel['prev-receita-mes'] ?? '00'; @endphp @convert_money($money) </h3></div>
            <div class="item-rel-dash largura20 displayFlex flexCentralizado flexStart"><h3>R$ @php $money = $rel['prev-receita-semana'] ?? '00'; @endphp @convert_money($money) </h3></div>
            <div class="item-rel-dash largura20 displayFlex flexCentralizado flexStart"><h3>R$ @php $money = $rel['prev-receita-dia'] ?? '00'; @endphp @convert_money($money) </h3></div>
        </div>

        <div id="wrap-rel-dash-receita" class="displayFlex flexCentralizado flexSpaceBetween">
            <div class="item-rel-dash largura25"><h2>Receita Bruta</h2></div>
            <div class="item-rel-dash largura20 displayFlex flexCentralizado flexStart"><h3>R$ @php $money = $rel['receita-total']; @endphp @convert_money($money) </h3></div>
            <div class="item-rel-dash largura20 displayFlex flexCentralizado flexStart"><h3>R$ @php $money = $rel['receita-mes']; @endphp @convert_money($money) </h3></div>
            <div class="item-rel-dash largura20 displayFlex flexCentralizado flexStart"><h3>R$ @php $money = $rel['receita-semana']; @endphp @convert_money($money) </h3></div>
            <div class="item-rel-dash largura20 displayFlex flexCentralizado flexStart"><h3>R$ @php $money = $rel['receita-dia']; @endphp @convert_money($money) </h3></div>
        </div>

        {{-- (-impostos -tx_paypal) --}}
        <div id="wrap-rel-dash-receita" class="displayFlex flexCentralizado flexSpaceBetween">
            <div class="item-rel-dash largura25"><h2>Receita Líquida</h2></div>
            <div class="item-rel-dash largura20 displayFlex flexCentralizado flexStart"><h3>R$ @php $money = $rel['receita-bruta-total'] ?? '00'; @endphp @convert_money($money) </h3></div>
            <div class="item-rel-dash largura20 displayFlex flexCentralizado flexStart"><h3>R$ @php $money = $rel['receita-bruta-mes'] ?? '00'; @endphp @convert_money($money) </h3></div>
            <div class="item-rel-dash largura20 displayFlex flexCentralizado flexStart"><h3>R$ @php $money = $rel['receita-bruta-semana'] ?? '00'; @endphp @convert_money($money) </h3></div>
            <div class="item-rel-dash largura20 displayFlex flexCentralizado flexStart"><h3>R$ @php $money = $rel['receita-bruta-dia'] ?? '00'; @endphp @convert_money($money) </h3></div>
        </div>

        <div id="wrap-rel-dash-freelas" class="displayFlex flexCentralizado flexSpaceBetween margemT20">
            <div class="item-rel-dash largura25"><h2>Freelas</h2></div>
            <div class="item-rel-dash largura20 displayFlex flexCentralizado flexStart"><h3>{{ strlen($rel['freelas-total']) > 1 ? $rel['freelas-total'] : '0' . $rel['freelas-total'] }}</h3></div>
            <div class="item-rel-dash largura20 displayFlex flexCentralizado flexStart"><h3>{{ strlen($rel['freelas-mes']) > 1 ? $rel['freelas-mes'] : '0' . $rel['freelas-mes'] }}</h3></div>
            <div class="item-rel-dash largura20 displayFlex flexCentralizado flexStart"><h3>{{ strlen($rel['freelas-semana']) > 1 ? $rel['freelas-semana'] : '0' . $rel['freelas-semana'] }}</h3></div>
            <div class="item-rel-dash largura20 displayFlex flexCentralizado flexStart"><h3>{{ strlen($rel['freelas-dia']) > 1 ? $rel['freelas-dia'] : '0' . $rel['freelas-dia'] }}</h3></div>
        </div>
        <div id="wrap-rel-dash-freelas" class="displayFlex flexCentralizado flexSpaceBetween margemT20">
            <div class="item-rel-dash largura25"><h2>Actived Freelas</h2></div>
            <div class="item-rel-dash largura20 displayFlex flexCentralizado flexStart"><h3>{{ strlen($rel['active-freelas-total']) > 1 ? $rel['active-freelas-total'] : '0' . $rel['active-freelas-total'] }}</h3></div>
            <div class="item-rel-dash largura20 displayFlex flexCentralizado flexStart"><h3>{{ strlen($rel['active-freelas-mes']) > 1 ? $rel['active-freelas-mes'] : '0' . $rel['active-freelas-mes'] }}</h3></div>
            <div class="item-rel-dash largura20 displayFlex flexCentralizado flexStart"><h3>{{ strlen($rel['active-freelas-semana']) > 1 ? $rel['active-freelas-semana'] : '0' . $rel['active-freelas-semana'] }}</h3></div>
            <div class="item-rel-dash largura20 displayFlex flexCentralizado flexStart"><h3>{{ strlen($rel['active-freelas-dia']) > 1 ? $rel['active-freelas-dia'] : '0' . $rel['active-freelas-dia'] }}</h3></div>
        </div>
        <div id="wrap-rel-dash-pubs" class="displayFlex flexCentralizado flexSpaceBetween">
            <div class="item-rel-dash largura25"><h2>Publishers</h2></div>
            <div class="item-rel-dash largura20 displayFlex flexCentralizado flexStart"><h3>{{ strlen($rel['publishers-total']) > 1 ? $rel['publishers-total'] : '0' . $rel['publishers-total'] }}</h3></div>
            <div class="item-rel-dash largura20 displayFlex flexCentralizado flexStart"><h3>{{ strlen($rel['publishers-mes']) > 1 ? $rel['publishers-mes'] : '0' . $rel['publishers-mes'] }}</h3></div>
            <div class="item-rel-dash largura20 displayFlex flexCentralizado flexStart"><h3>{{ strlen($rel['publishers-semana']) > 1 ? $rel['publishers-semana'] : '0' . $rel['publishers-semana'] }}</h3></div>
            <div class="item-rel-dash largura20 displayFlex flexCentralizado flexStart"><h3>{{ strlen($rel['publishers-dia']) > 1 ? $rel['publishers-dia'] : '0' . $rel['publishers-dia'] }}</h3></div>
        </div>
        <div id="wrap-rel-dash-pubs" class="displayFlex flexCentralizado flexSpaceBetween">
            <div class="item-rel-dash largura25"><h2>Actived Publishers</h2></div>
            <div class="item-rel-dash largura20 displayFlex flexCentralizado flexStart"><h3>{{ strlen($rel['active-publishers-total']) > 1 ? $rel['active-publishers-total'] : '0' . $rel['active-publishers-total'] }}</h3></div>
            <div class="item-rel-dash largura20 displayFlex flexCentralizado flexStart"><h3>{{ strlen($rel['active-publishers-mes']) > 1 ? $rel['active-publishers-mes'] : '0' . $rel['active-publishers-mes'] }}</h3></div>
            <div class="item-rel-dash largura20 displayFlex flexCentralizado flexStart"><h3>{{ strlen($rel['active-publishers-semana']) > 1 ? $rel['active-publishers-semana'] : '0' . $rel['active-publishers-semana'] }}</h3></div>
            <div class="item-rel-dash largura20 displayFlex flexCentralizado flexStart"><h3>{{ strlen($rel['active-publishers-dia']) > 1 ? $rel['active-publishers-dia'] : '0' . $rel['active-publishers-dia'] }}</h3></div>
        </div>



        <div id="wrap-rel-dash-jobs" class="displayFlex flexCentralizado flexSpaceBetween margemT20">
            <div class="item-rel-dash largura25"><h2>Jobs</h2></div>
            <div class="item-rel-dash largura20 displayFlex flexCentralizado flexStart"><h3>{{ strlen($rel['jobs-total']) > 1 ? $rel['jobs-total'] : '0' . $rel['jobs-total'] }}</h3></div>
            <div class="item-rel-dash largura20 displayFlex flexCentralizado flexStart"><h3>{{ strlen($rel['jobs-mes']) > 1 ? $rel['jobs-mes'] : '0' . $rel['jobs-mes'] }}</h3></div>
            <div class="item-rel-dash largura20 displayFlex flexCentralizado flexStart"><h3>{{ strlen($rel['jobs-semana']) > 1 ? $rel['jobs-semana'] : '0' . $rel['jobs-semana'] }}</h3></div>
            <div class="item-rel-dash largura20 displayFlex flexCentralizado flexStart"><h3>{{ strlen($rel['jobs-dia']) > 1 ? $rel['jobs-dia'] : '0' . $rel['jobs-dia'] }}</h3></div>
        </div>
        <div id="wrap-rel-dash-job-exec" class="displayFlex flexCentralizado flexSpaceBetween">
            <div class="item-rel-dash largura25"><h2>In Progress</h2></div>
            <div class="item-rel-dash largura20 displayFlex flexCentralizado flexStart"><h3>{{ strlen($rel['in-progress-total']) > 1 ? $rel['in-progress-total'] : '0' . $rel['in-progress-total'] }}</h3></div>
            <div class="item-rel-dash largura20 displayFlex flexCentralizado flexStart"><h3>{{ strlen($rel['in-progress-mes']) > 1 ? $rel['in-progress-mes'] : '0' . $rel['in-progress-mes'] }}</h3></div>
            <div class="item-rel-dash largura20 displayFlex flexCentralizado flexStart"><h3>{{ strlen($rel['in-progress-semana']) > 1 ? $rel['in-progress-semana'] : '0' . $rel['in-progress-semana'] }}</h3></div>
            <div class="item-rel-dash largura20 displayFlex flexCentralizado flexStart"><h3>{{ strlen($rel['in-progress-dia']) > 1 ? $rel['in-progress-dia'] : '0' . $rel['in-progress-dia'] }}</h3></div>
        </div>
        <div id="wrap-rel-dash-job-conc" class="displayFlex flexCentralizado flexSpaceBetween">
            <div class="item-rel-dash largura25"><h2>Concluded</h2></div>
            <div class="item-rel-dash largura20 displayFlex flexCentralizado flexStart"><h3>{{ strlen($rel['concluded-total']) > 1 ? $rel['concluded-total'] : '0' . $rel['concluded-total'] }}</h3></div>
            <div class="item-rel-dash largura20 displayFlex flexCentralizado flexStart"><h3>{{ strlen($rel['concluded-mes']) > 1 ? $rel['concluded-mes'] : '0' . $rel['concluded-mes'] }}</h3></div>
            <div class="item-rel-dash largura20 displayFlex flexCentralizado flexStart"><h3>{{ strlen($rel['concluded-semana']) > 1 ? $rel['concluded-semana'] : '0' . $rel['concluded-semana'] }}</h3></div>
            <div class="item-rel-dash largura20 displayFlex flexCentralizado flexStart"><h3>{{ strlen($rel['concluded-dia']) > 1 ? $rel['concluded-dia'] : '0' . $rel['concluded-dia'] }}</h3></div>
        </div>
        <div id="wrap-rel-dash-job-ref" class="displayFlex flexCentralizado flexSpaceBetween">
            <div class="item-rel-dash largura25"><h2>Refused</h2></div>
            <div class="item-rel-dash largura20 displayFlex flexCentralizado flexStart"><h3>{{ strlen($rel['refused-total']) > 1 ? $rel['refused-total'] : '0' . $rel['refused-total'] }}</h3></div>
            <div class="item-rel-dash largura20 displayFlex flexCentralizado flexStart"><h3>{{ strlen($rel['refused-mes']) > 1 ? $rel['refused-mes'] : '0' . $rel['refused-mes'] }}</h3></div>
            <div class="item-rel-dash largura20 displayFlex flexCentralizado flexStart"><h3>{{ strlen($rel['refused-semana']) > 1 ? $rel['refused-semana'] : '0' . $rel['refused-semana'] }}</h3></div>
            <div class="item-rel-dash largura20 displayFlex flexCentralizado flexStart"><h3>{{ strlen($rel['refused-dia']) > 1 ? $rel['refused-dia'] : '0' . $rel['refused-dia'] }}</h3></div>
        </div>
        
        <div id="wrap-rel-dash-job-quote" class="displayFlex flexCentralizado flexSpaceBetween">
            <div class="item-rel-dash largura25"><h2>In Quotes</h2></div>
            <div class="item-rel-dash largura20 displayFlex flexCentralizado flexStart"><h3>{{ strlen($rel['quoted-total']) > 1 ? $rel['quoted-total'] : '0' . $rel['quoted-total'] }}</h3></div>
            <div class="item-rel-dash largura20 displayFlex flexCentralizado flexStart"><h3>{{ strlen($rel['quoted-mes']) > 1 ? $rel['quoted-mes'] : '0' . $rel['quoted-mes'] }}</h3></div>
            <div class="item-rel-dash largura20 displayFlex flexCentralizado flexStart"><h3>{{ strlen($rel['quoted-semana']) > 1 ? $rel['quoted-semana'] : '0' . $rel['quoted-semana'] }}</h3></div>
            <div class="item-rel-dash largura20 displayFlex flexCentralizado flexStart"><h3>{{ strlen($rel['quoted-dia']) > 1 ? $rel['quoted-dia'] : '0' . $rel['quoted-dia'] }}</h3></div>
        </div>

        {{--  --}}


    </div>

@stop
