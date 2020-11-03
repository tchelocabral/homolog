@extends('adminlte::page')

@section('title', $imagem->tipo->nome)
    
@section('content_header')
    {{ Breadcrumbs::render('detalhe-imagem', $imagem) }}

    <a class="titulo-topo-tab" href="{{ route('projetos.show', encrypt($imagem->projeto->id)) }}">Projeto
        {{ $imagem->projeto->nome }}
    </a>
@stop

@section('content')

    <div class="row margemT40 centralizado">
        @empty($imagem)
            <h1>Imagem não Encontrada</h1>
        @else
             @isset($finalizadores)
                <ul id="lista-finalizador" class="invisivel">
                    @foreach($finalizadores as $f)
                        <li 
                        data-url="{{route('imagem.add.job', ['img_id' => encrypt($imagem->id), 'finalizador_id' => encrypt($f->id)])}}" 
                        data-id= "{{encrypt($f->id)}}"
                        data-nome= "{{$f->name}}"
                        ></li>
                    @endforeach
                </ul>
            @endif

            <div id="tabs-imagem" class="nav-tabs-custom altura-minima">
                {{-- Titulos das Tabs--}}
                <ul class="nav nav-tabs verde" id="tabs-imagem" role="tablist">
                    <li class="active">
                        <a data-toggle="tab" href="#detalhes" aria-expanded="true" class="nav-link animated demo verde active" id="infodoprojet-tab" aria-controls="Dados da Imagem" aria-selected="false">
                            Dados da Imagem
                        </a>
                    </li>
                    <li>
                        <a data-toggle="tab" href="#imagens" aria-expanded="false" class="nav-link animated demo verde" id="jobs-tab" aria-selected="false">Jobs da Imagem</a>
                    </li>
                    <li>
                        <a data-toggle="tab" href="#arquivos" aria-expanded="false" class="nav-link animated demo verde" id="arquivos-tab" aria-selected="false">Arquivos</a>
                    </li>
                    @can('lista-revisao')
                        <li>
                            <a data-toggle="tab" href="#revisoes" aria-expanded="false" class="nav-link animated demo verde" id="revisoes-tab" aria-selected="false">Previews</a>
                        </li>
                    @endcan

                </ul>

                {{-- Conteudos das Tabs --}}
                <div class="tab-content">
                    {{-- Tab Dados da Imagem --}}
                    <div id="detalhes" class="tab-pane fade in active">
                        <div class="row">
                            {{-- Botoes Toolbox --}}
                            <div class="col-md-8 margemT10">
                                <div class="btn-toolbar tab-pane fade in" role="toolbar">
                                    @can('atualiza-imagem')
                                        <a href="{{ route('imagem.add.job', encrypt($imagem->id)) }}" class="btn btn-success " title="Criar Novo Job" data-toggle="tooltip" {{in_array($imagem->status, [2,3]) ? 'style=display:none;' : ""}}>
                                            <i class="fa fa-plus" aria-hidden="true"></i>
                                        </a>

                                        <a class="btn btn-primary" data-toggle="tooltip" title="Alterar Finalizador" id="setar-finalizador" {{in_array($imagem->status, [2,3]) ? 'style=display:none;' : ""}}>
                                            <i class="fa fa-exchange" aria-hidden="true"></i>
                                        </a>

                                        <a href="{{ route('projeto.add.arquivo', encrypt($imagem->projeto->id)) }}" class="btn btn-info " title="Adicionar Arquivos ao Projeto" data-toggle="tooltip" {{in_array($imagem->status, [2,3]) ? 'style=display:none;' : ""}}>
                                            <i class="fa fa-archive" aria-hidden="true"></i>
                                        </a>
                                    
                                        <a href="{{ route('imagens.edit', encrypt($imagem->id)) }}" class="btn btn-primary" data-toggle="tooltip" title="Editar Informações">
                                        <i class="fa fa-pencil" aria-hidden="true"></i>
                                        </a>
                                    @endcan

                                    {{--@can('atualiza-imagem')--}}
                                        {{--<form action="{{ route('imagem.concluir', encrypt($imagem->id)) }}" class="form-delete" id="form-deletar-tipo-img-{{ $imagem->projeto->id }}" name="form-deletar-tipo-img-{{ $imagem->projeto->id }}" method="POST" enctype="multipart/form-data">--}}
                                            {{--@method('POST')--}}
                                            {{--@csrf--}}
                                            {{--<a href="#" class="btn btn-success margemL5" {{ $imagem->concluido()<100 ? 'disabled' : '' }} title="Concluir Imagem" data-toggle="tooltip" type="submit">--}}
                                                {{--<i class="fa fa-thumbs-up" aria-hidden="true"></i>--}}
                                            {{--</a>--}}
                                         {{--</form>--}}
                                    {{--@endcan--}}

                                    @can('deleta-imagem')
                                        <form action="{{ route('imagens.destroy', encrypt($imagem->id)) }}" class="form-delete" id="form-deletar-tipo-img-{{ $imagem->projeto->id }}" name="form-deletar-tipo-img-{{ $imagem->projeto->id }}" method="POST" enctype="multipart/form-data">
                                            @method('DELETE')
                                            @csrf
                                            <a href="#" class="btn btn-danger deletar-item margemL5" title="Deletar Imagem" data-toggle="tooltip" type="submit">
                                                <i class="fa fa-close" aria-hidden="true"></i>
                                            </a>
                                         </form>
                                    @endcan
                                </div>
                            </div>
                            <div class="col-md-4 margemT20">
                                @php 
                                    $class_status = 'success';
                                    $porcentagem_status = $imagem->concluido();
                                    foreach($imagem->jobs as $key => $job) {
                                        if($job->status==8) {
                                            $class_status = 'danger';
                                            $porcentagem_status =100;
                                        }
                                    }
                                @endphp
                                <div class="progress-bar progress-bar-animated progress-bar-striped progress-bar-{{ $class_status }} progresso pull-right" role="progressbar" aria-valuenow="{{ $porcentagem_status }}" aria-valuemin="0" aria-valuemax="100" ></div>
                            </div>
                        </div>
                        <hr>
                        <div class="box box-solid no-border">
                            <div class="box-body box-profile">
                                <div class="row">
                                     {{-- Dados da Imagem --}}
                                    <div class="col-md-12 paddingB20">
                                        <div class="col-md-4">
                                            <h3>Nome</h3>
                                            <h4>{{ $imagem->nome }}</h4>
                                        </div>

                                        <div class="col-md-4">
                                            <h3>Imagem</h3>
                                            <h4>{{ $imagem->tipo->nome }}</h4>
                                        </div>
                                         <div class="col-md-4">
                                            <h3>Grupo</h3>
                                            <h4>{{ $imagem->tipo->grupo->nome }}</h4>
                                        </div>
                                        <div class="col-md-4">
                                            <h3>Status</h3>
                                            <h4>{{ $imagem->status_revisao ?? 'Sem revisão adicionada'}}</h4>
                                        </div>
                                        <div class="col-md-4">
                                            <h3>Coordenador</h3>
                                            <h4>{{  $imagem->projeto->coordenador ? $imagem->projeto->coordenador->name : 'Não Informado' }}</h4>
                                        </div>
                                        <div class="col-md-4">
                                            <h3>Finalizador</h3>
                                            <h4>{{ $imagem->finalizador ? $imagem->finalizador->name : 'Não informado' }}</h4>
                                        </div>
                                        <div class="col-md-4">                                   
                                            @if($imagem->projeto->coordenador && $imagem->projeto->coordenador->id == \Auth()->user()->id || Gate::check('gerencia-politicas') )
                                                <div class="col-md-12" style="padding-left: 0;">
                                                    <h3>Próxima Revisão</h3>
                                                    <h4>{{ $imagem->data_revisao ? $imagem->data_revisao->format('d/m/Y')  : 'Não informado' }}</h4>
                                                </div>
                                            @endif
                                        </div>

                                         <div class="col-md-4">
                                            <h3>Data de Criação</h3>
                                            <h4>{{ $imagem->created_at->format('d/m/Y') }}</h4>
                                        </div>
                                        <div class="col-md-4">
                                            <h3>Última Atualização</h3>
                                            <h4>{{ $imagem->updated_at->format('d/m/Y') }}</h4>
                                        </div>
                                        <div class="col-md-4">
                                            <h3>Previsão de Entrega</h3>
                                            <h4>{{ $imagem->data_entrega ? $imagem->data_entrega->format('d/m/Y') : 'Não informado' }}</h4>
                                        </div> 

                                        @can('visualiza-valor')
                                            <div class="col-md-4">
                                                <h3>Valor</h3>
                                                <h4>R$  {{ $imagem->valor ?? '0.00' }}</h4>
                                            </div>
                                        @endcan        

                                        <div class="col-md-8 margemT20">
                                            <h3>Descrição Extra</h3>
                                            <h5>{{ $imagem->descricao ?? 'Sem descrição' }}</h5>
                                        </div>



                                        {{-- Retirado momentaneamente --}}
                                        {{-- <div class="col-md-4">
                                            <h3>Situação</h3>
                                            <h4>{{ $imagem->situacao() }}</h4>
                                        </div> --}}

                                        <div class="col-md-12 ">
                                            <h3>Observações</h3>
                                            <h5>{{ $imagem->observacoes ?? 'Sem observações' }}</h5>
                                        </div>

                                    </div>

                                    {{-- Campos Personalizados --}}
                                    @if($imagem->campos_personalizados)
                                        <div class="col-md-8">
                                            <h4>Dados Extra</h4>
                                            @foreach($imagem->campos_personalizados as $campo)
                                                <h5 class="negrito">{{ $campo['nome'] }}</h5>
                                                <p>{{ $campo['valor'] }}</p>
                                            @endforeach
                                        </div>
                                    @endif
                                       
                                    @if(count($imagem->revisoes)>0)
                                        @php $rev = $imagem->revisoes->last(); @endphp
                                        <div class="col-md-12">
                                            <hr>
                                            <h3>Última Revisão</h3>
                                            <div class="card card-revisao margemT20">
                                                <div class="row">
                                            {{--    <div class="col-md-1">{{$rev->numero_revisao}}</div>--}}
                                                    <div class="col-md-2">{{$rev->nome}}</div>
                                                    <div class="col-md-5">{{\Carbon\Carbon::parse($rev->created_at)->format('d/m/Y')}}</div>
                                                    <div class="col-md-2">
                                                        <div class="progress cor-personalizada" style="background: #fff;">
                                                            <div class="progress-bar progress-bar-animated progress-bar-striped progress-bar-info progresso " role="progressbar" aria-valuenow="{{ '0' }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-1"><span class="link visualiza-revisao" data-url="{{ route('visualizar.revisao.imagem', encrypt($rev->id)) }}" data-title="'Revisão de Preview {{$rev->nome}}" >Visualizar</span></div>
                                                    @if(Auth::user()->hasAnyRole(['admin', 'desenvolvedor', 'coordenador']))
                                                    <div class="col-md-1"><span class="link edita-revisao" data-url="{{ route('editar.revisao.imagem', encrypt($rev->id)) }}"  data-title="'Revisão de Preview {{$rev->nome}}">Editar</span></div>
                                                    @endif
                                                    
                                                    <div class="col-md-1">
                                                        @can('deleta-revisao')
                                                            <form action="{{ route('excluir.revisao.imagem', ['imagem_id'=> encrypt($imagem->id), 'revisao_id'=>encrypt($rev->id)]) }}" class="form-delete" id="form-deletar-tipo-img-{{ $rev->id }}" name="form-deletar-tipo-img-{{ $rev->id }}" method="POST" enctype="multipart/form-data">
                                                                @method('DELETE')
                                                                @csrf
                                                                <a href="#" class="btn btn-danger deletar-item margemL5" title="Excluir Revisão" data-toggle="tooltip" type="submit">
                                                                    <i class="fa fa-close" aria-hidden="true"></i>
                                                                </a>
                                                            </form>
                                                        @endcan
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif


                                </div> {{-- end row --}}
                            </div>

                            {{--Box foote concluir / Reabrir--}}
                            @can('atualiza-imagem')
                                <div class="box-footer with-border">
                                    <hr>

                                    @if( in_array($imagem->status, [0,1]))
                                        
                                        <form action="{{ route('imagem.concluir', encrypt($imagem->id)) }}" class="" id="form-concluir-img-{{ $imagem->id }}" name="form-deletar-tipo-img-{{ $imagem->projeto->id }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <button class="btn btn-success margemL5 pull-right" {{ $imagem->concluido()<100 ? "disabled" : "" }} title="Concluir Imagem" data-toggle="tooltip" type="submit">
                                                Definir Imagem como concluída
                                                {{--<i class="fa fa-check" aria-hidden="true"></i>--}}
                                            </button>
                                        </form>

                                    @else
                                        
                                        <form action="{{ route('imagem.reabrir', encrypt($imagem->id)) }}" class="" id="form-reabrir-img-{{ $imagem->id }}" name="form-reabrir-img-{{ $imagem->id }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <button class="btn btn-warning margemL5 pull-right" title="Reabrir Imagem" data-toggle="tooltip" type="submit">
                                                Reabrir Imagem
                                                {{--<i class="fa fa-check" aria-hidden="true"></i>--}}
                                            </button>

                                        </form>
                                    @endif  
                                </div>
                            @endcan

                        </div>
                    </div>

                    {{-- Tab Jobs da Imagem --}}
                    <div id="imagens" class="tab-pane fade">
                        <div class="row">   
                            <div class="col-md-12 col-sm-12">
                                <div class="box box-solid box-primary no-border">
                                    <a class="larguraTotal" data-toggle="collapse" data-parent="#accordionJobs" href="#collapseOneJobs" aria-expanded="false">
                                    </a>
                                    <div id="collapseOneJobs" class="panel-collapse collapse in" aria-expanded="false" >
                                        <div class="box-body box-profile">
                                            <table class="table">
                                                <thead>
                                                <th colspan="">Lista de jobs</th>
                                                <th>
                                                    @can('criar-job')
                                                    <a class="pull-right" href="{{ route('imagem.add.job', encrypt($imagem->id)) }}">Criar Job</a>
                                                    @endcan
                                                </th>
                                                </thead>
                                                <tbody>
                                                @isset($imagem->jobs)
                                                    @forelse($imagem->jobs as $job)
                                                        <tr>
                                                            <td>
                                                                <a id="word-break" href="{{ route('jobs.show', encrypt($job->id)) }}" class="margemB5">
                                                                    {{ $job->nome }}
                                                                </a>
                                                                <p><b>Colaborador(a) :</b> {{ $job->delegado ? $job->delegado->name : 'Não informado'}}</p>
                                                            </td>
                                                            <td>
                                                                @php 
                                                                    $class_status = '';
                                                                    if($job->status==8) {
                                                                        $class_status = 'danger';
                                                                    }
                                                                @endphp
                                                                <div class="progress-bar progress-bar-animated progress-bar-striped progress-bar-{{ $class_status }} progresso margemT10" role="progressbar" aria-valuenow="{{ $job->concluido() }}" aria-valuemin="0" aria-valuemax="100" ></div>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="3">
                                                                <h4>
                                                                    Sem Jobs | <a href="{{ route('imagem.add.job', encrypt($imagem->id)) }}">Criar Job</a>
                                                                </h4>
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                        
                    {{-- Tab Arquivos --}}
                     <div id="arquivos" class="tab-pane fade">
                        <div class="row">
                             <div class="col-md-12 col-sm-12">
                                <div class="box box-solid box-primary no-border">
                                    <div id="collapseOneArquivos" class="panel-collapse collapse in" aria-expanded="false" >
                                        <div class="box-body box-profile">
                                            <table class="table">
                                                <thead>
                                                <th colspan="3">Lista de Arquivos</th>
                                                </thead>
                                                <tbody>
                                                    @isset($imagem->arquivos)
                                                        @forelse($imagem->arquivos as $midia)
                                                            <tr>
                                                                @if(pathinfo($midia->caminho, PATHINFO_EXTENSION ) == 'jpg' || pathinfo($midia->caminho, PATHINFO_EXTENSION) == 'png')
                                                                    <td><img src="{{ URL::to('') . '/storage/' . $midia->caminho }}" width="28" height="28" alt=""></td>
                                                                @else
                                                                    @php
                                                                        $exts   = ['3ds', 'cad', 'doc', 'dxf', 'max', 'pdf', 'psd', 'txt', 'docx', 'xls', 'zip', 'dwg'];
                                                                        $ext    = pathinfo($midia->caminho, PATHINFO_EXTENSION);
                                                                        $icone  = '/icones/';
                                                                        $icone .= in_array($ext, $exts) ? $ext : 'padrao';
                                                                        $icone .= '.png';
                                                                    @endphp
                                                                    <td><img src="{{$icone}}" width="28" height="28" alt="{{$ext}}"></td>
                                                                @endif
                                                                <td>{{ $midia->tipo_arquivo->nome }}</td>
                                                                <td>{{ $imagem->nome }}</td>
                                                                <td>{{ $midia->nome }}</td>
                                                                <td class="texto-centralizado">
                                                                    <a href="{{ URL::to('') . '/storage/' . $midia->caminho }}" target="_blank">
                                                                        <i class="fa fa-eye" aria-hidden="true" title="Visualizar" data-toggle="tooltip"></i>
                                                                    </a>
                                                                </td>
                                                                <td class="texto-centralizado">
                                                                    <a href="{{ URL::to('') . '/storage/' . $midia->caminho }}" download>
                                                                        <i class="fa fa-download" aria-hidden="true" title="Download" data-toggle="tooltip"></i>
                                                                    </a>
                                                                </td>

                                                                <td class="texto-centralizado">
                                                                    <a  href="{{ route('imagem.desvincular.arquivos', ["arquivo" => encrypt($midia->id), "imagens" => encrypt($imagem->id)]) }}" style="height: 0px;width: 0px;overflow:hidden;" class="desvincular-hidden"></a>
                                                                    <a class="desvincular text-danger negrito" data-toggle="tooltip" title="Desvincular Arquivo" href="#">
                                                                        <i class="fa" aria-hidden="true"></i> x
                                                                    </a>
                                                                </td>

                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="3">
                                                                    <h4>
                                                                        Sem Arquivos |  <a href="{{ route('imagem.add.arquivo', encrypt($imagem->id)) }}" data-toggle="tooltip">Adicionar Arquivos</a>
                                                                    </h4>
                                                                </td>
                                                            </tr>
                                                        @endforelse
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                     </div>

                     {{-- Tab Revisoes --}}
                    @can('lista-revisao')
                        <div id="revisoes" class="tab-pane fade">
                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    @include('revisoes.lista',  ['revisoes' => $imagem->revisoes, 'imagem_id'=>$imagem->id])
                                </div>
                            </div>
                        </div>
                        
                    @endcan

                </div>
            </div>
        @endempty
    </div>
    
    @include('app.includes.carregando')

