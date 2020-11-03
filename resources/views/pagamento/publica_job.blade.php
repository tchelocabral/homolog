@extends('adminlte::page')

@section('title', @isset($job) ? 'Job ' . $job->nome : 'Job')

@section('content_header')
   {{-- {{ Breadcrumbs::render('detalhe-job', $job) }} --}}
   <h3>{{ __('messages.Confirmação de Pagamento') }}</h3>
@stop

@section('content') 

    {{-- <input type="hidden" id="mod-tit-tasks" value="{{ __('modal.Tarefas do Job') }}">
    <input type="hidden" id="mod-btn-ok" value="{{ __('modal.Publicar Job') }}">
    <input type="hidden" id="mod-btn-cancel" value="{{ __('modal.Cancelar') }}">
    <input type="hidden" id="mod-tit-prazo" value="{{ __('modal.Deadline') }}">

    <input type="hidden" id="mod-no-termos" value="{{ __('modal.Você deve concordar com os termos de uso da plataforma para pegar um Job') }}"> --}}

    <div class="container centalizado">
        <form action="{{ route('job.mudar.pagamento', encrypt($job->id)) }}" id="form-pagamento" 
            class="form-pega-job"  name="form_pagamento" enctype="multipart/form-data" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <input type="hidden" id="status" name="status" value="">
                    <input type="hidden" id="pago-em" name="pago_em" value="">
                    <input type="hidden" id="chave-de-pgto" name="chave_de_pgto" value="">
                    <input type="hidden" name="valor" id="valor" value="{{ $job->valor }}">


                    <div class="col-md-12"> 
                        <h2 class="titulo-modal-pega-job">Job <span id="nome-job" class="nome-job-modal-destaque" value="{{$job->nome}}"><b>  {{$job->nome}} </b></span></h2>
                    </div>
                
                    <div class="col-md-6">
                        <div class="col-md-6 margemT20">
                            <h5 class=""> {{ __('modal.Valor') }}</h5>
                            <h4 class="negrito">{{$job->valor_job_clean}}</h4>
                            <input type="hidden" id="valor-job" value="{{$job->valor_job_clean}}">
                            {{-- <h5 class="">Freelancer: {{$job->valor_delegado}} </h5>
                            <h5 class=""> {{ __('modal.Taxas') }}: {{$job->valor_taxa}} </h5> --}}
                        </div>
                        <div class="col-md-6 margemT20 ">
                            <h5 class=""> {{ __('modal.Deadline') }}</h4>
                            <h4 > {{$job->data_prox_revisao ? $job->data_prox_revisao->format('d/m/Y') : '-'}}</h4>
                        </div>
                        <div class="col-md-6 margemT10 ">
                            @if($job->data_limite)
                                <h5 class="">{{ __('modal.Aceita Propostas até') }}</h5>
                                <h4>{{$job->data_limite ? $job->data_limite->format('d/m/Y') : '-'}} </h4>
                            @endif
                        </div>
                            
                        <div class="col-md-12"> 
                            <h4 class=""> {!!html_entity_decode($job->descricao) !!}</h4>
                        </div>
                        
                    </div>
                    <div class="col-md-6 margemT10"> 
                        <h5 class="margemT20"> {{ __('modal.Tarefas do Job') }}:</h5>
                        @if($job->tasks)
                            @foreach ($job->tasks as $task)
                                <div class="col-md-12"><h5 class="negrito">-{{$task->nome}}</h5></div>
                            @endforeach
                        @endif
                    </div>             
                </div>
            </div>
       
            <hr class="size">


            <div class="row margemT20">
                <h3>{{ __('messages.Método de Pagamento')}}</h3>
            </div>

            @if($job->jobsPagamentosPos->count()<=0)

            
                <div class="row margemT40"> 
                    @if($job->status != $statusarray['emproposta']) 
                        <div class="col-md-3 col-sm-4"> 
                            @can('pos-pagamento')
                                <input type="radio" name="tipo_pagamento" id="tipo-pagamento" value="0" checked> Pré-Pagamento
                            @endcan
                            <div class="row margemT40"> 
                                <div class="col-md-3">
                                    <div id="paypal-button-container"></div>
                                    <input type="hidden" id="titulo" name="titulo" value="{{ __('messages.Pagamento') }}">
                                    <input type="hidden" id="mensagem" name="mensagem" value="{{ __('messages.Sua transição foi concluída com sucesso') }}">
                                    <input type="hidden" id="mensagem" name="metodo_pagamento_pre" value="paypal">
                                    
                                </div>
                            </div>
                        </div>
                    @endif
                    @can('pos-pagamento')
                        <div class="col-md-3 col-sm-4"> 
                            <input type="radio" name="tipo_pagamento" id="tipo-pagamento" value="1"> Pós-Pagamento
                            
                            <h4>{{ __('messages.Selecione o prazo para pagar')}}</h4>
                            <select name="prazo_pagamento" id="qtde-dias-pagamento" >
                                <option value="0">0 {{ __('messages.Dias - Após o Job concluído')}}</option>
                                <option value="7">7 {{ __('messages.Dias - Após o Job concluído')}}</option>
                                <option value="14">14 {{ __('messages.Dias - Após o Job concluído')}}</option>
                                                    
                            </select>
                            <h4>Forma de Pagamento</h4>
                            <select name="metodo_pagamento" id="método-pagamento" >
                            
                                <option value="-1">{{ __('messages.Forma de pagamento')}}</option>
                                @foreach ($metodos_pagamento as $index => $item)
                                    <option value="{{ $index }}">{{ __('messages.'.$item)}}</option>
                                @endforeach
                                                    
                            </select>
                            <button id="publicar-job" class="btn btn-success pull-right margemT10 " value="" name="btn_avaliar">{{ __('messages.Confirmar') }}</button>
                        </div>
                    @endcan

                </div>
               
            @else
                <div class="row margemT40"> 
                    
                    <input type="hidden" name="tipo_pagamento" id="tipo-pagamento" value="1">
                    @foreach ($metodos_pagamento as $index => $item)
                        @php  
                            $formatacao  = ''; 
                            $check = '';
                        if($job->jobsPagamentosPos[0]->metodo_pagamento == $index) {
                          $formatacao  = 'bg-success';
                          $check = "checked";
                        }
                        @endphp
                        @if($item=='Pay Pal')

                            <div class="col-md-4 col-sm-4 metodo-pagamento {{ $formatacao  }} margemT30"> 
                                <div class="row"> 
                                    <div class="col-md-12">
                                        <input type="radio" name="metodo_pagamento" value="{{ $index }}" {{ $check }}> {{ $item }}
                
                                       
                                        <div id="paypal-button-container" class="margemT30 largura80"></div>
                                        <input type="hidden" id="titulo" name="titulo" value="{{ __('messages.Pagamento') }}">
                                        <input type="hidden" id="mensagem" name="mensagem" value="{{ __('messages.Sua transição foi concluída com sucesso') }}">
                                    </div>
                                </div>
                            </div>
                        @elseif($item=='Transferencia Bancaria')
                            <div class="col-md-4 col-sm-4 {{ $formatacao  }} margemT30"> 
                                <div class="row"> 
                                    <div class="col-md-12">
                                        <input type="radio" name="metodo_pagamento" value="{{ $index }}" {{ $check }}> {{ $item }}
                                        <h5>Anexar Comprovante pagamento </h5>
                                        {{-- @if($item=='Transferencia Bancaria') --}}
                                            <input type="date" name="pago_em" id="pago-em" class="form-control" min="{{date('Y-m-d')}}" />

                                            <div class="input-group image-preview image-upload">
                                                <input id="arquivo-preview-1"  type="text" class="form-control image-preview-filename" disabled="disabled" placeholder="{{ __('messages.Nenhum arquivo selecionado') }}" />
                                                <span class="input-group-btn">
                                                    <!-- image-preview-clear button -->
                                                    <button type="button" class="btn btn-default image-preview-clear" >
                                                        <span class="glyphicon glyphicon-remove"></span> {{ __('messages.Limpar') }}
                                                    </button>
                                                    <!-- image-preview-input -->
                                                    <div class="btn btn-default image-preview-input">
                                                        <span class="glyphicon glyphicon-folder-open"></span>
                                                        <span class="image-preview-input-title">{{ __('messages.Procurar') }}</span>
                                                        <input id="input-thumb-1" name="comprovante_pagamento"  type="file" accept="image/x-png, image/jpeg, image/gif" />
                                                    </div>
                                                </span>
                                                {{-- <input id="thumb-1" type="file" accept="*" name="thumb_ref" style="position: absolute; top: 0px; right: 1000vw;" />  --}}
                                            </div>
                                            <br>
                                            <button id="publicar-job" class="btn btn-success pull-right margemT10 " value="" name="btn_avaliar">{{ __('messages.Enviar comprovante') }}</button>
                                            
                                        {{-- @endif --}}

                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif
        </form>
    </div>

    {{-- <span id="pp-id" value="{{ env('PAYPAL_ID') }}"></span> --}}

    @php $pp_id = env('PAYPAL_ID'); @endphp

