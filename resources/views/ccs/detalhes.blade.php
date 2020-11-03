@extends('adminlte::page')

@section('title',  'Detalhes Centro de Custo')

@section('content_header')
   {{ Breadcrumbs::render('detalhe-centrocusto', $custo) }}

@stop

@section('content')

    <div class="row largura90 centralizado">
  
        @empty($custo)
            <h1>Centro de Custo não Encontrado</h1>
        @else
            <div class="row">
                <div class="col-md-5 col-md-offset-3">
                    <div class="box box-solid box-primary no-border com-shadow">
                        <div class="box-header com-borda th-ocean">
                            <h3 class="box-title">Detalhes de {{ $custo->nome }}</h3>
                        </div>
                        <div class="box-body box-profile">
                            <div class="btn-toolbar margemT10" role="toolbar">
                                <a href="{{ route('centro-custo.edit', encrypt($custo->id)) }}" class="btn btn-info" title="Editar Item" data-toggle="tooltip">
                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                </a>

                                <form action="{{ route('centro-custo.destroy', encrypt($custo->id)) }}" class="form-delete" id="form-deletar-tipo-img-{{ $custo->id }}" name="form-deletar-tipo-img-{{ $custo->id }}" method="POST" enctype="multipart/form-data">
                                    @method('DELETE')
                                    @csrf
                                    <a href="#" class="btn btn-danger deletar-item margemL5" title="Excluir Centro de Custo" data-toggle="tooltip" type="submit">
                                        <i class="fa fa-close" aria-hidden="true"></i>
                                    </a>
                                </form>
                            </div>
                            <hr class="margemB40">
                            <div class="col-md-12">
                                <p><b>Nome</b></p>
                                <p id="nome" class="margemB20" >{{ $custo['nome'] or old('nome')  }} </p>
                            </div>

                            <div class="col-md-12 margemT10">
                                <p><b>Descrição</b></p>
                                <p id="descricao" class="margemB20" >{{ $custo['descricao'] or old('descricao') }}</p>
                                  <hr class="margemB40">
                            </div>                          
                        </div>
                    </div>
                </div>
            </div>
        @endempty
    </div>

@stop