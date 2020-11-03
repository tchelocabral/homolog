@if($avaliar)
    <div class="avaliacao-overlay larguraTotal">
        <div id="div-avaliacao" class="avaliado-job">
            <form action="{{ route('add.avaliacao') }}" method="POST">
                @csrf
                <h3 class="texto-branco texto-centralizado">
                    @if($role=="publicador" || $role=="coordenador")
                        Avalie o Freelancer que fez o seu job
                        <input name="avaliado_id" value="{{ $job->delegado_para }}" type="hidden">
                    @elseif($role=="freelancer" || $role=="admin" || $role=="desenvolvedor" )
                        Avalie o Publicador do job que você fez
                        <input name="avaliado_id" value="{{ $job->publicador_id }}" type="hidden">
                    @endif
                    <input name="avaliador_id" value="{{ $usuario_ativo->id }}" type="hidden">
                </h3>
                <input id="input-21b" value="0" type="text" name="nota" class="rating" data-min=0 data-max=5 data-step=0.5 data-size="{{$tamanho ?? 'md'}}"
                required title="">
                <div class="clearfix"></div>
                <hr>
                <h4 class="texto-branco">Observações</h4>
                
                <input name="type" value="{{ strtolower (class_basename($job)) }}" type="hidden">
                <input name="job_id" value="{{ encrypt($job->id) }}" type="hidden">
                <textarea id="observacoes" name="observacoes" class="form-control" style="resize:none;"></textarea><br>
                <button id="publicar-job" class="btn btn-success pull-right " value="" name="btn_avaliar">{{ __('messages.Confirmar nota') }}</button>
            </form>
        </div>
    </div>
@else
    @if($media)
        <div class="avaliado-job sem-padding-left margemR10">
            <input id="input-4" name="input-4" value="{{$media}}" readonly class="rating rating-loading" data-show-clear="false" data-show-caption="false"  data-size="{{$tamanho ?? 'sm'}}">

        </div>
    @else
        @foreach($avaliacoes  as $index => $ava)
            <div class="avaliado-job sem-padding-left margemR10">
                <input id="input-21b" value="{{$ava->nota}}" type="text" class="rating"  data-show-caption="false" data-show-clear="false"   data-size="{{$tamanho ?? 'sm'}}"
                title="" readonly>
                <div class="clearfix"></div>
                {{-- comentado pois só o publicador avalia hoje --}}
                {{-- <i>Por: {{$ava->avaliador->name}} ({{ucfirst($ava->avaliador->roles->first()->name)}})</i> --}}
            </div>
        @endforeach
    @endif
@endif