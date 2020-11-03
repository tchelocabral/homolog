
@extends('adminlte::page')

@section('title', __('messages.Minha Conta'))

@section('content_header')
   {{ Breadcrumbs::render('minha conta') }} 
@stop

@section('content')
    <div class="row margemT40 centralizado">
        
        <div class="nav-tabs-custom altura-minima">
            <ul class="nav nav-tabs azul" id="tabs-politica" role="tablist">
              <li>
                <a data-toggle="tab" href="#conta-cadastrada"   aria-expanded="true" class="nav-link">{{__('messages.Editar Conta Cadastrada')}} </a>
              </li>                  
            </ul>
            {{-- {{ dd($dado_pag) }} --}}
            <div class="tab-content">
                <div id="conta-cadastrada" class="tab-pane fade in active">
                  @include('conta.nova_conta')
                </div>

            </div><!-- end tab content -->
        </div>
       
    </div>
@stop
