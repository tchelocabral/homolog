@extends('adminlte::page')

@section('title', __('messages.Projetos Concluídos'))

@section('content_header')
        {{ Breadcrumbs::render('projetos concluidos') }}
@stop

@section('content')

    <div class="row largura80 centralizado">
        <div class="col-md-12">
            <h1 class="margemB40">{{ __('messages.Lista de Projetos Concluídos')}}</h1>
            @unless($projetos->count())
                <p>{{ __('messages.Não Existem Projetos Concluídos')}}</p>
            @else
                <table id="lista-dashboard" class="table table-striped larguraTotal com-shadow">
                    <thead>
                        <tr>
                            <th colspan="" class="th-ocean texto-branco padding12 border-left">#</th>
                            <th colspan="" class="th-ocean texto-branco padding12">{{ __('messages.Projeto')}}</th>
                            <th colspan="" class="th-ocean texto-branco padding12">{{ __('messages.Cliente')}}</th>
                            <th colspan="" class="th-ocean texto-branco padding12">{{ __('messages.Criação')}}</th>
                            <th colspan="" class="th-ocean texto-branco padding12">{{ __('messages.Previsão de Entrega')}}</th>
                            <th colspan="" class="th-ocean texto-branco padding12">{{ __('messages.Progresso')}}</th>
                            <th colspan="" class="th-ocean texto-branco padding12 border-right">{{ __('messages.Ações')}}</th>
                        </tr>
                    </thead>
                    <tbody class="fundo-branco">
                        @foreach($projetos as $prop => $proj)
                            <tr class="">
                                <td class="desktop">{{ $prop+1 }}</td>
                                <td>{{ $proj->nome  }}</td>
                                <td class="desktop">{{ $proj->cliente->nome_fantasia }}</td>
                                <td class="desktop">{{ $proj->created_at ? \Carbon\Carbon::parse($proj->created_at)->format('d.m.Y') :  __('messages.Não Informado')}}</td>
                                <td class="desktop">{{ $proj->data_previsao_entrega ? \Carbon\Carbon::parse($proj->data_previsao_entrega)->format('d.m.Y') :  __('messages.Não Informado')}}</td>
                                <td>
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-animated progresso" role="progressbar" aria-valuenow="{{ $proj->concluido() }}" aria-valuemin="0" aria-valuemax="100" ></div>
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('projetos.show', encrypt($proj->id)) }}" class="">{{ __('messages.Detalhes')}}</a>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                    <tfoot>

                    </tfoot>
                </table>
            @endunless
        </div>
    </div>

@stop




@push('js')
    <script src="{{ asset('js/jquery.dataTables.js')}}"></script>

    <script>
      $(function () {
        $('#lista-dashboard').DataTable({
          "paging": true,
          "lengthChange": true,
          "searching": true,
          "ordering": true,
          "info": true,
          "autoWidth": false,
          'sProcessing': '{{ __('messages.Processando...')}}',

        });

        $('[type="search"]').addClass("form-control")

        $("select[name='lista-dashboard_length']").addClass("custom-select custom-select-sm form-control form-control-sm")


      });

    </script>

@endpush


