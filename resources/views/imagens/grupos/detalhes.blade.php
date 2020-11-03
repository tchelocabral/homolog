@extends('adminlte::page')

@section('title',  'Detalhes Grupo de Imagem')

@section('content_header')
   {{ Breadcrumbs::render('detalhe-grupoimagem', $grupo_imagem) }}

@stop

@section('content')

    <div class="row largura90 centralizado">
  
        @empty($grupo_imagem)
            <h1>Grupo de Imagem não Encontrado</h1>
        @else
            <div class="row">
                <div class="col-md-5 col-md-offset-3">
                    <div class="box box-solid box-primary no-border com-shadow">
                        <div class="box-header com-borda th-ocean">
                            <h3 class="box-title">Detalhes do Grupo de Imagens: {{ $grupo_imagem->nome }}</h3>
                        </div>
                        <div class="box-body box-profile">
                            <div class="btn-toolbar margemT10" role="toolbar">
                                <a href="{{ route('grupo-imagem.edit', encrypt($grupo_imagem->id)) }}" class="btn btn-info" title="Editar Item" data-toggle="tooltip">
                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                </a>

                                <form action="{{ route('grupo-imagem.destroy', encrypt($grupo_imagem->id)) }}" class="form-delete" id="form-deletar-tipo-img-{{ $grupo_imagem->id }}" name="form-deletar-tipo-img-{{ $grupo_imagem->id }}" method="POST" enctype="multipart/form-data">
                                    @method('DELETE')
                                    @csrf
                                    <a href="#" class="btn btn-danger deletar-item margemL5" title="Excluir Grupo de Imagem" data-toggle="tooltip" type="submit">
                                        <i class="fa fa-close" aria-hidden="true"></i>
                                    </a>
                                </form>
                            </div>
                            <hr class="margemB40">
                            <div class="col-md-12">
                                <p><b>Nome</b></p>
                                <p id="nome" class="margemB20" >{{ $grupo_imagem['nome'] or old('nome')  }} </p>
                            </div>

                            <div class="col-md-12 margemT10">
                                <p><b>Descrição</b></p>
                                <p id="descricao" class="margemB20" >{{ $grupo_imagem['descricao'] or old('descricao') }}</p>
                            </div> 

                            <div class="col-md-12 margemT10">
                                <p><b>Observações</b></p>
                                <p id="observacoes" class="margemB20" >{{ $grupo_imagem['observacoes'] or old('observacoes') }}</p>
                                  <hr class="margemB40">
                            </div>                          
                        </div>
                    </div>
                </div>
            </div>
        @endempty
    </div>

@stop