@extends('adminlte::page')

@section('title', $tipojob->nome ? $tipojob->nome : __('messages.Tipo de Job'))

@section('content_header')
   {{ Breadcrumbs::render('editar tipojob', $tipojob) }}
    
@stop

@section('content')

    <div class="row largura90 centralizado">
        @empty($tipojob)
            <h1>{{ __('messages.Tipo de job não Encontrado')}}</h1>
        @else
            <form id="form-projeto" name="form-projeto" action="{{ route('tipojobs.update', encrypt($tipojob->id)) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="_method" value="put">

                <div class="row margemB40">
                    <div class="col-md-8">
                        <h1 class="">{{ __('messages.Editar Dados do Tipo de Job')}}</h1>
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
                                    @include('tipojob.inputs', [$tipojob = $tipojob, $detalhe = null])
                                </div>
                            </div>
                            <div class="box-footer footer-com-padding">
                                <button type="submit" class="btn btn-primary pull-right">{{ __('messages.Atualizar Informações do Tipo de Job')}}</button>
                            </div>
                        </div>
                    </div>
                </div>

            </form>
        @endif
    </div>

@stop