
<table id="lista-dashboard" class="table larguraTotal table-striped">
    <thead class="">
    <tr class="">
        <th colspan="" class="fundo-escuro texto-branco padding10">#</th>
        <th colspan="" class="fundo-escuro texto-branco padding10">{{ __('messages.Projeto')}}</th>
        <th colspan="" class="fundo-escuro texto-branco padding10">{{ __('messages.Descrição')}}</th>
        <th colspan="" class="fundo-escuro texto-branco padding10">{{ __('messages.Criação')}}</th>
        <th colspan="" class="fundo-escuro texto-branco padding10">{{ __('messages.Previsão')}}</th>
        <th colspan="" class="fundo-escuro texto-branco padding10"></th>
    </tr>
    </thead>
    <tbody class="fundo-branco">

    @foreach($projetos as $prop => $proj)

        <tr class="">
            <td class="desktop">#{{ $proj->id }}</td>
            <td>{{ $proj->nome  }}</td>
            <td class="desktop">{{ $proj->descricao }}</td>
            <td class="desktop">{{ $proj->created_at ? \Carbon\Carbon::parse($proj->created_at)->format('d.m.Y') : __('messages.Não Informado')}}</td>
            <td class="desktop">{{ $proj->data_previsao_entrega ? \Carbon\Carbon::parse($proj->created_at)->format('d.m.Y') : __('messages.Não Informado')}}</td>
            <td>
                <a href="{{ route('projetos.show', encrypt($proj->id)) }}" class="">{{ __('messages.Detalhes')}}</a>
            </td>
        </tr>

    @endforeach

    </tbody>
    <tfoot>

    </tfoot>
</table>