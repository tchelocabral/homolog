@extends('adminlte::page')

@section('title', 'Novo Tipo de Imagem')

@section('content_header')
   {{ Breadcrumbs::render('novo tipo imagem') }}
@stop

@section('content')

    <div class="row largura90 centralizado">

        <form id="form-tipo-imagem" name="form-tipo-imagem" action="{{ route('tiposimagens.store') }}" method="POST" enctype="multipart/form-data">

            @csrf

            <div class="row">

                <div class="col-md-8">
                    <div class="box box-solid box-primary no-border com-shadow">
                        <div class="box-header com-borda th-ocean">
                            <h3 class="box-title">Novo Tipo de Imagem</h3>
                        </div>
                        <div class="box-body box-profile">

                            @include('imagens.tipos.inputs', ['tipo_imagem' => null, 'detalhe' => null])

                        </div>
                        <div class="box-footer footer-com-padding">
                            <button type="submit" class="btn btn-success pull-right">Adicionar Novo Tipo de Imagem</button>
                        </div>
                    </div>
                </div>

            </div>

        </form>

    </div>

@stop

