<div class="row div-item-form">
    <div class="col-md-12">
        <p class=""><b>Nome</b></p>
        @isset($detalhe)
            <p id="nome" class="margemB20" >{{ $habilidade['nome'] or 'Não Informado' }}</p>
        @else
            <input type="text" name="nome" class="form-control" value="{{ $habilidade['nome']  or old('nome') }}" placeholder="Digite aqui" />
        @endif
    </div>
</div>

<div class="row div-item-form">
    <div class="col-md-12">
        <p class=""><b>Descrição</b></p>
        @isset($detalhe)
            <p id="descricao" class="margemB20" >{{ $habilidade['descricao'] or 'Não Informado' }}</p>
        @else
            <textarea name="descricao" class="col-md-12 form-control" value="{{ $habilidade['descricao']  or old('descricao') }}" placeholder="" >{{ $habilidade['descricao']  or old('descricao') }}</textarea>
        @endif
    </div>
</div>

<div class="row div-item-form">
    <div class="col-md-12">
        <p class=""><b>Teste</b></p>
        @isset($detalhe)
            <p id="teste" class="margemB20" >{{ $habilidade['teste'] or 'Não Informado' }}</p>
        @else
            <textarea name="teste" class="col-md-12 form-control" value="{{ $habilidade['teste']  or old('teste') }}" placeholder="" >{{ $habilidade['teste']  or old('teste') }}</textarea>
        @endif
    </div>
</div>

 <div class="row div-item-form">
    <div class="col-md-12">
        <p class="detalhe-label"><b>Cor</b></p>
        @isset($detalhe)
            <p class="cor">
                <span class="label label-{{ $habilidade->cor }}">{{ $habilidade->nome_cor()}}</span>
            </p>
        @else
            <select id="combo-cores" name="cor" class="form-control select2 margemT10">
                <option value="primary" 
                    {{isset($habilidade) && $habilidade->cor == 'primary' ? 'selected=selected' : ''}}>
                Azul</option>
                <option value="info" 
                    {{isset($habilidade) && $habilidade->cor == 'info' ? 'selected=selected' : '' }}>
                Azul Claro</option>
                <option value="secondary" 
                    {{isset($habilidade) && $habilidade->cor == 'secondary' ? 'selected=selected' : '' }}>
                Cinza</option>
                <option value="warning" 
                    {{isset($habilidade) && $habilidade->cor == 'warning' ? 'selected=selected' : '' }}>
                Laranja</option>
                <option value="dark" 
                    {{isset($habilidade) && $habilidade->cor == 'dark' ? 'selected=selected' : '' }}>
                Preto</option>
                <option value="light" 
                    {{isset($habilidade) && $habilidade->cor == 'light' ? 'selected=selected' : '' }}>
                Transparente</option>
                <option value="success" 
                    {{isset($habilidade) && $habilidade->cor == 'success' ? 'selected=selected' : '' }}>Verde</option>
                <option value="danger" 
                    {{isset($habilidade) && $habilidade->cor == 'danger' ? 'selected=selected' : '' }}>
                Vermelho</option>
            </select>
        @endif
    </div>
</div>


