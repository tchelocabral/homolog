@extends('adminlte::page')

@section('title', __('messages.Lista de Arquivos para Download'))

@section('content_header')
    {{ Breadcrumbs::render('todos os tutoriais') }}
@stop

@section('content')
    
    <div class="row largura80 centralizado">
        <div class="col-md-12">
            <h1 class="margemB40">{{ __('messages.Lista de Arquivos para Download') }}</h1>
           
            {{-- @if($editar_resource)
                <div class="btn-toolbar margemT10 btn-lista-novo" role="toolbar">
                    @php
                        $rota="create.resources.files";

                    @endphp
                    <a href="{{ route($rota) }}" class="btn btn-success no-border " title="{{ __('messages.Criar Novo')}}" data-toggle="tooltip">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                    </a>
                </div>
            @endif --}}
            
            @unless($resources->count())
                    <p>{{ __('messages.Não Existem Arquivos') }}</p>
            @else
                <div class="col-md-12">
                    <div class="card-body">
                        <div>
                            <div class="filter-container p-0 row" style="padding: 3px; position: relative; width: 100%; display: flex; flex-wrap: wrap; height: 515px;">
                                
                                @foreach($resources as $index => $res)
                                    <div class="filtr-item col-md-6 col-lg-3 col-sm-12"  >

                                        <a href="{{ Storage::url($res->arquivo) }}" download>
                                            @php
                                                $exts   = ['3ds', 'cad', 'doc', 'dxf', 'max', 'pdf', 'psd', 'txt', 'docx', 'xls', 'zip', 'dwg'];
                                                $ext    = pathinfo($res->arquivo, PATHINFO_EXTENSION);
                                                $icone  = '/icones/';
                                                $icone .= in_array($ext, $exts) ? $ext : 'padrao';
                                                $icone .= '.png';
                                                $img_name = explode('/', $res->arquivo) ?? array();
                                            @endphp
                                        
                                        <img alt="{{ __('messages.click para baixar o arquivo')}}" src="{{$icone}}" width="28" height="28" alt="{{$ext}}">
                                     

                                        </a>

                                        @if($editar_resource)
                                            {{-- <div class="displayFlex"><a href="{{ route("edit.resources.files", encrypt($res->id))   }}">{{ __('messages.Editar')}} </a> |  
                                            <form action="{{ route('delete.resources.files', encrypt($res->id)) }}" class="form-delete" id="form-deletar-tipo-img-{{ $res->id }}" name="form-deletar-tipo-img-{{ $res->id }}" method="POST" enctype="multipart/form-data">
                                                @method('DELETE')
                                                @csrf
                                                <a href="#" class="deletar-item" > {{ __('messages.Deletar')}}</a>
                                            </form></div> --}}
                                        @endif

                                        <h3>{{ $res->nome }}</h3>
                                        <h4>{{ $res->descricao }}</h4>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endunless
        </div>
    </div>
@stop



