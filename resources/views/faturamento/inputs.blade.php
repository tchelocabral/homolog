

<div class="row div-tem-form">
    <div class="col-md-12">
        <p><b>Apelido</b></p>
        @isset($detalhe)
            <p id="apelido" class="margemB20" >{{ $faturamento->apelido ?? 'Não Informado' }}</p>
        @else
            <input type="text" id="faturamento-apelido" name="apelido" class="form-control faturamento" value="{{ $faturamento->apelido  ?? old('apelido') }}" placeholder="" required="required">
        @endisset
    </div>
</div>

<div class="row div-tem-form">
    <div class="col-md-12">
        <p><b>Razao Social</b></p>
        @isset($detalhe)
            <p id="razao_social" class="margemB20" >{{ $faturamento->razao_social ?? 'Não Informado' }}</p>
        @else
            <input type="text" id="faturamento-razao-social" name="razao_social" class="form-control faturamento" value="{{ $faturamento->razao_social  ?? old('faturamento_razao_social') }}" placeholder="" required="required">
        @endif
    </div>
</div>

<div class="row div-tem-form">
    <div class="col-md-12">
        <p><b>Nome Fantasia</b></p>
        @isset($detalhe)
            <p id="nome_fanatasia" class="margemB20" >{{ $faturamento->nome_fanatasia ?? 'Não Informado' }}</p>
        @else
            <input type="text" id="faturamento-nome-fantasia" name="nome_fantasia" class="form-control faturamento" value="{{ $faturamento->nome_fantasia  ?? old('faturamento_nome_fantasia') }}" placeholder="">
        @endisset
    </div>
</div>

<div class="row div-tem-form">
    <div class="col-md-12">
        <p><b>Cnpj</b></p>
        @isset($detalhe)
            <p id="cnpj" class="margemB20" >{{ $faturamento->cnpj ?? 'Não Informado' }}</p>
        @else
            <input type="text" id="faturamento-cnpj" name="cnpj" class="form-control faturamento cnpj" value="{{ $faturamento->cnpj  ?? old('cnpj') }}" placeholder="" required="required">
        @endisset
    </div>
</div>



<div class="row div-tem-form">
    <div class="col-md-12">
        <p><b>Nome Contato</b></p>
        @isset($detalhe)
            <p id="faturamento_nome_contato" class="margemB20" >{{ $faturamento->nome_contato ?? 'Não Informado' }}</p>
        @else
            <input type="text" id="faturamento-nome-contato" name="nome_contato" required="required" class="form-control faturamento" value="{{ $faturamento->nome_contato  ?? old('faturamento_email') }}" placeholder="">
        @endisset
    </div>
</div>

<div class="row div-tem-form">
    <div class="col-md-12">
        <p><b>E-mail Contato</b></p>
        @isset($detalhe)
            <p id="faturamento_email" class="margemB20" >{{ $faturamento->email_contato ?? 'Não Informado' }}</p>
        @else
            <input type="text" id="faturamento-email-contato" name="email_contato" required="required" class="form-control faturamento " value="{{ $faturamento->email_contato  ?? old('faturamento_email') }}" placeholder="">
        @endisset
    </div>
</div>