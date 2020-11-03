@extends('adminlte::page')

@section('title', @isset($job) ? 'Job ' . $job->nome : 'Job')

@section('css')
    @if($job->faz_avaliacao || $job->mostra_avaliacao)
        <link rel="stylesheet" href="{{ asset('css/star-rating.css')}} ">
        @if($job->faz_avaliacao)
            <link rel="stylesheet" href="{{ asset('css/avaliacao.css')}} ">
        @endif
    @endif
@endsection

@section('content_header')
   {{ Breadcrumbs::render('detalhe-job', $job) }}
   @if($job->avulso)
        <h3>{{ $job->nome }}</h3>
        @if($job->faz_avaliacao)
            @include('avaliacao.modal', ['job' => $job, 'avaliar' => true, 'role' => $usuario_ativo_role])
        @elseif($job->mostra_avaliacao)
            <div class="displayFlex flexStart larguraTotal">
                @include('avaliacao.modal', ['job' => $job, 'avaliar' => false, 'avaliacoes' => $job->avaliacoes, 'media' => null])
            </div>
        @endif
   @else
        <h3>  
            <a class="titulo-topo-tab" href="{{ route('projetos.show', encrypt($job->imagens->first()->projeto->id)) }}">
                {{ $job->imagens->first()->projeto->cliente->nome_fantasia . ' - ' . $job->imagens->first()->projeto->nome }}
            </a>
        </h3>
   @endif
@stop

