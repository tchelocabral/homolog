@extends('adminlte::page')

@section('title', 'Habilidades')

@section('content_header')
    {{ Breadcrumbs::render('todas as habilidades') }}
@stop

@section('content')
    
    <div class="row largura80 centralizado">
        <div class="col-md-12">
            <h1 class="margemB40">Lista de Habilidades Cadastradas</h1>

            @unless($habilidades->count())
                <p>Não Existem Habilidades Cadastradas</p>
            @else

            {{--  Botao Criar Novo --}}
            <div class="btn-toolbar margemT10 btn-lista-novo" role="toolbar">
                <a href="{{ route('habilidades.create') }}" class="btn btn-success no-border " title="Criar Nova Habilidade" data-toggle="tooltip">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                </a>
            </div>

            <table id="lista-dashboard" class="table table-striped larguraTotal">
                <thead class="">
                <tr class="">
                    <th colspan="6" class="th-ocean texto-branco padding12 com-borda box-title">Habilidades</th>
                </tr>
                </thead>
                <tbody class="fundo-branco com-shadow">
                @foreach($habilidades as $hab)
                    <tr class="">
                        <td class="desktop">#{{ $hab->id }}</td>
                        <td class="desktop">{{  $hab->nome }}</td>
                        <td class="desktop">{{  $hab->descricao }}</td>
                        <td class="desktop"><span class="label label-{{ $hab->cor }}">{{  $hab->nome_cor() }}</span></td>
                        <td class="desktop">{{  $hab->created_at ? \Carbon\Carbon::parse($hab->created_at)->format('d.m.Y - H:i') : 'Não Informado'}}</td>
                        <td>
                            <a href="{{ route('habilidades.show', encrypt($hab->id)) }}" class="">Detalhes</a>
                            |
                            <a href="{{ route('habilidades.edit', encrypt($hab->id)) }}"" class="">Editar</a>
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