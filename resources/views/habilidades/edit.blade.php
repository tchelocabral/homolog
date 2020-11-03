@extends('adminlte::page')

@section('title', $habilidade->nome ? $habilidade->nome : 'Habilidade')

@section('content_header')
   {{ Breadcrumbs::render('editar habilidade', $habilidade) }}
    
@stop

@section('content')

    <div class="row largura90 centralizado">
        @empty($habilidade)
            <h1>Habilidade não Encontrada</h1>
        @else
            <form id="form-cliente" name="form-cliente" action="{{ route('habilidades.update', encrypt($habilidade->id)) }}" method="POST" enctype="multipart/form-data">
       
                @csrf
                {{method_field('PATCH')}}

                <div class="row">
                    <div class="col-md-5 col-md-offset-3">
                        <div class="box box-solid box-primary no-border com-shadow">
                            <div class="box-header com-borda th-ocean">
                                <h3 class="box-title">Editar Habilidade: {{ $habilidade->nome }}</h3>
                            </div>
                            <div class="box-body box-profile">

                                @include('habilidades.inputs', ['habilidade' => $habilidade, 'detalhe' => null])

                            </div>
                            <div class="box-footer footer-com-padding">
                                <div class="row">
                                    <div class="col-md-12 displayFlex flexSpaceBetween">
                                        <button type="submit" class="btn btn-success">Salvar Alterações</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        @endempty
    </div>
@stop