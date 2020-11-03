@extends('adminlte::page')

@section('title', $deliveryformat->nome ? $deliveryformat->nome : __('messages.Formato de entrega'))

@section('content_header')
   {{ Breadcrumbs::render('detalhe-deliveryformat', $deliveryformat) }}
@stop

@section('content')
    <div class="row margemT40 centralizado">
        @empty($deliveryformat)
            <h1>{{ __('messages.Formato de entrega não encontrado')}}</h1>
        @else
            <h2>{{ __('messages.Detalhes do Formato de entrega')}}: {{ $deliveryformat->nome }}</h2>
            <div class="nav-tabs-custom altura-minima">
                <ul class="nav nav-tabs azul" id="tabs-deliveryformat" role="tablist">
                    <li class="active">
                        <a data-toggle="tab" href="#detalhes" aria-expanded="true" class="nav-link">{{ __('messages.Informações do Formato de entrega')}}</a>
                    </li>
                </ul>
                <div class="tab-content">
                    {{-- Tab #detalhes--}}
                    <div id="detalhes" class="tab-pane fade in active">
                        <div class="btn-toolbar margemT10" role="toolbar">
                                <a href="{{ route('deliveryformat.edit', encrypt($deliveryformat->id)) }}" class="btn btn-primary" data-toggle="tooltip" title="{{ __('messages.Editar Informações')}}">
                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                </a>
                                {{-- <a href="{{ route('deliveryformat.add.arquivo', encrypt($deliveryformat->id)) }}" class="btn btn-info " title="{{ __('messages.Adicionar Arquivos ao Formato de entrega')}}" data-toggle="tooltip">
                                    <i class="fa fa-archive margemR5" aria-hidden="true"></i>
                                </a>
                                <a href="{{ route('deliveryformat.add.arquivos', encrypt($deliveryformat->id)) }}" class="btn cyan " title="{{ __('messages.Vincular Arquivos Existentes')}}" data-toggle="tooltip">
                                    <i class="fa fa-paperclip margemR5" aria-hidden="true"></i>
                                </a> --}}
                                @if($deliveryformat->podeApagar)
                                    <form action="{{ route('deliveryformat.destroy', encrypt($deliveryformat->id)) }}" method="POST" class="form-delete">
                                        @method('DELETE')
                                        @csrf
                                        <a href="#" class="btn btn-danger deletar-item margemL5" title="{{ __('messages.Excluir Formato de entrega')}}" data-toggle="tooltip" type="submit">
                                            <i class="fa fa-close" aria-hidden="true"></i>
                                        </a>
                                    </form>
                                @else
                                    <form action="{{ route('deliveryformats.transfer.deletar', encrypt($deliveryformat->id)) }}" method="POST" class="form-delete">
                                        @csrf
                                        <a href="#" class="btn btn-danger deletar-item-tipo-job margemL5" title="{{ __('messages.Troca de jobs Tipo Job')}}" data-toggle="tooltip" type="submit">
                                            <i class="fa fa-close" aria-hidden="true"></i>
                                        </a>
                                        <input type="hidden" name="qtd_tipos" id="qtd-tipos" value="{{ count($deliveryformat->jobs)  }}">
                                        <div class="invisivel">
                                            <select id="tipos-troca" name="tipos_troca" class="">
                                                <option value="-1">{{ __('messages.Escolha um tipo') }}</option>
                                                @foreach($deliveryformat->lista_tipos_troca as $index => $tipo_troca)
                                                    <option value="{{ $tipo_troca->id }}">{{$tipo_troca->nome }}</option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </form>
                                @endif
                        </div>
                        <hr>
                        @include('deliveryformat.inputs', ['deliveryformat' => $deliveryformat, 'detalhe' => true])
                    </div>

                    {{-- Tab #jobs--}}
                    <div id="jobs" class="tab-pane fade">
                        {{--<h2>{{ __('messages.em desenvolvimento')}}</h2>--}}
                    </div>

                </div>
            </div>
        @endempty
    </div>
@stop
@push('js')
    <script>
        $(document).ready(function () {
        })
    </script>
@endpush