@extends('adminlte::page')

@section('title', 'Adicionar Arquivo a Imagem')

@section('content_header')
    {{--<h1 class="margemB40">Novo Tipo de Imagem</h1>--}}
@stop

@section('content')

    <div class="row centralizado">
        <div class="col-sm-12 col-md-6">
            <div class="box box-solid box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Selecione onde deseja incluir os arquivos</h3>
                </div>
                <div class="box-body">
                    <div class="col-md-12">
                        <input type="radio" class="" value="projeto" name="onde_incluir" id="radio_projeto">
                        <label for="radio-projeto" class="radioLabel"><span></span> Projeto</label>
                        <select id="combo-projetos" class="form-control" name="projeto_id">
                            @isset($projetos)
                                @unless($projetos)
                                    <option value="">Sem Projetos Cadastrados</option>
                                @else
                                    @foreach($projetos as $prj)
                                        <option value="{{ $prj->id }}">{{$prj->nome }}</option>
                                    @endforeach
                                @endif
                            @else
                                <option value="">Sem Projetos Cadastrados</option>
                            @endif
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row largura90 centralizado">
        <form id="form-add-arquivo" name="form-add-arquivo" action="{{ route('projeto.gravar.arquivo') }}" method="POST" enctype="multipart/form-data">
            {{--security token--}}
            @csrf
            <div class="row margemB40">
                <div class="col-md-8">
                    <h1 class="">Adicionar Arquivos</h1>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="box box-solid box-primary no-border">
                        <div class="box-header with-border">
                            <h3 class="box-title">Selecione a imagem e os arquivos que deseja incluir nela</h3>
                        </div>
                        <div class="box-body box-profile">

                            @isset($imagem)
                                <div class="col-sm-12 col-md-3">
                                    <div class="row div-item-form">
                                        <div class="col-md-12">
                                            <h3 class="box-title">Dados da Imagem</h3>
                                            <hr>
                                            {{--                                            @include('projeto.inputs', ['projeto' => $projeto, 'detalhe' => true])--}}
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="col-sm-12 col-md-3">
                                    <div class="row div-item-form">
                                        <div class="col-md-12">
                                            <h3 class="box-title">Selecione o(s) Arquivos que estão no Projeto</h3>
                                            <hr>
                                            <div class="row div-item-form">
                                                <div class="col-md-12">
                                                    <p><b>Lista de Arquivos</b></p>
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
                            <div class="col-sm-12 col-md-4">
                                <h3 class="box-title">Dados da Arquivo</h3>
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
                                    </div>
                                </div>
                                <hr>
                                <div class="row div-tem-form">
                                    <div class="col-md-2 pull-right">
                                        <a id="add-arquivo" class="btn btn-success pull-right" href="javascript:void(0);">
                                            <i class="fa fa-check" aria-hidden="true"></i>
                                            Add
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-5">
                                <p class="text-right"><b>Lista de Arquivos que serão incluídas no Projeto:</b></p>
                                <table class="table" id="lista-arquivos">
                                    <thead>
                                    <tr>
                                        <th colspan="2">Tipo de Arquivo</th>
                                        <th>Revisão_00</th>
                                        <th>Valor</th>
                                        @if(empty($detalhe))
                                            <th>Remover</th>
                                        @endif
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="box-footer">
                            @isset($projeto)
                                <a class="btn btn-info pull-left">Mais Detalhes do Projeto</a>
                            @endif
                            <button type="submit" class="btn btn-success pull-right">Confirmar Imagens</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

@stop

{{-- Controle das Imagem --}}
@push('js')
    <script>
        $(document).ready(function () {

            // ADD Ref
            jQuery('#add-ref').on('click', function (e) {
                e.preventDefault();
                addReferencia(e);
            });
            // Remover Ref
            jQuery('.remove-ref').on('click', function (e) {
                e.preventDefault();
                $(this).parents('.conteudo-ref').first().remove();
            });

            // ADD Imagem
            jQuery('#add-imagem').on('click', function (e) {
                e.preventDefault();
                addImagem(e);
            });
            // Remover Imagem
            jQuery('.remove-imagem').on('click', function (e) {
                e.preventDefault();
                $(this).parents('.conteudo-imagem').first().remove();
            });

            function addImagem(e){

                //  pega valor do campo
                var comboTipo       = document.getElementById("combo-tipo-imagem");
                var tipoSelecionado = comboTipo.options[ comboTipo.selectedIndex ];
                var tipoID          = tipoSelecionado.value;
                var data_revisao    = document.getElementById('data_revisao').value;
                var valor           = document.getElementById('valor').value;
                // var referencias     = $('.refs_input');

                // verifica qual o valor da última linha inserida e gera a próxima
                var listaImagens = document.getElementById("lista-arquivos");
                var length  = $('.conteudo-imagem').length;
                var imagemID = length+1;

                // gera o html da nova linha do nova imagem
                var imagem =  geraHtmlImagem(imagemID, tipoSelecionado, tipoID, data_revisao, valor);
                // console.log(tecnico);

                jQuery("#lista-arquivos").append(imagem);
                jQuery("#remove-imagem" + imagemID).on("click", function(e){
                    $(this).parents('.conteudo-imagem').first().remove();
                });

                limpaCampos();

            }
            function geraHtmlImagem(imagemID, tipoSelecionado, tipoID, data_revisao, valor){

                var imagem = '<tr class="conteudo-imagem">';
                imagem    += '<td colspan="2">';
                imagem    += '<p>' + tipoSelecionado.text + '</p>';
                imagem    += '<input type="hidden" class="arquivos" name="arquivos[' + imagemID + '][imagem_tipo_id]" value="' + tipoID + '">';
                imagem    += '<input type="hidden" class="arquivos" name="arquivos[' + imagemID + '][nome]" value="' + tipoSelecionado.text + '">';
                imagem    += '</td>';
                imagem    += '<td>';
                imagem    += '<p>' + data_revisao + '</p>';
                imagem    += '<input type="hidden" class="arquivos" name="arquivos[' + imagemID + '][data_revisao]" value="' + data_revisao + '">';
                imagem    += '</td>';
                imagem    += '<td>';
                imagem    += '<p>' + valor + '</p>';
                imagem    += '<input type="hidden" class="arquivos" name="arquivos[' + imagemID + '][valor]" value=' + valor +'>';
                imagem    += '</td>';
                imagem    += '<td>';
                imagem    += '<a id="remove-imagem' + imagemID + '" href="javascript:void(0);" class="btn btn-danger remove-imagem">';
                imagem    += '<i class="fa fa-times"></i>';
                imagem    += '</a>';
                imagem    += '</td>';
                imagem    += '</tr>';

                return imagem;
            }
            function limpaCampos(){
                document.getElementById("combo-tipo-imagem").selectedIndex = 0;
                document.getElementById("data_revisao").value = '';
                var midias = jQuery('.conteudo-ref');
                $.each(midias, function (index, value) {
                    value.remove();
                });
            }

        });
    </script>
@endpush
{{-- Fim do Controle de Imagem --}}

