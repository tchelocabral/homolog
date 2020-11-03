
<link rel="stylesheet" href="{{ asset('css/revisao.css') }}">
<div class="row paddingB40">
    <div class="col-md-12 col-sm-12">
        <form action="{{ route('revisao.avaliacao.store') }}" method="POST" enctype="multipart/form-data" id="form-revisao">
            <div class="row">
           
                <div class="col-md-4 pull-right">
                    <p class="negrito larguraTotal texto-direita margemT20" >{{ __('messages.Novo Deadline') }}</p>
                    <div  class="input-group margemT5 pull-right">    
                        <input type="date" name="data_entrega" id="data-entrega" class="form-control required-revisao" min="{{date('Y-m-d')}}" value="" required="true" />
                    </div>
                </div>
            </div>
           
                <div class="row">
                    <div class="col-md-4 pull-right">
                        <p class="negrito larguraTotal texto-direita margemT20" >{{ __('messages.Selecione uma imagem para fazer a revisão') }}</p>
                        <div id="image-preview" class="input-group image-preview margemT5">
                            <input type="text" id="image-preview-filename" class="form-control image-preview-filename" disabled="disabled" placeholder="Nenhuma imagem selecionada" name="logo" />
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
                    <br>
                    <i>{{ __('messages.Para deletar um pin click duas vezes sobre ele') }}</i>
                   <!--  <input type="text" name="pontoAtual" value="0" id="pontoAtual" > -->
                </div>
                <canvas id="canvas-revisao-img" ondrop="drop(event)" ondragover="allowDrop(event)" class="canvas-revisao-imagem"></canvas>
                <textarea id="pontos" rows="10" style="display: none;"></textarea>
                      @csrf
                    <input type="hidden" name="img_final" id="img-final">
                <div class="row" id="areaInput"></div>
                <div class="row">
                    <div class="col-md-3"> <br>
                        <input type="submit" id="revisao-avaliacao-submit" name="salvar" value="{{ __('messages.Salvar Revisão')}}" class="btn btn-primary"/>
                    </div>
                    <div class="col-md-3">
                        {{-- <a id="download" download="myImage.jpg" href="" onclick="download_img(this);">Download to myImage.jpg</a> --}}
                        <input type="hidden" name="foto_gerada" id="foto-gerada" value="">
                    </div>
                </div>
            </div>
            <input type="hidden" name="job_id" value="{{$job->id}}">
            <input type="hidden" name="revisao_atual" value="{{$revisao_atual}}">
            <input type="hidden" name="job_nome" value="{{$job->nome}}">
            <input type="hidden" name="tira_arquivos" id="tira-arquivos" value="{{$job->tira_arquivos}}">
        </form>
    </div>
</div>

<input type="hidden" id="text-n-info" value={{ __('messages.Não Informado') }}>
<input type="hidden" id="text-nv-rev" value={{ __('messages.Nova Revisão') }}>

@push('js')
    <script src="{{ asset('js/revisao.js') }}"></script>
    <!-- funções para revisao -->
    <script type="text/javascript">

    </script>
@endpush
