@extends('adminlte::page')

@section('title', 'Editar Categoria de Custo')

@section('content_header')
   {{ Breadcrumbs::render('editar categoriacusto', $categoria) }}
@stop

@section('content')

    <div class="row largura90 centralizado">
        @empty($categoria)
            <h1>Categoria de Custo não encontrada</h1>
        @else
            <form id="form-cliente" name="form-cliente" action="{{ route('categoria-custo.update', encrypt($categoria->id)) }}" method="POST" enctype="multipart/form-data">
       
                @csrf
                {{method_field('PATCH')}}

                <div class="row">
                    <div class="col-md-5 col-md-offset-3">
                        <div class="box box-solid box-primary no-border com-shadow">
                            <div class="box-header com-borda th-ocean">
                                <h3 class="box-title">Editar Categoria de Custo: {{ $categoria->nome }}</h3>
                            </div>
                            <div class="box-body box-profile">
                               <div class="col-md-12">
                                    <p class=""><b>Nome</b></p>
                                    @isset($detalhe)
                                        <p id="nome" class="margemB20" >{{ $categoria['nome'] or  __('messages.Não Informado') }}</p>
                                    @else
                                        <input type="text" name="nome" class="form-control" value="{{ $categoria['nome']  or old('nome') }}" placeholder="Digite aqui" >
                                    @endif
                                </div>

                                <div class="col-md-12" style="margin-top: 10px;">
                                    <p class=""><b>Descrição</b></p>
                                    @isset($detalhe)
                                        <p id="descricao" class="margemB20" >{{ $categoria['descricao'] or  __('messages.Não Informado') }}</p>
                                    @else
                                        <textarea name="descricao" class="col-md-12 form-control" value="{{ $categoria['descricao']  or old('descricao') }}" placeholder="" >{{ $categoria['descricao']  or old('descricao') }}</textarea>
                                    @endif
                                </div>
                            </div>
                            <div class="box-footer footer-com-padding">
                                <div class="row">
                                    <div class="col-md-12 displayFlex flexSpaceBetween">
                                        <button type="submit" class="btn btn-success">Salvar Alterações</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        @endempty
    </div>

@stop