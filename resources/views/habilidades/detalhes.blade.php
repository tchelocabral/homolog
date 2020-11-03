@extends('adminlte::page')

@section('title', $habilidade->nome ? $habilidade->nome : 'Habilidade')

@section('content_header')
   {{ Breadcrumbs::render('detalhe-habilidade', $habilidade) }}
@stop

@section('content')

    <div class="row largura90 centralizado">
    
        @empty($habilidade)
            <h1>Habilidade não Encontrada</h1>
        @else
            <div class="row">
                <div class="col-md-5 col-md-offset-3">
                    <div class="box box-solid box-primary no-border com-shadow">
                        <div class="box-header com-borda th-ocean">
                            <h3 class="box-title">Detalhes de {{ $habilidade->nome }}</h3>
                        </div>
                        <div class="box-body box-profile">
                            <div class="btn-toolbar margemT10" role="toolbar">
                                <a href="{{ route('habilidades.edit', encrypt($habilidade->id)) }}" class="btn btn-info" title="Editar Item" data-toggle="tooltip">
                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                </a>

                                <form action="{{ route('habilidades.destroy', encrypt($habilidade->id)) }}" class="form-delete" id="form-deletar-tipo-img-{{ $habilidade->id }}" name="form-deletar-tipo-img-{{ $habilidade->id }}" method="POST" enctype="multipart/form-data">
                                    @method('DELETE')
                                    @csrf
                                    <a href="#" class="btn btn-danger deletar-item margemL5" title="Excluir Habilidade" data-toggle="tooltip" type="submit">
                                        <i class="fa fa-close" aria-hidden="true"></i>
                                    </a>
                                </form>

                            </div>
                            <hr class="margemB40">
                            @include('habilidades.inputs', ['habilidade' => $habilidade, 'detalhe' => true])
                        </div>
                    </div>
                </div>
            </div>
        @endempty
    </div>

@stop