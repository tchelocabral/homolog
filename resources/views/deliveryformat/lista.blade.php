@extends('adminlte::page')

@section('title', __('messages.Todos os formatos de entrega'))

@section('content_header')
   {{ Breadcrumbs::render(__('messages.todos formatos de entrega')) }}
    
@stop

@section('content')

    <div class="row largura90 centralizado">
        <h1 class="margemB40">{{ __('messages.Formatos de Entrega cadastrados')}}</h1>

        <div class="col-md-12">
            
            <div class="btn-toolbar margemT10 btn-lista-novo" role="toolbar">
                <a href="{{ route('deliveryformat.create') }}" class="btn btn-success no-border " title="{{ __('messages.Criar Novo formato de entrega')}}" data-toggle="tooltip">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                </a>
            </div>

            @unless($deliverysformat->count())
                <p>{{ __('messages.Não Existem formatos de entrega Cadastrados')}}</p>
            @else

                <table id="lista-dashboard" class="table table-striped larguraTotal ">
                    <thead class="">
                    <tr class="">
                        <th colspan="7" class="th-ocean texto-branco padding12 com-borda box-title">{{ __('messages.Formatos de Entrega cadastrados')}}</th>
                    </tr>
                    </thead>
                    <tbody class="fundo-branco com-shadow">
                    @foreach($deliverysformat as $deliveryformat)
                        <tr class="">
                            <td class="desktop">#{{ $deliveryformat->id }}</td>
                            <td>{{ $deliveryformat->nome  }}</td>
                            <td class="desktop">{{ $deliveryformat->descricao }}</td>
                            <td class="desktop">{{ $deliveryformat->boas_praticas }}</td>
                            <td class="texto-direita paddingR20">
                                <a href="{{ route('deliveryformat.show', encrypt($deliveryformat->id)) }}" class="">{{ __('messages.Detalhes')}}</a>
                                |
                                <a href="{{ route('deliveryformat.edit', encrypt($deliveryformat->id)) }}" class="">{{ __('messages.Editar')}}</a>
                                 {{-- |
                                <form action="{{ route('deliveryformat.destroy', encrypt($deliveryformat->id)) }}" class="form-delete" id="form-deletar-tipo-img-{{ $deliveryformat->id }}" name="form-deletar-tipo-img-{{ $deliveryformat->id }}" method="POST" enctype="multipart/form-data">
                                    @method('DELETE')
                                    @csrf
                                    <a href="#" class="deletar-item" type="submit">
                                       {{ __('messages.Excluir')}}
                                    </a>
                                </form> --}}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endunless
        </div>
    </div>

@stop