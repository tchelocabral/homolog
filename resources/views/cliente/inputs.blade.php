
<!-- logo -->
<div class="col-md-4">
    @isset($detalhe)
        {{-- <h3 class="margem-bottom-menor margemB20">{{ __('messages.Logo')}}</h3> --}}
        @if($cliente->logo)
             <img src="{{URL::asset('storage/' . $cliente->logo)}}" alt="{{ __('messages.Referência do Job')}}" class="img-responsive card-job-thumb margemT40">
        @else
            <img src="{{asset('storage/imagens/jobs/job_default.png')}}" alt="{{ __('messages.Referência do Job')}}" class="img-responsive card-job-thumb margemT40">
        @endif
    @else
        <h3 class="margem-bottom-menor ">{{ __('messages.Logo')}}</h3>

        <div class="input-group image-preview ">
            <input type="text" class="form-control margemT5 image-preview-filename" disabled="disabled" placeholder="{{ __('messages.Nenhuma imagem selecionada')}}" name="{{ __('messages.logo')}}" />

            <span class="input-group-btn">
                <!-- image-preview-clear button -->
                <button type="button" class="btn btn-default image-preview-clear" style="display:none;">
                    <span class="glyphicon glyphicon-remove"></span> {{ __('messages.Limpar')}}
                </button>
                <!-- image-preview-input -->
                <div class="btn btn-default image-preview-input">
                    <span class="glyphicon glyphicon-folder-open"></span>
                    <span class="image-preview-input-title">{{ __('messages.Procurar')}}</span>
                    <input type="file" accept="image/x-png, image/jpeg, image/gif" name="logo" value="{{ $cliente['logo'] ?? old('logo') }}" />
                </div>
            </span>

        </div>

        @if($cliente)
            <div class="img-avatar no-style margemT10">
                <div class="bkg-img-avatar no-style"  
                    style="{{ isset($cliente) && $cliente->logo ? 'background:url(' .URL::asset('storage/' .$cliente->logo) . ')' : '' }}">
                </div>
            </div>
        @endif
      
    @endisset
</div>

<div class="col-md-4">
    <h3 class="margem-bottom-menor">{{ __('messages.Nome Fantasia') }}</h3>
    @isset($detalhe)
        <h4>{{ $cliente['nome_fantasia'] ?? old('nome_fantasia')  }} </h4>
    @else
        <input type="text" name="nome_fantasia" id="nome_fantasia" class="form-control margemT5" value="{{ $cliente->nome_fantasia ?? old('nome_fantasia') }}" placeholder="" />
    @endisset
</div>


<div class="col-md-4">
    <h3 class="margem-bottom-menor">{{ __('messages.CNPJ Master')}}</h3>
    @isset($detalhe)
        <h4> {{ $cliente['cnpj'] ?? old('cnpj') }}</h4>
    @else
        <input type="text" name="cnpj" id="cnpj" class="form-control margemT5 cnpj" value="{{ $cliente['cnpj'] ?? old('cnpj') }}" placeholder="" />
    @endisset
</div>




@isset($detalhe)
    <div class="col-md-6">
        <h3 class="margem-bottom-menor">{{ __('messages.Desde')}}</h3>
        <h4>{{ $cliente ? \Carbon\Carbon::parse($cliente->created_at)->format('d/m/Y') : __('messages.Não Informado')}}</h4>
    </div>
    <div class="col-md-4">
        <h3 class="margem-bottom-menor">{{ __('messages.Última Atualização')}}</h3>
        <h4>{{ $cliente ? \Carbon\Carbon::parse($cliente->updated_at)->format('d/m/Y') : __('messages.Não Informado')}}</h4>
    </div>
@endisset

