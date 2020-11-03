@extends('adminlte::page')

@section('title', __('messages.Todos os tipos de jobs'))

@section('content_header')
   {{ Breadcrumbs::render('todos tipos jobs') }}
    
@stop

@section('content')

    <div class="row largura90 centralizado">
        <h1 class="margemB40">{{ __('messages.Tipos de Job Cadastrados')}}</h1>

        <div class="col-md-12">
            
            <div class="btn-toolbar margemT10 btn-lista-novo" role="toolbar">
                <a href="{{ route('tipojobs.create') }}" class="btn btn-success no-border " title="{{ __('messages.Criar Novo Tipo de Job')}}" data-toggle="tooltip">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                </a>
            </div>

            @unless($tiposjob->count())
                <p>{{ __('messages.Não Existem Tipos de Job Cadastrados')}}</p>
            @else

                <table id="lista-dashboard" class="table table-striped larguraTotal ">
                    <thead class="">
                    <tr class="">
                        <th colspan="8" class="th-ocean texto-branco padding12 com-borda box-title">{{ __('messages.Tipos de Job Cadastrados')}}</th>
                    </tr>
                    </thead>
                    <tbody class="fundo-branco com-shadow">
                    @foreach($tiposjob as $tipojob)
                        <tr class="">
                            <td class="desktop">#{{ $tipojob->id }}</td>
                            <td>
                                <img src="{{ $tipojob->imagem ? Storage::url($tipojob->imagem) : Storage::url('imagens/tipojobs/tipo-padrao.png') }}" alt="Thumbnail" height="56" width="56">
                            </td>
                            <td>{{ $tipojob->nome  }}</td>
                            <td class="desktop">{{ $tipojob->descricao }}</td>
                            <td class="desktop">{{ $tipojob->boas_praticas }}</td>
                            <td class="texto-direita paddingR20">
                                <a href="{{ route('tipojobs.show', encrypt($tipojob->id)) }}" class="">{{ __('messages.Detalhes')}}</a>
                                |
                                <a href="{{ route('tipojobs.edit', encrypt($tipojob->id)) }}" class="">{{ __('messages.Editar')}}</a>
                                |
                                <a href="{{ route('tipojob.add.arquivos', encrypt($tipojob->id)) }}">
                                    {{ __('messages.Adicionar Arquivos')}}
                                </a>
                                 {{-- |
                                <form action="{{ route('tipojobs.destroy', encrypt($tipojob->id)) }}" class="form-delete" id="form-deletar-tipo-img-{{ $tipojob->id }}" name="form-deletar-tipo-img-{{ $tipojob->id }}" method="POST" enctype="multipart/form-data">
                                    @method('DELETE')
                                    @csrf
                                    <a href="#" class="deletar-item" type="submit">
                                       {{ __('messages.Excluir')}}
                                    </a>
                                </form> --}}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endunless
        </div>
    </div>

@stop