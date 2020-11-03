
<link rel="stylesheet" href="{{ asset('css/revisao.css') }}">
<div class="row paddingB40">
   
    <div class="col-md-12 col-sm-12">
        <form action="{{ route('update.revisao.imagem', encrypt($revisao->id)) }}" method="POST" enctype="multipart/form-data" id="form-revisao-edita">
            <div>
                <div>
                    <img id="imagem-pin" draggable="true" ondragstart="drag(event)" onclick="deletePoint(event)" src="{{ asset('images/pins/azul/1.png') }}" class="image-preview-filename pin">
                    <input type="hidden" name="revisao_id" value="{{$revisao->id}}">
                    <input type="hidden" name="revisao_nome" value="{{$revisao->nome}}">
                    <input type="hidden" name="imagem_id" value="{{$revisao->imagem_id}}">
                    
                </div>
                <div class="col-md-12">
                    <canvas id="canvas-revisao" class="canvas-detalhe canvas-revisao-imagem" ondrop="drop(event)" ondragover="allowDrop(event)" ></canvas>
                    <textarea id="pontos" rows="10" style="display: none;"></textarea>
                    @csrf
                              <img src="{{ URL::asset('storage/'.$revisao->src)}}" name="imagem_final" id="img_final" style="width: 50px; visibility: hidden;">

                </div>
                <div class="row" id="areaInput">

                    @foreach($revisao->marcadores as $index => $marc)
                        <div class="col-md-12" id="divPonto{{$index+1}}" data-pin="{{  count($marc->midias) == '0' ? '1' : count($marc->midias)}}">
                            <div class="row">
                                <div class="col-md-12">
                                    <img id="pin{{$index+1}}"  src="{{ asset('images/pins/azul/'. ($index+1). '.png')}}" class="image-preview-filename pin">
                                    <input type="hidden" name="pins[{{ $index+1 }}][marcador_id]" value="{{$marc->id}}">
                                </div>
                            </div>
                            <div class="row" id="RowPinX{{$index+1}}">
                                <div class="col-md-3">
                                    <div class="largura95 divTxtPonto">
                                        <textarea class="form-control image-preview-filename" required="true" name="pins[{{ $index+1 }}][texto]" id="txtPontoDesc{{ $marc->id }}" style="height:100px">{{ $marc->texto }}</textarea>
                                        <input type="hidden" name="xPin" id="xPin{{ $index }}" value="{{ $marc->x }}">
                                        <input type="hidden" name="yPin" id="yPin{{ $index }}" value="{{ $marc->y }}">

                                        <input type="hidden" name="pin_flag" id="pin-flag-{{$index+1}}" data-origin="banco"  value="banco" data-url="{{route('deletar.pin.revisao', encrypt($marc->id))}}">

                                        @foreach($marc->midias as  $indexMarc => $mid)
                                           

                                            <input type="hidden" name="" id="" value="{{ URL::asset('storage/'. $mid->src )}}" class="form-control image-preview-filename largura100"> 
                                            @if ($mid->caminho_arquivo)
                                                                              
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-md-3" id="colPinX{{$index+1}}">
                                    @forelse($marc->midias as  $indexMi => $mid)
                                        <div class="input-group image-preview margemB5" data-original-title="" title="">
                                            <span class="image-preview-span-num">{{$indexMi+1}}</span>
                                            <input class="form-control image-preview-filename largura90" 
                                                type="text" 
                                                placeholder="Nenhum arquivo selecionado" 
                                                id="arquivo-preview-{{$index+1}}-{{$indexMi+1}}" 
                                                name="caminho_arquivo[{{$index+1}}][]" 
                                                value="{{ $mid->caminho_arquivo }}" 
                                                data-url="{{route('deletar.midia.pin.revisao', 
                                                ['midId' => encrypt($mid->id), 'marcId' => encrypt($marc->id)])}}" 
                                                data-origin="banco">
                                            <span class="input-group-btn largura ">
                                                <div class="btn btn-default image-preview-input">
                                                    <span class="glyphicon glyphicon-folder-open"></span>
                                                    <span class="image-preview-input-title"> Procurar</span>
                                                    <input type="file" 
                                                        id="input-arquivo-{{$index+1}}-{{$indexMi+1}}" 
                                                        name="input-arquivo[{{$index+1}}][]" accept="*">
                                                </div>
                                                <button class="btn btn-default image-preview-clear" 
                                                        type="button" 
                                                        id="buttom_image_pin_clear-{{$index+1}}-{{$indexMi+1}}">
                                                    <span class="glyphicon glyphicon-remove"> Limpar</span>
                                                </button>
                                                @if ($indexMi<1)
                                                    <button type="button" class="btn btn-success" style="margin-left: 0px;" title="Adicionar Arquivo" id="bt-add-arquivo-{{$index+1}}-{{$indexMi+1}}"> + </button>
                                                @endif

                                                <script type="text/javascript">
                                                    ponto = "{!! $index+1 !!}";
                                                    pontoMidia = "{!! $indexMi+1 !!}";
                                                    addButton = false;
                                                    if(pontoMidia==1)
                                                    {
                                                        addButton = true;
                                                    }
                                                    //alert(ponto);
                                                    AddFilePin(ponto, pontoMidia, addButton);
                                                </script> 
                                                
                                            </span>
                                        </div>
                                    @empty
                                       <div class="input-group image-preview margemB5" data-original-title="" title="">
                                            <span class="image-preview-span-num">1</span>
                                            <input class="form-control image-preview-filename largura90" 
                                                type="text" 
                                                placeholder="{{ __('messages.Nenhum arquivo selecionado') }}" 
                                                id="arquivo-preview-{{$index+1}}-1" 
                                                name="caminho_arquivo[{{$index+1}}][]" 
                                                value="" 
                                                data-url="" 
                                                data-origin="front">
                                            <span class="input-group-btn largura ">
                                                <div class="btn btn-default image-preview-input">
                                                    <span class="glyphicon glyphicon-folder-open"></span>
                                                    <span class="image-preview-input-title"> Procurar</span>
                                                    <input type="file" 
                                                        id="input-arquivo-{{$index+1}}-1" 
                                                        name="input-arquivo[{{$index+1}}][]" accept="*">
                                                </div>
                                                <button class="btn btn-default image-preview-clear" 
                                                        type="button" 
                                                        id="buttom_image_pin_clear-{{$index+1}}-1">
                                                    <span class="glyphicon glyphicon-remove"> Limpar</span>
                                                </button>
                                                <button type="button" class="btn btn-success" style="margin-left: 0px;" title="{{ __('messages.Adicionar Arquivo') }}" id="bt-add-arquivo-{{$index+1}}-1"> + </button>

                                                <script type="text/javascript">
                                                    ponto = "{!! $index+1 !!}";
                                                    pontoMidia = "1";
                                                    addButton = false;
                                                    if(pontoMidia==1)
                                                    {
                                                        addButton = true;
                                                    }
                                                    //alert(ponto);
                                                    AddFilePin(ponto, pontoMidia, addButton);
                                                </script> 
                                                
                                            </span>
                                        </div> 
                                    @endforelse
                                </div>
                                <div class="col-md-6" id="colpreview{{$index+1}}"> 
                                    @foreach($marc->midias as  $indexMid => $mid)
                                    <div class="btn btn-default div-img-preview" id="div_img_preview{{$index+1}}-{{$indexMid+1}}">
                                        <span class="image-preview-span-num-title" id="img_preview-title{{$index+1}}-{{$indexMid+1}}">{{$indexMid+1}}</span>
                                        <img id="img_preview{{$index+1}}-{{$indexMid+1}}" src="{{ URL::asset('storage/'. $mid->src )}}" style="width:200px; height: 200px" class="image-preview-fixo">
                                    </div>   
                                    @endforeach                           
                                </div>
                             </div>  
                             <input type="hidden" id="hdnPontoX{{$index+1}}" name="pins[{{$index+1}}][x]" value="{{ $marc->x }}">
                             <input type="hidden" id="hdnPontoY{{$index+1}}" name="pins[{{$index+1}}][y]" value="{{ $marc->y }}">
                             <input type="hidden" id="hdnIdPonto{{$index+1}}" name="pins[{{$index+1}}][ponto]" value="{{$index+1}}">
                        </div>    
                    @endforeach
                         
               </div>
               <div class="row">
                    <div class="col-md-3"> <br>
                        <input type="hidden" name="tira_arquivos" id="tira-arquivos" value="0">

                        <input type="submit" name="salvar" value="{{ __('messages.Salvar Revisão') }}" class="btn btn-primary" />
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
    
    imgSectionEdita = document.getElementById("img_final");

 
</script>

