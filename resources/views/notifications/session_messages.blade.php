
{{-- Devolve div de mensagens da Session --}}

{{--{{ dd(Session) }}--}}

<!-- SECTION MESSAGES -->
@if(session()->has('message.level'))
    <div class="session-message session-notification-efeito session-message-{{ session()->get('message.level') }} ">
        <div class="row">
            <div class="col-md-11">
              {!! session()->get('message.content') !!}  
            </div>
            <div class="col-md-1 semPadding">
                <span id="fechar-session-message"><i class="fa fa-close"></i></span>        
            </div>
        </div>
        @if(session()->has('message.erro'))
            <div class="row">
                <div class="col-md-12">
                    {!! session()->get('message.erro') !!}
                </div>
            </div>
        @endif
        <div class="progress" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
    </div>
@endif
<!-- SECTION MESSAGES -->


{{--Validation Messages--}}
@if (count($errors) > 0)
    <div class="session-message session-notification-efeito session-message-erro">
        <div class="row">
            @foreach ($errors->all() as $error)
                <div class="col-md-11">
                    <p class="texto-branco margemB5">- {{ $error }}</p>
                </div>
                @if($loop->first)
                    <div class="col-md-1 semPadding">
                        <span id="fechar-session-message"><i class="fa fa-close"></i></span>        
                    </div>
                @endif
            @endforeach
        </div>
        <div class="progress" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
    </div>
@endif
{{--Validation Messages--}}
