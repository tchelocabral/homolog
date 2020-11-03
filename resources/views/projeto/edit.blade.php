@extends('adminlte::page')

@section('title', $projeto->nome_fantasia ? $projeto->nome_fantasia : __('messages.Editar Projeto'))

@section('content_header')
      {{ Breadcrumbs::render( __('messages.editar projeto'), $projeto) }}
@stop

@section('content')

    <div class="row largura90 centralizado">
        @empty($projeto)
            <h1>{{ __('messages.Cliente não Encontrado')}}</h1>
        @else
            <form id="form-projeto" name="form-projeto" action="{{ route('projetos.update', encrypt($projeto->id)) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="_method" value="put">

                <div class="row margemB40">
                    <div class="col-md-8">
                        <h1 class="">{{ __('messages.Editar Dados do Projeto')}}</h1>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12 col-md-6">
                        <div class="box box-solid box-primary com-shadow">
                            <div class="box-header com-borda th-ocean">
                                <h3 class="box-title">{{ __('messages.Preencha os campos com os dados do Projeto')}}</h3>
                            </div>
                            <div class="box-body">
                                <div class="col-md-12">
                                    <h3 class="">{{ __('messages.Dados do Projeto')}}</h3>
                                    <hr>
                                    @include('projeto.inputs', [$projeto = $projeto, $detalhe = null, $clientes = $clientes])
                                </div>
                            </div>
                            <div class="box-footer footer-com-padding">
                                <button type="submit" class="btn btn-primary pull-right">{{ __('messages.Atualizar Informações do Projeto')}}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        @endif
    </div>

@stop