

<style type="text/css">
    
    .com-margem-top {
        margin-top: -35px;
    }

    .tamanho-menor {
        width: 20px;
    }

</style>

<div class="row">

    {{-- Thumb --}}
    
    @if(1==2)
        @isset($detalhe)
            <div class="col-md-12 margemB20">
                <div class="row">
                    <div class="col-md-12">
                        <div class="image-wrap">
                            <img src="{{ Storage::url($deliveryformat['imagem']) ?? Storage::url('imagens/deliveryformats/tipo-padrao.png') }}" alt="Thumbnail" height="56" width="56">
                        </div>
                    </div>
                </div>
            </div>
        @else
            
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-12">
                        @isset($deliveryformat)
                            <div class="image-wrap">
                                <img src="{{ Storage::url($deliveryformat['imagem']) ?? Storage::url('imagens/deliveryformats/tipo-padrao.png') }}" alt="Thumbnail" height="56" width="56">
                            </div>
                        @endisset
                    </div>
                </div>
            </div>

            <!-- image-preview-filename input [CUT FROM HERE]-->
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-12">
                        <p class="larguraTotal texto-direto"><b>Thumbnail</b></p>
                        <input id="input-arquivo-thumb-deliveryformat" name="imagem" type="file" accept="image/png, image/jpeg, image/gif"  />
                        {{-- <div class="input-group image-preview margemT20 margemB20">
                            <input id="arquivo-preview-thumb-deliveryformat" type="text" class="form-control image-preview-filename" disabled="disabled" placeholder="{{ __('messages.Nenhum arquivo selecionado') . ' (56x56)' }}" />
                            <span class="input-group-btn">
                                
                                <!-- image-preview-input -->
                                <div class="btn btn-default image-preview-input">
                                    <span class="glyphicon glyphicon-folder-open"></span>
                                    <span class="image-preview-input-title">{{ __('messages.Procurar')}}</span>
                                    <input id="input-arquivo-thumb-deliveryformat" name="imagem" type="file" accept="image/png, image/jpeg, image/gif"  />
                                </div>
                                <!-- image-preview-clear button -->
                                <button type="button" class="btn btn-default image-preview-clear" >
                                    <span class="glyphicon glyphicon-remove"></span> {{ __('messages.Limpar')}}
                                </button>
                            </span>
                            <input id="arquivo" type="file" accept="*"  style="position: absolute; top: 0px; right: 1000vw;" /> 
                        </div> --}}
                    </div>
                </div>
            </div>
        @endif
    @endif
    
    {{-- Inputs --}}
    <div class="col-md-6">
        <div class="row div-item-form">
            <div class="col-md-12">
                <p class=""><b>{{ __('messages.Nome') }}</b></p>
                @isset($detalhe)
                    <p id="nome" class="margemB20" >{{ $deliveryformat['nome'] ?? 'Não Informado' }}</p>
                @else
                    <input type="text" name="nome" class="form-control" value="{{ $deliveryformat['nome']  ?? old('nome') }}" placeholder="{{ __('messages.Digite aqui') }}" >
                @endif
            </div>
        </div>

        <div class="row div-item-form">
            <div class="col-md-12">
                <p class=""><b>{{ __('messages.Descrição') }}</b></p>
                @isset($detalhe)
                    <p id="descricao" class="margemB20" >{{ $deliveryformat['descricao'] ?? 'Não Informado' }}</p>
                @else
                    <textarea name="descricao" class="col-md-12 form-control" value="{{ $deliveryformat['descricao']  ?? old('descricao') }}" placeholder="" >{{ $deliveryformat['descricao']  ?? old('descricao') }}</textarea>
                @endif
            </div>
        </div>

        {{-- <div class="row div-item-form">
            <div class="col-md-12">
                @isset($detalhe)
                    @if($deliveryformat->revisao)
                        <p><b>{{ __('messages.Esse Job é de Revisão') }}</b></p>
                    @elseif($deliveryformat->finalizador)
                        <p><b>{{ __('messages.Esse Job define Finalizador') }}</b></p>
                    @elseif($deliveryformat->gera_custo)
                        <p><b>{{ __('messages.Esse Job gera custo ao Cliente') }}</b></p>
                    @endif
                @else
                    <div class="row margemBottom">
                        <div class="col-md-6">
                            <p class=""><b>Selecione as Opções</b></p>
                            <input type="checkbox" name="revisao" value="1" {{ isset($deliveryformat) && $deliveryformat->revisao ? 'checked="checked"' : ''}}> {{ __('messages.Job de Revisão') }}  
                       </div>
                        <div class="col-md-6">
                            <input type="checkbox" name="finalizador" value="1" {{ isset($deliveryformat) && $deliveryformat->finalizador ? 'checked="checked"' : ''}}> {{ __('messages.Job define Finalizador') }}<br> 
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <input type="checkbox" name="gera_custo" value="1" {{ isset($deliveryformat) && $deliveryformat->gera_custo ? 'checked="checked"' : ''}}> {{ __('messages.Adicionar Custo ao Cliente') }}  
                        </div>
                  
                        <div class="col-md-6">
                            <input type="checkbox" name="solicite_hr" value="1" {{ isset($deliveryformat) && $deliveryformat->solicite_hr ? 'checked="checked"' : ''}}> {{ __('messages.Solicite HR') }}  
                        </div>
                    </div>

                @endif
            </div>
        </div> --}}


        @if(empty($detalhe))@endif

        {{-- Campos Personalizados --}}
        @isset($campos_separado) {{-- para box separado como opção --}}
        <!-- coloquei esse end if, pois o isset nao tem um, acredito que seja o que faltava -->
        @endif

        <div class="row div-item-form margemT20">
            <div class="col-md-12 com-margem-top">
                <h3>{{ __('messages.Campos Personalizados') }}</h3>
            </div>
        </div>

        @if(empty($detalhe))
            <div class="row div-item-form margemT10">
                <div class="col-sm-6 col-md-6">
                    <p><b>{{ __('messages.Nome do Campo') }}</b></p>
                    <input type="text" class="form-control" name="" id="nome-campo">
                </div>
                <div class="col-sm-6 col-md-6">
                    <p><b>{{ __('messages.Tipo de Campo') }}</b></p>
                    <select id="combo-tipos-campos" name="" class="form-control select2">
                        {{--<option value="checkbox">Checkbox</option>--}}
                        <option value="number">{{ __('messages.Number') }}</option>
                        <option value="text">{{ __('messages.Text') }}</option>
                        <option value="textarea">{{ __('messages.Texto Longo') }}</option>
                        <option value="select">{{ __('messages.Texto Multipla Escolha') }}</option>
                    </select>
                </div>

                <div class="col-md-6 pull-right margemT10">
                    <button id="add-campo" class="btn btn-success btn-flat pull-right"><i class="fa fa-check"></i> {{ __('messages.ADD') }}</button>
                </div>


                <div class="col-sm-6 col-md-12" id="div-select">
                    <table class="table" id="lista-options-campos">
                        <thead>
                        <tr>
                            <th colspan="2">{{ __('messages.Valor') }}</th>
                            <th>{{ __('messages.Select') }}</th>
                            @if(empty($detalhe))
                                <th>{{ __('messages.Adicionar') }}</th>
                                <th>{{ __('messages.Remover') }}</th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>

                </div>


            </div>

            <div class="row div-item-form margemT10">
                {{-- <div class="col-md-6 pull-right">
                    <button id="add-campo" class="btn btn-success btn-flat pull-right"><i class="fa fa-check"></i> {{ __('messages.ADD') }}</button>
                </div> --}}
            </div>
        @endif

    </div>
    {{-- Campos personalizados --}}
    <div class="col-md-6 cor-gelo">
        <div class="row div-item-form margemT10 margemB40">
            <div class="col-md-6">
                @if(empty($detalhe))

                @endif
                <table class="table" id="lista-campos">
                    <thead>
                    <tr>
                        <th colspan="2">{{ __('messages.Nome') }}</th>
                        <th>{{ __('messages.Tipo') }}</th>
                        <th>{{ __('messages.Opções') }}</th>
                        @if(empty($detalhe))
                            <th>{{ __('messages.Remover') }}</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    @isset($deliveryformat->campos_personalizados)
                        @forelse($deliveryformat->campos_personalizados as $campo)
                            @isset($campo["options"])
                                @foreach($campo['options'] as $options)
                                    <tr class="conteudo-campo">
                                        <td colspan="2">
                                            <p>{{$campo['nome']}}</p>
                                            <input type="hidden" class="campos-personalizados" name="campos_personalizados[{{$campo['nome']}}][nome]" value="{{$campo['nome']}}">
                                        </td>
                                        <td>
                                            <p>{{ $campo['tipo'] == 'textarea' ? 'Texto Longo' : ucfirst($campo['tipo']) }}</p>
                                            <input type="hidden" class="campos-personalizados" name="campos_personalizados[{{$campo['nome']}}][tipo]" value="{{$campo['tipo']}}">
                                        </td>
                                            <input type="hidden" class="campos-personalizados" name="campos_personalizados[{{$campo['nome']}}][options][]" value="{{$campo['tipo']}}">

                                        <td>
                                            <p>{{ $options }}</p>
                                            
                                        </td>
                                        @if(empty($detalhe))
                                            <td>
                                                <a id="remove-campo{{$loop->iteration}}" href="javascript:void(0);" class="btn btn-flat btn-danger remove-campo">
                                                    <i class="fa fa-times"></i>
                                                </a>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            @else
                                <tr class="conteudo-campo">
                                    <td colspan="2">
                                        <p>{{$campo['nome']}}</p>
                                        <input type="hidden" class="campos-personalizados" name="campos_personalizados[{{$campo['nome']}}][nome]" value="{{$campo['nome']}}">
                                    </td>
                                    <td>
                                        <p>{{ $campo['tipo'] == 'textarea' ? 'Texto Longo' : ucfirst($campo['tipo']) }}</p>
                                        <input type="hidden" class="campos-personalizados" name="campos_personalizados[{{$campo['nome']}}][tipo]" value="{{$campo['tipo']}}">
                                    </td>
                                    <td>

                                    </td>
                                    @if(empty($detalhe))
                                        <td>
                                            <a id="remove-campo{{$loop->iteration}}" href="javascript:void(0);" class="btn btn-flat btn-danger remove-campo">
                                                <i class="fa fa-times"></i>
                                            </a>
                                        </td>
                                    @endif
                                </tr>

                            @endif

                        @empty

                        @endforelse
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>


