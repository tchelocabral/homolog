<div class="row largura90 centralizado">
    <h1 class="margemB40">{{ __('messages.Pagamentos')}}</h1>

    <div class="col-md-12">
        @unless($dado_pag['lista_pagamentos']->count())
            <p>{{ __('messages.Não Existem valores para débito')}}</p>
        @else
            <table id="lista-dashboard" class="table table-striped larguraTotal">   
                <thead class="">
                <tr class="">
                    <th colspan="" class="box-title th-ocean texto-branco padding12 com-border-left">#</th>
                    <th colspan="" class="box-title th-ocean texto-branco padding12">{{ __('messages.Freelancer')}}</th>
                    <th colspan="" class="box-title th-ocean texto-branco padding12">{{ __('messages.Conta PayPal')}}</th>
                    <th colspan="" class="box-title th-ocean texto-branco padding12">{{ __('messages.Cód. Serviço')}}</th>
                    <th colspan="" class="box-title th-ocean texto-branco padding12">{{ __('messages.Concluído em')}}</th>
                    <th colspan="" class="box-title th-ocean texto-branco padding12">{{ __('messages.Valor Total')}}</th>

                    {{-- @hasanyrole('admin|desenvolvedor') --}}
                    <th colspan="" class="box-title th-ocean texto-branco padding12">{{ __('messages.Valor Freela')}}</th>
                    {{-- @endhasanyrole --}}
                    
                    <th colspan="" class="box-title th-ocean texto-branco padding12">{{ __('messages.Status')}}</th>
                    <th colspan="" class="box-title th-ocean texto-branco padding12">{{ __('messages.Data do Pagamento')}}</th>
                    <th colspan="" class="box-title th-ocean texto-branco padding12 com-border-right">{{ __('messages.Ações')}}</th>
                </tr>
                </thead>
                <tbody class="fundo-branco com-shadow">
                    @csrf
                    {{-- {{ dd($dado_pag['lista_pagamentos']) }} --}}
                @foreach($dado_pag['lista_pagamentos'] as $con)
                   {{-- {{ dd($con) }} --}}
                    @php 
                        $con->conta_paypal = json_decode($con->dados_bancarios, true)['conta_paypal'] ?? 'Não informado';  
                        $cor_linha = '';
                        if ($con->status==2) {
                            $cor_linha = "warning";
                        } elseif($con->status==3) {
                            $cor_linha = "success";
                        } elseif($con->status==4){
                            $cor_linha = "danger";
                        }
                    @endphp
                    
                    <tr class=" {{ $cor_linha }} ">
                        <td>#{{ $con->id }}</td>
                        <td>{{ $con->para->name  }}</td>
                        <td>{{ $con->conta_paypal  }}</td>
                        <td>{{ $con->job->nome  }}</td>
                        <td>{{ $con->job->data_entrega ? $con->job->data_entrega->format('d/m/Y') : '-'  }}</td>
                        <td>R$ {{($con->valor_de)}}</td>
                        {{-- @hasanyrole('admin|desenvolvedor') --}}
                        <td>R$ {{($con->valor_para)}}</td>
                        {{-- @endhasanyrole --}}
                        <td>{{ $con->status==1 ? __('messages.Pendente'): __('messages.Executado')  }}</td>
                        <td>
                            {{ $con->pago_em ? $con->pago_em->format('d/m/Y') : '-'  }}
                        </td>
                      
                        <td>
                            @php 
                               

                                $job_pago_em = null;
                                $job_pago_em_original = null;
                                if($con->job->pagamentoEfetivado && $con->job->pagamentoEfetivado->pago_em != null ) {
                                    $job_pago_em_original = \Carbon\Carbon::parse($con->job->pagamentoEfetivado->pago_em)->format('Y-m-d');
                                    $job_pago_em = \Carbon\Carbon::parse($job_pago_em_original)->format('d/m/Y') ;

                                    if($con->status == 3)
                                    {
                                        $job_para_pago_em = \Carbon\Carbon::parse($con->para_pago_em)->format('d/m/Y') ; 
                                    }
                                }
                                else {
                                    $job_pago_em_original = now()->format('Y-m-d');
                                    $job_pago_em = 'Não informado';
                                    $job_para_pago_em = $job_pago_em_original; 
                                }

                            @endphp
                            <input type="hidden" id="texto-titulo"                 value="{{ __('messages.Pagamento') }}">
                            <input type="hidden" id="texto-confirme"               value="{{ __('messages.Confirma') }}">
                            <input type="hidden" id="texto-data-pagamento"         value="{{ __('messages.Data do Pagamento') }}"> 
                            <input type="hidden" id="texto-confirmando-pagamento"  value="{{ __('messages.Confirmando Pagamento do') }}"> 
                            <input type="hidden" id="texto-de"                     value="{{ __('messages.De') }}"> 
                            <input type="hidden" id="texto-para"                   value="{{ __('messages.Para') }}"> 
                            <input type="hidden" id="texto-total-freelancer"       value="{{ __('messages.Total Freelancer') }}"> 
                            <input type="hidden" id="texto-pago-em"                value="{{ __('messages.Pago em') }}"> 
                            <input type="hidden" id="texto-pagamento-de"           value="{{ __('messages.Pagamento de') }}"> 
                            <input type="hidden" id="texto-valor"                  value="{{ __('messages.Valor') }}"> 
                            <input type="hidden" id="texto-taxas"                  value="{{ __('messages.Taxas') }}"> 
                            <input type="hidden" id="texto-confirmar-liberacao"    value="{{ __('messages.Confirmar Liberação') }}"> 
                            <input type="hidden" id="texto-recibo-liberacao"       value="{{ __('messages.Recibo da Liberação do') }}"> 
                            <input type="hidden" id="texto-confirmar-pagamento"    value="{{ __('messages.Confirmar Pagamento') }}"> 
                            <input type="hidden" id="texto-confirmando-pagamento"  value="{{ __('messages.Confirmando Pagamento do') }}">
                            <input type="hidden" id="texto-recibo-liberacao"       value="{{ __('messages.Recibo da Liberação do') }}"> 
                            <input type="hidden" id="texto-confirmar-pagamento"    value="{{ __('messages.Confirmar Pagamento') }}"> 

                            <a 
                                class                       = "confirmar-pagamento"
                                href                        = "javascript:void(0);"
                                data-id                     = "{{ $con->id }}"
                                data-de                     = "{{ $con->de->name }}"
                                data-para                   = "{{ $con->para->name }}"
                                data-model                  = "{{ encrypt($con->job->id) }}"
                                data-pagador                = "{{ Auth::user()->name }}"
                                data-taxa                   = "{{ $con->taxa }}"
                                data-cc                     = "{{ $con->centro_de_custo_id }}"
                                data-cat-custo              = "{{ $con->categoria_de_custo_id }}"
                                data-m-type                 = "{{ ucfirst($con->model_type) }}"
                                data-m-id                   = "{{ $con->model_id }}"
                                data-m-nome                 = "{{ $con->job->nome }}"
                                data-conta                  = "{{ $con->conta_paypal }}"
                                data-valor-de               = "{{ $con->valor_de }}"
                                data-valor-para             = "{{ $con->valor_para }}"
                                data-valor-taxa             = "{{ $con->valor_taxa }}"
                                data-job-pago-em            = "{{ $job_pago_em }}"
                                data-job-pago-original      = "{{ $job_pago_em_original }}"
                                data-rota                   = "{{ route('confirmar.pagamento') }}"
                                data-token                  = "{{ @csrf_token() }}"
                                data-tipo-modal             = "{{ $con->status==1 && $con->pagador_id == null ? 'form': 'detalhes' }}"
                                data-texto-titulo           = "{{ __('messages.Pagamento do Publicador')  }}"

                                >
                                {{ $con->status==1 ? __('messages.Confirmar'): __('messages.Visualizar') }} {{ __('messages.Pagamento Publicador') }}
                            </a>
                            @if( $con->status >= 2)
                                @php
                                    $pago_em ="";
                                    if($con->pago_em && $con->pago_em != null ) {
                                        $pago_em = $con->pago_em->format('d/m/Y') ;
                                    }
                                @endphp
                                | 
                                <a 
                                    class                       = "confirmar-pagamento-freelancer"
                                    href                        = "javascript:void(0);"
                                    data-id                     = "{{ $con->id }}"
                                    data-de                     = "{{ $con->de->name }}"
                                    data-para                   = "{{ $con->para->name }}"
                                    data-model                  = "{{ encrypt($con->job->id) }}"
                                    data-pagador                = "{{ Auth::user()->name }}"
                                    data-taxa                   = "{{ $con->taxa }}"
                                    data-cc                     = "{{ $con->centro_de_custo_id }}"
                                    data-cat-custo              = "{{ $con->categoria_de_custo_id }}"
                                    data-m-type                 = "{{ ucfirst($con->model_type) }}"
                                    data-m-id                   = "{{ $con->model_id }}"
                                    data-m-nome                 = "{{ $con->job->nome }}"
                                    data-conta                  = "{{ $con->conta_paypal }}"
                                    data-valor-de               = "{{ $con->valor_de }}"
                                    data-valor-para             = "{{ $con->valor_para }}"
                                    data-valor-taxa             = "{{ $con->valor_taxa }}"
                                    data-job-pago-em            = "{{ $pago_em }}"
                                    data-job-para-pago-em       = "{{ $pago_em }}"
                                    data-rota                   = "{{ route('confirmar.pagamento.freelancer') }}"
                                    data-token                  = "{{ @csrf_token() }}"
                                    data-tipo-modal             = "{{ $con->status==2 && $con->para_pagador_id == null ? 'form': 'detalhes' }}"
                                    data-texto-titulo           = "{{ __('messages.Liberação de Pagamento')  }}"

                                    >
                                    {{ $con->status==2 && $con->para_pagador_id == null ? __('messages.Liberar'): __('messages.Visualizar') }} {{ __('messages.Pagamento Freelancer') }}
                                </a>
                            @endif

                        </td>
                        
                    </tr>
                @endforeach
                {{-- {{ dd($dado_pag['lista_pagamentos']->last()) }} --}}
                </tbody>
                <tfoot>

                </tfoot>
            </table>
        @endunless
    </div>
</div>

@push('js')
    <script src="{{ asset('js/confirma_pagamento.js')}}"></script>

    {{-- <script src="{{ asset('js/jquery.dataTables.js')}}"></script> --}}
    {{-- 
    <script>
      $(function () {
        $('#lista-dashboard').DataTable({
          "paging": false,
          "lengthChange": true,
          "searching": true,
          "ordering": true,
          "info": true,
          "autoWidth": false,
          'sProcessing': 'Processando...',

        });

        $('[type="search"]').addClass("form-control")

        $("select[name='lista-dashboard_length']").addClass("custom-select custom-select-sm form-control form-control-sm")


      });

    </script> --}}

@endpush