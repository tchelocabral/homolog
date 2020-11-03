<h1 class="margemT5 margemB5 titulo-principal texto-esquerda titulo-lista-dash">
    <span class="larguraTotal alturaTotal texto-preto"><i class="fa fa-angle-right"></i></span> Jobs Disponíveis
    </span>
</h1>
<div id="collapsejobfreela" class="tab-content accordion" aria-labelledby="panel-proand" data-parent="#img-accordion">
{{-- Tab #card--}}
    <div id="cardJobAberto" class="tab-pane fade  in active">
        <div class="btn-toolbar margemT10 tab-pane fade in" role="toolbar">
            <div class="col-md-12 painel-jobs">

            @foreach($homeDados as $job)
                
                <div class="col-md-3 col-lg-3 col-sm-12 "> 
                    <div class="box-group" id="accordion">
                        <div class="panel box box-success   com-shadow">
                            <div class="col-md-6" >
                                <p class="semMargem">
                                    <span class="word-break text-break">
                                    {{ $job->user->name ?? $job->nome }}
                                    </span> 

                                </p>
                            </div>
                            <div class="col-md-6" align="right">
                                <p class="semMargem">
                                    <span  class="word-break">
                                    @if($job->delegado) Colaborador<br>
                                        {{$job->delegado->name}}
                                    @endif 
                                    </span> 
                                </p>
                            </div>
                            <div id="collapseOne" class="panel-collapse collapse in" aria-expanded="false" >
                                <div class="box-body">
                                    <div class="col-md-12" align="center">
                                        @if($job->thumb)
                                            <img src="{{asset('storage/' . $job->thumb)}}" alt="Referência do Job" class="img-responsive card-job-thumb">
                                        @else
                                            <img src="{{asset('storage/imagens/jobs/job.png')}}" alt="Referência do Job" class="img-responsive card-job-thumb">
                                        @endif
                                    </div>
                                    <div class="col-md-12 margemT20 margemB5 container-entrega-job">
                                        <p class="semMargem"><b>Entrega: </b>
                                        @php 
                                            $data = $job->data_prox_revisao ?  $job->data_prox_revisao->format('d.m.Y') : 'Não informado' 
                                        @endphp
                                        {{ $data }}</p>
                                    </div>    
                                    <div class="col-md-12 margemT20 margemB5">
                                        <span id="job-freela-{{$job->id}}" data-valor="{{$job->valor_job ? 'R$ '.(floatval($job->valor_job)*0.75) : 'A Combinar'}}" data-nome="{{$job->nome or $job->id}}"
                                        data-descricao="{{$job->descricao or ''}}"
                                        data-entrega="{{$data}}"
                                        data-url="{{route('freela.pega.job', $job->id)}}"
                                        {{-- data-token="{{csrf_field()}}" --}}
                                        data-tasks="{{$job->tasks or ''}}"
                                        class="pega-job invisivel">
                                        </span>
                                        @forelse($job->tasks as $task)
                                            <span id="tasks-{{$task->id}}-job-{{$job->id}}" data-nome="{{$task->nome}}" class="task-job-{{$job->id}}">
                                            </span>
                                        @empty  
                                        @endforelse
                                        <button class="btn btn-primary acao-pega-job margemR5" value="{{$job->id}}" data-id="{{$job->id}}" data-rota="{{ route('jobs.show', encrypt($job->id)) }}" name="btnPegaJob" >Visualizar</button> 
                                        <button class="btn btn-success acao-pega-job"  data-id="{{$job->id}}" name="btnPegaJob" >Pegar Job</button>
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