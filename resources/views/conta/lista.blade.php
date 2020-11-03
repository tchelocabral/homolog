
@extends('adminlte::page')

@section('title', __('messages.Minha Conta'))

@section('content_header')
   {{ Breadcrumbs::render('minha conta') }} 
@stop

@section('content')
    <div class="row margemT40 centralizado">
        @can("recebe-pagamento")
        <div class="row">
            <div class="col-md-3 col-sm-6 col-12">
              <div class="info-box">
                <span class="info-box-icon fundo-azul texto-branco"><i class="fa fa-money"></i></span>
  
                <div class="info-box-content">
                  <span class="info-box-text">{{ __('messages.Recebido')}}</span>
                  <span class="info-box-number">R$ @php $money = $dado_pag['recebidos']; @endphp @convert_money($money) </span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>  
            <!-- /.col -->
            <div class="col-md-3 col-sm-6 col-12">
              <div class="info-box">
                <span class="info-box-icon fundo-verde texto-branco"><i class="fa fa-money"></i></span>
  
                <div class="info-box-content">
                  <span class="info-box-text">{{ __('messages.A Receber')}}</span>
                  <span class="info-box-number">R$ @php $money = $dado_pag['receber']; @endphp @convert_money($money)</span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-md-3 col-sm-6 col-12">
              <div class="info-box">
                <span class="info-box-icon fundo-verde texto-branco"><i class="fa fa-money"></i></span>
  
                <div class="info-box-content">
                  <span class="info-box-text">{{ __('messages.Jobs em Execução')}}</span>
                  <span class="info-box-number">R$ @php $money = $dado_pag['execucao']; @endphp @convert_money($money)</span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-md-3 col-sm-6 col-12">
              {{-- <div class="info-box">
                <span class="info-box-icon texto-branco"><i class="fa fa-money"></i></span>
  
                <div class="info-box-content">
                  <span class="info-box-text">{{ __('messages.Total')}}</span>
                  <span class="info-box-number">R$ @php $money = $dado_pag['total_recebimento']; @endphp @convert_money($money)</span>
                </div>
              </div> --}}
            </div>
        </div>
        @endcan
        @can("faz-pagamento")
        <div class="row">
          <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
              <span class="info-box-icon fundo-azul texto-branco"><i class="fa fa-money"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">{{ __('messages.Pago')}}</span>
                <span class="info-box-number">R$ @php $money = $dado_pag['pagos']; @endphp @convert_money($money)</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
              <span class="info-box-icon fundo-verde texto-branco"><i class="fa fa-money"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">{{ __('messages.A Pagar')}}</span>
                <span class="info-box-number">R$ @php $money = $dado_pag['pagar']; @endphp @convert_money($money)</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
              <span class="info-box-icon fundo-verde texto-branco"><i class="fa fa-money"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">{{ __('messages.Jobs em Execução')}}</span>
                <span class="info-box-number">R$ @php $money = $dado_pag['pagamento_execucao']; @endphp @convert_money($money)</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-md-3 col-sm-6 col-12">
            {{-- <div class="info-box">
              <span class="info-box-icon texto-branco"><i class="fa fa-money"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">{{ __('messages.Total')}}</span>
                <span class="info-box-number">R$ @php $money = $dado_pag['total_pagamento']; @endphp @convert_money($money)</span>
              </div>
            </div> --}}
          </div>
        </div>
        @endcan
        
        <div class="nav-tabs-custom altura-minima">
            <ul class="nav nav-tabs azul" id="tabs-politica" role="tablist">
              @can("faz-pagamento")
                @if($permissao == 'admin')
                  <li class="{{$permissao == 'admin' ? 'active' : ''}}">
                    <a data-toggle="tab" href="#pagamentos" aria-expanded="false" class="nav-link">{{ __('messages.Liberar Pagamentos')}}</a>
                  </li>
                @endif
              @endcan

              @can("recebe-pagamento")
                <li class="{{$permissao == 'freelancer' ? ' active' : ''}}">
                  <a data-toggle="tab" href="#recebimentos" aria-expanded="false" class="nav-link">{{ __('messages.Recebimentos')}}</a>
                </li>
              @endcan

              @can('faz-pagamento')
                <li class="{{$permissao == 'publicador' ? ' active' : ''}}">
                  <a data-toggle="tab" href="#pagamentos-jobs" aria-expanded="false" class="nav-link">{{ __('messages.Pagamentos Efetuados')}}</a>
                </li>
              @endcan

              @can('faz-pagamento')
              <li class="">
                <a data-toggle="tab" href="#pagamentos-pendentes" aria-expanded="false" class="nav-link">{{ __('messages.Jobs Aguardando Pagamentos')}}</a>
              </li>
              @endcan
            </ul>
            {{-- {{ dd($dado_pag) }} --}}
            <div class="tab-content">
                {{-- Tab #detalhes--}}

                @can("faz-pagamento")
                  <div id="pagamentos" class="tab-pane fade {{$permissao == 'admin' ? 'in active' : ''}} ">
                    @include('conta.pagamentos')
                  </div>
                @endcan
                
                @can("recebe-pagamento")
                  <div id="recebimentos" class="tab-pane fade  {{$permissao == 'freelancer' ? 'in active' : ''}}">
                    @include('conta.recebimentos')
                  </div>
                @endcan

                @can('faz-pagamento')
                  <div id="pagamentos-jobs" class="tab-pane fade {{$permissao == 'publicador' ? 'in active' : ''}} ">
                    @include('conta.pagamentos_jobs')
                  </div>
                @endcan
                
                @can('faz-pagamento')
                  <div id="pagamentos-pendentes" class="tab-pane fade  ">
                    <div class="row largura90 centralizado">
                      @include('job.tabela_jobs', ['jobs' => $jobs])
                      {{-- @include('cliente.inputs', ['cliente' => $cliente, 'detalhe' => true]) --}}
                    </div>
                  </div>
                @endcan


            </div><!-- end tab content -->
        </div>
       
    </div>
@stop
