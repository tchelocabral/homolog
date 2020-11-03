@extends('adminlte::page')

@section('title', $projeto->nome ? $projeto->nome : __('messages.Cliente'))

@section('content_header')
    {{ Breadcrumbs::render('detalhe-projeto', $projeto) }}
    <h2 class="">{{ __('messages.Projeto') }} {{ $projeto->nome }}</h2>
@stop

@section('content')
    <div class="row margemT40 centralizado">
        @empty($projeto)
            <h1>{{ __('messages.Projeto não Encontrado')}}</h1>
        @else
            <div class="nav-tabs-custom altura-minima">
                <ul class="nav nav-tabs azul" id="tabs-projeto" role="tablist">
                    <li class="active in">
                        <a data-toggle="tab" href="#imagens" aria-expanded="true" class="nav-link" id="imagens-tab" aria-selected="false">{{ __('messages.Imagens')}}</a>
                    </li>
                    <li>
                        <a data-toggle="tab" href="#jobs" aria-expanded="false" class="nav-link" id="jobs-tab" aria-selected="false">{{ __('messages.Jobs')}}</a>
                    </li>
                    <li>
                        <a data-toggle="tab" href="#arquivos" aria-expanded="false" class="nav-link" id="Arquivos-tab" aria-selected="false">{{ __('messages.Arquivos')}}</a>
                    </li>
                    <li>
                        <a data-toggle="tab" href="#faturamento" aria-expanded="false" class="nav-link" id="faturamento-tab" aria-selected="false">{{ __('messages.Faturamento')}}</a>
                    </li>
                    <li>
                        <a data-toggle="tab" href="#detalhes" aria-expanded="false" class="nav-link" id="infodoprojet-tab" aria-controls="{{ __('messages.Informações do Projeto')}}" aria-selected="false">
                            {{ __('messages.Informações do Projeto')}}</a>
                    </li>
                </ul>
                <div class="tab-content">
                    
                    {{-- Tab #detalhes--}}
                    <div id="detalhes" class="tab-pane fade">
                        <div class="btn-toolbar margemT10 tab-pane fade in" role="toolbar">
                            @can('atualiza-projeto')
                            <a href="{{ route('projetos.edit', encrypt($projeto->id)) }}" class="btn btn-primary" data-toggle="tooltip" title="{{ __('messages.Editar Informações')}}">
                                <i class="fa fa-pencil" aria-hidden="true"></i>
                            </a>
                            <a href="{{ route('projeto.add.arquivo', encrypt($projeto->id)) }}" class="btn btn-info " title="{{ __('messages.Adicionar Arquivos ao Projeto')}}" data-toggle="tooltip" {{in_array($projeto->status, [2,3]) ? 'style=display:none;' : ""}}>
                                <i class="fa fa-archive" aria-hidden="true"></i>
                            </a>
                            <a href="{{ route('imagens.add', encrypt($projeto->id)) }}" class="btn btn-warning " title="{{ __('messages.Adicionar Imagens ao Projeto')}}" data-toggle="tooltip" {{in_array($projeto->status, [2,3]) ? 'style=display:none;' : ""}}>
                                <i class="fa fa-image" aria-hidden="true"></i>
                            </a>
                            <a href="{{ route('jobs.create', encrypt($projeto->id)) }}" class="btn btn-success " title="{{ __('messages.Criar Novo Job')}}" data-toggle="tooltip" {{in_array($projeto->status, [2,3]) ? 'style=display:none;' : ""}}>
                                <i class="fa fa-plus" aria-hidden="true"></i>
                            </a>
                            <a href="{{ route('projeto.vincular.img.arquivo', encrypt($projeto->id)) }}" class="btn cyan " title="{{ __('messages.Vincular arquivos e imagens')}}" data-toggle="tooltip" {{in_array($projeto->status, [2,3]) ? 'style=display:none;' : ""}}>
                                <i class="fa fa-paperclip" aria-hidden="true"></i>
                            </a>
                            @endcan 

                            @can('gerencia-financeiro')
                            <a href="{{ route('clientes.show', encrypt($projeto->cliente_id)) }}" class="btn btn-success " title="{{ __('messages.Editar Faturamento')}}" data-toggle="tooltip">
                                <i class="fa fa-money" aria-hidden="true"></i>
                            </a>
                            @endcan 

                            @can('deleta-projeto')
                            <form action="{{ route('projetos.destroy', encrypt($projeto->id)) }}" class="form-delete" id="form-deletar-projeto-{{ $projeto->id }}" name="form-deletar-projeto-{{ $projeto->id }}" method="POST" enctype="multipart/form-data">
                                @method('DELETE')
                                @csrf
                                <a href="#" class="btn btn-danger deletar-item margemL5" title="{{ __('messages.Excluir Projeto')}}" data-toggle="tooltip" type="submit">
                                    <i class="fa fa-close" aria-hidden="true"></i>
                                </a>
                            </form>
                            @endcan
                            
                            @can('atualiza-projeto')

                                @if( in_array($projeto->status, [0,1]))
                                    
                                    @if($projeto->concluido() >= 100)

                                        <form action="{{ route('projeto.concluir', encrypt($projeto->id)) }}" class="" id="form-concluir-projeto{{$projeto->id}}" name="form-concluir-projeto-{{ $projeto->id }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <button class="btn btn-success pull-right margemR10" title="{{ __('messages.Concluir Projeto')}}" data-toggle="tooltip" type="submit">
                                            {{ __('messages.Definir Projeto com Concluído')}}{{-- <i class="fa fa-close" aria-hidden="true"></i> --}}
                                            </button>
                                        </form>
                                    @endif

                                @else
                                    
                                    <form action="{{ route('projeto.reabrir', encrypt($projeto->id)) }}" class="" id="form-reabrir-img-{{ $projeto->id }}" name="form-reabrir-img-{{ $projeto->id }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <button class="btn btn-warning margemL5 pull-right" title="{{ __('messages.')}}" data-toggle="tooltip" type="submit">
                                        {{ __('messages.Reabrir Projeto')}}
                                            {{--<i class="fa fa-check" aria-hidden="true"></i>--}}
                                        </button>

                                    </form>
                                @endif
                            @endcan
                        </div>
                        <hr>
                        
                        <div class="row">
                            <div class="col-md-8">
                                @include('projeto.inputs', ['projeto' => $projeto, 'coordenadores' => null , 'detalhe' => true])
                            </div>

                            <div class="col-md-4">
                                <div class="container-fluid grafico-wrapper">
                                    <div class="col-md-6" id="grafico-size">
                                        {{-- {{dd($projeto)}} --}}
                                        {{-- <h4>{{ $projeto->situacao() ?? '' }}</h4> --}}
                                        <canvas id="imagem-chart" class="graficos" width="136" height="136" data-valor="{{ $projeto->concluido() }}"></canvas>
                                    </div>
                                </div>

                                
                            </div>
                        </div>

                    </div>

                    {{-- Tab #imagens--}}
                    <div id="imagens" class="tab-pane fade in active">
                        <!-- @can('cria-imagem','atualiza-imagem') -->
                        <div class="btn-toolbar margemT10" role="toolbar">
                            @can('cria-imagem')
                                <a href="{{ route('imagens.add', encrypt($projeto->id)) }}" class="btn btn-warning " title="{{ __('messages.Adicionar Imagens ao Projeto')}}" data-toggle="tooltip">
                                    <i class="fa fa-image" aria-hidden="true"></i>
                                    {{-- Adicionar Imagem --}}
                                </a>
                            @endcan
                            @can('atualiza-imagem')
                                <a href="{{ route('projeto.vincular.img.arquivo', encrypt($projeto->id)) }}" class="btn cyan " title="{{ __('messages.Vincular arquivos e imagens')}}" data-toggle="tooltip">
                                    <i class="fa fa-paperclip" aria-hidden="true"></i>
                                    {{-- Vincular arquivos e imagens --}}
                                </a>
                            @endcan
                              <h3 class="" style="text-align:center;">{{ __('messages.Lista de Imagens do Projeto')}}
                                             </h3>
                        </div>
                        <hr>
                        <!-- @endcan -->
                        @unless(!$projeto->imagens)
                        <div id="img-accordion">
                            <BR>
                            <table id="lista-dashboard"  class="table table-striped larguraTotal com-shadow com-filtro">
                                <thead>
                                    <th class="th-ocean texto-branco padding12 border-left">
                                        <div class="panel-heading cor-personalizada" >
                                            <div class="row">
                                                <div class="col-md-2">{{ __('messages.Nome')}}</div>
                                                <div class="col-md-2">{{ __('messages.Tipo')}}</div>
                                                <div class="col-md-1">{{ __('messages.Status')}}</div>
                                                <div class="col-md-2">{{ __('messages.Finalizador')}}</div>
                                                <div class="col-md-2">{{ __('messages.Progresso')}}</div>
                                                <div class="col-md-1">
                                                    <span class="pull-right titulo-tab-imagens" href="" class="" title="{{ __('messages.Criar Novo Job')}}" data-toggle="tooltip">{{ __('messages.Ação')}}</span>
                                                </div>
                                            </div>  
                                        </div>
                                    </th>
                                </thead>
                                <tbody class="fundo-branco">
                                    @foreach($projeto->imagens as $img)
                                    <tr class="">
                                        <td class="desktop margemL10">
                                            <div class="panel panel-default card-sem-borda card-imagem">
                                                <div class="panel-heading cor-personalizada" id="panel-img-{{$img->id}}" style="background: #fff;">
                                                    <a class="larguraTotal alturaTotal texto-preto" data-toggle="collapse" data-target="#collapseme{{$img->id}}" aria-expanded="false" aria-controls="#collapseme{{$img->id}}" role="button">
                                                        <div class="row">
                                                            <div class="col-md-2">
                                                                <p class="titulo-tab-imagens">{{ $img->nome ?? '' }} {{ $img->descricao ? ' - ' . $img->descricao : '' }}
                                                                </p>
                                                            </div>
                                                            {{-- <div class="col-md-2">
                                                                <p class="titulo-tab-imagens">{{ $img->tipo ? $img->tipo->nome : ''}}</p>
                                                            </div> --}}
                                                            <div class="col-md-2">
                                                                <p class="titulo-tab-imagens">{{ $img->tipo->grupo->nome ??   '' }}
                                                                </p>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <p class="titulo-tab-imagens">{{$img->status_revisao ?? ''}}
                                                                </p>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <p class="titulo-tab-imagens">{{$img->finalizador ? $img->finalizador->name : ''}}
                                                                </p>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="progress semMargem cor-personalizada" style="background: #fff;">
                                                                @php 
                                                                    $class_status = 'success';
                                                                    $porcentagem_status = $img->concluido();
                                                                    foreach($img->jobs as $key => $job) {
                                                                        if($job->status==8) {
                                                                            $class_status = 'danger';
                                                                            $porcentagem_status =100;
                                                                        }
                                                                    }
                                                                @endphp
                                                                    <div class="progress-bar progress-bar-animated progress-bar-striped progress-bar-{{ $class_status }} progresso pull-right" role="progressbar" aria-valuenow="{{ $porcentagem_status }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-1">
                                                                
                                                                <a class="pull-right titulo-tab-imagens" href="{{ route('imagem.add.job', encrypt($img->id)) }}" class="" title="{{  __('messages.Criar Novo Job') }}" data-toggle="tooltip">
                                                                    {{ __('messages.Add Job')}}
                                                                </a>
                                                            </div>
                                                        </div>  
                                                    </a>
                                                </div>
                                                <!-- // conteudo collapse -->
                                                <div id="collapseme{{$img->id}}" class="collapse" aria-labelledby="panel-img-{{$img->id}}" data-parent="#img-accordion">
                                                    <div class="panel-body">
                                                        <div class="row margemB10">
                                                            <div class="col-md-10">
                                                                <h4><b>{{ __('messages.Nome')}}:</b> {{ $img->tipo->nome ?? ''}}</h4>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <a href="{{ route('imagens.show', encrypt($img->id)) }}" class="btn btn-info pull-right">
                                                                    {{ __('messages.Mais detalhes')}}
                                                                </a>  
                                                            </div>
                                                            <div class="col-md-12">
                                                                <h4><b>{{ __('messages.Descrição')}}: </b> {{ $img->descricao ??  __('messages.Não Informado')}}</h4>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <h4><b>{{ __('messages.Finalizador')}}: </b> {{ $img->finalizador ? $img->finalizador->name :  __('messages.Não Informado') }}</h4>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <h4><b>{{ __('messages.Status')}}: </b>{{$img->status_revisao ??  __('messages.Sem Revisão adicionada')}}</h4>
                                                            </div>
                                                        </div>
                                                        
                                                        @if(!count($img->jobs) > 0)
                                                            <h3>{{ __('messages.Sem jobs para esta imagem')}} | <a href="{{ route('imagem.add.job', encrypt($img->id)) }}">{{ __('messages.Criar Job')}}</a></h3>
                                                        @else
                                                            <div class="table-responsive">
                                                                <table class="table table-striped nome-job" >
                                                                    <thead class="fundo-escuro">
                                                                        <th class="texto-branco">{{ __('messages.Nome do Job')}}</th>
                                                                        <th class="texto-branco">{{ __('messages.Coordenador')}}</th>
                                                                        <th class="texto-branco">{{ __('messages.Colaborador')}}</th>
                                                                        <th class="texto-branco">{{ __('messages.Progresso')}}</th>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach($img->jobs as $jb)
                                                                            <tr>
                                                                                <td><a href="{{ route('jobs.show', encrypt($jb->id)) }}">{{ $jb->nome }}</a></td>
                                                                                <td>{{ $jb->coordenador ? $jb->coordenador->name :  __('messages.Não Informado')}}</td>
                                                                                <td>{{ $jb->delegado    ? $jb->delegado->name    :  __('messages.Não Informado')}}</td>
                                                                                <td>
                                                                                    <div class="progress">
                                                                                        <div class="progress-bar progress-bar-animated progress-bar-striped progress-bar-success progresso pull-right" role="progressbar" aria-valuenow="{{ $jb->concluido() }}" aria-valuemin="0" aria-valuemax="100" ></div>
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
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                        @endif          
                    </div>

                    {{-- Tab #arquivos--}}
                    <div id="arquivos" class="tab-pane fade">
                        @can('atualiza-projeto')
                        <div class="btn-toolbar margemT10" role="toolbar">
                            <a href="{{ route('projeto.add.arquivo', encrypt($projeto->id)) }}" class="btn btn-info" title=" {{ __('messages.Adicionar Arquivos ao Projeto') }}" data-toggle="tooltip">
                                <i class="fa fa-archive" aria-hidden="true"></i>
                            </a>
                            <a href="{{ route('projeto.vincular.img.arquivo', encrypt($projeto->id)) }}" class="btn cyan" title=" {{ __('messages.Vincular arquivos e imagens') }}" data-toggle="tooltip">
                                <i class="fa fa-paperclip" aria-hidden="true"></i>
                            </a>
                        </div>
                        @endcan
                        <hr>
                        @unless(!$projeto->arquivos)
                            <div class="table-responsive">
                                <table class="table table-striped no-margin margemB40">
                                    <thead>
                                        <tr>
                                            <th>{{ __('messages.Tipo')}}</th>
                                            <th>{{ __('messages.Nome')}}</th>
                                            <th>{{ __('messages.Thumb')}}</th>
                                            <th class="texto-centralizado">{{ __('messages.Imagens Vinculadas')}}</th>
                                            <th class="texto-centralizado">{{ __('messages.Opções')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($projeto->arquivos->sortBy('tipo_arquivo.nome') as $arq)
                                        <tr>
                                            <td>{{ $arq->tipo_arquivo->nome }}</td>
                                            
                                            <td>{{ $arq->nome }}</td>
                                            
                                            @if(pathinfo($arq->caminho, PATHINFO_EXTENSION ) == 'jpg' || pathinfo($arq->caminho, PATHINFO_EXTENSION) == 'png')
                                                <td>
                                                    <img src="{{ URL::to('') . '/storage/' . $arq->caminho }}" width="28" height="28" alt="">
                                                </td>
                                            @else
                                                @php
                                                    $exts   = ['3ds', 'cad', 'doc', 'dxf', 'max', 'pdf', 'psd', 'txt', 'docx', 'xls', 'zip', 'dwg'];
                                                    $ext    = pathinfo($arq->caminho, PATHINFO_EXTENSION);
                                                    $icone  = '/icones/';
                                                    $icone .= in_array($ext, $exts) ? $ext : 'padrao';
                                                    $icone .= '.png';
                                                @endphp
                                                <td>
                                                    <img src="{{$icone}}" width="28" height="28" alt="{{$ext}}">
                                                </td>
                                            @endif
                                            <td>
                                                @foreach($arq->imagens as $arq_img)
                                                    <a href="{{ route('imagens.show', encrypt($arq_img->id)) }}" class="texto-preto">
                                                        {{ $arq_img->nome }}
                                                        {{!$loop->last ? ', ' : ''}}
                                                    </a>
                                                @endforeach 
                                            </td>
                                            <td class="texto-centralizado">
                                                <div class="dropdown">
                                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                        <i class="fa fa-cog" aria-hidden="true"></i>
                                                    </a>
                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenu{{ $arq->id }}" id="janela-dropdown02">

                                                        <li>
                                                            <a href="{{ Storage::url($arq->caminho) }}" download>
                                                                <i class="fa fa-download" aria-hidden="true"></i> {{ __('messages.Baixar')}}
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ Storage::url($arq->caminho) }}" target="_blank">
                                                                <i class="fa fa-eye" aria-hidden="true"></i> {{ __('messages.Visualizar')}}
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>

                    {{-- Tab #faturamento--}}
                    <div id="faturamento" class="tab-pane fade">
                        @can("gerencia-financeiro")
                        <div class="btn-toolbar margemT10" role="toolbar">
                            <a href="{{ route('clientes.show', encrypt($projeto->cliente_id)) }}" class="btn btn-primary " title=" {{ __('messages.Editar Faturamento') }}" data-toggle="tooltip">
                                <i class="fa fa-pencil margemR5" aria-hidden="true"></i>
                                {{ __('messages.Editar Faturamento')}}
                            </a>
                            <a href="#" class="btn btn-danger" title=" {{ __('messages.Adicionar Despesa ao Projeto') }}" data-toggle="tooltip">
                                <i class="fa fa-minus margemR5" aria-hidden="true"></i>
                               {{ __('messages.Adicionar Despesa')}}
                            </a>
                        </div>
                        <hr>
                        @endcan
                        @isset($projeto->faturamentos[0])
                            @include('faturamento.inputs', ['faturamento' => $projeto->faturamentos[0], 'detalhe' => true])
                        @endif
                    </div>

                    {{-- Tab #jobs--}}
                    <div id="jobs" class="tab-pane fade">
                        @can('cria-job')
                        <div class="btn-toolbar margemT10" role="toolbar">
                            <a href="{{ route('jobs.create', ['img_id'=>encrypt(0),'proj_id'=>encrypt($projeto->id)]) }}" class="btn btn-success no-border " title=" {{ __('messages.Criar Novo Job') }}" data-toggle="tooltip">
                                <i class="fa fa-plus" aria-hidden="true"></i>
                            </a>
                        </div>
                        <hr>
                        @endcan

                        {{--@unless(!$projeto->arquivos)--}}
                        <div class="table-responsive">
                            <table class="table table-striped no-margin margemB40 search-table">
                                <thead>
                                    <tr>
                                        <th>{{ __('messages.Tipo')}}</th>
                                        <th>{{ __('messages.Nome')}}</th>
                                        <th>{{ __('messages.Imagens Vinculadas')}}</th>
                                        <th>{{ __('messages.Tasks')}}</th>
                                        <th>{{ __('messages.Última Atualização')}}</th>
                                        <th class="">{{ __('messages.Progresso')}}</th>
                                        {{--<th class="texto-centralizado">{{ __('messages.Opções')}}</th>--}}
                                        @if($concluir_job)
                                            <th colspan="" class="box-title padding12 com-border-right">
                                                <form action="{{ route('job.mudarStatus.varios') }}" name="form_concluir_jobs" id="form-concluir-jobs" method="post">
                                                    @csrf
                                                    <button type="submit" disabled="true" data-toggle="tooltip" title="{{ __('messages.Concluir') }}" class="btn btn-default margemR20" id="button-jobs-concluir"><i class="fa fa-check-circle" aria-hidden="true"></i></button>
                                                </form>
                                            </th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($projeto->imagens)
                                        @foreach($projeto->imagens as $img)
                                            @if($img->jobs)
                                                @foreach($img->jobs->sortBy('created_at') as $job)
                                                    <tr>
                                                        <td>{{ $job->tipo->nome ?? '' }}</td>
                                                        <td><a href="{{ route('jobs.show', encrypt($job->id)) }}">{{ $job->nome }}</a></td>
                                                        <td>
                                                            @foreach($job->imagens as $i)
                                                                <a href="{{ route('imagens.show', encrypt($i->id)) }}" class="texto-preto">
                                                                    {{ $i->nome }}
                                                                </a>
                                                                @if(!$loop->last) , @endif
                                                            @endforeach
                                                        </td>

                                                        <td>@foreach($job->tasks as $tk)
                                                                <a href="{{ route('tasks.show', encrypt($tk->id)) }}" class="texto-preto">
                                                                    {{ $tk->nome }}
                                                                </a>
                                                                @if(!$loop->last) , @endif
                                                            @endforeach
                                                        </td>
                                                        <td><p class="texto-centralizado">{{ $job->updated_at   ? $job->updated_at->format('d/m/y') : __('messages.Não informado')}}</p></td>
                                                        <td class="">
                                                            <div class="progress">
                                                                @php 
                                                                    $class_status = '';
                                                                    if($job->status==8) {
                                                                        $class_status = 'background-color:#a90c14';
                                                                    }
                                                                @endphp
                                                                <div class="progress-bar progress-bar-animated progresso" style="{{ $class_status }}" role="progressbar" aria-valuenow="{{ $job->concluido() }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                            </div>
                                                        </td>
                                                        @if($concluir_job)
                                                            <td class="displayFlex flexCentralizado">
                                                                @if($job->pode_concluir)
                                                                    <input type="checkbox" name="concluir_job" id="concluir-job-{{ $job->id }}" class="job-concluir" value="{{ $job->id }}">
                                                                @endif
                                                            </td>
                                                        @endif
                                                       
                                                    </tr>
                                                @endforeach
                                            @endif
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endempty
    </div>

@stop

@push('js')

    <script>

        // Mudar cor da tab principal do accordion ao abrir
        $(document).ready(function(){
          $(".collapse").on("hide.bs.collapse", function(){
                $(this).css('background-color', '#f1f1f1');
                $(this.parentElement.children[0]).css('background-color', '#fff');
                // $('#' + this.parentElement.children[0].id + ' .titulo-tab-imagens').addClass('texto-preto');
                $('#' + this.parentElement.children[0].id + ' .titulo-tab-imagens').removeClass('texto-branco');
                $('#' + this.parentElement.children[0].id + ' a').removeClass('texto-branco');
          });
          $(".collapse").on("show.bs.collapse", function(){
                $(this).css('background-color', '#f1f1f1');
                $(this.parentElement.children[0]).css('background-color', '#5c9ba5');
                // $('#' + this.parentElement.children[0].id + ' .titulo-tab-imagens').removeClass('texto-preto');
                $('#' + this.parentElement.children[0].id + ' .titulo-tab-imagens').addClass('texto-branco');
                $('#' + this.parentElement.children[0].id + ' a').addClass('texto-branco');
          });
        });

    </script>

@endpush

@push('js')
    <script src="{{ asset('js/jquery.dataTables.js')}}"></script>

    <script>

        $(function () {
            var countJobs = 0;

            // ao clicar num check job-concluir marca ou desmarca e avlaia se habilita o botão de concluir jobs 
            $('.job-concluir').on('ifChanged', function(e) {
                e.preventDefault();
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
      
        
            $('#lista-dashboard').DataTable({
            "paging": false,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            'sProcessing': {{ __('messages.Processando...') }},

            });

            $('[type="search"]').addClass("form-control")

            $("select[name='lista-dashboard_length']").addClass("custom-select custom-select-sm form-control form-control-sm")


      });

    </script>

@endpush