</div>


{{-- Controle dos campos --}}
@push('js')
    <script src="{{ asset('js/arquivos.js') }}"></script>
    {{-- <script src="{{ asset('js/imagens.js') }}"></script> --}}
    <script>

        $(document).ready(function() {

            // Add Registro
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
                var tipoSelecionado      = comboTipos.options[ comboTipos.selectedIndex ].value;
                // var tipoSelecionadoValor = tipoSelecionado.value;
                var nome                 = document.getElementById('nome-campo').value;

                if(nome)

                // verifica qual o valor da última linha inserida e gera a próxima
                var listaCampos = document.getElementById("lista-campos");
               
                var campo = "";
                if(tipoSelecionado!="select") {
                    var length  = $('.conteudo-campo').length;
                    var campoID = length+1;
                    
                    adicionarCampoLista(campoID, tipoSelecionado, nome, null)
                }
                else {
                    var length  = $('.conteudo-campo-option').length;
                    var campoID = length+1;

                    campo = geraHtmlOption(campoID, tipoSelecionado, nome);

                    jQuery("#lista-options-campos").append(campo);
                    
                    jQuery("#add-option-campo" + campoID).on('click', function (e) {
                        e.preventDefault();
                        var optioncampo     = document.getElementById("option-campo"+campoID).value;
                        var tipoSelecionado = document.getElementById("select-campo"+campoID).value;
                        
                        var tipoCampo = document.getElementById("tipo-select-campo"+campoID).value;
                        
                        // addCampoOption(campoID, tipoCampo, tipoSelecionado, optioncampo);
                                 
                        adicionarCampoLista(campoID, tipoCampo, nome, optioncampo)
                        $(this).parents('.conteudo-campo-option').first().remove();
                    });
                    
                    jQuery("#remove-option-campo" + campoID).on("click", function(e){
                        $(this).parents('.conteudo-campo-option').first().remove();
                    });
                }
            }


            function adicionarCampoLista(campoID, tipoSelecionado, nome, option){
                // gera o html da nova linha do novo tecnico
                campo =  geraHtmlTecnico(campoID, tipoSelecionado, nome, option);
                    // console.log(tecnico);
                    jQuery("#lista-campos").append(campo);
                    jQuery("#remove-campo" + campoID).on("click", function(e){
                        $(this).parents('.conteudo-campo').first().remove();
                    });
                    limpaCampos();

            }

            function geraHtmlTecnico(campoID, tipoSelecionado, nome, option){

                var campo = '<tr class="conteudo-campo">';
                campo    += '<td colspan="2">';
                campo    += '<p>' + nome + '</p>';
                campo    += '<input type="hidden" class="campos-personalizados" name="campos_personalizados[' + nome + '][nome]" value="' + nome + '">';
                campo    += '</td>';
                campo    += '<td>';
                campo    += '<p>' + tipoSelecionado + '</p>';
                campo    += '<input type="hidden" class="campos-personalizados" name="campos_personalizados[' + nome + '][tipo]" value="' + tipoSelecionado + '">';
                campo    += '</td>';
                campo    += '<td>';
                 if(option!=null){
                    campo    += '<p>' + option + '</p>';
                    campo    += '<input type="hidden" class="campos-personalizados" name="campos_personalizados[' + nome + '][options][]" value="' + option + '">';
                }
                campo    += '</td>';
                campo    += '<td>';
                campo    += '<a id="remove-campo' + campoID + '" href="javascript:void(0);" class="btn btn-danger remove-campo">';
                campo    += '<i class="fa fa-times"></i>';
                campo    += '</a>';
                campo    += '</td>';
                campo    += '</tr>';

                return campo;
            }


            function geraHtmlOption(campoID, tipoSelecionado, nome){

                var campo = '<tr class="conteudo-campo-option">';
                    campo    += '<td colspan="2">';
                    campo    += '<input type="text" class="form-control" id="option-campo'+campoID+'" name="campos_option_perso[' + nome + '][nome]" value="">';
                    campo    += '</td>';
                    campo    += '<td align="center">';
                    campo    += nome+'<input type="hidden" id="select-campo'+campoID+'"  class="campos-personalizados" name="campos_personalizados[' + nome + '][tipo]" value="' + nome + '">';
                    campo    += '<input type="hidden" id="tipo-select-campo'+campoID+'"  class="campos-personalizados" name="tpo_campos_personalizados[' + nome + '][tipo]" value="select">';
                    campo    += '</td>';
                    campo    += '<td align="center">';
                    campo    += '<a id="add-option-campo' + campoID + '" href="javascript:void(0);" class="btn btn-success add-campo-option">';
                    campo    += '<i class="fa fa-times"></i>';
                    campo    += '</a>';
                    campo    += '</td>';
                    campo    += '<td align="center">';
                    campo    += '<a id="remove-option-campo' + campoID + '" href="javascript:void(0);" class="btn btn-danger remove-campo-option">';
                    campo    += '<i class="fa fa-times"></i>';
                    campo    += '</a>';
                    campo    += '</td>';
                    campo    += '</tr>';

                return campo;
            }

            function limpaCampos(){
                // document.getElementById("combo-tipos-campos").selectedIndex = 0;
                document.getElementById("nome-campo").value = '';
                jQuery('#nome-campo').focus();
            }

            /////// Ordem das Tasks //////////
           function geraHTMLTaskParaOrdenar(task_id, task) {
                $('#lista-tasks > tbody:last-child').append(
                    '<tr id="task-' + task_id + '" class="task-arrastavel">' +
                        '<td class="texto-centralizado">' + 
                            '<i class="fa fa-bars"></i>' +
                        '</td>' +  
                        
                        '<td>' + task + 
                            '<input type="hidden" value="' + task_id + '" name="tasks[]" />' +
                        '</td>' + 
                    '</tr>'
                );
            }

            $('#combo-tasks').on("select2:select", function(e) { 
                var task_id = e.params.data.id;
                var task    = e.params.data.text;
                geraHTMLTaskParaOrdenar(task_id, task);
            });

            $('#combo-tasks').on("select2:unselect", function(e) { 
                jQuery("#task-" + e.params.data.id).remove();
            });

            // Drag and Drop Table Row
            $("#lista-tasks > tbody").sortable({cursor: "grabbing"});

        }); //end document ready

    </script>

@endpush
