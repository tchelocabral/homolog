
<div class="col-md-5  col-md-offset-1">

	<div class="row div-item-form">
	    <div class="col-md-12">
	        <p class=""><b><span id="span-nome">{{ isset($user_roles) ? (in_array($tutorial_tipo, $user_roles) ? __('messages.Display Name') : __('messages.Nome Tutorial')) : __('messages.Nome Tutorial')}}</span></b></p>
	        @isset($detalhe)
	            <p id="nome" class="margemB20" >{{ $tutorial->name ?? 'Não Informado' }}</p>
	        @else
	            <input type="text" name="nome" class="form-control" value="{{ $tutorial->nome  ?? old('nome') }}" placeholder="{{ __('messages.Digite aqui')}}" >
	        @endif
	    </div>
	</div>

	<div class="row div-item-form">		
		<div class="col-md-12">
			<p><b>URL</b></p>
			@isset($detalhe)
				<p id="url" class="margemB20" >{{  $tutorial->url ?? old('url')  }} </p>
			@else
				<input type="text" name="url" id="url" class="col-md-12 form-control" value="{{  $tutorial->url ?? old('url') }}" placeholder="{{__('messages.Digite o embed do vídeo')}}">
			@endisset
		</div>
	</div>
</div>
<div class="col-md-5  col-md-offset-1">
	<div class="row div-item-form">
	    <div class="col-md-12">
	        <p class=""><b>{{__('messages.Descrição')}}</b></p>
	        @isset($detalhe)
	            <p id="descricao" class="margemB20" >{{ $tutorial->bio ??  'Não Informado' }}</p>
	        @else
	            <textarea id="descricao" name="descricao" class="form-control" placeholder="{{__('messages.Descrição do tutorial')}}">{{ $tutorial->descricao  ?? old('descricao') }}</textarea>
	        @endif
	    </div>
	</div>
</div>
