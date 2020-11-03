<h1 class="margemT5 margemB5 titulo-principal texto-esquerda titulo-lista-dash">
    <Span class="larguraTotal alturaTotal texto-preto" ><i class="fa fa-angle-right"></i></span> Jobs em Aberto</span>
</h1>
<div id="collapsejobabertos" class="tab-content" aria-labelledby="panel-proand" data-parent="#img-accordion">

    <div id="cardJobAberto" class="tab-pane fade  in active">
        <div class="btn-toolbar margemT10 tab-pane fade in" role="toolbar">
            <div class="col-md-12 painel-jobs">
                @foreach($jobs_abertos as $job)
                    <div class="col-md-3 col-lg-3 col-sm-12 card-job" data-rota="{{ route('jobs.show', encrypt($job->id)) }}">
                        <div class="box-group" id="accordion">
                            <div class="panel box box-success   com-shadow ">
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
                                <div id="collapseOne" class="panel-collapse collapse in" aria-expanded="false" >
                                    <div class="box-body">
                                        <div class="col-md-12" align="center">
                                            @if($job->thumb)
                                                <img src="{{asset('storage/' . $job->thumb)}}" alt="Referência do Job" class="img-responsive card-job-thumb">
                                            @else
                                                <img src="{{asset('storage/imagens/jobs/job_default.png')}}" alt="Referência do Job" class="img-responsive card-job-thumb">
                                            @endif
                                        </div>
                                        <div class="col-md-8 margemT20 margemB5 container-entrega-job">
                                            <p class="semMargem">
                                                <b>Entrega: </b>
                                                @php 
                                                    $data = $job->data_prox_revisao ?  $job->data_prox_revisao->format('d.m.Y') : 'Não informado' 
                                                @endphp
                                            {{ $data }}</p>
                                        </div>    
                                        <div class="col-md-12 margemT20 margemB5">
                                            <span id="job-freela-{{$job->id}}"       data-valor="{{$job->valor_job ? 'R$ '.$job->valor_job : 'A Combinar'}}" data-nome="{{$job->nome or $job->id}}" data-descricao="{{$job->descricao or ''}}" data-entrega="{{$data}}" data-url="{{route('freela.pega.job', encrypt($job->id))}}" {{-- data-token="{{csrf_field()}}" --}} data-tasks="{{$job->tasks or ''}}" class="pega-job invisivel">
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