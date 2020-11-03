

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
    @isset($detalhe)
        <div class="col-md-12 margemB20">
            <div class="row">
                <div class="col-md-12">
                    <div class="image-wrap">
                        <img src="{{ Storage::url($tipojob['imagem']) ?? Storage::url('imagens/tipojobs/tipo-padrao.png') }}" alt="Thumbnail" height="56" width="56">
                    </div>
                </div>
            </div>
        </div>
    @else
        
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-12">
                    @isset($tipojob)
                        <div class="image-wrap">
                            <img src="{{ Storage::url($tipojob['imagem']) ?? Storage::url('imagens/tipojobs/tipo-padrao.png') }}" alt="Thumbnail" height="56" width="56">
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
                    <input id="input-arquivo-thumb-tipojob" name="imagem" type="file" accept="image/png, image/jpeg, image/gif"  />
                    {{-- <div class="input-group image-preview margemT20 margemB20">
                        <input id="arquivo-preview-thumb-tipojob" type="text" class="form-control image-preview-filename" disabled="disabled" placeholder="{{ __('messages.Nenhum arquivo selecionado') . ' (56x56)' }}" />
                        <span class="input-group-btn">
                            
                            <!-- image-preview-input -->
                            <div class="btn btn-default image-preview-input">
                                <span class="glyphicon glyphicon-folder-open"></span>
                                <span class="image-preview-input-title">{{ __('messages.Procurar')}}</span>
                                <input id="input-arquivo-thumb-tipojob" name="imagem" type="file" accept="image/png, image/jpeg, image/gif"  />
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
    
    {{-- Inputs --}}
    <div class="col-md-6">
        <div class="row div-item-form">
            <div class="col-md-12">
                <p class=""><b>{{ __('messages.Nome') }}</b></p>
                @isset($detalhe)
                    <p id="nome" class="margemB20" >{{ $tipojob['nome'] ?? 'Não Informado' }}</p>
                @else
                    <input type="text" name="nome" class="form-control" value="{{ $tipojob['nome']  ?? old('nome') }}" placeholder="{{ __('messages.Digite aqui') }}" >
                @endif
            </div>
        </div>

        <div class="row div-item-form">
            <div class="col-md-12">
                <p class=""><b>{{ __('messages.Descrição') }}</b></p>
                @isset($detalhe)
                    <p id="descricao" class="margemB20" >{{ $tipojob['descricao'] ?? 'Não Informado' }}</p>
                @else
                    <textarea name="descricao" class="col-md-12 form-control" value="{{ $tipojob['descricao']  ?? old('descricao') }}" placeholder="" >{{ $tipojob['descricao']  ?? old('descricao') }}</textarea>
                @endif
            </div>
        </div>

        <div class="row div-item-form">
            <div class="col-md-12">
                <p class=""><b>{{ __('messages.Boas Práticas') }}</b></p>
                @isset($detalhe)
                    <p id="boas_praticas" class="margemB20" >{{ $tipojob['boas_praticas'] ?? 'Não Informado' }}</p>
                @else
                    <textarea name="boas_praticas" class="col-md-12 form-control" value="{{ $tipojob['boas_praticas']  ?? old('boas_praticas') }}" placeholder="" >{{ $tipojob['boas_praticas']  ?? old('boas_praticas') }}</textarea>
                @endif
            </div>
        </div>

        <div class="row div-item-form">
            <div class="col-md-12">
                @isset($detalhe)
                    @if($tipojob->revisao)
                        <p><b>{{ __('messages.Esse Job é de Revisão') }}</b></p>
                    @elseif($tipojob->finalizador)
                        <p><b>{{ __('messages.Esse Job define Finalizador') }}</b></p>
                    @elseif($tipojob->gera_custo)
                        <p><b>{{ __('messages.Esse Job gera custo ao Cliente') }}</b></p>
                    @endif
                @else
                    <div class="row margemBottom">
                        <div class="col-md-6">
                            <p class=""><b>Selecione as Opções</b></p>
                            <input type="checkbox" name="revisao" value="1" {{ isset($tipojob) && $tipojob->revisao ? 'checked="checked"' : ''}}> {{ __('messages.Job de Revisão') }}  
                       </div>
                        <div class="col-md-6">
                            <input type="checkbox" name="finalizador" value="1" {{ isset($tipojob) && $tipojob->finalizador ? 'checked="checked"' : ''}}> {{ __('messages.Job define Finalizador') }}<br> 
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <input type="checkbox" name="gera_custo" value="1" {{ isset($tipojob) && $tipojob->gera_custo ? 'checked="checked"' : ''}}> {{ __('messages.Adicionar Custo ao Cliente') }}  
                        </div>
                  
                        <div class="col-md-6">
                            <input type="checkbox" name="solicite_hr" value="1" {{ isset($tipojob) && $tipojob->solicite_hr ? 'checked="checked"' : ''}}> {{ __('messages.Solicite HR') }}  
                        </div>
                    </div>

                @endif
            </div>
        </div>

        {{-- Tasks --}}
       {{--  <div class="row div-item-form">
            <div class="col-md-12">
                @isset($detalhe)
                    <p class=""><b>Tasks</b></p>
                    <ul>
                        @forelse($tipojob->tasks as $task)
                           <li>{{ $task->nome }}</li>
                        @empty   
                            <p>Sem Tasks vinculadas ao Tipo de Job</p>
                        @endforelse
                    </ul>
                @else
                    <div class="row margemBottom">
                        <div class="col-md-6">
                            <p class=""><b>Selecione uma ou mais Tasks para o Job</b></p>
                            <select id="combo-tasks" name="tasks[]" class="form-control select2" multiple>
                                @isset($tasks)
                                    @unless($tasks)
                                        <option value="-1">Sem Tasks Cadastradas</option>
                                    @else
                                        @foreach($tasks as $task)
                                           <option value="{{ $task->id }}" 
                                                {{ isset($tipo_job) && in_array($task->id, $tipojob_tasks_id) ? 'selected="selected"' : '' }} >
                                                {{ $task->nome }}
                                            </option>
                                        @endforeach
                                    @endunless
                                @endif
                            </select> 
                        </div>
                    </div>
                @endif
            </div>
        </div> --}}
    </div>

    {{-- Box Tasks --}}
    @isset($detalhe)
        <div class="col-md-6">             
            <div class="col-md-12 col-sm-12 tab-tasks pull-right">
                    <div class="box box-solid box-primary no-border com-shadow ">
                        <a class="larguraTotal" data-toggle="collapse" data-parent="#accordionTasks" href="#collapseOneTasks" aria-expanded="false">
                            <div class="box-header with-border fundo-verde com-borda">
                                <h3 class="box-title larguraTotal cor-branca"> {{ __('messages.Tasks') }}</h3>
                            </div>
                        </a>
                        <div id="collapseOneTasks" class="panel-collapse collapse in" aria-expanded="false" >
                            <div class="box-body box-profile">
                            @isset($tipojob->tasks)
                                @can('executa-job')
                                    <table class="table">
                                        <thead>
                                            <th colspan="3">{{ __('messages.Lista de tarefas do Job') }}</th>
                                        </thead>
                                        <tbody>
                                            @foreach($tipojob->tasks as $task)
                                                <tr>
                                                    @if($tipojob->coordenador && Auth::user()->id == $tipojob->coordenador->id || $tipojob->delegado && $tipojob->delegado->id == Auth::user()->id || Gate::check('gerencia-politicas'))
                                                        <td class="tamanho-menor">{{ ($loop->index+1).'-' }}</td>
                                                        <td class="task-nome" id="nome-task-{{$task->id}}">{{ $task->nome }}</td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endcan 
                            @endif
                        </div>
                        </div>
                    </div>
            </div>
        </div>
    @else
        <div class="col-md-6">
            <div class="box box-solid box-primary no-border com-shadow">
                <div class="box-header fundo-verde com-borda">
                    <h3 class="box-title">{{ __('messages.Tasks') }}</h3>
                </div>
                <div class="box-body">
                    {{-- Tasks --}}
                    <div class="row">
                        <div class="col-sm-12 col-md-12">
                            <p class="detalhe-label"><b>{{ __('messages.Selecione uma ou mais Tasks para o Tipo de Job') }}</b></p>
                            <select id="combo-tasks" name="combo-tasks[]" class="form-control select2 col-md-6" multiple>
                                @isset($tasks)
                                    @unless($tasks)
                                        <option value="-1">{{ __('messages.Sem Tasks Cadastradas') }}</option>
                                    @else
                                        @foreach($tasks as $task)
                                            @isset($tipojob_tasks_id)
                                                <option value="{{ $task->id }}" 
                                                    {{ in_array($task->id, $tipojob_tasks_id) ? 'selected=selected' : '' }} >
                                                    {{ $task->nome }}
                                                </option>
                                            @else
                                                <option value="{{ $task->id }}">
                                                    {{ $task->nome }}
                                                </option>
                                            @endif
                                        @endforeach
                                    @endunless
                                @endif
                            </select>
                        </div>
                    </div>  
                
                    <div class="row">
                        <div class="col-md-12">
                            <h3 class="texto-preto">{{ __('messages.Ordem para Execução das Tasks') }}</h3>
                            <table class="table table-hover table-bordered" id="lista-tasks">
                                    <thead>
                                    <tr>
                                        <th colspan="2">Tasks</th>
                                    </tr>
                                </thead>
                                @isset($tipojob->tasks)
                                    <tbody class="body-drag">
                                        @foreach($tipojob->tasks as $task)
                                            <tr id="task-{{ $task->id }}" class="task-arrastavel">
                                                <td class="texto-centralizado"> 
                                                    <i class="fa fa-bars"></i>
                                                </td>  
                                                <td>
                                                    {{ $task->nome }}
                                                    <input type="hidden" value="{{ $task->id }}" name="tasks[]" />
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                @endif
                                <tfooter><tr></tr></tfooter>
                            </table>
                        </div>
                    </div>
                </div>  
                {{-- <div class="box-footer footer-com-padding borda-t-cinza">
                    <button type="submit" class="btn btn-success pull-right">Adicionar Novo Job</button>
                </div> --}}
            </div>
        </div>
    @endif

    {{-- Campos personalizados --}}
    <div class="col-md-6 cor-gelo">
        
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
                    </select>
                </div>
            </div>

            <div class="row div-item-form margemT10">
                <div class="col-md-6 pull-right">
                    <button id="add-campo" class="btn btn-success btn-flat pull-right"><i class="fa fa-check"></i> {{ __('messages.ADD') }}</button>
                </div>
            </div>
        @endif

        <div class="row div-item-form margemT10 margemB40">
            <div class="col-md-12">
                @if(empty($detalhe))

                @endif
                <table class="table" id="lista-campos">
                    <thead>
                    <tr>
                        <th colspan="2">{{ __('messages.Nome') }}</th>
                        <th>{{ __('messages.Tipo') }}</th>
                        @if(empty($detalhe))
                            <th>{{ __('messages.Remover') }}</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    @isset($tipojob->campos_personalizados)
                        @forelse($tipojob->campos_personalizados as $campo)
                            <tr class="conteudo-campo">
                                <td colspan="2">
                                    <p>{{$campo['nome']}}</p>
                                    <input type="hidden" class="campos-personalizados" name="campos_personalizados[{{$campo['nome']}}][nome]" value="{{$campo['nome']}}">
                                </td>
                                <td>
                                    <p>{{ $campo['tipo'] == 'textarea' ? 'Texto Longo' : ucfirst($campo['tipo']) }}</p>
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
    </div>


</div>


{{-- Controle dos campos --}}
@push('js')
    <script src="{{ asset('js/arquivos.js') }}"></script>
    {{-- <script src="{{ asset('js/imagens.js') }}"></script> --}}
    <script>

        $(document).ready(function() {

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

                // gera o html da nova linha do novo tecnico
                var campo =  geraHtmlTecnico(campoID, tipoSelecionado, nome);
                // console.log(tecnico);

                jQuery("#lista-campos").append(campo);
                jQuery("#remove-campo" + campoID).on("click", function(e){
                    $(this).parents('.conteudo-campo').first().remove();
                });
                limpaCampos();
            }

            function geraHtmlTecnico(campoID, tipoSelecionado, nome){

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
