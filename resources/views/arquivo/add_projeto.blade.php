@extends('adminlte::page')

@section('title', 'Adicionar Arquivo a Projeto')

@section('content_header')
   {{ Breadcrumbs::render('adicionar arquivo', $projeto) }}
@stop

@section('content')

    <div class="row centralizado">
        <form id="form-add-arquivo" name="form-add-arquivo" action="{{ route('projeto.gravar.arquivo', encrypt($projeto->id)) }}" method="POST" enctype="multipart/form-data">
        
            @csrf
            <div class="row">
                <div class="col-md-8">
                    <h1 class="">Adicionar Arquivos ao Projeto</h1>
                </div>
            </div>
            <div class="row margemB10">
                <div class="col-md-12">
                    <a href="{{ route('projeto.vincular.img.arquivo', encrypt($projeto->id)) }}" class="btn cyan" title="Vincular arquivos e imagens" data-toggle="tooltip">
                        <i class="fa fa-paperclip" aria-hidden="true"></i> 
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-solid box-primary no-border com-shadow">
                        <div class="box-header com-borda th-ocean">
                            <h3 class="box-title">Selecione o tipo de arquivo e o arquivo que deseja incluir no Projeto</h3>
                        </div>
                        <div class="box-body box-profile">

                            @isset($projeto)
                                <div class="col-sm-12 col-md-5">
                                    <div class="row div-item-form">
                                        <div class="col-md-12">                                           
                                            <input type="hidden" name="projeto_id" value="{{ encrypt($projeto->id)}}">
                                            <p class="detalhe-label"><b>{{ __('messages.Nome do Projeto')}}</b></p>
                                            <p id="nome" class="margemB20 info-detalhe-maior" >
                                                <a href="{{ route('projetos.show', encrypt($projeto->id)) }}" data-toggle="tooltip" title="Ir para o Projeto">
                                                    {{ $projeto['nome'] or  __('messages.Não Informado') }}
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
                                                    <input id="input-arquivo" type="file" accept="*" name="lista_arquivos[]" multiple />
                                                </div>
                                            </span>
                                                <input id="lista_arquivos" type="file" accept="*" name="lista_arquivos[]" multiple style="position: absolute; top: 0px; right: 1000px; ">
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row div-tem-form">
                                        <div class="col-md-2 pull-right">
                                            <button id="add_arq" class="btn btn-success pull-right" type="submit">
                                                <i class="fa fa-plus" aria-hidden="true"></i>
                                                Adicionar ao Projeto
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="col-sm-12 col-md-3">
                                    <div class="row div-item-form">
                                        <div class="col-md-12">
                                            <h3 class="box-title">Selecione o Projeto</h3>
                                            <hr>
                                            <div class="row div-item-form">
                                                <div class="col-md-12">
                                                    <p><b>Lista de Clientes</b></p>
                                                    <select id="combo-projetos" name="projeto_id" class="form-control select2 margemT10">
                                                        @isset($clientes)
                                                            @unless($clientes)
                                                                <option value="">Sem Clientes Cadastrados</option>
                                                            @else
                                                                @foreach($clientes as $cli)
                                                                    <option value="{{ $cli->id }}">{{$cli->nome_fantasia }}</option>
                                                                @endforeach
                                                            @endunless
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row div-item-form">
                                                <div class="col-md-12">
                                                    <p><b>Qual o Projeto ?</b></p>
                                                    <select id="combo-projetos" name="projeto_id" class="form-control select2 margemT10">
                                                        @isset($projetos)
                                                            @unless($projetos)
                                                                <option value="">Sem Projetos Cadastrados</option>
                                                            @else
                                                                @foreach($projetos as $proj)
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

                            <div class="col-sm-12 col-md-6 col-md-offset-1">
                                <p class=""><b>Lista de Arquivos do Projeto:</b></p>
                                <table class="table" id="lista-arquivos">
                                    <thead>
                                        <tr>
                                            <th>Tipo</th>
                                            <th>Nome Arquivo</th>
                                            <th><center>Opções</center></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @empty(!$projeto->arquivos)
                                        @foreach($projeto->arquivos as $midia)
                                            <tr>
                                                <td>{{ $midia->tipo_arquivo->nome }}</td>
                                                <td style="max-width: 300px; word-wrap: break-word;">{{ $midia->nome_arquivo }}</td>
                                                <td>
                                                    <center>
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
                                                                        href="{{ action('ProjetoController@desvincularArquivos', ['arquivo' => encrypt($midia->id), 'projeto' => encrypt($projeto->id)])}}"
                                                                        class="desvincular-hidden">
                                                                    </a>
                                                                    <a class="desvincular">
                                                                        <i class="fa fa-close" aria-hidden="true"></i> Remover Arquivo
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </center>
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

@stop

@include('app.includes.carregando')



{{-- Controle dos Arquivos --}}
@push('js')
    <script src="{{ asset('js/arquivos.js') }}"></script>
    <script>
        $(document).ready(function () {
            // ADD Arquivo
            jQuery('#add_arq_OLD_191029').on('click', function (e) {
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
                var nome_arquivo    = tipoSelecionado.text;
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
                    formData.append('projeto_id', {{ $projeto->id }});

                    var params = {tipo_arquivo: tipoID, nome: nome_arquivo, arquivo: arquivo, projeto_id: {{ $projeto->id  }} };
                    var url = "{{ route('projeto.gravar.arquivo', encrypt($projeto->id)) }}";

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
                arq   += '<div class="dropdown">';
                arq   += '<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">';
                arq   += '<i class="fa fa-cog" aria-hidden="true"></i>';
                arq   += '</a>';
                arq   += '<ul class="dropdown-menu" aria-labelledby="dropdownMenu' + arquivo_prj.id + '">';
                arq   += '<li>';
                arq   += '<a href="' + url_base_storage + arquivo_prj.caminho + '" download>';
                arq   += '<i class="fa fa-download" aria-hidden="true"></i> Baixar';
                arq   += '</a>';
                arq   += '</li>';
                arq   += '<li>';
                arq   += '<a href="' + url_base_storage + arquivo_prj.caminho + '" target="_blank">';
                arq   += '<i class="fa fa-eye" aria-hidden="true"></i> Visualizar';
                arq   += '</a>';
                arq   += '</li>';
                arq   += '<li>';
                arq   += '<a class="remove-arquivo" href="javascript:void(0);">';
                arq   += '<i class="fa fa-times"></i> Remover Arquivo';
                arq   += '</a>';
                arq   += '</li>';
                arq   += '</ul>';
                arq   += '</div>';
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
                // $('#nome-arquivo').val("");
                $('#combo-tipo-arquivo').focus()
            }
        });

    </script>
@endpush
{{-- Fim do Controle de Arquivos --}}
