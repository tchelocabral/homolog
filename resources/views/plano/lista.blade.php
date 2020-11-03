@extends('adminlte::page')

@section('title', 'Planos')

@section('content_header')
    {{ Breadcrumbs::render('todos os planos') }}
@stop

@section('content')

    <div class="row largura80 centralizado">
        <div class="col-md-12">
            <h1 class="margemB40">{{ __('messages.Planos Cadastrados') }} - {{ $planos->count() }}</h1>
           

            {{--  Botao Criar Novo --}}
            <div class="btn-toolbar margemT10 btn-lista-novo" role="toolbar">
            @php
                $rota="planos.create";
            @endphp
                <a href="{{ route($rota) }}" class="btn btn-success no-border " title="Criar Plano" data-toggle="tooltip">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                </a>
            </div>
                
            @unless($planos->count())
                    <p>Não Existem {{ __('messages.Planos Cadastrados') }}</p>
            @else

                <table id="lista-dashboard" class="table larguraTotal table-striped search-table">
                    <thead class="">
                    <tr class="">
                        <th class="th-ocean texto-branco padding12 ">Nome</th>
                        <th class="th-ocean texto-branco padding12 ">Valor</th>
                        <th class="th-ocean texto-branco padding12 ">Descrição</th>
                        <th class="th-ocean texto-branco padding12 ">Status</th>
                        <th class="th-ocean texto-branco padding12 ">Criado em:</th>
                        <th class="th-ocean texto-branco padding12 ">Ação</th>
                    </tr>
                    </thead>
                    <tbody class="fundo-branco com-shadow">
                        

                        @foreach($planos as $index => $pla)
                            @php
                                $class_formatacao_fundo = '';
                                $class_formatacao_texto = '';

                                if($pla->status == 0 ) 
                                {
                                    $class_formatacao_fundo = 'fundo-vermelho';
                                    $class_formatacao_texto = 'texto-branco';
                                }
                                elseif($pla->status == 2)
                                {
                                    $class_formatacao_fundo = 'fundo-laranja';
                                    $class_formatacao_texto = 'texto-preto';
                                }
                                
                            @endphp
                            <tr class="{{ $class_formatacao_fundo }}  {{ $class_formatacao_texto }}">
                                <td class="desktop">{{ $pla->nome }}</td>
                                <td class="desktop">{{ $pla->valor }}</td>
                                <td class="desktop"><p class="word-break"> {{ $pla->descricao }}</p></td>
                                <td class="desktop">{{ $pla->status }}</td>
                                <td class="desktop">{{ $pla->created_at ? \Carbon\Carbon::parse($pla->created_at)->format('d.m.Y') : 'Não Informado'}}</td>
                                <td>
                                    <a href="planos/{{ encrypt($pla->id) }}" class="">{{ __('messages.Detalhes')}}</a> 
                                    {{-- |
                                    <a href="{{ route('planos.edit', encrypt($pla->id)) }}" class="">{{ __('messages.Editar')}}</a> --}}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endunless
        </div>
    </div>
@stop