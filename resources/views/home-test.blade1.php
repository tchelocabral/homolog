@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    {{-- {{ Breadcrumbs::render('home') }} --}}
    {{-- <h1 class="margemB10 margemT10 titulo-principal">Painel de Jobs</h1> --}}
@stop

@section('content')

<div class="row">

            {{-- Painel Consolidado Superior --}}
            <div class="col-md-12">
            
            @if(2==1)

                {{-- Projetos em Andamento  --}}
                @if(isset($projetos_andamento) && count($projetos_andamento)>0)
                    <div class="col-md-4 ">
                        <div class="small-box bg-info com-shadow">
                            <div class="inner">
                                <h3>{{count($projetos_andamento)}}</h3>
                                <p>Projeto{{count($projetos_andamento) > 1 ? 's' : ''}} em Andamento</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-pie-graph"></i>
                            </div>
                            <a href="{{ route('projetos.andamento') }}" class="small-box-footer texto-preto">Mais informações</a>
                        </div>
                    </div>
                @endif
                {{-- Imagens em Andamento --}}
                @if(isset($projetos_andamento) && count($projetos_andamento)>0)
                    <div class="col-md-4">
                        <div class="small-box bg-warning com-shadow">
                            <div class="inner">
                                <h3>{{count($imgs_andamento)}}</h3>
                                <p>Image{{count($imgs_andamento) > 1 ? 'ns' : 'm'}} em Andamento</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-pie-graph"></i>
                            </div>
                            <a href="#" class="small-box-footer texto-preto">Mais informações</a>
                        </div>
                    </div>
                @endif
                {{-- Jobs em Andamento --}}
                @if(isset($jobs_andamento) && count($jobs_andamento)>0)
                    <div class="col-md-4">
                        <div class="small-box bg-danger com-shadow">
                            <div class="inner">
                                <h3>{{count($jobs_andamento)}}</h3>
                                <p>Jobs em Andamento</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-pie-graph"></i>
                            </div>
                            <a href="{{ route('jobs.andamento') }}" class="small-box-footer texto-preto">Mais informações</a>
                        </div>
                    </div>
                @endif
                {{-- Tasks Em Andamento --}}
                @if(isset($tasks_andamento) && count($tasks_andamento)>0)
                    <div class="col-md-4">
                        <div class="small-box bg-light com-shadow">
                            <div class="inner">
                                <h3>{{count($tasks_andamento)}}</h3>
                                <p>Tasks em Andamento</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-pie-graph"></i>
                            </div>
                            <a href="#" class="small-box-footer texto-preto">Mais informações</a>
                        </div>
                    </div>
                @endif
                {{-- Projetos Concluidos --}}
                @if(isset($projetos_concluidos) && count($projetos_concluidos)>0)
                    <div class="col-md-4">
                        <div class="small-box bg-success com-shadow">
                            <div class="inner">
                                <h3>{{count($projetos_concluidos)}}</h3>
                                <p>Projetos Concluídos</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-pie-graph"></i>
                            </div>
                            <a href="{{ route('projetos.concluidos') }}" class="small-box-footer texto-preto">Mais informações</a>
                        </div>
                    </div>
                @endif
                {{-- Jobs Concluidos --}}
                @if(isset($jobs_concluidos) && count($jobs_concluidos)>0)
                    <div class="col-md-4">
                        <div class="small-box bg-success com-shadow">
                            <div class="inner">
                                <h3>{{count($jobs_concluidos)}}</h3>
                                <p>Jobs Concluídos</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-pie-graph"></i>
                            </div>
                            <a href="{{ route('jobs.concluidos') }}" class="small-box-footer texto-preto">Mais informações</a>
                        </div>
                    </div>
                @endif
                {{-- Jobs Parados --}}
                @if(isset($jobs_parados) && count($jobs_parados)>0)
                    <div class="col-md-4">
                        <div class="small-box bg-danger com-shadow">
                            <div class="inner">
                                <h3>{{count($jobs_parados)}}</h3>
                                <p>Jobs Parados</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-pie-graph"></i>
                            </div>
                            <a href="{{ route('jobs.parados') }}" class="small-box-footer texto-preto">Mais informações</a>
                        </div>
                    </div>
                @endif       

            @endif
            </div>
        </div>
        <hr>
    <div class="row padding20">
        <div class="col-md-12" id="jobs_freelas" data-url="{{ route('home.item', 'jobs_freelas') }}">            
        </div>
        <div class="col-md-12" id="projeto-andamento" data-url="{{ route('home.item', 'projetos_andamento') }}">            
        </div>
        <div class="col-md-12" id="projetos_coordenando" data-url="{{ route('home.item', 'projetos_coordenando') }}">            
        </div>


    </div>
    
    {{-- end row --}}
  
@stop

@push('js')
    

<script type="text/javascript" src="{{ asset('js/pega_job.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/dashboard.js') }}"></script>

@endpush

