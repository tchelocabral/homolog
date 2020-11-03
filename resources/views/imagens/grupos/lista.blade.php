@extends('adminlte::page')

@section('title', 'Grupos de Imagens')

@section('content_header')
   {{ Breadcrumbs::render('todos grupos imagens') }}
@stop

@section('content')

    <div class="row largura80 centralizado">
        <div class="col-md-12">            

            <h1 class="margemB40">Lista de Grupos de Imagens Cadastrados</h1>

            <div class="btn-toolbar margemT10 btn-lista-novo" role="toolbar">
                <a href="{{ route('grupo-imagem.create') }}" class="btn btn-success no-border " title="Criar Novo Grupo de Imagem" data-toggle="tooltip">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                </a>
            </div>

            @unless($grupo_imagens->count())
                <p>Não Existem Grupos de Imagens Cadastrados</p>
            @else
                <table id="lista-dashboard" class="table table-striped larguraTotal com-shadow">
                    <thead>
                        <tr>
                            <th colspan="" class="th-ocean texto-branco padding12 border-left">#</th>
                            <th colspan="" class="th-ocean texto-branco padding12">Nome</th>
                            <th colspan="" class="th-ocean texto-branco padding12">Descrição</th>
                            <th colspan="" class="th-ocean texto-branco padding12">Observações</th>
                            <th colspan="" class="th-ocean texto-branco padding12 border-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="fundo-branco">

                        @foreach($grupo_imagens as $grupo)
                            <tr class="">
                                <td class="desktop">#{{ $grupo->id }}</td>
                                <td>{{ $grupo->nome  }}</td>
                                <td class="desktop">{{ $grupo->descricao }}</td>                                                 
                                <td class="desktop">{{ $grupo->observacoes }}</td>                                                 
                                <td>
                                    <a href="{{ route('grupo-imagem.show', encrypt($grupo->id)) }}" class="">Detalhes</a>
                                    | 
                                    <a href="{{ route('grupo-imagem.edit', encrypt($grupo->id)) }}" class="">Editar</a>
                                    |
                                    <form action="{{ route('grupo-imagem.destroy', encrypt($grupo->id)) }}" class="form-delete" id="form-deletar-tipo-img-{{ $grupo->id }}" name="form-deletar-tipo-img-{{ $grupo->id }}" method="POST" enctype="multipart/form-data">
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