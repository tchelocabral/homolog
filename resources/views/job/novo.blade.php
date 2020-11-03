@extends('adminlte::page')

@section('title', __('messages.Novo Job'))

@section('content_header')
   {{ Breadcrumbs::render('criar job', $projeto) }} 
@stop

@section('content')

    <div class="row centralizado">
        <h1 class="margemB40">{{ __('messages.Criar Novo Job')}}</h1>
        <form id="form-novo-job" name="form-tipo-imagem" action="{{ route('jobs.store') }}" method="POST" enctype="multipart/form-data">

            {{--security token--}}
            @csrf

            <div class="row">
                {{-- Box Novo Job --}}
                <div class="col-md-4">
                    <div class="box box-solid box-primary com-shadow">
                        <div class="box-header com-borda th-ocean">
                            <h3 class="box-title">{{ __('messages.Selecione o Cliente, o Projeto, as Imagens e o Job a ser feito')}}</h3>
                        </div>
                        <div class="box-body">

                            {{-- Primeira Linha --}}
                            <div class="row div-item-form">
                                {{-- Clientes --}}
                                <div class="col-sm-12 col-md-6">
                                    <p class="detalhe-label"><b>{{ __('messages.Cliente')}}</b></p>
                                    @isset($detalhe)
                                        <p class="info-detalhe-maior">{{ $job->cliente->nome_fantasia ?? __('messages.Não Informado') }}</p>
                                    @else
                                        @isset($projeto)
                                            <input type="hidden" name="cliente_id" value="{{$projeto->cliente->id}}">
                                            <h4 id="nome-cliente">{{$projeto->cliente->nome_fantasia}}</h4>
                                        @else
                                            <select id="combo-clientes" name="cliente_id" class="form-control select2 margemT10" >
                                                <option value="-1">{{ __('messages.Escolha um Cliente')}}</option>
                                                @isset($clientes)
                                                    @unless($clientes)
                                                        <option value="-1">{{ __('messages.Sem Clientes Cadastrados')}}</option>
                                                    @else
                                                        @foreach($clientes as $cli)
                                                            <option value="{{ $cli->id }}" {{ isset($projeto) && $cli->id == $projeto->cliente->id ? ' selected="selected"' : ''  }}>{{ $cli->nome_fantasia }}</option>
                                                        @endforeach
                                                    @endunless
                                                @endif
                                            </select>
                                        @endif  
                                    @endif
                                </div>

                                {{-- Projetos --}}
                                <div class="col-sm-12 col-md-6">
                                    <p class="detalhe-label"><b>{{ __('messages.Projetos do Cliente Selecionado')}}</b></p>
                                    @isset($detalhe)
                                        <p class="info-detalhe-maior">{{ $projeto->cliente->nome_fantasia ?? __('messages.Não Informado') }}</p>
                                    @else
                                         @isset($projeto)
                                            <input type="hidden" name="projeto_id" value="{{encrypt($projeto->id)}}">
                                            <h4 id="nome-projeto">{{$projeto->nome}}</h4>
                                        @else
                                            <select id="combo-projetos" name="projeto_id" class="form-control select2 margemT10"  >
                                                @isset($projeto)
                                                    <option value="{{ $projeto->id }}">{{ $projeto->nome }}</option>
                                                @else
                                                    <option value="-1">{{ __('messages.Selecione o Cliente para carregar seus Projetos')}}</option>
                                                @endif
                                            </select>
                                        @endif
                                    @endif
                                </div>
                            </div>

                            {{-- Segunda Linha --}}
                            <div class="row div-item-form margemT20">
                                {{-- Imagens do Projeto com R_00--}}
                                <div class="col-sm-12 col-md-6">
                                    <p class="detalhe-label"><b>{{ __('messages.Lista de Imagens do Projeto')}}</b></p>
                                    @isset($detalhe)
                                    @else
                                        <select id="combo-imagens" name="imagens[]" class="form-control select2 margemT10" multiple="multiple">
                                            @isset($projeto)
                                                @foreach($imgs_r00 as $img)
                                                    <option value="{{$img->id}}" {{ isset($img_id) && $img_id == $img->id ? 'selected="selected"' : ''}} desc="{{str_replace(" ", "_", $img->descricao)}}">{{ $img->nome . ' - ' . ($img->tipo->grupo->nome ?? '') }}</option>
                                                @endforeach
                                            @else
                                                <option value="-1">{{ __('messages.Selecione o Projeto para carregar as imagens')}}</option>
                                            @endif
                                        </select>
                                    @endif
                                </div>

                                {{-- Tipos de Jobs --}}
                                <div class="col-sm-12 col-md-6">
                                    <p class="detalhe-label"><b>{{ __('messages.Tipos de Job')}}</b></p>
                                    @isset($detalhe)
                                        <p class="info-detalhe-maior">{{ $job->tipo->nome ?? __('messages.Não Informado') }}</p>
                                    @else
                                        <select id="combo-tipos-jobs" name="tipojob_id" class="form-control select2 margemT10">
                                            <option value="-1">{{ __('messages.Escolha um Tipo de Job')}}</option>
                                            @isset($tipos_jobs)
                                                @unless($tipos_jobs)
                                                    <option value="-1">{{ __('messages.Sem Tipos de Job Cadastrados')}}</option>
                                                @else
                                                    @foreach($tipos_jobs as $tipo_job)
                                                        <option value="{{ $tipo_job->id }}">{{$tipo_job->nome }}</option>
                                                    @endforeach
                                                @endunless
                                            @endif
                                        </select>
                                    @endif
                                </div>
                            </div>

                            {{-- Terceira Linha --}}
                            <div class="row div-item-form margemT20">
                                {{-- Nome do Job --}}
                                <div class="col-sm-12 col-md-12">
                                    <p class="detalhe-label"><b>{{ __('messages.Nome do Job')}}</b></p>
                                    <input type="text" class="form-control " name="nome" id="job-nome" />
                                </div>
                            </div>

                            <div class="row div-item-form margemT20">
                                {{-- Tipos de Formatos de entrega --}}
                                <div class="col-sm-12 col-md-6">
                                    <p class="detalhe-label"><b>{{ __('messages.Tipos de Formatos de Entrega') }}</b></p>
                                    @isset($detalhe)
                                        <p class="info-detalhe-maior">{{ $job->tipo->nome ?? 'Não Informado' }}</p>
                                    @else
                                        <select id="combo-tipos-delivery" name="deliveryformat_id" class="form-control select2 margemT10">
                                            <option value="-1">{{ __('messages.Escolha um Tipo de Formato de Entrega') }}</option>
                                            @isset($tipos_delivery)
                                                @unless($tipos_delivery)
                                                    <option value="-1">{{ __('messages.Sem Tipos de Formato de Entrega Cadastrados') }}</option>
                                                @else
                                                    @foreach($tipos_delivery as $tipo_delivery)
                                                        <option value="{{ $tipo_delivery->id }}">{{$tipo_delivery->nome }}</option>
                                                    @endforeach
                                                @endunless
                                            @endif
                                        </select>
                                    @endif
                                </div>
                                {{-- Nome do Job --}}
                                <div class="col-sm-12 col-md-6" id="dados_tipo_delivery">
                                </div>

                            </div>

                            {{-- Quarta Linha --}}
                            <div class="row div-item-form margemT20">
                                @can('define-coordenador-job')
                                    {{-- Coordenadores. Pré selecionada Erika --}}
                                    <div class="col-sm-12 col-md-6">
                                        <p class="detalhe-label"><b>{{ __('messages.Coordenadores')}}</b></p>
                                        @isset($detalhe)
                                            <p class="info-detalhe">{{ $coordenador->name ?? __('messages.Não Informado') }}</p>
                                        @else
                                            <select id="combo-coordenadores" name="coordenador_id" class="form-control select2">
                                                <option value="-1">{{ __('messages.Escolha um Coordenador')}}</option>
                                                @isset($coordenadores)
                                                    @unless($coordenadores)
                                                        <option value="-1">{{ __('messages.Sem Coordenadores Cadastrados')}}</option>
                                                    @else
                                                        {{--todo: pre-selecionar a érika --}}
                                                        @foreach($coordenadores as $c)
                                                            <option value="{{ $c->id }}"
                                                                {{ 
                                                                    ($projeto && $projeto->coordenador && $c->id == $projeto->coordenador->id)  
                                                                    || ($c->email == "erika@mintstudios.com.br")
                                                                    ? 'selected="selected"' : ''  }}>{{$c->name }}</option>
                                                        @endforeach
                                                    @endunless
                                                @endif
                                            </select>
                                        @endif
                                    </div>
                                @endcan

                                {{-- Usuários --}}
                                @can('delega-job')
                                    <div class="col-sm-12 col-md-6">
                                        <p class="detalhe-label"><b>{{ __('messages.Delegar diretamente para') }}</b></p>
                                        @isset($detalhe)
                                            <p class="info-detalhe">{{ $usuario->name ?? __('messages.Não Informado') }}</p>
                                        @else
                                            <select id="combo-usuarios" name="delegado_para" class="form-control select2">
                                                <option value="-1">{{ __('messages.Escolha um Usuário')}}</option>
                                                @isset($usuarios)
                                                    @unless($usuarios)
                                                        <option value="-1">{{ __('messages.Sem Usuários Cadastrados')}}</option>
                                                    @else
                                                        @foreach($usuarios as $user)
                                                            <option value="{{ $user->id }}">{{$user->name }}</option>
                                                        @endforeach
                                                    @endunless
                                                @endif
                                            </select>
                                        @endif
                                    </div>
                                @endcan

                                {{-- Só quem visualiza o mktplace pode publicar job para freela --}}
                                @can('menu-mkt-job')
                                    {{-- Painel de Job --}}
                                    <div class="col-sm-6 col-md-6" style="margin-top: 15px;">
                                        <p class="detalhe-label"><b>{{ __('messages.Painel de Job')}}</b></p>
                                        
                                        <input type="radio" class="form-control" 
                                        name="freela"  checked="checked" value="0" > Interno
                                        <input type="radio" class="form-control  margemL20" 
                                        name="freela" value="1"> Freela
                                    </div>
                                @endcan

                                {{-- Data Entrega --}}
                                <div class="col-sm-6 col-md-6" style="margin-top: 15px;">
                                    <p class="detalhe-label"><b>{{ __('messages.Data de Entrega')}}</b></p>
                                    <input type="date" name="data_prox_revisao" id="data_revisao" class="form-control" min="{{date('Y-m-d')}}" />
                                </div>
                            </div>

                            {{-- Quinta Linha --}}
                            <div class="row div-item-form margemT20">
                                
                                {{-- Valor --}}
                                <div class="col-sm-6 col-md-6">
                                    @can('insere-valor')
                                        <p class="detalhe-label"><b>{{ __('messages.Valor do Job')}}</b></p>
                                        <input id="valor" type="text" data-type="currency" class="form-control" name="valor_job" step="0.01" placeholder="R$ 0,00" />
                                    @endcan
                                </div>
                            </div>

                            {{-- Sexta Linha --}}
                            <div class="row div-item-form margemT20">
                                {{-- Descrição --}}
                                <div class="col-sm-12 col-md-12">
                                    <p class="detalhe-label"><b>{{ __('messages.Descrição do Job')}}</b></p>
                                    @isset($detalhe)
                                    @else
                                        <textarea id="job-descricao" name="descricao" class="form-control editor" rows="10"></textarea>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="box-footer footer-com-padding">
                            <button type="submit" class="btn btn-success pull-right">{{ __('messages.Adicionar Novo Job')}}</button>
                        </div>
                    </div>
                </div>

                {{-- Box Novas Mídias --}}
                @can('adiciona-detalhes-adicionais-job')
                    <div class="col-md-4">
                        <div class="box box-solid box-primary no-border com-shadow">
                            <div class="box-header th-gray com-borda">
                                <h3 class="box-title">{{ __('messages.Detalhes adicionais para o Job')}}</h3>
                            </div>
                            <div class="box-body" id="word-break">
                                {{-- Dados do Tipo de Job Selecionado --}}
                                <div id="div-dados-tipo" class="row div-item-form "></div>
                                {{-- Mídias de Referência do Tipo de Job --}}
                                <div id="div-midias-tipo" class="margemT20"></div>

                                {{-- Referências e Boas Práticas --}}
                                <div class="row div-item-form">
                                    <div class="col-md-12">
                                        <table id="tabela-novas-referencias" class="table">
                                            <thead>
                                                <th colspan="5">{{ __('messages.Novos arquivos de referência e boas práticas')}}</th>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="row div-item-form novo-arquivo-referencia">
                                    {{--Tipo de Arquivo--}}
                                    <div class="col-sm-12 col-md-6">
                                        <p><b>{{ __('messages.Tipo de Arquivo')}}</b></p>
                                        <select id="combo-tipo-arquivo-1" name="novas_ref[tipo_id][]" class="combo-tipo-arquivo form-control select2 margemT10">
                                            @isset($tipos_arquivos)
                                                @unless($tipos_arquivos)
                                                    <option value="">{{ __('messages.Sem Tipos de Arquivos Cadastrados')}}</option>
                                                @else
                                                    @foreach($tipos_arquivos as $tipo)
                                                        <option value="{{ $tipo->id }}">{{$tipo->nome }}</option>
                                                    @endforeach
                                                @endunless
                                            @endif
                                        </select>
                                    </div>

                                    {{--Arquivo Input--}}
                                    <div class="col-sm-12 col-md-12 margemTop">
                                        <p><b>{{ __('messages.Selecione o Arquivo')}}</b></p>
                                        <div class="input-group image-preview">
                                            <input id="arquivo-preview-1" type="text" class="form-control image-preview-filename" disabled="disabled" placeholder="Nenhum arquivo selecionado" />
                                            <span class="input-group-btn">
                                                
                                                <!-- image-preview-input -->
                                                <div class="btn btn-default image-preview-input">
                                                    <span class="glyphicon glyphicon-folder-open"></span>
                                                    <span class="image-preview-input-title">{{ __('messages.Procurar')}}</span>
                                                    <input id="input-arquivo-1" name="arquivos_ref[]" type="file" accept="*" />
                                                </div>
                                                <!-- image-preview-clear button -->
                                                <button type="button" class="btn btn-default image-preview-clear" >
                                                    <span class="glyphicon glyphicon-remove"></span> {{ __('messages.Limpar')}}
                                                </button>
                                            </span>
                                            <input id="arquivo-1" type="file" accept="*" name="arquivos_ref[]" style="position: absolute; top: 0px; right: 1000vw;" /> 
                                        </div>
                                    </div>
                                </div>
                                <div id="linha-botao-add" class="row div-item-form">
                                    <div class="col-md-12">
                                        <a id="add-arquivo-referencia" type="button" class="btn btn-primary btn-add margemT40 pull-right">{{ __('messages.ADD Outro')}}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                
                    </div> 
                @endcan

                {{-- Box Tasks --}}
                <div class="col-md-4">
                    <div class="box box-solid box-primary no-border com-shadow">
                        <div class="box-header th-gray com-borda fundo-verde">
                            <h3 class="box-title">{{ __('messages.Tasks')}}</h3>
                        </div>
                        <div class="box-body">
                            {{-- Tasks --}}
                            <div class="row">
                                <div class="col-sm-12 col-md-12">
                                    <p class="detalhe-label"><b>{{ __('messages.Selecione uma ou mais Tasks para o Job')}}</b></p>
                                    <select id="combo-tasks" name="combo-tasks[]" class="form-control select2" multiple>
                                        @isset($tasks)
                                            @unless($tasks)
                                                <option value="-1">{{ __('messages.Sem Tasks Cadastradas')}}</option>
                                            @else
                                                @foreach($tasks as $task)
                                                    <option value="{{ $task->id }}">{{ $task->nome }}</option>
                                                @endforeach
                                            @endunless
                                        @endif
                                    </select>
                                </div>
                            </div>  
                        
                            <div class="row margemT40">
                                <div class="col-md-12">
                                    {{-- <h3 class="texto-preto">{{ __('messages.Ordem para Execução das Tasks')}}</h3> --}}
                                    <table id="lista-tasks" class="table table-hover table-bordered">
                                        <thead>
                                            <th colspan="2">{{ __('messages.Ordem para Execução das Tasks')}}</th>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>  
                    </div>
                </div>

            {{-- </div>end row --}}

            {{-- <div class="row"> --}}
               
            {{-- </div> --}}

        </form>
    </div>
