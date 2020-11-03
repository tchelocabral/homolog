
<div class="row largura90 centralizado">
    <h1 class="margemB40">{{ __('messages.Pagamentos')}}</h1>

    <div class="col-md-12">
        @unless($dado_pag['lista_pagamentos_jobs']->count())
            <p>-</p>
        @else
            <table id="lista-dashboard" class="table table-striped larguraTotal">   
                <thead class="">
                <tr class="">
                    <th colspan="" class="box-title th-ocean texto-branco padding12 com-border-left">#</th>
                    <th colspan="" class="box-title th-ocean texto-branco padding12">{{ __('messages.Nome do Job')}}</th>
                    <th colspan="" class="box-title th-ocean texto-branco padding12">{{ __('messages.Tipo de Job')}}</th>
                    <th colspan="" class="box-title th-ocean texto-branco padding12">{{ __('messages.Criado em')}}</th>
                    <th colspan="" class="box-title th-ocean texto-branco padding12">{{ __('messages.Publicado em')}}</th>
                    <th colspan="" class="box-title th-ocean texto-branco padding12">{{ __('messages.Concluido em')}}</th>
                    <th colspan="" class="box-title th-ocean texto-branco padding12">{{ __('messages.Valor Total')}}</th>
                    <th colspan="" class="box-title th-ocean texto-branco padding12">{{ __('messages.Pago em')}}</th>
                    {{-- <th colspan="" class="box-title th-ocean texto-branco padding12">{{ __('messages.Valor Taxas')}}</th> --}}
                    {{-- @hasanyrole('admin|desenvolvedor')
                        <th colspan="" class="box-title th-ocean texto-branco padding12">{{ __('messages.Valor Freela')}}</th>
                    @endhasanyrole --}}
                    {{-- <th colspan="" class="box-title th-ocean texto-branco padding12">{{ __('messages.Status')}}</th> --}}
                    {{-- <th colspan="" class="box-title th-ocean texto-branco padding12 com-border-right">{{ __('messages.Ações')}}</th> --}}
                </tr>
                </thead>
                <tbody class="fundo-branco com-shadow">
                    @csrf
                @foreach($dado_pag['lista_pagamentos_jobs'] as $pag)
                    {{-- @php $con->conta_paypal = json_decode($con->dados_bancarios, true)['conta_paypal'] ?? 'Não informado';  @endphp --}}
                    <tr class="">
                        <td>#{{ $pag->id }}</td>
                        <td>{{ $pag->nome  }}</td>
                        <td>{{ $pag->tipo->nome  }}</td>
                        <td>{{ $pag->created_at  ? $pag->created_at->format('d/m/Y')  : '-'  }}</td>
                        <td>{{ $pag->data_inicio ? $pag->data_inicio->format('d/m/Y') : '-' }}</td>
                        <td>{{ $pag->data_entrega ? $pag->data_entrega->format('d/m/Y') : '-' }}</td>
                        <td>R$ {{ $pag->valor_job }}</td>
                        <td>{{ $pag->pago_em ? $pag->pago_em->format('d/m/Y') : '-' }}</td>
                        {{-- <td>R$ {{ (floatval($pag->taxa / 100)*floatval($pag->valor_job)) }}</td> --}}
                        {{-- <td>
                            <a 
                                class            = "confirmar-pagamento"
                                href            = "javascript:void(0);"
                                data-id         = "{{ $con->id }}"
                                data-de         = "{{ $con->de->name }}"
                                data-para       = "{{ $con->para->name }}"
                                data-model      = "{{ encrypt($con->job->id) }}"
                                data-pagador    = "{{ Auth::user()->name }}"
                                data-taxa       = "{{ $con->taxa }}"
                                data-cc         = "{{ $con->centro_de_custo_id }}"
                                data-cat-custo  = "{{ $con->categoria_de_custo_id }}"
                                data-m-type     = "{{ ucfirst($con->model_type) }}"
                                data-m-id       = "{{ $con->model_id }}"
                                data-m-nome     = "{{ $con->job->nome }}"
                                data-conta      = "{{ $con->conta_paypal }}"
                                data-valor-de   = "{{ $con->valor_de }}"
                                data-valor-para = "{{ $con->valor_para }}"
                                data-valor-taxa = "{{ $con->valor_taxa }}"
                                data-rota       = "{{ route('confirmar.pagamento') }}"
                                data-token      = "{{ @csrf_token() }}"
                                data-tipo-modal = "{{ $con->status==1 ? 'form': 'detalhes' }}">
                                {{ $con->status==1 ? __('messages.Liberar'): __('messages.Visualizar') }} {{ __('messages.Pagamento') }}
                            </a>
                        </td> --}}
                    </tr>
                @endforeach
                </tbody>
                <tfoot>

                </tfoot>
            </table>
        @endunless
    </div>
</div>