@stop

@push('js')
    
    <!-- funções para lista de arquivos e dados da -->
    <script>

        // Mudar cor da tab principal do accordion ao abrir
        $(document).ready(function(){

          $(".collapse").on("hide.bs.collapse", function(){
                $(this).css('background-color', '#f1f1f1');
                $(this.parentElement.children[0]).css('background-color', '#fff');
                $('#' + this.parentElement.children[0].id + ' a').addClass('texto-preto');             
                $('#' + this.parentElement.children[0].id + ' a').removeClass('texto-branco');             
          });
          $(".collapse").on("show.bs.collapse", function(){
                $(this).css('background-color', '#f1f1f1');
                $(this.parentElement.children[0]).css('background-color', '#5c9ba5');
                $('#' + this.parentElement.children[0].id + ' a').removeClass('texto-preto');
                $('#' + this.parentElement.children[0].id + ' a').addClass('texto-branco');
          });

            // ### Funçao para trocar finalizador
           // cria um array com todos os finalizadores
            var finalizadores = {!! $finalizadores->get() !!};
            // cria um select com todos os finalizadores
            var select_finalizadores = '<select id="finalizadores" name="finalizador_id" class="form-control select-2">';
            // percorre o array dos finalizadores e monta todos os options
            finalizadores.forEach(montaSelectFinalizadores);
            function montaSelectFinalizadores(value, index, array){
                select_finalizadores += '<option value="' + value.id + '">' + value.name + '</option>';
            }
            // fecha o select dos finalizadores
            select_finalizadores += '</select>';
            // console.log(select_finalizadores);
            
            // evento de click do botao troca finalizador
            $('#setar-finalizador').on('click', function(e){
                e.preventDefault();
                setarFinalizador(this);
            });

            function setarFinalizador(ele) {
                console.log(ele);
                console.log('setar finalizador');
                // var url   = jQuery(ele).data('url');
                $.confirm({
                    icon: 'fa',
                    title: 'Escolha o finalizador da Imagem',
                    content: '' +
                    '<form action="{!! route('imagem.add.finalizador', encrypt($imagem->id) ); !!}" id="form-finalizador">' +
                        '<div class="form-group">' +
                            '<p>Selecione o Finalizador:</p>' +
                            select_finalizadores +
                        '</div>' +
                    '</form>',
                    backgroundDismiss: true,
                    closeIcon: true,
                    type: 'orange',
                    boxWidth: '30%',
                    useBootstrap: false,
                    cancelButton: 'Não',
                    buttons: {
                        confirmar: function(){
                            var finalizador = this.$content.find('#finalizadores');
                            if(!finalizador){
                                return false;
                            }
                            finalizador.closest('#form-finalizador').submit();
                        },
                        cancelar: function(){
                        }
                    }
                });
            }            

        });

    </script>

@endpush