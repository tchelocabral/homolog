@extends('adminlte::page')

@section('title',  __('messages.Jobs em por usuários'))

@section('content_header')
   {{ Breadcrumbs::render('relatorios.dashboard') }} 
@stop

@section('content')

<div class="nav-tabs-custom nav-dashboard margemT50">
  <h1 class="margemT5 margemB5 titulo-principal texto-esquerda  titulo-lista-dash">{{ __('messages.Consolidado Jobs por Usuários')}}</h1>

  <ul class="nav nav-tabs displayFlex flexEnd paddingR20" id="tabs-projeto" role="tablist">
      <li class="active in">
          <a data-toggle="tooltip" href="#delegado" aria-expanded="true" class="nav-link" id="imagens-tab" aria-selected="false" title="{{ __('messages.Delegado')}}"> <i class="fa fa-list" aria-hidden="true"></i></a>
      </li>
      <li >
          <a data-toggle="tooltip" href="#coordenador" aria-expanded="true" class="nav-link" id="imagens-tab" aria-selected="false" title="{{ __('messages.Coordenando')}}"><i class="fa fa-credit-card" aria-hidden="true"></i></a>
      </li>
      <li>
          <a data-toggle="tooltip" href="#projetos" aria-expanded="true" class="nav-link" id="imagens-tab" title="{{ __('messages.Projetos')}}" aria-selected="false"><i class="fa fa-info" aria-hidden="true"></i></a>
      </li>
  </ul>

  <div class="tab-content">
    {{-- Tab #Delegado--}}
    <div id="delegado" class="tab-pane fade in active">
      <div class="btn-toolbar margemT10 tab-pane " role="toolbar">
        <h3 class="margemL10">{{ __('messages.Delegado')}}</h3>
        <table id="lista-dashboard-projetos" class="table table-striped larguraTotal">
            <thead>
              <tr>
                <th class="th-ocean texto-branco padding12">{{ __('messages.Nome')}}</th>
                <th class="th-ocean texto-branco padding12">{{ __('messages.Novo')}}</th>
                <th class="th-ocean texto-branco padding12">{{ __('messages.Delegado')}}</th>
                <th class="th-ocean texto-branco padding12">{{ __('messages.Em Execução')}}</th>
                <th class="th-ocean texto-branco padding12">{{ __('messages.Em Revisão')}}</th>
                <th class="th-ocean texto-branco padding12">{{ __('messages.Em Avaliação')}}</th>
                <th class="th-ocean texto-branco padding12">{{ __('messages.Concluído')}}</th>
                <th class="th-ocean texto-branco padding12">{{ __('messages.Recusado')}}</th>
                <th class="th-ocean texto-branco padding12">{{ __('messages.Reaberto')}}</th>
                <th class="th-ocean texto-branco padding12">{{ __('messages.Parado')}}</th>  
              </tr>
            </thead>
            <tbody>
              @foreach ($jobs as $key => $value) 
                <tr>
                  <td class="desktop margemL10">
                    <strong>{{$key}}</strong>
                  </td>
                    @foreach($value as $keyint => $valueint)
                    @if($keyint=="delegado")
                      @foreach($valueint as $keystatus => $valuestatus)
                        <td class="desktop"> {{'' .$valuestatus .''}} </td>
                      @endforeach
                    @endif
                    @endforeach
                </tr>
                @endforeach
              <tbody>
          </table>
      </div>
    </div>
    {{-- Tab #Coordenador--}}
    <div id="coordenador" class="tab-pane fade">
      <div class="btn-toolbar margemT10 tab-pane " role="toolbar">
        <h3 class="margemL10">{{ __('messages.Coordenador')}}</h3>
        <table id="lista-dashboard-projetos" class="table table-striped larguraTotal">
            <thead>
              <tr>
                <th class="th-ocean texto-branco padding12">{{ __('messages.Nome2')}}</th>
                <th class="th-ocean texto-branco padding12">{{ __('messages.Novo')}}</th>
                <th class="th-ocean texto-branco padding12">{{ __('messages.Delegado')}}</th>
                <th class="th-ocean texto-branco padding12">{{ __('messages.Em Execução')}}</th>
                <th class="th-ocean texto-branco padding12">{{ __('messages.Em Revisão')}}</th>
                <th class="th-ocean texto-branco padding12">{{ __('messages.Em Avaliação')}}</th>
                <th class="th-ocean texto-branco padding12">{{ __('messages.Concluído')}}</th>
                <th class="th-ocean texto-branco padding12">{{ __('messages.Recusado')}}</th>
                <th class="th-ocean texto-branco padding12">{{ __('messages.Reaberto')}}</th>
                <th class="th-ocean texto-branco padding12">{{ __('messages.Parado')}}</th>  
              </tr>
            </thead>
            <tbody>
              @foreach ($jobs as $key => $value) 
                <tr>
                  <td class="desktop margemL10">
                    <strong>{{$key}}</strong>
                  </td>
                    @foreach($value as $keyint => $valueint)
                    @if($keyint=="coordenando")
                      @foreach($valueint as $keystatus => $valuestatus)
                        <td class="desktop"> {{'' .$valuestatus .''}} </td>
                      @endforeach
                    @endif
                    @endforeach
                </tr>
                @endforeach
              <tbody>
          </table>
      </div>
    </div>
    {{-- Tab #projeto--}}
    <div id="projetos" class="tab-pane fade">
      <div class="btn-toolbar margemT10 tab-pane " role="toolbar">
        <h3 class="margemL10">{{ __('messages.Projetos')}}</h3>
        <table id="lista-dashboard-projetos" class="table table-striped larguraTotal">
            <thead>
              <tr>
                <th class="th-ocean texto-branco padding12">{{ __('messages.Nome3')}}</th>
                <th class="th-ocean texto-branco padding12">{{ __('messages.Novo')}}</th>
                <th class="th-ocean texto-branco padding12">{{ __('messages.Delegado')}}</th>
                <th class="th-ocean texto-branco padding12">{{ __('messages.Em Execução')}}</th>
                <th class="th-ocean texto-branco padding12">{{ __('messages.Em Revisão')}}</th>
                <th class="th-ocean texto-branco padding12">{{ __('messages.Em Avaliação')}}</th>
                <th class="th-ocean texto-branco padding12">{{ __('messages.Concluído')}}</th>
                <th class="th-ocean texto-branco padding12">{{ __('messages.Recusado')}}</th>
                <th class="th-ocean texto-branco padding12">{{ __('messages.Reaberto')}}</th>
                <th class="th-ocean texto-branco padding12">{{ __('messages.Parado')}}</th>  
              </tr>
            </thead>
            <tbody>
              @foreach ($jobs as $key => $value) 
                <tr>
                  <td class="desktop margemL10">
                    <strong>{{$key}}</strong>
                  </td>
                    @foreach($value as $keyint => $valueint)
                    @if($keyint=="projeto")
                      @foreach($valueint as $keystatus => $valuestatus)
                        <td class="desktop"> {{'' .$valuestatus .''}} </td>
                      @endforeach
                    @endif
                    @endforeach
                </tr>
                @endforeach
              <tbody>
          </table>
      </div>
    </div>

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
          "autoWidth": false,
          'sProcessing': {{ __('messages.Processando...') }},

        });

        $('[type="search"]').addClass("form-control")

        $("select[name='lista-dashboard_length']").addClass("custom-select custom-select-sm form-control form-control-sm")


      });

    </script>

@endpush