@extends('adminlte::page')

@section('title', 'Adicionar Arquivo ao Tipo Job')

@section('content_header')
   {{ Breadcrumbs::render('adicionar arquivo tipojob', $tipojob) }}
   @stop

@section('content')

    <div class="row centralizado">
        <form id="form-add-arquivo" name="form-add-arquivo" action="{{ route('tipojobs.gravar.arquivo', encrypt($tipojob->id)) }}" method="POST" enctype="multipart/form-data">
            {{--security token--}}
            @csrf
            <div class="row margemB40">
                <div class="col-md-8">
                    <h1 class="">Adicionar Arquivos ao Tipo de Job</h1>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="box box-solid box-primary no-border com-shadow">
                        <div class="box-header com-borda th-ocean">
                            <h3 class="box-title">Selecione o tipo de arquivo e o arquivo que deseja incluir no Tipo Job</h3>
                        </div>
                        <div class="box-body box-profile">

                            @isset($tipojob)
                                <div class="col-sm-12 col-md-5">
                                    <div class="row div-item-form">
                                        <div class="col-md-12">
                                            <h3 class="">Tipo de Job</h3>
                                            <hr>
                                            <input type="hidden" name="tipojobs_id" value="{{ $tipojob->id }}">
                                            <p class="detalhe-label"><b>Tipo de Job</b></p>
                                            <p id="nome" class="margemB20 info-detalhe-maior" >
                                                <a href="{{ route('tipojobs.show', encrypt($tipojob->id)) }}" data-toggle="tooltip" title="Ir para o Tipo Job">
                                                    {{ $tipojob['nome'] or 'Não Informado' }}
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                    <h3 class="box-title">Arquivo</h3>
                                    <hr>
                                    <div class="row div-item-form">
                                        <div class="col-md-12">
                                            <p><b>Selecione o Tipo de Arquivo</b></p>
                                            <select id="combo-tipo-arquivo" name="arquivo_tipo_id" class="form-control select2 margemT10">
                                                @isset($tipos_arquivos)
                                                    @unless($tipos_arquivos)
                                                        <option value="">Sem Tipos de Arquivos Cadastrados</option>
                                                    @else
                                                        @foreach($tipos_arquivos as $tipo)
                                                            <option value="{{ $tipo->id }}">{{$tipo->nome }}</option>
                                                        @endforeach
                                                    @endunless
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row div-tem-form">
                                        <div class="col-md-12">
                                            <p><b>Selecione o arquivo</b></p>
                                            <div class="input-group image-preview margemT20">
                                                <input id="arquivo" type="text" class="form-control image-preview-filename" disabled="disabled" placeholder="Nenhuma imagem selecionada.">
                                                <span class="input-group-btn">
                                                <!-- image-preview-clear button -->
                                                <button type="button" class="btn btn-default image-preview-clear" style="display:none;">
                                                    <span class="glyphicon glyphicon-remove"></span> Limpar
                                                </button>
                                                    <!-- image-preview-input -->
                                                <div class="btn btn-default image-preview-input">
                                                    <span class="glyphicon glyphicon-folder-open"></span>
                                                    <span class="image-preview-input-title">Procurar</span>
                                                    <input id="input-arquivo" type="file" accept="*" />
                                                </div>
                                            </span>
                                                <input id="lista_arquivos" type="file" accept="*" name="lista_arquivos[]" multiple="multiple" style="position: absolute; top: 0px; right: 1000px; ">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row div-tem-form margemT20">
                                        <div class="col-md-12">
                                            <p><b>Dê um nome para o arquivo</b></p>
                                            <input type="text" class="form-control" id="nome-arquivo" placeholder="Nenhum nome..." />
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row div-tem-form">
                                        <div class="col-md-2 pull-right">
                                            <a id="add_arq" class="btn btn-success pull-right" href="#">
                                                <i class="fa fa-plus" aria-hidden="true"></i>
                                                Adicionar ao Tipo Job
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="col-sm-12 col-md-3">
                                    <div class="row div-item-form">
                                        <div class="col-md-12">
                                            <h3 class="box-title">Selecione o Tipo Job</h3>
                                            <hr>
                                            <div class="row div-item-form">
                                                <div class="col-md-12">
                                                    <p><b>Qual o Tipo de Job ?</b></p>
                                                    <select id="combo-tipo-job" name="tipojob_id" class="form-control select2 margemT10">
                                                        @isset($tipojob)
                                                            @unless($tipojob)
                                                                <option value="">Sem Tipos de Job Cadastrados</option>
                                                            @else
                                                                @foreach($tipojob as $proj)
                                                                    <option value="{{ $proj->id }}">{{$proj->nome }}</option>
                                                                @endforeach
                                                            @endunless
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="col-sm-12 col-md-6">
                                <p class=""><b>Lista de Arquivos do Tipo Job:</b></p>
                                <table class="table" id="lista-arquivos">
                                    <thead>
                                    <tr>
                                        <th>Tipo</th>
                                        <th>Nome</th>
                                        <th>Nome Arquivo</th>
                                        <th>Opções</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @empty(!$tipojob->midias)
                                        @foreach($tipojob->midias as $midia)
                                            <tr>
                                                <td>{{ $midia->tipo_arquivo->nome }}</td>
                                                <td>{{ $midia->nome }}</td>
                                                <td style="max-width: 300px; word-wrap: break-word;">{{ $midia->nome_arquivo }}</td>
                                                <td>
                                                    <div class="dropdown">
                                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                            <i class="fa fa-cog" aria-hidden="true"></i>
                                                        </a>
                                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu{{ $midia->id }}">

                                                            <li>
                                                                <a href="{{ Storage::url($midia->caminho) }}" download>
                                                                    <i class="fa fa-download" aria-hidden="true"></i> Baixar
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="{{ Storage::url($midia->caminho) }}" target="_blank">
                                                                    <i class="fa fa-eye" aria-hidden="true"></i> Visualizar
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a  style="height: 0px;width: 0px;overflow:hidden;"
                                                                    href="{{ action('TipoJobController@desvincularArquivos', ['arquivo' => $midia->id, 'id' => $tipojob->id])}}"
                                                                    class="desvincular-hidden">
                                                                </a>
                                                                <a class="desvincular">
                                                                    <i class="fa fa-close" aria-hidden="true"></i> Remover Arquivo
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endempty
                                    </tbody>
                                </table>
                            </div>

                        </div>
                        <div class="box-footer">
                        </div>
                    </div>
                </div>
            </div>

        </form>
    </div>

    @include('app.includes.carregando')
