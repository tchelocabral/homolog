<input type="hidden" name="contato_id" value="{{ $contato ? $contato->id : -1 }}" >

<div class="col-md-4">
    <h3 class="margem-bottom-menor">{{ __('messages.Nome')}}</h3>
    @isset($detalhe)
         <h4>{{ $contato->nome ?? '-' }}</h4>
    @else
        @isset($contato)
            <input type="text" name="nome_contato" id="nome_contato" class="form-control margemT5" value="{{ $contato->nome ?? old('nome_contato') }}" placeholder="{{ __('messages.Nome do contato')}}" >
        @else
            <input type="text" name="nome_contato" id="nome_contato" class="form-control margemT5" value="{{ old('nome_contato') ?? '' }}" placeholder="{{ __('messages.Nome do contato')}}" >
        @endisset
    @endisset
</div>

<div class="col-md-4">
    <h3 class="margem-bottom-menor">{{ __('messages.Telefone')}}</h3>
    @isset($detalhe)
        <h4>{{ $contato->tel ?? '-' }}</h4>
    @else
        @isset($contato)
            <input type="text" name="tel_contato" id="tel_contato" class="tel form-control margemT5" value="{{ $contato->tel ?? old('tel_contato') }}" placeholder="" >
        @else
            <input type="text" name="tel_contato" id="tel_contato" class="tel form-control margemT5" value="{{ old('tel_contato') ?? '' }}" placeholder="" >
        @endisset
    @endisset
</div>

<div class="col-md-4">
    <h3 class="margem-bottom-menor">{{ __('messages.Celular')}}</h3>
    @isset($detalhe)
        <h4>{{ $contato->cel ?? '-' }}</h4>
    @else
        @isset($contato)
            <input type="text" name="cel_contato" id="cel_contato" class="tel form-control margemT5" value="{{ $contato->cel ?? old('cel_contato') }}" placeholder="" >
        @else
            <input type="text" name="cel_contato" id="cel_contato" class="tel form-control margemT5" value="{{ old('cel_contato')  ?? '' }}" placeholder="" >
        @endisset
    @endisset
</div>

<div class="col-md-4">
    <h3 class="margem-bottom-menor">{{ __('messages.E-mail')}}</h3>
    @isset($detalhe)
       <h4>{{ $contato->email ?? '-' }}</h4>
    @else
        @isset($contato)
            <input type="email" name="email_contato" id="email_contato" class="col-md-12 form-control margemT5" value="{{ $contato->email ?? old('email_contato') }}" placeholder="" >
        @else
            <input type="email" name="email_contato" id="email_contato" class="col-md-12 form-control margemT5" value="{{ old('email_contato') ?? '' }}" placeholder="" >
        @endisset
    @endisset
</div>

