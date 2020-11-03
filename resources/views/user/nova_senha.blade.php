@extends('adminlte::page')

@section('title', __('messages.Cadastrar Nova Senha'))

@section('content_header')

@stop

@section('content')

    <div class="displayFlex flexCentralizado larguraTotal secao70">
        <div class="box box-primary box-solid largura33 tab-largura75 mob-largura80">
            <div class="box-header with-border">
                <h4 class="texto-branco">{{ __('messages.Cadastrar Nova Senha') }}</h4>
                <!-- <p>{{$user_id ?? 'logado'}}</p> -->
            </div>
            <div class="box-body box-profile">

                <form id="form-user-nova-senha" name="form-user-nova-senha" action="{{ route('user.gravar.senha') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="_method" value="PUT">
                    <input type="hidden" name="user_id" value="{{ $user_id ?? Auth::id() }}">
                    <div class="row div-item-form">
                        <div class="col-md-12">
                            <p class=""><b>{{ __('messages.Digite a nova senha') }}</b> ({{ __('messages.mínimo de 6 caracteres') }})</p>
                            <input type="password" name="password" class="form-control" placeholder="{{ trans('adminlte::adminlte.password') }}">
                            @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="row div-item-form">
                        <div class="col-md-12">
                            <p class=""><b>{{ __('messages.Confirme sua senha') }}</b></p>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="{{ trans('adminlte::adminlte.retype_password') }}">
                            @if ($errors->has('password_confirmation'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <hr>
                    <div class="row div-item-form">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-success">{{ __('messages.Gravar senha') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop
