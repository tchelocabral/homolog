
<tr class="conteudo-arquivo">
    <td colspan="2">
    <p>{{ $midia->tipo }}</p>
    </td>
    <td colspan="3">
    <p>{{ $midia->nome }}</p>
    </td>
    <td>
        <form action="{{ route('midias.destroy') }}">
            <a id="remove-arquivo{{ $midia->id }}" href="javascript:void(0);" class="btn btn-danger remove-arquivo">
                <i class="fa fa-times"></i>
            </a>
        </form>
    </td>
</tr>