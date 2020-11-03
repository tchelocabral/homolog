@extends('adminlte::page')

@section('title', __('messages.Clientes'))

@section('content_header')
    {{ Breadcrumbs::render('todos os clientes') }}
@stop

@section('content')

    <div class="row largura80 centralizado">
        <div class="col-md-12">
            <h1 class="margemB40">{{ __('messages.Lista de Clientes Cadastrados')}}</h1>

            @unless($clientes->count())
                <p>{{ __('messages.Não Existem Clientes Cadastrados')}}</p>
            @else

                {{--  Botao Criar Novo --}}
                <div class="btn-toolbar margemT10 btn-lista-novo" role="toolbar">
                    <a href="{{ route('clientes.create') }}" class="btn btn-success no-border " title="{{ __('messages.Criar Novo Cliente')}}" data-toggle="tooltip">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                    </a>
                </div>

                <table id="lista-dashboard" class="table table-striped larguraTotal table-striped">
                    <thead class="">
                    <tr class="">
                        <th colspan="8" class="th-ocean texto-branco padding12 com-borda box-title">{{ __('messages.Clientes')}}</th>
                    </tr>
                    </thead>
                    <tbody class="fundo-branco com-shadow">
                    @foreach($clientes as $prop => $cli)
                      
                        <tr class="">
                            <td class="desktop">#{{ $cli->id }}</td>
                            <td class="desktop">{{ $cli->nome_fantasia }}</td>
                            <td class="desktop">{{ $cli->cnpj or '' }}</td>
                            <td class="desktop">{{ $cli->created_at ? \Carbon\Carbon::parse($cli->created_at)->format('d.m.Y - H:i') : __('messages.Não Informado')}}</td>
                            <td>
                                <a href="clientes/{{ encrypt($cli->id) }}" class="">{{ __('messages.Detalhes')}}</a>
                                |
                                <a href="{{ route('clientes.edit', encrypt($cli->id)) }}" class="">{{ __('messages.Editar')}}</a>
                            </td>
                           
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

