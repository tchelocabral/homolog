@extends('adminlte::page')

@section('title', $cliente->nome_fantasia ? $cliente->nome_fantasia : __('messages.Cliente'))

@section('content_header')
    {{ Breadcrumbs::render('editar cliente', $cliente) }}  
@stop

@section('content')

    <div class="row largura90 centralizado">
        <h1 class="margemB40">{{ __('messages.Editar Cliente')}}</h1>

        <form id="form-cliente" name="form-cliente" action="{{ route('clientes.update', encrypt($cliente->id)) }}" method="POST" enctype="multipart/form-data">

            @csrf
            {{ method_field('PUT') }}

            <div class="row">
                <div class="col-md-12">
                    <div class="box box-solid box-primary com-shadow">
                        <div class="box-header fundo-verde com-borda">
                            <h3 class="box-title">{{ __('messages.Dados do Cliente')}}</h3>
                        </div>
                        <div class="box-body">
                            <div class="col-md-12 margemB40">
                                @include('cliente.inputs', ['cliente' => $cliente])
                            </div>


                            <hr>
                            <div class="col-md-10 margemB40">
                                <h3 class=" texto-preto margemB0">{{ __('messages.Dados de Contato')}}</h3>
                                @include('contato.inputs', ['contato' => $cliente->primeiro_contato()])
                            </div>


                            <hr>
                            <div class="col-md-10">
                                <h3 class=" texto-preto margemB0">{{ __('messages.Endereço')}}</h3>
                                @include('endereco.inputs', ['endereco' => $cliente->primeiro_endereco()])
                            </div>
                        </div>
                        <div class="box-footer footer-com-padding">
                            <button type="submit" class="btn btn-success pull-right">{{ __('messages.Alterar Informações')}}</button>
                        </div>
                    </div>
                </div>
            </div>

        </form>
    </div>

@stop

@push('js')
    <script src="{{ asset('js/imagens.js') }}"></script>
    <script src="{{ asset('js/ceps.js') }}"></script>
    <script src="{{ asset('js/perfil.js') }}"></script>
@endpush