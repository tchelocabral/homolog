@extends('adminlte::page')

@section('title', 'Membros')

@section('content_header')
    {{ Breadcrumbs::render('todos os membros') }}
@stop

@section('content')

    <div class="row largura80 centralizado">
        <div class="col-md-12">
            <h1 class="margemB40">{{$tipo}} Cadastrados - {{ $users->count() }}</h1>
           

            {{--  Botao Criar Novo --}}
            <div class="btn-toolbar margemT10 btn-lista-novo" role="toolbar">
            @php
                $rota="users.create";
                if($tipo=="Coordenadores") {
                    $rota = "users.create.coordenador";
                }

            @endphp
                <a href="{{ route($rota) }}" class="btn btn-success no-border " title="Criar Novo" data-toggle="tooltip">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                </a>
            </div>
                
            @unless($users->count())
                    <p>Não Existem {{$tipo}} Cadastrados</p>
            @else

                <table id="lista-dashboard" class="table larguraTotal table-striped com-filtro">
                    <thead class="">
                    <tr class="">
                        <th colspan="" class="th-ocean texto-branco padding12 ">Nome</th>
                        <th colspan="" class="th-ocean texto-branco padding12 ">Política de Acesso</th>
                        <th colspan="" class="th-ocean texto-branco padding12 ">ID</th>
                        <th colspan="" class="th-ocean texto-branco padding12 ">E-mail</th>
                        <th colspan="" class="th-ocean texto-branco padding12 ">Marcador</th>
                        <th colspan="" class="th-ocean texto-branco padding12 ">Membro desde</th>
                        <th colspan="" class="th-ocean texto-branco padding12 ">Detalhes</th>
                        <th colspan="" class="th-ocean texto-branco padding12 ">Ativo</th>
                    </tr>
                    </thead>
                    <tbody class="fundo-branco com-shadow">
                        

                        @foreach($users as $prop => $usu)
                        @php
                            $class_formatacao_fundo = '';
                            $class_formatacao_texto = '';

                            if($usu->ativo == 1 && empty(!$usu->exclusao_solicitada_em)) 
                            {
                                $class_formatacao_fundo = 'fundo-vermelho';
                                $class_formatacao_texto = 'texto-branco';
                            }
                            elseif($usu->ativo == 0)
                            {
                                if($usu->desativado_em ==null)
                                {
                                    $class_formatacao_fundo = 'fundo-laranja';
                                    $class_formatacao_texto = 'texto-branco';
                                }
                                else {
                                    $class_formatacao_fundo = 'fundo-vinho';
                                    $class_formatacao_texto = 'texto-branco';
                                }
                            }
                            
                        @endphp
                        <tr class="{{ $class_formatacao_fundo }}  {{ $class_formatacao_texto }}">
                            <td>{{ $usu->name  }}</td>
                            <td class="desktop">{{  $usu->roles()->first()->name?? null }}</td>
                            <td class="desktop">#{{ $usu->id }}</td>
                            <td class="desktop">{{ $usu->email }}</td>
                            <td class="desktop">{{ $usu->marcador }}</td>
                            <td class="desktop">{{ $usu->created_at ? \Carbon\Carbon::parse($usu->created_at)->format('d.m.Y') : 'Não Informado'}}</td>
                            <td>
                                <a href="{{ route('users.show', encrypt($usu->id)) }}" class="">Detalhes</a>
                            </td>
                            <td class="desktop">
                                <input id="check{{ $usu->id }}"  name="check{{ $usu->id }}" type="checkbox" class="centralizado"
                                       value="{{ $usu->ativo }}"  {{ $usu->ativo ? 'checked = "checked"' : ''}} disabled="disabled">
                                <label for="check{{ $usu->id }}" class="radioLabel"><span></span></label>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endunless
        </div>
    </div>
@stop
