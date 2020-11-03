@extends('adminlte::page')

@section('title', $tipojob->nome ? $tipojob->nome : __('messages.Tipo de Job'))

@section('content_header')
   {{ Breadcrumbs::render('detalhe-tipojob', $tipojob) }}
@stop

@section('content')
    <div class="row margemT40 centralizado">
        @empty($tipojob)
            <h1>{{ __('messages.Tipo de Job não Encontrado')}}</h1>
        @else
            <h2>{{ __('messages.Detalhes do Tipo de Job')}}: {{ $tipojob->nome }}</h2>
            <div class="nav-tabs-custom altura-minima">
                <ul class="nav nav-tabs azul" id="tabs-tipojob" role="tablist">
                    <li class="active">
                        <a data-toggle="tab" href="#detalhes" aria-expanded="true" class="nav-link">{{ __('messages.Informações do Tipo de Job')}}</a>
                    </li>
                    <li>
                        <a data-toggle="tab" href="#arquivos" aria-expanded="false" class="nav-link">{{ __('messages.Arquivos')}}</a>
                    </li>
                </ul>
                <div class="tab-content">

                  

                    {{-- Tab #detalhes--}}
                    <div id="detalhes" class="tab-pane fade in active">
                        <div class="btn-toolbar margemT10" role="toolbar">
                                <a href="{{ route('tipojobs.edit', encrypt($tipojob->id)) }}" class="btn btn-primary" data-toggle="tooltip" title="{{ __('messages.Editar Informações')}}">
                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                </a>
                                <a href="{{ route('tipojob.add.arquivo', encrypt($tipojob->id)) }}" class="btn btn-info " title="{{ __('messages.Adicionar Arquivos ao Tipo de Job')}}" data-toggle="tooltip">
                                    <i class="fa fa-archive margemR5" aria-hidden="true"></i>
                                </a>
                                <a href="{{ route('tipojob.add.arquivos', encrypt($tipojob->id)) }}" class="btn cyan " title="{{ __('messages.Vincular Arquivos Existentes')}}" data-toggle="tooltip">
                                    <i class="fa fa-paperclip margemR5" aria-hidden="true"></i>
                                </a>
                                @if($tipojob->podeApagar)
                                    <form action="{{ route('tipojobs.destroy', encrypt($tipojob->id)) }}" method="POST" class="form-delete">
                                        @method('DELETE')
                                        @csrf
                                        <a href="#" class="btn btn-danger deletar-item margemL5" title="{{ __('messages.Excluir Tipo Job')}}" data-toggle="tooltip" type="submit">
                                            <i class="fa fa-close" aria-hidden="true"></i>
                                        </a>
                                    </form>
                                @else
                                    <form action="{{ route('tipojobs.transfer.deletar', encrypt($tipojob->id)) }}" method="POST" class="form-delete">
                                        @csrf
                                        <a href="#" class="btn btn-danger deletar-item-tipo-job margemL5" title="{{ __('messages.Troca de jobs Tipo Job')}}" data-toggle="tooltip" type="submit">
                                            <i class="fa fa-close" aria-hidden="true"></i>
                                        </a>
                                        <input type="hidden" name="qtd_tipos" id="qtd-tipos" value="{{ count($tipojob->jobs)  }}">
                                        <div class="invisivel">
                                            <select id="tipos-troca" name="tipos_troca" class="">
                                                <option value="-1">{{ __('messages.Escolha um tipo') }}</option>
                                                @foreach($tipojob->lista_tipos_troca as $index => $tipo_troca)
                                                    <option value="{{ $tipo_troca->id }}">{{$tipo_troca->nome }}</option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </form>
                                @endif
                        </div>
                        <hr>
                        @include('tipojob.inputs', ['tipojob' => $tipojob, 'detalhe' => true])
                    </div>

                    {{-- Tab #arquivos--}}
                    <div id="arquivos" class="tab-pane fade">
                        <div class="btn-toolbar margemT10" role="toolbar">
                            <a href="{{ route('tipojob.add.arquivo', encrypt($tipojob->id)) }}" class="btn btn-info " title="{{ __('messages.Adicionar Arquivos ao Tipo de Job')}}" data-toggle="tooltip">
                                <i class="fa fa-archive margemR5" aria-hidden="true"></i>
                                {{ __('messages.Adicionar Arquivos')}}
                            </a>
                            <a href="{{ route('tipojob.add.arquivos', encrypt($tipojob->id)) }}" class="btn cyan " title="{{ __('messages.Vincular Arquivos Existentes')}}" data-toggle="tooltip">
                                <i class="fa fa-paperclip margemR5" aria-hidden="true"></i>
                                {{ __('messages.Vincular Arquivos Existentes')}}
                            </a>
                        </div>
                        <hr>
                            <div class="table-responsive paddingB50">
                                <table class="table no-margin margemB40">
                                    <thead>
                                        <tr>
                                            <th>{{ __('messages.Thumb')}}</th>
                                            <th>{{ __('messages.Tipo')}}</th>
                                            <th>{{ __('messages.Arquivo')}}</th>
                                            <th>{{ __('messages.Nome')}}</th>
                                            <th>{{ __('messages.Opções')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($tipojob->midias as $img)
                                        <tr>
                                            @if(pathinfo($img->caminho, PATHINFO_EXTENSION ) == 'jpg' || pathinfo($img->caminho, PATHINFO_EXTENSION) == 'png')
                                                {{--<td><img src="{{ Storage::url($img->caminho) }}" width="28" height="28" alt=""></td>--}}
                                                <td><img src="{{ Storage::url($img->caminho) }}" width="28" height="28" alt=""></td>
                                            @else
                                                <td><img src="{{"/icones/".pathinfo($img->caminho, PATHINFO_EXTENSION)}}.png" width="28" height="28" alt="{{pathinfo($img->caminho, PATHINFO_EXTENSION)}}"></td>
                                            @endif
                                            <td>{{ $img->tipo_arquivo->nome }}</td>
                                            <td style="max-width: 250px; word-wrap: break-word;">{{ $img->nome_arquivo }}</td>
                                            <td>{{ $img->nome }}</td>
                                            <td>
                                                <div class="dropdown">
                                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                        <i class="fa fa-cog" aria-hidden="true"></i>
                                                    </a>
                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenu{{ $img->id }}">

                                                        <li>
                                                            <a href="{{ Storage::url($img->caminho) }}" download>
                                                                <i class="fa fa-download" aria-hidden="true"></i> {{ __('messages.Baixar')}}
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ Storage::url($img->caminho) }}" target="_blank">
                                                                <i class="fa fa-eye" aria-hidden="true"></i> {{ __('messages.Visualizar')}}
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a  style="height: 0px;width: 0px;overflow:hidden;"
                                                                href="{{ action('TipoJobController@desvincularArquivos', ['arquivo' => $img->id, 'tipojob' => $tipojob->id])}}"
                                                                class="desvincular-hidden">
                                                            </a>
                                                            <a class="desvincular">
                                                                <i class="fa fa-close" aria-hidden="true"></i> {{ __('messages.Desvincular Arquivo')}}
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
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