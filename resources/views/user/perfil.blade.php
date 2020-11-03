{{-- {{ dd($user->jobs) }} --}}

@extends('adminlte::page')

@section('title', 'Perfil de ' . $user->name)

@section('content_header')
    {{ Breadcrumbs::render('perfil', $user) }}
@stop

@section('css')
    @if($user->mostra_avaliacao)
        <link rel="stylesheet" href="{{ asset('css/star-rating.css')}} ">
    @endif
@endsection

<link rel="stylesheet" href="{{ asset('css/jquery.fancybox.css') }}">


@section('content')

    <div class="row largura80 centralizado">

        @empty($user)
            <h1>{{ __('messages.Perfil não Encontrado') }}</h1>
        @else
            <div class="row">  
                <div class="btn-toolbar margemT10 tab-pane fade in" role="toolbar">
                    <h1 class="margemB40">{{ __('messages.Perfil') }}</h1>
                    @if(\Auth::user()->hasAnyRole(['admin', 'desenvolvedor']))      
                        @php
                            if(empty($user->plano_id)) {
                                $frase_botao_plano = __('messages.Atribua um Plano'); 
                            }
                            else {
                                $frase_botao_plano = __('messages.Mudar Plano'); 
                            }
                        @endphp
                        
                        @if(!empty($user->plano_id)) 
                            {{__('messages.Plano').' '. $user->plano_id}} 
                        @endif
                        
                        <div class="col-4" >
                            <a class="larguraTotal alturaTotal texto-preto"  data-toggle="collapse" data-target="#user-plano-{{ $user->id }}" aria-expanded="false" aria-controls="#collapsejobrecu" role="button"><span class="accordion-marc"><i class="fa fa-angle-right"></i></span>
                                {{ $frase_botao_plano }}
                            </a>
                        </div>

                        <div class="col-4" class="collapse" aria-expanded="false" id="user-plano-{{ $user->id}}">
                            <div class="row"> 
                                <form action="{{ route('user.atribuir.plano')}}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="id" value="{{encrypt($user->id)}}">
                                    <input type="hidden" name="role_id" value="{{$user->roles->first()->id}}">
                                    <input type="hidden" name="role_name" value="{{$user->roles->first()->name}}">
                                    <div class="col-md-4"> 
                                        <select id="planos" name="plano_id" class="form-control select2 margemT10">
                                            <option value="-1">{{ __('messages.Escolha um Plano') }}</option>
                                            @isset($planos)
                                                @unless($planos)
                                                    <option value="-1">{{ __('messages.Sem Planos Cadastrados') }}</option>
                                                @else
                                                    @foreach($planos as $index => $plan)
                                                        <option value="{{ $plan->id }}">{{$plan->nome }}</option>
                                                    @endforeach
                                                @endunless
                                            @endif
                                        </select>
                                    </div>
                                    <div class="col-md-4"> 
                                        <button id="publicar-job" class="btn btn-success pull-right margemR5" name="btnCriaPlano">{{ __('messages.Atribuir Plano') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>                  
                    @endif
                </div>

                <div class="btn-toolbar margemT10 tab-pane fade in" role="toolbar">
                    @if(Auth::id() == $user->id)
                        <a href="{{ route('user.nova.senha', encrypt($user->id)) }}" class="btn btn-warning" title="{{__('messages.Alterar Senha')}}" data-toggle="tooltip">{{ __('messages.Alterar Senha') }}</a>



                        @if(empty(!$user->exclusao_solicitada_em))
                            <a href="#" class="btn btn-danger margemL5" title="{{__('messages.Solicitação para encerrar a conta enviada')}}" data-toggle="tooltip">
                                {{ __('messages.Solicitação para encerrar a conta enviada') }}
                            </a>
                        @else
                            <form action="{{ route('user.encerrar.conta', encrypt($user->id)) }}" class="form-conta-encerrada semMargem" id="form-encerrar-conta" name="form-encerrar-conta" method="POST" enctype="multipart/form-data" >
                                    @csrf
                                <a href="#" class="btn btn-danger encerrar-item margemL5" title="{{__('messages.Encerrar Conta')}}" data-toggle="tooltip" type="submit">
                                    {{ __('messages.Encerrar Conta') }}
                                </a>
                            </form>
                        @endif

                        
                    @endif 
                    @if( $user->editar_perfil)     
                        
                        <a href="{{ route('users.edit', encrypt($user->id)) }}" class="btn btn-success " title="{{__('messages.Editar Usuário')}}" data-toggle="tooltip">
                            {{-- <i class="fa fa-pencil" aria-hidden="true"></i> --}}
                            {{ __('messages.Editar') }} {{__('messages.Perfil')}}
                        </a>

                        @can('gerencia-politicas') 
                            {{-- ATIVA/DESATIVA USUÁRIO --}}
                            <a href="{{  route('user.mudar.status', encrypt($user->id)) }}" class="btn btn-warning " title="Mudar Status Usuário" data-toggle="tooltip">
                                {{ !$user->ativo ? __('messages.Ativar') : __('messages.Desativar') }} {{__('messages.Perfil')}}
                            </a>   
                            
                            {{-- REENVIA NOTIFICAÇÃO PARA ATIVAÇÃO --}}
                            <form action="{{ route('envia.ativacao.user') }}" class="form-add semMargem " id="form-add-slot-user-{{ $user->id }}" name="form_add_slot_user_{{ $user->id }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ encrypt($user->id) }}">
                                <button class="btn cyan margemL5" title="{{__('messages.Reenviar Ativação de Conta')}}" data-toggle="tooltip" type="submit">
                                    {{-- <i class="fa fa-plus" aria-hidden="true"></i> --}}
                                    {{__('messages.Reenviar Ativação de Conta')}}
                                </button>
                            </form>
                            
                            {{-- AUMENTA SLOTS DE JOBS LIVRES --}}
                            <form action="{{ route('user.add.job.slot') }}" class="form-add-slot semMargem " id="form-add-slot-user" name="form_add_slot_user_{{ $user->id }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ encrypt($user->id) }}">
                                <input type="hidden" name="tipo" value="livre">
                                <button class="btn btn-primary aumentar-slots margemL5" 
                                title="{{__('messages.Aumentar Slots')}}" 
                                data-botao-sim = "{{__('messages.Sim, alterar')}}!"
                                data-botao-nao = "{{__('messages.Não, nunca')}}!"
                                data-title="{{__('messages.Confirmar a quantidade do Slot?')}}" 
                                data-msg="{{__('messages.Digite a nova quantidade de Slot de Job')}}" 
                                data-toggle="tooltip" type="submit">
                                    {{-- <i class="fa fa-plus" aria-hidden="true"></i> --}}
                                    {{__('messages.Aumentar Slots Job Livre')}}
                                </button>
                            </form>

                            {{-- SLOTS JOBS PROPOSTAS E CANDIDATURAS --}}
                            <form action="{{ route('user.add.job.slot.candidatura') }}" class="form-add-slot-candidatura semMargem " id="form-add-slot-candidatura-user" name="form_add_slot_user_{{ $user->id }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ encrypt($user->id) }}">
                                <input type="hidden" name="tipo" value="candiodaturas">
                                <a href="javascript:void(0);" class="btn btn-primary aumentar-slot-candidatura margemL5" 
                                title="{{__('messages.Aumentar Slots Jobs de Candidaturas e Propostas')}}" 
                                data-botao-sim = "{{__('messages.Sim, alterar')}}!"
                                data-botao-nao = "{{__('messages.Não, nunca')}}!"
                                data-title="{{__('messages.Confirmar a quantidade do Slot?')}}" 
                                data-msg="{{__('messages.Digite a nova quantidade de Slot de Job')}}" 
                                data-toggle="tooltip" type="submit">
                                    {{-- <i class="fa fa-plus" aria-hidden="true"></i> --}}
                                    {{__('messages.Aumentar Slots para Jobs de Candidaturas e Propostas')}}
                                </a>
                            </form>
                            {{-- DELETA USUÁRIO --}}
                        
                            @php
                                if(empty(!$user->exclusao_solicitada_em)) {
                                    $frase_botao_excluir = __('messages.Usuário solicitou o encerramento da conta'); 
                                }
                                else {
                                    $frase_botao_excluir = __('messages.Excluir Usuário'); 
                                }
                            @endphp
                            <div class="row">
                                <div class="col-12">
                                    @if($user->podeApagar)
                                        <form action="{{ route('user.deletar', encrypt($user->id)) }}" class="form-delete semMargem" id="form-deletar-usuario-{{ $user->id }}" name="form-deletar-projeto-{{ $user->id }}" method="POST" enctype="multipart/form-data">
                                            @method('DELETE')
                                            @csrf
                                            <a href="#" class="btn btn-danger deletar-item margemL5" title="{{ $frase_botao_excluir }}" data-toggle="tooltip" type="submit">
                                                {{ $frase_botao_excluir }}  
                                            </a>
                                        </form>
                                    @else
                                        <form action="{{ route('user.transferir.deletar', encrypt($user->id)) }}" class="form-delete semMargem" id="form-deletar-usuario-{{ $user->id }}" name="form-deletar-projeto-{{ $user->id }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <a href="#" class="btn btn-danger transferir-item-usuario margemL5" title="{{ $frase_botao_excluir }}" data-toggle="tooltip" type="submit">
                                                {{ $frase_botao_excluir }}  
                                            </a>
                                            {{-- {{ dd($user->jobs->get()) }} --}}
                                            <input type="hidden" name="qtd_jobs" id="qtd-jobs" value="{{ $user->jobs ? count($user->jobs) : '0'  }}">
                                            <input type="hidden" name="qtd_avaliando" id="qtd-avaliando" value="{{ $user->avaliando ? count($user->avaliando) : '0'  }}">
                                            <input type="hidden" name="qtd_coordenando" id="qtd-coordenando" value="{{ $user->coordenando ? count($user->coordenando) : '0'  }}">
                                            <input type="hidden" name="qtd_coordenando_projetos" id="qtd-coordenando-projetos" value="{{ $user->coordenandoProjetos ? count($user->coordenandoProjetos) : '0'  }}">
                                            <div class="invisivel">
                                                <select id="usuarios-troca" name="usuarios_troca" class="">
                                                    <option value="-1">{{ __('messages.Escolha um usuário') }}</option>
                                                    @foreach($user->lista_usuarios_troca as $index => $user_troca)
                                                        <option value="{{ $user_troca->id }}">{{$user_troca->name }}</option>
                                                    @endforeach

                                                </select>
                                            </div>
                                        </form>
                                    @endif
                                </div>
                            </div>

                            
                            
                            {{-- <a href="{{ route('user.nova.senha', encrypt($user->id)) }}" class="btn btn-warning" title="{{__('messages.Excluir conta')}}" data-toggle="tooltip">{{ __('messages.Excluir Conta') }}</a> --}}



                        @endcan
                        
                    @endif
                    
                    @if(Auth::id() == $user->id )
                        {{-- DELETA USUÁRIO --}}
                        {{-- <div class="row">
                            <div class="col-12">
                                <form action="{{ route('user.deletar', encrypt($user->id)) }}" class="form-delete semMargem" id="form-deletar-usuario-{{ $user->id }}" name="form-deletar-projeto-{{ $user->id }}" method="POST" enctype="multipart/form-data">
                                    @method('DELETE')
                                    @csrf
                                    <a href="#" class="btn btn-danger deletar-item margemL5" title="{{__('messages.Excluir Usuário')}}" data-toggle="tooltip" type="submit">
                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                    </a>
                                </form>
                            </div>
                        </div> --}}
                    @endif


                </div>
                
            </div>
            <hr>
            @php $em_andamento = 0; $concluidos = 0; @endphp

            <div class="row clearfix">

                <div class="col-md-3">

                    <!-- Profile Image -->
                    @php
                        $class_formatacao = 'borda-azul-t';
                        $class_formatacao_button = 'fundo-azul texto-branco';

                        if($user->ativo == 1 && empty(!$user->exclusao_solicitada_em)) 
                        {
                            $class_formatacao = 'borda-vermelho-t';
                            $class_formatacao_button = 'fundo-vermelho texto-branco';
                        }
                        elseif($user->ativo == 0 )
                        {
                            if($user->desativado_em ==null)
                            {
                                $class_formatacao = 'borda-laranja-t';
                                $class_formatacao_button = 'orange texto-branco';
                            }
                            else {
                                $class_formatacao = 'borda-vinho-t';
                                $class_formatacao_button = 'fundo-vinho texto-branco';
                            }
                        }
                       
                    @endphp

                    <div class="box {{ $class_formatacao }} com-shadow">
                      <div class="box-header with-border">
                        <div class="img-avatar">
                          <img class="bkg-img-avatar" src="{{ $user->image ? URL::asset($user->image) : URL::asset("storage/images/user/avatar-default.png") }}" alt="User profile picture">
                        </div>
        
                        <h3 class="profile-username text-center">{{ $user->name  }}</h3>
                        

                        @if($user->mostra_avaliacao)
                            <div class="displayFlex  larguraTotal flexCentralizado">
                                @include('avaliacao.modal', ['job' => null, 'avaliar' => false, 'avaliacoes' => $user->avaliacoes, 'media' => $user->media_nota, 'tamanho' => false])
                            </div>
                        @endif

                        <p class="text-muted text-center">
                            Membro desde: <br>
                            {{$user->created_at->format('d/m/Y')}}
                        </p>

                        <ul class="list-group list-group-unbordered mb-3">
                          <li class="list-group-item displayFlex flexCentralizado flexSpaceBetween margemR10">
                            <b>{{ __('messages.Jobs concluídos') }}</b> <a class="float-right">{{ $user->total_completo ?? '0' }}</a>
                          </li>
                          <li class="list-group-item displayFlex flexCentralizado flexSpaceBetween margemR10">
                            <b>{{ __('messages.Jobs em andamento') }}</b> <a class="float-right">{{ $user->total_executando ?? '0' }}</a>
                          </li>

                          @if($user->roles[0]->name == 'freelancer')
                            <li class="list-group-item displayFlex flexCentralizado flexSpaceBetween margemR10">
                                <b>{{ __('messages.Slots Job Livre') }}</b> <a class="">{{ $user->slots_livre_uso . '/' . $user->total_slots_livre }}</a>
                            </li>
                            <li class="list-group-item displayFlex flexCentralizado flexSpaceBetween margemR10">
                                <b>{{ __('messages.Slots Jobs de Candidatura') }}</b> <a class="">{{ $user->slots_candidaturas_uso . '/' . $user->total_slots_candidaturas }}</a>
                            </li>
                          @endif

                        </ul>
                       
                        @if( $user->editar_perfil) 
                            <a href = "{{ $user->url_portfolio  }}" class="texto-centralizado btn {{ $class_formatacao_button }} btn-block"  target="_bllsank">
                                <b> {{ __('messages.Portfólio') }}</b>
                            </a>
                        @endif

                      

                    </div>
                      <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                        

                    <!-- About Me Box -->
                    <div class="box {{ $class_formatacao }} com-shadow">
                        <div class="box-header with-border ">
                            <h4 class="box-title texto-preto">{{ __('messages.Dados pessoais e de acesso do usuário') }} </h4>
                           <div class="card-header">
                                <h3 class="card-title">{{ __('messages.Bio') }}</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                {{-- <strong><i class="fas fa-book mr-1"></i> Education</strong> --}}
                                <p class="text-muted">
                                    {{ $user->bio }}
                                </p>
                                <hr>
                                <strong><i class="fas fa-pencil-alt mr-1"></i> {{ __('messages.Localização') }}</strong>
                                <p class="text-muted">
                                    {{ $user->cidade }} {{ $user->estado }}
                                </p>
                                <hr>
                            </div>
                            <!-- /.card-body -->
                        </div>
                    </div>
                    <!-- /.card -->
                </div>


                  <!-- Tab Jobs -->
                  <div class="col-md-9">
                  
                    <div class="w-100">
                        <div class="box {{$class_formatacao}} com-shadow">
                          <div class="box-header with-border">
                            <h3 class="box-title texto-preto">{{__('messages.Imagens Galeria')}}</h3>

                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            @empty($user->galeria)
                                <h4 class="texto-preto">{{ __('messages.Usuário sem Jobs Cadastrados') }}</h4>
                            @else
                                <div class="div-item-form">
                                    @foreach($user->galeria as $index => $gal)
                                    <div class="col-md-4 margemB10">
                                        <a class="grouped_elements" rel="group1" href="{{asset('storage/'. $gal->value)}}">
                                            <img img src="{{asset('storage/'. $gal->value)}}" class="" style="width:100%">
                                        </a>
                                    </div>
                                    @endforeach
                                </div>
                                <!-- /.div-table-responsive -->
                            @endempty                                               
                        </div>                 
                        <!-- /.box-body -->
                      </div>
                    </div>

                    <div class="w-100">
                        <div class="box {{$class_formatacao }} com-shadow">
                          <div class="box-header with-border">
                            <h3 class="box-title texto-preto">{{ __('messages.Jobs do Usuário') }}</h3>

                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            @empty($user->jobs)
                                <h4>{{ __('messages.Usuário sem Jobs Cadastrados') }}</h4>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-striped larguraTotal  search-table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>{{ __('messages.Job') }}</th>
                                                <th>{{ __('messages.Progresso') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $em_andamento = 0; $concluidos = 0; @endphp
                                            @foreach($user->jobs->reverse() as $job)
                                                @php 
                                                    $job->concluido()<100 ? $em_andamento++ : $concluidos++;  
                                                @endphp
                                                <tr> 
                                                    <td><a href="{{ route('jobs.show', encrypt($job->id)) }}">{{ $job->id }}</a></td>
                                                    <td><a href="{{ route('jobs.show', encrypt($job->id)) }}">{{ $job->nome }}</a></td>
                                                    <td class="">
                                                        <div class="progress">
                                                            <div class="progress-bar progress-bar-animated progresso" role="progressbar" aria-valuenow="{{ $job->concluido() }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.div-table-responsive -->
                            @endempty                                               
                        </div>                 
                        <!-- /.box-body -->
                      </div>
                    </div>

                </div>
              
            </div> <!-- end row clearfix -->
        @endempty
    </div>
@stop


@push('js')

<script src="{{ asset('js/jquery.fancybox.min.js') }}"></script>
    
    @if($user->mostra_avaliacao)
        <script src="{{ asset('js/star-rating.js') }}"></script>
    @endif

    <script>
        $(document).ready(function() {

            $("a.grouped_elements").fancybox();

            // /* This is basic - uses default settings */
            
            // $("a#single_image").fancybox();
            
            // /* Using custom settings */
            
            // $("a#inline").fancybox({
            //     'hideOnContentClick': true
            // });
        
            // /* Apply fancybox to multiple items */
            
            // $("a.grouped_elements").fancybox({
            //     'transitionIn'	:	'elastic',
            //     'transitionOut'	:	'elastic',
            //     'speedIn'		:	600, 
            //     'speedOut'		:	200, 
            //     'overlayShow'	:	false
            // });
            
            // $("a.grouped_elements").fancybox({
            //     'transitionIn'		: 'none',
            //     'transitionOut'		: 'none',
            //     'titlePosition' 	: 'over',
            //     'titleFormat'       : function(title, currentArray, currentIndex, currentOpts) {
            //         return '<span id="fancybox-title-over">Image ' +  (currentIndex + 1) + ' / ' + currentArray.length + ' ' + title + '</span>';
            //     }
            // });

        });
    </script>

@endpush