@extends('adminlte::page')

@section('title', 'Políticas de Acesso')

@section('content_header')
    {{ Breadcrumbs::render('todas as politicas') }}
@stop

@section('content')
    <div class="row margemT40 centralizado">
        <div class="nav-tabs-custom altura-minima">
            <ul class="nav nav-tabs azul" id="tabs-politica" role="tablist">
                @php
                    $personalizacao_permissao = false;
                    $todos_politicas = true;

                    // estavam aqui os actives
                    $active_politicas = "";
                    $tab_active_politicas = "";
                    
                    $active_permissao = "";
                    $tab_active_permissoes_persona = "";

                    $active_dash = "active in";
                    $tab_active_dash = "in active";

                    if($user_permissao) {
                        $personalizacao_permissao = true;
                        $todos_politicas = false;

                        $active_permissao = "active in";
                        $active_politicas = "";                

                        $tab_active_politicas = "";
                        $tab_active_permissoes_persona = "in active";

                        $active_dash = "";
                        $tab_active_dash = "";
                    }              

                @endphp

                <li class="{{ $active_politicas }}">
                    <a data-toggle="tab" href="#todas" aria-expanded="{{ $todos_politicas }}" class="nav-link">Todas as Políticas</a>
                </li>
                <li><a data-toggle="tab" href="#nova" aria-expanded="false" class="nav-link">Nova Política </a></li>
                <li><a data-toggle="tab" href="#atribuir" aria-expanded="false" class="nav-link">Atribuir Política</a></li>
                <li class="{{ $active_permissao }}">
                    <a data-toggle="tab" href="#permisao-personalizada" aria-expanded="{{ $personalizacao_permissao }}" class="nav-link">Permissão Personalizada</a>
                </li>
                @if (\Auth::user()->hasAnyRole('desenvolvedor'))
                    <li><a data-toggle="tab" href="#nova-permissao" aria-expanded="false" class="nav-link">Criar Permissão</a></li>
                @endif
                <li class="{{$active_dash}}"><a data-toggle="tab" href="#permissoes-politicas" aria-expanded="false" class="nav-link">Permissões por Políticas</a></li>
            </ul>

            <div class="tab-content">
                
                {{-- Tab #todasPoliticas--}}
                <div id="todas" class="tab-pane fade {{ $tab_active_politicas  }}">
                    <!-- conteudo da tab detalhes -->
                    <div class="row">
                        <div class="col-md-12">
                            <h1 class="margemB40">Lista de Políticas de Acesso Cadastradas</h1>
                            @unless($roles->count())
                               
                            @else
                                <table id="lista-dashboard" class="table table-striped larguraTotal com-shadow">
                                    <thead class="">
                                        <tr class="">
                                            <th colspan="" class="th-ocean texto-branco padding12 com-border-left">#</th>
                                            <th colspan="" class="th-ocean texto-branco padding12">Política</th>
                                            <th colspan="" class="th-ocean texto-branco paddingR50 padding12 com-border-right texto-direita ">Detalhes</th>
                                            <th colspan="" class="th-ocean texto-branco padding12 "></th>
                                        </tr>
                                    </thead>
                                    <tbody class="fundo-branco">

                                    @foreach($roles as $role)                                
                                        <tr class="">
                                            <td class="desktop">#{{ $role->id }}</td>
                                            <td>{{ ucfirst($role->name) }}</td>
                                            <td class="texto-direita ">
                                                <a href="{{ route('politicas.show', encrypt($role->id)) }}" class="">Detalhes</a>
                                                <a href="{{ route('politicas.edit', encrypt($role->id)) }}" class=""> | Editar</a>
                                            </td>
                                            <td></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @endunless
                        </div>
                    </div>                  
                </div>

                <!-- conteudo da tab criar nova politica -->
                {{-- Tab #novaPolitica--}}
                <div id="nova" class="tab-pane fade">
                    <div class="row">
                        <div class="col-md-12">
                            <h1 class="margemB40">Cadastrar Nova Política de Acesso</h1>
                        </div>
                    </div>

                    <form id="form-nova" name="form-nova" action="{{ route('politicas.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf       
                        <div class="row div-item-form">
                            <div class="col-md-6">
                                <h3>Nome</h3>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Digite aqui" />
                            </div>
                        </div>

                        <div class="row div-item-form margemT40">
                            <div class="col-md-12">
                                <p><b>Selecione as Permissões</b></p>
                                <p><input type="checkbox" id="checkTodos" name="checkTodos"> Selecionar Todas</p>
                            </div>
                        </div>
                        @php $current = '' @endphp
                        @foreach($permissions as $per)
                            <!-- Título da Permissão -->
                            @if($per->bloco != $current) 
                                @if($current != '')
                                    </div>
                                @endif      
                                <div class="row ">
                                    <div class="col-md-12">
                                        <hr class="margemT40 margemB10">
                                        <h4 class="">
                                            <a class="larguraTotal alturaTotal texto-preto" data-toggle="collapse" data-target="#{{ ucfirst($per->bloco) }}" aria-expanded="false" aria-controls="#collapsejobrecu" role="button"><span class="accordion-marc"><i class="fa fa-angle-right"></i></span>
                                            Permissões de {{ ucfirst($per->bloco) }}
                                            </a>
                                        </h4>
                                        <hr class="margemT10 margemB5">
                                    </div>
                                </div>
                                <div class="row collapse" id="{{ ucfirst($per->bloco) }}">
                                @php $current = $per->bloco @endphp
                            @endif
                            <div class="col-md-4 margemT10"> 
                                <input type="checkbox" class="check-permissao" id="permission{{$per->id}}" name="permissions[]" value="{{$per->id}}" /> {{ $per->name }}
                            </div>
                            @if($loop->last)
                                </div>
                            @endif      
                        @endforeach
                        <div class="row">
                            <div class="col-md-12 footer-com-padding">
                                <hr>
                                <button type="submit" class="btn btn-success pull-right">Adicionar Política</button>
                            </div>
                        </div>                        
                    </form>
                </div>

                <!-- conteudo da tab atribuir política -->
                {{-- Tab #atribuirPolitica--}}
                <div id="atribuir" class="tab-pane fade">
                    <div class="row">
                        <div class="col-md-12">
                            <h1 class="margemB40">Atribuir Política de Acesso</h1>
                        </div>
                    </div>

                    <form id="form-atribuir" name="form-atribuir" action="{{ route('atribuir.politica') }}" method="POST" enctype="multipart/form-data">
                        @csrf 
                        <div class="row div-item-form">
                            <div class="col-md-5">
                                <h3>Usuário</h3>
                                <select name="user" class="form-control select2 margemT10 larguraTotal">
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-5 col-md-offset-1">
                                <h3>Política</h3>
                                <select name="role" class="form-control select2 margemT10 larguraTotal">
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                                    @endforeach
                                </select><br /><br />
                                <button type="submit" class="btn btn-success pull-right">Atribuir Política</button>
                            </div>              
                        </div> 
                    </form> 
                </div>

                <div id="nova-permissao" class="tab-pane fade">
                    <div class="row">
                        <div class="col-md-12">
                            <h1 class="margemB40">Criar Nova Permissão de Acesso</h1>
                        </div>
                    </div>

                    <div class="row">
                        <form action="{{route('politicas.nova.permissao')}}" method="POST" name="form-nova-permissao" id="form-nova-permissao">
                            @csrf
                           <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-4">
                                        Permissão
                                        <input type="text" placeholder="Nome da Permissão" name="name" id="nome-permissao" class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        Bloco
                                        <input type="text" placeholder="Bloco da Permissão (Ex: cliente, job, task...)" name="bloco" id="bloco-permissao" class="form-control">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 margemT10">
                                        <button type="submit" class="btn btn-success">Salvar Nova Permissão</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                </div><!-- end tab content -->   

                <!-- conteudo da tab permisao personalizada -->
                {{-- Tab #permisaopersonalizada--}}
                <div id="permisao-personalizada" class="tab-pane fade  {{ $tab_active_permissoes_persona  }}">
                    <div class="row">
                        <div class="col-md-12">
                            <h1 class="margemB40">Permissão Personalizada de Acesso</h1>
                        </div>
                    </div>

                    <form id="form-atribuir" name="form-atribuir" action="{{ route('usuario.permissao') }}" method="GET" enctype="multipart/form-data">
                        @csrf
                        <div class="row div-item-form">
                            <div class="col-md-5">
                                <h3>Usuário</h3>
                                <select name="user" class="form-control select2 margemT10 larguraTotal" onchange="this.form.submit()">
                                    <option value="">Selecione um usuário</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- <div class="col-md-5 col-md-offset-1">
                                <h3>Permissões</h3>
                                <select name="permissiom" class="form-control select2 margemT10 larguraTotal">
                                    @foreach($permissions as $per)
                                        <option value="{{ $role->id }}">{{ ucfirst($per->name) }}</option>
                                    @endforeach
                                </select><br /><br />
                                <button type="submit" class="btn btn-success pull-right">Atribuir permissão</button>
                            </div>               -->
                        </div> 
                    </form> 

                    @if($user_permissao)
                    <div class="row div-item-form">
                        <div class="col-md-5">
                            <h3>{{($user_permissao->name)}}</h3>
                        </div>
                        <div class="col-md-12">
                            <p><b>Selecione as Permissões</b></p>
                            <p><input type="checkbox" id="checkTodosPermissao" name="checkTodosPermissao"> Selecionar Todos</p>

                            @php $current = '' @endphp
                            <form id="form-politicas" name="form-politicas" action="{{ route('atribuir.permissao.usuario', encrypt($user_permissao->id)) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="usuario_id" value="{{ $user_permissao->id }}">
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
                                        <div class="row collapse in"  id="{{ ucfirst($per->bloco) }}">
                                        @php $current = $per->bloco @endphp
                                    @endif
                                    <div class="col-md-4 margemT10"> 
                                        <input type="checkbox" class="check-permissao-personalizadas" id="permission{{$per->id}}" 
                                        name="permissions[]" value="{{$per->id}}" 
                                        {{ in_array($per->id, $user_permissions) ? 'checked="checked' : '' }}
                                        />
                                        {{ $per->name }}
                                    </div>
                                @endforeach
                                <div class="box-footer">
                                    <button type="submit" class="btn btn-primary pull-right">Atualizar Permissão</button>
                                </div>
                            </form>                            
                        </div>
                    </div>
                    @endif
                </div>

                 <!-- conteudo da tab permissoes-politicas -->
                {{-- Tab #permissoes-politicas--}}
                <div id="permissoes-politicas" class="tab-pane fade {{ $tab_active_dash }}">
                    <div class="row">
                        <div class="col-md-12">
                            <h1 class="margemB40 ">Lista de Permissões por Políticas</h1>
                            <div class="table-responsive">
                                <table class="table no-margin table-striped larguraTotal com-shadow search-table">
                                    <thead>
                                        <tr>
                                            <th class="negrito com-border-left  th-ocean texto-branco"> Permissões / Políticas </th>
                                            <th class="negrito com-border-left  th-ocean texto-branco"> Tipo </th>
                                            @foreach ($roles as $role)
                                            <th class="texto-centralizado th-ocean texto-branco">{{ $role->name }}</th>
                                            @php
                                                    $role->permissions_ids = $role->permissions->pluck('id')->toArray();   
                                                @endphp
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($permissions as $permissao)
                                            <tr>
                                                <td>{{ $permissao->name }}</td>
                                                <td>{{ $permissao->bloco }}</td>
                                                @foreach ($roles as $role)
                                                    @if(in_array($permissao->id, $role->permissions_ids))
                                                        <td class="texto-centralizado bg-success texto-preto">x</td>
                                                    @else
                                                        <td class="texto-centralizado">-</td>
                                                    @endif
                            
                                                    
                                                @endforeach
                                            </tr>
                                        @endforeach
                            
                                    </tbody>
                            
                                </table>
                            </div>
                        </div>
                    </div>
                </div>


        </div>


                {{-- Tab #nova-permissao--}}
          
            </div>
        </div>
    </div>
@stop
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


            jQuery('#checkTodosPermissao').on('ifChanged', function (e) {
                this.checked ? selecionaTodosPersonalizado() : selecionaNenhumPersonalizado();
            });

            function selecionaTodosPersonalizado() {
                var permissions = jQuery('.check-permissao-personalizadas');
                $.each(permissions, function (key, value) {
                    value.checked = true;
                    jQuery('#'+value.id).iCheck('update');
                    jQuery('#'+value.id).iCheck('check');
                });
            }
            function selecionaNenhumPersonalizado() {
                var permissions = jQuery('.check-permissao-personalizadas');
                $.each(permissions, function (key, value) {
                    value.checked = false;
                    jQuery('#'+value.id).iCheck('update');
                    jQuery('#'+value.id).iCheck('uncheck');
                });
            }

        });

        

</script>

@endpush