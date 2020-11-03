@extends('adminlte::page')

@section('title', 'Projetos')

@section('content_header')
   {{ Breadcrumbs::render('todos centros custos') }}
@stop

@section('content')

    <div class="row largura80 centralizado">
        <div class="col-md-12">            

            <h1 class="margemB40">Lista de Centros de Custos Cadastrados</h1>

            <div class="btn-toolbar margemT10 btn-lista-novo" role="toolbar">
                <a href="{{ route('centro-custo.create') }}" class="btn btn-success no-border " title="Criar Novo Centro de Custo" data-toggle="tooltip">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                </a>
            </div>

            @unless($custos->count())
                <p>Não Existem Centros de Custos Cadastrados</p>
            @else
                <table id="lista-dashboard" class="table table-striped larguraTotal com-shadow">
                    <thead>
                        <tr>
                            <th colspan="" class="th-ocean texto-branco padding12 border-left">#</th>
                            <th colspan="" class="th-ocean texto-branco padding12">Nome</th>
                            <th colspan="" class="th-ocean texto-branco padding12">Descrição</th>
                            <th colspan="" class="th-ocean texto-branco padding12 border-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="fundo-branco">

                        @foreach($custos as $custo)
                            <tr class="">
                                <td class="desktop">#{{ $custo->id }}</td>
                                <td>{{ $custo->nome  }}</td>
                                <td class="desktop">{{ $custo->descricao }}</td>                                                 
                                <td>
                                    <a href="{{ route('centro-custo.show', encrypt($custo->id)) }}" class="">Detalhes</a>
                                    | 
                                    <a href="{{ route('centro-custo.edit', encrypt($custo->id)) }}" class="">Editar</a>
                                    |
                                    <form action="{{ route('centro-custo.destroy', encrypt($custo->id)) }}" class="form-delete" id="form-deletar-tipo-img-{{ $custo->id }}" name="form-deletar-tipo-img-{{ $custo->id }}" method="POST" enctype="multipart/form-data">
                                    @method('DELETE')
                                    @csrf
                                    <a href="#" class="deletar-item" data-toggle="tooltip" type="submit">
                                      Deletar
                                    </a>
                                </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endunless
        </div>
    </div>

@stop