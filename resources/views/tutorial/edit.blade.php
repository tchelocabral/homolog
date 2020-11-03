@extends('adminlte::page')

@section('title', __('messages.Tutorial'))


@section('content')
    <div class="row largura80 centralizado">
        <h1 class="margemB40">{{ __('messages.Editar Tutorial') }}</h1>
     
        @empty($tutorial)
            <h1>{{ __('messages.Tutorial não Encontrado') }}.</h1>
        @else
            <form id="form-user" name="form-user" action="{{ route('update.tutorial', encrypt($tutorial->id)) }}" method="POST" enctype="multipart/form-data">
                {{--security token--}}
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="box box-solid box-primary com-shadow">
                        <div class="box-header th-ocean com-borda">
                            <h3 class="box-title">{{ __('messages.Dados do Tutorial') }} </h3>
                        </div>
                        
                        <div class="box-body box-profile">                    
                            @include('tutorial.inputs', ['usuario' => $tutorial])
                        </div>

                        <div class="box-footer footer-com-padding">
                            <button type="submit" class="btn btn-success pull-right">{{ __('messages.Atualizar Tutorial') }}</button>
                        </div>
                    </div>
                </div>
                
            </form>
        @endempty
    </div>
@stop