@section('content') 

    <div class="row margemT40 centralizado">
        @empty($job)
            <h1>{{ __('messages.Job não Encontrado')}}</h1>
        @else
            <input type="hidden" id="rota_progresso"         value="{{ route('progresso.job', encrypt($job->id)) }}">
            <input type="hidden" id="rota_progresso_revisao" value="{{ route('progresso.revisao.job', encrypt($job->id)) }}">

            <div id="tabs-imagem"  class="nav-tabs-custom altura-minima"> 
                
                {{-- Titulos das Tabs--}}
                <ul class="nav nav-tabs laranja" id="tabs-job" role="tablist">
                    <li class="{{ isset($aba) && $aba == 'detalhes' ? 'active' : '' }}">
                        <a data-toggle="tab" href="#detalhes" aria-expanded="true" class="nav-link animated demo tomato " id="infodoprojet-tab" aria-controls="Dados da Imagem" aria-selected="false">
                            Job
                        </a>
                    </li>
                    <li>
                        <a data-toggle="tab" href="#info" aria-expanded="false" class="nav-link animated demo tomato" id="Arquivos-tab" aria-selected="false">{{ __('messages.Informações Extras')}}</a>
                    </li>

                    @if(!$job->avulso)
                        <li>
                            <a data-toggle="tab" href="#imagens" aria-expanded="false" class="nav-link animated demo tomato" id="Arquivos-tab" aria-selected="false">{{ __('messages.Imagens')}}</a>
                        </li>
                    @endif
                    <li class="{{ isset($aba) && $aba == 'files' ? 'active' : '' }}">
                        <a data-toggle="tab" href="#arquivos" aria-expanded="false" class="nav-link animated demo tomato" id="imagens-tab" aria-selected="false">{{ __('messages.Arquivos')}}</a>
                    </li>  

                     @foreach($job->revisoes as $index => $rev)
                        @if($index<@count($job->revisoes))
                        <li>
                            <a id="{{$job->id}}_R0{{$index+1}}" data-toggle="tab" href="#R0{{$index+1}}" aria-expanded="false" class="nav-link animated demo tomato" id="Arquivos-tab" aria-selected="false"> R0{{$index+1}}</a>
                        </li>
                        @endif           

                    @endforeach
                   
                    @if ($job->pode_comentar)  
                        <li>
                            <a data-toggle="tab" href="#comments-content" aria-expanded="false" class="nav-link animated demo tomato" id="comments-tab" aria-selected="false">{{ __('messages.Comentários')}}</a>
                        </li>
                    @endif

                    @can('aceita-proposta-job')   
                        <li>
                            <a data-toggle="tab" href="#proposals-content" aria-expanded="false" class="nav-link animated demo tomato" id="proposals-tab" aria-selected="false">{{ $job->status == $status_array['emproposta'] ? __('messages.Propostas') : __('messages.Candidaturas') }}</a>
                        </li>
                    @endcan                   


                </ul>

                {{-- Conteudos das Tabs --}}

                <div class="tab-content">

                    {{-- Tab Dados do Job --}}
                    <div id="detalhes" class="tab-pane fade {{ isset($aba) && $aba == 'detalhes' ? 'in active' : '' }}">
                        {{-- Botões --}}
                        <div class="row">
                            <div class="col-md-2 margemL30">
                                <h3 class="texto-preto">
                                    {{ __('messages.' . $status_array[$job->status] ) }}
                                    @if($job->status != 5 && @count($job->revisoes)>0)
                                         - R0{{@count($job->revisoes)}}
                                    @endif
                                </h3>
                                <h4><strong>{{ __('messages.Deadline')}}</strong>: {{ $job->data_prox_revisao ? $job->data_prox_revisao->format('d/m/Y') : __('messages.Não Informado') }}</h4>
                                @if($job->verificaStatus('concluido'))
                                    <h4><strong>{{ __('messages.Concluído em')}}</strong>: {{ $job->data_entrega ? $job->data_entrega->format('d/m/Y') : __('messages.Não Informado') }}</h4>
                                @endif
                            </div>

                            <div class="col-md-5 margemT10 ">
                                @if (Auth::user()->hasAnyRole(['desenvolvedor', 'admin','coordenador', 'publicador']))
                                    <div class="btn-toolbar" role="toolbar">
                                        @if($job->pode_editar)
                                            @can('atualiza-job')
                                                <a href="{{ route('jobs.edit', encrypt($job->id)) }}" class="btn btn-info" title="{{ __('messages.Editar Item')}}" data-toggle="tooltip">
                                                <i class="fa fa-pencil" aria-hidden="true"></i>
                                                </a>
                                            @endcan

                                            @can('deleta-job')
                                                <form action="{{ route('jobs.destroy', encrypt($job->id)) }}" class="form-delete" id="form-deletar-tipo-img-{{ $job->id }}" name="form-deletar-tipo-img-{{ $job->id }}" method="POST" enctype="multipart/form-data">
                                                    @method('DELETE')
                                                    @csrf
                                                    <a href="#" class="btn btn-danger deletar-item margemL5" title="{{  __('messages.Excluir Job')}}" data-toggle="tooltip" type="submit">
                                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                                    </a>
                                                </form>
                                            @endcan
                                        @endif

                                       
                                        @if(!$job->verificaStatus('recusado') && !$job->verificaStatus('concluido') && !$job->verificaStatus('parado'))
                                        
                                            @can('concluir-job')
                                                <form action="{{ route('job.mudarStatus', [encrypt($job->id),5]) }}" class="form-concluido" id="form-concluir-tipo-img" name="form-concuir-tipo-img-{{ $job->id }}" method="POST" enctype="multipart/form-data" style="visibility:
                                                {{$job->concluido()>=100 ? 'visible' : 'hidden' }}" >
                                                    @csrf
                                                    @if($job->concluir_ok)
                                                        <button class="btn btn-success concluir-item-ok margemL5" title="{{ __('messages.Concluir Job')}}" data-toggle="tooltip" type="submit">
                                                            <i class="fa fa-check-circle" aria-hidden="true"></i>
                                                        </button>
                                                    @else
                                                        <a href="#" class="btn btn-success concluir-item margemL5" title="{{ __('messages.Concluir Job')}}"  data-toggle="tooltip" type="submit">
                                                            <i class="fa fa-check-circle" aria-hidden="true"></i>
                                                        </a>
                                                    @endif
                            
                                                </form>
                                            @endcan

                                            {{-- Verifica se o job está concluido ou se teve data expirada --}}
                                            @php $mostra = $job->concluido()>=100 || $job->data_prox_revisao < \Carbon\Carbon::now() @endphp
                                            
                                            <form action="{{ route('job.mudarStatus', [encrypt($job->id),6]) }}" class="form-recusado"   
                                                id="form-recusado-tipo-img" name="form-recusado-tipo-img-{{ $job->id }}" 
                                                method="POST" enctype="multipart/form-data"  style="visibility:{{$mostra ? 'visible' : 'hidden' }}">
                                                @csrf 
                                                <a href="#" class="btn btn-danger recusar-item margemL5" title="{{ __('messages.Recusar Job')}}" 
                                                    data-toggle="tooltip" type="submit">
                                                    <i class="fa fa-close" aria-hidden="true"></i>
                                                </a>
                                            </form>

                                            @if($job->pode_editar)
                                                <form action="{{ route('job.mudarStatus', [encrypt($job->id),8]) }}" class="form-parado"     
                                                    id="form-parado-tipo-img" name="form-parado-tipo-img-{{ $job->id }}" 
                                                    method="POST" enctype="multipart/form-data"  style="visibility:                   
                                                    {{$job->concluido()<100 && $job->status != 8 ? 'visible' : 'hidden' }}">
                                                    @csrf
                                                    <a href="#" class="btn btn-danger parar-item margemL5" title="{{ __('messages.Parar Job')}}" 
                                                        data-toggle="tooltip" type="submit">
                                                        <i class="fa fa-ban" aria-hidden="true"></i>
                                                    </a>
                                                </form>
                                            @endif
                                        @endif

                                        @if($job->verificaStatus('concluido') || $job->verificaStatus('recusado')  || $job->verificaStatus('parado'))
                                            @if($job->pode_editar)
                                                <form action="{{ route('job.mudarStatus', [encrypt($job->id),7]) }}" class="form-reabir" id="form-reabir-tipo-img" 
                                                    name="form-reabir-tipo-img-{{ $job->id }}" method="POST" enctype="multipart/form-data" 
                                                    >
                                                    @csrf
                                                    <button class="btn btn-warning reabir-item margemL5" title="{{ __('messages.Reabrir Job')}}" data-toggle="tooltip" type="submit">
                                                        <i class="fa fa-unlock" aria-hidden="true"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        @endif

                                        {{-- prorrogar data entrega --}}
                                        @if(!$job->verificaStatus('recusado') && !$job->verificaStatus('concluido') && !$job->verificaStatus('parado'))
                                            <form action="{{ route('job.prorrogarDataEntrega', [encrypt($job->id)]) }}" class="form-prorrogar" id="form-prorrogar-tipo-img" 
                                                name="form-prorrogar-tipo-img-{{ $job->id }}" method="POST" enctype="multipart/form-data" 
                                                >
                                                {{-- <input type="hidden" id="data-atual-entrega" value="{{ $job->data_prox_revisao ? $job->data_prox_revisao->format('d/m/Y') : date('d/m/Y') }}"> --}}
                                                {{-- //26/10/2020 - prorrogar para qualquer data igual ou maior data atual --}}
                                                <input type="hidden" id="data-atual-entrega" value="{{ date('Y-m-d') }}">
                                                @csrf
                                                <button class="btn btn-warning prorrogar-prazo-item margemL5" title="{{ __('messages.Prorrogar Job') }}" data-toggle="tooltip" type="submit">
                                                    <i class="fa fa-calendar" aria-hidden="true"></i>
                                                </button>
                                            </form>
                                        @endif
                                       
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-3 margemT20">
                                <div class="col-md-2 negrito">{{ __('messages.Job')}}</div>
                                <div class="col-md-10">
                                    <div id="progresso-job" class="progress-bar progress-bar-animated progress-bar-striped progress-bar-{{ $job->status == 8 ? 'danger' : 'success' }}  progresso pull-right" style="float" role="progressbar" aria-valuenow="{{ $job->concluido() }}" aria-valuemin="0" aria-valuemax="100" ></div>
                                </div>
                                <div class="col-md-2 negrito margemT5">{{ __('messages.Revisão')}}</div>
                                <div class="col-md-10 margemT5">
                                    <div id="progresso-revisao" class="progress-bar progress-bar-animated progress-bar-striped cyan  progresso pull-right" role="progressbar" aria-valuenow="{{ $job->concluidoRevisao() }}" aria-valuemin="0" aria-valuemax="100" ></div>
                                    <input type="hidden" value= "{{ $job->concluidoRevisao() }}" id="valor-progresso-revisao">
                                </div>
                            </div>
                             
                            @if($job->verificaStatus('parado')) 
                                <div class="col-md-12 margemT30">
                                    <strong> {{ __('messages.Motivo da pausa')}}:</strong>
                                    <p> {{ $job_parado->motivo }}</p>
                                </div>
                            @endif
                        </div>
                        <hr>
                        <div class="box box-solid no-border">
                            <div class="box-body box-profile">
                                <div class="row">
                                    {{-- Dados do Job --}}
                                    <div class="col-md-8">
                                        
                                        @if(!$job->avulso) {{-- Se não for avulso --}}
                                            <div class="col-md-12 margemB40">
                                                <h3 class="margem-bottom-menor margemT5">{{ __('messages.Job')}}</h3>
                                                <h4>{{ $job->nome }}</h4>
                                            </div>
                                        @endif
                                        
                                        <div class="col-md-3">
                                            <h5 class="margem-bottom-menor negrito">{{ __('messages.Tipo de Job')}}</h5>
                                            <a class="texto-preto" href="#">
                                                <h4>{{ $job->tipo->nome ?? __('messages.Não Informado') }}</h4>
                                            </a>
                                        </div>
                                        @if($job->coordenador && $job->coordenador->name)
                                            <div class="col-md-3">
                                                <h5 class="margem-bottom-menor negrito">{{ __('messages.Coordenador')}}</h3>
                                                <h4>{{ $job->coordenador->name ?? __('messages.Não Informado') }}</h4>
                                            </div>
                                        @endif
                                        
                                        @if(!\Auth::user()->isFreela())
                                            @php
                                                $usuario_ativo = \Auth::user();
                                                $link_perfil = false;
                                                if(($usuario_ativo->isPublicador() || $usuario_ativo->isCoordenador()) && $job->delegado_para){
                                                    $link_perfil = true;
                                                }
                                                
                                            @endphp
                                            <div class="col-md-3">
                                                <h5 class="margem-bottom-menor negrito">{{ $link_perfil ? __('messages.Freelancer') : __('messages.Delegado para')  }} </h3>
                                                <h4>
                                                    @if ($link_perfil)
                                                        <a href="{{ route('users.show', encrypt($job->delegado_para)) }}"> {{$job->delegado->name}}</a>
                                                    @else
                                                        {{ $job->delegado->name ?? __('messages.Não Informado') }} 
                                                    @endif 
                                                </h4>
                                                
                                            </div>
                                        @endif
                                        
                                        <div class="col-md-3">
                                            <h5 class="margem-bottom-menor negrito">{{ __('messages.Data de Criação')}}</h3>
                                            <h4>{{ $job->created_at ? $job->created_at->format('d/m/Y') :  __('messages.Não Informado') }}</h4>
                                        </div>
                                        
                                        <div class="col-md-3">
                                            <h5 class="margem-bottom-menor negrito">{{ __('messages.Última Atualização')}}</h3>
                                            <h4>{{ $job->created_at ? $job->created_at->format('d/m/Y') :  __('messages.Não Informado') }}</h4>
                                        </div>
                                        
                                        {{-- <div class="col-md-3">
                                            <h5 class="margem-bottom-menor negrito">{{ __('messages.Previsão de Entrega')}}</h3>
                                            <h4>{{ $job->data_prox_revisao ? $job->data_prox_revisao->format('d/m/Y') :  __('messages.Não Informado') }}</h4>
                                        </div>  --}}
                                        
                                        @if(!\Auth::user()->isPublicador())
                                            <div class="col-md-3">
                                                <h5 class="margem-bottom-menor negrito">
                                                    @if($job->user->id == $job->user_id)
                                                        {{ __('messages.Publicado por')}}
                                                    @else
                                                        {{ __('messages.Criado por')}}
                                                    @endif
                                                </h3>
                                                <h4>{{ $job->user->name ??  __('messages.Não Informado') }}</h4>
                                            </div>
                                        @endif

                                        @can('visualiza-valor')
                                            <div class="col-md-3">
                                                <h5 class="margem-bottom-menor negrito">{{ __('messages.Valor')}}</h3>
                                                    @php $money = $job->valor_desconto ?? ($job->valor_job ?? '0,00'); @endphp
                                                    <h4>R$ 
                                                        {{-- Se em candidatura --}}
                                                        @if($job->candidaturaFreela)
                                                            @php $money =floatval($job->candidaturaFreela->valor); @endphp
                                                            @convert_money($money)
                                                        @else
                                                            @if($money)
                                                                @convert_money($money)
                                                            @endif
                                                        @endif 
                                                        @if($job->status==$status_array['emproposta'])
                                                            @if($job->candidaturaFreela)
                                                                {{ '(' . __('messages.Sua Proposta foi enviada!') . ')'  }}
                                                            @else
                                                                {{ '(' . __('messages.Aguardando Propostas') . ')'  }}
                                                            @endif
                                                        @elseif($job->status==$status_array['emcandidatura'])
                                                            @if($job->candidaturaFreela)
                                                                {{ '(' . __('messages.Sua Candidatura foi enviada!') . ')'  }}
                                                            @else
                                                                {{ '(' . __('messages.Aguardando Candidaturas') . ')'  }}
                                                            @endif
                                                        @endif
                                                    </h4>
                                            </div>
                                            @if($job->status==$status_array['emcandidatura'] || $job->status==$status_array['emproposta'])
                                                <div class="col-md-3">
                                                    <h5 class="margem-bottom-menor negrito">{{ __('messages.Data limite para Propostas')}}</h3>
                                                    <h4>
                                                        {{ $job->data_limite ? $job->data_limite->format('d/m/Y') : __('messages.Não Informado') }}
                                                    </h4>
                                                </div>
                                            @endif

                                        @endcan

                                        @if(Auth::user()->hasAnyRole(['admin', 'desenvolvedor']))
                                            <div class="col-md-3">
                                                <h5 class="margem-bottom-menor negrito">{{ __('messages.Painel')}}</h3>
                                                <h4>{{ $job->freela==1 ? __('messages.Freela'): __('messages.Interno') }}</h4>
                                            </div>
                                        @endif

                                        @if($job->job_delivery_value)
                                            <div class="col-md-3">
                                                <h5 class="margem-bottom-menor negrito">
                                                    {{ __('messages.Formato de Entrega')}}
                                                </h3>
                                                <h4>@foreach ($job->job_delivery_value  as $index =>  $item)
                                                    {{ $item }} 
                                                    @if(count($job->job_delivery_value) > $index+1)
                                                    / 
                                                    @endif
                                                @endforeach </h4>
                                            </div>
                                        @endif
                                        
                                        <div class="col-md-9 sem-padding-left margemT20">
                                            <div class="col-md-12">
                                                <h5 class="margem-bottom-menor negrito">{{ __('messages.Descrição')}}</h3>
                                                <h5>
                                                    @php if($job->descricao == null) {
                                                        $job->descricao = __('messages.Não Informado');
                                                    }
                                                    @endphp 
                                                    {!!html_entity_decode($job->descricao) !!}
                                                </h5>
                                            </div>

                                        </div>


                                        
                                        <div class="col-md-3">
                                            @if($job->thumb)
                                                <img src="{{URL::asset('storage/' . $job->thumb)}}" alt="{{ __('messages.Referência do Job')}}" class="img-responsive card-job-thumb">
                                            @else
                                                <img src="{{asset('storage/imagens/jobs/job_default.png')}}" alt="{{ __('messages.Referência do Job')}}" class="img-responsive card-job-thumb">
                                            @endif
                                        </div>    
                                        
                                        

                                           
                                    {{-- comentando para teste 22/09/2020 --}}
                                    {{-- @if($job->freela==1 ) --}}

                                    @if($job->mostra_revisao)  
                                    @if(count($job->avaliacao)>0)
                                    <div class="col-md-12" id="box-revisao" >
                                        
                                        {{-- Revisões --}}                                        
                                        <div class="col-md-12 tab-avaliacao pull-right">
                                            <div class="box box-solid box-primary no-border com-shadow ">
                                                <a class="larguraTotal" data-toggle="collapse" data-parent="#accordionTasks" href="#collapseOneTasks" aria-expanded="false">
                                                    <div class="box-header with-border fundo-verde com-borda">
                                                        <h3 class="box-title larguraTotal cor-branca">{{ __('messages.Revisão')}}</h3>
                                                    </div>
                                                </a>
                                                <div id="collapseOneTasks" class="panel-collapse collapse in" aria-expanded="false" >
                                                    <div class="box-body box-profile">
                                                        
                                                        <table class="table table-striped no-margin margemB40">
                                                            <thead>
                                                                <tr>
                                                                    <th>{{ __('messages.Nome')}}</th>
                                                                    <th class="texto-centralizado">{{ __('messages.Visualizar')}}</th>
                                                                    <th class="texto-centralizado">{{ __('messages.Baixar')}}</th>
                                                                    <th class="texto-centralizado">{{ __('messages.Data de Entrega')}}</th>
                                                                </tr>
                                                            </thead>
                                                        <tbody>     
                                                        @foreach($job->revisoes as $index => $aval)                      
                                                            <tr>
                                                                <td>R0{{$index+1}}</td>
                                                                <td class="texto-centralizado" >
                                                                    @if($index<3)
                                                                        @if($index<@count($job->revisoes))
                                                                        <a  class="nav-link-revisao" data-tab="{{$job->id}}_R0{{$index+1}}" aria-selected="false"><i class="fa fa-eye margemR5" aria-hidden="true"></i></a>
                                                                            {{-- <span class="link visualiza-revisao " data-url="{{ route('visualizar.revisao.avaliacao', ['id'=>$job->revisoes[$index]->id, 'tira_arquivo'=>1]) }}" data-tira-arquivos="true" data-title="R0{{$index+1}}" >
                                                                                <i class="fa fa-eye margemR5" aria-hidden="true"></i>
                                                                            </span> --}}
                                                                        @endif

                                                                    @endif
                                                                </td>
                                                                <td  class="texto-centralizado">
                                                                    <a class="texto-centralizado larguraTotal studio-download-link" href="{{ Storage::url($aval->imagem_revisao) }}" download>
                                                                        <i class="fa fa-download" aria-hidden="true"></i> 
                                                                    </a>
                                                                </td>
                                                                <td class="texto-centralizado">
                                                                    {{  $aval->data_entrega ? Carbon\Carbon::parse($aval->data_entrega)->format('d/m/Y') : '' }}
                                                                        
                                                                </td>
                                                            </tr>                                                                
                                                        @endforeach
                                                        </table>

                                                        @if($job->cria_revisao || $job->deleta_revisao_atual )
                                                            <div class="col-md-12 col-sm-12">
                                                                <div class="col-md-12 margemB5" >
                                                                    <h3 class="texto-centralizado" >
                                                                    {{ __('messages.Revisão')}}
                                                                    </h3>
                                                                </div>
                                                                @if($job->cria_revisao)
                                                                    <div class="col-md-12 texto-centralizado">
                                                                        <span id="add-revisao" class="btn no-border" data-url="{{ route('nova.revisao.avalicao', ['id'=>encrypt($job->id), 'tira_arquivo'=>0]) }}" data-title="{{ __('messages.Nova Revisão') }}" data-deadline="{{ $job->data_prox_revisao ?? '' }}" data-toggle="tooltip">
                                                                            <i class="fa fa-plus margemR5" aria-hidden="true"></i> {{ __('messages.Criar Revisão')}}
                                                                        </span>
                                                                    </div>
                                                                @endif
                                                                @if($job->deleta_revisao_atual)
                                                                    <div class="col-md-12 texto-centralizado">
                                                                        
                                                                        <form action="{{ route('excluir.revisao.avaliacao', encrypt($job->revisoes->last()->id)) }}" class="form-delete" 
                                                                            id="form-deletar-avalicao-job-{{ $job->revisoes->last()->id }}" 
                                                                            name="form-deletar-avaliacao-job-{{ $job->revisoes->last()->id }}" 
                                                                            method="POST" enctype="multipart/form-data">
                                                                            @method('DELETE')
                                                                            @csrf
                                                                            <h4>{{ __('messages.Excluir revisão atual?')}}</h4>
                                                                            <a href="#" class="btn btn-danger deletar-item margemL5" title="{{ __('messages.Excluir Revisão')}}" data-toggle="tooltip" type="submit">
                                                                                <i class="fa fa-trash" aria-hidden="true"></i>
                                                                            </a>
                                                                        </form>
                                                                    </div>
                                                                @endif
                                                                @if($job->hr_solicitado==0)
                                                                    <div class="col-md-3 margemB5 pull-right">
                                                                        
                                                                        <form action="{{ route('job.solicitar.hr', encrypt($job->id)) }}" class="form-solicitar-hr" id="form-solicitar-hr" name="form_solicitar_hr_{{ $job->id }}" method="POST" enctype="multipart/form-data" style="visibility:visible' }}" >
                                                                            @csrf
                                                                            <a href="#" class="btn btn-success solicite-hr-item pull-right" title="{{ __('messages.Solicite HR')}}" data-title="{{ __('messages.Solicite HR')}}" data-mensagem="{{ __('messages.Solicitar o HR?')}}"  data-toggle="tooltip" type="submit">
                                                                                {{ __('messages.Solicitar HR')}}  <i class="fa fa-check-circle margemL5" aria-hidden="true"></i> 
                                                                            </a>
                                                                        </form>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        @endif                                                        

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    
                                    </div>
                                    @endif
                                @endif
                                {{-- @endif --}}
                                
                                {{-- comentado para teste 22/09/2020 --}}
                                {{-- @if($job->freela==1 ) --}}
                                {{-- Arquivo Para Avaliação --}}
                                <div class="col-md-12" id="arquivo-avaliacao" style="visibility: {{ $job->valor_progressao>=100 ? 'visible' : 'hidden' }};" >
                                    
                                    <div class="col-md-12 tab-avaliacao pull-right">
                                        <div class="box box-solid box-primary no-border com-shadow ">
                                            <a class="larguraTotal" data-toggle="collapse" data-parent="#accordionTasks" href="#collapseOneTasks" aria-expanded="false">
                                                <div class="box-header with-border fundo-verde com-borda">
                                                    <h3 class="box-title larguraTotal cor-branca">{{ __('messages.Avaliação')}}</h3>
                                                </div>
                                            </a>
                                            <div id="collapseOneTasks" class="panel-collapse collapse in" aria-expanded="false" >
                                                <div class="box-body box-profile">
                                                    {{-- {{ dd($job->cria_upload) }} --}}
                                                    @if($job->delegado && $job->delegado->id == Auth::user()->id)
                                                        @if($job->hr_solicitado ==0)
                                                            <form id="form-job-avaliacao" class="displayFlex" name="form-job-avaliacao" action="{{ route('jobs.upload.avaliacao', encrypt($job->id)) }}" method="POST" enctype="multipart/form-data">
                                                                @csrf
                                                
                                                                {{-- inserir essa parte via js com o 100% após a mensagem de suceso  --}}
                                                                <span id="cria_upload" value="{{ $job->cria_upload ? '1' : '0' }}"></span>
                                                                @if($job->pode_link_hr)
                                                                    {{-- input para link de arquivos de avaliação --}}
                                                                    <div id="div-upload-avaliacao" class="col-md-12  pull-right" style="display:{{ $job->cria_upload ? 'block ' : 'none'}}">
                                                                        

                                                                            <h4 class="negrito"> {{ __('messages.Cole o link da avaliação na rede') }} </h4>
                                                                            <div class="col-md-9">
                                                                                <input type="text" name="imagem_avaliacao" value="" class="form-control" placeholder="{{ __('messages.Cole o link da avaliação na rede')}}."  >
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <button type="submit" class="btn btn-success pull-right"  > 
                                                                                    <span class="glyphicon glyphicon-ok"></span> {{ __('messages.Enviar')}}
                                                                                </button> 
                                                                            </div>
                                                        
                                                                    </div>
                                                                @else
                                                                    <div id="div-upload-avaliacao" class="col-md-12  pull-right" style="display:{{ $job->cria_upload ? 'block ' : 'none'}}">
                                                                        <div class="col-md-9">
                                                                            <div id="file-include-avalicao" class="input-group image-preview"> 
                                                                                <input id="arquivo" type="text" class="form-control image-preview-filename" disabled="disabled" placeholder="{{ __('messages.Nenhuma imagem selecionada')}}."> 
                                                                                <span class="input-group-btn"> 
                                                                                    <button type="button" class="btn btn-default image-preview-clear" style="display:none;"> 
                                                                                    <span class="glyphicon glyphicon-remove"></span> {{ __('messages.Limpar')}}
                                                                                    </button> 
                                                                                    <div class="btn btn-default image-preview-input"> 
                                                                                        <span class="glyphicon glyphicon-folder-open"></span> 
                                                                                        <span class="image-preview-input-title">{{ __('messages.Procurar')}}</span> 
                                                                                        <input id="input-arquivo" type="file" accept="*" name="imagem_avaliacao" /> 
                                                                                    </div> 
                                                                                </span> 
                                                                            </div> 
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <div id="file-include-avalicao-botao" class=""> 
                                                                                <button type="submit" class="btn btn-success image-preview-submit pull-right"  > 
                                                                                    <span class="glyphicon glyphicon-ok"></span> {{ __('messages.Enviar')}}
                                                                                </button> 
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                                
                                                                {{-- fim inserir --}}
                                                                <input id="nome-job" type="hidden" value="{{ $job->nome }}" name="nome_job" />
                                                            </form>
                                                        @else 
                                                            @if($job->hr_url=="")
                                                                @if($job->pode_link_hr)
                                                                    {{-- input para link de HR --}}
                                                                    <form id="form-job-hr" class="displayFlex" name="form-job-hr" action="{{ route('jobs.upload.hr', encrypt($job->id)) }}" method="POST" enctype="multipart/form-data">
                                                                        @csrf
                                                                        <h4 class="negrito"> {{ __('messages.Cole o link da rede do HR') }} </h4>
                                                                        <div class="col-md-9">
                                                                            <input type="text" name="hr_link" value="" class="form-control" placeholder="{{ __('messages.Cole o link da rede do HR')}}."  >
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <button type="submit" class="btn btn-success pull-right"  > 
                                                                                <span class="glyphicon glyphicon-ok"></span> {{ __('messages.Enviar')}}
                                                                            </button> 
                                                                        </div>
                                                                    </form>
                                                                @else
                                                                    {{-- form enviar hr --}}
                                                                    <h4 class="negrito"> {{ __('messages.Enviar HR') }} </h4>
                                                                    <form id="form-job-hr" class="displayFlex" name="form-job-hr" action="{{ route('jobs.upload.hr', encrypt($job->id)) }}" method="POST" enctype="multipart/form-data">
                                                                        @csrf

                                                                        {{-- inserir essa parte via js com o 100% após a mensagem de suceso  --}}
                                                                        <span id="cria_upload" value="{{ $job->cria_upload ? '1' : '0' }}"></span>
                                                                        <div id="div-upload-hr" class="col-md-12  pull-right" style="display:{{ $job->cria_upload ? 'block ' : 'none'}}">
                                                                            <div class="col-md-9">
                                                                                <div id="file-include-hr" class="input-group image-preview"> 
                                                                                    <input id="arquivo" type="text" class="form-control image-preview-filename" disabled="disabled" placeholder="{{ __('messages.Nenhuma imagem selecionada')}}."> 
                                                                                    <span class="input-group-btn"> 
                                                                                        <button type="button" class="btn btn-default image-preview-clear" style="display:none;"> 
                                                                                        <span class="glyphicon glyphicon-remove"></span> {{ __('messages.Limpar')}}
                                                                                        </button> 
                                                                                        <div class="btn btn-default image-preview-input"> 
                                                                                            <span class="glyphicon glyphicon-folder-open"></span> 
                                                                                            <span class="image-preview-input-title">{{ __('messages.Procurar')}}</span> 
                                                                                            <input id="input-arquivo" type="file" accept="*" name="imagem_hr" /> 
                                                                                        </div> 
                                                                                    </span> 
                                                                                </div> 
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <div id="file-include-hr-botao" class=""> 
                                                                                    <button type="submit" class="btn btn-success image-preview-submit pull-right"  > 
                                                                                        <span class="glyphicon glyphicon-ok"></span> {{ __('messages.Enviar')}}
                                                                                    </button> 
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        
                                                                        {{-- fim inserir --}}
                                                                        <input id="nome-job" type="hidden" value="{{ $job->nome }}" name="nome_job" />
                                                                    </form>
                                                                @endif
                                                                
                                                            @endif
                                                        @endif

                                                        
                                                    @endif

                                                    {{-- arquivos HR --}}
                                                    @if($job->hr_url)
                                                        {{-- mostra arquivo HR se já foi salvo --}}
                                                        <span class="negrito"> HR  </span>
                                                        <a href="{{ URL::asset('storage/' . $job->hr_url )}}" download>
                                                            @php
                                                                $exts   = ['3ds', 'cad', 'doc', 'dxf', 'max', 'pdf', 'psd', 'txt', 'docx', 'xls', 'zip', 'dwg'];
                                                                $ext    = pathinfo($job->hr_url, PATHINFO_EXTENSION);
                                                                $icone  = '/icones/';
                                                                $icone .= in_array($ext, $exts) ? $ext : 'padrao';
                                                                $icone .= '.png';
                                                                $img_name = explode('/', $job->hr_url) ?? array();
                                                            @endphp
                                                            <img alt="{{ __('messages.click para baixar o arquivo')}}" src="{{$icone}}" width="28" height="28" alt="{{$ext}}">
                                                            {{ end($img_name) }}
                                                        </a>
                                                        @if($job->pode_link_hr)
                                                            <input type="text" readonly name="link_hr_interno" id="link-hr-interno" value="{{ end($img_name) }}" style="position: absolute; top: -9999px">
                                                            <input type="hidden" name="titulo_copy_hr" id="titulo-copy-hr" value="{{ __('messages.Endereço HR copiado')}}">
                                                            <input type="hidden" name="texto_copy_hr" id="texto-copy-hr" value="{{ __('messages.Endereço HR copiado para área de transfêrencia')}}">
                                                            <a href="#"  onclick="copyStringToClipboard()" style="font-size:12px;color:blue" title="{{ __('messages.Copiar endereço HR')}}" >
                                                                <i class="fa fa-clipboard" aria-hidden="true"></i>
                                                            </a>
                                                        @endif
                                                        
                                                    @endif

                                                    {{-- revisao --}}
                                                    @if($job->mostra_revisao)  
                                                        <table class="table table-striped no-margin margemB40 margemT20">
                                                            <thead>
                                                                <tr>
                                                                    <th>{{ __('messages.Arquivo')}}</th>
                                                                    {{-- <th>Nome</th> --}}
                                                                    <th class="texto-centralizado">{{ __('messages.Revisão')}}</th>
                                                                    <th class="texto-centralizado">{{ __('messages.Enviado em')}}</th>
                                                                    <th class="texto-centralizado">{{ __('messages.Baixar')}}</th>
                                                                </tr>
                                                            </thead>
                                                        <tbody>   
                                                        @foreach($job->avaliacao as $index => $aval)                      
                                                            <tr>

                                                                <td>{{$index+1}} 
                                                                    @if(!$job->pode_link_hr)
                                                                        <a href="{{ URL::asset('storage/' . $aval->imagem )}}" download>
                                                                            @php
                                                                                $exts   = ['3ds', 'cad', 'doc', 'dxf', 'max', 'pdf', 'psd', 'txt', 'docx', 'xls', 'zip', 'dwg'];
                                                                                $ext    = pathinfo($aval->imagem, PATHINFO_EXTENSION);
                                                                                $icone  = '/icones/';
                                                                                $icone .= in_array($ext, $exts) ? $ext : 'padrao';
                                                                                $icone .= '.png';
                                                                                $img_name = explode('/', $aval->imagem) ?? array();
                                                                            @endphp
                                                                            <img alt="{{ __('messages.click para baixar o arquivo')}}" src="{{$icone}}" width="28" height="28" alt="{{$ext}}">
                                                                            {{ end($img_name) }}
                                                                        </a>
                                                                    @else
                                                                        <a href="{{ URL::asset('storage/' . $aval->imagem )}}">
                                                                            @php
                                                                                $exts   = ['3ds', 'cad', 'doc', 'dxf', 'max', 'pdf', 'psd', 'txt', 'docx', 'xls', 'zip', 'dwg'];
                                                                                $ext    = pathinfo($aval->imagem, PATHINFO_EXTENSION);
                                                                                $icone  = '/icones/';
                                                                                $icone .= in_array($ext, $exts) ? $ext : 'padrao';
                                                                                $icone .= '.png';
                                                                                $img_name = explode('/', $aval->imagem) ?? array();
                                                                            @endphp
                                                                            <img alt="{{ __('messages.click para baixar o arquivo')}}" src="{{$icone}}" width="28" height="28" alt="{{$ext}}">
                                                                            {{ end($img_name) }}
                                                                        </a>
                                                                        
                                                                        <input type="text" readonly name="link_avaliacao_interno" id="link-avaliacao-interno{{$index+1}}" value="{{ end($img_name) }}" style="position: absolute; top: -9999px">
                                                                        <input type="hidden" name="titulo_copy_avaliacao" id="titulo-copy-avaliacao" value="{{ __('messages.Endereço avaliacao copiado')}}">
                                                                        <input type="hidden" name="texto_copy_avaliacao" id="texto-copy-avaliacao" value="{{ __('messages.Endereço avaliacao copiado para área de transfêrencia')}}">
                                                                        
                                                                    @endif
                                                                </td>
                                                                <td class="texto-centralizado" >
                                                                    <span class="" >R0{{ $index}}</span>
                                                                </td>
                                                                <td class="texto-centralizado" >
                                                                    {{ $aval->created_at->format('d/m/yy') }}
                                                                </td>
                                                                <td  class="texto-centralizado">
                                                                    @if(!$job->pode_link_hr)
                                                                        <a href="{{ Storage::url($aval->imagem) }}" download>
                                                                            <i class="fa fa-download" aria-hidden="true"></i> 
                                                                        </a>
                                                                    @else
                                                                        <a href="#"  onclick="copyStringToClipboard('link-avaliacao-interno{{$index+1}}')" style="font-size:12px;color:blue" title="{{ __('messages.Copiar endereço HR')}}" >
                                                                            <i class="fa fa-clipboard" aria-hidden="true"></i>
                                                                        </a>
                                                                    @endif
                                                                </td>
                                                            </tr>                                                                
                                                        @endforeach
                                                        </table>
                                                    @endif

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- @endif --}}


                                    </div>

                                    <div class="col-md-4">

                                        {{-- Tasks Job --}}
                                        <div class="col-md-12">
                                            {{-- Tasks --}}
                                            <div class="col-md-12 col-sm-12 tab-tasks pull-right">
                                                <div class="box box-solid box-primary no-border com-shadow ">
                                                    <a class="larguraTotal" data-toggle="collapse" data-parent="#accordionTasks" href="#collapseOneTasks" aria-expanded="false">
                                                        <div class="box-header with-border fundo-verde com-borda">
                                                            <h3 class="box-title larguraTotal cor-branca"> {{ __('messages.Tasks')}}</h3>
                                                        </div>
                                                    </a>
                                                    <div id="collapseOneTasks" class="panel-collapse collapse in" aria-expanded="false" >
                                                        <div class="box-body box-profile">
                                                        @isset($job->tasks)
                                                            @can('executa-job')
                                                            {{-- {{ dd($job->tasks) }} --}}

                                                                <table class="table">
                                                                    <thead>
                                                                        <th colspan="3">{{ __('messages.Lista de tarefas do Job')}}</th>
                                                                    </thead>
                                                                    <tbody>
                                                                        {{-- @if(
                                                                            ($job->coordenador && Auth::user()->id   == $job->coordenador->id)  || 
                                                                            ($job->delegado    && $job->delegado->id == Auth::user()->id)       || 
                                                                            $job->user_id      == Auth::user()->id   || Auth::user()->isAdmin() || 
                                                                            Auth::user()->isDev() 
                                                                            ) --}}
                                                                            @foreach($job->tasks as $task)
                                                                                <tr>
                                                                                    @if($job->tasks_inputs)
                                                                                        <td>
                                                                                            {{-- @if($job->verificaStatus('concluido') || $job->verificaStatus('recusado') || $job->verificaStatus('parado') ||  ($job->user_id == Auth::user()->id && !Auth::user()->isAdmin())  )  --}}
                                                                                            <input 
                                                                                                @if($job->desativa_tasks || $job->avaliacao->count()>0) 
                                                                                                    disabled="true" 
                                                                                                @endif 
                                                                                                
                                                                                                type="checkbox" class="task-check" id="task-{{$task->id}}"

                                                                                                value="{{ encrypt($task->id) }}" name="task_id[][{{encrypt($task->id)}}]"
                                                                                                data-url="{{ route('executar.task', [encrypt($job->id), encrypt($task->id)]) }}"
                                                                                                {{ $task->pivot->status != 0 ? 'checked="checked"' : '' }} >
                                                                                    </td>
                                                                                    @endif
                                                                                    <td class="task-nome" id="nome-task-{{encrypt($task->id)}}">{{ $task->nome }}</td>
                                                                                </tr>
                                                                            @endforeach
                                                                        {{-- @endif --}}
                                                                    </tbody>
                                                                </table>
                                                            @endcan
                                                        @endif
                                                    </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Tasks Revisao --}}
                                        @isset($tasks_revisao_job)
                                            <div class="col-md-12">
                                                
                                                {{-- Tasks Revisao --}}
                                                @foreach ($job->revisoes as $indexRev => $rev)
                                                
                                                    <div class="col-md-12 col-sm-12 tab-tasks pull-right">
                                                        <div class="box box-solid box-primary no-border com-shadow ">
                                                            <a class="larguraTotal" data-toggle="collapse" data-parent="#accordionTasks" href="#collapseOneTasks" aria-expanded="false">
                                                                <div class="box-header with-border fundo-verde com-borda">
                                                                    <h3 class="box-title larguraTotal cor-branca"> {{ __('messages.Tasks Revisão')}} R0{{$indexRev+1}}</h3>
                                                                </div>
                                                            </a>
                                                            <div id="collapseOneTasks" class="panel-collapse collapse in" aria-expanded="false" >
                                                                <div class="box-body box-profile">
                                                                    @can('executa-job')
                                                                    {{-- {{ dd($job->tasks) }} --}}

                                                                        <table class="table">
                                                                            <thead>
                                                                                <th colspan="3">R0{{$indexRev+1}} {{ __('messages.Lista de tarefas da Revisão')}} </th>
                                                                            </thead>
                                                                            <tbody>
                                                                                {{-- @if(
                                                                                    ($job->coordenador && Auth::user()->id == $job->coordenador->id) || 
                                                                                    ($job->delegado    && $job->delegado->id == Auth::user()->id) || 
                                                                                    $job->publicador_id == Auth::user()->id  ||  Auth::user()->isAdmin() || 
                                                                                    Auth::user()->isDev()
                                                                                    ) --}}
                                                                                    @foreach($rev->tasksRevisao as $indexTask =>  $task)
                                                                                        <tr>
                                                                                            <td>
                                                                                                <input 
                                                                                                    @if($job->desativa_tasks  || $indexRev+1 < $job->avaliacao->count()) 
                                                                                                        disabled="true" 
                                                                                                    @endif 
                                                                                                    type="checkbox" class="task-revisao-check" id="task-{{$task->id}}"
                                                                                                    value="{{ encrypt($task->id) }}" name="task_revisao_id[][{{encrypt($task->id)}}]"
                                                                                                    data-url="{{ route('executar.task.revioes.job', [encrypt($job->id), encrypt($task->id)]) }}"
                                                                                                    {{ $task->status != 0 ? 'checked="checked"' : '' }} 
                                                                                                />
                                                                                                <span class="descricao-task-revisao margemL20"> 
                                                                                                    {{$task->task_name}} <i class="info-icon fa fa-info"></i>

                                                                                                    <div class="dadosTask invisivel">                                                
                                                                                                        <p class="paddingL10 paddingR10 margemB0">{{$task->task_description}}</p>
                                                                                                    </div>
                                                                                                </span>

                                                                                            </td>
                                                                                        </tr>
                                                                                    @endforeach
                                                                                {{-- @endif --}}
                                                                            </tbody>
                                                                        </table>
                                                                    @endcan
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                @endforeach
                                            </div>
                                        @endif
                                    </div>

                                </div>{{-- end row --}}

                                <div class="row margemT10">
                                 



                                </div>
                                

                            </div>
                        </div>                       
                    </div>

                    {{-- Tab Informações --}}
                    <div id="info" class="tab-pane fade">
                       
                        @isset($job->campos_personalizados)
                            <div class="row">
                                <div class="col-md-5 col-sm-12">
                                    <div class="box box-solid box-primary no-border">
                                        <div id="collapseOneCampos" class="panel-collapse collapse in" aria-expanded="false" >
                                            <div class="box-body box-profile">
                                                @foreach($job->campos_personalizados as $tipo => $campo)
                                                    <h3>{{ $tipo }}</h3>
                                                    <h4>{{ $campo }}</h4>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <h4>{{ __('messages.Sem informações extras') }}</h4>
                        @endif
                    </div>
                    
                    {{-- Se não for Job Avulso --}}
                    @if(!$job->avulso)

                        {{-- Tab Imagens do Job --}}
                        <div id="imagens" class="tab-pane fade">
                            {{-- <div class="btn-toolbar margemT10" role="toolbar">
                                <a href="#" class="btn btn-warning " title="Adicionar Imagens ao Projeto" data-toggle="tooltip">
                                    <i class="fa fa-image" aria-hidden="true"></i>
                                </a>
                                <a href="#" class="btn cyan " title="Vincular arquivos e imagens" data-toggle="tooltip">
                                    <i class="fa fa-paperclip" aria-hidden="true"></i>
                                </a>
                            </div> --}}
                            <hr>
                            @unless(!$job->imagens)
                                <div id="img-accordion">
                                    @foreach($job->imagens as $img)
                                        <div class="panel panel-default card-sem-borda card-imagem">
                                            <div class="panel-heading cor-personalizada" id="panel-img-{{$img->id}}" style="background: #fff;">
                                                <a class="larguraTotal alturaTotal texto-preto" data-toggle="collapse" data-target="#collapseme{{$img->id}}" aria-expanded="false" aria-controls="#collapseme{{$img->id}}" role="button">
                                                    <div class="row">
                                                        <div class="col-md-2">
                                                            <p class="titulo-tab-imagens">{{ $img->nome }}</p>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <p class="titulo-tab-imagens">{{ $img->tipo->nome . ' - ' . $img->tipo->grupo->nome }}</p>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <p class="titulo-tab-imagens">{{$img->status_revisao ?? ''}}</p>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <p class="titulo-tab-imagens">{{$img->finalizador ? $img->finalizador->name : ''}}</p>
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
                                                                <div class="progress-bar progress-bar-animated progress-bar-striped progress-bar-{{ $class_status }} progresso pull-right" role="progressbar" aria-valuenow="{{ $img->concluido() }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1">
                                                            @can('criar-job')
                                                                <a class="pull-right" href="{{ route('imagem.add.job', encrypt($img->id)) }}" class="" title="{{ __('messages.Novo Job') }}" data-toggle="tooltip">
                                                                  {{ __('messages.Add Job') }}
                                                                </a>
                                                            @endcan
                                                        </div>
                                                    </div>  
                                                </a>
                                            </div>
                                            <!-- // conteudo collapse -->
                                            <div id="collapseme{{$img->id}}" class="collapse" aria-labelledby="panel-img-{{$img->id}}" data-parent="#img-accordion">
                                                <div class="panel-body">
                                                    <p><b>{{ __('messages.Nome') }} :</b> {{ $img->tipo->nome }}</p>
                                                    <p><b>{{ __('messages.Descrição') }}: </b> {{ $img->descricao ?? __('messages.Não Informado')}}</p>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <p><b>{{ __('messages.Finalizador') }}: </b> {{ $img->finalizador ? $img->finalizador->name : __('messages.Não Informado') }}</p>
                                                        </div>
                                                    </div>
                                                    <p><b>{{ __('messages.Status') }} : </b>{{$img->status_revisao ?? 'Sem Revisão adicionada'}}</p>
                                                    <div class="row margemB10">
                                                        <div class="col-md-12">
                                                            <a href="{{ route('imagens.show', encrypt($img->id)) }}" class="btn btn-info">{{ __('messages.Mais detalhes') }}</a>  
                                                        </div>
                                                    </div>
                                                    
                                                   {{--  @if(!count($img->jobs) > 0)
                                                        <h3>Sem jobs para esta imagem | <a href="{{ route('imagem.add.job', encrypt($img->id)) }}">Criar Job</a></h3>
                                                    @else
                                                        <div class="table-responsive">
                                                            <table class="table nome-job">
                                                                <thead class="fundo-escuro">
                                                                    <th class="texto-branco">Nome do Job</th>
                                                                    <th class="texto-branco">Coordenador</th>
                                                                    <th class="texto-branco">Colaborador</th>
                                                                    <th class="texto-branco">Progresso</th>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach($img->jobs as $jb)
                                                                        <tr>
                                                                            <td><a href="{{ route('jobs.show', encrypt($jb->id)) }}">{{ $jb->nome }}</a></td>
                                                                            <td>{{ $jb->coordenador ? $jb->coordenador->name : 'Não Informado'}}</td>
                                                                            <td>{{ $jb->delegado    ? $jb->delegado->name    : 'Não Informado'}}</td>
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
                                                    @endif --}}
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif          
                        </div>
                    @endif      
                    
                    {{-- Tab Arquivos --}}
                    <div id="arquivos" class="tab-pane fade {{ isset($aba) && $aba == 'files' ? 'in active' : '' }}">
                        <div class="row">
                            
                            <div class="col-md-8 col-sm-6">
                                <div class="box box-solid box-primary no-border">
                                    <div id="collapseOneArquivos" class="panel-collapse collapse in" aria-expanded="false" >
                                        <div class="box-body box-profile">
                                            @isset($job->midias)
                                                <div class="col-md-12 col-sm6">
                                                    <table class="table">
                                                        <thead>
                                                            <th colspan="3">{{ __('messages.Lista de Arquivos') }}</th>
                                                        </thead>
                                                        <tbody>
                                                        @foreach($job->midias as $midia)
                                                            <tr>
                                                                @if(pathinfo($midia->caminho, PATHINFO_EXTENSION ) == 'jpg' || pathinfo($midia->caminho, PATHINFO_EXTENSION) == 'png')
                                                                    <td>
                                                                        <a href="{{ URL::to('') . '/storage/' . $midia->caminho }}" target="_blank"><img src="{{ URL::to('') . '/storage/' . $midia->caminho }}" width="auto" height="100" alt=""></a></td>
                                                                @else
                                                                    @php
                                                                        $exts   = ['3ds', 'cad', 'doc', 'dxf', 'max', 'pdf', 'psd', 'txt', 'docx', 'xls', 'zip', 'dwg'];
                                                                        $ext    = pathinfo($midia->caminho, PATHINFO_EXTENSION);
                                                                        $icone  = '/icones/';
                                                                        $icone .= in_array($ext, $exts) ? $ext : 'padrao';
                                                                        $icone .= '.png';
                                                                    @endphp
                                                                    <td>
                                                                        <a href="{{ URL::to('') . '/storage/' . $midia->caminho }}" target="_blank"><img src="{{$icone}}" width="auto" height="50" alt="{{$ext}}"></a></td>
                                                                @endif
                                                                <td>{{ $midia->nome_arquivo }}</td>
                                                                <td>{{ $midia->descricao ?? __('messages.Não informado') }}</td>
                                                                <td class="texto-centralizado">
                                                                    <a href="{{ URL::to('') . '/storage/' . $midia->caminho }}" target="_blank">
                                                                        <i class="fa fa-eye" aria-hidden="true" title="Visualizar"></i>
                                                                    </a>
                                                                    
                                                                </td>
                                                                <td class="texto-centralizado">
                                                                    <a href="{{ URL::to('') . '/storage/' . $midia->caminho }}" download>
                                                                        <i class="fa fa-download" aria-hidden="true" title="Download"></i>
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        @endforeach                                             
                                                        </tbody>
                                                    </table>
                                                </div>

                                                @if(!$job->avulso)
                                                    <div class="col-md-5 col-sm-6">

                                                        <table class="table table-com-margin-top">
                                                            <thead>
                                                                <th colspan="3">{{ __('messages.Lista de Arquivos das Imagens') }}</th>
                                                            </thead>
                                                            <tbody>
                                                        
                                                            @foreach($job->imagens as $img)
                                                                @isset($img->arquivos)
                                                                    @foreach($img->arquivos as $midia)
                                                                        <tr>
                                                                            @if(pathinfo($midia->caminho, PATHINFO_EXTENSION ) == 'jpg' || pathinfo($midia->caminho, PATHINFO_EXTENSION) == 'png')
                                                                                <td><img src="{{ URL::to('') . '/storage/' . $midia->caminho }}" width="28" height="28" alt=""></td>
                                                                            @else
                                                                                @php
                                                                                    $exts   = ['3ds', 'cad', 'doc', 'dxf', 'max', 'pdf', 'psd', 'txt', 'docx', 'xls', 'zip', 'dwg'];
                                                                                    $ext    = pathinfo($midia->caminho, PATHINFO_EXTENSION);
                                                                                    $icone  = '/icones/';
                                                                                    $icone .= in_array($ext, $exts) ? $ext : 'padrao';
                                                                                    $icone .= '.png';
                                                                                @endphp
                                                                                <td><img src="{{$icone}}" width="28" height="28" alt="{{$ext}}"></td>
                                                                            @endif
                                                                            <td>{{ $midia->tipo_arquivo->nome }}</td>
                                                                            {{-- <td>{{ $img->nome }}</td>
                                                                            <td>{{ $midia->nome }}</td> --}}
                                                                            <td class="texto-centralizado">
                                                                                <a href="{{ URL::to('') . '/storage/' . $midia->caminho }}" target="_blank">
                                                                                    <i class="fa fa-eye" aria-hidden="true" title="Visualizar"></i>
                                                                                </a>
                                                                            </td>
                                                                            <td class="texto-centralizado">
                                                                                <a href="{{ URL::to('') . '/storage/' . $midia->caminho }}" download>
                                                                                    <i class="fa fa-download" aria-hidden="true" title="Download"></i>
                                                                                </a>
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                @endif
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if($faz_upload_arquivos_ref)
                                <div class="col-md-4 col-sm6">
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
                                    <form action="{{ route('add.arquivo.job') }}" method="POST" enctype="multipart/form-data">
                                        
                                        @csrf
                                        <input type="hidden" name="job_id" value="{{ \Crypt::encrypt($job->id) }}">

                                        <div class="row div-item-form novo-arquivo-referencia">
                                            {{--Tipo de Arquivo--}}
                                            <div class="col-sm-12 col-md-6">
                                                <p><b>{{ __('messages.Tipo de Arquivo') }}</b></p>
                                                <select id="combo-tipo-arquivo-1" name="tipo_id" class="combo-tipo-arquivo form-control select2 margemT10">
                                                    @isset($tipos_arquivos)
                                                        @unless($tipos_arquivos)
                                                            <option value="">{{ __('messages.Sem Tipos de Arquivos Cadastrados') }}</option>
                                                        @else
                                                            @foreach($tipos_arquivos as $tipo)
                                                                <option value="{{ $tipo->id }}">{{__('messages.' . $tipo->nome) }}</option>
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
                                                            <input id="input-arquivo-1" name="arquivos[]" type="file" accept="*" multiple="multiple"/>
                                                        </div>
                                                        <!-- image-preview-clear button -->
                                                        <button type="button" class="btn btn-default image-preview-clear" >
                                                            <span class="glyphicon glyphicon-remove"></span> {{ __('messages.Limpar') }}
                                                        </button>
                                                    </span>
                                                    {{-- <input id="arquivo-1" type="file" accept="*" name="arquivo" style="position: absolute; top: 0px; right: 1000vw;" />  --}}
                                                </div>
                                            </div>
                                        </div>

                                        {{-- <div id="linha-botao-add" class="row div-item-form">

                                        </div> --}}
                                        
                                        <div id="linha-botao-add" class="row div-item-form">
                                            <div class="col-md-12">
                                                <button type="submit" class="btn btn-primary btn-add margemT40 pull-right">{{ __('messages.Adicionar Arquivos') }}</button>
                                                {{-- <a id="add-arquivo-referencia" type="button" class="btn btn-primary btn-add margemT40 pull-right">{{ __('messages.Adicionar Arquivo') }}</a> --}}
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            @endif

                        </div>
                    </div>


                    @foreach($job->revisoes as $index => $rev)
                        @if($index<@count($job->revisoes))
                            {{-- Tab Imagens do Job --}}
                            <div id="R0{{$index+1}}" class="tab-pane fade">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12">
                                        <div class="box box-solid box-primary no-border">
                                            <div id="collapseOneArquivos" class="panel-collapse collapse in" aria-expanded="false" >
                                                <div class="box-body box-profile texto-centralizado">
                                                    @if($rev->imagem_revisao)
                                                        <img src="{{ URL::asset('storage/'.$rev->imagem_revisao)}}">
                                                    @endif
                                                    @if($rev->data_entrega)
                                                        <br>
                                                        <strong>{{ __('messages.Data de Entrega') }}: </strong> {{  Carbon\Carbon::parse($rev->data_entrega)->format('d/m/Y')  }}
                                                    @endif
                                                </div>

                                                <div class="texto-centralizado">
                                                    <hr>
                                                    @foreach($rev->marcadores  as  $indexMid => $marcRev)
                                                        <div class="col-md-3">
                                                            <span><img id="{{ 'imagem-pin'.$marcRev->ordem}}"  src="{{asset('images/pins/azul/'. ($indexMid+1). '.png')}}" ></span><br>
                                                            <span>{{ $marcRev->texto }}</span><br>
                                                            <input type="hidden" name="xPin" id="xPin{{ $marcRev->id }}" value="{{ $marcRev->x }}">
                                                            <input type="hidden" name="yPin" id="yPin{{ $marcRev->id }}" value="{{ $marcRev->y }}">
                                    
                                                            @if(!$rev->tira_arquivos)
                                                            @foreach($marcRev->midias as $mid)
                                                                <img src="{{ URL::asset('storage/'. $mid->src )}}" class="img-detalhe-min">
                                                                @if ($mid->caminho_arquivo)
                                                                <p>
                                                                    {{ __('messages.Caminho arquivo') }}: {{ $mid->caminho_arquivo }}
                                                                </p>
                                                                @endif
                                                            @endforeach
                                                            @endif
                                                        </div>    
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                 
                    {{-- @if ($job->pode_comentar)  --}}
                        <div id="comments-content" class="tab-pane fade">
                            {{-- Comentários do Job --}}
                            <div class="row">
                                {{-- TODO: arrumar, usado devido a js com echo php... --}}
                                @php $indexKey = ''; @endphp
                                    {{-- <input type="hidden" value="{{ @csrf }}" id="token_base_destroy_coment"> --}}
                                    <input type="hidden" value="{{ str_replace('x','',route('comments.destroy', 'x')) }}" id="rota_base_destroy_coment">
                                    @php
                                        $marcArroba = '';
                                        foreach ($membros as $key => $mem): 
                                            $marcArroba = $marcArroba.$mem->marcador.',';
                                        endforeach 

                                    @endphp
                                    <div class="col-md-12 margemT40">
                                        <textarea  id="marcadores" style="visibility: hidden;width:1px;">{{$marcArroba}}</textarea>
                                        
                                        {{-- Novo Comentário --}}
                                        <div class="col-md-6">
                                            <h3 class="margem-bottom-menor margemT5 margemB40" >{{ __('messages.Escreva um comentário')}} </h3>
                                            @if(!$job->avulso && !$job->freela)
                                                @php
                                                    $coord_marc = 
                                                        $job->coordenador && $job->coordenador->marcador 
                                                        ? $job->coordenador->marcador
                                                        // : explode('@',$job->coordenador->email);
                                                        : false;

                                                    $dele_marc = 
                                                        $job->delegado && $job->delegado->marcador
                                                        ? $job->delegado->marcador
                                                        : false;
                                                @endphp
                                                @if($coord_marc)
                                                    <h4>{{ __('messages.Para marcar o coordenador em um comentário use')}} <span id="marcacao_coord" class="negrito">{{$coord_marc}}</span></h4>
                                                @endif

                                                @if($dele_marc)
                                                    <h4>{{ __('messages.Para marcar o delegado em um comentário use')}} <span id="marcacao_delegado" class="negrito">{{$dele_marc}}</span id="" class="negrito"></h4>
                                                @endif
                                            @endif 
                                            <!-- form inserir comentário --> 
                                            <div class = "form-group" id="div-comentario" > 
                                                <textarea  name = "descricao" id = "descricao_comentario" class = "form-control comment texto-preto" rows="10"></textarea>
                                                <input type = "hidden" name = "formatacao" id = "formatacao" value = "{{corUsuario($usuario_ativo->roles()->first()->name)}}" class="comment" />   
                                                <input type = "hidden" name = "commentable_id" id = "commentable_id" value = "{{$job->id}}" class="comment" />   
                                                <input type = "hidden" name = "type" id = "type" value = "job" class=" comment" />   
                                                <input type = "hidden" name = "parent_id" id="parent_id" value = "{{$job->comment ? $job->comment->id : null }}" class="comment" />
                                                <input type = "hidden" name = "url_comentario" id = "url_comentario" value = "{{route ('comments.store')}}" class=" comment" />
                                                <input type = "hidden" name = "usuario" value = "{{Auth::user()->name}}"  class="comment" />

                                                <input type = "hidden" name = "count_comment" id = "count_comment" value = "{{$job->comments()->get()->count()}}" class=" comment" />
                                            </div>
                                            <div class = "form-group" > 
                                                <input type = "submit" class="btn btn btn-warning inserir-comentario" value="{{ __('messages.Comentar')}}" data-comment="comment"  />    
                                            </div>
                                        </div>

                                        {{-- lista comentários --}}
                                        
                                        <div class="col-md-4 col-md-offset-1" id="lista-comentarios"> 
                                            <h3 class="margem-bottom-menor margemT5 margemB40" >{{ __('messages.Comentários desse Job')}} </h3>
                                            @if($job->comments())
                                                
                                                @foreach ($job->comments as $indexKey => $com)
                                                    <div class="row display-comment div-comment{{$indexKey}} {{corUsuario($com->name_role)}} paddingT10 paddingB20 paddingR10 margemB10">
                                                        <div id="div-comment{{$indexKey}}" class="col-md-12">
                                                            {{-- Comentário Principal --}}
                                                            <div class="largura90 paddingB20">
                                                                <h4 class="negrito semMargem">
                                                                {{ __('messages.Por') . ': ' . $com->user->name}}
                                                                </h4>
                                                                <h4>
                                                                @php
                                                                    $stringCorrigir = $com->descricao;
                                                                    foreach ($membros as $key => $mem): 

                                                                        $novoCampo = '<strong>'.$mem->marcador.'</strong>' ;
                                                                        $stringCorrigir = str_replace($mem->marcador, $novoCampo , $stringCorrigir);

                                                                    endforeach 
                                                                @endphp
                                                                {!! $stringCorrigir !!}

                                                                </h4>
                                                            </div>

                                                            @if($com->respostas)
                                                                @php $criarCampo = false; @endphp
                                                                @foreach($com->respostas as $indexKeyRes => $comRes)
                                                                    <div class="larguraTotal display-comment margemB10 padding10 {{corUsuario($comRes->name_role)}}"  id="div-respostas-comment{{$indexKey}}"> 
                                                                        <h4 class="negrito semMargem">     
                                                                        {{ ('messages.Por')}}: {{$comRes->user->name}} 
                                                                        </h4>
                                                                        <h5 class="margemB5">     
                                                                            @php
                                                                                $stringCorrigir = $comRes->descricao;
                                                                                foreach ($membros as $key => $mem): 

                                                                                    $novoCampo = '<strong>'.$mem->marcador.'</strong>' ;
                                                                                    $stringCorrigir = str_replace($mem->marcador, $novoCampo , $stringCorrigir);

                                                                                endforeach 
                                                                            @endphp
                                                                            {!! $stringCorrigir !!}
                                                                        </h5>
                                                                    </div>
                                                                @endforeach

                                                            @endif 
                                                            @php $idLast = false; @endphp

                                                        </div>
                                                        <div class="col-md-12 reply margemT20">
                                                            

                                                            <form action="{{route ('comments.destroy',encrypt($com->id))}}" class="form-delete" id="form-deletar-comment-{{ $com->id }}" name="form-deletar-comment-{{ $com->id }}" method="POST" enctype="multipart/form-data">
                                                                @method('DELETE')
                                                                @csrf
                                                                <a href="#"  id ="excluirComment" class="comment{{$indexKey}}  deletar-item margemL5" 
                                                                > 
                                                                    {{ __('messages.Excluir')}} 
                                                                </a>
                                                                {{-- title="Excluir Comentário e Respostas" data-toggle="tooltip" --}}
                                                            </form> 
                                                            <span class="margemL5 margemR5 divisor"> | </span>  
                                                            <span class="responder mouse-pointer" id ="reply"> {{ __('messages.Responder')}} </span>
                                                             - 
                                                            <span class="responder">
                                                                {{ __('messages.Comentado em')}}: {{ $com->created_at->format('d-m-Y h:m:s') }}
                                                            </span>  
                                                            {{-- title="Responder Comentário" data-toggle="tooltip" --}}
                                                            <div class = "form-group" > 
                                                                <textarea name = "descricao" class = "form-control comment{{$indexKey}} texto-preto" id = "descricao_comentario"></textarea>
                                                                <!-- <input type = "text" name = "descricao" class = "form-control comment{{$indexKey}}" id = "descricao_comentario"/> -->    
                                                                <input type = "hidden" name = "commentable_id" value = "{{$job->id}}" class="comment{{$indexKey}}" />
                                                                <input type = "hidden" name = "parent_id" value = "{{$com ? $com->id : null}}" class="comment{{$indexKey}}" /> 
                                                                <input type = "hidden" name = "type" value = "job"  class="comment{{$indexKey}}" />
                                                                <input type = "hidden" name = "url_comentario" id = "url_comentario" value = "{{route ('comments.store')}}" class=" comment{{$indexKey}}" />
                                                                <input type = "hidden" name = "usuario" value = "{{Auth::user()->name}}"  class="comment{{$indexKey}}" />

                                                                <input type = "submit" class="btn btn btn-warning margemT10 inserir-comentario" data-comment = "comment{{$indexKey}}"  value = "{{ __('messages.Enviar')}}" /> 
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        
                                                    </div>
                                                    @endforeach
                                                @endif
                                        </div>

                                    </div>
                            </div>
                        </div>
                    {{-- @endif --}}

                    @can('aceita-proposta-job')  
                        <div id="proposals-content" class="tab-pane fade">
                            {{-- Propostas do Job --}}
                            <h3> {{__('messages.Data Limite para')}} {{ $job->status == $status_array['emproposta'] ? __('messages.Propostas') : __('messages.Candidaturas') }}:  {{ $job->data_limite ? $job->data_limite->format('d/m/Y') : __('messages.Não Informado') }} </h3>
                            @if(($job->verificaStatus('emproposta') || $job->verificaStatus('emcandidatura')) && $job->data_limite <= date('Y-m-d'))
                                <form action="{{ route('job.prorrogar.data.proposta', [encrypt($job->id)]) }}" class="form-prorrogar-poposta" id="form-prorrogar-proposta" 
                                    name="form-prorrogar-proposta-{{ $job->id }}" method="POST" enctype="multipart/form-data" 
                                    >
                                    {{-- <input type="hidden" id="data-atual-entrega" value="{{ $job->data_prox_revisao ? $job->data_prox_revisao->format('d/m/Y') : date('d/m/Y') }}"> --}}
                                    <input type="hidden" id="data-atual-proposta" value="{{ $job->data_limite ? $job->data_limite : date('Y-m-d') }}">
                                    @csrf
                                    <button class="btn btn-info prorrogar-prazo-proposta-item margemL5" title="{{ __('messages.Prorrogar periodo para Propostas') }}" data-toggle="tooltip" type="submit">
                                        <i class="fa fa-calendar" aria-hidden="true"></i>
                                    </button>
                                </form>
                            @endif

                            <div class="row">
                                {{-- TODO: arrumar, usado devido a js com echo php... --}}
                                <div class="col-md-12 margemT40">
                                    <h3>{{ $job->status == $status_array['emproposta'] ? __('messages.Propostas') : __('messages.Candidaturas') }}</h3>

                                    @include('candidaturas.tabela-por-job', ['candidaturas'=> $job->candidaturas, 'job' => $job])
                                </div>
                            </div>
                        </div>
                    @endcan

                </div>
                {{-- end tab-content --}}
            </div>
        @endempty
    </div>

    {{-- Revisar com modelo adminlte de chat --}}
    @php
        function corUsuario($name) {
            $cor_fundo = "fundo-cinza";
            $cor_texto = "texto-preto";
            
            $role_name = $name;

            if($role_name=="freelancer")
            {
                $cor_fundo = "fundo-verde-escuro";
                $cor_texto = "texto-branco";
            }
            else if($role_name=="publicador")
            {
                $cor_fundo = "fundo-azul";
                $cor_texto = "texto-branco";
            }

            return $cor_fundo.  " " . $cor_texto;
        }
    @endphp
                                            
    @include('app.includes.carregando')
    <input type="hidden" id="text-n-info" value={{ __('messages.Não Informado') }}>
    <input type="hidden" id="text-nv-rev" value={{ __('messages.Nova Revisão') }}>
