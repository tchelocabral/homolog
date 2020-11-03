<div class="nav-tabs-custom nav-dashboard margemT50">
    <h1 class="margemT5 margemB5 titulo-principal texto-esquerda titulo-lista-dash">
    <a class="larguraTotal alturaTotal texto-preto" data-toggle="collapse" data-target="#collapseprojand" aria-expanded="false" aria-controls="#collapseprojand" role="button">
        <span class="accordion-marc"><i class="fa fa-angle-right"></i></span> Projetos em Andamento </a></h1>

    <ul class="nav nav-tabs displayFlex flexEnd paddingR20" id="tabs-projeto" role="tablist">
        <li class="active in ">
            <a data-toggle="tooltip" href="#lista" aria-expanded="true" class="nav-link " id="imagens-tab" aria-selected="false" title="Listas"> <i class="fa fa-list" aria-hidden="true"></i></a>
        </li>
        <li >
            <a data-toggle="tooltip" href="#card" aria-expanded="true" class="nav-link " id="imagens-tab" aria-selected="false" title="Cards"><i class="fa fa-credit-card " aria-hidden="true"></i></a>
        </li>
        <li>
            <a data-toggle="tooltip" href="#detalhes" aria-expanded="true" class="nav-link fundo-azul" id="imagens-tab" title="Detalhes" aria-selected="false"><i class="fa fa-info" aria-hidden="true"></i></a>
        </li>
    </ul>
                    
    <div id="collapseprojand" class="tab-content collapse" aria-labelledby="panel-proand" data-parent="#img-accordion">
        {{-- Tab #detalhes--}}
        <div id="detalhes" class="tab-pane fade">
            <div class="btn-toolbar margemT10 tab-pane fade in" role="toolbar">
                <table id="lista-dashboard-projeand" class="table table-striped larguraTotal com-shadow com-filtro-lista">
                    <thead>
                        <tr>
                            <th class="th-ocean texto-branco padding12 border-left largura10 ">#</th>
                            <th class="th-ocean texto-branco padding12">Projeto</th>
                            <th class="th-ocean texto-branco padding12">Cliente</th>
                            <th class="th-ocean texto-branco padding12 largura15">Criação</th>
                            <th class="th-ocean texto-branco padding12 largura15">Previsão de Entrega</th>
                            <th class="th-ocean texto-branco padding12">Progresso</th>
                            <th class="th-ocean texto-branco padding12 border-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="fundo-branco">
                        
                        @foreach($homeDados as $prop => $proj)
                        <tr class="">
                            <td class="desktop margemL10 largura10">{{ $prop+1 }}</td>
                            <td><a href="{{ route('projetos.show', encrypt($proj->id)) }}" class="texto-preto">{{ $proj->nome  }}</a></td>
                            {{-- <td class="desktop ">{{ $proj->descricao }}</td> --}}
                            <td class="desktop">{{ $proj->cliente->nome_fantasia }}</td>
                            <td class="desktop largura15">{{ $proj->created_at ? \Carbon\Carbon::parse($proj->created_at)->format('d.m.Y') : ''}}</td>
                            <td class="desktop largura15">{{ $proj->data_previsao_entrega ? \Carbon\Carbon::parse($proj->data_previsao_entrega)->format('d.m.Y') : ''}}</td>
                            <td>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-animated progresso" role="progressbar" aria-valuenow="{{ $proj->concluido() }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </td>
                            <td>
                                <a href="{{ route('projetos.show', encrypt($proj->id)) }}" class="texto-preto">Detalhes</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Tab #card--}}
        <div id="card" class="tab-pane fade  ">
            <div class="btn-toolbar margemT10 tab-pane fade in" role="toolbar">
                <div class="col-md-12 painel-jobs">
                @foreach($homeDados as $proj_and)
                    <div class="col-md-6 col-lg-4 col-sm-12 card-job" data-rota="{{ route('projetos.show', encrypt($proj_and->id)) }}">
                        <div class="box-group" id="accordion">
                            <div class="panel box box-primary com-shadow">
                                <div class="box-header">
                                    <h4><b>Projeto: </b>
                                        <a href="{{ route('projetos.show', encrypt($proj_and->id)) }}" >{{ $proj_and->cliente->nome_fantasia }} - {{ $proj_and->nome }}
                                        </a>
                                    </h4>
                                    <span class="float-right text-danger margemLeft"></span>
                                </div>
                                <div id="collapseOne" class="panel-collapse collapse in" aria-expanded="false" >
                                    <div class="box-body">
                                        <div class="col-md-4">
                                            {{-- aqui foir retirado {{ $job->status }} do data-status --}}
                                            <canvas id="proj-and-chart-{{$proj_and->id}}" class="graficos" data-status="" width="136" height="136" data-valor="{{ $proj_and->concluido() }}"></canvas>
                                        </div>
                                        <div class="col-md-6 col-md-offset-2">
                                            @isset($proj_and->coordenador)
                                                <h4 class="margemT10"><b>Coordenador:</b></h4>
                                                <p>{{ $proj_and->coordenador->name }}</p>
                                            @endif
                                        </div>
                                        <div class="col-md-12 margemT20">
                                            <p><b>Previsão de Entrega: </b>
                                            {{ $proj->data_previsao_entrega ? $proj->data_previsao_entrega->format('d.m.Y') : 'Não Informado' }}
                                            </p>
                                        </div>        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                </div> 
            </div>
        </div>

        {{-- Tab #lista--}}
        <div id="lista" class="tab-pane fade  in active">
            <div class="btn-toolbar margemT10 tab-pane fade in" role="toolbar">
                @unless(!$homeDados)
                <div id="img-accordion">
                    <BR>
                    <table id="lista-dashboard-projeand-lista" class="table larguraTotal com-shadow com-filtro">
                        <thead>
                            <th class="th-ocean texto-branco padding12 border-left">
                                <div class="panel-heading  cor-personalizada" >
                                    <div class="row">
                                        <div class="col-md-2">Nome</div>
                                        <div class="col-md-2">Cliente</div>
                                        <div class="col-md-2">Coordenador</div>
                                        <div class="col-md-2">Progresso</div>
                                            <div class="col-md-2">Data de entrega</div>
                                        <div class="col-md-2">
                                            <span class="pull-right titulo-tab-imagens" href="" class="" title="Criar Novo Job" data-toggle="tooltip">
                                            Ação</span>
                                        </div>
                                    </div>  
                                </div>
                            </th>
                        </thead>
                        <tbody class="fundo-branco">
                        @foreach($homeDados as $pro)
                        <tr class="">
                            <td class="desktop margemL10 no-border">
                                <div class="panel panel-default card-sem-borda card-imagem">
                                    <div class="panel-heading painel-lista cor-personalizada" id="panel-img-{{$pro->id}}" style="background: #fff;">
                                        <a class="larguraTotal alturaTotal texto-preto" data-toggle="collapse" data-target="#collapseme{{$pro->id}}" aria-expanded="false" aria-controls="#collapseme{{$pro->id}}" role="button">
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <p class="titulo-tab-imagens">{{ $pro->nome }}
                                                    </p>
                                                </div>
                                                <div class="col-md-2">
                                                    <p class="titulo-tab-imagens">{{ $pro->cliente->nome_fantasia }}
                                                    </p>
                                                </div>

                                                <div class="col-md-2">
                                                    <p class="titulo-tab-imagens">{{ isset($pro->coordenador) ? $pro->coordenador->name : 'Não Informado' }}
                                                    </p>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="progress semMargem cor-personalizada" style="background: #fff;">

                                                        <div class="progress-bar progress-bar-animated progress-bar-striped progress-bar-success progresso pull-right" role="progressbar" aria-valuenow="{{ $pro->concluido() }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    {{$pro->data_previsao_entrega ? \Carbon\Carbon::parse($pro->data_previsao_entrega)->format('d.m.Y') : 'Não Informado'}}
                                                </div>
                                                <div>
                                                    <div class="col-md-2">
                                                        <a class="pull-right titulo-tab-imagens  link-detalhe" href="{{ route('projetos.show', encrypt($pro->id)) }}" class="" title="Detalhes projeto" data-toggle="tooltip">
                                                        Detalhes
                                                    </a>
                                                </div>
                                            </div>  
                                        </a>
                                    </div>

                                    <div id="collapseme{{$pro->id}}" class="collapse" aria-labelledby="panel-pro-{{$pro->id}}" data-parent="#img-accordion">
                                        <div class="panel-body">
                                            <div class="row margemB10">
                                                <div class="col-md-12">
                                                    <h4><b>Dados</b></h4>
                                                </div>

                                                
                                                <div class="col-md-10">
                                                    <h4><b>Nome :</b> {{ $pro->nome }}</h4>
                                                </div>
                                                <div class="col-md-2">
                                                    <a href="{{ route('projetos.show', encrypt($pro->id)) }}" class="btn btn-info pull-right">
                                                        Mais detalhes
                                                    </a>  
                                                </div>
                                                <div class="col-md-12">
                                                    <h4><b>Descrição: </b> {{ $pro->descricao ?? 'Não Informado'}}</h4>
                                                </div>
                                                <div class="col-md-12">
                                                    <h4><b>Coodernador: </b> {{ isset($pro->coordenador) ? $pro->coordenador->name : 'Não Informado' }}</h4>
                                                </div>
                                                </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                @endif        

            </div>
        </div>
    </div>
</div>