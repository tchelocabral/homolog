@extends('adminlte::page')

@section('title', 'Nova Habilidade')

@section('content_header')
   {{ Breadcrumbs::render('nova habilidade') }}
@stop

@section('content')

    <div class="row largura90 centralizado">

        <form id="form-tipo-imagem" name="form-tipo-imagem" action="{{ route('habilidades.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">

                <div class="col-md-6 col-md-offset-3">
                    <div class="box box-solid box-primary no-border com-shadow">
                        <div class="box-header com-borda th-ocean">
                            <h3 class="box-title">Nova Habilidade</h3>
                        </div>
                        <div class="box-body box-profile">
                                
                            @include('habilidades.inputs', ['habilidade' => null, 'detalhe' => null])
                           
                        </div>
                        <div class="box-footer footer-com-padding">
                            <button type="submit" class="btn btn-success pull-right">Adicionar Nova Habilidade</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@stop

