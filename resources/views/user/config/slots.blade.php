{{-- mostra seção com slots de jobs do Usuário
precisa do usuario, e se é pra montar o form --}}

<div class="row">
    <div class="col-12">
        <h4>{{ __('messages.Slots de Jobs do Usuário') }}</h4>
    </div>
    @if($monta_form)
        <div class="row">
            <form action="{{ route('user.add.slots') }}" method="post">
                @csrf
                <input type="hidden" name="user_id" value="{{ $user_id }}">
    @endif
    <div class="col-6">
        <p>{{ __('messages.Jobs Livres') }}</p>
        <p>{{ '$user->qtde_jobs_andamento' }}</p>
        @if($monta_form)
            <input type="number" name="qtde_jobs_andamento" id="qtde-jobs-andamento" value="">
        @endif
    </div>
    <div class="col-6">
        <p>{{ __('messages.Jobs de Candidatura') }}</p>
        <p>{{ '$user->qtde_jobs_candidatura' }}</p>
        @if($monta_form)
            <input type="number" name="qtde_jobs_candidatura" id="qtde-jobs-candidatura" value="">
        @endif
    </div>
    @if($monta_form)
        </form></div>
    @endif

</div>