
@extends('adminlte::page')

@section('title',  __('messages.Jobs'))

@section('content_header')
   {{ Breadcrumbs::render('todos os jobs') }} 
@stop

@section('content')

    <div class="row centralizado">
        <div class="col-md-12">
            <h1 class="margemB40">{{ __('messages.Jobs' . ($titulo ? ' ' . $titulo : '')) }}</h1>
            {{-- <h1 class="margemB40">{{ $titulo}}</h1> --}}

            <div class="col-md-12">
                @unless($jobs->count())
                    <p>{{ __('messages.Não Existem Jobs para esta Lista')}}.</p>
                @else
                    <table id="lista-dashboard" class="table table-striped larguraTotal com-filtro">
                        <thead class="">
                            <tr class="">
                                <th colspan="" class="box-title th-ocean texto-branco padding12 com-border-left">#</th>
                                <th colspan="" class="box-title th-ocean texto-branco padding12 com-border-left texto-centralizado">{{ __('messages.Thumb')}}</th>
                                <th colspan="" class="box-title th-ocean texto-branco padding12">{{ __('messages.Nome do Job')}}</th>
                                <th colspan="" class="box-title th-ocean texto-branco padding12  texto-centralizado">{{ __('Ref')}}</th>
                                <th colspan="" class="box-title th-ocean texto-branco padding12">{{ __('messages.Status')}}</th>
                                <th colspan="" class="box-title th-ocean texto-branco padding12">{{ __('messages.Deadline')}}</th>
                                @if($role['name'] == 'freelancer')
                                    <th colspan="" class="box-title th-ocean texto-branco padding12">{{ __('messages.Publicador')}}</th>
                                @else
                                    <th colspan="" class="box-title th-ocean texto-branco padding12">{{ __('messages.Executado Por')}}</th>
                                @endif
                                @can('visualiza-valor')
                                    <th colspan="" class="box-title th-ocean texto-branco padding12">{{ __('messages.Valor')}}</th>
                                @endcan
                                <th colspan="" class="box-title th-ocean texto-branco padding12">{{ __('messages.Progresso')}}</th>
                                <th colspan="" class="box-title th-ocean texto-branco padding12 com-border-right">{{ __('messages.Ações')}}
                                </th>
                                @if(isset($concluir_job) && $concluir_job)
                                    <th colspan="" class="box-title th-ocean padding12 com-border-right">
                                        <form action="{{ route('job.mudarStatus.varios') }}" name="form_concluir_jobs" id="form-concluir-jobs" method="post">
                                            @csrf
                                            <button type="submit" disabled="true" data-toggle="tooltip" title="{{ __('messages.Concluir') }}" class="btn btn-default margemR20" id="button-jobs-concluir"><i class="fa fa-check-circle" aria-hidden="true"></i></button>
                                        </form>
                                    </th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="fundo-branco com-shadow">
                            @foreach($jobs as $job)
                                <tr class="">
                                    <td class="desktop">
                                        <a href="{{ route('jobs.show', encrypt($job->id)) }}">#{{ $job->id }}</a>
                                    </td>
                                    <td class="desktop">
                                        @if($job->thumb)
                                            <img src="{{URL::asset('storage/' . $job->thumb)}}" alt="{{ __('messages.Referência do Job')}}" class="img-responsive card-job-thumb">
                                        @else
                                            <img src="{{asset('storage/imagens/jobs/job_default.png')}}" alt="{{ __('messages.Referência do Job')}}" class="img-responsive card-job-thumb">
                                        @endif

                                    </td>    
                                    <td class="desktop">
                                        <a class="word-break" style="word-wrap: break-word" href="{{ route('jobs.show', encrypt($job->id)) }}">{{ $job->nome  }}</a>
                                    </td>
                                    <td class="desktop texto-centralizado">
                                        @isset($job->campos_personalizados)
                                            @php 
                                                $chave_array = array_key_exists ('Ref', $job->campos_personalizados);                                             
                                                if($chave_array) {
                                                    echo $job->campos_personalizados['Ref'];
                                                }
                                                else {
                                                    echo '---';
                                                }
                                            @endphp
                                        @else
                                            ---
                                        @endif
                                    </td>
                                    <td >
                                        {{ __('messages.' . $statusarray[$job->status]) }}
                                    </td>
                                    {{-- <td class="{{ ($job->data_prox_revisao && $job->data_prox_revisao > \Carbon\Carbon::now()) ? 'bg-success' : 'bg-danger' }}"> --}}
                                    <td class="">
                                        {{ $job->data_prox_revisao ? $job->data_prox_revisao->format('d/m/yy') : '-'}}
                                    </td>
                                    <td class="desktop">
                                        @if($role == 'freelancer')
                                            {{$job->user ? $job->user->name : __('messages.Não Informado')}}
                                        @else
                                            {{$job->delegado ? $job->delegado->name : __('messages.Não Informado')}}
                                        @endif
                                    </td>
                                    @can('visualiza-valor')
                                        <td class="desktop">
                                            @php $money = $job->valor_desconto ?? ($job->valor_job ?? '0.00'); @endphp
                                            R$ @convert_money($money)
                                        </td>
                                    @endcan
                                    <td>
                                        <div class="progress ">
                                            <div class="progress-bar progress-bar-animated progresso" role="progressbar" aria-valuenow="{{ $job->concluido() }}" aria-valuemin="0" aria-valuemax="100">
                                            </div>
                                        </div>
                                    </td>
                                    <td class="centralizado">
                                        @if($role == 'freelancer' && $job->status == 0)
                                            -
                                        @else
                                            <a href="{{ route('jobs.show', encrypt($job->id)) }}" class="">{{ __('messages.Visualizar')}}</a> 
                                        @endif
                                        @if($job->verificaStatus('pagamentopendente'))
                                            | <a href="{{ route('job.publicador.view.pagamento', encrypt($job->id)) }}" class="">{{ __('messages.Pagar')}}</a> 
                                        @endif
                                    </td>
                                    @if(isset($concluir_job) && $concluir_job)
                                        <td class="displayFlex flexCentralizado">
                                            @if($job->pode_concluir)
                                                <input type="checkbox" name="concluir_job" id="concluir-job-{{ $job->id }}" class="job-concluir" value = "{{ $job->id }}" />
                                            @endif
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                        </tfoot>
                    </table>
                @endunless
            </div>
        </div>
    </div>
@stop

@push('js')
    <script>

        $(document).ready(function(){
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
        });



    </script>

@endpush