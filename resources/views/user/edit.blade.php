@extends('adminlte::page')

@section('title', __('messages.Perfil'))

<link rel="stylesheet" href="{{ asset('css/jquery.fancybox.css') }}">


@section('content')

    <div class="row largura80 centralizado">
        <h1 class="margemB40">{{ __('messages.Editar Membro') }}</h1>
     
        @empty($usuario)
            <h1>{{ __('messages.Perfil não Encontrado') }}.</h1>
        @else
            <form id="form-user" name="form-user" action="{{ route('users.update', encrypt($usuario->id)) }}" method="POST" enctype="multipart/form-data">
                {{--security token--}}
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="box box-solid box-primary com-shadow">
                        <div class="box-header th-ocean com-borda">
                            <h3 class="box-title">{{ __('messages.Dados pessoais e de acesso do usuário') }} </h3>
                        </div>
                        
                        <div class="box-body box-profile">                    
                            @include('user.inputs', ['usuario' => $usuario])
                        </div>

                        <div class="box-footer footer-com-padding">
                            <button type="submit" class="btn btn-success pull-right">{{ __('messages.Atualizar Membro') }}</button>
                        </div>
                    </div>
                </div>
                
            </form>
        @endempty
    </div>
@stop



@push('js')

    <script src="{{ asset('js/jquery.fancybox.min.js') }}"></script>
    
    <script>
        $(document).ready(function() {

            $("a.grouped_elements").fancybox();

            // /* This is basic - uses default settings */
            
            // $("a#single_image").fancybox();
            
            // /* Using custom settings */
            
            // $("a#inline").fancybox({
            //     'hideOnContentClick': true
            // });
        
            // /* Apply fancybox to multiple items */
            
            // $("a.grouped_elements").fancybox({
            //     'transitionIn'	:	'elastic',
            //     'transitionOut'	:	'elastic',
            //     'speedIn'		:	600, 
            //     'speedOut'		:	200, 
            //     'overlayShow'	:	false
            // });
            
            // $("a.grouped_elements").fancybox({
            //     'transitionIn'		: 'none',
            //     'transitionOut'		: 'none',
            //     'titlePosition' 	: 'over',
            //     'titleFormat'       : function(title, currentArray, currentIndex, currentOpts) {
            //         return '<span id="fancybox-title-over">Image ' +  (currentIndex + 1) + ' / ' + currentArray.length + ' ' + title + '</span>';
            //     }
            // });

        });
    </script>

@endpush