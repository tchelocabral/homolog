@extends('adminlte::page')

@section('title', $tipo_imagem->nome ? $tipo_imagem->nome : 'Tipo de Imagem')

@section('content_header')
   {{ Breadcrumbs::render('detalhe-tipoimagem', $tipo_imagem) }}

@stop

@section('content')

    <div class="row largura90 centralizado">
  
        @empty($tipo_imagem)
            <h1>Tipo de Imagem não Encontrado</h1>
        @else
            <div class="row">
                <div class="col-md-5 col-md-offset-3">
                    <div class="box box-solid box-primary no-border com-shadow">
                        <div class="box-header com-borda th-ocean">
                            <h3 class="box-title">Detalhes de {{ $tipo_imagem->nome }}</h3>
                        </div>
                        <div class="box-body box-profile">
                            <div class="btn-toolbar margemT10" role="toolbar">
                                <a href="{{ route('tiposimagens.edit', encrypt($tipo_imagem->id)) }}" class="btn btn-info" title="Editar Item" data-toggle="tooltip">
                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                </a>
                                @if($tipo_imagem->podeApagar)
                                    <form action="{{ route('tiposimagens.destroy', encrypt($tipo_imagem->id)) }}" class="form-delete" id="form-deletar-tipo-img-{{ $tipo_imagem->id }}" name="form-deletar-tipo-img-{{ $tipo_imagem->id }}" method="POST" enctype="multipart/form-data">
                                        @method('DELETE')
                                        @csrf
                                        <a href="#" class="btn btn-danger deletar-item margemL5" title="Excluir Tipo de Imagem" data-toggle="tooltip" type="submit">
                                            <i class="fa fa-close" aria-hidden="true"></i>
                                        </a>
                                    </form>
                                @else
                                    <form action="{{ route('tipoimagems.transfer.deletar', encrypt($tipo_imagem->id)) }}" method="POST" class="form-delete">
                                        @csrf
                                        <a href="#" class="btn btn-danger deletar-item-tipo-job margemL5" title="{{ __('messages.Troca de jobs Tipo Job')}}" data-toggle="tooltip" type="submit">
                                            <i class="fa fa-close" aria-hidden="true"></i>
                                        </a>
                                        <input type="hidden" name="qtd_tipos" id="qtd-tipos" value="{{ count($tipo_imagem->imagens)  }}">
                                        <div class="invisivel">
                                            <select id="tipos-troca" name="tipos_troca" class="">
                                                <option value="-1">{{ __('messages.Escolha um tipo') }}</option>
                                                @foreach($tipo_imagem->lista_tipos_troca as $index => $tipo_troca)
                                                    <option value="{{ $tipo_troca->id }}">{{$tipo_troca->nome }}</option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </form>
                                @endif


                            </div>
                            <hr class="margemB40">
                            @include('imagens.tipos.inputs', ['tipo_imagem' => $tipo_imagem, 'detalhe' => true])
                        </div>
                    </div>
                </div>
            </div>
        @endempty
    </div>

@stop