
@isset($projeto)
<input type="hidden" name="projeto_id" value="{{ $projeto->id }}" />
@endisset

<div class="row div-item-form">
    <div class="col-md-12">
        <p class="detalhe-label"><b>{{ __('messages.Nome do Projeto')}}</b></p>
        @isset($detalhe)
        <p id="nome" class="margemB20 info-detalhe-maior" >{{ $projeto['nome'] ??  __('messages.Não Informado') }}</p>
        @else
        <input type="text" name="nome" class="form-control margemBottom" value="{{ $projeto['nome']  ?? old('nome') }}" placeholder="{{ __('messages.Digite aqui')}}" />
        @endif
    </div>
    
    <div class="col-md-4">
        <p class="detalhe-label"><b>{{ __('messages.Cliente')}}</b></p>
        @isset($detalhe)
        <p class="info-detalhe-maior">{{ $projeto->cliente->nome_fantasia ??  __('messages.Não Informado') }}</p>
        @else
        <select id="combo-clientes" name="cliente_id" class="form-control select2 margemT10">
            @isset($clientes)
            @unless($clientes)
            <option value="">{{ __('messages.Sem Clientes Cadastrados')}}</option>
            @else
            @foreach($clientes as $cli)
            <option value="{{ $cli->id }}" 
                {{ isset($projeto) && $cli->id == $projeto->cliente->id ? 'selected="selected"' : ''  }}>{{ $cli->nome_fantasia }}
            </option>
            @endforeach
            @endunless
            @endif
        </select>
        @endif
    </div>
    
    <div class="col-md-4">
        <p class="detalhe-label"><b>{{ __('messages.Coordenador')}}</b></p>
        @isset($detalhe)
        <p class="info-detalhe-maior">{{ isset($projeto->coordenador) ? $projeto->coordenador->name :  __('messages.Não Informado') }}</p>
        @else
        @can('atualiza-projeto')
        <select id="combo-coordenador" name="coordenador_id" class="form-control select2 margemT10">
            @isset($coordenadores)
            @unless($coordenadores)
            <option value="">{{ __('messages.Sem Coordenadores Cadastrados')}}</option>
            @else
            <option value="-1">{{ __('messages.Sem Coordenador')}}</option>
            <option value="" disabled>-----------------------</option>
            @foreach($coordenadores as $coord)
            <option value="{{ $coord->id }}"
                {{ isset($projeto) && isset($projeto->coordenador) && $coord->id == $projeto->coordenador->id ? 'selected="selected"' : ''  }}>{{ $coord->name }}
            </option>
            @endforeach                        
            @endunless
            @endif
        </select>
        @endcan
        @endif
    </div>
    
    <div class="col-md-4">
        <p class="detalhe-label"><b>{{ __('messages.Previsão de Entrega')}}</b></p>
        @isset($detalhe)
        <p id="data_previsao_entrega" class="margemB20 info-detalhe-maior" >{{  $projeto->data_previsao_entrega ? $projeto->data_previsao_entrega->format('d/m/Y') :  __('messages.Não Informado') }}</p>
        @else
        <input type="date" name="data_previsao_entrega" class="form-control" value="{{ isset($projeto) && $projeto->data_previsao_entrega ? $projeto->data_previsao_entrega->format('Y-m-d') : old('data_previsao_entrega') }}" min="{{date('Y-m-d')}}" />
        @endif
    </div>
    
</div> {{-- end row --}}

<div class="row div-item-form">
    <div class="col-md-12">
        <p class="detalhe-label"><b>{{ __('messages.Descrição')}}</b></p>
        @isset($detalhe)
        <p id="descricao" class="margemB20 info-detalhe-maior" >{{ $projeto['descricao'] ??  __('messages.Não Informado') }}</p>
        @else
        <input type="text" name="descricao" class="form-control" value="{{ $projeto['descricao']  ?? old('descricao') }}" placeholder="" />
        @endif
    </div>
</div>

<div class="row div-item-form">
    <div class="col-md-12">
        <p class="detalhe-label"><b>{{ __('messages.Observações')}}</b></p>
        @isset($detalhe)
        <p id="observacoes" class="margemB20 info-detalhe-maior" >{{ $projeto['observacoes'] ??  __('messages.Não Informado') }}</p>
        @else
        <textarea id="observacoes" name="observacoes" class="form-control">{{ $projeto['observacoes']  ?? old('observacoes') }}</textarea>
        @endif
    </div>
</div>