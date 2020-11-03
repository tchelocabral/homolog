{{-- {{ dd($user->jobs) }} --}}

@extends('adminlte::page')

@section('title', 'Permissões por políticas')

@section('content_header')
    {{ Breadcrumbs::render('Permissões por politicas') }}
@stop

@section('content')
<div class="table-responsive">
    <table class="table no-margin table-striped larguraTotal com-shadow">
        <thead>
            <tr>
                <th class="negrito com-border-left"> Permissões / Políticas </th>
                @foreach ($roles as $role)
                    <th class="texto-centralizado th-ocean texto-branco padding12">{{ $role->name }}</th>
                    @php
                        $role->permissions_ids = $role->permissions->pluck('id')->toArray();   
                    @endphp
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($permissions as $permissao)
                <tr>
                    <td>{{ $permissao->name }}</td>
                    @foreach ($roles as $role)
                        @if(in_array($permissao->id, $role->permissions_ids))
                            <td class="texto-centralizado fundo-azul texto-branco">x</td>
                        @else
                            <td class="texto-centralizado">-</td>
                        @endif

                        
                    @endforeach
                </tr>
            @endforeach

        </tbody>

    </table>
</div>
@stop


@push('js')

<script src="{{ asset('js/jquery.fancybox.min.js') }}"></script>
    
  

@endpush