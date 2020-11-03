
<div class="col-md-12">
	<div class="row div-item-form margemT20">
        <div class="col-md-12">

			@if($usuario != null)
				<div class="row div-item-form margemT10">
					<div class="col-md-12">
						<a href="{{ route('user.nova.senha', encrypt($usuario->id)) }}" class="btn btn-info margemL10 pull-right">{{__('messages.Resetar Senha')}}</a>
						<!-- <p><b>Senha</b></p>
						<input type="password" name="password" id="email" class="col-md-12 form-control"  placeholder="{{__('Usado também para Login')}}">
						<p><b>Confirme sua senha</b></p>
			
						<input type="password" name="password_confirmation" id="email" class="col-md-12 form-control"  placeholder="{{__('Repita a senha')}}">
						@if ($errors->has('password_confirmation'))
							<span class="help-block">
								<strong>{{ $errors->first('password_confirmation') }}</strong>
							</span>
						@endif -->
					</div>
				</div>    
			@endif

			@php $usuario_tipo =""; @endphp
			@if($tipo == 'Membro' || (isset($usuario) && $usuario->mudar_tipo))

				<p class="margemB20"><b>{{ __('messages.Tipo de Membro') }}</b></p>
				<input type="hidden" name="tipo" value="{{ isset($tipo) ? $tipo : ''}}">
				
				@isset($detalhe)
					<p id="tipo" class="margemB20" >{{  $usuario->tipo_usuario ?? old('tipo')  }} </p>
				@else
					<!-- Usuário que criado por um nao-publicador fica com publicador_id nullo -->
					<input type="hidden" name="publicador_id" id="publicador_id" value="">
	
					@foreach($roles as $role)
						@php 
							$usuario_tipo = $role->name == "publicador" ? $role->id : -1   
						@endphp
						@if ($role->name=="desenvolvedor" && !\Auth::user()->hasAnyRole('desenvolvedor'))

						@else
							<div class="col-md-2 margemT10">
								<input type="radio" id="role-{{$role->id}}" name="roles[]" value="{{ $role->id }}" class="tipo-membro"
									{{ isset($usuario) && in_array($role->id, $user_roles) ? 'checked="checked"' : '' }} data-nome="{{ $role->name }}" 
								/>{{ ucfirst($role->name) }}
							</div>
						@endif 
					@endforeach

				@endisset
			@elseif(isset($tipo) && $tipo == "Coordenador")
				<input type="hidden" name="roles[]" value="{{$roles->get()->first()->id}}">
				<input type="hidden" name="publicador_id" id="publicador_id" value="{{Auth::user()->id}}">
			@else
				<input type="hidden" name="roles[]" value="{{$user_roles[0] ?? ''}}">

				{{-- <p id="tipo" class="margemB20" >{{  $usuario->tipo_usuario ?? old('tipo')  }} </p> --}}
			@endif
				
        </div>
    </div>
</div>


