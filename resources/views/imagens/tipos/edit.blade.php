@extends('adminlte::page')

@section('title', $tipo_imagem->nome ? $tipo_imagem->nome : 'Tipo de Imagem')

@section('content_header')
   {{ Breadcrumbs::render('editar tipoimagem', $tipo_imagem) }}
@stop

@section('content')

    <div class="row largura90 centralizado">
        @empty($tipo_imagem)
            <h1>Tipo de Imagem não Encontrado</h1>
        @else
            <form id="form-cliente" name="form-cliente" action="{{ route('tiposimagens.update', encrypt($tipo_imagem->id)) }}" method="POST" enctype="multipart/form-data">
                {{--security token--}}
                @csrf
                {{--force update resource route--}}
                {{method_field('PATCH')}}

                <div class="row">
                    <div class="col-md-8">
                        <div class="box box-solid box-primary no-border com-shadow">
                            <div class="box-header com-borda th-ocean">
                                <h3 class="box-title">Editar Tipo: {{ $tipo_imagem->nome }}</h3>
                            </div>
                            <div class="box-body box-profile">

                                @include('imagens.tipos.inputs', ['tipo_imagem' => $tipo_imagem, 'detalhe' => null])

                            </div>
                            <div class="box-footer footer-com-padding">
                                <div class="row">
                                    <div class="col-md-12 displayFlex flexSpaceBetween">
                                        <button type="submit" class="btn btn-success">Salvar Alterações</button>
                                    </div>
                                    {{--@endif--}}
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </form>
        @endempty
    </div>

@stop