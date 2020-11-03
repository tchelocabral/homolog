
{{--{{ $revisoes }}--}}

<div class="row largura90 centralizado paddingB40">
    <h1 class="margemB40">__{{('messages.Previews')}}</h1>

    <div class="col-md-12">
        
        @can('atualiza-revisao')
            @if($pode_add_revisao)
                <div class="btn-toolbar margemT10 btn-lista-novo" role="toolbar">
                    <span id="add-revisao" class="btn btn-success no-border" data-url="{{ route('nova.revisao.imagem', encrypt($imagem->id)) }}" title="{{ __('messages.Criar l') }}" data-toggle="tooltip">
                        <i class="fa fa-plus margemR5" aria-hidden="true"></i>  {{__('messages.Nova Revisão')}}
                    </span>
                </div>
            @endif
        @endcan 


        @forelse($imagem->revisoes as $rev)
            <div class="card card-revisao margemT20">
                <div class="row">
                {{-- <div class="col-md-1">{{$rev->numero_revisao}}</div>--}}
                    <div class="col-md-2">{{$rev->nome}}</div>
                    <div class="col-md-5">{{\Carbon\Carbon::parse($rev->created_at)->format('d/m/Y')}}</div>
                    <div class="col-md-2">
                        <div class="progress cor-personalizada" style="background: #fff;">
                            <div class="progress-bar progress-bar-animated progress-bar-striped progress-bar-info progresso " role="progressbar" aria-valuenow="{{ '0' }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    <div class="col-md-1"><span class="link visualiza-revisao" data-url="{{ route('visualizar.revisao.imagem', encrypt($rev->id)) }}" data-title="{{ __('messages.Revisão de Preview') . ' ' . $rev->nome}}" >{{__('messages.Visualizar')}}</span></div>
                    @if(Auth::user()->hasAnyRole(['admin', 'desenvolvedor', 'coordenador']))
                    <div class="col-md-1"><span class="link edita-revisao" data-url="{{ route('editar.revisao.imagem', encrypt($rev->id)) }}" data-title="{{ __('messages.Revisão de Preview') . ' ' . $rev->nome}}" >{{__('messages.Editar')}}</span></div>
                    @endif

                    <div class="col-md-1">
                        @can('deleta-revisao')
                        <form action="{{ route('excluir.revisao.imagem', ['imagem_id'=> encrypt($imagem_id), 'revisao_id'=>encrypt($rev->id)]) }}" class="form-delete" id="form-deletar-revisao-{{ $rev->id }}" name="form-deletar-revisao-{{ $rev->id }}" method="POST" enctype="multipart/form-data">
                            @method('DELETE')
                            @csrf
                            <a href="#" class="btn btn-danger deletar-item margemL5" title="{{ __('messages.Excluir Revisão') }}" data-toggle="tooltip" type="submit">
                                <i class="fa fa-close" aria-hidden="true"></i>
                            </a>
                        </form>
                        @endcan
                    </div>
                </div>
            </div>
        @empty
            <h4 class="margemT30">{{ __('messages.Não existem Revisões para esta Imagem') }}</h4>
        @endforelse

    </div>
</div>



