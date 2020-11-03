@extends('adminlte::page')

@section('title', 'Tutoriais')

@section('content_header')
    {{ Breadcrumbs::render('todos os tutoriais') }}
@stop

@section('content')

    <div class="row largura80 centralizado">
        <div class="col-md-12">
            <h1 class="margemB40">{{ __('messages.Lista de tutoriais') }}</h1>
           
            @if($editar_tutorial)
                <div class="btn-toolbar margemT10 btn-lista-novo" role="toolbar">
                    @php $rota="create.tutorial"; @endphp
                    <a href="{{ route($rota) }}" class="btn btn-success no-border " title="{{ __('messages.Criar Novo')}}" data-toggle="tooltip">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                    </a>
                </div>
            @endif
            
            @unless($tutoriais->count())
                    <p>{{ __('messages.Não Existem Tutorias') }}</p>
            @else
                <div class="col-md-12">
                    <div class="card-body">
                        <div>
                            <div class="filter-container p-0 row" style="padding: 3px; position: relative; width: 100%; display: flex; flex-wrap: wrap; height: 515px;">
                                @foreach($tutoriais as $index => $tut)
                                    <div class="filtr-item col-md-6 col-lg-3 col-sm-12"  >
                                        <iframe  src="{{ $tut->url }}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                        @if($editar_tutorial)
                                            <div class="displayFlex"><a href="{{ route("edit.tutorial", encrypt($tut->id))   }}">{{ __('messages.Editar')}} </a> |  
                                            <form action="{{ route('delete.tutorial', encrypt($tut->id)) }}" class="form-delete" id="form-deletar-tipo-img-{{ $tut->id }}" name="form-deletar-tipo-img-{{ $tut->id }}" method="POST" enctype="multipart/form-data">
                                                @method('DELETE')
                                                @csrf
                                                <a href="#" class="deletar-item" > {{ __('messages.Deletar')}}</a>
                                            </form></div>
                                        @endif
                                        <h3>{{ $tut->nome }}</h3>
                                        <h4>{{ $tut->descricao }}</h4>
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



