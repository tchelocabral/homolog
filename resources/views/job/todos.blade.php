@extends('adminlte::page')

@section('title', __('messages.Todos os Jobs'))

@section('content_header')
   {{ Breadcrumbs::render('todos os jobs') }} 
@stop

@section('content')

    <div class="row largura90 centralizado">
        <h1 class="margemB40">Todos os Jobs</h1>

        <div class="col-md-12">
            {{-- @unless($jobs->count()) --}}
                {{-- <p>Não Existem Jobs</p> --}}
            {{-- @else --}}
                {{-- <table id="lista-dashboard" class="table table-striped larguraTotal">
                    <thead class="">
                        <tr class="">
                            <th colspan="" class="box-title th-ocean texto-branco padding12 com-border-left">#</th>
                            <th colspan="" class="box-title th-ocean texto-branco padding12 com-border-left">Thumb</th>
                            <th colspan="" class="box-title th-ocean texto-branco padding12" >Nome do Job</th>
                            <th colspan="" class="box-title th-ocean texto-branco padding12">Status</th>
                            @if($role == 'freelancer')
                                <th colspan="" class="box-title th-ocean texto-branco padding12">Publicador</th>
                            @else
                                <th colspan="" class="box-title th-ocean texto-branco padding12">Executado Por</th>
                            @endif
                            @can('visualiza-valor')
                                <th colspan="" class="box-title th-ocean texto-branco padding12">Valor</th>
                            @endcan
                            <th colspan="" class="box-title th-ocean texto-branco padding12">Progresso</th>
                            <th colspan="" class="box-title th-ocean texto-branco padding12 com-border-right">Ações</th>
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
                                    <img src="{{URL::asset('storage/' . $job->thumb)}}" alt="Referência do Job" class="img-responsive card-job-thumb">
                                @else
                                    <img src="{{asset('storage/imagens/jobs/job_default.png')}}" alt="Referência do Job" class="img-responsive card-job-thumb">
                                @endif

                            </td>    
                            <td class="desktop">
                                <a class="word-break" style="word-wrap: break-word" href="{{ route('jobs.show', encrypt($job->id)) }}">{{ $job->nome  }}</a>
                            </td>
                            <td >
                                {{$statusarray[$job->status]}}
                            </td>
                            <td class="desktop">
                                @if($role == 'freelancer')
                                    {{$job->user ? $job->user->name : 'Não Informado'}}
                                @else
                                    {{$job->delegado ? $job->delegado->name : 'Não Informado'}}
                                @endif
                            </td>
                            @can('visualiza-valor')
                                <td class="desktop">
                                    @php $money = $job->valor_desconto ?? ($job->valor_job ?? '0.00'); @endphp
                                    R$ @convert_money($money)
                                </td>
                            @endcan
                            <td>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-animated progresso" role="progressbar" aria-valuenow="{{ $job->concluido() }}" aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>
                            </td>
                            <td>
                                <a href="{{ route('jobs.show', encrypt($job->id)) }}" class="">Detalhes</a> 
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table> --}}
                @include('job.lista')
            {{-- @endunless --}}
        </div>
    </div>

@stop


@push('js')
    <script src="{{ asset('js/jquery.dataTables.js')}}"></script>

    <script>
      $(function () {
        $('#lista-dashboard').DataTable({
          "paging": false,
          "lengthChange": true,
          "searching": true,
          "ordering": true,
          "info": true,
          "autoWidth": true,
          'sProcessing': {{ __('messages.Processando...') }},

        });

        $('[type="search"]').addClass("form-control")

        $("select[name='lista-dashboard_length']").addClass("custom-select custom-select-sm form-control form-control-sm")


      });

    </script>

@endpush