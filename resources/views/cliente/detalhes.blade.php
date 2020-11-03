@extends('adminlte::page')

@section('title', $cliente->nome_fantasia )

@section('content_header')
    {{ Breadcrumbs::render('detalhe-cliente', $cliente) }}



<h2 class="">{{ __('messages.Dados do Cliente') }} - {{ $cliente['nome_fantasia'] ?? old('nome_fantasia')  }} </h2>

    <div class="row">
        <div class="col-md-8 margemT10">
            <div class="btn-toolbar" role="toolbar">
                @can('atualiza-cliente')
                    <a href="{{ route('clientes.edit', encrypt($cliente->id)) }}" class="btn btn-info" title="{{ __('messages.Editar Item') }}" data-toggle="tooltip">
                        <i class="fa fa-pencil" aria-hidden="true"></i>
                    </a>
                @endcan
                
                @can('deleta-cliente')
                    <form action="{{ route('clientes.destroy', encrypt($cliente->id)) }}" class="form-delete" id="form-deletar-tipo-img-{{ $cliente->id }}" name="form-deletar-tipo-img-{{ $cliente->id }}" method="POST" enctype="multipart/form-data">
                        @method('DELETE')
                        @csrf
                        <a href="#" class="btn btn-danger deletar-item margemL5" title="Excluir Cliente" data-toggle="tooltip" type="submit">
                            <i class="fa fa-close" aria-hidden="true"></i>
                        </a>
                    </form>
                @endcan
            </div>
        </div>
    </div>
@stop

@section('content')

