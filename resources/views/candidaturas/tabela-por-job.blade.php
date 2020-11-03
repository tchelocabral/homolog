@if(!count($candidaturas) > 0)
<h3>{{ $job->status == $status_array['emproposta'] ? __('messages.Sem propostas para esse Job') : __('messages.Sem candidaturas para esse Job') }}</h3>
{{-- __('messages.Sem propostas para ese Job') --}}
@else

<div class="table-responsive">
    <table class="table table-striped nome-job" >
        <thead class="fundo-escuro">
            <th class="texto-branco">{{ __('messages.Freelancer')}}</th>
            <th class="texto-branco">{{ __('messages.Valor')}}</th>
            {{-- <th class="texto-branco">{{ __('messages.Data de Criação')}}</th> --}}
            <th class="texto-branco">{{ __('messages.Status')}}</th>
            <th class="texto-branco texto-centralizado" >{{ __('messages.Data')}}</th>
            <th class="texto-branco texto-centralizado" >{{ __('messages.Ação')}}</th>
        </thead>
        <tbody>
            @foreach($candidaturas as $cand)
                <tr>
                    <td><a href="{{ route('users.show', encrypt($cand->user->id)) }}"> {{ $cand->user->name }}</a> </td>
                    <td>
                        @php $money = $cand->valor_proposta_calculo; @endphp
                        R$  @convert_money($money)
                    </td>
                    {{-- <td>{{ $cand->created_at->format('d/m/Y - H:m') }}</td> --}}
                    {{-- <td>{{ $cand->created_at }}</td> --}}
                    <td>
                        {{ $cand->status==0 ? __('messages.Aberto') : ''  }}
                        {{ $cand->status==1 ? __('messages.Aceito')  : '' }}
                        {{ $cand->status==2 ? __('messages.Recusado') : ''  }}
                        {{ $cand->status==3 ? __('messages.Cancelado') : ''  }}
                        {{ $cand->status==4 ? __('messages.Sem slot') : ''  }}
                        {{ $cand->status==5 ? __('messages.Expirado') : ''  }}
                    </td>
                    <td align="center">
                        {{ $cand->created_at ? $cand->created_at->format('d/m/Y') :  __('messages.Não Informado') }}
                    </td>
                    <td  class="displayFlex flexCentralizado">
                        @if($cand->status==0 )
                            <form action="{{ route('candidatura.mudarStatus', [encrypt($cand->id),1]) }}" class="form-proposta-job "     
                                id="form-proposta-job-{{ $cand->id }}" name="form_proposta_job_{{ $cand->id }}" 
                                method="POST" >
                                @csrf
                                <a href="#" class="btn btn-success confirmar-acao-item margemL5" title="Aceitar" 
                                data-toggle="tooltip" type="submit" 
                                data-title="{{__('messages.Confirma aceitar esta proposta?')}}" 
                                data-url="{{ route('candidatura.mudarStatus', [encrypt($cand->id),1]) }}">
                                    <i class="fa fa-check-circle" aria-hidden="true"></i>
                                </a>
                            </form>
                            <form action="{{ route('candidatura.mudarStatus', [encrypt($cand->id),2]) }}" class="form-proposta-job "     
                                id="form-proposta-job-{{ $cand->id }}" name="form_proposta_job_{{ $cand->id }}" 
                                method="POST" >
                                @csrf
                                <a href="#" class="btn btn-danger confirmar-acao-item margemL5" title="Recusar" 
                                data-toggle="tooltip" type="submit"
                                data-title="{{__('messages.Confirma recusar esta proposta?')}}" 
                                data-url="{{ route('candidatura.mudarStatus', [encrypt($cand->id),2]) }}">
                                    <i class="fa fa-ban" aria-hidden="true"></i>
                                </a>
                            </form>
                        @endif
                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>    
</div>
@endif