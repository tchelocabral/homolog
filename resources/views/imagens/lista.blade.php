@extends('adminlte::page')

@section('title', 'Imagem')

@section('content_header')
    {{--<h1 class="margemB40">Tipos de Imagem Cadastradas</h1>--}}
@stop

@section('content')

    <div class="row largura80 centralizado">
        <h1 class="margemB40">Imagens</h1>

        <div class="col-md-12">

            @unless($imagens->count())
                <p>Não Existem Imagens Cadastradas</p>
            @else
                <table id="lista-dashboard" class="table table-striped larguraTotal">
                    <thead class="">
                        <tr class="">
                            <th colspan="1" class="fundo-escuro texto-branco padding10">#</th>
                            <th colspan="1" class="fundo-escuro texto-branco padding10">Nome da Imagem</th>
                            <th colspan="1" class="fundo-escuro texto-branco padding10">Projeto</th>
                            <th colspan="1" class="fundo-escuro texto-branco padding10">Revisão_00</th>
                            <th colspan="1" class="fundo-escuro texto-branco padding10">Criado em</th>
                            <th colspan="1" class="fundo-escuro texto-branco padding10">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="fundo-branco">
                    @foreach($imagens as $prop => $img)
                        <tr class="">
                            <td class="desktop">#{{ $img->id }}</td>
                            <td>{{ $img->nome  }}</td>
                            <td>{{ $img->projeto->nome  or 'Não Informado' }}</td>
                            <td class="desktop">{{ $img->data_revisao ? $img->data_revisao->format('d.m.Y') : 'Não Informado' }}</td>
                            <td class="desktop">{{ $img->created_at ? \Carbon\Carbon::parse($img->created_at)->format('d.m.Y') : 'Não Informado'}}</td>
                            <td>
                                <a href="{{ route('imagens.show', encrypt($img->id)) }}" class="">Detalhes</a>
                                 |
                                <a href="{{ route('imagens.edit', encrypt($img->id)) }}" class="">Editar</a>

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