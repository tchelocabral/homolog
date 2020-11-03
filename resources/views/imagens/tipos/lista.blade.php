@extends('adminlte::page')

@section('title', 'Tipos de Imagem')

@section('content_header')
   {{ Breadcrumbs::render('todos tipos imagens') }}
@stop

@section('content')

    <div class="row largura90 centralizado">
        <h1 class="margemB40">Tipos de Imagem Cadastrados</h1>

        <div class="col-md-12">
            
            {{--  Botao Criar Novo --}}
            <div class="btn-toolbar margemT10 btn-lista-novo" role="toolbar">
                <a href="{{ route('tiposimagens.create') }}" class="btn btn-success no-border " title="Criar Novo Tipo de Imagem" data-toggle="tooltip">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                </a>
            </div>

            @unless($tipos_imagens->count())
                <p>Não Existem Tipos de Imagem Cadastrados</p>
            @else
                <table id="lista-dashboard" class="table  table-striped larguraTotal">
                    <thead class="">
                    <tr class="">
                        <th colspan="8" class="th-ocean texto-branco padding12 box-title com-borda">Tipos de Imagem Cadastrados</th>
                    </tr>
                    </thead>
                    <tbody class="fundo-branco com-shadow">
                    @foreach($tipos_imagens as $prop => $tipo)
                        <tr class="">
                            <td class="desktop">#{{ $tipo->id }}</td>
                            <td>{{ $tipo->nome  }}</td>
                            <td class="desktop">{{ $tipo->descricao }}</td>
                            <td class="desktop">{{ $tipo->grupo->nome }}</td>
                            <td class="desktop">{{ $tipo->created_at ? \Carbon\Carbon::parse($tipo->created_at)->format('d.m.Y - H:i') : 'Não Informado'}}</td>
                            <td>
                                <a href="{{ route('tiposimagens.show', encrypt($tipo->id)) }}" class="">Detalhes</a>
                                 |
                                <a href="{{ route('tiposimagens.edit', encrypt($tipo->id)) }}" class="">Editar</a>
                                 |
                                {{-- <form action="{{ route('tiposimagens.destroy', encrypt($tipo->id)) }}" class="form-delete" id="form-deletar-tipo-img-{{ $tipo->id }}" name="form-deletar-tipo-img-{{ $tipo->id }}" method="POST" enctype="multipart/form-data">
                                    @method('DELETE')
                                    @csrf
                                    <a href="#" class="deletar-item" type="submit">
                                       Deletar
                                    </a>
                                </form> --}}

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