@extends('adminlte::page')

@section('title', 'Editar Política de Acesso')

@section('content_header')

@stop

@section('content')

 <div class="row largura80 centralizado">
        <h1 class="margemB40">Editar Política de Acesso</h1>

        <form id="form-politicas" name="form-politicas" action="{{ route('politicas.update', encrypt($role->id)) }}" method="POST" enctype="multipart/form-data">
            @csrf
            {{method_field('PATCH')}}
            <div class="row">
                <div class="box box-solid box-primary com-shadow">
                    <div class="box-header th-ocean com-borda">
                        <h3 class="box-title">Dados da política de acesso</h3>
                    </div>
                    <div class="box-body box-profile">
                        <div class="col-md-12">
                            <h3>Dados do Acesso</h3>
                            <hr>

                            <div class="row div-item-form">
                                <div class="col-md-6">
                                    <p class=""><b>Nome</b></p>
                                    <input type="text" name="name" class="form-control" value="{{ $role->name }}" placeholder="Digite aqui" />
                                </div>
                            </div>

                            <div class="row div-item-form">
                                <div class="col-md-12">
                                    <p><b>Selecione as Permissões</b></p>
                                    
                                    <p><input type="checkbox" id="checkTodos" name="checkTodos"> Selecionar Todos</p>

                                    @php $current = '' @endphp

                                    @foreach($permissions as $per)
                                        @if($per->bloco != $current) 
                                            @if($current != '')
                                                </div>
                                            @endif      
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <hr class="margemT40 margemB10">
                                                    <h4 class="">
                                                        <a class="larguraTotal alturaTotal texto-preto" data-toggle="collapse" data-target="#{{ ucfirst($per->bloco) }}" aria-expanded="false" aria-controls="#collapsejobrecu" role="button"><span class="accordion-marc"><i class="fa fa-angle-right"></i></span>Permissões de {{ ucfirst($per->bloco) }}</a>
                                                    </h4>
                                                    <hr class="margemT10 margemB5">
                                                </div>
                                            </div>
                                            <div class="row collapse"  id="{{ ucfirst($per->bloco) }}">
                                            @php $current = $per->bloco @endphp
                                        @endif
                                        <div class="col-md-4 margemT10"> 
                                            <input type="checkbox" class="check-permissao" id="permission{{$per->id}}" 
                                            name="permissions[]" value="{{$per->id}}" 
                                            {{ in_array($per->id, $rolePermissions) ? 'checked="checked' : '' }}
                                            />
                                            {{ $per->name }}
                                        </div>
                                    @endforeach
                                    
                                </div>
                            </div>
                        </div>  

                    </div>
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary pull-right">Atualizar Política</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    @endsection

    @push('js')

        <script type="text/javascript">
            
            // Funçao para fazer o botao selecionar todos os check buttons
            $(document).ready(function () {

                 jQuery('#checkTodos').on('ifChanged', function (e) {
                        this.checked ? selecionaTodos() : selecionaNenhum();
                    });
                    function selecionaTodos() {
                        var permissions = jQuery('.check-permissao');
                        $.each(permissions, function (key, value) {
                            value.checked = true;
                            jQuery('#'+value.id).iCheck('update');
                            jQuery('#'+value.id).iCheck('check');
                        });
                    }
                    function selecionaNenhum() {
                        var permissions = jQuery('.check-permissao');
                        $.each(permissions, function (key, value) {
                            value.checked = false;
                            jQuery('#'+value.id).iCheck('update');
                            jQuery('#'+value.id).iCheck('uncheck');
                        });
                    }
                });
                   
        </script>

    @endpush
