<link rel="stylesheet" href="{{ asset('css/revisao.css') }}">
<div class="row paddingB40">
    <div class="col-md-12 col-sm-12">
        <div>
            <div>
                <img id="imagem_pin_visual"  src="{{ asset('images/pins/azul/1.png') }}" style="visibility: hidden;">
                <span><img id="imagem-pin" style="visibility: hidden;" ></span><br>
               <!--  <input type="text" name="pontoAtual" value="0" id="pontoAtual" > -->
            </div>
            <div> 
                <div class="col-md-12">
                <canvas id="canvas-revisao" class="canvas-detalhe canvas-revisao-imagem"></canvas>

                @csrf
               {{-- <input type="hidden" name="img_final" id="img-final"  value="{{URL::asset($revisao->src)}}">    --}}

              
                {{-- @if(URL::to('/')=="http://fullfreela.local")  --}}
                     {{-- <img src="{{asset('images/casao.jpg')}}" name="img_final-visual" id="img_final" style="width: 50px; visibility: hidden;"> --}}
                     {{-- <img src="{{asset('storage/'.$revisao->src)}}" name="img_final-visual" id="img_final" style="width: 50px; visibility: hidden;"> --}}
                {{-- @else --}}
                    <img src="{{ URL::asset('storage/'.$revisao->src)}}" name="img_final-visual" id="img_final" style="width: 50px; visibility: hidden;">
                {{-- @endif --}}

            </div>
            <div>
                <hr>
                @foreach($revisao->marcadores  as  $indexMid => $marcRev)
                    <div class="col-md-3">
                        <span><img id="{{ 'imagem-pin'.$marcRev->ordem}}"  src="{{asset('images/pins/azul/'. ($indexMid+1). '.png')}}" ></span><br>
                        <span>{{ $marcRev->texto }}</span><br>
                        
                        <input type="hidden" name="xPin" id="xPin{{ $marcRev->id }}" value="{{ $marcRev->x }}">
                        <input type="hidden" name="yPin" id="yPin{{ $marcRev->id }}" value="{{ $marcRev->y }}">

                        @if(!$revisao->tira_arquivos)
                        @foreach($marcRev->midias as $mid)
                            <img src="{{ URL::asset('storage/'. $mid->src )}}" class="img-detalhe-min">
                            @if ($mid->caminho_arquivo)
                            <p>
                                {{ __('messages.Caminho arquivo') }}: {{ $mid->caminho_arquivo }}
                            </p>
                            @endif
                        @endforeach
                        @endif
                    </div>    
                @endforeach
            </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

       imgSection = document.getElementById("img_final"); 

</script>