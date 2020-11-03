@extends('adminlte::page')

@section('title', $cliente->nome_fantasia ? $cliente->nome_fantasia : 'Cliente')

@section('content_header')

@stop

@section('content')

    <div class="row largura80 centralizado">
  
        @empty($cliente)
            <h1>Cliente não Encontrado</h1>
        @else
            <form id="form-cliente" name="form-cliente" action="{{ route('clientes.update', encrypt($cliente->id)) }}" method="POST" enctype="multipart/form-data">
                {{--security token--}}
                @csrf

                <h1 class="margemB40">{{ $cliente->razao_social }}</h1>
                <div class="row">
                    <div class="col-md-4">
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title">Dados do Cliente</h3>
                            </div>
                            <div class="box-body box-profile">

                                 @include('contato.inputs', ['contato' => null])

                            </div>
                            <div class="box-footer">
                                <button type="submit" class="btn btn-info pull-right">Salvar Alterações</button>
                            </div>
                        </div>
                    </div>
                    
                    {{-- <div class="col-md-8">
                        <div class="box box-info">
                            <div class="box-header with-border">
                                <h3 class="box-title">Projetos</h3>

                                <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                    </button>
                                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                                </div>
                            </div> --}}

                            {{-- <div class="box-body"> --}}
                                {{--{{dd($cliente->projetos)}}--}}
                               {{--  @if(!isset($cliente->projetos) || $cliente->projetos->isEmpty() )
                                    <h4>Ainda não existem Projetos com este Cliente</h4>
                                @else
                                    <div class="table-responsive">
                                        <table class="table no-margin">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Projeto</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody> --}}
                                              {{--   @each('views.projeto.item-tabela', $cliente->projetos,  'proj') --}}
                           {{--                  </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                             --}}
                            {{-- <div class="box-footer clearfix">
                                <a href="{{ route('projetos.create') }}" class="btn btn-sm btn-info btn-flat pull-left">Novo Projeto</a>
                            </div> --}}
                     {{--    </div>
                    </div> --}}
                </div>
            </form>
        @endempty
    </div>

@stop