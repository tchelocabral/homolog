<div class="row largura90 centralizado">
    <h1 class="margemB40">{{ __('messages.Recebimentos')}}</h1>

    <div class="col-md-12">
        {{-- {{ dd(empty($dado_pag['lista_recebimentos'])) }} --}}
        @if(!empty($dado_pag['lista_recebimentos']) && $dado_pag['lista_recebimentos']->count() > 0)
            <table id="lista-dashboard" class="table table-striped larguraTotal">
                <thead class="">
                <tr class="">
                    <th colspan="" class="box-title th-ocean texto-branco padding12 com-border-left">#</th>
                    <th colspan="" class="box-title th-ocean texto-branco padding12">{{ __('messages.Tipo Serviço')}}</th>
                    <th colspan="" class="box-title th-ocean texto-branco padding12">{{ __('messages.Cód. Serviço')}}</th>
                    <th colspan="" class="box-title th-ocean texto-branco padding12">{{ __('messages.Valor')}}</th>
                    <th colspan="" class="box-title th-ocean texto-branco padding12">{{ __('messages.Status')}}</th>
                    <th colspan="" class="box-title th-ocean texto-branco padding12">{{ __('messages.Data da Liberação')}}</th>
                    <th colspan="" class="box-title th-ocean texto-branco padding12">{{ __('messages.Data do Pagamento')}}</th>
                    @if($permissao == 'admin')
                        <th colspan="" class="box-title th-ocean texto-branco padding12 com-border-right">{{ __('messages.Ações')}}</th>
                    @endif
                </tr>
                </thead>
                <tbody class="fundo-branco com-shadow">
                @foreach($dado_pag['lista_recebimentos'] as $con)
                    <tr class="">
                        <td class="desktop">#{{ $con->id }}</td>
                        <td>{{ ucfirst($con->model_type)  }}</td>
                        <td>{{ $con->job->nome  }}</td>
                        <td>R$ {{($con->valor_para)}}</td>
                        <td>{{ $con->status==1 ? __('messages.Pendente'):  __('messages.Executado')  }}</td>
                        <td>{{ $con->created_at->format('d/m/Y')  }}</td>
                        {{-- <td>{{  }}</td> --}}
                        <td>
                            {{ $con->pago_em ? $con->pago_em->format('d/m/Y') : '-' }}
                        </td>                        
                        @if($permissao == 'admin')
                            <td>
                                @if($con->status==2) 
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
                                        data-conta      = "{{ $con->dados_bancarios }}"
                                        data-valor-de   = "{{ $con->valor_de }}"
                                        data-valor-para = "{{ $con->valor_para }}"
                                        data-valor-taxa = "{{ $con->valor_taxa }}"
                                        data-rota       = "{{ route('confirmar.pagamento') }}"
                                        data-token      = "{{ @csrf_token() }}"
                                        data-tipo-modal = 'detalhes'>
                                        {{ __('messages.Visualizar Recebimento')}}
                                    </a>
                                @else
                                    <p> - </p>
                                @endif
                            </td>
                        @endif

                    </tr>
                @endforeach
                </tbody>
                <tfoot>

                </tfoot>
            </table>
        @else
            <p>{{ __('messages.Não Existem valors para crédito')}}</p>
        @endif
    </div>
</div>

@push('js')
{{-- <script src="{{ asset('js/jquery.dataTables.js')}}"></script>

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