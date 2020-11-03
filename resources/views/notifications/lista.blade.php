@extends('adminlte::page')

@section('title', __('messages.Notificações'))

@section('content_header')
   {{ Breadcrumbs::render('todas as notificacoes') }}
@stop

@section('content')

    <div class="row largura90 centralizado">
        <h1 class="margemB40">{{ __('messages.Todas as Notificações') }}</h1>

        <div class="col-md-12">
            
            @unless($notifications->count())
                <p>{{ __('messages.Não Existem Notificações') }} </p>
            @else
                <table id="lista-dashboard" class="table table-striped larguraTotal com-shadow">
                    <thead class="">
                    <tr class="">
                        <th class="th-ocean texto-branco padding12 box-title">#</th>
                        <th class="th-ocean texto-branco padding12 box-title" colspan="2">{{ __('messages.Todas as Notificações') }}</th>
                        <th class="th-ocean texto-branco padding12 box-title texto-centralizado">{{ __('messages.Notificado Em') }}</th>
                        <th class="th-ocean texto-branco padding12 box-title texto-centralizado">{{ __('messages.Lido Em') }}</th>
                        <th class="th-ocean texto-branco padding12 box-title texto-centralizado">#</th>
                    </tr>
                    </thead>
                    <tbody class="fundo-branco">
                    @foreach($notifications as $not)
                        @php
                            $icone = 'fa-bell';
                            if(strpos($not->type, "Payment"))
                            {
                                $icone = 'fa-paypal';
                            }
                            else if(strpos($not->type, "Comment"))
                            {
                                $icone = 'fa-envelope';
                            }
                        @endphp
                        <tr class="">
                            <td class="desktop"> <i class="fa {{ $icone }} pos-rel"> <i></td>

                            <td class="desktop">{{ utf8_decode($not->data['titulo']) }}</td>
                            <td class="">{{ utf8_decode($not->data['message'])  }}</td>
                            <td class="texto-centralizado"> {{ $not->created_at->format('d.m.Y') }} </td>
                            <td class="texto-centralizado"> {{ $not->read_at ? $not->read_at->format('d.m.Y') : '-' }} </td>
                            <td class="texto-centralizado"><a href="{{utf8_decode($not->data['rota'])}}">{{ __('messages.Visualizar') }}</a></td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    </tfoot>
                </table>
            @endunless
        </div>
    </div>
@stop

