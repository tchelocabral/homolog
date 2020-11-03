<div class="nav-tabs-custom nav-dashboard margemT50">
    <h1 class="margemT5 margemB5 titulo-principal texto-esquerda titulo-lista-dash">  
        <a class="larguraTotal alturaTotal texto-preto" data-toggle="collapse" data-target="#collapsejobanda" aria-expanded="false" aria-controls="#collapsejobanda" role="button"><span class="accordion-marc"><i class="fa fa-angle-right"></i></span> Jobs em Andamento</a>
    </h1>
    <ul class="nav nav-tabs displayFlex flexEnd paddingR20" id="tabs-projeto" role="tablist">
        <li class="active in">
            <a data-toggle="tooltip" href="#listaJobsExecutando" aria-expanded="true" class="nav-link fundo-laranja" id="imagens-tab" aria-selected="false" title="Listas"> <i class="fa fa-list" aria-hidden="true"></i></a>
        </li>
        <li >
            <a data-toggle="tooltip" href="#cardJobsExecutando" aria-expanded="true" class="nav-link fundo-laranja" id="imagens-tab" aria-selected="false" title="Cards"><i class="fa fa-credit-card" aria-hidden="true"></i></a>
        </li>
        <li>
            <a data-toggle="tooltip" href="#detalhesJobsExecutando" aria-expanded="true" class="nav-link fundo-laranja" id="imagens-tab" title="Detalhes" aria-selected="false"><i class="fa fa-info" aria-hidden="true"></i></a>
        </li>
    </ul>
    <div id="collapsejobanda" class="tab-content collapse" aria-labelledby="panel-proand" data-parent="#img-accordion">
        {{-- Tab #detalhes--}}
        <div id="detalhesJobsExecutando" class="tab-pane fade">
            <div class="btn-toolbar margemT10 tab-pane fade in" role="toolbar">
                    <table id="lista-dashboard-job-and" class="table larguraTotal com-filtro-lista table-striped">
                    <thead class="">
                        <tr class="th-orange">
                            <th class="col-md-1 box-title texto-branco padding12 com-border-left largura10 ">#</th>
                            <th class="box-title texto-branco padding12">Nome do Job</th>
                            <th class="box-title texto-branco padding12 largura15">Colaborador</th>
                            <!--     <th class="box-title texto-branco padding12">Descrição</th>-->                                                         
                            <th class="box-title texto-branco padding12 largura15">Criação</th>
                            <th class="box-title texto-branco padding12 largura15">Data de entrega</th>
                            <th class="box-title texto-branco padding12">Progresso</th>
                            <th class="box-title texto-branco padding12 com-border-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="fundo-branco com-shadow">
                        @foreach($jobs_andamento as $job)
                            <tr class="">
                                <td class="desktop largura10">#{{ $job->id }}</td>
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
                                        <a href="{{ route('jobs.show', encrypt($job->id)) }}"aria-expanded="false" class="word-break texto-preto">
                                        Detalhes</a>
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
                                                <img src="{{asset('storage/' . $job->thumb)}}" alt="Referência do Job" class="img-responsive card-job-thumb">
                                            @else
                                                <img src="{{asset('storage/imagens/jobs/job_default.png')}}" alt="Referência do Job" class="img-responsive card-job-thumb">
                                            @endif
                                        </div>
                                            
                                        <div class="col-md-6 margemT10 margemB5 ">
                                            <canvas id="job-and-chart-{{$job->id}}" class="graficos"  width="136" height="136" data-valor="{{ $job->concluido() }}" data-status="{{ $job->status }}"></canvas>
                                        </div>

                                        <div class="col-md-12 margemT10 margemB5 container-entrega-job">
                                            <p class="semMargem">
                                                <b>Entrega: </b>
                                                @php 
                                                    $data = $job->data_prox_revisao ? $job->data_prox_revisao->format('d.m.Y') : 'Não informado' 
                                                @endphp
                                                {{ $data }}
                                            </p>
                                        </div>
                                        <!--<div class="col-md-12 margemT20 margemB5 container-entrega-job">
                                            <p class="semMargem">
                                                <b>Coordenador: </b>
                                                {{ $job->coordenador->name ?? 'Não Informado' }}
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
                                        <div class="col-md-4">Nome</div>
                                        <div class="col-md-2">Colaborador</div>
                                        <div class="col-md-2">Descrição</div>
                                        <div class="col-md-1">Progresso</div>
                                        <div class="col-md-2">
                                            Data Entrega
                                        </div>
                                        <div class="col-md-1">
                                            <span class="pull-right titulo-tab-imagens" href="" class="" title="Criar Novo Job" data-toggle="tooltip">Ação</span>
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
                                                    {{$job->data_prox_revisao ? $job->data_prox_revisao->format('d.m.Y') : 'Não informado'}} 
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
                                                    <h4><b>Nome :</b> {{ $job->nome }}</h4>
                                                </div>
                                                <div class="col-md-2">
                                                    <a href="{{ route('jobs.show', encrypt($job->id)) }}" class="btn btn-info pull-right">
                                                        Mais detalhes
                                                    </a>  
                                                </div>
                                                <div class="col-md-12">
                                                    <h4><b>Descrição: </b> {{ $job->descricao ?? 'Não Informado'}}</h4>
                                                </div>
                                                <div class="col-md-12">
                                                    <h4><b>Coordenador: </b> {{ $job->coordenador ? $job->coordenador->name : 'Não Informado' }}</h4>
                                                </div>
                                                <div class="col-md-12">
                                                    <h4><b>Status: </b>
                                                    {{$job->getStatus($job->status)}}</h4>

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