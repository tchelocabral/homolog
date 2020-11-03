@extends('adminlte::page')

@section('title', 'Categorias de Custo')

@section('content_header')
   {{ Breadcrumbs::render('todas categorias custos') }}
@stop

@section('content')

    <div class="row largura80 centralizado">
        <div class="col-md-12">            

            <h1 class="margemB40">Lista de Categorias de Custos Cadastradas</h1>

            <div class="btn-toolbar margemT10 btn-lista-novo" role="toolbar">
                <a href="{{ route('categoria-custo.create') }}" class="btn btn-success no-border " title="Criar Nova Categoria de Custo" data-toggle="tooltip">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                </a>
            </div>

            @unless($categorias->count())
                <p>Não Existem Categorias de Custos Cadastradas</p>
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

                        @foreach($categorias as $categoria)
                            <tr class="">
                                <td class="desktop">#{{ $categoria->id }}</td>
                                <td>{{ $categoria->nome  }}</td>
                                <td class="desktop">{{ $categoria->descricao }}</td>                                                 
                                <td>
                                    <a href="{{ route('categoria-custo.show', encrypt($categoria->id)) }}" class="">Detalhes</a>
                                    | 
                                    <a href="{{ route('categoria-custo.edit', encrypt($categoria->id)) }}" class="">Editar</a>
                                    |
                                    <form action="{{ route('categoria-custo.destroy', encrypt($categoria->id)) }}" class="form-delete" id="form-deletar-tipo-img-{{ $categoria->id }}" name="form-deletar-tipo-img-{{ $categoria->id }}" method="POST" enctype="multipart/form-data">
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