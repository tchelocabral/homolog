@extends('adminlte::page')

@section('title', 'Tasks')

@section('content_header')
   {{ Breadcrumbs::render('todas as tasks') }}
@stop

@section('content')

    <div class="row largura90 centralizado">
        <h1 class="margemB40">Tasks Cadastradas</h1>

        <div class="col-md-12">
            @can('cria-task')
            <div class="btn-toolbar margemT10 btn-lista-novo" role="toolbar">
                <a href="{{ route('tasks.create') }}" class="btn btn-success no-border " title="Criar Nova Task" data-toggle="tooltip">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                </a>
            </div>
            @endcan

            @unless($tasks->count())
                <p>Não Existem Tasks Cadastrados</p>
            @else
                <table id="lista-dashboard" class="table table-striped larguraTotal com-shadow">
                    <thead class="">
                    <tr class="">
                        <th colspan="5" class="th-ocean texto-branco padding12 com-borda box-title">Tasks Cadastradas</th>
                    </tr>
                    </thead>
                    <tbody class="fundo-branco">
                    @foreach($tasks as $task)
                        <tr class="">
                            <td class="desktop">#{{ $task->id }}</td>
                            <td class="texto-centralizado">{{ $task->nome  }}</td>
                            <td class="texto-centralizado">{{ $task->descricao ?? 'Sem descrição cadastrada'}}</td>
                            <td class="desktop">{{ $task->notification ? 'Envia notificação ao Coordenador' : 'Não envia notificação ao Coordenador' }}</td>
                            <td class="texto-direita paddingR20">
                                <a href="{{ route('tasks.show', encrypt($task->id)) }}" class="">Detalhes</a>
                                @can('atualiza-task')
                                 |
                                <a href="{{ route('tasks.edit', encrypt($task->id)) }}" class="">Editar</a>
                                @endcan
                                @can('delete-task')
                                 |
                                <form action="{{ route('tasks.destroy', encrypt($task->id)) }}" class="form-delete" id="form-deletar-tipo-img-{{ $task->id }}" name="form-deletar-tipo-img-{{ $task->id }}" method="POST" enctype="multipart/form-data">
                                    @method('DELETE')
                                    @csrf
                                    <a href="#" class="deletar-item" type="submit">
                                       Excluir
                                    </a>
                                </form>
                                @endcan

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

