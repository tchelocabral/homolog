@component('mail::message')

{{ __('notif.Olá') }} <b>{{ $userNotif->destinatario->name }}, <b>

<b>{{ $userNotif->mensagem['msg'] }}</b>


{{-- Action Button --}}
@isset($actionText)
@component('mail::button', ['url' => $actionUrl, 'color' => 'green'])
{{ __('notif.Veja mais aqui') }}
@endcomponent
@endisset

<i style="margin-top:35px;" align="center">*{{ __('notif.não responda esse e-mail') }}! </i>
<hr>

{{-- <b> <a href="https://www.facebook.com/fullfreela"> Facebook </a> | 
    <a href="https://www.twitter.com/fullfreela"> Twitter </a>| 
    <a href="https://www.instagram.com/fullfreela"> Instagram </a>| 
    <a href="https://www.youtube.com.br/fullfreela"> Youtube </a>| 
    <a href="https://www.linkedin.com/fullfreela"> Linkedin </a></b> --}}
    
{{-- Outro Lines --}}
@foreach ($outroLines as $line)
{{ $line }}

@endforeach

{{-- Salutation --}}
@if (! empty($salutation))
{{ $salutation }}
@else
{{ __('notif.Enviado por') }} <a href="https://www.fullfreela.com"><b>{{ config('app.name') }}</b></a>
@endif

{{-- Subcopy --}}
@isset($actionText)
@component('mail::subcopy')
{{ __('notif.Se você estiver com problemas para clicar no botão') . ' "' . $actionText . '", ' . __('notif.copie e cole o URL abaixo no seu navegador') . ':'}}
{{-- @lang(
    "Se você estiver com problemas para clicar no botão \":actionText\", copie e cole o URL abaixo\n".
    'no seu navegador: ',
    [
        'actionText' => $actionText
    ]
) --}}
[{{ $actionUrl }}]({!! $actionUrl !!})
@endcomponent
@endisset


@endcomponent

