@extends('adminlte::page')

@section('title', {{ __('messages.Jobs em Concluídos')}})

@section('content_header')
   {{ Breadcrumbs::render('jobs concluidos') }} 
@stop

@section('content')


    <div class="row largura90 centralizado">
        <h1 class="margemB40">{{ __('messages.Jobs Concluídos')}}</h1>

        <div class="col-md-12">
            @include('job.lista')
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
          'sProcessing': __('messages.Processando...'),

        });

        $('[type="search"]').addClass("form-control")

        $("select[name='lista-dashboard_length']").addClass("custom-select custom-select-sm form-control form-control-sm")


      });

    </script>

@endpush