@extends('adminlte::page')

@section('title', 'Novo Grupo de Imagem')

@section('content_header')
    {{ Breadcrumbs::render('novo grupo imagem') }}
@stop

@section('content')

    <div class="row largura90 centralizado">

        <form id="form-projeto" name="form-projeto" action="{{ route('grupo-imagem.store') }}" method="POST" enctype="multipart/form-data">

            @csrf
            <div class="row margemB40">
                <div class="col-md-8">
                    <h1 class="">Novo Grupo de Imagem</h1>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12 col-md-6">
                    <div class="box box-solid box-primary com-shadow">
                        <div class="box-header with-border th-ocean com-borda">
                            <h3 class="box-title">Preencha os campos com os dados do Grupo de Imagem</h3>
                        </div>
                        <div class="box-body">
                            <div class="col-md-12">
                                <h3 class="box-title">Dados do Grupo de Imagem</h3>
                                <hr>

                                <div class="row div-item-form">
                                    <div class="col-md-12">
                                        <p><b>Nome</b></p>
                                        @isset($detalhe)
                                            <p id="nome" class="margemB20" >{{ $grupo['nome'] ?? old('nome')  }} </p>
                                        @else
                                            <input type="text" name="nome" id="nome" class="form-control" value="{{ $grupo['nome'] ?? old('nome') }}" placeholder="" />
                                        @endisset
                                    </div>

                                    <div class="col-md-12 margemT10">
                                        <p><b>Descrição</b></p>
                                        @isset($detalhe)
                                            <p id="descricao" class="margemB20" >{{ $grupo['descricao'] ?? old('descricao')  }} </p>
                                        @else
                                            <textarea id="descricao" name="descricao" class="form-control">{{ $grupo['descricao']  ?? old('descricao') }}</textarea>
                                        @endisset
                                    </div>

                                    <div class="col-md-12 margemT10">
                                        <p><b>Observações</b></p>
                                        @isset($detalhe)
                                            <p id="observacoes" class="margemB20" >{{ $grupo['observacoes'] ?? old('observacoes')  }} </p>
                                        @else
                                            <textarea id="observacoes" name="observacoes" class="form-control">{{ $grupo['observacoes']  ?? old('observacoes') }}</textarea>
                                        @endisset
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer footer-com-padding">
                            <button type="submit" class="btn btn-success pull-right">Salvar Grupo de Imagem</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

@stop


 {{--Controle da Lista de Imagem--}}
@push('js')
    <script>
        $(document).ready(function () {

            // Remover Registro
            jQuery('#add-imagem').on('click', function (e) {
                e.preventDefault();
                addImagem(e);
            });

            // Remover Registro
            jQuery('.remove-imagem').on('click', function (e) {
                e.preventDefault();
                $(this).parents('.conteudo-imagem').first().remove();
            });


            function addImagem(e){

                //  pega valor do campo
                var comboTipo       = document.getElementById("combo-tipo-imagem");
                var tipoSelecionado = comboTipo.options[ comboTipo.selectedIndex ];
                var tipoID = tipoSelecionado.value;
                var data_revisao    = document.getElementById('data_revisao').value;
                // var caminho_arquivo = document.getElementById('ref_caminho').value;

                // verifica qual o valor da última linha inserida e gera a próxima
                var listaImagens = document.getElementById("lista-imagens");
                var length  = $('.conteudo-imagem').length;
                var imagemID = length+1;

                // gera o html da nova linha do novo tecnico
                var imagem =  geraHtmlTecnico(imagemID, tipoSelecionado, tipoID, data_revisao);
                // console.log(tecnico);

                jQuery("#lista-imagens").append(imagem);
                jQuery("#remove-imagem" + imagemID).on("click", function(e){
                    $(this).parents('.conteudo-imagem').first().remove();
                });
                limpaCampos();
            }
            function geraHtmlTecnico(imagemID, tipoSelecionado, tipoID, data_revisao){

                var imagem = '<tr class="conteudo-imagem">';
                imagem    += '<td colspan="2">';
                imagem    += '<p>' + tipoSelecionado.text + '</p>';
                imagem    += '<input type="hidden" class="imagens" name="imagens[' + imagemID + '][imagem_tipo_id]" value="' + tipoID + '">';
                imagem    += '<input type="hidden" class="imagens" name="imagens[' + imagemID + '][nome]" value="' + tipoSelecionado.text + '">';
                imagem    += '<input type="hidden" class="imagens" name="imagens[' + imagemID + '][observacoes]" value="Adicionada na criação do projeto.">';
                imagem    += '</td>';
                imagem    += '<td>';
                imagem    += '<p>' + data_revisao + '</p>';
                imagem    += '<input type="hidden" class="imagens" name="imagens[' + imagemID + '][data_revisao]" value="' + data_revisao + '">';
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
                document.getElementById("ref_caminho").value = '';
            }
        });
    </script>

@endpush