@stop

@push('js')
    <script src="{{ asset('js/imagens.js') }}"></script>

    <script src="{{ asset('js/arquivos.js') }}"></script>
    {{-- <script src="{{ env('PAYPAL_ID') ?? 'https://www.paypal.com/sdk/js?client-id=AbwzOoXbMUetH1TYpqcwoGpNEFQVn3A0uEpJNHHiflC5BrlIoH9X2CzweYz4jWUmelGFVjprkPJKsFeK&currency=BRL' }}"></script> --}}
    <script src="{{ $pp_id }}"></script>
    <script>
        var valor_pagamento = document.getElementById("valor-job").value;
        valor_pagamento = valor_pagamento.replace("R$ ", "");
        
        var nome_job = 'Job ';
        nome_job    += document.getElementById('nome-job').value ?? '';

        if(valor_pagamento == '') {
            valor_pagamento = '0.01'
        }
        // valor_pagamento = '0.01';
        paypal.Buttons({
            createOrder: function(data, actions) {
            // This function sets up the details of the transaction, including the amount and line item details.
            return actions.order.create({
                purchase_units: [{
                    amount: {
                        value: valor_pagamento,
                    },
                    description: nome_job,
                }]
            });
            },
            onApprove: function(data, actions) {
            // This function captures the funds from the transaction.
            return actions.order.capture().then(function(details) {
                // This function shows a transaction success message to your buyer.
                var mensagem = document.getElementById("mensagem").value;
                var titulo = document.getElementById("titulo").value;
                sucesso_info(titulo,mensagem +", " +  details.payer.name.given_name);
                //                alert(mensagem +", " +  details.payer.name.given_name);
               
                console.log(details);
                document.getElementById('status').value = details.purchase_units[0].payments.captures[0].status;
                document.getElementById('pago-em').value = details.update_time;
                document.getElementById('chave-de-pgto').value = details.id;
                document.getElementById('valor').value = details.purchase_units[0].amount.value;

                // var tipo_pagamento = document.getElementById("tipo-pagamento").value;
                // if(tipo_pagamento==0) {
                //     document.getElementById('metodo_pagamento_pre').value = 0;
                // }
                // alert('Id Payment' +  details.id);
                document.getElementById('form-pagamento').submit();
            });
            }
        }).render('#paypal-button-container');
        // This function displays Smart Payment Buttons on your web page.

    </script>

@endpush