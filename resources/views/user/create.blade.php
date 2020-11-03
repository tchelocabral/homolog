@extends('adminlte::page')

@section('title', 'Novo Membro')

@section('content_header')
    {{ Breadcrumbs::render('novo membro') }}
@stop

@section('content')

    <div class="row largura80 centralizado">
        <h1 class="margemB40">Cadastrar Novo {{isset($tipo) ? $tipo : 'Membro'}}</h1>
        @php
            $rota="users.store";
            // if($tipo=="Coordenador") {
            //      $rota = "users.store.coordenador"; }

        @endphp

        <form id="form-user" name="form-user" action="{{ route($rota) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <div class="box box-solid box-primary com-shadow">
                    <div class="box-header th-ocean com-borda">
                        <h3 class="box-title">Dados pessoais e de acesso do novo usuário </h3>
                    </div>
                    <div class="box-body box-profile">
                        @include('user.inputs', ['usuario'=> null, 'roles'=> $roles])
                    </div>
                    <div class="box-footer footer-com-padding">
                        <button type="submit" class="btn btn-success pull-right">Adicionar Membro</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

@stop

