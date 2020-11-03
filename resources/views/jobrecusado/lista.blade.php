@extends('adminlte::page')

@section('title', 'Jobs')

@section('content_header')
   {{ Breadcrumbs::render('todos os jobs') }} 
@stop

@section('content')

    <div class="row largura90 centralizado">
        <h1 class="margemB40">Jobs Cadastrados</h1>

        <div class="col-md-12">
            @unless($jobs->count())
                <p>Não Existem Jobs Cadastrados</p>
            @else
                <table id="lista-dashboard" class="table table-striped larguraTotal">
                    <thead class="">
                    <tr class="">
                        <th colspan="" class="box-title th-ocean texto-branco padding12 com-border-left">#</th>
                        <th colspan="" class="box-title th-ocean texto-branco padding12">Nome do Job</th>
                        <th colspan="" class="box-title th-ocean texto-branco padding12">Descrição</th>
                        <th colspan="" class="box-title th-ocean texto-branco padding12">Colaborador</th>
                        <th colspan="" class="box-title th-ocean texto-branco padding12">Progresso</th>
                        <th colspan="" class="box-title th-ocean texto-branco padding12 com-border-right">Ações</th>
                    </tr>
                    </thead>
                    <tbody class="fundo-branco com-shadow">
                    @foreach($jobs as $job)
                        <tr class="">
                            <td class="desktop">#{{ $job->id }}</td>
                            <td>{{ $job->nome  }}</td>
                            <td class="desktop">{{ $job->descricao }}</td>
                            <td class="desktop">{{ $job->delegado->name or 'Não informado' }}</td>
                            <td>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-animated progresso" role="progressbar" aria-valuenow="{{ $job->concluido() }}" aria-valuemin="0" aria-valuemax="100" ></div>
                                </div>
                            </td>
                            <td>
                                <a href="{{ route('jobs.show', encrypt($job->id)) }}" class="">Detalhes</a>
                                @isset($job->coordenador)
                                    @if($job->coordenador->id == \Auth()->user()->id || Gate::check('gerencia-politicas') )
                                        |
                                        <a href="{{ route('jobs.edit', encrypt($job->id)) }}" class="">Editar</a>
                                    @endif
                                @endif
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