@stop

@include('app.includes.carregando')

{{-- Controle dos campos --}}
@push('js')
    <script src="{{ asset('js/arquivos.js') }}"></script>
    <script src="{{ asset('js/numeros.js') }}"></script>
    <script src="{{ asset('js/ckeditor.js') }}"></script>

    <script>

        $(document).ready(function() {

            // Projetos por Cliente
            $('#combo-clientes').on('change', function (e) {
                this.value == -1 ? semProjetos() : carregarProjetos(this.value);
            });
            function carregarProjetos(cli){
                var cli_id = {id: cli};
                var url    = "{{ route('cliente.projetos.json') }}";
                $.ajax({
                    url: url,
                    type: 'GET',
                    data: cli_id,
                    beforeSend: function(xhr){
                        zerarProjetos();
                        jQuery('#combo-clientes').prop('disabled', true);
                        // jQuery('#combo-clientes').select2("readonly", true);
                    },
                    success: function (data) {
                        // console.log(data.length);
                        // console.log(data);
                        jQuery('#combo-clientes').prop('disabled', false);
                        // jQuery('#combo-clientes').select2("readonly", false);
                        if(data.length === 0){
                            // console.log('cliente sem projetos');
                            jQuery('#combo-projetos').append('<option value="-1">{{ __("messages.Este Cliente não possui Projetos Cadastrados")}}</option>');
                        }else{
                            data.forEach(criarProjeto);
                        }
                        jQuery('#combo-projetos').change();
                    },
                    error: function(data){
                        semProjetos();
                        jQuery('#combo-clientes').prop('disabled', false);
                        // jQuery('#combo-clientes').select2("readonly", false);
                    },
                    complete: function(){
                        montarNomeJob();
                    }
                });
            }
            function criarProjeto(element, index, array) {
             
                $('#combo-projetos').append('<option value="' + element.id + '">' + element.nome + '</option>');
            }
            function zerarProjetos(){
                $('#combo-projetos').html('');
            }
            function semProjetos(){
                $('#combo-projetos').html('<option value="-1">{{ __("messages.Selecione o Cliente para carregar seus projetos.")}}</option>');
            }

            // Imagens por Projeto
            $('#combo-projetos').on('change', function(e) {
                this.value === '-1' ? semImagens() : carregarImagens(this.value);
            });
            function carregarImagens(proj){
                var proj_id = {id: proj};
                var url    = "{{ route('projeto.imagens.r00') }}";
                $.ajax({
                    url: url,
                    type: 'GET',
                    data: proj_id,
                    beforeSend: function(xhr){
                        zerarImagens();
                        jQuery('#combo-projetos').prop('disabled', true);
                    },
                    success: function (data) {
      
                        jQuery('#combo-projetos').prop('disabled', false);
                        if(data.length === 0){
                            $('#combo-imagens').removeAttr('mult    iple').select2();
                            jQuery('#combo-imagens').append('<option value="-1">{{ __("messages.Este Projeto não possui Imagens com data de Revisão Cadastradas")}}.</option>');
                        }else{
                            $('#combo-imagens').attr('multiple', 'multiple').select2();
                            data.forEach(criarImagem);
                            $('#combo-imagens').focus();
                        }
                    },
                    error: function(data){
                        semImagens();
                        jQuery('#combo-projetos').prop('disabled', false);
                     
                    },
                    complete: function(){
                        montarNomeJob();
                    }
                });
            }
            function criarImagem(element, index, array) {
                
                $('#combo-imagens').append('<option value="' + element.id + '">' + element.nome + '</option>');
            }
            function zerarImagens(){
                $('#combo-imagens').removeAttr('multiple').select2();
                $('#combo-imagens').html('');
            }
            function semImagens(){
                $('#combo-imagens').removeAttr('multiple').select2();
                $('#combo-imagens').html('<option value="-1">{{ __("messages.Selecione o Projeto para carregar as imagens com data de Revisão")}}.</option>');
            }

            // Tipo de Job
            // Evento que dispara no change do select
            $('#combo-tipos-jobs').on('change', function (e) {
                this.value === '-1' ? zerarTipoDeJobSelecionado() : buscarDadosTipoDeJob(this);
            });

             // Tipo de delivery  
            // Evento que dispara no change do select
            $('#combo-tipos-delivery').on('change', function (e) {
                //dados_tipo_delivery
               this.value === '-1' ? zerarTipoDeDeliverySelecionado() : buscarDadosTipoDeDelivery(this);
            });

            // Função para buscar o tipo de delevy que foi selecionado
            function buscarDadosTipoDeDelivery(tipo){
                // Pega o valor do tipo de job selecionado
                var tipo_id = {id: tipo.value};
                // Pega a rota padrão do endpoint com os dados do tipo de job
                var url = "{{ route('tipodelivery.dados') }}";
                // Chamada Ajax
                $.ajax({
                    url: url,
                    type: 'GET',
                    data: tipo_id,
                    // antes de chamar o ajax
                    beforeSend: function(xhr){
                        zerarTipoDeDeliverySelecionado();
                        jQuery('#combo-tipos-delivery').prop('disabled', true);

                    },
                    // se a requisicao for bem-sucedida
                    success: function (data) {
                        jQuery('#combo-tipos-delivery').prop('disabled', false);
                        
                        if(data.length === 0){
                            zerarTipoDeDeliverySelecionado();

                        }else{
                            carregarDadosTipoDeDelivery(data);
                        }
                        // montarNomeJob();
                    },
                    // se der erro
                    error: function(data){
                        zerarTipoDeDeliverySelecionado();
                        jQuery('#combo-tipos-delivery').prop('disabled', false);
                    },
                });
            }


            function carregarDadosTipoDeDelivery(tipo_delivery) {
                
                if(tipo_delivery.campos_personalizados != null){
                    console.log(tipo_delivery.campos_personalizados);
                    Object.entries(tipo_delivery.campos_personalizados).forEach(camposPersonalizadosDelivery);
                }

            }

            function camposPersonalizadosDelivery(campo, index, array) {
                var nome = campo[1].nome.capitalize() ;
                var input = '<b>' + nome + '</b><br><input class="form-control" type="text" name="campos_personalizados[' + campo[1].nome + ']"/>';
                switch(campo[1].tipo){
                    case 'textarea':
                        input = '<b>' + nome + '</b><br><textarea class="form-control" name="campos_personalizados[' + nome + ']"></textarea>';
                        break;
                    case 'checkbox':
                        input = '<label class="margemT10"><input type="checkbox" name="campos_personalizados[' + nome + ']"><span class="margemL10"></span><b>' + nome + '</b><br></label>';
                        break;
                    case 'select':
                        input = '<label class="margemT10" style="color:#707070"><span class=""></span>'+ nome + '</label><br>';
                        input += '<select class="form-control select2" multiple name="job_delivery_value[]" id="select-delivey-format">';
                        input += '<option value="-1">Escolha uma opção</option>';
                        input += selectPersonalizadoDelivery(campo[1]['options']);
                        input +='</select>';
                        break;
                    default:
                        input = '<b>' + nome + '</b><br><input class="form-control" type="text" name="campos_personalizados[' + nome + ']"/>';
                        break;


                }
                $('#dados_tipo_delivery').append('' + input + '');
                $('#select-delivey-format').select2();
            }

            function selectPersonalizadoDelivery(campo){
                var option = "";
                for (let index = 0; index < campo.length; index++) {
                    option += '<option value="'+campo[index]+'">'+campo[index]+'</option>'; campo[index];                    
                }
                return option;
            }

            function zerarTipoDeDeliverySelecionado() {
                $('#dados_tipo_delivery').html('');
            }

            // Função para buscar o tipo de job que foi selecionado
            function buscarDadosTipoDeJob(tipo){
                // Pega o valor do tipo de job selecionado
                var tipo_id = {id: tipo.value};
                // Pega a rota padrão do endpoint com os dados do tipo de job
                var url    = "{{ route('tipojobs.dados') }}";
                // Chamada Ajax
                $.ajax({
                    url: url,
                    type: 'GET',
                    data: tipo_id,
                    // antes de chamar o ajax
                    beforeSend: function(xhr){
                        zerarTipoDeJobSelecionado();
                        jQuery('#combo-tipos-jobs').prop('disabled', true);

                    },
                    // se a requisicao for bem-sucedida
                    success: function (data) {
                        jQuery('#combo-tipos-jobs').prop('disabled', false);
                        
                        if(data.length === 0){
                            zerarTipoDeJobSelecionado();

                        }else{
                            carregarDadosTipoDeJob(data);
                            if(data.tasks !== undefined && data.tasks.length !== 0){
                                setarTasksTipoDeJob(data.tasks);
                            }

                            if(data.midias.length !== 0){
                                $('#div-midias-tipo').append(
                                    '<div class="row div-item-form">' +
                                        '<div class="col-md-12">' +
                                            '<label class="margemT20 margemB20">' +
                                                '<input type="checkbox" id="check01" name="alterar_ref"><span class="margemL10"></span>{{ __("messages.Não quero manter nenhum arquivo padrão do Tipo de Job")}}' +
                                            '</label>' +
                                        '</div>' +
                                    '</div>' +
                                    '<div class="row div-item-form">' +
                                        '<div class="col-md-12">' +
                                            '<table id="tabela-referencias" class="table">' +
                                                '<thead><th colspan="5">{{ __("messages.Mídias de referência e boas práticas do Tipo de Job selecionado")}}</th></thead>' +
                                                '<tbody></tbody>' +
                                            '</table>' +
                                        '</div>' +
                                    '</div>'
                                );
                                data.midias.forEach(carregarMidiasTipo);

                                $("#check01").change(function(){
                                    if($(this).prop("checked")==true) {
                                        $("#tabela-referencias input[type=checkbox]").each(function(){
                                            $(this).prop("checked", false);
                                        });
                                    }

                                })
                            }
                        }
                        // montarNomeJob();
                    },
                    // se der erro
                    error: function(data){
                        zerarTipoDeJobSelecionado();
                        jQuery('#combo-tipos-jobs').prop('disabled', false);
                    },
                    // quando estiver tudo completo
                    complete: function(){
                        montarNomeJob();
                    }
                });
            }

            function carregarDadosTipoDeJob(tipo_job) {
                
                // console.log(tipo_job);
                if(tipo_job.descricao !== null){
                    $('#div-dados-tipo').append('<div class="col-md-12"><h4>' + tipo_job.descricao + '</h4></div><hr class="col-md-12 margemB5 margemT5">');
                }
                if(tipo_job.revisao){
                    $('#div-dados-tipo').append('<div class="col-md-12"><h4>{{ __("messages.Este é um Job de Revisão")}}</h4></div><hr class="col-md-12 margemB5 margemT5">');
                }
                if(tipo_job.finalizador){
                    $('#div-dados-tipo').append('<div class="col-md-12"><h4>{{ __("messages.Este Job define o Finalizador da Imagem")}}</h4></div><hr class="col-md-12 margemB5 margemT5">');
                }
                if(tipo_job.gera_custo){
                    $('#div-dados-tipo').append('<div class="col-md-12"><h4>{{ __("messages.Este Job gera Custo Extra ao Cliente")}}</h4></div><hr class="col-md-12 margemB5 margemT5">');
                }
                if(tipo_job.campos_personalizados != null){
                    Object.entries(tipo_job.campos_personalizados).forEach(camposPersonalizados);
                }
                if(tipo_job.boas_praticas != null){
                     $('#div-dados-tipo').append('<hr class="col-md-12 margemT20 margemB5"><div class="col-md-12"><p><b>{{ __("messages.Boas Práticas")}}:</b></p><h4>' + tipo_job.boas_praticas + '</h4></div><hr class="col-md-12 margemB5 margemT5">');
                }
            }

            function setarTasksTipoDeJob(tasks) {
                var tasks_ids = [];
                // Percorre o objeto
                Object.entries(tasks).forEach(function(task, index, array){
                    tasks_ids.push(tasks[index].id);
                });
                // Setando os valores do select
                jQuery('#combo-tasks').val(tasks_ids);   
                // Disparando o evento do select2 de seleção
                jQuery('#combo-tasks').trigger('change.select2');
                // Disparando o evento do select2 para ordenar tasks
                Object.entries(tasks).forEach(function(task, index, array){
                    jQuery('#combo-tasks').trigger({
                        type: 'select2:select',
                        params:{
                            data: {
                                id:   tasks[index].id,
                                text: tasks[index].nome
                            }
                        }
                    });    
                });
            }

            function camposPersonalizados(campo, index, array) {
                var input = '<b>' + campo[1].nome + '</b><br><input class="form-control" type="text" name="campos_personalizados[' + campo[1].nome + ']"/>';
                switch(campo[1].tipo){
                    case 'textarea':
                        input = '<b>' + campo[1].nome + '</b><br><textarea class="form-control" name="campos_personalizados[' + campo[1].nome + ']"></textarea>';
                        break;
                    case 'checkbox':
                        input = '<label class="margemT10"><input type="checkbox" name="campos_personalizados[' + campo[1].nome + ']"><span class="margemL10"></span><b>' + campo[1].nome + '</b><br></label>';
                        break;
                    default:
                        input = '<b>' + campo[1].nome + '</b><br><input class="form-control" type="text" name="campos_personalizados[' + campo[1].nome + ']"/>';
                        break;
                }
                $('#div-dados-tipo').append('<div class="col-sm-12 col-md-6">' + input + '</div>');
            }

            function carregarMidiasTipo(element, index, array) {
                $('#tabela-referencias > tbody:last-child').append(
                    '<tr>' +
                        '<td><input type="checkbox" name="midias_ref[]" value="' + element.id + '" checked="checked"></td>' +
                        '<td>' + element.tipo_arquivo.nome + '</td>' +
                        '<td>' + element.caminho + '</td>' +
                        '<td>' +
                            '<div class="dropdown">' +
                                '<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">' +
                                    '<i class="fa fa-cog" aria-hidden="true"></i>' +
                                '</a>' +
                                '<ul class="dropdown-menu" aria-labelledby="dropdownMenu' + element.id + '">' +
                                    '<li>' +
                                        '<a href="' + url_base_storage + element.caminho + '" download>' +
                                            '<i class="fa fa-download" aria-hidden="true"></i> Baixar' +
                                        '</a>' +
                                    '</li>' +
                                    '<li>'  +
                                        '<a href="' + url_base_storage + element.caminho + '" target="_blank">' +
                                            '<i class="fa fa-eye" aria-hidden="true"></i> Visualizar' +
                                        '</a>' +
                                    '</li>' +
                                '</ul>' +
                            '</div>' +
                        '</td>' +
                    '</tr>'
                );
            }

            function zerarTipoDeJobSelecionado() {
                $('#div-dados-tipo').html('');
                $('#div-midias-tipo').html('');
                $('#tabela-referencias > tbody').html('');
                $('#combo-tasks').val(0);
                $('#combo-tasks').trigger('change.select2');
                // Zera as tasks que estavam pré-selecionadas
                zeraOrdemDeTasksSelecionadas();
            }

            // Imagens change
            $('#combo-imagens').on('select2:close', function (e) { montarNomeJob(e); });

            // Arquivos de Referência
            $('#combo-tipo-arquivo-1').on('change', function () {
                $('#nome-arquivo-1').val(this.selectedOptions[0].text);
            });
            $('#add-arquivo-referencia').on('click', function (e) {
                addArquivoReferencia();
            });
            function addArquivoReferencia(){
                var numero_linha = $('.combo-tipo-arquivo').length + 1;
                var nova_linha   = montarLinhaArquivoReferencia(numero_linha);
                $('#linha-botao-add').before(nova_linha);
                $('#combo-tipo-arquivo-1 option').clone().appendTo('#combo-tipo-arquivo-' + numero_linha);
                $('#combo-tipo-arquivo-' + numero_linha).on('change', function () {
                    $('#nome-arquivo-' + numero_linha).val(this.selectedOptions[0].text);
                });
                transformaFilePreview(); //js/arquivos.js
            }
            function montarLinhaArquivoReferencia(numero_linha){
                var linha = '<hr><div class="row div-item-form novo-arquivo-referencia">';
                linha    +=     '<div class="col-sm-12 col-md-6">';
                linha    += '       <p><b>Tipo de Arquivo</b></p>';
                linha    += '       <select id="combo-tipo-arquivo-' + numero_linha +'" name="novas_ref[tipo_id][]" class="combo-tipo-arquivo form-control select2 margemT10"></select>';
                linha    += '   </div>';
                linha    += '   <div class="col-sm-12 col-md-12 margemTop">';
                linha    += '       <p><b>Selecione o Arquivo</b></p>';
                linha    += '       <div class="input-group image-preview">';
                linha    += '           <input id="arquivo-preview-' + numero_linha + '" type="text" class="form-control image-preview-filename" disabled="disabled" placeholder="{{ __("messages.Nenhum arquivo selecionado")}}">';
                linha    += '    <span class="input-group-btn">';
                linha    += '       <div class="btn btn-default image-preview-input">';
                linha    += '            <span class="glyphicon glyphicon-folder-open"></span>';
                linha    += '               <span class="image-preview-input-title">Procurar</span>';
                linha    += '                   <input id="input-arquivo-' + numero_linha + '" name="arquivos_ref[]" type="file" accept="*" />';
                linha    += '        </div>';

                  linha    += '             <button type="button" class="btn btn-default image-preview-clear">';
                linha    += '     <span class="glyphicon glyphicon-remove"></span> {{ __("messages.Limpar")}}';
                linha    += '               </button>';
                linha    += '      </span>';
                linha    += '           <input id="arquivo-' + numero_linha + '" type="file" accept="*" name="arquivos_ref[]" style="position: absolute; top: 0px; right: 1000vw; ">';
                linha    += '       </div>';
                linha    += '   </div>';
                linha    += '</div>';
                return linha;
            }
            // Nome do Job
            function montarNomeJob(e = null) {
                
                var nome_projeto = '';
                var cliente      = '';
                var tem_imagem   = false;

                if($('input[name="cliente_id"]').val() != -1 ){
                    if($('#nome-cliente').text()){
                        cliente = $('#nome-cliente').text().split(" ")[0];    
                    }else{
                        cliente = $('#combo-clientes option:selected').text().split(" ")[0];    
                    }
                    nome_projeto = cliente.replace(" ","_") + '_';

                    if($('input[name="projeto_id"]').val()){
                        var projeto   = '';
                        if($('#nome-projeto').text()){
                            projeto = $('#nome-projeto').text().replace(" ", "_");    
                        }else{
                            projeto = $('#combo-projetos option:selected').text().replace(/ /g,"_");    
                        }    
                        nome_projeto += projeto + '_';
                    }

                    if( $('#combo-imagens').val()           != null 
                        && $('#combo-imagens').val()        != -1 
                        && $('#combo-imagens').val().length > 0
                    ){
                        tem_imagem = true;
                        if(e!=null){
                            nome_projeto += e.currentTarget.selectedOptions[0].text.replace(/ - /g, "_").replace(/ /g, "_");
                            nome_projeto += "_" + e.currentTarget.selectedOptions[0].attributes.desc.value;
                        } else {
                            nome_projeto += $('#combo-imagens')[0].selectedOptions[0].text.replace(/ - /g, "_").replace(/ /g, "_");
                            nome_projeto += "_" + $('#combo-imagens')[0].selectedOptions[0].attributes.desc.value;
                        }
                        if($('#combo-imagens').val().length > 1){
                            nome_projeto += '_multi';
                        }
                    }
                    if($('#combo-tipos-jobs').val() != -1){
                        var job = $('#combo-tipos-jobs option:selected').text().replace(/ /g, "_");
                        nome_projeto += tem_imagem ? '_' + job : job;
                    }
                }
                $('#job-nome').val(nome_projeto.toLowerCase());
            }

            /////// Ordem das Tasks //////////

            function zeraOrdemDeTasksSelecionadas(){
                $('#lista-tasks tr.task-arrastavel').remove();    
            }
            function geraHTMLTaskParaOrdenar(task_id, task){
                $('#lista-tasks > tbody:last-child').append(
                    '<tr id="task-' + task_id + '" class="task-arrastavel">' +
                        '<td class="texto-centralizado">' + 
                            '<i class="fa fa-bars"></i>' +
                        '</td>' +  

                        '<td>' + task + 
                            '<input type="hidden" value="' + task_id + '" name="tasks[]" />' +
                        '</td>' + 
                    '</tr>'
                );
            }

            $('#combo-tasks').on("select2:select", function(e) { 
                var task_id = e.params.data.id;
                var task    = e.params.data.text;
                geraHTMLTaskParaOrdenar(task_id, task);
            });

            $('#combo-tasks').on("select2:unselect", function(e) { 

                jQuery("#task-" + e.params.data.id).remove();
            });

            // Drag and Drop Table Row
            $("#lista-tasks > tbody").sortable({cursor: "grabbing"});

        }); //end document ready


        ClassicEditor
			.create( document.querySelector( '.editor' ), {
				
				toolbar: {
					items: [
						'heading',
						'|',
						'bold',
						'italic',
						'link',
						'bulletedList',
						'numberedList',
						'|',
						'indent',
						'outdent',
						'|',
						'blockQuote',
						'undo',
						'redo'
					]
				},
				language: 'pt-br',
				table: {
					contentToolbar: [
						'tableColumn',
						'tableRow',
						'mergeTableCells'
					]
				},
				licenseKey: '',
				
			} )
			.then( editor => {
				window.editor = editor;
				
			} )
			.catch( error => {
				console.error( 'Oops, something went wrong!' );
				console.error( 'Please, report the following error on https://github.com/ckeditor/ckeditor5/issues with the build id and the error stack trace:' );
				console.warn( 'Build id: 3qxixuj0jjak-qrolc6ajm7ow' );
				console.error( error );
            } );
            
    </script>
    
@endpush