@extends('adminlte::page')

@section('title', 'Editar Imagem')

@section('content_header')
   {{ Breadcrumbs::render('editar imagem', $imagem) }}
@stop

@section('content')

    <div class="row largura90 centralizado">
        @empty($imagem)
            <h1>Imagem não Encontrada</h1>
        @else
            <div class="row margemB40">
                <div class="col-md-8">
                    <h1 class="">Editar Dados da Imagem</h1>
                </div>
            </div>

            <div class="row">
                <form id="form-imagem" name="form-imagem" action="{{ route('imagens.update', encrypt($imagem->id)) }}" method="POST" enctype="multipart/form-data">

                    @csrf
                    <input type="hidden" name="_method" value="put" />
                    <input type="hidden" name="img_id" value="{{ $imagem->id }}" />

                    {{-- Box Dados do Job --}}
                    <div class="col-sm-12 col-md-6">
                        <div class="box box-solid box-primary com-shadow">
                            <div class="box-header with-border th-ocean">
                                <h3 class="box-title">Preencha os campos com os dados da Imagem</h3>
                            </div>

                            <div class="box-body">

                                <!-- Tipo da Imagem -->
                                <div class="row div-item-form">
                                    <div class="col-md-12">
                                        <p><b>Tipo de Imagem</b></p>
                                        <select id="combo-tipo-imagem" name="imagem_tipo_id" class="form-control select2 margemT10">
                                            @unless($tipos_imgs)
                                                <option value="">Sem Tipos de Imagem Cadastrados</option>
                                            @else
                                                @foreach($tipos_imgs as $tipo)
                                                    <option value="{{ $tipo->id }}"  {{ $tipo->id == $imagem->tipo->id ? 'selected=selected' : '' }}>{{ $tipo->nome . ' - ' . $tipo->grupo->nome }}</option>
                                                @endforeach
                                            @endunless
                                        </select>
                                    </div>
                                </div>
                                
                                <!-- Nome -->
                                <div class="row div-tem-form margemT20">
                                    <div class="col-md-12">
                                        <p><b>Nome da Imagem</b></p>
                                        <input type="text" id="nome" name="nome" class="form-control"  value="{{ $imagem->nome }}" />
                                    </div>
                                </div>
                        

                                <!-- Descrição -->
                                <div class="row div-tem-form margemT20">
                                    <div class="col-md-12">
                                        <p><b>Descrição complementar da Imagem</b></p>
                                        <input type="text" id="descricao" name="descricao" class="form-control"  value="{{ $imagem->descricao }}" />
                                    </div>
                                </div>
                        
                                <!-- Finalizador -->
                                <div class="row div-item-form">
                                    <div class="col-md-12 margemTop">
                                        <p><b>Selecione o Finalizador</b></p>
                                        <select id="combo-finalizador" name="finalizador_id" class="form-control select2 margemT10" {{in_array($imagem->status, [2,3]) ? "disabled=disabled" : ""}}>
                                            @unless($finalizadores)
                                                <option value="">Sem Usuários Cadastrados</option>
                                            @else
                                                    <option value="-1">Sem Finalizador no Momento</option>
                                                @foreach($finalizadores as $fn)
                                                    <option value="{{ $fn->id }}" {{ $fn->id == $imagem->finalizador_id ? 'selected=selected' : '' }}>{{ $fn->name }}</option>
                                                @endforeach
                                            @endunless
                                        </select>
                                    </div>
                                </div>

                                 <div class="row div-tem-form margemT20">

                                    <!-- Data de Revisão -->
                                    <div class="col-md-6">
                                        <p><b>Data Prevista Primeira Revisão</b></p>
                                        <input type="date" name="data_revisao" id="data_revisao" class="form-control" value="{{ $imagem->data_revisao ? $imagem->data_revisao->format('Y-m-d') : old('data_revisao') }}" min="{{date('Y-m-d')}}"  {{in_array($imagem->status, [2,3]) ? "disabled=disabled" : ""}}/>
                                    </div>
                                    
                                    <!-- Valor -->
                                    <div class="col-md-6">
                                        @can('insere-valor')
                                            <p><b>Valor da Imagem</b></p>
                                            <input id="valor" type="text" data-type='currency' class="form-control numeros" name="valor" step="0.01" value="{{ $imagem->valor }}" {{in_array($imagem->status, [2,3]) ? "disabled=disabled" : ""}}>
                                        @endcan
                                    </div>

                                    <!-- Observações -->
                                    <div class="col-md-6">
                                        <p><b>Observações</b></p>
                                        <textarea class="form-control" name="observacoes" id="observacoes">
                                            {{ $imagem->observacoes or old('observacoes') }}
                                        </textarea>
                                    </div>
                                </div>
                            </div> <!-- end box-body -->

                            <div class="box-footer footer-com-padding">
                                <button type="submit" class="btn btn-primary pull-right">Atualizar Informações da Imagem
                                </button>
                            </div>
                        </div>
                    </div>

                </form> {{-- end of edit form --}}
            </div>{{-- end row --}}
        @endif
    </div>
@stop

{{-- Controle dos campos --}}
@push('js')
    <script src="{{ asset('js/arquivos.js') }}"></script>
    <script src="{{ asset('js/numeros.js') }}"></script> 
@endpush