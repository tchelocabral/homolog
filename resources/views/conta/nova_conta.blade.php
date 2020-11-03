
        {{-- @if(is_null($usuario_auth->conta_paypal))  --}}
        <form id="form-conta-user" name="form_conta_user" action="{{ route('add.conta.paypal') }}" method="POST" enctype="multipart/form-data">
            {{--security token--}}
            @csrf
            <input type="hidden" value="{{ $usuario_auth->id}}" name="user_id" id="user-id">
            <div class="row">
                {{-- Box Nova conta --}}
                <div class="col-md-12">
                    <div class="col-md-12">
                        <h3 class="margemB20">{{ __('messages.Dados da Conta')}}</h3>
                    </div>

                    <div class="col-sm-12 col-md-4">
                        <div class="box box-solid box-primary com-shadow paddingB40">
                            <div class="box-header th-white">
                                <h3 class="box-title ">{{ __('messages.Pagamento')}}</h3>
                            </div>
                            <div class="box-body">
                                                        
                                <div class="col-md-12">
                                    {{-- <h5 class="larguraTotal texto-centralizado margemB20">{{ __('messages.Pagamento')}}</h5> --}}
                                    <p class="detalhe-label"><b>{{ __('messages.Conta PayPal')}}</b></p>
                                    <input type="text" id="conta_paypal" name="conta_paypal" class="form-control" required value="{{ $usuario_auth->conta_paypal ?? '' }}" />
                                </div>

                                {{-- Observações --}}
                                {{-- <div class="row div-item-form margemT20">
                                    <div class="col-sm-12 col-md-12">
                                        <p class="detalhe-label"><b>{{ __('messages.Observações')}}</b></p>
                                        <textarea id="observacoes" name="observacoes" class="form-control"></textarea>
                                    </div>
                                </div> --}}


                                {{-- DADOS BANCARIOS --}}
                                    {{-- <div class="row div-item-form"> --}}
                                        {{-- Banco --}}
                                        {{-- <div class="col-sm-12 col-md-6">
                                            <p class="detalhe-label"><b>{{ __('messages.'Banco')}}</b></p>
                                            <input type="text" id="banco" name="banco" class="form-control" required >
                                        </div> --}}

                                        {{-- Nome agencia --}}
                                        {{-- <div class="col-sm-12 col-md-6">
                                            <p class="detalhe-label"><b>{{ __('messages.Agencia')}}</b></p>
                                            <input type="text" class="form-control " name="agencia" id="agencia" required />
                                        </div> --}}

                                        {{-- Conta --}}
                                        {{-- <div class="col-sm-12 col-md-6">
                                            <p class="detalhe-label"><b>{{ __('messages.Conta')}}'</b></p>
                                            <input type="text" class="form-control " name="conta" id="conta" required  />
                                        </div> --}}

                                        {{-- <div class="col-sm-12 col-md-6">
                                            <p class="detalhe-label"><b>{{ __('messages.Tipo Conta')}}</b></p>
                                            <input type="text" class="form-control " name="tipo_conta" id="tipo-conta" required />
                                        </div> --}}

                                        {{-- Conta --}}
                                        {{-- <div class="col-sm-12 col-md-6">
                                            <p class="detalhe-label"><b>{{ __('messages.CPF Titular')}}</b></p>
                                            <input type="text" class="form-control cpf" name="cpf_titular" id="cpf-titulae" />
                                        </div> --}}
                                        
                                    {{-- </div> --}}
                                    {{-- <div class="row div-item-form"> --}}
                                        {{-- Banco --}}
                                        {{-- <div class="col-sm-12 col-md-6">
                                            <p class="detalhe-label"><b>{{ __('messages.Banco')}}</b></p>
                                            <input type="text" id="banco" name="banco" class="form-control" required >
                                        </div> --}}
                                    {{-- </div> --}}
                                {{-- DADOS BANCARIOS --}}
                                
                            </div>
                        </div>
                    </div>


                    <div class="col-sm-12 col-md-4">
                        <div class="box box-solid box-primary com-shadow paddingB40">
                            <div class="box-header th-white">
                                <h3 class="box-title ">{{ __('messages.Dados Pessoais')}}</h3>
                            </div>
                            <div class="box-body">
                                <div class="col-md-12">
                                    {{-- <h5 class="larguraTotal texto-centralizado margemB20">{{ __('messages.Dados Pessoais')}}</h5> --}}
                                    <p class="detalhe-label"><b>{{ __('messages.CPF')}}</b></p>
                                    <input type="text" id="cpf" name="cpf" class="form-control cpf" required value="{{ $usuario_auth->cpf ?? '' }}" />
                                    <p class="detalhe-label"><b>{{ __('messages.Data de Nascimento')}}</b></p>
                                    <input type="date" name="data_nascimento" id="data_nascimento" class="form-control"  required value="{{ $usuario_auth->data_nascimento ? $usuario_auth->data_nascimento->format('Y-m-d') : '' }}"/>
                                    <p class="detalhe-label"><b>{{ __('messages.País que nasceu')}}</b></p>
                                    <input type="text" name="pais_nascimento" id="pais_nascimento" class="form-control"  required value="{{ $usuario_auth->pais_nascimento ?? '' }}"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-sm-12 col-md-4">
                        <div class="box box-solid box-primary com-shadow paddingB40">
                            <div class="box-header th-white">
                                <h3 class="box-title ">{{ __('messages.Endereço')}}</h3>
                            </div>
                            <div class="box-body">
                                <div class="col-md-12">
                                    {{-- <h5 class="larguraTotal texto-centralizado margemB20">{{ __('messages.Endereço')}}</h5> --}}
                                    <p class="detalhe-label"><b>{{ __('messages.Rua')}}</b></p>
                                    <input type="text" id="rua" name="logradouro" class="form-control" required value="{{ $usuario_auth->logradouro ?? '' }}" />
                                    <p class="detalhe-label"><b>{{ __('messages.Cidade')}}</b></p>
                                    <input type="text" name="cidade" id="cidade" class="form-control"  required value="{{ $usuario_auth->cidade ?? '' }}" /> 
                                    <p class="detalhe-label"><b>{{ __('messages.País')}}</b></p>
                                    <input type="text" name="pais" id="pais" class="form-control"  required value="{{ $usuario_auth->pais ?? '' }}"/>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <hr />
                            <button type="submit" class="btn btn-success pull-right">{{ $usuario_auth->conta_paypal>=1!="" ? __('messages.Atualizar Conta') : __('messages.Adicionar Conta') }}</button>
                        </div>
                    </div>
                </div>


            </div>{{-- end row --}}

        </form>
    {{-- @else --}}



        {{-- CONTA BANCÁRIA --}}
        {{-- @foreach ($conta as $item)
            
            <div class="box box-solid no-border">
                <div class="box-body box-profile">
                    <div class="row">
                        <div class="col-md-3">
                            <h3 class="margem-bottom-menor margemT5">{{ __('messages.Banco)}}</h3>
                            <h4>{{ $item->banco }}</h4>
                        </div>
                        <div class="col-md-3">
                            <h3 class="margem-bottom-menor margemT5">{{ __('messages.Agência)}}</h3>
                            <h4>{{ $item->agencia }}</h4>
                        </div>
                        <div class="col-md-3">
                            <h3 class="margem-bottom-menor margemT5">{{ __('messages.Conta)}}</h3>
                            <h4>{{ $item->conta }}</h4>
                        </div>
                        <div class="col-md-3">
                            <h3 class="margem-bottom-menor margemT5">{{ __('messages.Tipo Conta)}}</h3>
                            <h4>{{ $item->tipo_conta }}</h4>
                        </div>
                        <div class="col-md-3">
                            <h3 class="margem-bottom-menor margemT5">{{ __('messages.CPF)}}</h3>
                            <h4>{{ $item->cpf_titular }}</h4>
                        </div>
                        <div class="col-md-9">
                            <h3 class="margem-bottom-menor margemT5">{{ __('messages.Observação)}}</h3>
                            <h4>{{ $item->observacoes }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach --}}

        
    {{-- @endif --}}


