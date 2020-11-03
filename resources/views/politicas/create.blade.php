@extends('adminlte::page')

@section('title', 'Criar Política de Acesso')

@section('content_header')

@stop

@section('content')

 <div class="row largura80 centralizado">
        <h1 class="margemB40">Cadastrar Nova Política de Acesso</h1>

        <form id="form-user" name="form-user" action="{{ route('politicas.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="box box-solid box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Dados da nova política de acesso</h3>
                    </div>
                    <div class="box-body box-profile">
                        <div class="col-md-5">
                            <h3>Dados do Novo Acesso</h3>
                            <hr>

                            <div class="row div-item-form">
                                <div class="col-md-12">
                                    <p class=""><b>Nome</b></p>
                                    @isset($detalhe)
                                        <p id="nome" class="margemB20" >{{ $usuario['name'] or 'Não Informado' }}</p>
                                    @else
                                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Digite aqui" />
                                    @endif
                                </div>
                            </div>

                            <div class="row div-item-form">
                                <div class="col-md-12">
                                    <p><b>Selecione as Permissões</b></p>
                                    <select id="combo-permissao" name="permission[]" class="form-control select2" value="{{ old('permissao')  }}" multiple="multiple">
                                        
                                        @foreach($permission as $value)
                                            <option value="{{ $value->id }}"> {{ $value->name }}</option>
                                        @endforeach

                                    </select>
                                </div>
                            </div>
                        </div>  
                    </div>
                    <div class="box-footer">
                        <button type="submit" class="btn btn-success pull-right">Adicionar Política</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection