

<div class="row div-item-form">
    <div class="col-md-12">
        <p class=""><b>Nome</b></p>
        @isset($detalhe)
            <p id="nome" class="margemB20" >{{ $imagem['nome'] or 'Não Informado' }}</p>
        @else
            <input type="text" name="nome" class="form-control" value="{{ $imagem['nome']  or old('nome') }}" placeholder="Digite aqui" >
        @endif
    </div>
</div>

<div class="row div-item-form">
    <div class="col-md-12">
        <p class=""><b>Descrição</b></p>
        @isset($detalhe)
            <p id="descricao" class="margemB20" >{{ $imagem['descricao'] or 'Não Informado' }}</p>
        @else
            <textarea name="descricao" class="col-md-12 form-control" value="{{ $imagem['descricao']  or old('descricao') }}" placeholder="" >{{ $imagem['descricao']  or old('descricao') }}</textarea>
        @endif
    </div>
</div>

<div class="row div-item-form">
    <div class="col-md-12">
        <p class=""><b>Observações</b></p>
        @isset($detalhe)
            <p id="observacoes" class="margemB20" >{{ $imagem['observacoes'] or 'Não Informado' }}</p>
        @else
            <textarea name="observacoes" class="col-md-12 form-control" value="{{ $imagem['observacoes']  or old('observacoes') }}" placeholder="" >{{ $imagem['observacoes']  or old('observacoes') }}</textarea>
        @endif
    </div>
</div>

{{-- Campos Personalizados --}}
<div class="row div-item-form margemT20">
    <div class="col-md-12">
        <h3>Campos Personalizados</h3>
    </div>
</div>

@if(empty($detalhe))
    <div class="row div-item-form margemT10">
        <div class="col-sm-6 col-md-6">
            <p><b>Nome do Campo</b></p>
            <input type="text" class="form-control" name="" id="nome-campo">
        </div>
        <div class="col-sm-6 col-md-6">
            <p><b>Tipo de Campo</b></p>
            <select id="combo-tipos-campos" name="" class="form-control select2">
                <option value="text">Texto</option>
                <option value="number">Número</option>
            </select>
        </div>
    </div>

    <div class="row div-item-form margemT10">
        <div class="col-md-6 pull-right">
            <button id="add-campo" class="btn btn-success btn-flat pull-right"><i class="fa fa-check"></i> ADD</button>
        </div>
    </div>
@endif

<div class="row div-item-form margemT10 margemB40">
    <div class="col-md-12">
        @if(empty($detalhe))
            <p><b>Campos:</b></p>
        @endif
        <table class="table" id="lista-campos">
            <thead>
            <tr>
                <th colspan="2">Nome</th>
                <th>Tipo</th>
                @if(empty($detalhe))
                    <th>Remover</th>
                @endif
            </tr>
            </thead>
            <tbody>
                @isset($imagem->campos_personalizados)
                    @forelse($imagem->campos_personalizados as $campo)
                        <tr class="conteudo-campo">
                            <td colspan="2">
                                <p>{{$campo['nome']}}</p>
                                <input type="hidden" class="campos-personalizados" name="campos_personalizados[{{$campo['nome']}}][nome]" value="{{$campo['nome']}}">
                            </td>
                            <td>
                                <p>{{$campo['tipo']}}</p>
                                <input type="hidden" class="campos-personalizados" name="campos_personalizados[{{$campo['nome']}}][tipo]" value="{{$campo['tipo']}}">
                            </td>
                            @if(empty($detalhe))
                                <td>
                                    <a id="remove-campo{{$loop->iteration}}" href="javascript:void(0);" class="btn btn-flat btn-danger remove-campo">
                                        <i class="fa fa-times"></i>
                                    </a>
                                </td>
                            @endif
                        </tr>
                    @empty

                    @endforelse
                @endif
            </tbody>
        </table>
    </div>
</div>


