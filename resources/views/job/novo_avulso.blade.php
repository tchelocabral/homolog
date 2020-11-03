@extends('adminlte::page')

@section('title', __('messages.Novo Job') . ' ')

@section('content_header')
   {{-- {{ Breadcrumbs::render('criar job', $projeto) }}  --}}
@stop

@section('content')

    <input type="hidden" id="mod-tit" value="{{ __('modal.Você está publicando um novo Job') }}">
    <input type="hidden" id="mod-tit-tasks" value="{{ __('modal.Tarefas do Job') }}">
    <input type="hidden" id="mod-btn-ok" value="{{ __('modal.Publicar Job') }}">
    <input type="hidden" id="mod-btn-pagamento" value="{{ __('modal.Ir para pagamento') }}">
    <input type="hidden" id="mod-btn-cancel" value="{{ __('modal.Cancelar') }}">
    <input type="hidden" id="mod-tit-valor" value="{{ __('modal.Valor') }}">
    <input type="hidden" id="mod-tit-proposta" value="{{ __('modal.Aceita Propostas até') }}">
    <input type="hidden" id="mod-taxa" value="{{ __('modal.Taxas') }}">
    <input type="hidden" id="mod-tit-prazo" value="{{ __('modal.Deadline') }}">
    <input type="hidden" id="mod-li-termos" value="{{ __('modal.Li e concordo com os') }}">
    <input type="hidden" id="mod-termos" value="{{ __('modal.Termos de Uso') }}">
    <input type="hidden" id="mod-no-termos" value="{{ __('modal.Você deve concordar com os termos de uso da plataforma para pegar um Job') }}">
    {{-- <input type="hidden" id="url-modal" value="{{ route('job.publicador.view.pagamento') }}"> --}}

    <div class="row centralizado">
        <h1 class="margemB40">{{ __('messages.Publicar Novo Job')}}</h1>
        {{-- {{ route('job.avulso.store') }} --}}
        <form id="form-novo-job" name="form-tipo-imagem" action="{{ route('job.avulso.store') }}" method="POST" enctype="multipart/form-data">
            <input type="hidden" value="{{ route('job.avulso.store') }}" id="job-url">
            <input type="hidden" value="{{ route('termos.de.uso') }}" id="rota-termos">
            {{--security token--}}
            @csrf

            <div class="row">
                {{-- Box Novo Job --}}
                <div class="col-md-4">
                    <div class="box box-solid box-primary com-shadow paddingB40">
                        <div class="box-header th-ocean">
                            <h3 class="box-title">{{ __('messages.Detalhes do Job a ser feito')}}</h3>
                        </div>
                        <div class="box-body">
                            {{-- Primeira Linha --}}
                            <div class="row div-item-form">
                                
                                {{-- Clientes --}}
                                {{-- <div class="col-sm-12 col-md-6">
                                    <p class="detalhe-label"><b>Cliente</b></p>
                                        <select id="combo-clientes" name="cliente_id" class="form-control select2 margemT10" >
                                            <option value="-1">Escolha um Cliente</option>
                                            @isset($clientes)
                                                @unless($clientes)
                                                    <option value="-1">Sem Clientes Cadastrados</option>
                                                @else
                                                    @foreach($clientes as $cli)
                                                        <option value="{{ $cli->id }}">{{ $cli->nome_fantasia }}</option>
                                                    @endforeach
                                                @endunless
                                            @endif
                                        </select>
                                    
                                 </div> --}}

                                {{-- Tipos de Jobs --}}
                                <div class="col-sm-12 col-md-6">
                                    <p class="detalhe-label"><b>{{ __('messages.Tipos de Job') }}</b></p>
                                    @isset($detalhe)
                                        <p class="info-detalhe-maior">{{ $job->tipo->nome ?? 'Não Informado' }}</p>
                                    @else
                                        <select id="combo-tipos-jobs" name="tipojob_id" class="form-control select2 margemT10">
                                            <option value="-1">{{ __('messages.Escolha um Tipo de Job') }}</option>
                                            @isset($tipos_jobs)
                                                @unless($tipos_jobs)
                                                    <option value="-1">{{ __('messages.Sem Tipos de Job Cadastrados') }}</option>
                                                @else
                                                    @foreach($tipos_jobs as $tipo_job)
                                                        <option value="{{ $tipo_job->id }}">{{$tipo_job->nome }}</option>
                                                    @endforeach
                                                @endunless
                                            @endif
                                        </select>
                                    @endif

                                    <input type="hidden" value={{ $qtd_job }} id="qtd_job">
                                    <input type="hidden" value={{ $user_id }} id="publicador_id">
                                </div>
                                
                                {{-- Nome do Job --}}
                                <div class="col-sm-12 col-md-6">
                                    <p class="detalhe-label"><b>{{ __('messages.Nome do Job') }}</b></p>
                                    <input type="text" class="form-control " name="nome" id="job-nome-lab" disabled />
                                    <input type="hidden" class="form-control " name="nome" id="job-nome" />
                                </div>

                            </div>


                            <div class="row div-item-form">
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

                            {{-- Terceira Linha --}}
                            
                            <div class="row div-item-form margemT20">
                                {{-- Coordenadores --}}
                                @can('define-coordenador-job')
                                    <div class="col-sm-12 col-md-6">
                                        <p class="detalhe-label"><b>{{ __('messages.Coordenadores') }}</b></p>
                                        @isset($detalhe)
                                            <p class="info-detalhe">{{ $coordenador->name ?? 'Não Informado' }}</p>
                                        @else
                                            <select id="combo-coordenadores" name="coordenador_id" class="form-control select2">
                                                <option value="-1">{{ __('messages.Escolha um Coordenador') }}</option>
                                                @isset($coordenadores)
                                                    @unless($coordenadores)
                                                        <option value="-1">{{ __('messages.Sem Coordenadores Cadastrados') }}</option>
                                                    @else
                                                        @foreach($coordenadores as $c)
                                                            <option value="{{ $c->id }}">{{$c->name }}</option>
                                                        @endforeach
                                                    @endunless
                                                @endif
                                            </select>
                                        @endif
                                    </div>
                                @endcan

                                {{-- Delegado para --}}
                                @can('delega-job')
                                    <div class="col-sm-12 col-md-6">
                                        <p class="detalhe-label"><b>{{ __('messages.Delegar diretamente para') }} 
                                        </b></p>                                        
                                        @isset($detalhe)
                                            <p class="info-detalhe">{{ $usuario->name ?? 'Não Informado' }}</p>
                                        @else
                                            <select id="combo-usuarios" name="delegado_para" class="form-control select2">
                                                <option value="-1">{{ __('messages.Escolha um Usuário') }}</option>
                                                @isset($usuarios)
                                                    @unless($usuarios)
                                                        <option value="-1">{{ __('messages.Sem Usuários Cadastrados') }}</option>
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

                               {{-- Painel de Job --}}
                                @if($job_publicador)
                                    <input type="hidden" name="freela" value="1">
                                @else
                                    <div class="col-sm-6 col-md-6" style="margin-top: 15px;">
                                        <p class="detalhe-label"><b>{{ __('messages.Painel de Job') }}</b></p>
                                        @if(!$job_publicador)
                                        <input type="radio" class="form-control" 
                                        name="freela"  checked="checked" value="0" > {{ __('messages.Interno') }} 
                                        @endif
                                        <input type="radio" class="form-control margemL20" 
                                        name="freela" value="1" > {{ __('messages.Freela') }}
                                    </div>
                                @endif

                                {{-- Data Entrega --}}
                                <div class="col-sm-6 col-md-6" >
                                    <p class="detalhe-label"><b>{{ __('messages.Data de Entrega') }}</b></p>
                                    <input type="date" name="data_prox_revisao" id="data_revisao" class="form-control" min="{{date('Y-m-d')}}" />
                                </div>

                                 {{-- Solicitar proposta --}}
                                 <div class="col-sm-6 col-md-6" style="margin-top: 15px; height:50px">
                                    <p class="detalhe-label"><b>{{ __('messages.Avaliar Candidatos') }}</b></p>
                                    <input type="checkbox" id="avaliar-perfil" value="0" class="id-item-arquivo" name="avaliar_perfil">
                                </div>    

                                @can('insere-valor')
                                    {{-- Valor --}}
                                    <div class="col-sm-6 col-md-6" style="margin-top: 15px;" id="div-valor">
                                            <p class="detalhe-label"><b>{{ __('messages.Valor do Job') }}</b></p>
                                            <input id="valor-job" type="text" data-type="currency" class="form-control" name="valor_job" step="0.01" placeholder="R$ 0,00" />
                                            <p id="valor-desconto" class="detalhe-label"></p>
                                            <input type="hidden" id="taxa-real" data-type="currency" value="">
                                            <input type="hidden" name="taxa" id="taxa" value="{{ $configuracoes->taxa_adm_job }}">
                                            <input type="hidden" name="valor_freela" id="valor-freela" value="">
                                            <input type="hidden" name="valor_plataforma" id="valor-plataforma" value="">
                                    </div>
                                @endcan
                            </div>

                            <div class="row div-item-form margemT20">              
                                {{-- Solicitar proposta --}}
                                <div class="col-sm-6 col-md-6" style="margin-top: 15px; height:50px">
                                    <p class="detalhe-label"><b>{{ __('messages.Solicitar Proposta') }}</b></p>
                                    <input type="checkbox" id="solicita-proposta" value="0" class="id-item-arquivo" name="solicita_proposta">
                                </div>                               
                                
                                {{-- Data para proposta --}}
                                <div class="col-sm-6 col-md-6 invisivel" style="margin-top: 15px;" id="div-proposta">
                                    <p class="detalhe-label"><b>{{ __('messages.Limite para Proposta') }}</b></p>
                                    <input type="date" name="data_limite" id="data-limite" class="form-control" min="{{date('Y-m-d')}}" />
                                </div>

                                    
                            </div>
                            {{-- Quarta Linha --}}
                            <div class="row div-item-form margemT10">
                               
                                {{--Arquivo Input--}}


                                <div class="col-sm-12 col-md-12 margemTop">
                                    <p><b>{{ __('messages.Selecione imagem para o Job') }}</b></p>
                                    <div class="input-group image-preview image-upload">
                                        <input id="arquivo-preview-1" type="text" class="form-control image-preview-filename" disabled="disabled" placeholder="{{ __('messages.Nenhum arquivo selecionado') }}" />
                                        <span class="input-group-btn">
                                            <!-- image-preview-clear button -->
                                            <button type="button" class="btn btn-default image-preview-clear" >
                                                <span class="glyphicon glyphicon-remove"></span> {{ __('messages.Limpar') }}
                                            </button>
                                            <!-- image-preview-input -->
                                            <div class="btn btn-default image-preview-input">
                                                <span class="glyphicon glyphicon-folder-open"></span>
                                                <span class="image-preview-input-title">{{ __('messages.Procurar') }}</span>
                                                <input id="input-thumb-1" name="thumb_ref" type="file" accept="image/x-png, image/jpeg, image/gif" />
                                            </div>
                                        </span>
                                        {{-- <input id="thumb-1" type="file" accept="*" name="thumb_ref" style="position: absolute; top: 0px; right: 1000vw;" />  --}}
                                    </div>
                                </div>

                                
                                {{-- Descrição --}}
                                <div class="col-sm-12 col-md-12" style="margin-top: 15px;">
                                    <p class="detalhe-label"><b>{{ __('messages.Descrição do Job') }}</b></p>
                                    @isset($detalhe)
                                    @else
                                        <textarea id="job-descricao" name="descricao" class="form-control editor" rows="10"></textarea>
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                
                {{-- Box Novas Mídias --}}
                @can('adiciona-detalhes-adicionais-job')
                    <div class="col-md-4">
                        <div class="box box-solid box-primary no-border com-shadow">
                            <div class="box-header th-gray ">
                                <h3 class="box-title">{{ __('messages.Detalhes adicionais para o Job') }}</h3>
                            </div>
                            <div class="box-body" id="word-break">
                                
                                {{-- Dados do Tipo de Job Selecionado --}}
                                <div id="div-dados-tipo" class="row div-item-form margemB20"></div>
                                
                                {{-- Mídias de Referência do Tipo de Job --}}
                                {{-- <div id="div-midias-tipo" class="margemT20"></div>  --}}

                                {{-- Referências e Boas Práticas --}}
                                <div class="row div-item-form">
                                    <div class="col-md-12">
                                        <table id="tabela-novas-referencias" class="table borda-cinza fundo-cinza">
                                            <thead>
                                                <th colspan="5">{{ __('messages.Novos arquivos de referência e boas práticas') }}</th>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="row div-item-form novo-arquivo-referencia">
                                    {{--Tipo de Arquivo--}}
                                    <div class="col-sm-12 col-md-6">
                                        <p><b>{{ __('messages.Tipo de Arquivo') }}</b></p>
                                        <select id="combo-tipo-arquivo-1" name="novas_ref[tipo_id][]" class="combo-tipo-arquivo form-control select2 margemT10">
                                            @isset($tipos_arquivos)
                                                @unless($tipos_arquivos)
                                                    <option value="">{{ __('messages.Sem Tipos de Arquivos Cadastrados') }}</option>
                                                @else
                                                    @foreach($tipos_arquivos as $tipo)
                                                        <option value="{{ $tipo->id }}">{{ __('messages.' . $tipo->nome) }}</option>
                                                    @endforeach
                                                @endunless
                                            @endif
                                        </select>
                                    </div>

                                    {{--Arquivo Input--}}
                                    <div class="col-sm-12 col-md-12 margemTop">
                                        <p><b>{{ __('messages.Selecione o Arquivo') }}</b></p>
                                        <div class="input-group image-preview">
                                            <input id="arquivo-preview-1" type="text" class="form-control image-preview-filename" disabled="disabled" placeholder="{{ __('messages.Nenhum arquivo selecionado') }}" />
                                            <span class="input-group-btn">
                                                
                                                <!-- image-preview-input -->
                                                <div class="btn btn-default image-preview-input">
                                                    <span class="glyphicon glyphicon-folder-open"></span>
                                                    <span class="image-preview-input-title">{{ __('messages.Procurar') }}</span>
                                                    <input id="input-arquivo-1" name="arquivos_ref[]" type="file" accept="*" />
                                                </div>
                                                <!-- image-preview-clear button -->
                                                <button type="button" class="btn btn-default image-preview-clear" >
                                                    <span class="glyphicon glyphicon-remove"></span> {{ __('messages.Limpar') }}
                                                </button>
                                            </span>
                                            <input id="arquivo-1" type="file" accept="*" name="arquivos_ref[]" style="position: absolute; top: 0px; right: 1000vw;" /> 
                                        </div>
                                    </div>
                                </div>

                                <div id="linha-botao-add" class="row div-item-form">
                                    <div class="col-md-12">
                                        <a id="add-arquivo-referencia" type="button" class="btn btn-primary btn-add margemT40 pull-right">
                                            <i class="fa fa-plus"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> 
                @endcan

                {{-- Box Tasks --}}
                <div class="col-md-4">
                    <div class="box box-solid box-primary no-border com-shadow">
                        <div class="box-header fundo-verde ">
                            <h3 class="box-title">{{ __('messages.Tasks') }}</h3>
                        </div>
                        <div class="box-body">
                            {{-- Tasks --}}
                            <div class="row">
                                <div class="col-sm-12 col-md-12">
                                    <p class="detalhe-label"><b>{{ __('messages.Selecione uma ou mais Tasks para o Job') }}</b></p>
                                    <select id="combo-tasks" name="combo-tasks[]" class="form-control select2 col-md-6" multiple>
                                        @isset($tasks)
                                            @unless($tasks)
                                                <option value="-1">{{ __('messages.Sem Tasks Cadastradas') }}</option>
                                            @else
                                                @foreach($tasks as $task)
                                                    <option value="{{ $task->id }}">{{ $task->nome }}</option>
                                                @endforeach
                                            @endunless
                                        @endif
                                    </select>
                                </div>
                            </div>  
                        
                            <div class="row">
                                <div class="col-md-12">
                                    <h3 class="texto-preto">{{ __('messages.Ordem para Execução das Tasks') }}</h3>
                                    <table class="table table-hover table-bordered" id="lista-tasks">
                                         <thead>
                                            <tr>
                                                <th colspan="2">{{ __('messages.Tasks') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfooter><tr></tr></tfooter>
                                    </table>
                                </div>
                            </div>
                        </div>  
                        <div class="box-footer footer-com-padding borda-t-cinza">
                            <!-- <button type="submit" class="btn btn-success pull-right">Adicionar Novo Job</button> -->
                            <button id="publicar-job" class="btn btn-success pull-right acao-avulso-job margemR5" value="" data-id="" data-rota="" name="btnPegaJob">{{ __('messages.Publicar Job') }}</button>


                        </div>
                    </div>
                </div>
               
            </div>{{-- end row --}}
            
            {{-- <div class="row"> --}}
                    {{-- Box Novas Mídias --}}
                    {{-- @can('adiciona-detalhes-adicionais-job') --}}
                    {{-- <div class="col-md-6"> --}}
                        {{-- <div class="box box-solid box-primary no-border com-shadow"> --}}
                            {{-- <div class="box-header th-gray com-borda"> --}}
                                {{-- <h3 class="box-title">Detalhes adicionais para o Job</h3> --}}
                            {{-- </div> --}}
                            {{-- <div class="box-body" id="word-break"> --}}
                                
                                {{-- Dados do Tipo de Job Selecionado --}}
                                {{-- <div id="div-dados-tipo" class="row div-item-form margemB20"></div> --}}
                                
                                {{-- Mídias de Referência do Tipo de Job --}}
                                {{-- <div id="div-midias-tipo" class="margemT20"></div>  --}}

                                {{-- Referências e Boas Práticas --}}
                                {{-- <div class="row div-item-form"> --}}
                                    {{-- <div class="col-md-12"> --}}
                                        {{-- <table id="tabela-novas-referencias" class="table borda-cinza fundo-cinza"> --}}
                                              {{-- <thead> --}}
                                                {{-- <th colspan="5">Novos arquivos de referência e boas práticas</th> --}}
                                              {{-- </thead> --}}
                                            {{-- <tbody> --}}
                                            {{-- </tbody> --}}
                                        {{-- </table> --}}
                                    {{-- </div> --}}
                                {{-- </div> --}}

                                {{-- <div class="row div-item-form novo-arquivo-referencia"> --}}
                                     {{--Tipo de Arquivo--}}
                                    {{-- <div class="col-sm-12 col-md-6">
                                        <p><b>Tipo de Arquivo</b></p>
                                        <select id="combo-tipo-arquivo-1" name="novas_ref[tipo_id][]" class="combo-tipo-arquivo form-control select2 margemT10">
                                            @isset($tipos_arquivos)
                                                @unless($tipos_arquivos)
                                                    <option value="">Sem Tipos de Arquivos Cadastrados</option>
                                                @else
                                                    @foreach($tipos_arquivos as $tipo)
                                                        <option value="{{ $tipo->id }}">{{$tipo->nome }}</option>
                                                    @endforeach
                                                @endunless
                                            @endif
                                        </select>
                                    </div> --}}

                                    {{--Arquivo Input--}}
                                    {{-- <div class="col-sm-12 col-md-12 margemTop">
                                        <p><b>Selecione o Arquivo</b></p>
                                        <div class="input-group image-preview">
                                            <input id="arquivo-preview-1" type="text" class="form-control image-preview-filename" disabled="disabled" placeholder="Nenhum arquivo selecionado" />
                                            <span class="input-group-btn"> --}}
                                                
                                                <!-- image-preview-input -->
                                                {{-- <div class="btn btn-default image-preview-input">
                                                    <span class="glyphicon glyphicon-folder-open"></span>
                                                    <span class="image-preview-input-title">Procurar</span>
                                                    <input id="input-arquivo-1" name="arquivos_ref[]" type="file" accept="*" />
                                                </div> --}}
                                                <!-- image-preview-clear button -->
                                                {{-- <button type="button" class="btn btn-default image-preview-clear" >
                                                    <span class="glyphicon glyphicon-remove"></span> Limpar
                                                </button>
                                            </span>
                                            <input id="arquivo-1" type="file" accept="*" name="arquivos_ref[]" style="position: absolute; top: 0px; right: 1000vw;" /> 
                                        </div>
                                    </div>
                                </div> --}}

                                {{-- <div id="linha-botao-add" class="row div-item-form">
                                    <div class="col-md-12">
                                        <a id="add-arquivo-referencia" type="button" class="btn btn-primary btn-add margemT40 pull-right">ADD Outro</a>
                                    </div>
                                </div>
                            </div> --}}
                        {{-- </div> --}}
                    {{-- </div>  --}}
                    {{-- @endcan --}}
            {{-- </div> --}}
            {{-- <input type="submit" value="abrir"> --}}
        </form>

        {{-- <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
            <input type="hidden" name="cmd" value="_xclick">
            <input type="hidden" name="business" value="mestre.urbano@gmail.com">
            <input type="hidden" name="lc" value="BR">
            <input type="hidden" name="item_name" value="Job">
            <input type="hidden" name="item_number" value="123456789">
            <input type="hidden" name="amount" value="100.00">
            <input type="hidden" name="currency_code" value="BRL">
            <input type="hidden" name="button_subtype" value="services">
            <input type="hidden" name="no_note" value="0">
            <input type="hidden" name="cn" value="Adicionar instruções especiais para o vendedor:">
            <input type="hidden" name="no_shipping" value="2">
            <input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynowCC_LG.gif:NonHosted">
            <input type="image" src="https://www.paypalobjects.com/pt_BR/BR/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - A maneira fácil e segura de enviar pagamentos online!">
            <img alt="" border="0" src="https://www.paypalobjects.com/pt_BR/i/scr/pixel.gif" width="1" height="1">
            </form> --}}
            
    </div>


@stop

@include('app.includes.carregando')

{{-- Controle dos campos --}}
@push('js')

    
    <script src="{{ asset('js/imagens.js') }}"></script>
    <script src="{{ asset('js/numeros.js') }}"></script>
    <script src="{{ asset('js/utils.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/avulso_job.js') }}"></script>
    <script src="{{ asset('js/ckeditor.js') }}"></script>
    <script>

        $(document).ready(function() {

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



            // Função para buscar o tipo de job que foi selecionado
            function buscarDadosTipoDeJob(tipo){
                // Pega o valor do tipo de job selecionado
                var tipo_id = {id: tipo.value};
                // Pega a rota padrão do endpoint com os dados do tipo de job
                var url = "{{ route('tipojobs.dados') }}";
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
                                                '<input type="checkbox" id="check01" name="alterar_ref"><span class="margemL10"></span>Não quero manter nenhum arquivo padrão do Tipo de Job' +
                                            '</label>' +
                                        '</div>' +
                                    '</div>' +
                                    '<div class="row div-item-form">' +
                                        '<div class="col-md-12">' +
                                            '<table id="tabela-referencias" class="table">' +
                                                '<thead><th colspan="5">Mídias de referência e boas práticas do Tipo de Job selecionado</th></thead>' +
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
            //TODO: CONFERIR SE Carrega apenas os campos personalizados para o job Avulso
            function carregarDadosTipoDeJob(tipo_job) {
                
                console.log(tipo_job);
                if(tipo_job.descricao !== null){
                    $('#div-dados-tipo').append('<div class="card-job-detalhes"><h4 class="margemB5 margemT5">' + tipo_job.descricao + '</h4></div>');
                }
                if(tipo_job.revisao){
                    $('#div-dados-tipo').append('<div class="card-job-detalhes"><h4 class="margemB5 margemT5">Este é um Job de Revisão</h4></div>');
                }
                if(tipo_job.finalizador){
                    $('#div-dados-tipo').append('<div class="card-job-detalhes"><h4 class="margemB5 margemT5">Este Job define o Finalizador da Imagem</h4></div>');
                }
                if(tipo_job.gera_custo){
                    $('#div-dados-tipo').append('<div class="card-job-detalhes"><h4 class="margemB5 margemT5">Este Job gera Custo Extra ao Cliente</h4></div>');
                }
                if(tipo_job.campos_personalizados != null){
                    Object.entries(tipo_job.campos_personalizados).forEach(camposPersonalizados);
                }
                if(tipo_job.boas_praticas != null){
                     $('#div-dados-tipo').append('<div class="card-job-detalhes"<p><b>Boas Práticas:</b></p><h4>' + tipo_job.boas_praticas + '</h4></div>');
                }
            }


            function carregarDadosTipoDeDelivery(tipo_delivery) {
                
                if(tipo_delivery.campos_personalizados != null){
                    console.log(tipo_delivery.campos_personalizados);
                    Object.entries(tipo_delivery.campos_personalizados).forEach(camposPersonalizadosDelivery);
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

            function zerarTipoDeDeliverySelecionado() {
                $('#dados_tipo_delivery').html('');
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
                transformaImageFileInput(); //js/imagens.js
            }
            function montarLinhaArquivoReferencia(numero_linha){
                var linha = '<hr><div class="row div-item-form novo-arquivo-referencia">';
                linha    +=     '<div class="col-sm-12 col-md-6">';
                linha    += '       <p><b>{{ __("messages.Tipo de Arquivo") }}</b></p>';
                linha    += '       <select id="combo-tipo-arquivo-' + numero_linha +'" name="novas_ref[tipo_id][]" class="combo-tipo-arquivo form-control select2 margemT10"></select>';
                linha    += '   </div>';
                linha    += '   <div class="col-sm-12 col-md-12 margemTop">';
                linha    += '       <p><b>Selecione o Arquivo</b></p>';
                linha    += '       <div class="input-group image-preview">';
                linha    += '           <input id="arquivo-preview-' + numero_linha + '" type="text" class="form-control image-preview-filename" disabled="disabled" placeholder="{{ __("messages.Nenhum arquivo selecionado") }}">';
                linha    += '    <span class="input-group-btn">';
                linha    += '       <div class="btn btn-default image-preview-input">';
                linha    += '            <span class="glyphicon glyphicon-folder-open"></span>';
                linha    += '               <span class="image-preview-input-title">{{ __("messages.Procurar") }}</span>';
                linha    += '                   <input id="input-arquivo-' + numero_linha + '" name="arquivos_ref[]" type="file" accept="*" />';
                linha    += '        </div>';

                  linha    += '             <button type="button" class="btn btn-default image-preview-clear">';
                linha    += '     <span class="glyphicon glyphicon-remove"></span> {{ __("messages.Limpar") }}';
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
                if($('#combo-tipos-jobs').val() != -1){
                    var tipojob = $('#combo-tipos-jobs').val();
                    // $('#job-nome').val(job.toLowerCase());

                    var qtd_jobs = parseInt(document.getElementById('qtd_job').value)+1;
                    var cod_publicador = document.getElementById('publicador_id').value;
                    var novo_nome = "";
                    
                    nome_job = ("0000" + cod_publicador).slice(-4);
                    nome_job += ("0000" + tipojob).slice(-3);
                    nome_job += ("0000" + qtd_jobs).slice(-4);
                    
                    document.getElementById("job-nome-lab").value = nome_job;
                    document.getElementById("job-nome").value = nome_job;


                } else {
                    $('#job-nome').val('');
                }
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

            
            // Para montagem dos textos de valor e modal de confirmação
            $('#valor-job').on('change', function (e) {
                var valor =  document.getElementById("valor-job").value;
                valor = valor.replace('R$ ','');
                valor = valor.replace('.','');
                valor = valor.replace(',','.');
                
                if((parseInt(valor))>0) 
                {
                    var valorTaxa   = document.getElementById("taxa").value/100; 
                    var novovalor   = valor*valorTaxa;
                    var freelavalor = valor - valor*valorTaxa;
                    var texto = "Freelancer: " + freelavalor.toLocaleString('pt-BR') + " <br> " + jQuery('#mod-taxa').val() + ": " + novovalor.toLocaleString('pt-BR');
                    document.getElementById("taxa-real").value = texto;
                    //texto +=  " administrativas";
                    document.getElementById("valor-freela").value = freelavalor;
                    document.getElementById("valor-plataforma").value = novovalor;

                    //comentado 28/08/2020 - nao mostra o valor do desconto no cadastro 
                    //comentado 19/10/2020 - volta a mostra o valor do desconto no cadastro 
                    document.getElementById("valor-desconto").innerHTML = texto ;
                }
                else{
                    document.getElementById("valor-desconto").innerHTML = "";
                }
            });

            $(document).on('ifChanged', 'input[name="solicita_proposta"]', function(e) {
                valor = this.value;
                if(this.checked)
                {
                    $('input[name=avaliar_perfil]').iCheck('uncheck'); 
                    $("#solicita-proposta").val(1);
                    $("#div-proposta").removeClass("invisivel");
                    $("#div-valor").addClass("invisivel");
                    $("#avaliar-perfil").val(0);
                    
                }
                else{
                    $("#solicita-proposta").val(0);
                    $("#div-proposta").addClass("invisivel");
                    $("#div-valor").removeClass("invisivel");
                    $("#div-valor").val("0");
                }
            });		

            $(document).on('ifChanged', 'input[name="avaliar_perfil"]', function(e) {
                valor = this.value;
                if(this.checked)
                {
                    $('input[name=solicita_proposta]').iCheck('uncheck'); 
                    $("#avaliar-perfil").val(1);
                    $("#div-proposta").removeClass("invisivel");
                    $("#div-valor").removeClass("invisivel");
                }
                else{
                    $("#avaliar-perfil").val(0);
                    $("#div-proposta").addClass("invisivel");

                }
            });		


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