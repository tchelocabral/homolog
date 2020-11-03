@extends('adminlte::page')

@section('title', 'Adicionar Arquivo a Imagem')

@section('content_header')
   {{-- Breadcrumbs::render('adicionar arquivo imagem', $projeto) --}}
@stop

@section('content')

    <div class="row larguraTotal">
        <form id="form-add-arquivo" name="form-add-arquivo" action="{{ route('imagem.vincular.arquivos') }}" method="POST" enctype="multipart/form-data">
            {{--security token--}}
            @csrf
            @isset($projetos)
                <input type="hidden" name="projeto_id" value="{{encrypt($projeto->id)}}">
            @endif
            
            <div class="row margemB40">
                <div class="col-md-8">
                    <h1 class="">Vincular Arquivos a Imagens</h1>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="box box-solid box-primary no-border com-shadow">
                        <div class="box-header com-borda th-ocean">
                            <h3 class="box-title">Selecione a imagem e os arquivos que deseja incluir nela</h3>
                        </div>
                        <div class="box-body box-profile com-shadow">
                            <div class="row">
                                {{-- Se vier com imagem explícita --}}
                                @isset($imagem)
                                    <div class="col-sm-12 col-md-3">
                                        <div class="row div-item-form">
                                            <div class="col-md-12">
                                                <h3 class="box-title">Dados da Imagem</h3>
                                                <hr>
                                                <input type="hidden" name="projeto_id" value="{{ $imagem->projeto->id }}">
                                                <p class="detalhe-label"><b>{{ __('messages.Nome do Projeto')}}</b></p>
                                                <p id="nome-projeto" class="margemB20 info-detalhe-maior" >
                                                    <a href="{{ route('projetos.show', encrypt($imagem->projeto->id)) }}" data-toggle="tooltip" title="Ir para o Projeto">
                                                        {{ $imagem->projeto->nome or  __('messages.Não Informado') }}
                                                    </a>
                                                </p>
                                                <input type="hidden" name="imagem_id" value="{{ $imagem->id }}">
                                                <p class="detalhe-label"><b>Nome da Imagem</b></p>
                                                <p id="nome-imagem" class="margemB20 info-detalhe-maior" >
                                                    {{ $imagem->nome or  __('messages.Não Informado') }}
                                                </p>
                                                <p class="detalhe-label"><b>Tipo</b></p>
                                                <p id="tipo-imagem" class="margemB20 info-detalhe-maior" >
                                                    {{ $imagem->tipo->nome . ' - ' . $imagem->tipo->grupo->nome }}
                                                </p>
                                                <p class="detalhe-label"><b>Descrição</b></p>
                                                <p id="imagem-descricao" class="margemB20 info-detalhe-maior" >
                                                    {{ $imagem->descricao or  __('messages.Não Informado') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="col-sm-12 col-md-12">
                                        <div class="row div-item-form">
                                            <div class="col-md-3">
                                                <h3 class="box-title no-margin">{{ __('messages.Nome do Projeto')}}</h3>
                                            </div>
                                            <div class="col-md-4">
                                                <select id="combo-projetos" name="projeto_id" class="form-control select2 margemT10" {{ isset($projeto) ? 'disabled' : '' }}>
                                                    @isset($projetos)
                                                        @unless($projetos)
                                                            <option value="">Sem Projetos Cadastrados</option>
                                                        @else
                                                            <option value="">Nenhum Projeto Selecionado</option>
                                                            @foreach($projetos as $prj)
                                                                <option value="{{ $prj->id }}" {{ isset($projeto) &&  $projeto->id == $prj->id ? 'selected="selected"' : '' }}>{{$prj->nome }}</option>
                                                            @endforeach
                                                        @endunless
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                            </div> 
                            <hr>
                            <div class="row">
                                    <div class="col-md-5">
                                        <h3 class="box-title">Imagens do Projeto</h3>
                                        <hr>
                                        <div class="row div-item-form">
                                            <div class="col-md-12">
                                                <table id="lista-imagens-projeto" class="table">
                                                    <thead>
                                                    <tr class="cyan">
                                                        <th><input type="checkbox" name="check-todas-imagens" id="check-todas-imagens"></th>
                                                        <th class="texto-branco">Imagem</th>
                                                        <th class="texto-branco">Tipo</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="col-sm-12 col-md-7">
                                    <h3 class="box-title">Arquivos do Projeto</h3>
                                    <hr>
                                    <div class="row div-item-form">
                                        <div class="col-md-12">
                                            <table id="lista-arquivos" class="table">
                                                <thead>
                                                    <tr class="cyan">
                                                        <th colspan="5" class="texto-branco">
                                                            <input type="checkbox" name="check-todos-arquivos" id="check-todos-arquivos"><span class="margemL10"></span> 
                                                            Todos
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @isset($imagem)
                                                        @if( !empty($imagem->projeto->arquivos) )
                                                            @foreach($imagem->projeto->arquivos as $arquivo)
                                                                <tr>
                                                                    <td>
                                                                        <input type="checkbox" id="arquivo{{ $arquivo->id }}" value="{{ $arquivo->id }}" class="id-item-arquivo" name="arquivos[]">
                                                                    </td>
                                                                    @if(pathinfo($arquivo->caminho, PATHINFO_EXTENSION ) == 'jpg' || pathinfo($arquivo->caminho, PATHINFO_EXTENSION) == 'png')
                                                                        <td><img src="{{ URL::asset($arquivo->caminho) }}" width="28" height="28" alt=""></td>
                                                                    @else
                                                                        <td><img src="{{"/icones/".pathinfo($arquivo->caminho, PATHINFO_EXTENSION)}}.png" width="28" height="28" alt="{{pathinfo($arquivo->caminho, PATHINFO_EXTENSION)}}"></td>
                                                                    @endif
                                                                    <td><p class="tipo-item-arquivo">{{ $arquivo->tipo_arquivo->nome }}</p></td>
                                                                    <td><p class="descricao-item-arquivo">{{ $arquivo->nome }}</p></td>
                                                                    <td><p class="descricao-item-arquivo">{{ $arquivo->nome_arquivo }}</p></td>
                                                                </tr>
                                                            @endforeach
                                                        @endif
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-success pull-right">Confirmar Arquivos</button>
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
            let tipoArquivos = [
                {nome: 'Arquitetura', valor: 'Arquitetura'},
                {nome: 'Decoração',   valor: 'Decoração'},
                {nome: 'Paisagismo',  valor: 'Paisagismo'},
                {nome: 'Referência',  valor: 'Referência'}];


            // Select Projetos
            $(document).on('change', '#combo-projetos', function (e) {
                this.value === "" ? limpaCampos() : preencheCampos(this);
            });
            function add_imagem(value) {
                var html =  '<tr class="item-imagem">';
                html     += '<td>';
                html     += '<input id="imagem' + value.id + '" type="checkbox" value="' + value.id + '" class="id-item-imagem" name="imagens[]">';
                html     += '</td>';
                html     += '<td>';
                html     += '<p class="nome-item-imagem">' + value.nome + '</p>';
                html     += '</td>';
                html     += '<td>';
                html     += '<p class="tipo-item-imagem">' + value.tipo.nome + ' - ' + value.tipo.grupo.nome + '</p>';
                html     += '</td>';
                html     += '</tr>';
                jQuery('#lista-imagens-projeto').append(html);
            }
            function add_arquivos(value) {
                var html ='<tr class="collapse in item-arquivo' + value.tipo_arquivo.nome + '">';
                html     += '<td>';
                html     += '<input type="checkbox" id="arquivo' + value.id +'" value="' + value.id + '" class="id-item-arquivo id-item-arquivo' + value.tipo_arquivo.nome  + '" name="arquivos[]">';
                html     += '</td>';
                html     += '<td>';
                if (value.caminho.split('.').pop() === 'jpg' || value.caminho.split('.').pop() === 'png') html += '<img src="' + url_base_storage + value.caminho  + '" width="28" height="28">'
                else html += '<img src="' + '/icones/' + value.caminho.split('.').pop() + '.png' + '" width="28" height="28">'
                html     += '</td>';
                html     += '<td>';
                html     += '<p class="tipo-item-arquivo">' + value.tipo_arquivo.nome + '</p>';
                html     += '</td>';
                html     += '<td>';
                html     += '<p class="nome-item-arquivo">' + value.nome + '</p>';
                html     += '</td>';
                html     += '<td>';
                html     += '<p class="nome-file-item-arquivo">' + value.nome_arquivo + '</p>';
                html     += '</td>';
                html     += '</tr>';
                jQuery('#template'+value.tipo_arquivo.nome).append(html);
            }
            function limpaCampos() {
                jQuery('.item-imagem').remove();
                jQuery('#lista-arquivos tbody').remove();
                jQuery('#combo-projetos').val("");
                jQuery('#combo-projetos').focus();
            }
            function preencheCampos(select) {
                var params = {id: select.value};
                var url    = "{{ route('projeto.imagens') }}";
                $.ajax({
                    url: url,
                    type: 'GET',
                    data: params,
                    beforeSend: function(xhr){
                    },
                    success: function (data) {
                        console.log(data);
                        $.each(data, function(key, value){
                           add_imagem(value);
                        });
                    },
                    error: function(data){
                        limpaCampos();
                    },
                    complete: function(){
                        jQuery('.id-item-imagem').iCheck({
                            checkboxClass: 'icheckbox_square-blue',
                            radioClass: 'iradio_square-blue'
                        });
                    }
                });

                var params_arquivos = {id: select.value};
                var url_arquivos    = "{{ route('projeto.arquivos') }}";
                $.ajax({
                    url: url_arquivos,
                    type: 'GET',
                    data: params_arquivos,
                    beforeSend: function(xhr){
                    },
                    success: function (data) {
                        console.log(data);
                        var html = ''
                        tipoArquivos.forEach(tipoArquivo => {
                            html = '<tbody class="clickable no-border">';
                            html += '<tr class="no-border">';
                            html += '<td colspan="5"  id="id'+ tipoArquivo.nome+'" aria-controls=".item-arquivo'+ tipoArquivo.nome + '" data-toggle="collapse" data-target=".item-arquivo'+ tipoArquivo.nome + '">';
                            html += '<span class="checkbox-blue">';
                            html += '<input type="checkbox" name="check-todos-arquivos-tipo' + tipoArquivo.nome +  '" id="check-todos-arquivos-tipo' + tipoArquivo.nome +  '"><span class="margemL10"></span>';
                            html += '</div>';
                            html += tipoArquivo.valor + '</td></tr>';
                            html += '</tbody>';
                            html += '<tbody id="template' + tipoArquivo.nome + '" class="no-border"></tbody>';
                            jQuery('#lista-arquivos').append(html);
                        });
                        $.each(data, function(key, value){
                           add_arquivos(value);
                        });
                    },
                    error: function(data){
                        limpaCampos();
                    },
                    complete: function(){
                        jQuery('.id-item-arquivo').iCheck({
                            checkboxClass: 'icheckbox_square-blue',
                            radioClass: 'iradio_square-blue'
                        });
                        jQuery('.checkbox-blue').iCheck({
                            checkboxClass: 'icheckbox_square-blue',
                            radioClass: 'iradio_square-blue'
                        });
                    }
                });

            }
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
                jQuery('#check-todos-arquivos-tipoArquitetura').iCheck('check');
                jQuery('#check-todos-arquivos-tipoDecoração').iCheck('check');
                jQuery('#check-todos-arquivos-tipoPaisagismo').iCheck('check');
                jQuery('#check-todos-arquivos-tipoReferência').iCheck('check');
                // var arquivos = jQuery('.id-item-arquivo');
                // $.each(arquivos, function (key, value) {
                //     console.log(value);
                //     value.checked = true;
                //     jQuery('#'+value.id).iCheck('update');
                //     jQuery('#'+value.id).iCheck('check');
                // });
            }
            jQuery('#lista-arquivos').on('ifChanged', '#check-todos-arquivos-tipoArquitetura', function (e) {
                this.checked ? selecionaTodosArquivosTipo('Arquitetura') : selecionaNenhumTipoArquivo('Arquitetura');
            });
            jQuery('#lista-arquivos').on('ifChanged', '#check-todos-arquivos-tipoDecoração', function (e) {
                this.checked ? selecionaTodosArquivosTipo('Decoração') : selecionaNenhumTipoArquivo('Decoração');
            });
            jQuery('#lista-arquivos').on('ifChanged', '#check-todos-arquivos-tipoPaisagismo', function (e) {
                this.checked ? selecionaTodosArquivosTipo('Paisagismo') : selecionaNenhumTipoArquivo('Paisagismo');
            });
            jQuery('#lista-arquivos').on('ifChanged', '#check-todos-arquivos-tipoReferência', function (e) {
                this.checked ? selecionaTodosArquivosTipo('Referência') : selecionaNenhumTipoArquivo('Referência');
            });
            function selecionaTodosArquivosTipo(tipo) {
                console.log(tipo)
                var arquivos = jQuery('.id-item-arquivo' + tipo);
                $.each(arquivos, function (key, value) {
                    console.log(value);
                    value.checked = true;
                    jQuery('#'+value.id).iCheck('update');
                    jQuery('#'+value.id).iCheck('check');
                });
            }
            function selecionaNenhumArquivo() {
                jQuery('#check-todos-arquivos-tipoArquitetura').iCheck('uncheck');
                jQuery('#check-todos-arquivos-tipoDecoração').iCheck('uncheck');
                jQuery('#check-todos-arquivos-tipoPaisagismo').iCheck('uncheck');
                jQuery('#check-todos-arquivos-tipoReferência').iCheck('uncheck');
                // var arquivos = jQuery('.id-item-arquivo');
                // $.each(arquivos, function (key, value) {
                //     console.log(value);
                //     value.checked = false;
                //     jQuery('#'+value.id).iCheck('update');
                //     jQuery('#'+value.id).iCheck('uncheck');
                // });
            }
            function selecionaNenhumTipoArquivo(tipo) {
                console.log(tipo)
                var arquivos = jQuery('.id-item-arquivo' + tipo);
                $.each(arquivos, function (key, value) {
                    console.log(value);
                    value.checked = true;
                    jQuery('#'+value.id).iCheck('update');
                    jQuery('#'+value.id).iCheck('uncheck');
                });
            }

            // Força evento change do combo projetos ca~so esteja selecionado
            if($('#combo-projetos option:selected').val() !== "" ){
                $('#combo-projetos').trigger("change");
            } 

            // Hover nas linhas de cabeçalho dos arquivos
            // jQuery('#lista-arquivos').on('hover', '.clickable tr td',
            //     function() {
            //         if (!$(this).children(':input')[0].checked) {
            //             $(this).children('.icheckbox_square-blue').addClass('hover');
            //         }
            //     },
            //     function(){
            //         if (!$(this).children(':input')[0].checked) {
            //             $(this).children('.icheckbox_square-blue').removeClass('hover');
            //         }
            //     }
            // );

        });
    </script>
@endpush
{{-- Fim do Controle de Carregamento de Imagem do Projeto --}}

