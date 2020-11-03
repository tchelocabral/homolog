<h1 class="margemB40">{{ __('messages.Jobs' . ($titulo ? ' ' . $titulo : '')) }}</h1>
<div class="col-md-12">

    @php
        $count_jobs =    $jobs->count();

        if(!empty($jobs_pos) && $jobs_pos->count()>0)
        {
            $count_jobs += $jobs_pos->count();
        }
        
    @endphp
    @unless($count_jobs)
        <p>{{ __('messages.Não Existem Jobs para esta Lista')}}.</p>
    @else
        <table id="lista-dashboard" class="table table-striped larguraTotal search-table">
            <thead class="">
                <tr class="">
                    <th colspan="" class="box-title th-ocean texto-branco padding12 com-border-left">#</th>
                    @if(!$oculta_imagem) 
                        <th colspan="" class="box-title th-ocean texto-branco padding12 com-border-left">{{ __('messages.Thumb')}}</th>
                    @endif
                    <th colspan="" class="box-title th-ocean texto-branco padding12">{{ __('messages.Nome do Job')}}</th>
                    <th colspan="" class="box-title th-ocean texto-branco padding12">{{ __('messages.Status')}}</th>
                    <th colspan="" class="box-title th-ocean texto-branco padding12">{{ __('messages.Deadline')}}</th>
                    @if(!$oculta_imagem) 
                        @if($role['name'] == 'freelancer')
                            <th colspan="" class="box-title th-ocean texto-branco padding12">{{ __('messages.Publicador')}}</th>
                        @else
                            <th colspan="" class="box-title th-ocean texto-branco padding12">{{ __('messages.Executado Por')}}</th>
                        @endif
                    @endif
                    @can('visualiza-valor')
                        <th colspan="" class="box-title th-ocean texto-branco padding12">{{ __('messages.Valor')}}</th>
                    @endcan
                    @if(!$oculta_imagem) 
                        <th colspan="" class="box-title th-ocean texto-branco padding12">{{ __('messages.Progresso')}}</th>
                    @endif
                    <th colspan="" class="box-title th-ocean texto-branco padding12 com-border-right">{{ __('messages.Ações')}}</th>
                </tr>
            </thead>
            <tbody class="fundo-branco com-shadow">
                @foreach($jobs as $job)

                    <tr class="{{ $job->class_formatacao_fundo}}  {{$job->class_formatacao_texto }}">
                        <td class="desktop">
                            <a href="{{ route('jobs.show', encrypt($job->id)) }}">#{{ $job->id }}</a>
                        </td>
                        @if(!$oculta_imagem) 
                            <td class="desktop">
                            
                                @if($job->thumb)
                                    <img src="{{URL::asset('storage/' . $job->thumb)}}" alt="{{ __('messages.Referência do Job')}}" class="img-responsive card-job-thumb">
                                @else
                                    <img src="{{asset('storage/imagens/jobs/job_default.png')}}" alt="{{ __('messages.Referência do Job')}}" class="img-responsive card-job-thumb">
                                @endif
                            </td>    
                        @endif
                        <td class="desktop">
                            <a class="word-break" style="word-wrap: break-word" href="{{ route('jobs.show', encrypt($job->id)) }}">{{ $job->nome  }}</a>
                        </td>
                        <td >
                            {{ __('messages.' . $statusarray[$job->status]) }}
                        </td>
                        <td>
                            {{ $job->data_prox_revisao ? $job->data_prox_revisao->format('d/m/yy') : '-'}}
                        </td>
                        @if(!$oculta_imagem) 
                        <td class="desktop">
                            @if($role == 'freelancer')
                                {{$job->user ? $job->user->name : __('messages.Não Informado')}}
                            @else
                                {{$job->delegado ? $job->delegado->name : __('messages.Não Informado')}}
                            @endif
                        </td>
                        @endif
                        @can('visualiza-valor')
                            <td class="desktop">
                                @php $money = $job->valor_desconto ?? ($job->valor_job ?? '0.00'); @endphp
                                R$ @convert_money($money)
                            </td>
                        @endcan
                        @if(!$oculta_imagem) 
                            <td>
                                <div class="progress ">
                                    <div class="progress-bar progress-bar-animated progresso" role="progressbar" aria-valuenow="{{ $job->concluido() }}" aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>
                            </td>
                        @endif
                        <td>
                            @if($role == 'freelancer' && $job->status == 0)
                                -
                            @else
                                <a href="{{ route('jobs.show', encrypt($job->id)) }}" class="">{{ __('messages.Visualizar')}}</a> 
                            @endif
                            @if($job->pagamento_pendente)
                                | <a href="{{ route('job.publicador.view.pagamento', encrypt($job->id)) }}" class="">{{ __('messages.Pagar')}}</a> 
                            @endif
                        </td>
                    </tr>
                @endforeach

                {{-- @if(!empty($jobs_pos))
                    @foreach($jobs_pos as $job)
                        <tr class="">
                            <td class="desktop">
                                <a href="{{ route('jobs.show', encrypt($job->id)) }}">#{{ $job->id }}</a>
                            </td>
                            @if(!$oculta_imagem) 
                                <td class="desktop">
                                
                                        @if($job->thumb)
                                            <img src="{{URL::asset('storage/' . $job->thumb)}}" alt="{{ __('messages.Referência do Job')}}" class="img-responsive card-job-thumb">
                                        @else
                                            <img src="{{asset('storage/imagens/jobs/job_default.png')}}" alt="{{ __('messages.Referência do Job')}}" class="img-responsive card-job-thumb">
                                        @endif
                                </td>    
                            @endif
                            <td class="desktop">
                                <a class="word-break" style="word-wrap: break-word" href="{{ route('jobs.show', encrypt($job->id)) }}">{{ $job->nome  }}</a>
                            </td>
                            <td >
                                {{ __('messages.' . $statusarray[$job->status]) }}
                            </td>
                            <td>
                                {{ $job->data_prox_revisao ? $job->data_prox_revisao->format('d/m/yy') : '-'}}
                            </td>
                            @if(!$oculta_imagem) 
                            <td class="desktop">
                                @if($role == 'freelancer')
                                    {{$job->user ? $job->user->name : __('messages.Não Informado')}}
                                @else
                                    {{$job->delegado ? $job->delegado->name : __('messages.Não Informado')}}
                                @endif
                            </td>
                            @endif
                            @can('visualiza-valor')
                                <td class="desktop">
                                    @php $money = $job->valor_desconto ?? ($job->valor_job ?? '0.00'); @endphp
                                    R$ @convert_money($money)
                                </td>
                            @endcan
                            @if(!$oculta_imagem) 
                                <td>
                                    <div class="progress ">
                                        <div class="progress-bar progress-bar-animated progresso" role="progressbar" aria-valuenow="{{ $job->concluido() }}" aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>
                                </td>
                            @endif
                            <td>
                                @if($role == 'freelancer' && $job->status == 0)
                                    -
                                @else
                                    <a href="{{ route('jobs.show', encrypt($job->id)) }}" class="">{{ __('messages.Visualizar')}}</a> 
                                @endif
                            @if($job->verificaStatus('pagamentopendente'))
                                    | <a href="{{ route('job.publicador.view.pagamento', encrypt($job->id)) }}" class="">{{ __('messages.Pagar')}}</a> 
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @endif --}}
            </tbody>
            <tfoot>
            </tfoot>
        </table>
    @endunless
</div>