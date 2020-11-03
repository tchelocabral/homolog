@extends('adminlte::page')

@section('title', __('messages.Novo Tipo de Job'))

@section('content_header')
   {{ Breadcrumbs::render('novo tipo job') }}
    
@stop

@section('content')

    <div class="row largura90 centralizado">

        <form id="form-projeto" name="form-projeto" action="{{ route('tipojobs.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row margemB40">
                <div class="col-md-8">
                    <h1 class="">{{ __('messages.Novo Tipo de Job')}}</h1>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12 col-md-12">
                    <div class="box box-solid box-primary com-shadow">
                        <div class="box-header com-borda th-ocean">
                            <h3 class="box-title">{{ __('messages.Preencha os campos com os dados do Tipo de Job')}}</h3>
                        </div>
                        <div class="box-body">
                            <div class="col-md-12">
                                <h3 class="">{{ __('messages.Dados do Tipo de Job')}}</h3>
                                <hr>
                                @include('tipojob.inputs', [$tipojob = null, $detalhe = null])
                            </div>
                        </div>
                        <div class="box-footer footer-com-padding">
                            <button type="submit" class="btn btn-success pull-right">{{ __('messages.Salvar Tipo de Job')}}</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

@stop


 {{--Controle da Lista de Imagem--}}
@push('js')

    <script>
        $(document).ready(function() {
        });
    </script>

@endpush