{{-- <style type="text/css">
    
    .sem-padding-left {
        padding-left: 0!important;
    }

    .sem-padding-right {
        padding-right: 0!important;
    }

</style> --}}


    

    <div class="row margemT40 centralizado">
        <div class="nav-tabs-custom altura-minima">
            <ul class="nav nav-tabs fundo-verde" id="tabs-politica" role="tablist">
                <li class="active in">  
                    <a data-toggle="tab" href="#dados" aria-expanded="true" class="nav-link">{{ __('messages.Dados') }}</a>
                </li>
                <li>                    
                    <a data-toggle="tab" href="#end" aria-expanded="false" class="nav-link">{{ __('messages.Endereço') }} </a>
                </li>
                <li>                    
                    <a data-toggle="tab" href="#contato" aria-expanded="false" class="nav-link">{{ __('messages.Contato') }}</a>
                </li>
                <li>                    
                    <a data-toggle="tab" href="#dados-fat" aria-expanded="false" class="nav-link">{{ __('messages.Faturamento') }}</a>
                </li>
                <li>                    
                    <a data-toggle="tab" href="#lista-fat" aria-expanded="false" class="nav-link">{{ __('messages.Lista de Faturamentos') }}</a>
                </li>
                <li>                    
                    <a data-toggle="tab" href="#lista-projetos" aria-expanded="false" class="nav-link">{{ __('messages.Lista de Projetos') }}</a>
                </li>
            </ul>

            <div class="tab-content">
                @empty($cliente)
                    <h1>{{ __('messages.Cliente não Encontrado') }}</h1>
                @else
                    {{-- Tab #dados--}}
                    <div id="dados" class="tab-pane fade in active">
                        @include('cliente.inputs', ['cliente' => $cliente, 'detalhe' => true])
                    </div>
                    {{-- end dtab --}}

                    {{-- Endereço --}}
                    <div id="end" class="tab-pane fade in ">
                        @include('endereco.inputs', ['endereco' => $cliente->primeiro_endereco(), 'detalhe' => true])
                    </div>       
                    {{-- end dtab --}}

                    {{-- Contato --}}
                    <div id="contato" class="tab-pane fade in ">
                        @include('contato.inputs', ['contato' => $cliente->primeiro_contato(), 'detalhe' => true])
                    </div>

                   
                    
                    {{-- end Tab --}}

                    {{-- Dados do Faturamentos --}}
                    <div id="dados-fat" class="tab-pane fade in ">
                        <div class="col-md-4">
                            <h3>{{ __('messages.Faturamento') }}</h3>
                             <form id="faturamento-novo" name="faturamento-novo" action="{{ route('cliente.faturamento.add') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" value="{{ $cliente->id }}" name="cliente_id">
                                <input type="hidden" value="-1" name="faturamento_id" id="faturamento-id">

                                @include('faturamento.inputs',['faturamento' => null])
                                 <div class="footer-com-padding ">
                                    {{-- ajax!! --}}
                                    <button type="btn" id="btn-faturamento" class="btn btn-success pull-right margemT10">{{ __('messages.Adicionar Faturamento') }}</button>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-7 col-md-offset-1">
                            <form id="form-faturamento" name="form-faturamento" action="{{ route('cliente.faturamento.vincular') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" value="{{ $cliente->id }}" name="cliente_id">

                                @can('cria-cliente')
                                <h3>{{ __('messages.Vincular projeto e Faturamento') }}</h3>
                                
                                <div class="col-md-6">
                                    <h3>{{ __('messages.Selecione um Faturamento já cadastrado') }}</h3>
                                    <select id="combo-faturamento" name="faturamento_id" class="form-control select2 margemT10">
                                        <option value="-1" data-fat="-1">{{ __('messages.Nenhum Faturamento Selecionado') }}</option>
                                        <option disabled>_________</option>
                                        @unless($cliente->faturamentos)
                                            <option value="-1" data-fat="-1">{{ __('messages.Não existem faturamentos cadastrados') }}
                                            </option>
                                        @else
                                            @foreach($cliente->faturamentos as $fat)
                                                <option value="{{ $fat->id }}" data-fat="{{$fat}}">{{$fat->apelido}}
                                                </option>
                                            @endforeach
                                        @endunless
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <h3>{{ __('messages.Selecione o Projeto') }}</h3>
                                    <select id="combo-projetos" name="projeto_id" class="form-control select2 margemT10">
                                        <option value="-1">{{ __('messages.Nenhum Projeto Selecionado') }}</option>
                                        <option disabled>_________</option>
                                        @unless($cliente->projetos)
                                            <option value="-1">{{ __('messages.Não existem projetos cadastrados') }}</option>
                                        @else
                                            @foreach($cliente->projetos as $prj)
                                                <option value="{{ $prj->id }}">{{$prj->nome}}</option>
                                            @endforeach
                                        @endunless
                                    </select>
                                </div>
                                <hr>
                                {{-- @include('faturamento.inputs') --}}
                                <div class="box-footer footer-com-padding ">
                                    {{-- ajax!! --}}
                                    <button type="btn" class="btn btn-success pull-right margemT10">{{ __('messages.Vincular Faturamento') }}</button>
                                </div>
                                @endcan
                            </form>
                        </div>
                    </div>
                    {{-- end dtab --}}

                    {{-- Lista de Faturamentos --}}
                    <div id="lista-fat" class="tab-pane fade in ">
                        <table id="financeiro" class="table larguraTotal">
                            <thead class="">
                            <tr class="">
                                <th colspan="" class="box-title th-ocean texto-branco com-border-left">#</th>
                                <th colspan="" class="box-title th-ocean texto-branco">{{ __('messages.Projetos') }}</th>
                                <th colspan="" class="box-title th-ocean texto-branco">{{ __('messages.Apelido') }}</th>
                                <th colspan="" class="box-title th-ocean texto-branco">{{ __('messages.Razão Social') }}</th>
                                <th colspan="" class="box-title th-ocean texto-branco">{{ __('messages.Nome Fantasia') }}</th>
                                <th colspan="" class="box-title th-ocean texto-branco">{{ __('messages.CNPJ') }}</th>
                                <th colspan="" class="box-title th-ocean texto-branco com-border-right">{{ __('messages.Ações') }}</th>
                            </tr>
                            </thead>
                            <tbody class="fundo-branco com-shadow">
                                @foreach($cliente->faturamentos as $fat)
                                    <tr class="">
                                        <td class="desktop" class="faturamento">{{ $fat->id }}</td>
                                        <td>
                                            @forelse($fat->projetos as $p)
                                                <a href="{{route('projetos.show', encrypt($p->id))}}" class="texto-preto">
                                                    {{ $p->nome }}
                                                    {{!$loop->last ? ', ' : ''}}
                                                </a>
                                            @empty
                                                <h5 class="semMargem">{{ __('messages.Sem projetos vinculados') }}</h5>
                                            @endforelse
                                        </td>
                                        <td class="desktop" class="faturamento">{{ $fat->apelido }}</td>
                                        <td class="desktop" class="faturamento">{{ $fat->razao_social }}</td>
                                        <td class="desktop" class="faturamento">{{ $fat->nome_fantasia }}</td>
                                        <td class="desktop" class="faturamento">{{ $fat->cnpj }}</td>
                                        <td>
                                            <form action="{{ route('deletar.cliente.faturamento') }}" class="form-delete" id="form-deletar-faturamento-{{ $fat->id }}" name="form-deletar-faturamento-{{ $fat->id }}" method="POST" enctype="multipart/form-data">
                                                <input type="hidden" value="{{$fat->id}}" name="fat_id">
                                                @method('DELETE')
                                                @csrf
                                                <a href="#" class="btn btn-danger deletar-item margemL5" title="{{ __('messages.Excluir Faturamento') }}" data-toggle="tooltip" type="submit">
                                                    <i class="fa fa-close" aria-hidden="true"></i>
                                                </a>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>  
                        </table>
                    </div>
                    {{-- end dtab --}}

                    {{-- Lista de Projetos --}}
                    <div id="lista-projetos" class="tab-pane fade in ">
                        <table id="financeiro" class="table larguraTotal">
                            <thead class="">
                            <tr class="">
                                <th colspan="" class="th-ocean texto-branco padding12 border-left">#</th>
                                <th colspan="" class="th-ocean texto-branco padding12">{{ __('messages.Projeto') }}</th>
                                <th colspan="" class="th-ocean texto-branco padding12">{{ __('messages.Criação') }}</th>
                                <th colspan="" class="th-ocean texto-branco padding12">{{ __('messages.Previsão de Entrega') }}</th>
                                <th colspan="" class="th-ocean texto-branco padding12">{{ __('messages.Progresso') }}</th>
                                <th colspan="" class="th-ocean texto-branco padding12 border-right">{{ __('messages.Ações') }}</th>
                            </tr>
                            </thead>
                            <tbody class="fundo-branco com-shadow">
                                @foreach($cliente->projetos as $index => $proj)
                                    <tr class="">
                                        <td class="desktop" class="faturamento">{{ $index+1 }}</td>
                                        <td class="desktop" class="faturamento">{{ $proj->nome }}</td>
                                        <td class="desktop">{{ $proj->created_at ? \Carbon\Carbon::parse($proj->created_at)->format('d.m.Y') : __('messages.Não Informado')}}</td>
                                        <td class="desktop">{{ $proj->data_previsao_entrega ? \Carbon\Carbon::parse($proj->data_previsao_entrega)->format('d.m.Y') : __('messages.Não Informado')}}</td>
                                        <td>
                                            <div class="progress">
                                                <div class="progress-bar progress-bar-animated progresso" role="progressbar" aria-valuenow="{{ $proj->concluido() }}" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="{{ route('projetos.show', encrypt($proj->id)) }}" class="">{{ __('messages.Detalhes') }}</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>  
                        </table>
                    </div>
                    {{-- end dtab --}}

                    

                @endempty
            </div>
        </div>
    </div>    
@include('app.includes.carregando')

@stop

@push('js')
    <script>
        (function() {
            // Select Faturamento - onload
            $(document).on('change', '#combo-faturamento', function(e) {

                this.value == -1 ? limpaCampos() : preencheCampos(this);
            });

            // dispara o evento de edição do faturamento ao clicar no botão editar
            $(document).on('click', '.edita-faturamento', function(e) {
                e.preventDefault();
                //chama a função
                selecionaFaturamento(e);
            });

        })();


        // limpa os campos de input
        function limpaCampos() {
            jQuery('#faturamento-apelido').val('').prop('disabled', false);
            jQuery('#faturamento-razao-social').val('').prop('disabled', false);
            jQuery('#faturamento-nome-fantasia').val('').prop('disabled', false);
            jQuery('#faturamento-cnpj').val('').prop('disabled', false);
            jQuery('#faturamento-nome-contato').val('').prop('disabled', false);
            jQuery('#faturamento-email-contato').val('').prop('disabled', false);

             jQuery('#btn-faturamento').html("Adicionar Faturamento");
             jQuery('#faturamento-novo').attr("action", "{{ route('cliente.faturamento.add') }}");
             jQuery('#faturamento-id').val("");
        }
        function preencheCampos(select) {
            var fat_id = {id: jQuery(select).val()} //.children("option:selected").value;
            var url    = "{{ route('cliente.faturamento.show') }}";
            $.ajax({
               url: url,
                type: 'GET',
                data: fat_id,
                beforeSend: function(xhr){
                    // jQuery('#faturamento-apelido').val('').prop('disabled', true);
                    // jQuery('#faturamento-razao-social').val('').prop('disabled', true);
                    // jQuery('#faturamento-nome-fantasia').val('').prop('disabled', true);
                    // jQuery('#faturamento-cnpj').val('').prop('disabled', true);
                    // jQuery('#faturamento-nome-contato').val('').prop('disabled', true);
                    // jQuery('#faturamento-email-contato').val('').prop('disabled', true);
                },
                success: function (data) {
                    console.log(data);
                    // alert(data.apelido);
                    jQuery('#faturamento-apelido').val(data.apelido);
                    jQuery('#faturamento-razao-social').val(data.razao_social);
                    jQuery('#faturamento-nome-fantasia').val(data.nome_fantasia);
                    jQuery('#faturamento-cnpj').val(data.cnpj);
                    jQuery('#faturamento-nome-contato').val(data.nome_contato);
                    jQuery('#faturamento-email-contato').val(data.email_contato);

                    jQuery('#btn-faturamento').html("{{ __('messages.Editar Faturamento') }}");
                    jQuery('#faturamento-novo').attr("action", "{{ route('update.cliente.faturamento') }}");
                    jQuery('#faturamento-id').val(data.id);

                },
                error: function(data){
                   limpaCampos();
                }
            });
        }
        function preencheCamposOld(select)
        {
            // alert(select.name);
            var tempSelect = jQuery(select);
            var dataFat = tempSelect.children("option:selected").attr('data-fat');
            
            limpaCampos();

            // alert(dataFat);
            jQuery('#faturamento-apelido').val(dataFat['apelido']);
            jQuery('#faturamento-razao-social').val(dataFat.razao_social);
            jQuery('#faturamento-nome-fantasia').val(data.nome_fantasia);
            jQuery('#faturamento-cnpj').val(data.cnpj);
            jQuery('#faturamento-nome-contato').val(data.nome_contato);
            jQuery('#faturamento-email-contato').val(data.email_contato);
        }

        // Função para habilitar a edição dos campos do faturamento
        function selecionaFaturamento(e) {

            // pega a linha da tabela
            var linha = e.currentTarget.closest('tr');
            //pega o id do registro salvo na linha
            var faturamento = linha.cells[0].innerHTML;

            // alert(faturamento);

            // Setando os valores do select
            jQuery('#combo-faturamento').val(faturamento);   
            // Disparando o evento change no para preencher os inputs
            jQuery('#combo-faturamento').trigger('change');
            // jQuery("#combo-faturamentos").prop('selectedIndex',faturamento);
            
            // mudar o nome do faturamento para o faturamento selecionado
            $("#combo-faturamentos").selectedIndex = linha.cells[0];

        }


        // $('#combo-tasks').on("select2:select", function(e) { 
        //     var task_id = e.params.data.id;
        //     var task    = e.params.data.text;
        //     geraHTMLTaskParaOrdenar(task_id, task);
        // });

    </script>
@endpush
