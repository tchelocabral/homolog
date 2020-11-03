@extends('adminlte::page')

@section('title', 'Nova Task')

@section('content_header')
   {{ Breadcrumbs::render('nova task') }}
@stop

@section('content')

    <div class="row largura90 centralizado">

        <form id="form-tipo-imagem" name="form-tipo-imagem" action="{{ route('tasks.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">

                <div class="col-md-6 col-md-offset-3">
                    <div class="box box-solid box-primary no-border com-shadow">
                        <div class="box-header com-borda th-ocean">
                            <h3 class="box-title">Nova Task</h3>
                        </div>
                        <div class="box-body box-profile">

                            @include('task.inputs', ['task' => null, 'detalhe' => null])

                        </div>
                        <div class="box-footer footer-com-padding">
                            <button type="submit" class="btn btn-success pull-right">Adicionar Nova Task</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@stop

