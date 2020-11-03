
@extends('adminlte::page')

@section('title', 'Detalhes da Política')

@section('content_header')
 {{--    {{ Breadcrumbs::render('politica-detalhe', $role) }} --}}
@stop

@section('content')
    <div class="row margemT40 centralizado">
      
            <div class="nav-tabs-custom altura-minima">
                <ul class="nav nav-tabs azul" id="tabs-politica" role="tablist">
                    <li class="active"><a data-toggle="tab" href="#detalhes" aria-expanded="true">Detalhes da Política</a></li>
                </ul>

                <div class="tab-content">
                    {{-- Tab #detalhes--}}
                    <div id="detalhes" class="tab-pane fade in active">
                        <div class="btn-toolbar margemT10" role="toolbar">
                                <a href="{{ route('politicas.edit', encrypt($role->id)) }}" class="btn btn-primary" data-toggle="tooltip" title="Editar Política">
                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                </a>

                                <form action="#" method="POST" class="form-delete">
                                    @method('DELETE')
                                    @csrf
                                    <a href="#" class="btn btn-danger deletar-item margemL5" title="Excluir Política" data-toggle="tooltip" type="submit">
                                        <i class="fa fa-close" aria-hidden="true"></i>
                                    </a>
                                </form>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <h3>Política:</h3>
                                <h1>{{ ucfirst($role->name) }}</h1>
                                    @php $current = '' @endphp
                                    @foreach($role->permissions as $per)
                                        @if($per->bloco != $current)
                                            @if($current != '')
                                                </tbody>
                                                </table>
                                                </div>
                                                </div>
                                            @endif
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <h4 class="th-gray texto-branco com-borda com-padding sem-margem-bottom">Permissões de {{ ucfirst($per->bloco) }}</h4>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <table class="table table-bordered">
                                                        <tbody>
                                                        @if($loop->last)
                                                            </tbody>
                                                            </table>
                                                            </div>
                                                            </div>
                                                        @endif
                                            @php $current = $per->bloco @endphp
                                        @endif
                                        <tr>
                                            <td>{{ $per->name }}</td>
                                        </tr>
                                    </div>
                                @endforeach
                            </div>                         
                        </div>
                    </div>
                </div><!-- end tab content -->
            </div>
    </div>
@stop