<div class="col-md-5">
    <!-- <h3>Dados Fullfreela</h3> -->
    <hr>

    <div class="row div-item-form">
        <div class="col-md-12">
            <p><b>{{__('Avatar')}}</b></p>
            <div class="img-avatar">
                <div class="bkg-img-avatar" 
                	style="{{ isset($usuario) && $usuario->image ? 'background:url(' . URL::asset($usuario->image) . ')' : '' }}">
              		
              	</div>
            </div>
            <!-- image-preview-filename input [CUT FROM HERE]-->
            <div class="input-group image-preview margemT20">
                <input type="text" class="form-control image-preview-filename" disabled="disabled" placeholder="{{__('messages.Tamanho recomendado')}}: 100X100px">
                 <!-- don't give a name === doesn't send on POST/GET -->
                <span class="input-group-btn">
                    <!-- image-preview-clear button -->
                    <button type="button" class="btn btn-default image-preview-clear" id="imagem-avatar-limpar" style="display:none;">
                        <span id="btn-file-avatar-clear" class="glyphicon glyphicon-remove"></span> {{__('messages.Limpar')}}
                    </button>
                    <!-- image-preview-input -->
                    <div id="btn-file-avatar" class="btn btn-default image-preview-input">
                        <span class="glyphicon glyphicon-folder-open"></span>
                        <span class="image-preview-input-title">{{__('messages.Procurar')}}</span>
                        <input type="file" id="image-avatar" accept="image/png, image/jpeg, image/gif" name="image"/> <!-- rename it -->
                    </div>
                </span>
            </div><!-- /input-group image-preview [TO HERE]-->
        </div>
    </div>

    <div class="row div-item-form margemT40">
 
		@isset($usuario)
			@if($usuario->mostra_porfolio)
				<div class="col-md-12 margemT10">
					<p><b>{{ __('messages.Portfólio') . ' URL' }}</b></p>
					@isset($detalhe)
						<p id="url-portfolio" class="margemB20" >{{  $usuario->url_portfolio ?? old('url_portfolio')  }} </p>
					@else
						<input type="url" value="{{ $usuario->url_portfolio}}"  class="col-md-12 form-control" name="url_portfolio" id="url-portfolio">
					@endisset
				</div>
			@endif
		@endisset
		

    </div>

    <div class="row div-item-form">
        <div class="col-md-12"> 
			@isset($usuario)
				@if($usuario->mostra_porfolio)
				{{-- @can("tem-galeria") CRIAR E UTILIZAR ESTA PERMISSION --}}
						<p><b>{{__('messages.Imagens Galeria')}}</b></p>
						<!-- image-preview-filename input [CUT FROM HERE]-->
					@isset($detalhe)
						{{ __('messages.Lista Imagens galeria') }}
						@foreach($usuario->galeria as $index => $gal)
							{{dd($gal)}}
						@endforeach
					@else
						<div class="row div-item-form">
							@foreach($usuario->galeria as $index => $gal)
							<div class="col-md-4 margemB10">
									<input type="checkbox" name="excluir_imagem_galeria[]" id="excluir-imagem-galeria-{{$gal->id}}" value="{{$gal->id}}"> {{ __('messages.Deletar Imagem')}}</a>
									<a class="grouped_elements" rel="group1" href="{{asset('storage/'. $gal->value)}}">
										<img img src="{{asset('storage/'. $gal->value)}}" class="" style="width:100%; max-height:80px">
									</a>
						</div>
							@endforeach
						</div>
						@for ($count_galeria = 0; $count_galeria < $usuario->qtd_galeria; $count_galeria++)


							<div class="input-group image-preview">
								<input id="arquivo-preview-{{ $count_galeria  }}" type="text" class="form-control image-preview-filename" disabled="disabled" placeholder="Nenhum arquivo selecionado" />
								<span class="input-group-btn">
									
									<!-- image-preview-input -->
									<div class="btn btn-default image-preview-input">
										<span class="glyphicon glyphicon-folder-open"></span>
										<span class="image-preview-input-title">{{ __('messages.Procurar')}}</span>
										<input id="input-arquivo-{{ $count_galeria  }}" name="galeria[]" type="file" accept="image/png, image/jpeg, image/gif"  />
									</div>
									<!-- image-preview-clear button -->
									<button type="button" class="btn btn-default image-preview-clear" >
										<span class="glyphicon glyphicon-remove"></span> {{ __('messages.Limpar')}}
									</button>
								</span>
								<input id="arquivo-{{ $count_galeria  }}" type="file" accept="*" name="galeria[]" style="position: absolute; top: 0px; right: 1000vw;" /> 
							</div>



							{{-- <div class="input-group image-preview margemT10">
								<input type="text" class="form-control image-preview-filename" 
								disabled="disabled" placeholder="{{ __('messages.Tamanho recomendado')}}: 100X100px"
								id="arquivo-preview-{{$count_galeria+1}}" 
								name="caminho_arquivo[{{$count_galeria+1}}][]" 
								>
								<!-- don't give a name === doesn't send on POST/GET -->
								<span class="input-group-btn">
									<!-- image-preview-clear button -->
									<button type="button" class="btn btn-default image-preview-clear" 
										style="display:none;" id="buttom_image_pin_clear-{{$count_galeria+1}}">
										<span class="glyphicon glyphicon-remove"></span> {{__('messages.Limpar')}}
									</button>
									<!-- image-preview-input -->
									<div class="btn btn-default image-preview-input">
										<span class="glyphicon glyphicon-folder-open"></span>
										<span class="image-preview-input-title">{{__('messages.Procurar')}}</span>
										<input type="file"  id="input-arquivo-{{$count_galeria+1}}"
											name="input-arquivo[{{$count_galeria+1}}][]"  
											accept="image/png, image/jpeg, image/gif" 
											name="image"/> <!-- rename it -->
									</div>
								</span>
							</div> --}}
							
							<!-- /input-group image-preview [TO HERE]-->
						@endfor
					@endisset
				{{-- @endcan --}}
				@endif
			@endisset
		</div>
    </div>

