<div class="row div-item-form">
    <div class="col-md-12">
        <p class=""><b>{{ __('messages.Nome do Job')}}</b></p>
        @isset($detalhe)
            <p id="nome" class="margemB20" >{{ $job['nome'] ?? __('Não Informado')}}</p>
        @else
            <input type="text" name="nome" class="form-control" value="{{ $job['nome']  ?? old('nome') }}" placeholder="{{ __('messages.Digite aqui')}}" />
        @endif
    </div>
</div>

<div class="row div-item-form">
    <div class="col-md-12">
        <p class=""><b>{{ __('messages.Descrição')}}</b></p>
        @isset($detalhe)
            <p id="descricao" class="margemB20" >{{ $job['descricao'] ?? __('messages.Não Informado')}}</p>
        @else
            <textarea name="descricao" class="col-md-12 form-control" value="{{ $job['descricao']  ?? old('descricao') }}" placeholder="" >{{ $job['descricao']  ?? old('descricao') }}</textarea>
        @endif
    </div>
</div>