@push('js')
<script src="{{ asset('js/revisao.js') }}"></script>
    <script>

        $(document).ready(function(){

            // Cria aba de nova revisão
            // function novaRevisao(e) {

            //     // pegar total de abas
            //     var tab_id   = jQuery(".tab-revisao").length;
            //     if(tab_id > 0){
            //         erro_info('Oops', 'Já existe uma aba de Revisão aberta.');
            //     }else {
            //         // Pega a rota padrão do endpoint com os dados do tipo de job
            //         var url = "{{ route('nova.revisao.imagem', encrypt($imagem->id)) }}";
            //         // Chamada Ajax
            //         $.ajax({
            //             url: url,
            //             type: 'GET',
            //             // antes de chamar o ajax
            //             beforeSend: function (xhr) {
            //             },
            //             // se a requisicao for bem-sucedida
            //             success: function (data) {
            //                 if (data.length === 0) {

            //                 } else {
            //                     console.log(data);
            //                     //Monta aba
            //                     //para fechar a aba <span> x </span>
            //                     var cont_aba = '<div id="nova-revisao-' + tab_id + '" class="tab-pane fade">' + data + '</div>';
            //                     var aba =
            //                         '<li>' +
            //                             '<a ' +
            //                                 'data-toggle="tab" ' +
            //                                 'href="#nova-revisao-' + tab_id + '" ' +
            //                                 'class="tab-revisao nav-link animated demo verde">' +
            //                                 'Nova Revisão <span class="pull-right margemL20 mouse-pointer"> x </span>' +
            //                             '</a> ' +
            //                         '</li>';

            //                     // pegar o container de abas
            //                     jQuery('#tabs-imagem ul li:last-child').after(aba);
            //                     jQuery('#tabs-imagem .tab-content').append(cont_aba);
            //                     jQuery('#tabs-imagem ul li:last-child a').tab('show');
            //                     jQuery('#tabs-imagem ul li:last-child a span').on("click", function () {
            //                         var anchor = $(this).siblings('a');
            //                         $(anchor.attr('href')).remove();
            //                         $(this).parent().remove();
            //                         $(".nav-tabs li").children('a').first().click();
            //                     });
            //                 }
            //             },
            //             // se der erro
            //             error: function (data) {
            //                 console.log(data)
            //             },
            //             // quando estiver tudo completo
            //             complete: function () {
            //             }
            //         });
            //     }
            // }
            // $('#add-revisao').on('click', function (e) {
            //     novaRevisao(e);
            // });



            // function visualizaRevisao(e) {

            //     // pegar total de abas
            //     var tab_id   = jQuery(".tab-revisao").length;
            //     if(tab_id > 0){
            //         erro_info('Oops', 'Já existe uma aba de Revisão aberta.');
            //     }else {
            //         // Pega a rota padrão do endpoint com os dados do tipo de job
            //         var url = e.currentTarget.attributes["data-url"].value;
            //         // Chamada Ajax
            //         $.ajax({
            //             url: url,
            //             type: 'GET',
            //             // antes de chamar o ajax
            //             beforeSend: function (xhr) {
            //             },
            //             // se a requisicao for bem-sucedida
            //             success: function (data) {
            //                 if (data.length === 0) {

            //                 } else {
            //                     console.log(data);
            //                     //Monta aba
            //                     //para fechar a aba <span> x </span>
            //                     var title = e.currentTarget.attributes["data-title"].value;

            //                     var cont_aba = '<div id="visualiza-revisao-' + tab_id + '" class="tab-pane fade">' + data + '</div>';
            //                     var aba =
            //                         '<li>' +
            //                             '<a ' +
            //                                 'data-toggle="tab" ' +
            //                                 'href="#visualiza-revisao-' + tab_id + '" ' +
            //                                 'class="tab-revisao nav-link animated demo verde">' +
            //                                 ' Revisão ' + title + ' <span class="pull-right margemL20 mouse-pointer"> x </span>' +
            //                             '</a> ' +
            //                         '</li>';

            //                     // pegar o container de abas
            //                     jQuery('#tabs-imagem ul li:last-child').after(aba);
            //                     jQuery('#tabs-imagem .tab-content').append(cont_aba);
            //                     jQuery('#tabs-imagem ul li:last-child a').tab('show');
            //                     jQuery('#tabs-imagem ul li:last-child a span').on("click", function () {
            //                         var anchor = $(this).siblings('a');
            //                         $(anchor.attr('href')).remove();
            //                         $(this).parent().remove();
            //                         $('#visualiza-revisao-' + tab_id).remove();
            //                         $(".nav-tabs li").children('a').last().click();
            //                     });
            //                 }
            //             },
            //             // se der erro
            //             error: function (data) {
            //                 console.log(data)
            //             },
            //             // quando estiver tudo completo
            //             complete: function () {
            //             }
            //         });
            //     }
            // }
            // $('.visualiza-revisao').on('click', function (e) {
            //     visualizaRevisao(e);
            // });



            // function editaRevisao(e) {

            //     // pegar total de abas
            //     var tab_id   = jQuery(".tab-revisao").length;
            //     if(tab_id > 0){
            //         erro_info('Oops', 'Já existe uma aba de Revisão aberta.');
            //     }else {
            //         // Pega a rota padrão do endpoint com os dados do tipo de job
            //         var url = e.currentTarget.attributes["data-url"].value;
            //         // Chamada Ajax
            //         $.ajax({
            //             url: url,
            //             type: 'GET',
            //             // antes de chamar o ajax
            //             beforeSend: function (xhr) {
            //             },
            //             // se a requisicao for bem-sucedida
            //             success: function (data) {
            //                 if (data.length === 0) {

            //                 } else {
            //                     console.log(data);
            //                     //Monta aba
            //                     //para fechar a aba <span> x </span>
            //                     var title = e.currentTarget.attributes["data-title"].value;

            //                     var cont_aba = '<div id="edita-revisao-' + tab_id + '" class="tab-pane fade">' + data + '</div>';
            //                     var aba =
            //                         '<li>' +
            //                             '<a ' +
            //                                 'data-toggle="tab" ' +
            //                                 'href="#edita-revisao-' + tab_id + '" ' +
            //                                 'class="tab-revisao nav-link animated demo verde">' +
            //                                 ' Edita Revisão ' + title + ' <span class="pull-right margemL20 mouse-pointer"> x </span>' +
            //                             '</a> ' +
            //                         '</li>';

            //                     // pegar o container de abas
            //                     jQuery('#tabs-imagem ul li:last-child').after(aba);
            //                     jQuery('#tabs-imagem .tab-content').append(cont_aba);
            //                     jQuery('#tabs-imagem ul li:last-child a').tab('show');
            //                     jQuery('#tabs-imagem ul li:last-child a span').on("click", function () {
            //                         var anchor = $(this).siblings('a');
            //                         $(anchor.attr('href')).remove();
            //                         $(this).parent().remove();
            //                         $('#edita-revisao-' + tab_id).remove();
            //                         $(".nav-tabs li").children('a').last().click();
            //                     });
            //                 }
            //             },
            //             // se der erro
            //             error: function (data) {
            //                 console.log(data)
            //             },
            //             // quando estiver tudo completo
            //             complete: function () {
            //             }
            //         });
            //     }
            // }
            // $('.edita-revisao').on('click', function (e) {
            //     editaRevisao(e);
            // });

        });
    </script>
@endpush