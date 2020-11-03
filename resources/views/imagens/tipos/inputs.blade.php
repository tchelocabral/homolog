<div class="row div-item-form">
    <div class="col-md-12">
        <p class=""><b>Pertence ao Grupo:</b></p>
        @isset($detalhe)
            <p id="grupo" class="margemB20" >{{ $tipo_imagem->grupo->nome ?? 'Não Informado' }}</p>
        @else
            <select id="combo-grupos" name="grupo_id" class="form-control select2 margemT10">
                @unless($grupos_imgs)
                    <option value="">Sem Grupos Cadastrados</option>
                @else
                    @foreach($grupos_imgs as $gru)
                        @php $selecionado = '_'; @endphp
                        @isset($tipo_imagem->grupo_id)
                            @php $selecionado = $tipo_imagem->grupo_id == $gru->id ? 'selected=selected' : ''; @endphp
                        @endif
                        <option value="{{ $gru->id }}" {{ ' ' . $selecionado }} >{{$gru->nome }}</option>
                    @endforeach
                @endunless
            </select>
        @endif
    </div>
</div>

<div class="row div-item-form">
    <div class="col-md-12">
        <p class=""><b>Nome</b></p>
        @isset($detalhe)
            <p id="nome" class="margemB20" >{{ $tipo_imagem['nome'] ?? 'Não Informado' }}</p>
        @else
            <input type="text" name="nome" class="form-control" value="{{ $tipo_imagem['nome']  ?? old('nome') }}" placeholder="Digite aqui" >
        @endif
    </div>
</div>

<div class="row div-item-form">
    <div class="col-md-12">
        <p class=""><b>Descrição</b></p>
        @isset($detalhe)
            <p id="descricao" class="margemB20" >{{ $tipo_imagem['descricao'] ?? 'Não Informado' }}</p>
        @else
            <textarea name="descricao" class="col-md-12 form-control" value="{{ $tipo_imagem['descricao']  ?? old('descricao') }}" placeholder="" >{{ $tipo_imagem['descricao']  ?? old('descricao') }}</textarea>
        @endif
    </div>
</div>

<div class="row div-item-form">
    <div class="col-md-6">
        @isset($detalhe)
            @can('visualiza-valor')
                <p class=""><b>Valor Referência</b></p>
                <p id="valor" class="margemB20" >{{ $tipo_imagem['valor'] ?? 'Não Informado' }}</p>
            @endcan
        @else
            @can('insere-valor')
                <p class=""><b>Valor Referência</b></p>
                <input type="number" name="valor" class="form-control" value="{{ $tipo_imagem['valor']  ?? old('valor') }}" placeholder="R$ 0,00" step="0.01" >
            @endcan
        @endif
    </div>
</div>


{{-- Campos Personalizados --}}
<div class="row div-item-form margemT20">
    <div class="col-md-12">
        <h3>Campos Personalizados</h3>
    </div>
</div>

@if(empty($detalhe))
    <div class="row div-item-form margemT10">
        <div class="col-sm-6 col-md-6">
            <p><b>Nome do Campo</b></p>
            <input type="text" class="form-control" name="" id="nome-campo">
        </div>
        <div class="col-sm-6 col-md-6">
            <p><b>Tipo de Campo</b></p>
            <select id="combo-tipos-campos" name="" class="form-control select2">
                <option value="text">Texto</option>
                <option value="number">Número</option>
            </select>
        </div>
    </div>

    <div class="row div-item-form margemT10">
        <div class="col-md-6 pull-right">
            <button id="add-campo" class="btn btn-success btn-flat pull-right"><i class="fa fa-check"></i> ADD</button>
        </div>
    </div>
@endif

<div class="row div-item-form margemT10 margemB40">
    <div class="col-md-12">
        @if(empty($detalhe))
            <p><b>Campos:</b></p>
        @endif
        <table class="table" id="lista-campos">
            <thead>
            <tr>
                <th colspan="2">Nome</th>
                <th>Tipo</th>
                @if(empty($detalhe))
                    <th>Remover</th>
                @endif
            </tr>
            </thead>
            <tbody>
                @isset($tipo_imagem->campos_personalizados)
                    @forelse($tipo_imagem->campos_personalizados as $campo)
                        <tr class="conteudo-campo">
                            <td colspan="2">
                                <p>{{$campo['nome']}}</p>
                                <input type="hidden" class="campos-personalizados" name="campos_personalizados[{{$campo['nome']}}][nome]" value="{{$campo['nome']}}">
                            </td>
                            <td>
                                <p>{{$campo['tipo']}}</p>
                                <input type="hidden" class="campos-personalizados" name="campos_personalizados[{{$campo['nome']}}][tipo]" value="{{$campo['tipo']}}">
                            </td>
                            @if(empty($detalhe))
                                <td>
                                    <a id="remove-campo{{$loop->iteration}}" href="javascript:void(0);" class="btn btn-flat btn-danger remove-campo">
                                        <i class="fa fa-times"></i>
                                    </a>
                                </td>
                            @endif
                        </tr>
                    @empty

                    @endforelse
                @endif
            </tbody>
        </table>
    </div>
</div>


{{-- Controle dos campos --}}
@push('js')
    <script>
        $(document).ready(function () {

            // Remover Registro
            jQuery('#add-campo').on('click', function (e) {
                e.preventDefault();
                addCampo(e);
            });

            // Remover Registro
            jQuery('.remove-campo').on('click', function (e) {
                e.preventDefault();
                $(this).parents('.conteudo-campo').first().remove();
            });

            function addCampo(e){

                //  pega valor do campo
                var comboTipos           = document.getElementById("combo-tipos-campos");
                var tipoSelecionado      = comboTipos.options[ comboTipos.selectedIndex ];
                // var tipoSelecionadoValor = tipoSelecionado.value;
                var nome                 = document.getElementById('nome-campo').value;

                if(nome)

                // verifica qual o valor da última linha inserida e gera a próxima
                    var listaCampos = document.getElementById("lista-campos");
                var length  = $('.conteudo-campo').length;
                var campoID = length+1;

                // gera o html da nova linha
                var campo =  geraHtml(campoID, tipoSelecionado, nome);

                jQuery("#lista-campos").append(campo);
                jQuery("#remove-campo" + campoID).on("click", function(e){
                    $(this).parents('.conteudo-campo').first().remove();
                });
                limpaCampos();
            }
            function geraHtml(campoID, tipoSelecionado, nome){

                var campo = '<tr class="conteudo-campo">';
                campo    += '<td colspan="2">';
                campo    += '<p>' + nome + '</p>';
                campo    += '<input type="hidden" class="campos-personalizados" name="campos_personalizados[' + nome + '][nome]" value="' + nome + '">';
                campo    += '</td>';
                campo    += '<td>';
                campo    += '<p>' + tipoSelecionado.text + '</p>';
                campo    += '<input type="hidden" class="campos-personalizados" name="campos_personalizados[' + nome + '][tipo]" value="' + tipoSelecionado.value + '">';
                campo    += '</td>';
                campo    += '<td>';
                campo    += '<a id="remove-campo' + campoID + '" href="javascript:void(0);" class="btn btn-danger remove-campo">';
                campo    += '<i class="fa fa-times"></i>';
                campo    += '</a>';
                campo    += '</td>';
                campo    += '</tr>';

                return campo;
            }
            function limpaCampos(){
                // document.getElementById("combo-tipos-campos").selectedIndex = 0;
                document.getElementById("nome-campo").value = '';
                jQuery("#nome-campo").focus();
            }
        });
    </script>

@endpush

