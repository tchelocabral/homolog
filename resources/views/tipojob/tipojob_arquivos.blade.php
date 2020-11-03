@extends('adminlte::page')

@section('title', __('messages.Adicionar Arquivo ao Tipo de Job'))

@section('content_header')
   {{ Breadcrumbs::render('adicionar arquivo tipojob', $tipojob) }}
@stop

@section('content')

    <div class="row larguraTotal">
        <form id="form-add-arquivo" name="form-add-arquivo" action="{{ route('tipojobs.vincular.arquivos') }}" method="POST" enctype="multipart/form-data">
            {{--security token--}}
            @csrf
            <div class="row margemB40">
                <div class="col-md-8">
                    <h1 class="">{{ __('messages.Adicionar Arquivos ao Tipo de Job')}}</h1>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="box box-solid box-primary no-border com-shadow">
                        <div class="box-header com-borda th-ocean">
                            <h3 class="box-title">{{ __('messages.Selecione os arquivos que deseja adicionar ao Tipo de job')}}</h3>
                        </div>
                        <div class="box-body box-profile">
                            <div class="row">
                                <div class="col-md-12">
                                    <h3 class="">{{ __('messages.Tipo de Job')}}</h3>
                                    <input type="hidden" name="tipojob_id" value="{{ $tipojob->id }}">
                                    <h3 class="box-title">
                                        <a href="{{ route('tipojobs.show', encrypt($tipojob->id)) }}" data-toggle="tooltip" title="{{ __('messages.Ir para o Tipo Job')}}">
                                            {{ $tipojob['nome'] ?? __('messages.Não Informado') }}
                                        </a>
                                    </h3>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-12 col-md-12">
                                    <h3 class="box-title">{{ __('messages.Arquivos')}}</h3>
                                    <hr>
                                    <div class="row div-item-form">
                                        <div class="col-md-12">
                                            <table id="lista-arquivos" class="table">
                                                <thead>
                                                <tr>
                                                    <th colspan="5"><input type="checkbox" name="check-todos-arquivos" id="check-todos-arquivos" class="margemR15"> {{ __('messages.Selecione todos os arquivos de todos os tipos')}}</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @isset($arquivos)
                                                    @foreach($arquivos as $arquivo)
                                                        <tr>
                                                            <td>
                                                                <input type="checkbox" id="arquivo{{ $arquivo->id }}" value="{{ $arquivo->id }}" class="id-item-arquivo" name="arquivos[]">
                                                            </td>
                                                            {{--@if(pathinfo($arquivo->caminho, PATHINFO_EXTENSION ) == 'jpg' || pathinfo($arquivo->caminho, PATHINFO_EXTENSION) == 'png')--}}
                                                                {{--<td><img src="{{ URL::asset($arquivo->caminho) }}" width="28" height="28" alt=""></td>--}}
                                                            {{--@else--}}
                                                                {{--<td><img src="{{"/icones/".pathinfo($arquivo->caminho, PATHINFO_EXTENSION)}}.png" width="28" height="28" alt="{{pathinfo($arquivo->caminho, PATHINFO_EXTENSION)}}"></td>--}}
                                                            {{--@endif--}}
                                                            @if(pathinfo($arquivo->caminho, PATHINFO_EXTENSION ) == 'jpg' || pathinfo($arquivo->caminho, PATHINFO_EXTENSION) == 'png')
                                                                <td><img src="{{ Storage::url($arquivo->caminho) }}" width="28" height="28" alt=""></td>
                                                            @else
                                                                @php
                                                                    $exts   = ['3ds', 'cad', 'doc', 'dxf', 'max', 'pdf', 'psd', 'txt', 'docx', 'xls', 'zip', 'dwg'];
                                                                    $ext    = pathinfo($arquivo->caminho, PATHINFO_EXTENSION);
                                                                    $icone  = '/icones/';
                                                                    $icone .= in_array($ext, $exts) ? $ext : 'padrao';
                                                                    $icone .= '.png';
                                                                @endphp
                                                                <td><img src="{{$icone}}" width="28" height="28" alt="{{$ext}}"></td>
                                                            @endif
                                                            <td><p class="tipo-item-arquivo">{{ __('messages.' . $arquivo->tipo_arquivo->nome) }}</p></td>
                                                            <td><p class="descricao-item-arquivo">{{ $arquivo->nome_arquivo }}</p></td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer footer-com-padding">
                            <button type="submit" class="btn btn-success pull-right">{{ __('messages.Confirmar Arquivos')}}</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @include('app.includes.carregando')

@stop

{{-- Controle de Carregamento das Imagem do Projeto --}}
@push('js')
    <script>
        $(document).ready(function () {
            jQuery('#lista-arquivos').on('click', '.clickable tr td', function (e) {
                $('.clickable').removeClass('active-collpase');
                if(!$(this).parents().next().find('tr').hasClass('in')) {
                    $(this).parents('.clickable').addClass('active-collpase');
                }
            });
            // Seleciona Todas as Imagem
            jQuery('#check-todas-imagens').on('ifChanged', function (e) {
                this.checked ? selecionaTodasImagens() : selecionaNenhumaImagem();
            });
            function selecionaTodasImagens() {
                var imagens = jQuery('.id-item-imagem');
                $.each(imagens, function (key, value) {
                    console.log(value);
                    value.checked = true;
                    jQuery('#'+value.id).iCheck('update');
                    jQuery('#'+value.id).iCheck('check');
                });
            }
            function selecionaNenhumaImagem() {
                var arquivos = jQuery('.id-item-imagem');
                $.each(arquivos, function (key, value) {
                    console.log(value);
                    value.checked = false;
                    jQuery('#'+value.id).iCheck('update');
                    jQuery('#'+value.id).iCheck('uncheck');
                });
            }

            // Seleciona Todos os Arquivos
            jQuery('#check-todos-arquivos').on('ifChanged', function (e) {
                this.checked ? selecionaTodosArquivos() : selecionaNenhumArquivo();
            });
            function selecionaTodosArquivos() {
                var arquivos = jQuery('.id-item-arquivo');
                $.each(arquivos, function (key, value) {
                    console.log(value);
                    value.checked = true;
                    jQuery('#'+value.id).iCheck('update');
                    jQuery('#'+value.id).iCheck('check');
                });
            }
            function selecionaNenhumArquivo() {
                var arquivos = jQuery('.id-item-arquivo');
                $.each(arquivos, function (key, value) {
                    console.log(value);
                    value.checked = false;
                    jQuery('#'+value.id).iCheck('update');
                    jQuery('#'+value.id).iCheck('uncheck');
                });
            }
        });
    </script>
@endpush
{{-- Fim do Controle de Carregamento de Imagem do Projeto --}}