@stop


{{-- Controle dos Arquivos --}}
@push('js')
    <script src="{{ asset('js/arquivos.js') }}"></script>
    <script>
        $(document).ready(function () {
            // ADD Arquivo
            jQuery('#add_arq').on('click', function (e) {
                e.preventDefault();
                addArquivo(e);
            });
            // Remover Arquivo
            jQuery('.remove-arquivo').on('click', function (e) {
                e.preventDefault();
                $(this).parents('.conteudo-arquivo').first().remove();
            });

            function addArquivo(e) {
                //  pega valor do campo
                var comboTipo       = document.getElementById("combo-tipo-arquivo");
                var tipoSelecionado = comboTipo.options[ comboTipo.selectedIndex ];
                var tipoID          = tipoSelecionado.value;
                var nome_arquivo    = document.getElementById('nome-arquivo').value;
                var arquivo         = document.getElementById('input-arquivo').files[0];

                var lista_arquivos  = document.getElementById('lista_arquivos').files;
                var tamanho_lista   = document.getElementById('lista_arquivos').files.length;

                file_ele = document.getElementById('input-arquivo');

                if(arquivo){
                    var formData = new FormData();
                    formData.append('tipo_arquivo', tipoID);
                    // formData.append('caminho', tipoID);
                    formData.append('nome', nome_arquivo);
                    formData.append('arquivo', arquivo);
                    formData.append('tipojob_id', {{ $tipojob->id }});

                    var params = {tipo_arquivo: tipoID, nome: nome_arquivo, arquivo: arquivo, tipojob_id: {{ $tipojob->id  }} };
                    var url = "{{ route('tipojobs.gravar.arquivo', encrypt($tipojob->id)) }}";
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': '{{ @csrf_token() }}'
                        }
                    });
                    $.ajax({
                        url: url,
                        data: formData,
                        type: 'post',
                        dataType: 'json',
                        contentType: false,
                        processData: false,
                        beforeSend: function(xhr){
                            // processando();
                        },
                        success: function(data) {
                            console.info(data);
                            atualiza_lista_de_arquivos(data, tipoSelecionado.text);
                            sucesso_info('Sucesso!', 'Arquivo incluído.')
                        },
                        error: function(data) {
                            erro_info('Oopss..', 'Problemas ao adicionar o arquivo. Recarregue a página e tente novamente.');
                            console.info(data);
                        },
                        always: function(data){
                            // fimProcessando();
                        }
                    });

                }
            }
            function atualiza_lista_de_arquivos(data, tipo) {
                var html = geraHtmlArquivo(data, tipo);
                jQuery('#lista-arquivos').append( html );
            }
            function geraHtmlArquivo(arquivo_prj, tipo_arquivo){
                var arq = '<tr class="conteudo-arquivo" data-id="' + arquivo_prj.id + '">';
                arq   += '<td >';
                arq   += '<p>' + tipo_arquivo + '</p>';
                arq   += '</td>';
                arq   += '<td >';
                arq   += '<p>' + arquivo_prj.nome + '</p>';
                arq   += '</td>';
                arq   += '<td >';
                arq   += '<p>' + arquivo_prj.nome_arquivo + '</p>';
                arq   += '</td>';
                arq   += '<td>';
                arq   += '<div class="dropdown">'
                arq   += '<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">'
                arq   += '<i class="fa fa-cog" aria-hidden="true"></i>'
                arq   += '</a>'
                arq   += '<ul class="dropdown-menu" aria-labelledby="dropdownMenu' + arquivo_prj.id + '">'
                arq   += '<li>'
                arq   += '<a href="/storage/'+arquivo_prj.caminho + '" download>'
                arq   += '<i class="fa fa-download" aria-hidden="true"></i> Baixar'
                arq   += '</a>'
                arq   += '</li>'
                arq   += '<li>'
                arq   += '<a href="/storage/'+arquivo_prj.caminho + '" target="_blank">'
                arq   += '<i class="fa fa-eye" aria-hidden="true"></i> Visualizar'
                arq   += '</a>'
                arq   += '</li>'
                arq   += '<li>'
                arq   += '<a class="remove-arquivo" href="javascript:void(0);">'
                arq   += '<i class="fa fa-times"></i> Remover Arquivo'
                arq   += '</a>'
                arq   += '</li>'
                arq   += '</ul>'
                arq   += '</div>'
                arq   += '</td>';
                arq   += '</tr>';
                return arq;
            }
            function limpaCampos(){
                document.getElementById("combo-tipo-arquivo").selectedIndex = 0;
                $('#arquivo').val("");
                $('.image-preview-clear').hide();
                $('.image-preview-input').val("");
                $('.image-preview-input-title').text("Procurar");
                $('#nome-arquivo').val("");
                $('#combo-tipo-arquivo').focus()
            }
        });
    </script>
@endpush
{{-- Fim do Controle de Arquivos --}}