</div>

<div class="col-md-5  col-md-offset-1">
    <!-- <h3>Dados do Usuário</h3> -->
    <hr>
	
	<div class="row div-item-form {{ isset($usuario) && $usuario->nome_role == "publicador" ? 'visivel' : 'invisivel'}}" id="divEmprresa">
	    <div class="col-md-6">
	        <p><b>{{__('messages.Razão Social')}}</b></p>
	        @isset($detalhe)
	            <p id="razao_social" class="margemB20" >{{  $usuario->razao_social ?? old('razao_social') }} </p>
	        @else
	            <input type="text" name="razao_social" id="razao_social" class="col-md-12 form-control " value="{{  $usuario->razao_social ?? old('razao_social') }}">
	        @endisset
	    </div>

	    <div class="col-md-6">
	        <p><b>{{__('messages.Nome Fantasia')}}</b></p>
	        @isset($detalhe)
	            <p id="nome_fantasia" class="margemB20" >{{  $usuario->nome_fantasia ?? old('nome_fantasia') }} </p>
	        @else
	            <input type="text" name="nome_fantasia" id="nome_fantasia" class="col-md-12 form-control " value="{{  $usuario->nome_fantasia ?? old('nome_fantasia') }}">
	        @endisset
	    </div>
	    <div class="col-md-6">
	        <p><b>{{__('messages.CNPJ')}}</b></p>
	        @isset($detalhe)
	            <p id="cnpj" class="margemB20" >{{  $usuario->cnpj ?? old('cnpj') }} </p>
	        @else
	            <input type="text" name="cnpj" id="cnpj" class="col-md-12 form-control " value="{{  $usuario->cnpj ?? old('cnpj') }}">
	        @endisset
	    </div>
	</div>



	<div class="row div-item-form">
		<div class="col-md-6" id="div-display">
	        <p class=""><b><span >{{  __('messages.Display Name') }}</span></b></p>
	        @isset($detalhe)
	            <p id="nome" class="margemB20" >{{ $usuario->display_name ?? 'Não Informado' }}</p>
	        @else
	            <input type="text" name="display_name" class="form-control" value="{{ $usuario->display_name  ?? old('display_name') }}" placeholder="{{ __('messages.Digite aqui')}}" >
	        @endif
		</div>
		
	    <div class="col-md-6">
	        <p class=""><b><span >{{  __('messages.Nome Completo') }}</span></b></p>
	        @isset($detalhe)
	            <p id="nome" class="margemB20" >{{ $usuario->name ?? 'Não Informado' }}</p>
	        @else
	            <input type="text" name="name" class="form-control" value="{{ $usuario->name  ?? old('name') }}" placeholder="{{ __('messages.Digite aqui')}}" >
	        @endif
		</div>


	</div>


	<div class="row div-item-form">		
		<div class="col-md-12">
			<p><b>E-mail</b></p>
			@isset($detalhe)
				<p id="email" class="margemB20" >{{  $usuario->email ?? old('email')  }} </p>
			@else
				<input type="email" name="email" id="email" class="col-md-12 form-control" value="{{  $usuario->email ?? old('email') }}" placeholder="{{__('messages.Usado também para Login')}}">
			@endisset
			@if($usuario==null)
				<p class="form-text text-muted">*{{ __('messages.O novo usuário cadastra sua senha no primeiro login') }}.</p>
			@endif
		</div>

		<div class="col-md-12">
			<p><b>Site</b></p>
			@isset($detalhe)
				<p id="p-site" class="margemB20" >{{  $usuario->site ?? old('site')  }} </p>
			@else
				<input type="text" name="site" id="site" class="col-md-12 form-control" value="{{  $usuario->site ?? old('site') }}" placeholder="{{__('messages.Site')}}">
			@endisset
		</div>


		<div class="col-md-12 margemT10">
			<p><b>{{__('messages.Marcador para Notificações')}}</b></p>
			@isset($detalhe)
				<p id="marcador" class="margemB20" >{{  $usuario->marcador ?? old('marcador')  }} </p>
			@else
				<input type="marcador" name="marcador" id="marcador" class="col-md-12 form-control" value="{{  $usuario->marcador ?? old('marcador') }}" placeholder="{{__('messages.Marcador para comentários e notificações')}}">
			@endisset
		</div>

	</div>

	<div class="row div-item-form">
	    <div class="col-md-12">
	        <p class=""><b>{{__('messages.Bio')}}</b></p>
	        @isset($detalhe)
	            <p id="bio" class="margemB20" >{{ $usuario->bio ??  'Não Informado' }}</p>
	        @else
	            <textarea id="bio" name="bio" class="form-control">{{ $usuario->bio  ?? old('bio') }}</textarea>
	        @endif
	    </div>
	</div>

	<div class="row div-item-form ">
	    <div id="div-sexo" class="col-md-6 {{ isset($user_roles) ? (in_array($usuario_tipo, $user_roles) ? 'invisivel' : '') : 'invisivel'}}">
	        <p><b>{{__('Sexo')}}</b></p>
	        <select id="combo-sexo" name="sexo" class="form-control select2" value="{{ old('sexo')  }}">
	            <option value="">{{__('Selecione')}}...</option>
	            <option value="1" {{ isset($usuario) && $usuario->sexo == 1 ? 'selected="selected"' : '' }} >{{__('messages.Feminino')}}</option>
	            <option value="2" {{ isset($usuario) && $usuario->sexo == 2 ? 'selected="selected"' : '' }} >{{__('messages.Masculino')}}</option>
	            <option value="3" {{ isset($usuario) && $usuario->sexo == 3 ? 'selected="selected"' : '' }} >{{__('messages.Outro')}}</option>
	        </select>
	    </div>
	    <div class="col-md-6">
	        <p class=""><b>{{__('messages.CEP')}}</b></p>
	        @isset($detalhe)
	            <p id="cep" class="" >{{ $usuario->cep ?? old('cep') }}</p>
	        @else
	            <input type="text" name="cep" id="cep" class="form-control cep" value="{{ $usuario->cep ?? old('cep') }}" placeholder="" maxlength="9">
	        @endisset
	    </div>
	</div>

	<div class="row div-item-form">
	    <div class="col-md-12">
	        <p><b>{{__('messages.Logradouro')}}</b></p>
	        @isset($detalhe)
	            <p id="logradouro" class="margemB20" >{{  $usuario->logradouro ?? old('logradouro')  }} </p>
	        @else
	            <input type="text" name="logradouro" id="logradouro" class="form-control" value="{{  $usuario->logradouro ?? old('logradouro') }}" placeholder="">
	        @endisset
	    </div>
	</div>

	<div class="row div-item-form">
	    <div class="col-md-12">
	        <p><b>{{__('messages.Bairro')}}</b></p>
	        @isset($detalhe)
	            <p id="bairro" class="margemB20" >{{  $usuario->bairro ?? old('bairro')  }} </p>
	        @else
	            <input type="text" name="bairro" id="bairro" class="col-md-12 form-control" value="{{  $usuario->bairro ?? old('bairro') }}" placeholder="">
	        @endisset
	    </div>
	</div>

	<div class="row div-item-form">
	    <div class="col-md-8">
	        <p><b>{{__('messages.Cidade')}}</b></p>
	        @isset($detalhe)
	            <p id="cidade" class="margemB20" >{{  $usuario->cidade ?? old('cidade')  }} </p>
	        @else
	            <input type="text" name="cidade" id="cidade" class="col-md-12 form-control" value="{{  $usuario->cidade ?? old('cidade') }}" placeholder="">
	        @endisset
	    </div>

	    <div class="col-md-4">
	        <p><b>{{__('messages.UF')}}</b></p>
	        @isset($detalhe)
	            <p id="uf" class="margemB20" >{{  $usuario->uf ?? old('uf')  }} </p>
	        @else
	            <input type="text" name="uf" id="uf" class="col-md-12 form-control" value="{{  $usuario->uf ?? old('uf') }}" placeholder="">
	        @endisset
	    </div>
	</div>

	

	<div class="row div-item-form">
	    <div class="col-md-6">
	        <p><b>{{__('messages.Número')}}</b></p>
	        @isset($detalhe)
	            <p id="numero" class="margemB20" >{{  $usuario->numero ?? old('numero') }} </p>
	        @else
	            <input type="number" name="numero" id="numero" class="col-md-12 form-control" value="{{  $usuario->numero ?? old('numero') }}" placeholder="">
	        @endisset
	    </div>

	    <div class="col-md-6">
	        <p><b>{{__('messages.Complemento')}}</b></p>
	        @isset($detalhe)
	            <p id="complemento" class="margemB20" >{{  $usuario->complemento ?? old('complemento')}} </p>
	        @else
	            <input type="text" name="complemento" id="complemento" class="col-md-12 form-control" value="{{  $usuario->complemento ?? old('complemento') }}" placeholder="">
	        @endisset
	    </div>
	</div>

	<div class="row div-item-form">
	    <div class="col-md-6">
	        <p><b>{{__('messages.Telefone')}}</b></p>
	        @isset($detalhe)
	            <p id="telefone" class="margemB20" >{{  $usuario->telefone ?? old('telefone') }} </p>
	        @else
	            <input type="text" name="telefone" id="telefone" class="col-md-12 form-control tel" value="{{  $usuario->telefone ?? old('telefone') }}">
	        @endisset
	    </div>

	    <div class="col-md-6">
	        <p><b>{{__('messages.Telefone Alternativo')}}</b></p>
	        @isset($detalhe)
	            <p id="tel_alternativo" class="margemB20" >{{  $usuario->tel_alternativo ?? old('tel_alternativo') }} </p>
	        @else
	            <input type="text" name="tel_alternativo" id="tel_alternativo" class="col-md-12 form-control tel" value="{{  $usuario->tel_alternativo ?? old('tel_alternativo') }}">
	        @endisset
	    </div>
	</div>

	
	



</div>




@push('js')

	<script src="{{ asset('js/ceps.js') }}"></script>
	{{-- <script src="{{ asset('js/perfil.js') }}"></script> --}}
	<script src="{{ asset('js/arquivos.js') }}"></script>
	<script type="text/javascript">
		
		jQuery(document).ready(function($) {
			
			jQuery('#email').keyup(function(event) {	
				var val = '@';
				if(this.value.indexOf('@') !== -1){
					val += this.value.split('@')[0];
				}
				jQuery('#marcador').val(val);
			});

			$(document).on('ifChanged', 'input[name="roles[]"]', function(e) {
				valor = this.value;
				nome_role =  jQuery(this).attr('data-nome');
				if(nome_role=="publicador" || nome_role=="freelancer" )
				{
					$("#divEmprresa").removeClass("invisivel");
					$("#div-sexo").addClass("invisivel");
					$("#div-display").removeClass("invisivel");;
				}
				else{
					$("#divEmprresa").addClass("invisivel");
					$("#div-sexo").removeClass("invisivel");
					$("#div-display").addClass("invisivel");
				}
			});			

		});

	</script>
@endpush	