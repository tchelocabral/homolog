@extends('adminlte::page')

@section('title', __('messages.Novo Plano'))

@section('content_header')
   {{-- {{ Breadcrumbs::render('criar plano', $projeto) }}  --}}
@stop

@section('content')

    <div class="row centralizado">
        <h1 class="margemB40">{{ __('messages.Novo Plano')}}</h1>
        {{-- {{ route('plano.store') }} --}}
        <form id="form-novo-plano" name="form-tipo-imagem" action="{{ route('planos.store') }}" method="POST" enctype="multipart/form-data">
            <input type="hidden" value="{{ route('termos.de.uso') }}" id="rota-termos">
            {{--security token--}}
            @csrf

            <div class="row">
                {{-- Box Novo plano --}}
                <div class="col-md-12">
                    <div class="box box-solid box-primary com-shadow paddingB40">
                        <div class="box-header th-ocean">
                            <h3 class="box-title">{{ __('messages.Detalhes do plano a ser feito')}}</h3>
                        </div>
                        <div class="box-body">
                            {{-- Primeira Linha --}}
                            <div class="row div-item-form">

                                {{-- Nome do plano --}}
                                <div class="col-sm-12 col-md-4">
                                    <p class="detalhe-label"><b>{{ __('messages.Nome do plano') }}</b></p>
                                    <input type="text" class="form-control " name="nome" id="plano-nome" required />
                                </div>

                                <div class="col-sm-12 col-md-4" id="div-valor">
                                    <p class="detalhe-label"><b>{{ __('messages.Valor do Job') }}</b></p>
                                    <input id="valor-job" type="text" data-type="currency" class="form-control" name="valor" step="0.01" placeholder="R$ 0,00" />
                                </div>

                                 {{-- Descrição --}}
                                 <div class="col-sm-12 col-md-12">
                                    <p class="detalhe-label"><b>{{ __('messages.Descrição do Plano') }}</b></p>
                                    @isset($detalhe)
                                    @else
                                        <textarea id="descricao" name="descricao" class="form-control" rows="5"></textarea>
                                    @endif
                                </div>

                                 {{-- Permissoes --}}
                                 <div class="col-sm-12 col-md-12" style="margin-top: 15px;">
                                    <p class="detalhe-label"><b>{{ __('messages.Permissões') }}</b></p>

                                </div>
                            </div>
                            <div class="row ">
                            @foreach ($role as $rol_item)
                                <div class="col-sm-12 col-md-6">
                                    <h3>{{ ucfirst($rol_item->name) }}</h3>
                                    @php $current = '' @endphp
                                    @foreach($permissions as $per)
                                        <!-- Título da Permissão -->
                                        @if($per->bloco != $current) 
                                            @if($current != '')
                                                </div>
                                            @endif      
                                            <div class="col-md-10">
                                                <hr class="margemT40 margemB10">
                                                <h4 class="">
                                                    <a class="larguraTotal alturaTotal texto-preto" data-toggle="collapse" data-target="#{{ ucfirst($per->bloco).'-'.$rol_item->name }}" aria-expanded="false" aria-controls="#collapsejobrecu" role="button"><span class="accordion-marc"><i class="fa fa-angle-right"></i></span>
                                                    Permissões de {{ ucfirst($per->bloco) }}
                                                    </a>
                                                </h4>
                                                <hr class="margemT10 margemB5">
                                            </div>
                                            <div class="row collapse" id="{{ ucfirst($per->bloco) .'-'.$rol_item->name}}">
                                            @php $current = $per->bloco @endphp
                                        @endif
                                        
                                        <div class="col-md-4 margemT10"> 
                                            <input type="checkbox" class="check-permissao" id="permission{{$per->id}}" 
                                            name="permissions[]" value="[{{ $rol_item->id }}-{{$per->id}}] " 
                                            {{ in_array($per->id, $rolePermissions[$rol_item->id]) ? 'checked="checked' : '' }}
                                            />
                                            {{ $per->name }}
                                        </div>

                                        @if($loop->last)
                                            </div>
                                        @endif      
                                   @endforeach     
                                </div> 
                            @endforeach     
                        </div>                       
                           
                            <div class="box-footer footer-com-padding borda-t-cinza">
                                <!-- <button type="submit" class="btn btn-success pull-right">Adicionar Novo Job</button> -->
                                <button id="publicar-job" class="btn btn-success pull-right margemR5" value="" data-id="" data-rota="" name="btnCriaPlano">{{ __('messages.Criar Plano') }}</button>
    
    
                            </div>

                        </div>
                    </div>
                </div>

               
            </div>{{-- end row --}}
            
    
        </form>

            
    </div>


@stop

@include('app.includes.carregando')

{{-- Controle dos campos --}}
@push('js')

    
    <script src="{{ asset('js/numeros.js') }}"></script>
    <script src="{{ asset('js/utils.js') }}"></script>

    <script>

        $(document).ready(function() {




        }); //end document ready

        
    </script>
    
@endpush