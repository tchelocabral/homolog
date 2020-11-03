@extends('adminlte::page')

@section('title', 'Novo Membro')

@section('content_header')
    {{ Breadcrumbs::render('novo membro') }}
@stop

@section('content')

    <div class="row largura80 centralizado">
        <h1 class="margemB40">{{ __('messages.Cadastrar Tutorial') }} </h1>
        @php
            $rota="add.tutoria";
        @endphp

        <form id="form-user" name="form-user" action="{{ route($rota) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <div class="box box-solid box-primary com-shadow">
                    <div class="box-header th-ocean com-borda">
                        <h3 class="box-title">{{ __('messages.Dados do Tutorial')}}</h3>
                    </div>
                    <div class="box-body box-profile">
                        @include('tutorial.inputs', ['tutorial'=>null])
                    </div>
                    <div class="box-footer footer-com-padding">
                        <button type="submit" class="btn btn-success pull-right">{{ __('messages.Adicionar Tutorial')}}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

@stop

