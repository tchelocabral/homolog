@extends('adminlte::page')

@section('title', __('messages.Dashboard'))

@section('content_header')
    {{-- {{ Breadcrumbs::render('home') }} --}}
    {{-- <h1 class="margemB10 margemT10 titulo-principal">Painel de Jobs</h1> --}}
@stop

@section('content')

    @if(env('APP_STATUS') && env('APP_STATUS') == 'instavel')
        @include('popups.instabilidade')
    @endif

    <input type="hidden" value="{{ route('termos.de.uso') }}" id="rota-termos">
    
    {{-- Contadores --}}
    @php
        $total_proj_and = $projetos_andamento  ? count($projetos_andamento) : 0;
        $total_proj_con = $projetos_concluidos ? count($projetos_concluidos) : 0;
        $total_proj_coo = $projetosCoordenando ? count($projetosCoordenando) : 0 ;
        
        $total_imgs_and = $imgs_andamento      ? count($imgs_andamento)     : 0;
        
        $total_jobs_abt = $jobs_abertos        ? count($jobs_abertos) : 0;
        $total_jobs_fre = $jobs_freelas        ? count($jobs_freelas) : 0;
        $total_jobs_exe = $executando          ? count($executando)  : 0; 
        $total_jobs_and = $jobs_andamento      ? count($jobs_andamento) : 0;
        $total_jobs_con = $jobs_concluidos     ? count($jobs_concluidos) : 0;
        $total_jobs_par = $jobs_parados        ? count($jobs_parados) : 0 ;
        $total_jobs_rec = $jobs_recusados      ? count($jobs_recusados) : 0;
        $total_jobs_coo = $coordenando         ? count($coordenando) : 0; 
        $total_jobs_ava = $avaliando           ? count($avaliando) : 0;
        
        $total_task_and = $tasks_andamento     ? count($tasks_andamento) : 0;

    @endphp

    {{-- Painel Consolidado Superior --}}
    <div class="row">

        <div class="col-md-12">
        
            {{-- Projetos em Andamento  --}}
            @if($total_proj_and>0)
                <div class="col-md-4 ">
                    <div class="small-box bg-info com-shadow">
                        <div class="inner">
                            <h3>{{$total_proj_and}}</h3>
                            <p>{{ __("messages.Projeto" . ($total_proj_and > 1 ? 's' : '') . " em Andamento") }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        {{-- <a href="{{ route('projetos.andamento') }}" class="small-box-footer texto-preto">Mais informações</a> --}}
                    </div>
                </div>
            @endif
            {{-- Imagens em Andamento --}}
            @if($total_proj_and>0)
                <div class="col-md-4">
                    <div class="small-box bg-warning com-shadow">
                        <div class="inner">
                            <h3>{{$total_imgs_and}}</h3>
                            <p>{{ __("messages.Image" . ($total_imgs_and > 1 ? 'ns' : 'm') . " em Andamento") }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        {{-- <a href="#" class="small-box-footer texto-preto">Mais informações</a> --}}
                    </div>
                </div>
            @endif
            {{-- Jobs Abertos  --}}
            @if($total_jobs_abt>0)
                <div class="col-md-4 ">
                    <div class="small-box bg-info com-shadow">
                        <div class="inner">
                            <h3>{{$total_jobs_abt}}</h3>
                            @php @endphp
                            <p> {{   __("messages.Job" . ($total_jobs_abt > 1 ? 's' : '') . " Aberto" . ($total_jobs_abt > 1 ? 's' : '') ) }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        {{-- <a href="{{ route('jobs.todos') }}" class="small-box-footer texto-preto">Mais informações</a> --}}
                    </div>
                </div>
            @endif
            {{-- Jobs em Execução  --}}
            @if($total_jobs_exe>0)
                <div class="col-md-4 ">
                    <div class="small-box bg-success com-shadow">
                        <div class="inner">
                            <h3>{{$total_jobs_exe}}</h3>
                            <p>{{ __("messages.Job" . ($total_jobs_exe > 1 ? 's' : '') . " em Execução") }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        {{-- <a href="{{ route('jobs.todos') }}" class="small-box-footer texto-preto">Mais informações</a> --}}
                    </div>
                </div>
            @endif
            {{-- Jobs em Andamento --}}
            @if($total_jobs_and>0)
                <div class="col-md-4">
                    <div class="small-box bg-danger com-shadow">
                        <div class="inner">
                            <h3>{{$total_jobs_and}}</h3>
                            <p>{{ __("messages.Job" . ($total_jobs_and > 1 ? 's' : '') . " em Andamento") }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        {{-- <a href="{{ route('jobs.andamento') }}" class="small-box-footer texto-preto">Mais informações</a> --}}
                    </div>
                </div>
            @endif
            {{-- Tasks Em Andamento --}}
            @if($total_task_and>0)
                <div class="col-md-4">
                    <div class="small-box bg-light com-shadow">
                        <div class="inner">
                            <h3>{{$total_task_and}}</h3>
                            <p>{{ __("messages.Task" . ($total_task_and > 1 ? 's' : '') . " em Andamento") }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        {{-- <a href="#" class="small-box-footer texto-preto">Mais informações</a> --}}
                    </div>
                </div>
            @endif
            {{-- Projetos Concluidos --}}
            @if($total_proj_con>0)
                <div class="col-md-4">
                    <div class="small-box bg-success com-shadow">
                        <div class="inner">
                            <h3>{{$total_proj_con}}</h3>
                            <p>{{ __("messages.Projeto" ($total_proj_con > 1 ? 's' : '') . " Concluído" . ($total_proj_con > 1 ? 's' : '') )}}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        {{-- <a href="{{ route('projetos.concluidos') }}" class="small-box-footer texto-preto">Mais informações</a> --}}
                    </div>
                </div>
            @endif
            {{-- Jobs Concluidos --}}
            @if($total_jobs_con>0)
                <div class="col-md-4">
                    <div class="small-box bg-success com-shadow">
                        <div class="inner">
                            <h3>{{$total_jobs_con}}</h3>
                            <p>{{ __("messages.Job" . ($total_jobs_con > 1 ? 's' : '') . " Concluído" . ($total_jobs_con > 1 ? 's' : '') )}}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        {{-- <a href="{{ route('jobs.concluidos') }}" class="small-box-footer texto-preto">Mais informações</a> --}}
                    </div>
                </div>
            @endif
            {{-- Jobs Parados --}}
            @if($total_jobs_par>0)
                <div class="col-md-4">
                    <div class="small-box bg-danger com-shadow">
                        <div class="inner">
                            <h3>{{$total_jobs_par}}</h3>
                            <p>{{ __("messages.Job" . ($total_jobs_par > 1 ? 's' : '') . " Parado" . ($total_jobs_par > 1 ? 's' : '') ) }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        {{-- <a href="{{ route('jobs.parados') }}" class="small-box-footer texto-preto">Mais informações</a> --}}
                    </div>
                </div>
            @endif       

        </div>

    </div>
    
    <hr>

    {{-- Filtros --}}
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-4 com-sm-12">
                <div class="nav-tabs-transparent nav-dashboard margemT50">
                    <h1 class="titulo-principal texto-esquerda titulo-lista-dash">
                        <a class="larguraTotal alturaTotal texto-preto" data-toggle="collapse" data-target="#collapse-filter" aria-expanded="false" aria-controls="#collapse-filter" role="button"><span class="accordion-marc"><i class="fa fa-angle-right"></i></span> {{ __('messages.Filtro') }}</a>
                    </h1>
                    <div id="collapse-filter" class="tab-content collapse" aria-labelledby="panel-proand" data-parent="#collapse-filter">
                        <div class="row larguraTotal">
                            {{-- <div class="col-md-12">
                                <h3>{{ __('messages.Filtro') }}</h3>
                            </div> --}}
                            <div class="col-md-12">
                                <h3><i>{{ __('messages.Selecione um ou mais Tipos de Job') }}</i></h3>
                                <form id="filtro-home" action="{{ route('home') }}" method="GET" name="filtr_home" class="">
                                    <div class="displayFlex">
                                        <img 
                                            src="{{ Storage::url('imagens/tipojobs/todos.png') }}" 
                                            alt="{{ __('messages.Todos os tipos de jobs') }}"
                                            data-toggle="tooltip" title="{{ __('messages.Todos os tipos de jobs') }}"
                                            data-tipo="todos" data-input="tipo-todos"
                                            height="56" width="56"
                                        >
                                        @foreach($tipos_jobs as $tp)
                                            
                                            <img 
                                                src="{{ $tp->imagem ? Storage::url( $tp->imagem  ) : '' }}" 
                                                alt="{{ $tp->nome }}"
                                                data-toggle="tooltip" title="{{ $tp->nome }}"
                                                data-tipo="{{ $tp->id }}" data-input="tipo-{{ $tp->id }}"
                                                height="56" width="56"
                                            >
                                        @endforeach
                                    </div>
                    
                                    <button type="submit" class="btn btn-success flexSelfEnd margemT10"><b>{{ __('messages.Filtrar') }}</b></button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
       
    {{-- Dashboard --}}
    <div class="row padding20">
        <!-- Painel de Jobs-->
        @empty(!$projetos)

            <div class="nav-tabs-custom nav-dashboard margemT50">
               <h1 class="titulo-principal texto-esquerda  titulo-lista-dash">{{ __('messages.Projetos') }}</h1>

                <ul class="nav nav-tabs displayFlex flexEnd paddingR20" id="tabs-projeto" role="tablist">
                    <li class="active in" data-toggle="tooltip" title="{{ __('messages.Listas') }}">
                        <a data-toggle="tab" href="#lista" aria-expanded="true" class="nav-link" id="imagens-tab" aria-selected="false" > <i class="fa fa-list" aria-hidden="true"></i></a>
                    </li>
                    <li data-toggle="tooltip" title="{{ __('messages.Cards') }}">
                        <a data-toggle="tab" href="#card" aria-expanded="true" class="nav-link" id="imagens-tab" aria-selected="false" ><i class="fa fa-credit-card" aria-hidden="true"></i></a>
                    </li>
                    <li data-toggle="tooltip" title="{{ __('messages.Detalhes') }}">
                        <a data-toggle="tab" href="#detalhes" aria-expanded="true" class="nav-link" id="imagens-tab" aria-selected="false"><i class="fa fa-info" aria-hidden="true"></i></a>
                    </li>
                </ul>
                
                <div class="tab-content">
                    {{-- Tab #detalhes--}}
                    <div id="detalhes" class="tab-pane fade">
                        <div class="btn-toolbar margemT10 tab-pane fade in" role="toolbar">
                            <table id="lista-dashboard-projetos" class="table table-striped larguraTotal com-shadow com-filtro">
                                <thead>
                                    <tr>
                                        <th colspan="" class="th-ocean texto-branco padding12 border-left largura10">#</th>
                                        <th colspan="" class="th-ocean texto-branco padding12">{{ __('messages.Projeto') }}</th>
                                        <th colspan="" class="th-ocean texto-branco padding12 largura15">{{ __('messages.Cliente') }}</th>
                                        <th colspan="" class="th-ocean texto-branco padding12 largura15">{{ __('messages.Criação') }}</th>
                                        <th colspan="" class="th-ocean texto-branco padding12 largura15">{{ __('messages.Previsão de Entrega') }}</th>
                                        <th colspan="" class="th-ocean texto-branco padding12">{{ __('messages.Progresso') }}</th>
                                        <th colspan="" class="th-ocean texto-branco padding12 border-right">{{ __('messages.Ações') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="fundo-branco">
                                    @foreach($projetos as $prop => $proj)
                                    <tr class="">
                                        <td class="desktop margemL10">{{ $prop+1 }}</td>
                                        <td><a href="{{ route('projetos.show', encrypt($proj->id)) }}" class="">{{ $proj->nome  }}</a></td>
                                        {{-- <td class="desktop">{{ $proj->descricao }}</td> --}}
                                        <td class="desktop">{{ $proj->cliente->nome_fantasia }}</td>
                                        <td class="desktop">{{ $proj->created_at ? \Carbon\Carbon::parse($proj->created_at)->format('d.m.Y') : ''}}</td>
                                          <td class="desktop">{{ $proj->data_previsao_entrega ? \Carbon\Carbon::parse($proj->data_previsao_entrega)->format('d.m.Y') : ''}}</td>
                                        <td>
                                            <div class="progress">
                                                <div class="progress-bar progress-bar-animated progresso" role="progressbar" aria-valuenow="{{ $proj->concluido() }}" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="{{ route('projetos.show', encrypt($proj->id)) }}" class="">
                                                {{ __('messages.Detalhes') }}
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Tab #card--}}
                    <div id="card" class="tab-pane fade  ">
                        <div class="btn-toolbar margemT10 tab-pane fade in" role="toolbar">
                            <div class="col-md-12 painel-jobs">
                                @foreach($projetos as $proj_and)
                                    <div class="col-md-6 col-lg-4 col-sm-12 card-job"   data-rota="{{ route('projetos.show', encrypt($proj_and->id)) }}">
                                        <div class="box-group" id="accordion">
                                            <div class="panel box box-primary com-shadow">
                                                <div class="box-header ">
                                                    <h4><b>{{ __('messages.Projeto') }}: </b>
                                                        <a href="{{ route('projetos.show', encrypt($proj_and->id)) }}" class="word-break">{{ $proj_and->cliente->nome_fantasia }} - {{ $proj_and->nome }}
                                                        </a>
                                                    </h4>
                                                    <span class="float-right text-danger margemLeft">
                                                    </span>
                                                </div>
                                                <div id="collapseOne" class="panel-collapse collapse in" aria-expanded="false" >
                                                    <div class="box-body">
                                                        <div class="col-md-4">
                                                            <canvas id="proj-and-chart-{{$proj_and->id}}" class="graficos"  data-status="{{ $job->status }}" width="136" height="136" data-valor="{{ $proj_and->concluido() }}"></canvas>
                                                        </div>
                                                        <div class="col-md-6 col-md-offset-2">
                                                            @isset($proj_and->coordenador)
                                                                <h4 class="margemT10"><b>{{ __('messages.Coordenador') }}:</b></h4>
                                                                <p>{{ $proj_and->coordenador->name }}</p>
                                                            @endif
                                                        </div>
                                                        <div class="col-md-12 margemT20">
                                                            <p><b>{{ __('messages.Deadline') }}: </b>
                                                            {{ $proj_and->data_prox_revisao ? $proj_and->data_prox_revisao->format('d.m.Y') : __('messages.Não informado') }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div> 
                        </div>
                    </div>

                    {{-- Tab #lista--}}
                    <div id="lista" class="tab-pane fade  in active">
                        <div class="btn-toolbar margemT10 tab-pane fade in" role="toolbar">
                            @unless(!$projetos)
                            <div id="img-accordion">
                                <BR>
                                <table id="lista-dashboard-projetos-lista" class="table table-striped larguraTotal com-shadow com-filtro-lista">
                                    <thead>
                                        <th class="th-ocean texto-branco padding12 border-left">
                                            <div class="panel-heading cor-personalizada" >
                                                <div class="row">
                                                    <div class="col-md-2">{{ __('messages.Nome') }}</div>
                                                    <div class="col-md-2">{{ __('messages.Cliente') }}</div>
                                                    <div class="col-md-2">{{ __('messages.Coordenador') }}</div>
                                                    <div class="col-md-2">{{ __('messages.Progresso') }}</div>
                                                    <div class="col-md-2">
                                                        <span class="pull-right titulo-tab-imagens" href="" class="" title="{{ __('messages.Criar Novo Job') }}" data-toggle="tooltip">{{ __('messages.Ação') }}</span>
                                                    </div>
                                                </div>  
                                            </div>
                                        </th>
                                    </thead>
                                    <tbody class="fundo-branco">
                                    @foreach($projetos as $pro)
                                    <tr class="">
                                        <td class="desktop margemL10 no-border">
                                            <div class="panel panel-default card-sem-borda card-imagem">
                                                <div class="panel-heading cor-personalizada" id="panel-img-{{$pro->id}}" style="background: #fff;">
                                                    <a class="larguraTotal alturaTotal texto-preto" data-toggle="collapse" data-target="#collapseme{{$pro->id}}" aria-expanded="false" aria-controls="#collapseme{{$pro->id}}" role="button">
                                                        <div class="row">
                                                            <div class="col-md-2">
                                                                <p class="titulo-tab-imagens">{{ $pro->nome }}
                                                                </p>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <p class="titulo-tab-imagens">{{ $pro->cliente->nome_fantasia }}
                                                                </p>
                                                            </div>
 
                                                            <div class="col-md-2">
                                                                <p class="titulo-tab-imagens">{{ isset($pro->coordenador) ? $pro->coordenador->name : __('messages.Não Informado') }}
                                                                </p>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="progress semMargem cor-personalizada" style="background: #fff;">
                                                                    <div class="progress-bar progress-bar-animated progress-bar-striped progress-bar-success progresso pull-right" role="progressbar" aria-valuenow="{{ $pro->concluido() }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <div class="col-md-2">
                                                                    <a class="pull-right titulo-tab-imagens link-detalhe" href="{{ route('projetos.show', encrypt($pro->id)) }}" class="" title="{{ __('messages.Detalhes projeto') }}" data-toggle="tooltip">
                                                                    {{ __('messages.Detalhes') }}
                                                                </a>
                                                            </div>
                                                        </div>  
                                                    </a>
                                                </div>
                                                <div id="collapseme{{$pro->id}}" class="collapse" aria-labelledby="panel-pro-{{$pro->id}}" data-parent="#img-accordion">
                                                    <div class="panel-body">
                                                        <div class="row margemB10">
                                                            <div class="col-md-12">
                                                                <h4><b>
                                                                    {{ __('messages.Dados Imagens') }}
                                                                </b></h4>
                                                            </div>

                                                            
                                                            <div class="col-md-10">
                                                                <h4><b>
                                                                    {{ __('messages.Nome') }} :</b> {{ $pro->nome }}
                                                                </h4>
                                                            </div>
          
                                                            <div class="col-md-12">
                                                                <h4>
                                                                <b>{{ __('messages.Descrição') }}: </b>
                                                                {{ $pro->descricao ??  __('messages.Não Informado') }}
                                                                </h4>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <h4><b>{{ __('messages.Coodernador') }}: </b> 

                                                                {{ isset($pro->coordenador) ? $pro->coordenador->name : __('messages.Não Informado') }}
                                                                </h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @endif        
                        </div>
                    </div>
                </div>

            </div>
                
        {{-- Não é cliente logado --}}
        @else      
            <input type="hidden" id="mod-tit" value="Job ">
            <input type="hidden" id="mod-tit-tasks" value="{{ __('modal.Tarefas do Job') }}">
            {{-- <input type="hidden" id="mod-btn-ok" value="{{ __('modal.Enviar Proposta') }}"> --}}
            <input type="hidden" id="mod-btn-ok" value="{{ __('modal.Aceitar') }}">
            <input type="hidden" id="mod-btn-cancel" value="{{ __('modal.Cancelar') }}">
            <input type="hidden" id="mod-tit-valor" value="{{ __('modal.Valor') }}">
            <input type="hidden" id="mod-taxa" value="{{ __('modal.Taxas') }}">
            <input type="hidden" id="mod-tit-prazo" value="{{ __('modal.Deadline') }}">
            <input type="hidden" id="mod-li-termos" value="{{ __('modal.Li e concordo com os') }}">
            <input type="hidden" id="mod-termos" value="{{ __('modal.Termos de Uso') }}">
            <input type="hidden" id="mod-no-termos" value="{{ __('modal.Você deve concordar com os termos de uso da plataforma para pegar um Job') }}">
            <input type="hidden" id="mod-propor-valor" value="{{ __('modal.Você deve definir um valor para se candidatar ao Job') }}">
            <input type="hidden" id="mod-details" value="{{ __('messages.Detalhes') }}">
            <input type="hidden" id="mod-valor-proposta" value="{{ __('modal.Valor Proposta') }}">
            <input type="hidden" id="mod-job-candidatura" value="{{ __('modal.Aberto a candidaturas') }}">
            
            <input type="hidden" id="mod-btn-paypal" value="{{ __('modal.Cadastre sua conta PayPal para pegar jobs') }}">
            <input type="hidden" id="mod-job-and" value="{{ __('modal.Termine o Job em andamento para pegar novos jobs') }}">
            <input type="hidden" id="mod-pro-and" value="{{ __('modal.Você já enviou uma proposta para esse Job') }}">
            
            @if($total_jobs_fre>0)
                    <h1 class="titulo-principal texto-esquerda">{{ __('messages.Jobs Disponíveis') }}</h1>
                    
                    <div id="collapsejobfreela" class="tab-content accordion" aria-labelledby="panel-proand" data-parent="#img-accordion">
                        
                        {{-- Tab #card--}}
                        <div id="cardJobAberto" class="tab-pane fade  in active">
                            <div class="btn-toolbar margemT10 tab-pane fade in" role="toolbar">
                                <div class="col-md-12 painel-jobs">
                                <!-- 
                                    Div abaixo, tirando click do card
                                    <div class="col-md-3 col-lg-3 col-sm-12 card-job">  -->
                                    {{-- {{ dd($jobs_freelas) }} --}}
                                    @foreach($jobs_freelas as $job)
                                        <div class="col-md-3 col-lg-3 col-sm-12 "> 
                                            <div class="box-group" id="accordion">
                                                <div class="panel box box-success com-shadow">
                                                    <div class="col-md-6" >
                                                        <p class="titulo-card-job word-break text-break">
                                                            {{ $job->user->display_name  ?? $job->nome }}
                                                        </p>
                                                        <p class="semMargem">
                                                            <b>{{ __('messages.Deadline') }}: </b>
                                                            @php $data = $job->data_prox_revisao ?  $job->data_prox_revisao->format('d.m.Y') : __('messages.Não informado') @endphp
                                                            {{ $data }}
                                                        </p>
                                                    </div>
                                                    <div class="col-md-6 ">
                                                        <img 
                                                            src="{{ Storage::url( $job->tipo->imagem ) }}" 
                                                            alt="{{ $job->tipo->nome }}"
                                                            data-toggle="tooltip" title="{{ $job->tipo->nome }}"
                                                            height="56" width="56"
                                                        >
                                                        @if($job->pagamento && $job->pagamento->prazo_pagamento) 
                                                            <p class="semMargem">
                                                                <b>Prazo Pagamento:</b><br>
                                                                {{$job->pagamento->prazo_pagamento}} dias
                                                            </p>
                                                        @endif 
                                                    </div>
                                                    <div class="col-md-12 displayFlex flexCentralizado flexSpaceBetween">
                                                        
                                                        <p class="semMargem word-break">
                                                            @if($job->delegado) 
                                                                {{$job->delegado->name}}
                                                            @endif 
                                                        </p>
                                                    </div>
                                                    <div class="box-body displayFlex flexCentralizado flexColunas">
                                                        <div class="col-md-12 displayFlex flexCentralizado thumb-card-wrapper">
                                                            @if($job->thumb)
                                                                <img src="{{asset('storage/' . $job->thumb)}}" alt="{{ __('messages.Referência do Job') }}" class="img-responsive card-job-thumb">
                                                            @else
                                                                <img src="{{asset('storage/imagens/jobs/job_default.png')}}" alt="{{ __('messages.Referência do Job') }}" class="img-responsive card-job-thumb">
                                                            @endif
                                                        </div>
                                                        
                                                        <div class="col-md-12 margemT20 container-entrega-job">
                                                            {{-- {{ __('messages.Preço') }}: --}}
                                                            <p class="semMargem texto-centralizado texto-valor"><b>
                                                                @php $money  = $job->money @endphp
                                                                @if($job->candidaturaFreela)
                                                                    R$ @convert_money($job->money)
                                                                    ({{ __('messages.Proposta Enviada')}})
                                                                @elseif($job->status == $status_array['emcandidatura'])
                                                                    R$ @convert_money($job->money)
                                                                    ({{ __('messages.Aceita Candidatura')}})
                                                                @elseif($job->status == $status_array['emproposta'])
                                                                    {{ __('messages.Aceita Propostas')}}
                                                                @else
                                                                    R$ @convert_money($job->money)
                                                                @endif
                                                            </b></p>
                                                        </div>    
                                                        <div class="col-md-12 margemT20 margemB5">
                                                            <span id="job-freela-{{$job->id}}" 
                                                                data-valor="{{$job->money ? 'R$ ' . number_format($job->money, 2, ",", ".") : __('messages.A Combinar')}}" 
                                                                data-nome="{{$job->nome ?? $job->id}}"
                                                                data-descricao="{{$job->descricao ?? ''}}"
                                                                data-entrega="{{$data}}"
                                                                data-proposta="{{ $job->status==$status_array['emproposta'] ? 1 :0 }}"
                                                                data-candidatura="{{ $job->status==$status_array['emcandidatura'] ? 1 :0 }}"
                                                                data-status="{{$job->status_nome}}"
                                                                data-mensagem-confirm="{{__('messages.Você confirma a proposta pelo valor informado?')}}"
                                                                data-date-proposta="{{$job->data_limite ? $job->data_limite->format('d.m.Y') : '' }}"
                                                                data-url="{{route('freela.pega.job', encrypt($job->id))}}"
                                                                {{-- data-token="{{csrf_field()}}" --}}
                                                                data-tasks="{{$job->tasks ?? ''}}"
                                                                class="pega-job invisivel">
                                                            </span>
                                                            @forelse($job->tasks as $task)
                                                                <span id="tasks-{{$task->id}}-job-{{$job->id}}" data-nome="{{$task->nome}}" class="task-job-{{$job->id}}">
                                                                </span>
                                                            @empty  
                                                            @endforelse
                                                            <div class="larguraTotal centralizado displayFlex flexCentralizado">
                                                                <button 
                                                                    class="btn btn-primary acao-pega-job margemR5 centralizado" 
                                                                    value="{{encrypt($job->id)}}" 
                                                                    data-id="{{$job->id}}" 
                                                                    data-rota="{{ route('jobs.show', encrypt($job->id)).'?view=files' }}" 
                                                                    data-limite-job="{{$limite_job}}" 
                                                                    data-pega-job="{{$pega_job}}"   
                                                                    {{-- data-manda-proposta = "{{($job->manda_proposta)}}" --}}
                                                                    data-proposta-enviada = "{{  $job->proposta_enviada }}"
                                                                    data-paypal="{{$conta_paypal}}" 
                                                                    {{-- data-qtde_jobs="{{$qtde_jobs_freela}}"  --}}
                                                                    data-conta-url="{{route('visualizar.conta.user')}}" 
                                                                    data-thumb-url="{{ $job->thumb ? asset('storage/' . $job->thumb) : asset('storage/imagens/jobs/job_default.png') }}" 
                                                                    name="btnPegaJob" >
                                                                    {{ __('messages.Visualizar') }}
                                                                </button> 
                                                            {{-- <button class="btn btn-success acao-pega-job"  data-id="{{$job->id}}" name="btnPegaJob" >Pegar Job</button> --}}
                                                            </div>
                                                        </div>       
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div> 
                            </div>
                        </div>
                    </div>
            @endif


            @if($total_jobs_abt>0)
                    <h1 class="titulo-principal texto-esquerda">{{ __('messages.Jobs em Aberto') }}</h1>
                    
                    <div id="collapsejobabertos" class="tab-content" aria-labelledby="panel-proand" data-parent="#img-accordion">

                        {{-- Tab #card--}}
                        <div id="cardJobAberto" class="tab-pane fade in active">
                            <div class="btn-toolbar margemT10 tab-pane fade in" role="toolbar">
                                <div class="col-md-12 painel-jobs">
                                @foreach($jobs_abertos as $job)
                                    <div class="col-md-3 col-lg-3 col-sm-12 card-job" data-rota="{{ route('jobs.show', encrypt($job->id)) }}">
                                        <div class="box-group" id="accordion">
                                            <div class="panel box box-success com-shadow ">
                                                <div class="col-md-6" align="left">
                                                    <p class="semMargem">
                                                        <a href="{{ route('jobs.show', encrypt($job->id)) }}"aria-expanded="false" class="word-break">{{ $job->nome }}</a> 
                                                    </p>
                                                </div>
                                                <div class="col-md-12 displayFlex flexCentralizado flexSpaceBetween">
                                                    <p class="semMargem word-break">
                                                        @if($job->delegado) 
                                                            {{$job->delegado->name}}
                                                        @endif 
                                                    </p>
                                                    <img 
                                                        src="{{ Storage::url( $job->tipo->imagem ) }}" 
                                                        alt="{{ $job->tipo->nome }}"
                                                        data-toggle="tooltip" title="{{ $job->tipo->nome }}"
                                                        height="56" width="56"
                                                    > 
                                                    </p>
                                                </div>
                                                <div id="collapseOne" class="panel-collapse collapse in" aria-expanded="false" >
                                                    <div class="box-body">
                                                        <div class="col-md-12 displayFlex flexCentralizado thumb-card-wrapper">
                                                            @if($job->thumb)
                                                                <img src="{{asset('storage/' . $job->thumb)}}" alt="{{ __('messages.Referência do Job') }}" class="img-responsive card-job-thumb">
                                                            @else
                                                                <img src="{{asset('storage/imagens/jobs/job_default.png')}}" alt="{{ __('messages.Referência do Job') }}" class="img-responsive card-job-thumb">
                                                            @endif
                                                        </div>
                                                        <div class="col-md-8 margemT20 margemB5 container-entrega-job">
                                                            <p class="semMargem">
                                                                <b>{{ __('messages.Deadline') }}: </b>
                                                                @php 
                                                                    $data = $job->data_prox_revisao ?  $job->data_prox_revisao->format('d.m.Y') : __('messages.Não informado') 
                                                                @endphp
                                                            {{ $data }}</p>
                                                        </div>    
                                                        <div class="col-md-12 margemT20 margemB5">
                                                            <span id="job-freela-{{$job->id}}"   
                                                                data-valor="{{$job->valor_job ? 'R$ '.$job->valor_job : __('messages.A Combinar')}}" 
                                                                data-nome="{{$job->nome ?? $job->id}}" 
                                                                data-descricao="{{$job->descricao ?? ''}}" 
                                                                data-entrega="{{$data}}" 
                                                                data-url="{{route('freela.pega.job', encrypt($job->id))}}" {{-- data-token="{{csrf_field()}}" --}} 
                                                                data-tasks="{{$job->tasks ?? ''}}" class="pega-job invisivel">
                                                            </span>
                                                            @forelse($job->tasks as $task)
                                                                <span 
                                                                    id="tasks-{{$task->id}}-job-{{$job->id}}" 
                                                                    data-nome="{{$task->nome}}"
                                                                    class="task-job-{{$job->id}}">
                                                                </span>
                                                            @empty  
                                                            @endforelse
                                                        </div>                            
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                </div> 
                            </div>
                        </div>
                    </div>
            @endif


            @if($projetos_andamento && $total_proj_and>0)
                <div class="nav-tabs-custom nav-dashboard margemT50">
                   <h1 class="titulo-principal texto-esquerda titulo-lista-dash">
                        <a class="larguraTotal alturaTotal texto-preto" data-toggle="collapse" data-target="#collapseprojand" aria-expanded="false" aria-controls="#collapseprojand" role="button">
                        <span class="accordion-marc"><i class="fa fa-angle-right"></i></span> {{ __('messages.Projetos') }} 
                        </a>
                    </h1>

                    <ul class="nav nav-tabs displayFlex flexEnd paddingR20" id="tabs-projeto" role="tablist">
                        <li class="active in " data-toggle="tooltip" title="{{ __('messages.Listas') }}">
                            <a data-toggle="tab" href="#lista" aria-expanded="true" class="nav-link " id="imagens-tab" aria-selected="false" > <i class="fa fa-list" aria-hidden="true"></i></a>
                        </li>
                        <li data-toggle="tooltip" title="{{ __('messages.Cards') }}">
                            <a data-toggle="tab" href="#card" aria-expanded="true" class="nav-link " id="imagens-tab" aria-selected="false" ><i class="fa fa-credit-card " aria-hidden="true"></i></a>
                        </li>
                        <li data-toggle="tooltip" title="{{ __('messages.Detalhes') }}">
                            <a data-toggle="tab" href="#detalhes" aria-expanded="true" class="nav-link fundo-azul" id="imagens-tab"  aria-selected="false"><i class="fa fa-info" aria-hidden="true"></i></a>
                        </li>
                    </ul>
                    
                    <div id="collapseprojand" class="tab-content collapse" aria-labelledby="panel-proand" data-parent="#img-accordion">
                        {{-- Tab #detalhes--}}
                        <div id="detalhes" class="tab-pane fade">
                            <div class="btn-toolbar margemT10 tab-pane fade in" role="toolbar">
                                <table id="lista-dashboard-projeand" class="table table-striped larguraTotal com-shadow com-filtro-lista">
                                    <thead>
                                        <tr>
                                            <th class="th-ocean texto-branco padding12 border-left largura10 ">#</th>
                                            <th class="th-ocean texto-branco padding12">{{ __('messages.Projeto') }}</th>
                                            <th class="th-ocean texto-branco padding12">{{ __('messages.Cliente') }}</th>
                                            <th class="th-ocean texto-branco padding12 largura15">{{ __('messages.Criação') }}</th>
                                            <th class="th-ocean texto-branco padding12 largura15">{{ __('messages.Previsão de Entrega') }}</th>
                                            <th class="th-ocean texto-branco padding12">{{ __('messages.Progresso') }}</th>
                                            <th class="th-ocean texto-branco padding12 border-right">{{ __('messages.Ações') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="fundo-branco">
                                        @foreach($projetos_andamento as $prop => $proj)
                                        <tr class="">
                                            <td class="desktop margemL10 largura10">{{ $prop+1 }}</td>
                                            <td><a href="{{ route('projetos.show', encrypt($proj->id)) }}" class="texto-preto">{{ $proj->nome  }}</a></td>
                                            {{-- <td class="desktop ">{{ $proj->descricao }}</td> --}}
                                            <td class="desktop">{{ $proj->cliente->nome_fantasia }}</td>
                                            <td class="desktop largura15">{{ $proj->created_at ? \Carbon\Carbon::parse($proj->created_at)->format('d.m.Y') : ''}}</td>
                                            <td class="desktop largura15">{{ $proj->data_previsao_entrega ? \Carbon\Carbon::parse($proj->data_previsao_entrega)->format('d.m.Y') : ''}}</td>
                                            <td>
                                                <div class="progress">
                                                    <div class="progress-bar progress-bar-animated progresso" role="progressbar" aria-valuenow="{{ $proj->concluido() }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="{{ route('projetos.show', encrypt($proj->id)) }}" class="texto-preto">{{ __('messages.Detalhes') }}</a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- Tab #card--}}
                        <div id="card" class="tab-pane fade  ">
                            <div class="btn-toolbar margemT10 tab-pane fade in" role="toolbar">
                                <div class="col-md-12 painel-jobs">
                                @foreach($projetos_andamento as $proj_and)
                                    <div class="col-md-6 col-lg-4 col-sm-12 card-job" data-rota="{{ route('projetos.show', encrypt($proj_and->id)) }}">
                                        <div class="box-group" id="accordion">
                                            <div class="panel box box-primary com-shadow">
                                                <div class="box-header">
                                                    <h4><b>{{ __('messages.Projeto') }}: </b>
                                                        <a href="{{ route('projetos.show', encrypt($proj_and->id)) }}" >{{ $proj_and->cliente->nome_fantasia }} - {{ $proj_and->nome }}
                                                        </a>
                                                    </h4>
                                                    <span class="float-right text-danger margemLeft"></span>
                                                </div>
                                                <div id="collapseOne" class="panel-collapse collapse in" aria-expanded="false" >
                                                    <div class="box-body">
                                                        <div class="col-md-4">
                                                            {{-- aqui foir retirado {{ $job->status }} do data-status --}}
                                                            <canvas id="proj-and-chart-{{$proj_and->id}}" class="graficos" data-status="" width="136" height="136" data-valor="{{ $proj_and->concluido() }}"></canvas>
                                                        </div>
                                                        <div class="col-md-6 col-md-offset-2">
                                                            @isset($proj_and->coordenador)
                                                                <h4 class="margemT10"><b>{{ __('messages.Coordenador') }}:</b></h4>
                                                                <p>{{ $proj_and->coordenador->name }}</p>
                                                            @endif
                                                        </div>
                                                        <div class="col-md-12 margemT20">
                                                            <p><b>{{ __('messages.Deadline') }}: </b>
                                                            {{ $proj->data_previsao_entrega ? $proj->data_previsao_entrega->format('d.m.Y') : __('messages.Não Informado') }}
                                                            </p>
                                                        </div>        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                </div> 
                            </div>
                        </div>

                        {{-- Tab #lista--}}
                        <div id="lista" class="tab-pane fade  in active">
                            <div class="btn-toolbar margemT10 tab-pane fade in" role="toolbar">
                                @unless(!$projetos_andamento)
                                <div id="img-accordion">
                                    <BR>
                                    <table id="lista-dashboard-projeand-lista" class="table larguraTotal com-shadow com-filtro">
                                        <thead>
                                            <th class="th-ocean texto-branco padding12 border-left">
                                                <div class="panel-heading  cor-personalizada" >
                                                    <div class="row">
                                                        <div class="col-md-2">{{ __('messages.Nome') }}</div>
                                                        <div class="col-md-2">{{ __('messages.Cliente') }}</div>
                                                        <div class="col-md-2">{{ __('messages.Coordenador') }}</div>
                                                        <div class="col-md-2">{{ __('messages.Progresso') }}</div>
                                                         <div class="col-md-2">{{ __('messages.Data de Entrega') }}</div>
                                                        <div class="col-md-2">
                                                            <span class="pull-right titulo-tab-imagens" href="" class="" title="{{ __('messages.Criar Novo Job') }}" data-toggle="tooltip">
                                                            {{ __('messages.Ação') }}</span>
                                                        </div>
                                                    </div>  
                                                </div>
                                            </th>
                                        </thead>
                                        <tbody class="fundo-branco">
                                        @foreach($projetos_andamento as $pro)
                                        <tr class="">
                                            <td class="desktop margemL10 no-border">
                                                <div class="panel panel-default card-sem-borda card-imagem">
                                                    <div class="panel-heading painel-lista cor-personalizada" id="panel-img-{{$pro->id}}" style="background: #fff;">
                                                        <a class="larguraTotal alturaTotal texto-preto" data-toggle="collapse" data-target="#collapseme{{$pro->id}}" aria-expanded="false" aria-controls="#collapseme{{$pro->id}}" role="button">
                                                            <div class="row">
                                                                <div class="col-md-2">
                                                                    <p class="titulo-tab-imagens">{{ $pro->nome }}
                                                                    </p>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <p class="titulo-tab-imagens">{{ $pro->cliente->nome_fantasia }}
                                                                    </p>
                                                                </div>
     
                                                                <div class="col-md-2">
                                                                    <p class="titulo-tab-imagens">{{ isset($pro->coordenador) ? $pro->coordenador->name : __('messages.Não Informado') }}
                                                                    </p>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <div class="progress semMargem cor-personalizada" style="background: #fff;">

                                                                        <div class="progress-bar progress-bar-animated progress-bar-striped progress-bar-success progresso pull-right" role="progressbar" aria-valuenow="{{ $pro->concluido() }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    {{$pro->data_previsao_entrega ? \Carbon\Carbon::parse($pro->data_previsao_entrega)->format('d.m.Y') :  __('messages.Não Informado') }}
                                                                </div>
                                                                <div>
                                                                    <div class="col-md-2">
                                                                        <a class="pull-right titulo-tab-imagens  link-detalhe" href="{{ route('projetos.show', encrypt($pro->id)) }}" class="" title="{{ __('messages.Detalhes projeto') }}" data-toggle="tooltip">
                                                                        {{ __('messages.Detalhes') }}
                                                                    </a>
                                                                </div>
                                                            </div>  
                                                        </a>
                                                    </div>

                                                    <div id="collapseme{{$pro->id}}" class="collapse" aria-labelledby="panel-pro-{{$pro->id}}" data-parent="#img-accordion">
                                                        <div class="panel-body">
                                                            <div class="row margemB10">
                                                                <div class="col-md-12">
                                                                    <h4><b>{{ __('messages.Dados') }}</b></h4>
                                                                </div>

                                                                
                                                                <div class="col-md-10">
                                                                    <h4><b>{{ __('messages.Nome') }} :</b> {{ $pro->nome }}</h4>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <a href="{{ route('projetos.show', encrypt($pro->id)) }}" class="btn btn-info pull-right">
                                                                        {{ __('messages.Mais detalhes') }}
                                                                    </a>  
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <h4><b>{{ __('messages.Descrição') }}: </b> {{ $pro->descricao ??  __('messages.Não Informado') }}</h4>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <h4><b>{{ __('messages.Coodernador') }}: </b> {{ isset($pro->coordenador) ? $pro->coordenador->name : __('messages.Não Informado') }}</h4>
                                                                </div>
                                                             </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @endif        

                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if($total_proj_coo>0)
                <div class="nav-tabs-custom nav-dashboard margemT50">
                   <h1 class="titulo-principal texto-esquerda titulo-lista-dash">
                        <a class="larguraTotal alturaTotal texto-preto" data-toggle="collapse" data-target="#collapseprocond" aria-expanded="false" aria-controls="#collapseprocond" role="button">
                           <span class="accordion-marc"><i class="fa fa-angle-right"></i></span> Projetos que está Coordenando
                        </a>
                    </h1>

                    <ul class="nav nav-tabs displayFlex flexEnd paddingR20" id="tabs-projeto" role="tablist">
                        <li class="active in" data-toggle="tooltip" title="{{ __('messages.Listas') }}">
                            <a data-toggle="tab" href="#listaProjCoord" aria-expanded="true" class="nav-link " id="imagens-tab" aria-selected="false" > <i class="fa fa-list" aria-hidden="true"></i></a>
                        </li>
                        <li data-toggle="tooltip" title="{{ __('messages.Cards') }}">
                            <a data-toggle="tab" href="#cardProjCoord" aria-expanded="true" class="nav-link" id="imagens-tab" aria-selected="false" ><i class="fa fa-credit-card" aria-hidden="true"></i></a>
                        </li>
                        <li data-toggle="tooltip" title="{{ __('messages.Detalhes') }}">
                            <a data-toggle="tab" href="#detalhesProjCoord" aria-expanded="true" class="nav-link" id="imagens-tab"  aria-selected="false"><i class="fa fa-info" aria-hidden="true"></i></a>
                        </li>
                    </ul>
                    
                    <div id="collapseprocond" class="tab-content collapse" aria-labelledby="panel-proand" data-parent="#img-accordion">
                        {{-- Tab #detalhes--}}
                        <div id="detalhesProjCoord" class="tab-pane fade">
                            <div class="btn-toolbar margemT10 tab-pane fade in" role="toolbar">
                                <table id="lista-dashboard-proj-coord" class="table table-striped larguraTotal com-filtro-lista com-shadow">
                                    <thead>
                                        <tr>
                                            <th colspan="" class="th-ocean texto-branco padding12 border-left largura10">#</th>
                                            <th colspan="" class="th-ocean texto-branco padding12">{{ __('messages.Projeto') }}</th>
                                            <th colspan="" class="th-ocean texto-branco padding12 largura15">{{ __('messages.Cliente') }}</th>
                                            <th colspan="" class="th-ocean texto-branco padding12 largura15">{{ __('messages.Criação') }}</th>
                                            <th colspan="" class="th-ocean texto-branco padding12 largura15">{{ __('messages.Previsão de Entrega') }}</th>
                                            <th colspan="" class="th-ocean texto-branco padding12">{{ __('messages.Progresso') }}</th>
                                            <th colspan="" class="th-ocean texto-branco padding12 border-right">{{ __('messages.Ações') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="fundo-branco">
                                        @foreach($projetosCoordenando as $prop => $proj)
                                            <tr class="">
                                                <td class="desktop margemL10">{{ $prop+1 }}</td>
                                                <td><a href="{{ route('projetos.show', encrypt($proj->id)) }}" class="texto-preto">{{ $proj->nome  }}</a></td>
                                                {{-- <td class="desktop">{{ $proj->descricao }}</td> --}}
                                                <td class="desktop">{{ $proj->cliente->nome_fantasia }}</td>
                                                <td class="desktop">{{ $proj->created_at ? \Carbon\Carbon::parse($proj->created_at)->format('d.m.Y') : ''}}</td>
                                                <td class="desktop">
                                                    {{$proj->data_previsao_entrega ? \Carbon\Carbon::parse($proj->data_previsao_entrega)->format('d.m.Y') : ''}}</td>
                                                <td>
                                                    <div class="progress">
                                                        <div class="progress-bar progress-bar-animated progresso" role="progressbar" aria-valuenow="{{ $proj->concluido() }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <a href="{{ route('projetos.show', encrypt($proj->id)) }}" class="texto-preto">{{ __('messages.Detalhes') }}</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        {{-- Tab #card--}}
                        <div id="cardProjCoord" class="tab-pane fade  ">
                            <div class="btn-toolbar margemT10 tab-pane fade in" role="toolbar">
                                <div class="col-md-12 painel-jobs">
                                @foreach($projetosCoordenando as $proj_and)
                                    <div class="col-md-6 col-lg-4 col-sm-12 card-job"   data-rota="{{ route('projetos.show', encrypt($proj_and->id)) }}">
                                            <div class="box-group" id="accordion">
                                                <div class="panel box box-primary com-shadow">
                                                    <div class="box-header ">
                                                        <h4><b>{{ __('messages.Projeto') }}: </b>
                                                            <a href="{{ route('projetos.show', encrypt($proj_and->id)) }}">{{ $proj_and->cliente->nome_fantasia }} - {{ $proj_and->nome }}
                                                            </a>
                                                        </h4>
                                                        <span class="float-right text-danger margemLeft">
                                                        </span>
                                                    </div>
                                                    <div id="collapseOne" class="panel-collapse collapse in" aria-expanded="false" >
                                                        <div class="box-body">
                                                            <div class="col-md-4">
                                                            {{-- aqui foir retirado {{ $job->status }} do data-status --}}
                                                                <canvas id="proj-and-chart-{{$proj_and->id}}" class="graficos"  data-status="" width="136" height="136" data-valor="{{ $proj_and->concluido() }}"></canvas>
                                                            </div>
                                                            <div class="col-md-6 col-md-offset-2">

                                                                @isset($proj_and->coordenador)
                                                                    <h4 class="margemT10"><b>{{ __('messages.Coordenador') }}:</b></h4>
                                                                    <p>{{ $proj_and->coordenador->name }}</p>
                                                                @endif
                                                            </div>
                                                            <div class="col-md-12 margemT20">
                                                                <p><b>{{ __('messages.Deadline') }}: </b>
                                                                {{$proj_and->data_previsao_entrega ? \Carbon\Carbon::parse($proj_and->data_previsao_entrega)->format('d.m.Y') :  __('messages.Não Informado') }}</p>
                                                            </div>                                  
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                @endforeach
                                </div> 
                            </div>
                        </div>
                        {{-- Tab #lista--}}
                        <div id="listaProjCoord" class="tab-pane fade  in active">
                            <div class="btn-toolbar margemT10 tab-pane fade in" role="toolbar">
                                @unless(!$projetosCoordenando)
                                    <div id="img-accordion">
                                        <BR>
                                        <table id="lista-dashboard-proj-coord-lista" class="table larguraTotal com-shadow table-striped com-filtro">
                                            <thead>
                                            <th class="th-ocean texto-branco padding12 border-left">
                                            <div class="panel-heading cor-personalizada" >
                                                <div class="row">
                                                    <div class="col-md-2">{{ __('messages.Nome') }}</div>
                                                    <div class="col-md-2">{{ __('messages.Cliente') }}</div>
                                                    <div class="col-md-2">{{ __('messages.Coordenador') }}</div>
                                                    <div class="col-md-2">{{ __('messages.Progresso') }}</div>
                                                    <div class="col-md-2">{{ __('messages.Data de Entrega') }}</div>
                                                    <div class="col-md-2">
                                                        <span class="pull-right titulo-tab-imagens" href="" class="" title="{{ __('messages.Criar Novo Job') }}" data-toggle="tooltip">{{ __('messages.Ação') }}</span>
                                                    </div>
                                                </div>  
                                            </div>
                                            </th>
                                            </thead>
                                            <tbody class="fundo-branco">
                                            @foreach($projetos_andamento as $pro)
                                            <tr class="">
                                                <td class="desktop margemL10 no-border">
                                                    <div class="panel panel-default card-sem-borda card-imagem">
                                                        <div class="panel-heading cor-personalizada" id="panel-img-{{$pro->id}}" style="background: #fff;">
                                                            <a class="larguraTotal alturaTotal texto-preto" data-toggle="collapse" data-target="#collapsepand{{$pro->id}}" aria-expanded="false" aria-controls="#collapsepand{{$pro->id}}" role="button">
                                                            <div class="row">
                                                                <div class="col-md-2">
                                                                    <p class="titulo-tab-imagens">{{ $pro->nome }}
                                                                    </p>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <p class="titulo-tab-imagens">{{ $pro->cliente->nome_fantasia }}
                                                                    </p>
                                                                </div>

                                                                <div class="col-md-2">
                                                                    <div class="progress semMargem cor-personalizada" style="background: #fff;">
                                                                        <div class="progress-bar progress-bar-animated progress-bar-striped progress-bar-success progresso pull-right" role="progressbar" aria-valuenow="{{ $pro->concluido() }}" aria-valuemin="0" aria-valuemax="100">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2">
                                                                     {{$pro->data_previsao_entrega ? \Carbon\Carbon::parse($pro->data_previsao_entrega)->format('d.m.Y') :  __('messages.Não Informado') }}
                                                                </div>
                                                                <div>
                                                                    <div class="col-md-2">
                                                                        <a class="pull-right titulo-tab-imagens link-detalhe" href="{{ route('projetos.show', encrypt($pro->id)) }}" class="" title="{{ __('messages.Detalhes projeto') }}" data-toggle="tooltip">
                                                                            {{ __('messages.Detalhes') }}
                                                                        </a>
                                                                    </div>
                                                                </div>  
                                                            </a>
                                                        </div>

                                                         <div id="collapsepand{{$pro->id}}" class="collapse" aria-labelledby="panel-pro-{{$pro->id}}" data-parent="#img-accordion">
                                                            <div class="panel-body">
                                                                <div class="row margemB10">
                                                                    <div class="col-md-10">
                                                                        <h4>
                                                                            <b>{{ __('messages.Nome') }} :</b> {{ $pro->nome }}
                                                                        </h4>
                                                                    </div>
                                                                    <div class="col-md-2">
                                                                        <a href="{{ route('projetos.show', encrypt($pro->id)) }}" class="btn btn-info pull-right">
                                                                            {{ __('messages.Mais detalhes') }}
                                                                        </a>  
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <h4><b>{{ __('messages.Descrição') }}: </b> {{ $pro->descricao ??  __('messages.Não Informado') }}</h4>
                                                                    </div>
                                                               </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif        
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            
            @if($jobs_parados && $total_jobs_par>0)
                <div class="nav-tabs-custom nav-dashboard margemT50">
                    <h1 class="titulo-principal texto-esquerda titulo-lista-dash">
                        <a class="larguraTotal alturaTotal texto-preto" data-toggle="collapse" data-target="#collapsejobparado" aria-expanded="false" aria-controls="#collapsejobparado" role="button"><span class="accordion-marc"><i class="fa fa-angle-right"></i></span> {{ __('messages.Jobs Parados') }}</a>
                    </h1> 
                    <ul class="nav nav-tabs displayFlex flexEnd paddingR20" id="tabs-projeto" role="tablist">
                        
                        <li class="active in" data-toggle="tooltip" title="{{ __('messages.Listas') }}">
                            <a data-toggle="tab" href="#listaJobsParado" aria-expanded="true" class="nav-link fundo-vermelho" id="imagens-tab" aria-selected="false" > <i class="fa fa-list" aria-hidden="true"></i></a>
                        </li>
                        <li data-toggle="tooltip" title="{{ __('messages.Cards') }}">
                            <a data-toggle="tab" href="#cardJobsParado" aria-expanded="true" class="nav-link fundo-vermelho" id="imagens-tab" aria-selected="false" ><i class="fa fa-credit-card" aria-hidden="true"></i></a>
                        </li>
                        <li data-toggle="tooltip" title="{{ __('messages.Detalhes') }}">
                            <a data-toggle="tab" href="#detalhesJobsParado" aria-expanded="true" class="nav-link fundo-vermelho" id="imagens-tab"  aria-selected="false"><i class="fa fa-info" aria-hidden="true"></i></a>
                        </li>
                    </ul>
                    
                    <div id="collapsejobparado" class="tab-content collapse" aria-labelledby="panel-proand" data-parent="#img-accordion">
                        {{-- Tab #detalhes--}}
                        <div id="detalhesJobsParado" class="tab-pane fade">
                            <div class="btn-toolbar margemT10 tab-pane fade in" role="toolbar">
                                <table id="lista-dashboard-job-parado" class="table table-striped larguraTotal com-filtro-lista">
                                    <thead class="">
                                        <tr class="th-strong-red">
                                            <th colspan="" class="box-title texto-branco padding12 com-border-left largura10">#</th>
                                            <th colspan="" class="box-title texto-branco padding12">{{ __('messages.Nome do Job') }}</th>
                                            <th colspan="" class="box-title texto-branco padding12">{{ __('messages.Colaborador') }}</th>
                                            <th colspan="" class="box-title texto-branco padding12">{{ __('messages.Criação') }}</th>
                                            <th colspan="" class="box-title texto-branco padding12">{{ __('messages.Data de Entrega') }}</th>
                                            <th colspan="" class="box-title texto-branco padding12">{{ __('messages.Progresso') }}</th>
                                            <th colspan="" class="box-title texto-branco padding12 com-border-right">{{ __('messages.">') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="fundo-branco com-shadow">
                                        @foreach($jobs_parados as $job)
                                            <tr class="">
                                                <td class="desktop">#{{ $job->id }}</td>
                                                <td><a href="{{ route('jobs.show', encrypt($job->id)) }}" class="texto-preto">{{ $job->nome  }}</a></td>
                                                <td>
                                                    @if($job->delegado)
                                                        {{$job->delegado->name}}
                                                    @endif
                                                </td>
                                                <td class=" largura15">
                                                    {{$job->created_at ? $job->created_at->format('d.m.Y') : ''}}
                                                </td>
                                                    <td class=" largura15">
                                                    {{$job->data_prox_revisao ? $job->data_prox_revisao->format('d.m.Y') : ''}}
                                                </td>  
                                                <td>
                                                    <div class="progress">
                                                        <div class="progress-bar progress-bar-animated progresso fundo-vermelho"  role="progressbar" aria-valuenow="{{ $job->concluido() }}" aria-valuemin="0" aria-valuemax="100" ></div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <a href="{{ route('jobs.show', encrypt($job->id)) }}"aria-expanded="false" class="word-break texto-preto">{{ __('messages.Detalhes') }}</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- Tab #card--}}
                        <div id="cardJobsParado" class="tab-pane fade  ">
                            <div class="btn-toolbar margemT10 tab-pane fade in" role="toolbar">
                                <div class="col-md-12 painel-jobs">
                                @foreach($jobs_parados as $job)
                                    <div class="col-md-3 col-lg-3 col-sm-12 card-job"  data-rota="{{ route('jobs.show',encrypt($job->id))}}">
                                        <div class="box-group" id="accordion">
                                            <div class="panel box box-danger com-shadow">
                                                <div class="box-header ">
                                                    <div class="col-md-6" align="left">
                                                        <p class="semMargem">
                                                            <a href="{{ route('jobs.show', encrypt($job->id)) }}"aria-expanded="false" class="word-break">{{ $job->nome }}</a> 
                                                        </p>
                                                    </div>
                                                    <div class="col-md-6" align="right">
                                                        <p class="semMargem">
                                                            <a href="{{ route('jobs.show', encrypt($job->id)) }}"aria-expanded="false" class="word-break">
                                                            @if($job->delegado) 
                                                                {{$job->delegado->name}}
                                                            @endif 
                                                            </a> 
                                                        </p>
                                                    </div>
                                                </div>
                                                <div id="collapseOne" class="panel-collapse collapse in" aria-expanded="false" >
                                                    <div class="box-body">
                                                        <div class="col-md-6" align="center">
                                                        @if($job->thumb)
                                                            <img src="{{asset('storage/' . $job->thumb)}}" alt="{{ __('messages.Referência do Job') }}" class="img-responsive card-job-thumb">
                                                        @else
                                                            <img src="{{asset('storage/imagens/jobs/job_default.png')}}" alt="{{ __('messages.Referência do Job') }}" class="img-responsive card-job-thumb">
                                                        @endif
                                                        </div>

                                                        <div class="col-md-6 margemT10 margemB5 ">
                                                            <canvas id="job-and-chart-{{ $job->id }}" data-status="{{ $job->status }}"  class="" width="136" height="136" data-valor="100"></canvas>
                                                        </div>

                    
                                                        <div class="col-md-8 margemT10 margemB5 container-entrega-job">
                                                            <p class="semMargem"><b>{{ __('messages.Deadline') }}: </b>
                                                                @php 
                                                                    $data = $job->data_prox_revisao ?  $job->data_prox_revisao->format('d.m.Y') : __('messages.Não informado') 
                                                                @endphp
                                                            {{ $data }}
                                                            </p>
                                                        </div>    
                                                       <!--  <div class="col-md-12 margemT10 margemB5">
                                                            @forelse($job->tasks as $task) -->
                                                                <!-- <span id="tasks-{{$task->id}}-job-{{$job->id}}" data-nome="{{$task->nome}}" class="task-job-{{$job->id}}">
                                                                </span> -->
                                                            <!-- @empty  
                                                            @endforelse
                                                            </div> -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div> 
                            </div>
                        </div>

                        {{-- Tab #lista--}}
                        <div id="listaJobsParado" class="tab-pane fade  in active">
                            <div class="btn-toolbar margemT10 tab-pane fade in" role="toolbar">
                                @unless(!$jobs_parados)
                                    <div id="img-accordion">
                                        <BR>
                                        <table id="lista-dashboard-job-parado-lista" class="table table-striped larguraTotal com-shadow com-filtro">
                                            <thead>
                                                <th class="th-strong-red texto-branco padding12 border-left">
                                                    <div class="panel-heading cor-personalizada" >
                                                        <div class="row">
                                                            <div class="col-md-4 ">{{ __('messages.Nome') }}</div>
                                                            <div class="col-md-2">
                                                                {{ __('messages.Colaborador') }}
                                                            </div>
                                                            <div class="col-md-2">
                                                                {{ __('messages.Descrição') }}
                                                            </div>
                                                            <div class="col-md-2">{{ __('messages.Progresso') }}</div>
                                                            <div class="col-md-2">
                                                                <span class="pull-right titulo-tab-imagens" href="" class="" title="{{ __('messages.Criar Novo Job') }}" data-toggle="tooltip">{{ __('messages.Ação') }}</span>
                                                            </div>
                                                        </div>  
                                                    </div>
                                                </th>
                                            </thead>
                                            <tbody class="fundo-branco">
                                            @foreach($jobs_parados as $job)
                                                <tr class="">
                                                    <td class="desktop margemL10 no-border">
                                                        <div class="panel panel-default card-sem-borda card-imagem">
                                                            <div class="panel-heading cor-personalizada" id="panel-job-aberto-{{$job->id}}" style="background: #fff;">
                                                                <a class="larguraTotal alturaTotal texto-preto" data-toggle="collapse" data-target="#collapsjobaberto{{$job->id}}" aria-expanded="false" aria-controls="#collapseme{{$job->id}}" role="button">
                                                                    <div class="row">
                                                                        <div class="col-md-4">
                                                                            <p class="titulo-tab-imagens">{{ $job->nome }}
                                                                            </p>
                                                                        </div>
                                                                        <div class="col-md-2">
                                                                            <p class="titulo-tab-imagens">
                                                                            @if($job->delegado)
                                                                                {{$job->delegado->name}}
                                                                            @endif
                                                                            </p>
                                                                        </div>                                                           
                                                                        <div class="col-md-2">
                                                                            <p class="titulo-tab-imagens word-break">{{$job->descricao}}
                                                                            </p>
                                                                        </div>
                                                                        <div class="col-md-2">
                                                                            <div class="progress semMargem cor-personalizada" style="background: #fff;">
                                                                                <div class="progress-bar progress-bar-animated progress-bar-striped progress-bar-success progresso pull-right fundo-vermelho" role="progressbar" aria-valuenow="{{ $job->concluido() }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                                            </div>
                                                                        </div>
                                                                        <div>
                                                                            <div class="col-md-2">
                                                                                <a class="pull-right titulo-tab-imagens link-detalhe" href="{{ route('jobs.show', encrypt($job->id)) }}" class="" title="{{ __('messages.Detalhes do Job') }}" data-toggle="tooltip">
                                                                                {{ __('messages.Detalhes') }}
                                                                            </a>
                                                                        </div>
                                                                    </div>  
                                                                </a>
                                                            </div>

                                                            <div id="collapsjobaberto{{$job->id}}" class="collapse" aria-labelledby="panel-job-aberto-{{$job->id}}" data-parent="#img-accordion">
                                                                <div class="panel-body">
                                                                    <div class="row margemB10">
                                                                        <div class="col-md-10">
                                                                            <h4><b>{{ __('messages.Nome') }} :</b> {{ $job->nome }}</h4>
                                                                        </div>
                                                                        <div class="col-md-2">
                                                                            <a href="{{ route('jobs.show', encrypt($job->id)) }}" class="btn btn-info pull-right">
                                                                                {{ __('messages.Mais detalhes') }}
                                                                            </a>  
                                                                        </div>
                                                                        <div class="col-md-12">
                                                                            <h4><b>{{ __('messages.Descrição') }}: </b> {{ $job->descricao ?? 'Não Informado'}}</h4>
                                                                        </div>
                                                                        <div class="col-md-12">
                                                                            <h4><b>{{ __('messages.Coordenador') }}: </b> {{ $job->coordenador ? $job->coordenador->name : __('messages.Não Informado') }}</h4>
                                                                        </div>
                                                                        <div class="col-md-12">
                                                                            <h4><b>{{ __('messages.Status') }}: </b>
                                                                            {{ __('messages.' . $job->getStatus($job->status)) }}</h4>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif        

                            </div>
                        </div>


                    </div>
                </div>                   
            @endif 

            <!-- Jobs que voce coordena -->
            @if($total_jobs_coo > 0)
                <div class="nav-tabs-custom nav-dashboard margemT50">
                    <h1 class="titulo-principal texto-esquerda titulo-lista-dash">
                        <a class="larguraTotal alturaTotal texto-preto" data-toggle="collapse" data-target="#collapsejobcoord" aria-expanded="false" aria-controls="#collapsejobcoord" role="button"> <span class="accordion-marc"><i class="fa fa-angle-right"></i></span> {{__('messages.Jobs que Coordena')}}</a>
                    </h1>
                    <ul class="nav nav-tabs displayFlex flexEnd paddingR20" id="tabs-projeto" role="tablist">
                        <li class="active in" data-toggle="tooltip" title="{{ __('messages.Listas') }}">
                            <a data-toggle="tab" href="#listaJobCoordena" aria-expanded="true" class="nav-link fundo-laranja" id="imagens-tab" aria-selected="false" > <i class="fa fa-list" aria-hidden="true"></i></a>
                        </li>
                        <li data-toggle="tooltip" title="{{ __('messages.Cards') }}">
                            <a data-toggle="tab" href="#cardJobCoordena" aria-expanded="true" class="nav-link fundo-laranja" id="imagens-tab" aria-selected="false" ><i class="fa fa-credit-card" aria-hidden="true"></i></a>
                        </li>
                        <li data-toggle="tooltip" title="{{ __('messages.Detalhes') }}">
                            <a data-toggle="tab" href="#detalhesJobCoordena" aria-expanded="true" class="nav-link fundo-laranja" id="imagens-tab" aria-selected="false"><i class="fa fa-info" aria-hidden="true"></i></a>
                        </li>
                    </ul>
                    <div id="collapsejobcoord" class="tab-content collapse" aria-labelledby="panel-proand" data-parent="#img-accordion">
                        {{-- Tab #detalhes--}}
                        <div id="detalhesJobCoordena" class="tab-pane fade">
                            <div class="btn-toolbar margemT10 tab-pane fade in" role="toolbar">
                                <table id="lista-dashboard-job-coord" class="table larguraTotal com-filtro-lista table-striped">
                                    <thead class="">
                                        <tr class="th-green">
                                            <th colspan="" class="box-title texto-branco padding12 com-border-left largura10">#</th>
                                            <th colspan="" class="box-title texto-branco padding12">{{ __('messages.Nome do Job') }}</th>
                                            <th colspan="" class="box-title texto-branco padding12">{{ __('messages.Colaborador') }}</th>
                                            <th colspan="" class="box-title texto-branco padding12">{{ __('messages.Descrição') }}</th>
                                            <th colspan="" class="box-title texto-branco padding12">{{ __('messages.Data de entrega') }}</th>
                                            <th colspan="" class="box-title texto-branco padding12">{{ __('messages.Progresso') }}</th>
                                            <th colspan="" class="box-title texto-branco padding12 com-border-right">{{ __('messages.Ações') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="fundo-branco com-shadow">
                                        @foreach($coordenando as $job)
                                            <tr class="">
                                                <td class="desktop">#{{ $job->id }}</td>
                                                <td><a href="{{ route('jobs.show', encrypt($job->id)) }}" class="texto-preto">{{ $job->nome  }}</a></td>
                                                <td> 
                                                @if($job->delegado)
                                                    {{$job->delegado->name}}
                                                @endif
                                                                </td>
                                                <td class="desktop">{{ $job->descricao }}</td>
                                                <td class=" largura15">
                                                    {{$job->created_at ? $job->created_at->format('d.m.Y') : __('messages.Não informado')}}
                                                </td>

                                                <td>
                                                    <div class="progress">
                                                        <div class="progress-bar progress-bar-animated progresso" role="progressbar" aria-valuenow="{{ $job->concluido() }}" aria-valuemin="0" aria-valuemax="100" ></div>
                                                    </div>
                                                </td>
                                                <td>
                                                     <a href="{{ route('jobs.show', encrypt($job->id)) }}"aria-expanded="false" class="word-break texto-preto">{{ __('messages.Detalhes') }}</a>

                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- Tab #card--}}
                        <div id="cardJobCoordena" class="tab-pane fade  ">
                            <div class="btn-toolbar margemT10 tab-pane fade in" role="toolbar">
                                <div class="col-md-12 painel-jobs">
            
                                @foreach($coordenando as $job)
                                    <div class="col-md-3 col-lg-3 col-sm-12 card-job"  data-rota="{{ route('jobs.show', encrypt($job->id)) }}">
                                        <div class="box-group" id="accordion">
                                            <div class="panel box box-success com-shadow">
                                                <div class="box-header ">
                                                    <div class="col-md-6" align="left">
                                                        <p class="semMargem">
                                                            <a href="{{ route('jobs.show', encrypt($job->id)) }}"aria-expanded="false" class="word-break">{{ $job->nome }}</a> 

                                                        </p>
                                                    </div>
                                                    <div class="col-md-6" align="right">
                                                        <p class="semMargem">
                                                            <a href="{{ route('jobs.show', encrypt($job->id)) }}"aria-expanded="false" class="word-break">
                                                            @if($job->delegado) 
                                                                {{$job->delegado->name}}
                                                            @endif 
                                                            </a> 
                                                        </p>
                                                    </div>
                                                </div>
                                                <div id="collapseOne" class="panel-collapse collapse in" aria-expanded="false" >
                                                    <div class="box-body">
                                                        <div class="col-md-6" align="center">
                                                            @if($job->thumb)
                                                                <img src="{{asset('storage/' . $job->thumb)}}" alt="{{ __('messages.Referência do Job') }}" class="img-responsive card-job-thumb">
                                                            @else
                                                                <img src="{{asset('storage/imagens/jobs/job_default.png')}}" alt="{{ __('messages.Referência do Job') }}" class="img-responsive card-job-thumb">
                                                            @endif

                                                        </div>
                                                        <div class="col-md-6 margemT10 margemB5 ">
                                                            <canvas id="job-and-chart-{{$job->id}}" class="graficos"  data-status="{{ $job->status }}" width="136" height="136" data-valor="{{ $job->concluido() }}"></canvas>
                                                        </div>

                                                        <div class="col-md-8 margemT20 margemB5 container-entrega-job">
                                                            <p class="semMargem"><b>{{ __('messages.Deadline') }}: </b>
                                                            @php 
                                                                $data = $job->data_prox_revisao ? $job->data_prox_revisao->format('d.m.Y') : __('messages.Não informado') 
                                                            @endphp
                                                            {{ $data }}
                                                            </p>
                                                        </div>    
                                                        @if($job->tasks)
                                                        <!-- <div class="col-md-12 margemT20 margemB5">
                                                      
                                                            @forelse($job->tasks as $task)
                                                                <span id="tasks-{{$task->id}}-job-{{$job->id}}" data-nome="{{$task->nome}}" class="task-job-{{$job->id}}">
                                                                </span>
                                                            @empty  
                                                            @endforelse
                                                        </div>  -->
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                </div> 
                            </div>
                        </div>

                        {{-- Tab #lista--}}
                        <div id="listaJobCoordena" class="tab-pane fade  in active">
                            <div class="btn-toolbar margemT10 tab-pane fade in" role="toolbar">
                                @unless(!$coordenando)
                                <div id="img-accordion">
                                    <BR>
                                    <table id="lista-dashboard-job-coord-lista" class="table table-striped larguraTotal com-shadow com-filtro" >
                                        <thead>
                                            <th class="th-green texto-branco padding12 border-left">
                                                <div class="panel-heading cor-personalizada" >
                                                    <div class="row">
                                                        <div class="col-md-4">{{ __('messages.Nome') }}</div>
                                                        <div class="col-md-2">{{ __('messages.Colaborador') }}</div>
                                                        <div class="col-md-2">{{ __('messages.Progresso') }}</div>
                                                        <div class="col-md-2">{{ __('messages.Data de entrega') }}</div>
                                                        <div class="col-md-2">
                                                            <span class="pull-right titulo-tab-imagens" href="" class="" >{{ __('messages.Ação') }}</span>
                                                        </div>
                                                    </div>  
                                                </div>
                                            </th>
                                        </thead>
                                        <tbody class="fundo-branco">
                                        @foreach($coordenando as $job)
                                        <tr class="">
                                            <td class="desktop margemL10 no-border">
                                                <div class="panel panel-default card-sem-borda card-imagem">
                                                    <div class="panel-heading cor-personalizada" id="panel-job-aberto-{{$job->id}}" style="background: #fff;">
                                                        <a class="larguraTotal alturaTotal texto-preto" data-toggle="collapse" data-target="#collapsjobaberto{{$job->id}}" aria-expanded="false" aria-controls="#collapseme{{$job->id}}" role="button">
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <p class="titulo-tab-imagens" >{{ $job->nome }}
                                                                    </p>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <p class="titulo-tab-imagens"> 
                                                                        @if($job->delegado)
                                                                            {{$job->delegado->name}}
                                                                        @endif
                                                                    </p>
                                                                </div>

                                                                <div class="col-md-2">
                                                                    <div class="progress semMargem cor-personalizada" style="background: #fff;">
                                                                        <div class="progress-bar progress-bar-animated progress-bar-striped progress-bar-success progresso pull-right" role="progressbar" aria-valuenow="{{ $job->concluido() }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2">
                                                                     {{$job->data_prox_revisao ? $job->data_prox_revisao->format('d.m.Y') : __('messages.Não informado')}} 
                                                                </div>
                                                                <div>
                                                                    <div class="col-md-2">
                                                                        <a class="pull-right titulo-tab-imagens link-detalhe" href="{{ route('jobs.show', encrypt($job->id)) }}" title="Detalhes job" data-toggle="tooltip">
                                                                        {{ __('messages.Detalhes') }}
                                                                    </a>
                                                                </div>
                                                            </div>  
                                                        </a>
                                                    </div>

                                                    <div id="collapsjobaberto{{$job->id}}" class="collapse" aria-labelledby="panel-job-aberto-{{$job->id}}" data-parent="#img-accordion">
                                                        <div class="panel-body">
                                                            <div class="row margemB10">
                                                                <div class="col-md-10">
                                                                    <h4><b>{{ ('messages.Nome') }} :</b> {{ $job->nome }}</h4>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <a href="{{ route('jobs.show', encrypt($job->id)) }}" class="btn btn-info pull-right">
                                                                        {{ ('messages.Mais detalhes') }}
                                                                    </a>  
                                                                </div>
     
                                                                <div class="col-md-12">
                                                                    <h4><b>{{ __('messages.Descrição') }}: </b> {{ $job->descricao ?? __('messages.Não Informado')}}</h4>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <h4><b>{{ __('messages.Coordenador') }}: </b> {{ $job->coordenador->nome ? $job->coordenador->nome : __('messages.Não Informado') }}</h4>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <h4><b>{{ __('messages.Status') }}: </b>
                                                                    {{ __('messages.' . $job->getStatus($job->status)) }}</h4>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @endif        

                            </div>
                        </div>
                    </div>
                </div>
            @endif
                            
            <!-- Jobs que voce avalia -->
            @if($total_jobs_ava > 0)
                <div class="nav-tabs-custom nav-dashboard margemT50">
                    <h1 class="titulo-principal texto-esquerda titulo-lista-dash">
                        <a class="larguraTotal alturaTotal texto-preto" data-toggle="collapse" data-target="#collapsejobavali" aria-expanded="false" aria-controls="#collapsejobavali" role="button"><span class="accordion-marc"><i class="fa fa-angle-right"></i></span> Jobs que Avalia</a>
                    </h1>
                    <ul class="nav nav-tabs displayFlex flexEnd paddingR20" id="tabs-projeto" role="tablist">
                        <li class="active in" data-toggle="tooltip" title="{{ __('messages.Listas') }}">
                            <a data-toggle="tab" href="#listaJobsAvaliando" aria-expanded="true" class="nav-link" id="imagens-tab" aria-selected="false" > <i class="fa fa-list" aria-hidden="true"></i></a>
                        </li>
                        <li data-toggle="tooltip" title="{{ __('messages.Cards') }}">
                            <a data-toggle="tab" href="#cardJobsAvaliando" aria-expanded="true" class="nav-link" id="imagens-tab" aria-selected="false" ><i class="fa fa-credit-card" aria-hidden="true"></i></a>
                        </li>
                        <li data-toggle="tooltip" title="{{ __('messages.Detalhes') }}">
                            <a data-toggle="tab" href="#detalhesJobsAvaliando" aria-expanded="true" class="nav-link" id="imagens-tab" aria-selected="false"><i class="fa fa-info" aria-hidden="true"></i></a>
                        </li>
                    </ul>
                    <div id="collapsejobavali" class="tab-content collapse" aria-labelledby="panel-proand" data-parent="#img-accordion">
                        {{-- Tab #detalhes--}}
                        <div id="detalhesJobsAvaliando" class="tab-pane fade">
                            <div class="btn-toolbar margemT10 tab-pane fade in" role="toolbar">
                                 <table id="lista-dashboard-job-avali" class="table table-striped larguraTotal com-filtro-lista">
                                        <thead class="">
                                            <tr class="th-green">
                                                <th colspan="" class="box-title texto-branco padding12 com-border-left largura10">#</th>
                                                <th colspan="" class="box-title texto-branco padding12">{{ __('messages.Nome do Job') }}</th>
                                                <th colspan="" class="box-title texto-branco padding12">{{ __('messages.Colaborador') }}</th>
                                                <th colspan="" class="box-title texto-branco padding12">{{ __('messages.Descrição') }}</th>
                                                <th colspan="" class="box-title texto-branco padding12 largura15">{{ __('messages.Criação') }}</th>
                                                <th colspan="" class="box-title texto-branco padding12 largura15">{{ __('messages.Data de Entrega') }}</th>
                                                <th colspan="" class="box-title texto-branco padding12">{{ __('messages.Progresso') }}</th>
                                                <th colspan="" class="box-title texto-branco padding12 com-border-right">{{ __('messages.Ações') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody class="fundo-branco com-shadow">
                                            @foreach($avaliando as $job)
                                                <tr class="">
                                                    <td class="desktop">#{{ $job->id }}</td>
                                                    <td><a href="{{ route('jobs.show', encrypt($job->id)) }}" class="texto-preto">{{ $job->nome  }}</a></td>
                                                    <td>
                                                    @if($job->delegado)
                                                        {{$job->delegado->name}}
                                                    @endif
                                                    </td>
                                                    <td class="desktop">{{ $job->descricao }}</td>
                                                    <td class=" largura15">
                                                        {{$job->created_at ? $job->created_at->format('d.m.Y') : ''}}
                                                    </td>
                                                    <td class=" largura15">
                                                        {{$job->data_prox_revisao ? $job->data_prox_revisao->format('d.m.Y') : ''}}
                                                    </td>
                                                    <td>
                                                        <div class="progress">
                                                            <div class="progress-bar progress-bar-animated progresso" role="progressbar" aria-valuenow="{{ $job->concluido() }}" aria-valuemin="0" aria-valuemax="100" ></div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                         <a href="{{ route('jobs.show', encrypt($job->id)) }}"aria-expanded="false" class="word-break texto-preto">{{ __('messages.Detalhes') }}</a>

                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                            </div>
                        </div>

                        {{-- Tab #card--}}
                        <div id="cardJobsAvaliando" class="tab-pane fade  ">
                            <div class="btn-toolbar margemT10 tab-pane fade in" role="toolbar">
                                <div class="col-md-12 painel-jobs">
            
                                 @foreach($avaliando as $job)
                                            <div class="col-md-3 col-lg-3 col-sm-12 card-job"  data-rota="{{ route('jobs.show', encrypt($job->id)) }}">
                                                <div class="box-group" id="accordion">
                                                    <div class="panel box box-success com-shadow">
                                                        <div class="box-header ">
                                                    <div class="col-md-6" align="left">
                                                        <p class="semMargem">
                                                            <a href="{{ route('jobs.show', encrypt($job->id)) }}"aria-expanded="false" class="word-break">{{ $job->nome }}</a> 

                                                        </p>
                                                    </div>
                                                    <div class="col-md-6" align="right">
                                                        <p class="semMargem">
                                                            <a href="{{ route('jobs.show', encrypt($job->id)) }}"aria-expanded="false" class="word-break">
                                                            @if($job->delegado) 
                                                                {{$job->delegado->name}}
                                                            @endif 
                                                            </a> 
                                                        </p>
                                                    </div>
                                                </div>
                                                        <div id="collapseOne" class="panel-collapse collapse in" aria-expanded="false" >
                                                            <div class="box-body">
                                                                <div class="col-md-6" align="center">
                                                                    @if($jo->thumb)
                                                                        <img src="{{asset('storage/' . $job->thumb)}}" alt="{{ __('messages.Referência do Job') }}" class="img-responsive card-job-thumb">
                                                                    @else
                                                                        <img src="{{asset('storage/imagens/jobs/job_default.png')}}" alt="{{ __('messages.Referência do Job') }}" class="img-responsive card-job-thumb">
                                                                    @endif
                                                                </div>
                                                                <div class="col-md-6 margemT10 margemB5 ">
                                                                    <canvas id="job-and-chart-{{$job->id}}" class="graficos"  data-status="{{ $job->status }}" width="136" height="136" data-valor="{{ $job->concluido() }}"></canvas>
                                                                </div>
                                                                <div class="col-md-8 margemT20 margemB5 container-entrega-job">
                                                                    <p class="semMargem"><b>{{ __('messages.Deadline') }}: </b>
                                                                    @php 
                                                                        $data = $job->data_prox_revisao ?  $job->data_prox_revisao->format('d.m.Y') : __('messages.Não informado') 
                                                                    @endphp
                                                                    {{ $data }}</p>
                                                                </div>    
                                                                <!-- <div class="col-md-12 margemT20 margemB5">

                                                                    @forelse($job->tasks as $task) -->
                                                                       <!-- <span 
                                                                            id="tasks-{{$task->id}}-job-{{$job->id}}" 
                                                                            data-nome="{{$task->nome}}"
                                                                            class="task-job-{{$job->id}}">
                                                                        </span> --> 
                                                                    <!-- @empty  
                                                                    @endforelse
                                                                </div>  -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                            


                                </div> 
                            </div>
                        </div>

                        {{-- Tab #lista--}}
                        <div id="listaJobsAvaliando" class="tab-pane fade  in active">
                            <div class="btn-toolbar margemT10 tab-pane fade in" role="toolbar">
                                @unless(!$avaliando)
                                <div id="img-accordion">
                                    <BR>
                                    <table id="lista-dashboard-job-avali-lista" class="table table-striped larguraTotal com-shadow com-filtro">
                                        <thead>
                                            <th class="th-green texto-branco padding12 border-left">
                                                <div class="panel-heading cor-personalizada" >
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                        {{ __('messages.Nome') }}</div>
                                                        <div class="col-md-2">{{ __('messages.Colaborador') }}</div>
                                                        <div class="col-md-2">{{ __('messages.Progresso') }}</div>
                                                        <div class="col-md-2">
                                                            <span class="pull-right titulo-tab-imagens" href="" class="" title="{{ __('messages.Criar Novo Job') }}" data-toggle="tooltip">{{ __('messages.Ação') }}</span>
                                                        </div>
                                                    </div>  
                                                </div>
                                            </th>
                                        </thead>
                                        <tbody class="fundo-branco">
                                        @foreach($avaliando as $job)
                                        <tr class="">
                                            <td class="desktop margemL10 no-border">
                                                <div class="panel panel-default card-sem-borda card-imagem">
                                                    <div class="panel-heading cor-personalizada" id="panel-job-aberto-{{$job->id}}" style="background: #fff;">
                                                        <a class="larguraTotal alturaTotal texto-preto" data-toggle="collapse" data-target="#collapsjobaberto{{$job->id}}" aria-expanded="false" aria-controls="#collapseme{{$job->id}}" role="button">
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <p class="titulo-tab-imagens">{{ $job->nome }}
                                                                    </p>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <p class="titulo-tab-imagens"> @if($job->delegado)
                                                                        {{$job->delegado->name}}
                                                                    @endif
                                                                    </p>
                                                                </div> 
                                                                <div class="col-md-2">
                                                                    <div class="progress semMargem cor-personalizada" style="background: #fff;">
                                                                        <div class="progress-bar progress-bar-animated progress-bar-striped progress-bar-success progresso pull-right" role="progressbar" aria-valuenow="{{ $job->concluido() }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                                    </div>
                                                                </div>
                                                                <div>
                                                                    <div class="col-md-2">
                                                                        <a class="pull-right titulo-tab-imagens link-detalhe" href="{{ route('jobs.show', encrypt($job->id)) }}" class="" title="{{ __('messages.Detalhes do Job') }}" data-toggle="tooltip">
                                                                        {{ __('messages.Detalhes') }}
                                                                    </a>
                                                                </div>
                                                            </div>  
                                                        </a>
                                                    </div>

                                                     <div id="collapsjobaberto{{$job->id}}" class="collapse" aria-labelledby="panel-job-aberto-{{$job->id}}" data-parent="#img-accordion">
                                                        <div class="panel-body">
                                                            <div class="row margemB10">
                                                                <div class="col-md-10">
                                                                    <h4><b>{{ __('messages.Nome') }} :</b> {{ $job->nome }}</h4>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <a href="{{ route('jobs.show', encrypt($job->id)) }}" class="btn btn-info pull-right">
                                                                        {{ __('messages.Mais detalhes') }}
                                                                    </a>  
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <h4><b>{{ __('messages.Descrição') }}: </b> {{ $job->descricao ?? __('messages.Não Informado')}}</h4>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <h4><b>{{ __('messages.Coordenador') }}: </b> {{ $job->coordenador->nome ? $job->coordenador->nome : __('messages.Não Informado') }}</h4>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <h4><b>{{ __('messages.Status') }}: </b>
                                                                    {{ __('messages.' . $job->getStatus($job->status)) }}</h4>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @endif        

                            </div>
                        </div>
                    </div>
                </div>
            @endif 

            <!-- Jobs que voce executa -->
            @if($total_jobs_exe>0)
                <div class="nav-tabs-custom nav-dashboard margemT50">
                    <h1 class="titulo-principal texto-esquerda titulo-lista-dash">
                        <a class="larguraTotal alturaTotal texto-preto" data-toggle="collapse" data-target="#collapsejobexec" aria-expanded="false" aria-controls="#collapsejobexec" role="button"><span class="accordion-marc"><i class="fa fa-angle-right"></i></span> {{ __('messages.Jobs em Execução') }}</a>
                    </h1>
                    <ul class="nav nav-tabs displayFlex flexEnd paddingR20" id="tabs-projeto" role="tablist">
                        <li class="active in" data-toggle="tooltip" title="{{ __('messages.Listas') }}">
                            <a data-toggle="tab" href="#listaJobsExecutando" aria-expanded="true" class="nav-link fundo-verde-escuro" id="imagens-tab" aria-selected="false" > <i class="fa fa-list" aria-hidden="true"></i></a>
                        </li>
                        <li data-toggle="tooltip" title="{{ __('messages.Cards') }}">
                            <a data-toggle="tab" href="#cardJobsExecutando" aria-expanded="true" class="nav-link fundo-verde-escuro" id="imagens-tab" aria-selected="false" ><i class="fa fa-credit-card" aria-hidden="true"></i></a>
                        </li>
                        <li data-toggle="tooltip" title="{{ __('messages.Detalhes') }}">
                            <a data-toggle="tab" href="#detalhesJobsExecutando" aria-expanded="true" class="nav-link fundo-verde-escuro" id="imagens-tab"  aria-selected="false"><i class="fa fa-info" aria-hidden="true"></i></a>
                        </li>
                    </ul>
                    <div id="collapsejobexec" class="tab-content collapse" aria-labelledby="panel-proand" data-parent="#img-accordion">
                        {{-- Tab #detalhes--}}
                      
                        <div id="detalhesJobsExecutando" class="tab-pane fade">
                            <div class="btn-toolbar margemT10 tab-pane fade in" role="toolbar">
                                 <table id="lista-dashboard-job-exec" class="table table-striped larguraTotal search-table"> 
                                     {{-- com-filtro-lista --}}
                                        <thead class="">
                                            <tr class="th-green">
                                                <th colspan="" class="box-title texto-branco padding12 com-border-left largura10">#</th>
                                                <th colspan="" class="box-title texto-branco padding12">{{ __('messages.Nome do Job') }}</th>
                                                <th colspan="" class="box-title texto-branco padding12 largura15">{{ __('messages.Colaborador') }}</th>
                                                <th colspan="" class="box-title texto-branco padding12">{{ __('messages.Descrição') }}</th>
                                                                                          
                                                
                                                <th colspan="" class="box-title texto-branco padding12 largura15">{{ __('messages.Criação') }}</th>
                                                <th colspan="" class="box-title texto-branco padding12 largura15">{{ __('messages.Data de Entrega') }}</th>

                                                <th colspan="" class="box-title texto-branco padding12">{{ __('messages.Progresso') }}</th>
                                                <th colspan="" class="box-title texto-branco padding12 com-border-right">{{ __('messages.Ações') }}</th>
                                                @can('concluir-job')
                                                    @if(isset($concluir_job) && $concluir_job)
                                                        <th colspan="" class="box-title padding12 com-border-right">
                                                            <form action="{{ route('job.mudarStatus.varios') }}" name="form_concluir_jobs" id="form-concluir-jobs" method="post">
                                                                @csrf
                                                                <button type="submit" disabled="true" data-toggle="tooltip" title="{{ __('messages.Concluir') }}" class="btn btn-default margemR20" id="button-jobs-concluir"><i class="fa fa-check-circle" aria-hidden="true"></i></button>
                                                            </form>
                                                            {{-- <button type="button" class="botao-job-concluir" value="" > teste</button>
                                                            <input type="checkbox" name="concluir_job" id="concluir-job-" class="job-concluir" value="7" > --}}
                                                        
                                                        </th>
                                                    @endif
                                                @endcan
                                            </tr>
                                        </thead>
                                        <tbody class="fundo-branco com-shadow">
                                            @foreach($executando as $job)
                                                <tr class="">
                                                    <td class="desktop">#{{ $job->id }}</td>
                                                    <td><a href="{{ route('jobs.show', encrypt($job->id)) }}" class="texto-preto">{{ $job->nome  }}</a></td>
                                                    <td>
                                                        @if($job->delegado)
                                                            {{$job->delegado->name}}
                                                        @endif
                                                    </td>
                                                    <td class="desktop">{{ strip_tags ($job->descricao) }}</td>
                                                    <td class=" largura15">
                                                        {{$job->created_at ? $job->created_at->format('d.m.Y') : ''}}
                                                    </td>
                                                    <td class=" largura15">
                                                        {{$job->data_prox_revisao ? $job->data_prox_revisao->format('d.m.Y') : ''}}
                                                    </td>
                                                    <td>
                                                        <div class="progress">
                                                            <div class="progress-bar progress-bar-animated progresso" role="progressbar" aria-valuenow="{{ $job->concluido() }}" aria-valuemin="0" aria-valuemax="100" ></div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                         <a href="{{ route('jobs.show', encrypt($job->id)) }}"aria-expanded="false" class="word-break texto-preto">{{ __('messages.Detalhes') }}</a>

                                                    </td>
                                                    @can('concluir-job')
                                                        @if(isset($concluir_job) && $concluir_job)
                                                            <td class="displayFlex flexCentralizado">
                                                                @if($job->pode_concluir)
                                                                    <input type="checkbox" name="concluir_job" id="concluir-job-{{ $job->id }}" class="job-concluir-lista" value="{{ $job->id }}">
                                                                @endif
                                                            </td>
                                                        @endif
                                                    @endcan
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                            </div>
                        </div>

                        {{-- Tab #card--}}
                        <div id="cardJobsExecutando" class="tab-pane fade  ">
                            <div class="btn-toolbar margemT10 tab-pane fade in" role="toolbar">
                                <div class="col-md-12 painel-jobs">
            
                                 @foreach($executando as $job)
                                            <div class="col-md-3 col-lg-3 col-sm-12 card-job"  data-rota="{{ route('jobs.show', encrypt($job->id)) }}">
                                                <div class="box-group" id="accordion">
                                                    <div class="panel box box-success com-shadow">
                                                        <div class="box-header ">
                                                    <div class="col-md-6" align="left">
                                                        <p class="semMargem">
                                                            <a href="{{ route('jobs.show', encrypt($job->id)) }}"aria-expanded="false" class="word-break">{{ $job->nome }}</a> 

                                                        </p>
                                                    </div>
                                                    <div class="col-md-6" align="right">
                                                        <p class="semMargem">
                                                            <a href="{{ route('jobs.show', encrypt($job->id)) }}"aria-expanded="false" class="word-break">
                                                            @if($job->delegado) 
                                                                {{$job->delegado->name}}
                                                            @endif 
                                                            </a> 
                                                        </p>
                                                    </div>
                                                </div>
                                                        <div id="collapseOne" class="panel-collapse collapse in" aria-expanded="false" >
                                                            <div class="box-body">
                                                                <div class="col-md-6" align="center">
                                                                    @if($job->thumb)
                                                                        <img src="{{asset('storage/' . $job->thumb)}}" alt="{{ __('messages.Referência do Job') }}" class="img-responsive card-job-thumb">
                                                                    @else
                                                                        <img src="{{asset('storage/imagens/jobs/job_default.png')}}" alt="{{ __('messages.Referência do Job') }}" class="img-responsive card-job-thumb">
                                                                    @endif
                                                                </div>

                                                                <div class="col-md-6 margemT10 margemB5 ">
                                                                    <canvas id="job-and-chart-{{$job->id}}" class="graficos"  data-status="{{ $job->status }}" width="136" height="136" data-valor="{{ $job->concluido() }}"></canvas>
                                                                </div>

                                                                <div class="col-md-8 margemT20 margemB5 container-entrega-job">
                                                                    <p class="semMargem"><b>{{ __('messages.Deadline') }}: </b>
                                                                        @php 
                                                                            $data = $job->data_prox_revisao ?  $job->data_prox_revisao->format('d.m.Y') : __('messages.Não informado') 
                                                                        @endphp
                                                                    {{ $data }}</p>
                                                                </div>    
                                                                <!-- <div class="col-md-12 margemT10 margemB5">
                                                                @forelse($job->tasks as $task)
                                                                <span id="tasks-{{$task->id}}-job-{{$job->id}}"   data-nome="{{$task->nome}}" class="task-job-{{$job->id}}"> </span>
                                                                @empty  
                                                                @endforelse
                                                                </div>  -->  
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                </div> 
                            </div>
                        </div>

                        {{-- Tab #lista--}}
                        <div id="listaJobsExecutando" class="tab-pane fade  in active">
                            <div class="btn-toolbar margemT10 tab-pane fade in" role="toolbar">
                                @unless(!$executando)
                                <div id="img-accordion">
                                    <br>
                                    <table id="lista-dashboard-job-exec-lista" class="table table-striped larguraTotal com-shadow com-filtro">
                                        <thead>
                                            <th class="th-green texto-branco padding12 border-left">
                                                <div class="panel-heading cor-personalizada" >
                                                    <div class="row">
                                                        <div class="col-md-4">{{ __('messages.Nome') }}</div>
                                                        <div class="col-md-2">{{ __('messages.Colaborador') }}</div>
                                                        <div class="col-md-2">{{ __('messages.Progresso') }}</div>
                                                        <div class="col-md-2">
                                                            <span class="pull-right titulo-tab-imagens" href="" class="" title="{{ __('messages.Criar Novo Job') }}" data-toggle="tooltip">{{ __('messages.Ação') }}</span>
                                                        </div>
                                                    </div>  
                                                </div>
                                            </th>
                                        </thead>
                                        <tbody class="fundo-branco">
                                        @foreach($executando as $job)
                                        <tr class="">
                                            <td class="desktop margemL10 no-border">
                                                <div class="panel panel-default card-sem-borda card-imagem">
                                                    <div class="panel-heading cor-personalizada" id="panel-job-aberto-{{$job->id}}" style="background: #fff;">
                                                        <a class="larguraTotal alturaTotal texto-preto" data-toggle="collapse" data-target="#collapsjobaberto{{$job->id}}" aria-expanded="false" aria-controls="#collapseme{{$job->id}}" role="button">
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <p class="titulo-tab-imagens">{{ $job->nome }}
                                                                    </p>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <p class="titulo-tab-imagens"> 
                                                                    @if($job->delegado)
                                                                        {{$job->delegado->name}}
                                                                    @endif
                                                                    </p>
                                                                </div>

                                                                <div class="col-md-2">
                                                                    <div class="progress semMargem cor-personalizada" style="background: #fff;">
                                                                        <div class="progress-bar progress-bar-animated progress-bar-striped progress-bar-success progresso pull-right" role="progressbar" aria-valuenow="{{ $job->concluido() }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                                    </div>
                                                                </div>
                                                                <div>
                                                                    <div class="col-md-2">
                                                                        <a class="pull-right titulo-tab-imagens link-detalhe" href="{{ route('jobs.show', encrypt($job->id)) }}" class="" title="{{ __('messages.Detalhes do Job') }}" data-toggle="tooltip">
                                                                        {{ __('messages.Detalhes') }}
                                                                    </a>
                                                                </div>
                                                            </div>  
                                                        </a>
                                                    </div>

                                                     <div id="collapsjobaberto{{$job->id}}" class="collapse" aria-labelledby="panel-job-aberto-{{$job->id}}" data-parent="#img-accordion">
                                                        <div class="panel-body">
                                                            <div class="row margemB10">
                                                                <div class="col-md-10">
                                                                    <h4><b>{{ __('messages.Nome') }} :</b> {{ $job->nome }}</h4>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <a href="{{ route('jobs.show', encrypt($job->id)) }}" class="btn btn-info pull-right">
                                                                        {{ __('messages.Mais detalhes') }}
                                                                    </a>  
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <h4><b>{{ __('messages.Descrição') }}: </b> {{ $job->descricao ?? __('messages.Não Informado')}}</h4>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <h4><b>{{ __('messages.Coordenador') }}: </b> {{ $job->coordenador ? $job->coordenador->name : __('messages.Não Informado') }}</h4>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <h4><b>{{ __('messages.Status') }}: </b>
                                                                    {{ __('messages.' . $job->getStatus($job->status)) }}</h4>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @endif        

                            </div>
                        </div>
                    </div>
                </div>
            @endif


            {{-- Jobs em andamento apenas para admin e dev --}}
            {{-- {{dd($jobs_andamento)}} --}}
            @if($total_jobs_and>0)
                <div class="nav-tabs-custom nav-dashboard margemT50">
                    <h1 class="titulo-principal texto-esquerda titulo-lista-dash">  
                        <a class="larguraTotal alturaTotal texto-preto" data-toggle="collapse" data-target="#collapsejobanda" aria-expanded="false" aria-controls="#collapsejobanda" role="button"><span class="accordion-marc"><i class="fa fa-angle-right"></i></span>{{ __("messages.Jobs em Andamento") }}</a>
                    </h1>
                    <ul class="nav nav-tabs displayFlex flexEnd paddingR20" id="tabs-projeto" role="tablist">
                        <li class="active in" data-toggle="tooltip" title="{{ __('messages.Listas') }}">
                            <a data-toggle="tab" href="#listaJobsExecutando" aria-expanded="true" class="nav-link fundo-laranja" id="imagens-tab" aria-selected="false" > <i class="fa fa-list" aria-hidden="true"></i></a>
                        </li>
                        <li data-toggle="tooltip" title="{{ __('messages.Cards') }}">
                            <a data-toggle="tab" href="#cardJobsExecutando" aria-expanded="true" class="nav-link fundo-laranja" id="imagens-tab" aria-selected="false" ><i class="fa fa-credit-card" aria-hidden="true"></i></a>
                        </li>
                        <li data-toggle="tooltip" title="{{ __('messages.Detalhes') }}">
                            <a data-toggle="tab" href="#detalhesJobsExecutando" aria-expanded="true" class="nav-link fundo-laranja" id="imagens-tab"  aria-selected="false"><i class="fa fa-info" aria-hidden="true"></i></a>
                        </li>
                    </ul>
                    <div id="collapsejobanda" class="tab-content collapse" aria-labelledby="panel-proand" data-parent="#img-accordion">
                        {{-- Tab #detalhes--}}
                        <form action="{{route('job.mudarStatus.varios')}}" id="form-concluir-job" method="post">
                            @csrf
                        </form>
                        <div id="detalhesJobsExecutando" class="tab-pane fade">
                            <div class="btn-toolbar margemT10 tab-pane fade in" role="toolbar">
                                 <table id="lista-dashboard-job-and" class="table larguraTotal com-filtro-lista table-striped">
                                    <thead class="">
                                        <tr class="th-orange">
                                            <th class="box-title texto-branco padding12 com-border-left largura10 ">#</th>
                                            <th class="box-title texto-branco padding12">{{ __('messages.Nome do Job') }}</th>
                                            <th class="box-title texto-branco padding12 largura15">{{ __('messages.Colaborador') }}</th>
                                            <!--     <th class="box-title texto-branco padding12">{{ __('messages.Descrição') }}</th>-->                                                         
                                            <th class="box-title texto-branco padding12 largura15">{{ __('messages.Criação') }}</th>
                                            <th class="box-title texto-branco padding12 largura15">{{ __('messages.Data de entrega') }}</th>
                                            <th class="box-title texto-branco padding12">{{ __('messages.Progresso') }}</th>
                                            <th class="box-title texto-branco padding12">100%</th>
                                            <th class="box-title texto-branco padding12 com-border-right">{{ __('messages.Ações') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="fundo-branco com-shadow">
                                        @foreach($jobs_andamento as $job)
                                            <tr class="">
                                                <td class=" ">#{{ $job->id }}</td>
                                                <td class="desktop"><a href="{{ route('jobs.show', encrypt($job->id)) }}" class="texto-preto">{{ $job->nome  }}</a></td>
                                                <td class="desktop  largura15"> 
                                                    @if($job->delegado)
                                                        {{$job->delegado->name}}
                                                    @endif 
                                                </td>
                                                <td class=" largura15">
                                                    {{$job->created_at ? $job->created_at->format('d.m.Y') : ''}}
                                                </td>

                                                <td class=" largura15">
                                                    {{$job->data_prox_revisao ? $job->data_prox_revisao->format('d.m.Y') : ''}}
                                                </td>

                                                <td>
                                                    <div class="progress">
                                                        <div class="progress-bar progress-bar-animated progresso progress-bar-{{ $job->status == 8 ? 'danger' : 'success' }}" role="progressbar" aria-valuenow="{{ $job->concluido() }}" aria-valuemin="0" aria-valuemax="100" ></div>
                                                    </div>
                                                </td>

                                                <td>
                                                    @if($job->concluido()>=100)
                                                        <div class="div-check-concluir-job">
                                                            <input type="checkbox" class="check-concluir-job" 
                                                            name="job-concluir-check" 
                                                            id="job-concluir-{{ $job->id }}" 
                                                            value="{{ $job->id }}">
                                                        </div>
                                                    @endif        
                                                </td>

                                                <td>
                                                     <a href="{{ route('jobs.show', encrypt($job->id)) }}"aria-expanded="false" class="word-break texto-preto">
                                                     {{ __('messages.Detalhes') }}</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- Tab #card--}}
                        <div id="cardJobsExecutando" class="tab-pane fade  ">
                            <div class="btn-toolbar margemT10 tab-pane fade in" role="toolbar">
                                <div class="col-md-12 painel-jobs">
                                 @foreach($jobs_andamento as $job)
                                    <div class="col-md-3 col-lg-3 col-sm-12 card-job"  data-rota="{{ route('jobs.show', encrypt($job->id)) }}">
                                        <div class="box-group" id="accordion">
                                            <div class="panel box box-warning com-shadow">
                                                <div class="box-header ">
                                                    <div class="col-md-6" align="left">
                                                        <p class="semMargem">
                                                            <a href="{{ route('jobs.show', encrypt($job->id)) }}"aria-expanded="false" class="word-break">{{ $job->nome }}</a> 
                                                        </p>
                                                    </div>
                                                    <div class="col-md-6" align="right">
                                                        <p class="semMargem">
                                                            <a href="{{ route('jobs.show', encrypt($job->id)) }}"aria-expanded="false" class="word-break">
                                                            @if($job->delegado) 
                                                                {{$job->delegado->name}}
                                                            @endif 
                                                            </a> 
                                                        </p>
                                                    </div>
                                                </div>
                                                <div id="collapseOne" class="panel-collapse collapse in" aria-expanded="false">
                                                    <div class="box-body">
                                                        <div class="col-md-6" align="center">
                                                            @if($job->thumb)
                                                                <img src="{{asset('storage/' . $job->thumb)}}" alt="{{ __('messages.Referência do Job') }}" class="img-responsive card-job-thumb">
                                                            @else
                                                                <img src="{{asset('storage/imagens/jobs/job_default.png')}}" alt="{{ __('messages.Referência do Job') }}" class="img-responsive card-job-thumb">
                                                            @endif
                                                        </div>
                                                         
                                                        <div class="col-md-6 margemT10 margemB5 ">
                                                            <canvas id="job-and-chart-{{$job->id}}" class="graficos"  width="136" height="136" data-valor="{{ $job->concluido() }}" data-status="{{ $job->status }}"></canvas>
                                                        </div>

                                                        <div class="col-md-12 margemT10 margemB5 container-entrega-job">
                                                            <p class="semMargem">
                                                                <b>{{ __('messages.Deadline') }}: </b>
                                                                @php 
                                                                    $data = $job->data_prox_revisao ? $job->data_prox_revisao->format('d.m.Y') : __('messages.Não informado') 
                                                                @endphp
                                                                {{ $data }}
                                                            </p>
                                                        </div>
                                                        <!--<div class="col-md-12 margemT20 margemB5 container-entrega-job">
                                                            <p class="semMargem">
                                                                <b>{{ __('messages.Coordenador') }}: </b>
                                                                {{ $job->coordenador->name ??  __('messages.Não Informado')  }}
                                                            </p>
                                                        </div>   -->

                                                        @if($job->tasks)
                                                        <div class="col-md-12 margemT10 margemB5">
                                                            @forelse($job->tasks as $task)
                                                                <span 
                                                                    id="tasks-{{$task->id}}-job-{{$job->id}}" 
                                                                    data-nome="{{$task->nome}}"
                                                                    class="task-job-{{$job->id}}">
                                                                </span>
                                                            @empty  
                                                            @endforelse
                                                        </div> 
                                                        @endif                     
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                </div> 
                            </div>
                        </div>

                        {{-- Tab #lista--}}
                        <div id="listaJobsExecutando" class="tab-pane fade  in active">
                            <div class="btn-toolbar margemT10 tab-pane fade in" role="toolbar">
                                @unless(!$jobs_andamento)
                                <div id="img-accordion">
                                    <BR>
                                    <table id="lista-dashboard-job-and-lista" class="table larguraTotal com-shadow com-filtro">
                                        <thead>
                                            <th class="th-orange texto-branco padding12 border-left">
                                                <div class="panel-heading cor-personalizada" >
                                                    <div class="row">
                                                        <div class="col-md-4">{{ __('messages.Nome') }}</div>
                                                        <div class="col-md-2">{{ __('messages.Colaborador') }}</div>
                                                        <div class="col-md-2">{{ __('messages.Descrição') }}</div>
                                                        <div class="col-md-1">{{ __('messages.Progresso') }}</div>
                                                        <div class="col-md-2">
                                                            {{ __('messages.Data Entrega') }}
                                                        </div>
                                                        <div class="col-md-1">
                                                            <span class="pull-right titulo-tab-imagens" href="" class="" title="{{ __('messages.Criar Novo Job') }}" data-toggle="tooltip">{{ __('messages.Ação') }}</span>
                                                        </div>
                                                    </div>  
                                                </div>
                                            </th>
                                        </thead>
                                        <tbody class="fundo-branco">
                                        @foreach($jobs_andamento as $job)
                                        <tr class="">
                                            <td class="desktop margemL10 no-border">
                                                <div class="panel panel-default card-sem-borda card-imagem">
                                                    <div class="panel-heading cor-personalizada" id="panel-job-aberto-{{$job->id}}" style="background: #fff;">
                                                        <a class="larguraTotal alturaTotal texto-preto" data-toggle="collapse" data-target="#collapsjobaberto{{$job->id}}" aria-expanded="false" aria-controls="#collapseme{{$job->id}}" role="button">
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <p class="titulo-tab-imagens">{{ $job->nome }}
                                                                    </p>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <p class="titulo-tab-imagens">
                                                                    @if($job->delegado)
                                                                        {{$job->delegado->name}}
                                                                    @endif
                                                                    </p>
                                                                </div>

                                                                <div class="col-md-2">
                                                                    <p class="titulo-tab-imagens word-break">{{$job->descricao}}
                                                                    </p>
                                                                </div>
                                                                <div class="col-md-1">
                                                                    <div class="progress semMargem cor-personalizada" style="background: #fff;">
                                                                        <div class="progress-bar progress-bar-animated progress-bar-striped progress-bar-{{ $job->status == 8 ? 'danger' : 'success' }} progresso pull-right" role="progressbar" aria-valuenow="{{ $job->concluido() }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2">
                                                                   {{$job->data_prox_revisao ? $job->data_prox_revisao->format('d.m.Y') : __('messages.Não informado')}} 
                                                                </div>
                                                                <div>
                                                                    <div class="col-md-1">
                                                                        <a class="pull-right titulo-tab-imagens  link-detalhe" href="{{ route('jobs.show', encrypt($job->id)) }}" class="" title="Detalhes Job" data-toggle="tooltip">
                                                                        Detalhes
                                                                    </a>
                                                                </div>
                                                            </div>  
                                                        </a>
                                                    </div>

                                                     <div id="collapsjobaberto{{$job->id}}" class="collapse" aria-labelledby="panel-job-aberto-{{$job->id}}" data-parent="#img-accordion">
                                                        <div class="panel-body">
                                                            <div class="row margemB10">
                                                                <div class="col-md-10">
                                                                    <h4><b>{{ __('messages.Nome') }} :</b> {{ $job->nome }}</h4>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <a href="{{ route('jobs.show', encrypt($job->id)) }}" class="btn btn-info pull-right">
                                                                        {{ __('messages.Mais detalhes') }}
                                                                    </a>  
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <h4><b>{{ __('messages.Descrição') }}: </b> {{ $job->descricao ?? __('messages.Não Informado')}}</h4>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <h4><b>{{ __('messages.Coordenador') }}: </b> {{ $job->coordenador ? $job->coordenador->name : __('messages.Não Informado') }}</h4>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <h4><b>{{ __('messages.Status') }}: </b>
                                                                    {{ __('messages.' . $job->getStatus($job->status)) }}</h4>

                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @endif        

                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- oculta jobs concluidos -->
            @if(2==1)
                @if($jobs_concluidos && $total_jobs_con>0)
                    <div class="nav-tabs-custom nav-dashboard margemT50">
                        <h1 class="titulo-principal texto-esquerda titulo-lista-dash">
                            <a class="larguraTotal alturaTotal texto-preto" data-toggle="collapse" data-target="#collapsejobconcl" aria-expanded="false" aria-controls="#collapsejobconcl" role="button"><span class="accordion-marc"><i class="fa fa-angle-right"></i></span> Jobs Concluídos</a>
                        </h1>
                        <ul class="nav nav-tabs displayFlex flexEnd paddingR20" id="tabs-projeto" role="tablist">
                            <li class="active in" data-toggle="tooltip" title="{{ __('messages.Listas') }}">
                                <a data-toggle="tab" href="#listaJobsConcluido" aria-expanded="true" class="nav-link fundo-verde-escuro" id="imagens-tab" aria-selected="false" > <i class="fa fa-list" aria-hidden="true"></i></a>
                            </li>
                            <li data-toggle="tooltip" title="{{ __('messages.Cards') }}">
                                <a data-toggle="tab" href="#cardJobsConcluido" aria-expanded="true" class="nav-link fundo-verde-escuro" id="imagens-tab" aria-selected="false" ><i class="fa fa-credit-card" aria-hidden="true"></i></a>
                            </li>
                            <li data-toggle="tooltip" title="{{ __('messages.detalhes') }}">
                                <a data-toggle="tab" href="#detalhesJobsConcluido" aria-expanded="true" class="nav-link fundo-verde-escuro" id="imagens-tab"  aria-selected="false"><i class="fa fa-info" aria-hidden="true"></i></a>
                            </li>
                        </ul>
                        <div id="collapsejobconcl" class="tab-content collapse" aria-labelledby="panel-proand" data-parent="#img-accordion">
                            {{-- Tab #detalhes--}}
                            <div id="detalhesJobsConcluido" class="tab-pane fade">
                                <div class="btn-toolbar margemT10 tab-pane fade in" role="toolbar">
                                    <table id="lista-dashboard-job-concluido" class="table table-striped larguraTotal com-filtro-lista">
                                            <thead class="">
                                                <tr class="th-green">
                                                    <th colspan="" class="box-title texto-branco padding12 com-border-left largura10">#</th>
                                                    <th colspan="" class="box-title texto-branco padding12">{{ __('messages.Nome do Job') }}</th>
                                                    
                                                    <th colspan="" class="box-title texto-branco padding12 largura15">{{ __('messages.Colaborador') }}</th>
                                                    
                                                    <th colspan="" class="box-title texto-branco padding12 largura15">{{ __('messages.Criação') }}</th>
                                       
                                                    <th colspan="" class="box-title texto-branco padding12 largura15">{{ __('messages.Data de Entrega') }}</th>


                                                    <th colspan="" class="box-title texto-branco padding12">{{ __('messages.Progresso') }}</th>
                                                    <th colspan="" class="box-title texto-branco padding12 com-border-right">{{ __('messages.Ações') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody class="fundo-branco com-shadow">
                                                @foreach($jobs_concluidos as $job)
                                                    <tr class="">
                                                        <td class="desktop">
                                                            #{{ $job->id }}</td>
                                                        <td><a href="{{ route('jobs.show', encrypt($job->id)) }}" class="texto-preto">{{ $job->nome  }}</a></td>
                                                        <td> 
                                                            @if($job->delegado)
                                                                {{$job->delegado->name}}
                                                            @endif
                                                        </td>
                                                        <td class=" largura15">
                                                            {{$job->created_at ? $job->created_at->format('d.m.Y') : ''}}
                                                        </td>  
                                                        <td class=" largura15">
                                                            {{$job->data_prox_revisao ? $job->data_prox_revisao->format('d.m.Y') : ''}}
                                                        </td>  
                                                        <td>
                                                            <div class="progress">
                                                                <div class="progress-bar progress-bar-animated progresso" role="progressbar" aria-valuenow="{{ $job->concluido() }}" aria-valuemin="0" aria-valuemax="100" ></div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('jobs.show', encrypt($job->id)) }}"aria-expanded="false" class="word-break texto-preto">{{ __('messages.Detalhes') }}</a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                </div>
                            </div>

                            {{-- Tab #card--}}
                            <div id="cardJobsConcluido" class="tab-pane fade  ">
                                <div class="btn-toolbar margemT10 tab-pane fade in" role="toolbar">
                                    <div class="col-md-12 painel-jobs">
                                    @foreach($jobs_concluidos as $job)
                                        <div class="col-md-3 col-lg-3 col-sm-12 card-job"  data-rota="{{ route('jobs.show', encrypt($job->id)) }}">
                                            <div class="box-group" id="accordion">
                                                <div class="panel box box-success com-shadow">
                                                    <div class="box-header ">
                                                        <div class="col-md-6" align="left">
                                                            <p class="semMargem">
                                                                <a href="{{ route('jobs.show', encrypt($job->id)) }}"aria-expanded="false" class="word-break ">{{ $job->nome }}</a> 

                                                            </p>
                                                        </div>
                                                        <div class="col-md-6" align="right">
                                                            <p class="semMargem">
                                                                <a href="{{ route('jobs.show', encrypt($job->id)) }}"aria-expanded="false" class="word-break">
                                                                @if($job->delegado) 
                                                                    {{$job->delegado->name}}
                                                                @endif 
                                                                </a> 
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div id="collapseOne" class="panel-collapse collapse in" aria-expanded="false" >
                                                        <div class="box-body">
                                                            <div class="col-md-6" align="center">
                                                                @if($job->thumb)
                                                                    <img src="{{asset('storage/' . $job->thumb)}}" alt="{{ __('messages.Referência do Job') }}" class="img-responsive card-job-thumb">
                                                                @else
                                                                    <img src="{{asset('storage/imagens/jobs/job_default.png')}}" alt="{{ __('messages.Referência do Job') }}" class="img-responsive card-job-thumb">
                                                                @endif
                                                            </div>

                                                            <div class="col-md-6 margemT10 margemB5 ">
                                                                <canvas id="job-and-chart-{{$job->id}}" class="graficos"  data-status="{{ $job->status }}" width="136" height="136" data-valor="{{ $job->concluido() }}"></canvas>
                                                            </div>

                                                            <div class="col-md-8 margemT10 margemB5 container-entrega-job">
                                                                <p class="semMargem"><b>{{ __('messages.Deadline') }}: </b>
                                                                @php 
                                                                    $data = $job->data_prox_revisao ? $job->data_prox_revisao->format('d.m.Y') : __('messages.Não informado') 
                                                                @endphp
                                                                {{ $data }}
                                                                </p>
                                                            </div>    
                                                            <!-- <div class="col-md-12 margemT10 margemB5">
                                                                @forelse($job->tasks as $task) -->
                                                                    <!-- <span  id="tasks-{{$task->id}}-job-{{$job->id}}"  data-nome="{{$task->nome}}" class="task-job-{{$job->id}}">
                                                                    </span> -->
                                                            <!--  @empty  
                                                                @endforelse
                                                            </div> -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                                                


                                    </div> 
                                </div>
                            </div>

                            {{-- Tab #lista--}}
                            <div id="listaJobsConcluido" class="tab-pane fade  in active">
                                <div class="btn-toolbar margemT10 tab-pane fade in" role="toolbar">
                                    @unless(!$jobs_concluidos)
                                    <div id="img-accordion">
                                        <BR>
                                        <table id="lista-dashboard-job-concluido-lista" class="table table-striped larguraTotal com-shadow com-filtro">
                                            <thead>
                                                <th class="th-green texto-branco padding12 border-left">
                                                    <div class="panel-heading cor-personalizada" >
                                                        <div class="row">
                                                            <div class="col-md-4">{{ __('messages.Nome') }}</div>
                                                            <div class="col-md-2">{{ __('messages.Colaborador') }}</div>
                                                            <div class="col-md-2">Descrição</div>
                                                            <div class="col-md-2">{{ __('messages.Progresso') }}</div>
                                                            <div class="col-md-2">
                                                                <span class="pull-right titulo-tab-imagens" href="" class="" title="{{ __('messages.Criar Novo Job') }}" data-toggle="tooltip">{{ __('messages.Ação') }}</span>
                                                            </div>
                                                        </div>  
                                                    </div>
                                                </th>
                                            </thead>
                                            <tbody class="fundo-branco">
                                            @foreach($jobs_concluidos as $job)
                                            <tr class="">
                                                <td class="desktop margemL10 no-border">
                                                    <div class="panel panel-default card-sem-borda card-imagem">
                                                        <div class="panel-heading cor-personalizada" id="panel-job-aberto-{{$job->id}}" style="background: #fff;">
                                                            <a class="larguraTotal alturaTotal texto-preto" data-toggle="collapse" data-target="#collapsjobaberto{{$job->id}}" aria-expanded="false" aria-controls="#collapseme{{$job->id}}" role="button">
                                                                <div class="row">
                                                                    <div class="col-md-4">
                                                                        <p class="titulo-tab-imagens word-break">{{ $job->nome }}
                                                                        </p>
                                                                    </div>
                                                                    <div class="col-md-2">
                                                                        <p class="titulo-tab-imagens word-break">
                                                                        @if($job->delegado)
                                                                            {{$job->delegado->name}}
                                                                        @endif
                                                                        </p>
                                                                    </div>

                                                                    <div class="col-md-2">
                                                                        <p class="titulo-tab-imagens">{{$job->descricao}}
                                                                        </p>
                                                                    </div>
                                                                    <div class="col-md-2">
                                                                        <div class="progress semMargem cor-personalizada" style="background: #fff;">
                                                                            <div class="progress-bar progress-bar-animated progress-bar-striped progress-bar-success progresso pull-right" role="progressbar" aria-valuenow="{{ $job->concluido() }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                                        </div>
                                                                    </div>
                                                                    <div>
                                                                        <div class="col-md-2">
                                                                            <a class="pull-right titulo-tab-imagens link-detalhe" href="{{ route('jobs.show', encrypt($job->id)) }}" class="" title="{{ __('messages.Detalhes do Job') }}" data-toggle="tooltip">
                                                                            Detalhes
                                                                        </a>
                                                                    </div>
                                                                </div>  
                                                            </a>
                                                        </div>

                                                        <div id="collapsjobaberto{{$job->id}}" class="collapse" aria-labelledby="panel-job-aberto-{{$job->id}}" data-parent="#img-accordion">
                                                            <div class="panel-body">
                                                                <div class="row margemB10">
                                                                    <div class="col-md-10">
                                                                        <h4><b>{{ __('messages.Nome') }} :</b> {{ $job->nome }}</h4>
                                                                    </div>
                                                                    <div class="col-md-2">
                                                                        <a href="{{ route('jobs.show', encrypt($job->id)) }}" class="btn btn-info pull-right">
                                                                            Mais detalhes
                                                                        </a>  
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <h4><b>{{ __('messages.Descrição') }}: </b> {{ $job->descricao ?? __('messages.Não Informado')}}</h4>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <h4><b>{{ __('messages.Coordenador') }}: </b> {{ $job->coordenador ? $job->coordenador->name : __('messages.Não Informado') }}</h4>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <h4><b>{{ __('messages.Status') }}: </b>
                                                                        {{ __('messages.' . $job->getStatus($job->status)) }}</h4>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    @endif        

                                </div>
                            </div>
                        </div>
                    </div>            
                @endif   
            @endif       

            @if($total_jobs_rec>0)
                <div class="nav-tabs-custom nav-dashboard margemT50">
                    <h1 class="titulo-principal texto-esquerda titulo-lista-dash">
                        <a class="larguraTotal alturaTotal texto-preto" data-toggle="collapse" data-target="#collapsejobrecu" aria-expanded="false" aria-controls="#collapsejobrecu" role="button"><span class="accordion-marc"><i class="fa fa-angle-right"></i></span> {{__('messages.Jobs Recusados')}}</a>
                    </h1>

                    <ul class="nav nav-tabs displayFlex flexEnd paddingR20" id="tabs-projeto" role="tablist">
                        <li class="active in" data-toggle="tooltip" title="{{ __('messages.Listas') }}">
                            <a data-toggle="tab" href="#listaJobsRecusado" aria-expanded="true" class="nav-link fundo-vermelho" id="imagens-tab" aria-selected="false" > <i class="fa fa-list" aria-hidden="true"></i></a>
                        </li>
                        <li data-toggle="tooltip" title="{{ __('messages.Cards') }}">
                            <a data-toggle="tab" href="#cardJobsRecusado" aria-expanded="true" class="nav-link fundo-vermelho" id="imagens-tab" aria-selected="false" ><i class="fa fa-credit-card" aria-hidden="true"></i></a>
                        </li>
                        <li data-toggle="tooltip" title="{{ __('messages.Detalhes') }}">
                            <a data-toggle="tab" href="#detalhesJobsRecusado" aria-expanded="true" class="nav-link fundo-vermelho" id="imagens-tab"  aria-selected="false"><i class="fa fa-info" aria-hidden="true"></i></a>
                        </li>
                    </ul>
                    
                    <div id="collapsejobrecu" class="tab-content collapse" aria-labelledby="panel-proand" data-parent="#img-accordion">
                        {{-- Tab #detalhes--}}
                        <div id="detalhesJobsRecusado" class="tab-pane fade">
                            <div class="btn-toolbar margemT10 tab-pane fade in" role="toolbar">
                                 <table id="lista-dashboard-job-recusado" class="table table-striped larguraTotal com-filtro-lista">
                                        <thead class="">
                                            <tr class="th-strong-red">
                                                <th colspan="" class="box-title texto-branco padding12 com-border-left largura10">#</th>
                                                <th colspan="" class="box-title texto-branco padding12">{{ __('messages.Nome do Job') }}</th>
                                                <th colspan="" class="box-title texto-branco padding12">{{ __('messages.Colaborador') }}</th>
                                                <th colspan="" class="box-title texto-branco padding12">{{ __('messages.Criação') }}</th>
                                                <th colspan="" class="box-title texto-branco padding12">{{ __('messages.Data de entrega') }}</th>
                                                <th colspan="" class="box-title texto-branco padding12">{{ __('messages.Progresso') }}</th>
                                                <th colspan="" class="box-title texto-branco padding12 com-border-right">{{ __('messages.Ações') }}</th>
                                            </tr>
                                        </thead>
                                        
                                        <tbody class="fundo-branco com-shadow">
                                            @foreach($jobs_recusados as $job)
                                                <tr class="">
                                                    <td class="desktop">#{{ $job->id }}</td>
                                                    <td><a href="{{ route('jobs.show', encrypt($job->id)) }}" class="texto-preto">{{ $job->nome  }}</a></td>
                                                    <td>
                                                    @if($job->delegado)
                                                        {{$job->delegado->name}}
                                                    @endif
                                                    </td>
                                                    <td class=" largura15">
                                                        {{$job->created_at ? $job->created_at->format('d.m.Y') : ''}}
                                                    </td>
                                                     <td class=" largura15">
                                                        {{$job->data_prox_revisao ? $job->data_prox_revisao->format('d.m.Y') : ''}}
                                                    </td>  
                                                    <td>
                                                        <div class="progress">
                                                            <div class="progress-bar progress-bar-animated progresso" role="progressbar" aria-valuenow="{{ $job->concluido() }}" aria-valuemin="0" aria-valuemax="100" ></div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                         <a href="{{ route('jobs.show', encrypt($job->id)) }}"aria-expanded="false" class="word-break texto-preto">{{ __('messages.Detalhes') }}</a>

                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                            </div>
                        </div>

                        {{-- Tab #card--}}
                        <div id="cardJobsRecusado" class="tab-pane fade  ">
                            <div class="btn-toolbar margemT10 tab-pane fade in" role="toolbar">
                                <div class="col-md-12 painel-jobs">
                                @foreach($jobs_recusados as $job)
                                    <div class="col-md-3 col-lg-3 col-sm-12 card-job"  data-rota="{{ route('jobs.show',encrypt($job->id))}}">
                                        <div class="box-group" id="accordion">
                                            <div class="panel box box-danger com-shadow">
                                                <div class="box-header ">
                                                    <div class="col-md-6" align="left">
                                                        <p class="semMargem">
                                                            <a href="{{ route('jobs.show', encrypt($job->id)) }}"aria-expanded="false" class="word-break">{{ $job->nome }}</a> 

                                                        </p>
                                                    </div>
                                                    <div class="col-md-6" align="right">
                                                        <p class="semMargem">
                                                            <a href="{{ route('jobs.show', encrypt($job->id)) }}"aria-expanded="false" class="word-break">
                                                            @if($job->delegado) 
                                                                {{$job->delegado->name}}
                                                            @endif 
                                                            </a> 
                                                        </p>
                                                    </div>
                                                </div>
                                                <div id="collapseOne" class="panel-collapse collapse in" aria-expanded="false" >
                                                    <div class="box-body">
                                                        <div class="col-md-6" align="center">
                                                        @if($job->thumb)
                                                            <img src="{{asset('storage/' . $job->thumb)}}" alt="{{ __('messages.Referência do Job') }}" class="img-responsive card-job-thumb">
                                                        @else
                                                            <img src="{{asset('storage/imagens/jobs/job_default.png')}}" alt="{{ __('messages.Referência do Job') }}" class="img-responsive card-job-thumb">
                                                        @endif
                                                        </div>

                                                        <div class="col-md-6 margemT10 margemB5 ">
                                                            <canvas id="job-and-chart-{{$job->id}}" class="graficos"  data-status="{{ $job->status }}" width="136" height="136" data-valor="{{ $job->concluido() }}"></canvas>
                                                        </div>

                    
                                                        <div class="col-md-8 margemT10 margemB5 container-entrega-job">
                                                            <p class="semMargem"><b>{{ __('messages.Deadline') }}: </b>
                                                                @php 
                                                                    $data = $job->data_prox_revisao ?  $job->data_prox_revisao->format('d.m.Y') : __('messages.Não informado') 
                                                                @endphp
                                                            {{ $data }}
                                                            </p>
                                                        </div>    
                                                       <!--  <div class="col-md-12 margemT10 margemB5">
                                                            @forelse($job->tasks as $task) -->
                                                                <!-- <span id="tasks-{{$task->id}}-job-{{$job->id}}" data-nome="{{$task->nome}}" class="task-job-{{$job->id}}">
                                                                </span> -->
                                                            <!-- @empty  
                                                            @endforelse
                                                            </div> -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div> 
                            </div>
                        </div>

                        {{-- Tab #lista--}}
                        <div id="listaJobsRecusado" class="tab-pane fade  in active">
                            <div class="btn-toolbar margemT10 tab-pane fade in" role="toolbar">
                                @unless(!$jobs_recusados)
                                <div id="img-accordion">
                                    <BR>
                                    <table id="lista-dashboard-job-recusado-lista" class="table table-striped larguraTotal com-shadow com-filtro">
                                        <thead>
                                            <th class="th-strong-red texto-branco padding12 border-left">
                                                <div class="panel-heading cor-personalizada" >
                                                    <div class="row">
                                                        <div class="col-md-4 ">{{ __('messages.Nome') }}</div>
                                                        <div class="col-md-2">
                                                            {{ __('messages.Colaborador') }}
                                                        </div>
                                                        <div class="col-md-2">
                                                            {{ __('messages.Descrição') }}
                                                        </div>
                                                        <div class="col-md-2">{{ __('messages.Progresso') }}</div>
                                                        <div class="col-md-2">
                                                            <span class="pull-right titulo-tab-imagens" href="" class="" title="{{ __('messages.Criar Novo Job') }}" data-toggle="tooltip">{{ __('messages.Ação') }}</span>
                                                        </div>
                                                    </div>  
                                                </div>
                                            </th>
                                        </thead>
                                        <tbody class="fundo-branco">
                                        @foreach($jobs_recusados as $job)
                                        <tr class="">
                                            <td class="desktop margemL10 no-border">
                                                <div class="panel panel-default card-sem-borda card-imagem">
                                                    <div class="panel-heading cor-personalizada" id="panel-job-aberto-{{$job->id}}" style="background: #fff;">
                                                        <a class="larguraTotal alturaTotal texto-preto" data-toggle="collapse" data-target="#collapsjobaberto{{$job->id}}" aria-expanded="false" aria-controls="#collapseme{{$job->id}}" role="button">
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <p class="titulo-tab-imagens">{{ $job->nome }}
                                                                    </p>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <p class="titulo-tab-imagens">
                                                                    @if($job->delegado)
                                                                        {{$job->delegado->name}}
                                                                    @endif
                                                                    </p>
                                                                </div>                                                           
                                                                <div class="col-md-2">
                                                                    <p class="titulo-tab-imagens word-break">{{$job->descricao}}
                                                                    </p>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <div class="progress semMargem cor-personalizada" style="background: #fff;">
                                                                        <div class="progress-bar progress-bar-animated progress-bar-striped progress-bar-success progresso pull-right" role="progressbar" aria-valuenow="{{ $job->concluido() }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                                    </div>
                                                                </div>
                                                                <div>
                                                                    <div class="col-md-2">
                                                                        <a class="pull-right titulo-tab-imagens link-detalhe" href="{{ route('jobs.show', encrypt($job->id)) }}" class="" title="{{ __('messages.Detalhes do Job') }}" data-toggle="tooltip">
                                                                        {{ __('messages.Detalhes') }}
                                                                    </a>
                                                                </div>
                                                            </div>  
                                                        </a>
                                                    </div>

                                                     <div id="collapsjobaberto{{$job->id}}" class="collapse" aria-labelledby="panel-job-aberto-{{$job->id}}" data-parent="#img-accordion">
                                                        <div class="panel-body">
                                                            <div class="row margemB10">
                                                                <div class="col-md-10">
                                                                    <h4><b>{{ __('messages.Nome') }} :</b> {{ $job->nome }}</h4>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <a href="{{ route('jobs.show', encrypt($job->id)) }}" class="btn btn-info pull-right">
                                                                        {{ __('messages.Mais detalhes') }}
                                                                    </a>  
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <h4><b>{{ __('messages.Descrição') }}: </b> {{ $job->descricao ?? __('messages.Não Informado')}}</h4>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <h4><b>{{ __('messages.Coordenador') }}: </b> {{ $job->coordenador ? $job->coordenador->name : __('messages.Não Informado') }}</h4>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <h4><b>{{ __('messages.Status') }}: </b>
                                                                    {{ __('messages.' . $job->getStatus($job->status)) }}</h4>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @endif        

                            </div>
                        </div>


                    </div>
                </div>                   
            @endif       


      
        @endif 

    </div>
    {{-- Dashboard --}}

  
@stop

@push('js')
    

    <script type="text/javascript" src="{{ asset('js/pega_job.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/numeros.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/filtros_home.js') }}"></script>

    <script>

        $(function () {
 
            // 26/10/2020 - retirar jquery da classe .com-filtro e colocado no fullfreela.js
            // $.fn.dataTable.moment('DD.MM.YYYY');
                // $.fn.dataTable.moment('DD.MM.YYYY');
                // //$.fn.dataTable.moment( 'dd/mm/yyyy');
                // $('.com-filtro').DataTable({
                // "paging": true,
                // "lengthChange": true,
                // "searching": true,
                // "ordering": true,
                // "info": true,
                // "autoWidth": true,
                // 'sProcessing': 'Processando...',
                // });        

                // $('[type="search"]').addClass("form-control");

            // $(".dataTables_length select").addClass("custom-select custom-select-sm form-control form-control-sm");
            


            $('.botao-concluir-job').click(function(){    
                $("#form-concluir-job").submit();
            });

            

            $('input[name=job-concluir-check]').change(function() {  

                //$('.check-concluir-job').on('ifChanged', function(e) {
                //e.preventDefault();

                this.checked ? criaCampo(this) : deletaCampo(this);

                if($("#form-concluir-job input.job-selecionado-filho").length>=1)
                {
                    
                    $('.botao-concluir-job').css('visibility', 'visible');
                }
                else
                {
                    $('.botao-concluir-job').css('visibility', 'hidden');
                }
            });
            function criaCampo(ele) {

                $('#form-concluir-job').append('<input type="hidden" class="job-selecionado-filho job-selecionado-'+ ele.value +'" id="job-selecionado-'+ ele.value +'" name="job_selecionado[]" value="'+ ele.value + '">');
            }

            function deletaCampo(ele) {
                $('.job-selecionado-'+ ele.value ).remove();
            }

            // TODO: Verificar método
            $(".botao-job-concluir").click(function() {
                // alert("teste botao");
            });

            var countJobs = 0;
            // alert(countJobs);
            // ao clicar num check job-concluir marca ou desmarca e avlaia se habilita o botão de concluir jobs 
            // $('input[name=concluir_job]').change(function(e) {
            $('.job-concluir-lista').on('ifChanged', function(e) {
                var jobId = this.value;
                if(this.checked) { 
                    countJobs++;
                    newInput = jQuery('<input type="hidden" name="job_selecionado[]" id="job-id-concluir-'+jobId+'" value="'+jobId+'">');
                    jQuery('#form-concluir-jobs').append(newInput);

                }else {
                    countJobs--;
                    jQuery('#job-id-concluir-'+jobId).remove();

                }

                if(countJobs>0){
                    $("#button-jobs-concluir").attr("disabled", false);
                }
                else{
                    $("#button-jobs-concluir").attr("disabled", true);
                }
            });

        });

    


    </script>
@endpush