@stop

@push('js')
    <script src="{{ asset('js/arquivos.js') }}"></script>
    <script src="{{ asset('js/revisao.js') }}"></script>
    @if($job->faz_avaliacao || $job->mostra_avaliacao)
        <script src="{{ asset('js/star-rating.js') }}"></script>
    @endif
    <script>

        // Mudar cor da tab principal do accordion ao abrir
        $(document).ready(function(){
            
            $(".collapse").on("hide.bs.collapse", function(){
                $(this).css('background-color', '#f1f1f1');
                $(this.parentElement.children[0]).css('background-color', '#fff');
                $('#' + this.parentElement.children[0].id + ' a').addClass('texto-preto');             
                $('#' + this.parentElement.children[0].id + ' a').removeClass('texto-branco');             
                $('#' + this.parentElement.children[0].id + ' a p').removeClass('texto-branco');
            });
            $(".collapse").on("show.bs.collapse", function(){
                $(this).css('background-color', '#f1f1f1');
                $(this.parentElement.children[0]).css('background-color', '#5c9ba5');
                $('#' + this.parentElement.children[0].id + ' a').removeClass('texto-preto');
                $('#' + this.parentElement.children[0].id + ' a').addClass('texto-branco');
                $('#' + this.parentElement.children[0].id + ' a p').addClass('texto-branco');
            });

            // Verificar se o input já esta checado ao abrir a lista de tasks,
            // se já estiver, criar o traço
            $('.task-check').each(function() {
                this.checked ? $('#nome-' + this.id).css({
                    'text-decoration': 'line-through', 'color': '#cdcdcd', 'font-style': 'italic'
                }) : '';
            });

             $('.task-revisao-check').each(function() {
                this.checked ? $('#nome-' + this.id).css({
                    'text-decoration': 'line-through', 'color': '#cdcdcd', 'font-style': 'italic'
                }) : '';
            });


             // Criar traço em cima da task qdo for o input for checado
             $('.task-revisao-check').on('ifChanged', function(e) {
                e.preventDefault();
                this.checked ? 
                executarTask(this) : 
                desfazerTask(this);
            });
            
            // Criar traço em cima da task qdo for o input for checado
            $('.task-check').on('ifChanged', function(e) {
                e.preventDefault();
                this.checked ? 
                executarTask(this) : 
                desfazerTask(this);
            });

            var valor_progresso_revisao = document.getElementById("valor-progresso-revisao").value;
            if(valor_progresso_revisao >= 100 && document.getElementById("cria_upload") && document.getElementById("cria_upload").value == '1'){
                renderFileAvaliacao(true);
            }

            function executarTaskAjax(ele) {
                // console.log(ele);
                // console.log('fazer task');
                var url   = jQuery(ele).data('url');
                var content = "{{ __('messages.Confirma que a task está concluída') }} ?";
                if(url){
                    $.confirm({
                        icon: 'fa',
                        title: 'Executar Task',
                        content: content,
                        backgroundDismiss: true,
                        closeIcon: true,
                        type: 'orange',
                        boxWidth: '30%',
                        useBootstrap: false,
                        cancelButton: 'Não',
                        buttons: {
                            confirmar: function(){
                                $.ajax({
                                    type: 'GET',
                                    url: url,
                                    success: function(data) {
                                        // console.log('Sucesso: ' + data);
                                        sucesso_info('Task concluída', "{{ __('messages.Task concluída com sucesso') }} .");
                                        jQuery(ele).iCheck('update');
                                        jQuery(ele).iCheck('check');
                                        carregarProgresso();
                                    },
                                    error: function(data) {
                                        // console.log('Erro: ', data);
                                        erro_info('Oopss', "{{ __('messages.Task não concluída') }} .");
                                        jQuery(ele).iCheck('update');
                                        jQuery(ele).iCheck('uncheck');
                                        // return false;
                                    }
                                });
                            },
                            cancelar: function(){
                                jQuery(ele).iCheck('update');
                                jQuery(ele).iCheck('uncheck');
                            }
                        }
                    });
                }
            }
            function desfazerTaskAjax(ele) {
                // console.log('Desfazer Task');
                // console.log(ele);
                var url = jQuery(ele).data('url').replace('executar', 'desfazer');
                var content = "{{ __('messages.Confirma desfazer a task') }} ?";
                if (url) {
                    $.confirm({
                        icon: 'fa',
                        title: 'Desfazer Task',
                        content: content,
                        backgroundDismiss: true,
                        closeIcon: true,
                        type: 'orange',
                        boxWidth: '30%',
                        useBootstrap: false,
                        cancelButton: 'Não',
                        buttons: {
                            confirmar: function() {
                                $.ajax({
                                    type: 'GET',
                                    url: url,
                                    success: function(data) {
                                        // console.log('Sucesso: ' + data);
                                        sucesso_info('Task desfeita', "{{ __('messages.Task desfeita com sucesso') }} .");
                                        jQuery(ele).iCheck('update');
                                        jQuery(ele).iCheck('uncheck');
                                        carregarProgresso();
                                    },
                                    error: function(data) {
                                        // console.log('Erro: ', data);
                                        erro_info('Oopss', "{{ __('messages.Task não desfeita') }} .");
                                        jQuery(ele).iCheck('update');
                                        jQuery(ele).iCheck('check');
                                    }
                                });
                            },
                            cancelar: function() {
                                jQuery(ele).iCheck('update');
                                jQuery(ele).iCheck('check');
                            }
                        }
                    });
                }
            }

            function carregarProgresso(){

                // Progresso Job
                var url = jQuery('#rota_progresso').val();
                $.ajax({
                    type: 'GET',
                    url: url,
                    success: function (data) {
                        // console.log(data);

                        var grafico = jQuery('#progresso-job');
                        grafico.css('width', data.progresso + '%')
                            .attr('aria-valuenow', data.progresso)
                            .html(data.progresso + '%');
                        // Atualizar grafico Canvas
                        // var grafico = jQuery('#imagem-chart');
                        // grafico.data('valor', data.progresso);
                        // atualiza_grafico(grafico[0]);
                        
                        var habilitaBotao = document.getElementById("form-concluir-tipo-img");
                        var habilitaBotaoRecusar =  document.getElementById("form-recusado-tipo-img");
                        var habilitaBotaoParar =  document.getElementById("form-parado-tipo-img");
                        var arquivoavaliacao =  document.getElementById("arquivo-avaliacao");
                        
                        if(habilitaBotao) {    
                            if(data.progresso>=100) {
                                habilitaBotao.disable=false;
                                habilitaBotao.style.visibility = "visible";

                                habilitaBotaoRecusar.disable=false;
                                habilitaBotaoRecusar.style.visibility = "visible";

                                habilitaBotaoParar.disable=false;
                                habilitaBotaoParar.style.visibility = "visible";
                            }
                            else
                            {
                                habilitaBotao.disable=true;
                                habilitaBotao.style.visibility = "hidden";

                                habilitaBotaoRecusar.disable=true;
                                habilitaBotaoRecusar.style.visibility = "hidden";
                                
                                habilitaBotaoParar.disable=true;
                                habilitaBotaoParar.style.visibility = "visible";
                            }
                        }

                        
                        if(data.progresso>=100) {
                            //document.location.reload();
                            finalizaProgresso();
                            if(arquivoavaliacao) {
                                arquivoavaliacao.disable=false;
                                arquivoavaliacao.style.visibility = "visible";
                            }
                        }
                        else
                        {
                            if(arquivoavaliacao) {
                                arquivoavaliacao.disable=true;
                                arquivoavaliacao.style.visibility = "hidden";
                            }
                            renderFileAvaliacao(false);
                        }
                    },
                    error: function(data) {
                        return false;
                    }
                });

                // Progresso imagens
                jQuery('.progresso-img').each(function(index, value){
                    // console.log('URL progresso imagem: ');
                    // console.log(value.attributes['data-url-progresso'].value);
                    var url_progresso_img = value.attributes['data-url-progresso'].value;
                    $.ajax({
                        type: 'GET',
                        url: url_progresso_img,
                        success: function (result) {
                            // console.log(result);

                            var grafico = jQuery('#' + value.id);
                            // grafico.data('valor', result.progresso);
                            grafico.css('width', result.progresso + '%')
                            .attr('aria-valuenow', result.progresso)
                            .html(result.progresso + '%');
                            // escrever método para atualizar gráfico de barra
                            // atualiza_grafico(grafico[0]);
                        },
                        error: function(data) {
                            return false;
                        }
                    });
                });

                 // Progresso Revisao Job
                var url = jQuery('#rota_progresso_revisao').val();
                $.ajax({
                    type: 'GET',
                    url: url,
                    success: function (data) {
                        // console.log(data);

                        var grafico = jQuery('#progresso-revisao');
                        grafico.css('width', data.progresso + '%')
                            .attr('aria-valuenow', data.progresso)
                            .html(data.progresso + '%');
                        // Atualizar grafico Canvas
                        // var grafico = jQuery('#imagem-chart');
                        // grafico.data('valor', data.progresso);
                        // atualiza_grafico(grafico[0]);
                        
                        var habilitaBotao = document.getElementById("form-concluir-tipo-img");
                        var habilitaBotaoRecusar =  document.getElementById("form-recusado-tipo-img");
                        var habilitaBotaoParar =  document.getElementById("form-parado-tipo-img");
                        var arquivoavaliacao =  document.getElementById("arquivo-avaliacao");
                        if(habilitaBotao) {    
                            if(data.progresso>=100) {
                                habilitaBotao.disable=false;
                                habilitaBotao.style.visibility = "visible";

                                habilitaBotaoRecusar.disable=false;
                                habilitaBotaoRecusar.style.visibility = "visible";

                                habilitaBotaoParar.disable=false;
                                habilitaBotaoParar.style.visibility = "visible";
                            }else{
                                habilitaBotao.disable=true;
                                habilitaBotao.style.visibility = "hidden";

                                habilitaBotaoRecusar.disable=true;
                                habilitaBotaoRecusar.style.visibility = "hidden";
                                
                                habilitaBotaoParar.disable=true;
                                habilitaBotaoParar.style.visibility = "visible";
                            }
                        }

                        
                        if(data.progresso>=100) {
                            //document.location.reload();
                            //finalizaProgresso();
                            // if(arquivoavaliacao) {
                            //     arquivoavaliacao.disable=false;
                            //     arquivoavaliacao.style.visibility = "visible";
                            // }
                        }else{
                            // if(arquivoavaliacao) {
                            //     arquivoavaliacao.disable=true;
                            //     arquivoavaliacao.style.visibility = "hidden";
                            // }
                        }
                    },
                    error: function(data) {
                        return false;
                    }
                });

            }

            // Sem confirm
            function executarTask(ele) {
                var url   = jQuery(ele).data('url');
                // console.log(url);
                $.ajax({
                    type: 'GET',
                    url: url,
                    dataType: 'text json',
                    success: function(data) {
                        // console.log('Sucesso: ' + data);
                        //sucesso_info('Task concluída', 'Task concluída com sucesso!');
                        jQuery(ele).iCheck('update');
                        jQuery(ele).iCheck('check');
                        $('#nome-' + ele.id).css({
                            'text-decoration': 'line-through', 'color': '#cdcdcd', 'font-style': 'italic'
                        });
                        carregarProgresso();
                    },
                    error: function(data) {
                        // console.log('Erro: ', data);
                        erro_info('Oopss', "{{ __('messages.Task não concluída') }} !");
                        jQuery(ele).iCheck('update');
                        jQuery(ele).iCheck('uncheck');
                    }
                });
                
            }

            function desfazerTask(ele) {
                var url = jQuery(ele).data('url').replace('executar', 'desfazer');
                $.ajax({
                    type: 'GET',
                    url: url,
                    success: function(data) {
                        // console.log('Sucesso: ' + data);
                //        sucesso_info('Task desfeita', 'Task desfeita com sucesso!');
                        jQuery(ele).iCheck('update');
                        jQuery(ele).iCheck('uncheck');
                        $('#nome-' + ele.id).css({
                            'text-decoration': 'none', 'color': '#000', 'font-style': 'normal'
                        });
                        carregarProgresso();
                    },
                    error: function(data) {
                        // console.log('Erro: ', data);
                        erro_info('Oopss', "{{ __('messages.Task não desfeita') }} !");
                        jQuery(ele).iCheck('update');
                        jQuery(ele).iCheck('check');
                    }
                });
            }


            // Sem confirm
            function inserirComentario(collection, data_coment) {

                // console.log(collection);
                var url ="";
                var descricaoOk = true;
                var formData = new FormData();

                var usuario   = "";
                var descricao = "";
                var classFormatacao = "";
                var id_coment = '';

                collection.each(function(index,item){
                    // ...
                    if(item.name =="url_comentario"){
                       url = item.value;
                    }else if(item.name == "descricao" && item.value == ""){
                        descricaoOk = false;
                    }else if(item.name == "usuario"){
                        usuario = item.value;
                    }else if(item.name == "formatacao"){
                        classFormatacao = item.value;  
                    }else {

                        formData.append(item.name, item.value);
                        if(item.name == "descricao"){
                            descricao = item.value;
                            item.value = "";
                        }
                    }

                });

                commentBusca = descricao;

                listaMarcadores = document.getElementById("marcadores").value;
                lm = listaMarcadores.split(",");
                listaMarcs = "";
                for(var i = 0; i < lm.length; i++)
                {
                    if(lm[i] != "") {
                        encontrouMarcador = descricao.indexOf(lm[i]);
                        if(encontrouMarcador >= 0)
                        {
                            listaMarcs += lm[i] + ","
                        }
                    }
                }

                formData.append("marcados", listaMarcs);

                // console.log(formData);
                if(descricaoOk) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': '{{ @csrf_token() }}'
                        }
                    });

                    $.ajax({
                        url: url,
                        data: formData,
                        type: 'post',
                        dataType: 'json',
                        contentType: false,
                        processData: false,
                        beforeSend: function(xhr){
                            // processando();
                        },
                        success: function(data) {
                            // console.info(data);
                            id_coment = data['id_comment'];

                            adicionaComentario(usuario, descricao, data_coment, id_coment, listaMarcs, classFormatacao);
                            // sucesso_info('Sucesso!', 'Comentário incluído.')
                        },
                        error: function(data) {
                            erro_info('Oopss..', "{{ __('messages.Problemas ao adicionar o comentário. Recarregue a página e tente novamente') }} .");
                             console.info(data);
                        },
                        always: function(data){
                            // fimProcessando();
                        }
                    });
                }else{
                    erro_info("Oops..", "{{ __('messages.Descricão do comentário tem que ser preenchida') }} !");
                }
            }   

            function adicionaComentario(usuario, comentario, data_coment, id_coment, listaMarcados, classFormatacao) {

                jQuery('.novo').removeClass('novo');

                var rota         = document.getElementById("rota_base_destroy_coment").value;
                var tkn          = jQuery('[name="_token"]')[0].value;


                for(var i = 0; i < lm.length; i++)
                {
                    if(lm[i] != "") {
                        comentario = comentario.replace(lm[i], "<strong>" + lm[i] + "</strong>");
                    }
                }


                if(data_coment=="comment"){
                    var next_comment = document.getElementById("count_comment").value;

                    data_coment = "comment"+id_coment;

                    var novo_comment = 
                        "<div class='row display-comment " + classFormatacao +" paddingT10 paddingB20 paddingR10 margemB10'><div id='div-comment" + next_comment + "' class='col-md-12'>";
                    novo_comment += 
                        "<div class='largura90 paddingB20'><h4 class='negrito semMargem'>{{__('messages.Por')}}: " + usuario + "</h4><h4> " +  comentario + "</h4></div>";
                    novo_comment += "<hr class='margemB5'><div class='largura90 reply'><form action='" + rota + id_coment + "' class='form-delete' id='form-deletar-comment-" + id_coment + "' name='form-deletar-comment-"+id_coment+"' method='POST' enctype='multipart/form-data'>";
                    novo_comment += "<input type='hidden' name='_method' value='DELETE'>";
                    novo_comment += "<input type='hidden' name='_token' value='" + tkn + "'>";
                    novo_comment += "<a href='javascript:void(0)' onclick='deletar_item(this);'  id='excluirComment" + id_coment + "' class='comment"+ next_comment+"  deletar-item margemL5' title='' data-toggle='tooltip' data-original-title='Desfazer Comentário'>{{ __('messages.Desfazer Comentário')}}</a>";
                    novo_comment +="</form></div>";
                    novo_comment +="</div></div>"; 

                    if(jQuery('#lista-comentarios .display-comment')[0] === undefined){
                        document.getElementById("lista-comentarios").innerHTML  += novo_comment;
                    }else{
                        jQuery(novo_comment).insertBefore('.div-comment0')[0];
                    }
                    

                }else{
                    document.getElementById("div-" + data_coment).innerHTML  += "<div class='larguraTotal display-comment margemB10 padding10 user novo' id='div-respostas-" + data_coment + "'><h4 class='negrito semMargem'>Por: " + usuario +"</h4><h5 class='margemB5'> " + comentario +"</h5><hr class='margemB5'><div class='larguraTotal reply'><form action='" + rota + id_coment + "' class='form-delete' id='form-deletar-comment-" + id_coment + "' name='form-deletar-comment-"+id_coment+"' method='POST' enctype='multipart/form-data'><input type='hidden' name='_method' value='DELETE'><input type='hidden' name='_token' value='" + tkn + "'><a href='javascript:void(0)'  id ='excluirComment-" + id_coment + "' class='"+ data_coment + " deletar-item margemL5' title='' data-toggle='tooltip' data-original-title='{{__('messages.Excluir Resposta')}}'>{{__('messages.Desfazer Resposta')}}</a></form></div></div>";
                }
                var btn_excluir = 'excluirComment' + id_coment;
                // jQuery('#' + btn_excluir).click(function(event) { event.preventDefault(); deletar_item(this); });
            }

            function marcaEnvolvidos(texto){
                // console.log(texto);
            }

            $(".inserir-comentario").click(function(e){
                var data_coment = this.getAttribute('data-comment');
                var collection =  $("."+data_coment);
                inserirComentario(collection, data_coment);
            });

            $('.reply > div').hide();

            $('.reply span.responder').click(function() {
                $(this).next().toggle('slow, 1000');
                $(this).next().find("textarea").focus();
            });
            
            $('#descricao_comentario').keyup(function(event) {
                /* Act on the event */
                marcaEnvolvidos(this.value);
            });

            // Dados dos Avatares
            $('.descricao-task-revisao').hover(function(){
                jQuery(this).find('.dadosTask').toggleClass('invisivel');
                // if(!$(this).children().hasClass('invisivel')) {
                //     $(this).children().addClass("invisivel");
                // }
                // else {
                //     $(this).children().removeClass(".invisivel");   
                // }
            });

            $('.nav-link-revisao').click(index,function () {
                //alert("teste" + $(this).data('tab'));
                $('#'+$(this).data('tab')).click();  

            });

            // $(function () {
            //     $('[data-toggle="tooltip"]').tooltip({ trigger: 'click'});
                
            // });

        });


        function finalizaProgresso() {
            sucesso_info("{{ __('messages.Parabéns')}}", "{{ __('messages.Você concluiu esta rodada! Envie seu Job agora para podermos avaliar')}}" );
            //document.location.reload();
            renderFileAvaliacao(true);

        } 

        function renderFileAvaliacao(incluir)
        {            
            if(document.getElementById("div-upload-avaliacao")) {
                if(incluir) {
                    document.getElementById("div-upload-avaliacao").style.display = "block";
                    // var dadosFile = '<div id="file-include-avalicao" class="input-group image-preview margemT20 largura80">' + 
                    //                     '<input id="arquivo" type="text" class="form-control image-preview-filename" disabled="disabled" placeholder="Nenhuma imagem selecionada.">' + 
                    //                     '<span class="input-group-btn">' + 
                    //                         '<button type="button" class="btn btn-default image-preview-clear" style="display:none;">' + 
                    //                         '<span class="glyphicon glyphicon-remove"></span> Limpar' + 
                    //                         '</button>' + 
                    //                         '<div class="btn btn-default image-preview-input">' + 
                    //                             '<span class="glyphicon glyphicon-folder-open"></span>' + 
                    //                             '<span class="image-preview-input-title">Procurar</span>' + 
                    //                             '<input id="input-arquivo" type="file" accept="*" name="imagem_avalicao" />' + 
                    //                         '</div>' + 
                    //                     '</span>' + 
                    //                 '</div>' + 
                    //                 '<div id="file-include-avalicao-botao" class="largura20 margemT20">' + 
                    //                     '<button type="submit" class="btn btn-success image-preview-submit pull-right"  >' + 
                    //                         '<span class="glyphicon glyphicon-ok"></span> Enviar' + 
                    //                     '</button>' + 
                    //                 '</div>';
                    // document.getElementById("form-job-avaliacao").innerHTML  = dadosFile;

                    transformaFilePreview();

                }else {

                    document.getElementById("div-upload-avaliacao").style.display = "none";
                }
            }
        }

       
        function copyStringToClipboard(nome_campo=null)
        {
            var el = null;
            var titulo_msg = null;
            var texto_msg =  null;
            if(nome_campo==null)
            {
                el = document.getElementById("link-hr-interno");
                titulo_msg =  document.getElementById("titulo-copy-hr");
                texto_msg =  document.getElementById("texto-copy-hr");
            }
            else
            {
                titulo_msg =  document.getElementById("titulo-copy-avaliacao");
                texto_msg =  document.getElementById("texto-copy-avaliacao");
                el = document.getElementById(nome_campo);
            }
            //var el = document.getElementById("link-hr-interno");

            /* Select the text field */
            el.select();
            el.setSelectionRange(0, 99999); /*For mobile devices*/
            // Select text inside element
            el.select();

            document.execCommand("copy");

            
            /* Alert the copied text */
             sucesso_info(titulo_msg.value, texto_msg.value, "1500");
             


        }
    </script>
@endpush