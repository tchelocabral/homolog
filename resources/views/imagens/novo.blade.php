@extends('adminlte::page')

@section('title', 'Adicionar Imagem a Projeto')

@section('content_header')
    {{ Breadcrumbs::render('adicionar imagem', $projeto) }}
@stop

@section('content')

    <style type="text/css">
        .sem-margem {
            margin-top: 0;
        }
    </style>

    <div class="row centralizado">
        <form id="form-add-img" name="form-add-img" action="{{ route('imagens.store') }}" method="POST" enctype="multipart/form-data">

            @csrf

            <input type="hidden" name="projeto_id" value="{{ encrypt($projeto->id) }}" />
            <div class="row margemB20">
                <div class="col-md-8">
                    <h2 class="sem-margem">Adicionar Imagens ao Projeto - <a href="{{ route('projetos.show', encrypt($projeto->id)) }}" data-toggle="tooltip" title="Ir para o Projeto">{{ $projeto['nome'] ?? 'Não Informado' }}</a></h2>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="box box-solid box-primary no-border com-shadow">
                        <div class="box-header com-borda th-ocean">
                            <h3 class="box-title">Informe os dados da Imagem e clique em Adicionar</h3>
                        </div>
                        <div class="box-body box-profile">
                            <div class="col-sm-12 col-md-4">
                                <h3 class="">Dados da Imagem</h3>
                                <hr>
                                
                                <!-- Tipo da Imagem -->
                                <div class="row div-item-form">
                                    <div class="col-md-12">
                                        <p><b>Selecione o Tipo de Imagem</b></p>
                                        <select id="combo-tipo-imagem" name="imagem_tipo_id" class="form-control select2 margemT10">
                                            @isset($tipos_imgs)
                                                @unless($tipos_imgs)
                                                    <option value="">Sem Tipos de Imagem Cadastrados</option>
                                                @else
                                                    @foreach($tipos_imgs as $tipo)                                                    
                                                        <option value="{{ $tipo->id }}" >{{ $tipo->nome . ' - ' . $tipo->grupo->nome }}</option>
                                                    @endforeach
                                                @endunless
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <!-- Descrição -->
                                <div class="row div-tem-form margemT20">
                                    <div class="col-md-12">
                                        <p><b>Descrição complementar da Imagem</b></p>
                                        <input type="text" id="descricao" name="descricao" class="form-control">
                                    </div>
                                </div>

                                <!-- Finalizador -->
                                <div class="row div-item-form">
                                    <div class="col-md-12 margemTop">
                                        <p><b>Selecione o Finalizador</b></p>
                                        <select id="combo-finalizador" name="finalizador_id" class="form-control select2 margemT10">
                                            @unless($finalizadores)
                                                <option value="">Sem Usuários Cadastrados</option>
                                            @else
                                                <option value="-1">Sem Finalizador no Momento</option>
                                                <option value="" disabled><hr></option>
                                                @foreach($finalizadores as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                @endforeach
                                            @endunless
                                        </select>
                                    </div>
                                </div>

                                <div class="row div-tem-form margemT20">

                                <!-- Data de Revisão -->
                                    <div class="col-md-6">
                                        <p><b>Data Prevista Primeira Revisão</b></p>
                                        <input type="date" name="data_revisao" id="data_revisao" class="form-control" min="{{date('Y-m-d')}}" />
                                    </div>
                                    
                                <!-- Valor -->
                                    @can('insere-valor')
                                        <div class="col-md-6">
                                            <p><b>Valor da Imagem</b></p>
                                            <input id="valor" type="text" data-type='currency' class="form-control" name="valor" step="0.01" placeholder="R$ 0,00">
                                        </div>
                                    @endcan
                                </div>

                                 <div class="box-footer footer-com-padding">
                                    <button type="submit" class="btn btn-success pull-right">Adicionar Imagem</button>
                                </div>
                            </form>
                        </div>

                            <div class="col-sm-12 col-md-7 col-md-offset-1">
                                <p class="box-header com-borda th-gray texto-branco sem-margem-bottom"><b>Lista de Imagens que serão incluídas ao Projeto</b></p>
                                <table class="table" id="lista-imagens" style="border: 1px solid #f4f4f4;">
                                    <thead>
                                        <tr>
                                            <th>Tipo</th>
                                            <th>Descrição</th>
                                            <th>Grupo</th>
                                            <th>Prox. Revisão</th>
                                            @can('visualiza-valor')
                                                <th>Valor</th>
                                            @endcan
                                            <th>Job</th>
                                            <th>Remover</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($projeto->imagens as $img)
                                            <tr>
                                                <td><a class="texto-preto" href="{{ route('imagens.show', encrypt($img->id)) }}"> {{ $img->tipo->nome }}</a></td>
                                                <td>{{ $img->descricao ?? '-'}}</td>
                                                <td>{{ $img->tipo->grupo->nome }}</td>
                                                <td>{{ $img->data_revisao ? \Carbon\Carbon::parse($img->data_revisao)->format('d.m.Y') : 'Não Informado'}}</td>
                                                @can('visualiza-valor')
                                                    <td>{{  'R$ '.number_format($img->valor, 2, ',', '.') }} </td>
                                                @endcan
                                                <td><a href="{{ route('imagem.add.job', encrypt($img->id)) }}">Novo</a></td>

                                                <td>
                                                    @can('deleta-imagem')
                                                        <form action="{{ route('imagens.destroy', encrypt($img->id)) }}" class="form-delete" id="form-deletar-tipo-img-{{ $projeto->id }}" name="form-deletar-tipo-img-{{ $projeto->id }}" method="POST" enctype="multipart/form-data">
                                                            @method('DELETE')
                                                            @csrf
                                                            <a href="#" class="btn btn-danger deletar-item margemL5" title="Deletar Imagem" data-toggle="tooltip" type="submit">
                                                                <i class="fa fa-close" aria-hidden="true"></i>
                                                            </a>
                                                         </form>
                                                    @endcan</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
<!-- <script type="text/javascript" src="{{ asset('js/adicionar_revisao.js') }}"></script>
 -->@stop

{{-- Controle das Imagem --}}
@push('js')
    <script src="{{ asset('js/numeros.js') }}"></script>
    <script>
        $(document).ready(function () {

            function limpaCampos(){
                document.getElementById("combo-tipo-imagem").selectedIndex = 0;
                document.getElementById("combo-finalizador").selectedIndex = 0;
                document.getElementById("data_revisao").value = '';
                var midias = jQuery('.conteudo-ref');
                $.each(midias, function (index, value) {
                    value.remove();
                });
            }
        });
    </script>
@endpush
{{-- Fim do Controle de Imagem --}}


