
@extends('adminlte::page')

@section('title', 'Detalhes da Plano')

@section('content_header')
 {{--    {{ Breadcrumbs::render('politica-detalhe', $role) }} --}}
@stop

@section('content')
    <div class="row margemT40 centralizado">
      
            <div class="nav-tabs-custom altura-minima">
                <ul class="nav nav-tabs azul" id="tabs-politica" role="tablist">
                    <li class="active"><a data-toggle="tab" href="#detalhes" aria-expanded="true">Detalhes do Plano</a></li>
                </ul>

                <div class="tab-content">
                    {{ $plano->nome }}
                    {{-- Tab #detalhes--}}
                        
                        <div id="detalhes" class="tab-pane fade in active">
                            <div class="btn-toolbar margemT10" role="toolbar">
                                {{-- {{ $permi->role_id }} --}}
                            </div>
                            <hr>
                            <div class="row">
                                @foreach($roles as $rol)
                                <div class="col-md-6">
                                   
                                    <h1>{{ ucfirst($plano->name) }}</h1>
                                    @php $current = '' @endphp
                                    @foreach($plano->permissions as $per)

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
                                @endforeach       
                                
                            </div>
                        </div>


                </div><!-- end tab content -->
            </div>
    </div>
@stop
