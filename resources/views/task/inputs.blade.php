<div class="row div-item-form">
    <div class="col-md-12">
        <p class=""><b>Nome</b></p>
        @isset($detalhe)
            <p id="nome" class="margemB20" >{{ $task->nome ?? 'Não Informado' }}</p>
        @else
            <input type="text" name="nome" class="form-control" value="{{ $task->nome  ?? old('nome') }}" placeholder="Digite aqui" >
        @endif
    </div>
</div>

<div class="row div-item-form">
    <div class="col-md-12">
        <p class=""><b>Descrição</b></p>
        @isset($detalhe)
            <p id="descricao" class="margemB20" >{{ $task->descricao ?? 'Não Informado' }}</p>
        @else
            <textarea name="descricao" class="col-md-12 form-control" placeholder="" >{{ $task->descricao  ?? old('descricao') }}</textarea>
        @endif
    </div>
</div>

<div class="row div-item-form">
    <div class="col-md-12">
        <p class="margemB20 "><b>Notificação para Coordenador</b></p>
        @isset($detalhe)
            <p id="notification" class="margemB20" >{{ $task->notification ? "Envia notificação" : 'Não envia notificação' }}</p>
        @else
            <div class="radio iradio margemB20">
                <label>
                   <input type="radio" class="form-control margemB10  margemR20" name="notification" {{ $task && $task->notification == 1 ? "checked='checked'":"" }} value="1"> Envia notificação 
                </label>
            </div>
            <div class="radio iradio">
                <label>
                  <input type="radio" class="form-control margemR20" name="notification" {{ $task && $task->notification == 0 ? "checked='checked'":"" }}  value="0" > Não envia notificação
                </label>
            </div>
        @endif
    </div>
</div>


