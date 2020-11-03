
<input type="hidden" name="endereco_id" value="{{ $endereco ? $endereco->id : -1 }}" />

<div class="col-md-4">
    <h3 class="margem-bottom-menor">{{ __('messages.CEP')}}</h3>
    @isset($detalhe)
        <h4>{{ $endereco['cep'] ?? '-' }}</h4>
    @else
        <input type="text" name="cep" id="cep" class="form-control margemT5 cep" value="{{ $endereco['cep'] ?? old('cep') }}" placeholder="" maxlength="9" />
    @endisset
</div>

<div class="col-md-4">
    <h3 class="margem-bottom-menor">{{ __('messages.Logradouro')}}</h3>
    @isset($detalhe)
        <h4>{{  $endereco['logradouro'] ?? '-'  }} </h4>
    @else
        <input type="text" name="logradouro" id="logradouro" class="form-control margemT5" value="{{  $endereco['logradouro'] ?? old('logradouro') }}" placeholder="" />
    @endisset
</div>

<div class="col-md-4">
    <h3 class="margem-bottom-menor">{{ __('messages.Bairro')}}</h3>
    @isset($detalhe)
        <h4>{{  $endereco['bairro'] ?? '-'  }} </h4>
    @else
        <input type="text" name="bairro" id="bairro" class="col-md-12 form-control margemT5" value="{{  $endereco['bairro'] ?? old('bairro') }}" placeholder="" />
    @endisset
</div>

<div class="col-md-4">
    <h3 class="margem-bottom-menor">{{ __('messages.Cidade')}}</h3>
    @isset($detalhe)
        <h4>{{  $endereco['cidade'] ?? '-'  }} </h4>
    @else
        <input type="text" name="cidade" id="cidade" class="col-md-12 form-control margemT5" value="{{  $endereco['cidade'] ?? old('cidade') }}" placeholder="" />
    @endisset
</div>

<div class="col-md-4">
    <h3 class="margem-bottom-menor">{{ __('messages.UF')}}</h3>
    @isset($detalhe)
        <H4>{{  $endereco['uf'] ?? '-'  }} </H4>
    @else
        <input type="text" name="uf" id="uf" class="col-md-12 form-control margemT5" value="{{  $endereco['uf'] ?? old('uf') }}" placeholder="" />
    @endisset
</div>

<div class="col-md-4">
    <h3 class="margem-bottom-menor">{{ __('messages.Número')}}</h3>
    @isset($detalhe)
        <h4>{{  $endereco['numero'] ?? '-' }} </h4>
    @else
        <input type="number" name="numero" id="numero" class="col-md-12 form-control margemT5" value="{{  $endereco['numero'] ?? old('numero') }}" placeholder="" />
    @endisset
</div>

<div class="col-md-8">
    <h3 class="margem-bottom-menor">{{ __('messages.Complemento')}}</b></p>
    @isset($detalhe)
        <h4>{{  $endereco['complemento'] ?? '-'}} </h4>
    @else
        <input type="text" name="complemento" id="complemento" class="col-md-12 form-control margemT5" value="{{  $endereco['complemento'] ?? old('complemento') }}" placeholder="" />
    @endisset
</div>

