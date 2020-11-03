
<link rel="stylesheet" href="{{ asset('css/revisao.css') }}">
<div class="row paddingB40">
    <div class="col-md-12 col-sm-12">
        <form action="{{ route('revisao.imagem.store') }}" method="POST" enctype="multipart/form-data" id="form-revisao">
            <div class="row">
                 <div class="col-md-4 pull-right">
                     <div id="image-preview" class="input-group image-preview margemT20">
                         <input type="text" id="image-preview-filename" class="form-control image-preview-filename" disabled="disabled" placeholder="{{ __('messages.Nenhuma imagem selecionada') }}" name="logo" />
                         <span class="input-group-btn">
                            <!-- image-preview-clear button -->
                            <button type="button" class="btn btn-default image-preview-clear" id="image-preview-clear" style="display:none;">
                                <span class="glyphicon glyphicon-remove"></span> {{ __('messages.Limpar') }}
                            </button>
                            <!-- image-preview-input -->
                            <div class="btn btn-default image-preview-input" id="image-preview-input">
                                <span class="glyphicon glyphicon-folder-open"></span>
                                <span class="image-preview-input-title" id="image-preview-input-title">{{ __('messages.Procurar') }}</span>
                                <input type="file" accept="image/x-png, image/jpeg, image/gif" name="img_revisao_base" id="img_revisao_base" value="" />
                            </div>
                         </span>
                     </div>
                 </div>
            </div>
            <div>
                <div>
                    <img id="imagem-pin" draggable="true" ondragstart="drag(event)" onclick="deletePoint(event)" src="{{ asset('images/pins/azul/1.png') }}">
                   <!--  <input type="text" name="pontoAtual" value="0" id="pontoAtual" > -->
                </div>
                <canvas id="canvas-revisao-img" ondrop="drop(event)" ondragover="allowDrop(event)" class="canvas-revisao-imagem"></canvas>
                <textarea id="pontos" rows="10" style="display: none;"></textarea>
                      @csrf
                    <input type="hidden" name="img_final" id="img-final">
                <div class="row" id="areaInput"></div>
                <div class="row">
                    <div class="col-md-3"> <br>
                        <input type="submit" name="salvar" value="Salvar Revisão" class="btn btn-primary" />
                    </div>
                </div>
            </div>
            <input type="hidden" name="imagem_id" value="{{$imagem_id}}">
            <input type="hidden" name="tira_arquivos" id="tira-arquivos" value="0">
        </form>
    </div>
</div>


<!-- funções para revisao -->
<script type="text/javascript">

   


</script>