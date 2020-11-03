@extends('adminlte::page')

@section('title', $deliveryformat->nome ? $deliveryformat->nome : __('messages.Formato de Entrega'))

@section('content_header')
   {{ Breadcrumbs::render('editar Formato de Entrega', $deliveryformat) }}
    
@stop

@section('content')

    <div class="row largura90 centralizado">
        @empty($deliveryformat)
            <h1>{{ __('messages.Formato de Entrega não Encontrado')}}</h1>
        @else
            <form id="form-delivery" name="form-delivery" action="{{ route('deliveryformat.update', encrypt($deliveryformat->id)) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="_method" value="put">

                <div class="row margemB40">
                    <div class="col-md-8">
                        <h1 class="">{{ __('messages.Editar Dados do Formato de Entrega')}}</h1>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="box box-solid box-primary com-shadow">
                            <div class="box-header com-borda th-ocean">
                                <h3 class="box-title">{{ __('messages.Preencha os campos com os dados do Formato de Entrega')}}</h3>
                            </div>
                            <div class="box-body">
                                <div class="col-md-12">
                                    <h3 class="">{{ __('messages.Dados do Formato de Entrega')}}</h3>
                                    <hr>
                                    @include('deliveryformat.inputs', [$deliveryformat = $deliveryformat, $detalhe = null])
                                </div>
                            </div>
                            <div class="box-footer footer-com-padding">
                                <button type="submit" class="btn btn-primary pull-right">{{ __('messages.Atualizar Informações do Formato de Entrega')}}</button>
                            </div>
                        </div>
                    </div>
                </div>

            </form>
        @endif
    </div>

@stop