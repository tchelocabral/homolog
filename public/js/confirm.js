/**
 * @CriaçãoCia
 * para funções específicas de confirmação de diálogos
 */
(function(){

    // Exemplo de Confirm com ação AJAX
    jQuery('.deletar-item-ajax').click(function(){
        var item_url    = jQuery(this).data('item-url');
        var item_id     = jQuery(this).data('item-id');

        $.ajaxSetup({
           headers: {
               'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') // Set token antes de deletar
           }
        });

        $.confirm({
            icon: 'fa fa-warning',
            title: 'Confirma a exclusão?',
            content: '' +
            '<form action="" class="form-delete-item">' +
                '<div class="form-group">' +
                    '<h4 class="texto-centralizado margemT20">Digite DELETAR para confirmar:</h4>' +
                    '<input type="text" placeholder="" class="deletar form-control texto-centralizado" required autofocus />' +
                '</div>' +
            '</form>',
            backgroundDismiss: true,
            closeIcon: true,
            type: 'red',
            boxWidth: '30%',
            autoClose: 'cancel|30000',
            useBootstrap: false,
            cancelButton: 'Não, nunca!',
            buttons: {
                remover: {
                    text: 'Sim, excluir!',
                    btnClass: 'btn-red',
                    action: function(){
                        var deletar = this.$content.find('.deletar').val();
                        if(!deletar || deletar !== 'DELETAR'){
                            erro_info('Oopss', 'Texto de confimação incorreto!');
                            return false;
                        }
                        $.ajax({
                            type: 'DELETE',
                            url: item_url,
                            success: function (data) {
                                console.log('Sucesso: ' + data);
                                jQuery('#'+item_id).remove();

                            },
                            error: function (data) {
                                console.log('Erro: ', data);
                            }
                        });
                        // document.getElementById("formRecusarDoacao" + item_ID).submit();
                    }
                },
                cancelar: function(){
                    // return false;
                }
            }
        });
    });

    // Exemplo de Confirm com submit de form
    jQuery('.deletar-item').click(function(e){
        e.preventDefault();
        deletar_item(this);
    });

    
    // Modal para alterar dados de um tipo para outro
    jQuery('.transferir-item-usuario').click(function(e){
        e.preventDefault();
        transferir_dados(this);
    });


    // Modal para alterar dados de um tipo para outro
    jQuery('.deletar-item-tipo-job').click(function(e){
        e.preventDefault();
        transferir_tipo(this);
    });


    // Exemplo de Confirm com submit de form
    jQuery('.desvincular').click(function(e){
        e.preventDefault();

        var item_id     = jQuery(this).data('item-id');

        $.confirm({
            icon: 'fa fa-warning',
            title: 'Confirma?',
            content: '' +
            '<form action="" class="form-delete-item">' +
                '<div class="form-group">' +
                    '<h4 class="texto-centralizado margemT20">Digite DESVINCULAR para confirmar:</h4>' +
                    '<input type="text" placeholder="" class="desvincular form-control texto-centralizado" required autofocus />' +
                '</div>' +
            '</form>',
            backgroundDismiss: true,
            closeIcon: true,
            type: 'red',
            boxWidth: '30%',
            autoClose: 'cancel|30000',
            // useBootstrap: false,
            // cancelButton: 'Não, nunca!',
            buttons: {
                remover: {
                    text: 'Sim, excluir!',
                    btnClass: 'btn-red',
                    action: function(){
                        var desvincular = this.$content.find('.desvincular').val();
                        if(!desvincular || desvincular !== 'DESVINCULAR'){
                            erro_info('Oopss', 'Texto de confimação incorreto!');
                            return false;
                        }
                        $(e.target).parent().find('.desvincular-hidden')[0].click();
                        // $(e.target).closest('.form-desvincular').click();
                    }
                },
                cancelar:{
                    text: 'Não, nunca!',
                    action: function(){
                        // return false;
                    }
                }
            }
        });
    });


    // Confirm: preenche o motivo da causa da recusa do job
    jQuery('.recusar-item').click(function(e) {
        e.preventDefault();

        var item_id     = jQuery(this).data('item-id');

        $.confirm({
            icon: 'fa fa-warning',
            title: 'Confirma a causa da recusa?',
            content: '' +
                '<form action="" class="form-recusa-item">' +
                    '<div class="form-group">' +
                        '<h4 class="texto-centralizado margemT20">Digite a CAUSA da recusa do Job:</h4>' +
                        '<input type="text" name="causa" placeholder="" class="deletar form-control texto-centralizado" required autofocus />' +
                    '</div>' +
                '</form>',
            backgroundDismiss: true,
            closeIcon: true,
            type: 'red',
            boxWidth: '30%',
            autoClose: 'cancel|30000',
            // useBootstrap: false,
            // cancelButton: 'Não, nunca!',
            buttons: {
                remover: {
                    text: 'Sim, recusar!',
                    btnClass: 'btn-red',
                    action: function(){
                        var deletar = this.$content.find('.deletar').val();
                        if(!deletar || deletar === ''){
                            erro_info('Oopss', 'Causa da recusa do Job precisa ser preenchido!');
                            return false;
                        }
                        $('#form-recusado-tipo-img').append('<input type="hidden" name="causa" value="'+deletar+'" />');

                        $(e.target).closest('.form-recusado').submit();
                    }
                },
                cancelar:{
                    text: 'Não, nunca!',
                    action: function(){
                        // return false;
                    }
                }
            }
        });
    });   

    // Confirm: aumenta quantidade de slots de jobs para um usuario
    jQuery('.aumentar-slots').click(function(e) {
        e.preventDefault();

        var item_id     = jQuery(this).data('item-id');
        var titulo      = jQuery(this).data('title');
        var mensagem    = jQuery(this).data('msg');
        var botao_sim   = jQuery(this).data('botao-sim');
        var botao_nao   = jQuery(this).data('botao-nao');
        $.confirm({
            icon: 'fa fa-warning',
            title: titulo,
            content: '' +
                '<form action="" class="form-recusa-item">' +
                    '<div class="form-group">' +
                        '<h4 class="texto-centralizado margemT20">'+mensagem+':</h4>' +
                        '<input type="text" name="qtde_slot_jobs" placeholder="" class="deletar form-control texto-centralizado" required autofocus />' +
                    '</div>' +
                '</form>',
            backgroundDismiss: true,
            closeIcon: true,
            type: 'blue',
            boxWidth: '30%',
            autoClose: 'cancel|30000',
            // useBootstrap: false,
            // cancelButton: 'Não, nunca!',
            buttons: {
                remover: {
                    text: botao_sim,
                    btnClass: 'btn-blue',
                    action: function(){
                        var deletar = this.$content.find('.deletar').val();
                        if(!deletar || deletar === ''){
                            erro_info('Oopss', 'Por favor, digite um valor!');
                            return false;
                        }
                        $('#form-add-slot-user').append('<input type="hidden" name="slots" value="'+deletar+'" />');

                        $(e.target).closest('.form-add-slot').submit();
                    }
                },
                cancelar:{
                    text: botao_nao,
                    action: function(){
                        // return false;
                    }
                }
            }
        });
    });   

    // Confirm: aumenta quantidade de slots de jobs para um usuario
    jQuery('.aumentar-slot-candidatura').click(function(e) {
        e.preventDefault();

        var item_id     = jQuery(this).data('item-id');
        var titulo      = jQuery(this).data('title');
        var mensagem    = jQuery(this).data('msg');
        var botao_sim   = jQuery(this).data('botao-sim');
        var botao_nao   = jQuery(this).data('botao-nao');

        $.confirm({
            icon: 'fa fa-warning',
            title: titulo,
            content: '' +
                '<form action="" class="form-recusa-item">' +
                    '<div class="form-group">' +
                        '<h4 class="texto-centralizado margemT20">'+mensagem+':</h4>' +
                        '<input type="text" name="qtde_slot_jobs" placeholder="" class="deletar form-control texto-centralizado" required autofocus />' +
                    '</div>' +
                '</form>',
            backgroundDismiss: true,
            closeIcon: true,
            type: 'blue',
            boxWidth: '30%',
            autoClose: 'cancel|30000',
            // useBootstrap: false,
            // cancelButton: 'Não, nunca!',
            buttons: {
                remover: {
                    text: botao_sim,
                    btnClass: 'btn-blue',
                    action: function(){
                        var deletar = this.$content.find('.deletar').val();
                        if(!deletar || deletar === ''){
                            erro_info('Oopss', 'Por favor, digite um valor!');
                            return false;
                        }
                        $('#form-add-slot-candidatura-user').append('<input type="hidden" name="slots" value="'+deletar+'" />');

                        $(e.target).closest('.form-add-slot-candidatura').submit();
                    }
                },
                cancelar:{
                    text: botao_nao,
                    action: function(){
                        // return false;
                    }
                }
            }
        });
    });   
    //aumentar slot
    

    // Confirm: prorrogar data entrega
    jQuery('.prorrogar-prazo-item').click(function(e){
        e.preventDefault();

        var item_id     = jQuery(this).data('item-id');
        var data_atual =  document.getElementById("data-atual-entrega").value;

        $.confirm({
            icon: 'fa fa-warning',
            title: 'Informe a nova data?',
            content: '' +
                '<form action="" class="form-prorroga-item">' +
                    '<div class="form-group">' +
                        '<h4 class="texto-centralizado margemT20">Digite a nova data de entrega do Job:</h4>' +
                        '<input type="date" name="causa" placeholder="" class="prorrogar form-control texto-centralizado" min="' + data_atual + '" required autofocus />' +
                    '</div>' +
                '</form>',
            backgroundDismiss: true,
            closeIcon: true,
            type: 'red',
            boxWidth: '30%',
            autoClose: 'cancel|30000',
            // useBootstrap: false,
            // cancelButton: 'Não, nunca!',
            buttons: {
                remover: {
                    text: 'Sim, Alterar!',
                    btnClass: 'btn-red',
                    action: function(){
                        var prorrogar = this.$content.find('.prorrogar').val();
                        //26/10/2020 - prorrogar para qualquer data igual ou maior data atual
                        if(!prorrogar || prorrogar === '' || prorrogar < data_atual){
                            erro_info('Oopss', 'Escolha uma nova data!');
                            return false;
                        }
                        $('#form-prorrogar-tipo-img').append('<input type="hidden" name="nova_data" value="'+prorrogar+'" />');

                        $(e.target).closest('.form-prorrogar').submit();
                    }
                },
                cancelar:{
                    text: 'Cancelar!',
                    action: function(){
                        // return false;
                    }
                }
            }
        });
    });  

    
    // // Confirm: prorrogar data entrega
    // jQuery('.prorrogar-prazo-item').click(function(e){
    //     e.preventDefault();

    //     var item_id     = jQuery(this).data('item-id');
    //     var data_atual =  document.getElementById("data-atual-entrega").value;

    //     $.confirm({
    //         icon: 'fa fa-warning',
    //         title: 'Informe a nova data?',
    //         content: '' +
    //             '<form action="" class="form-prorroga-item">' +
    //                 '<div class="form-group">' +
    //                     '<h4 class="texto-centralizado margemT20">Digite a nova data de entrega do Job:</h4>' +
    //                     '<input type="date" name="causa" placeholder="" class="prorrogar form-control texto-centralizado" min="' + data_atual + '" required autofocus />' +
    //                 '</div>' +
    //             '</form>',
    //         backgroundDismiss: true,
    //         closeIcon: true,
    //         type: 'red',
    //         boxWidth: '30%',
    //         autoClose: 'cancel|30000',
    //         // useBootstrap: false,
    //         // cancelButton: 'Não, nunca!',
    //         buttons: {
    //             remover: {
    //                 text: 'Sim, Alterar!',
    //                 btnClass: 'btn-red',
    //                 action: function(){
    //                     var prorrogar = this.$content.find('.prorrogar').val();
    //                     if(!prorrogar || prorrogar === '' || prorrogar <= data_atual){
    //                         erro_info('Oopss', 'Escolha uma nova data!');
    //                         return false;
    //                     }
    //                     $('#form-prorrogar-tipo-img').append('<input type="hidden" name="nova_data" value="'+prorrogar+'" />');

    //                     $(e.target).closest('.form-prorrogar').submit();
    //                 }
    //             },
    //             cancelar:{
    //                 text: 'Cancelar!',
    //                 action: function(){
    //                     // return false;
    //                 }
    //             }
    //         }
    //     });
    // });  
    

    // Confirm: prorrogar data para propostas
    jQuery('.prorrogar-prazo-proposta-item').click(function(e){
        e.preventDefault();

        var item_id     = jQuery(this).data('item-id');
        var data_atual =  document.getElementById("data-atual-proposta").value;

        $.confirm({
            icon: 'fa fa-warning',
            title: 'Informe a nova data?',
            content: '' +
                '<form action="" class="form-prorroga-item">' +
                    '<div class="form-group">' +
                        '<h4 class="texto-centralizado margemT20">Digite a nova data aceitar propostas para o Job:</h4>' +
                        '<input type="date" name="causa" placeholder="" class="prorrogar form-control texto-centralizado" min="' + data_atual + '" required autofocus />' +
                    '</div>' +
                '</form>',
            backgroundDismiss: true,
            closeIcon: true,
            type: 'red',
            boxWidth: '30%',
            autoClose: 'cancel|30000',
            // useBootstrap: false,
            // cancelButton: 'Não, nunca!',
            buttons: {
                remover: {
                    text: 'Sim, Alterar!',
                    btnClass: 'btn-red',
                    action: function(){
                        var prorrogar = this.$content.find('.prorrogar').val();
                        if(!prorrogar || prorrogar === '' || prorrogar <= data_atual){
                            erro_info('Oopss', 'Escolha uma nova data!');
                            return false;
                        }
                        $('#form-prorrogar-proposta').append('<input type="hidden" name="nova_data" value="'+prorrogar+'" />');

                        $(e.target).closest('.form-prorrogar-poposta').submit();
                    }
                },
                cancelar:{
                    text: 'Cancelar!',
                    action: function(){
                        // return false;
                    }
                }
            }
        });
    }); 


    // Confirm: preenche o motivo da causa da recusa do job
    jQuery('.parar-item').click(function(e){
        e.preventDefault();

        var item_id     = jQuery(this).data('item-id');

        $.confirm({
            icon: 'fa fa-warning',
            title: 'Confirma o motivo de parar esse Job?',
            content: '' +
                '<form action="" class="form-recusa-item">' +
                    '<div class="form-group">' +
                        '<h4 class="texto-centralizado margemT20">Digite o MOTIVO de parar esse Job:</h4>' +
                        '<input type="text" name="motivo" placeholder="" class="deletar form-control texto-centralizado" required autofocus />' +
                    '</div>' +
                '</form>',
            backgroundDismiss: true,
            closeIcon: true,
            type: 'red',
            boxWidth: '30%',
            autoClose: 'cancel|30000',
            // useBootstrap: false,
            // cancelButton: 'Não, nunca!',
            buttons: {
                remover: {
                    text: 'Sim, parar!',
                    btnClass: 'btn-red',
                    action: function(){
                        var deletar = this.$content.find('.deletar').val();
                        if(!deletar || deletar === ''){
                            erro_info('Oopss', 'Motivo para  parar o Job precisa ser preenchido!');
                            return false;
                        }
                        $('#form-parado-tipo-img').append('<input type="hidden" name="motivo" value="'+deletar+'" />');

                        $(e.target).closest('.form-parado').submit();
                    }
                },
                cancelar:{
                    text: 'Não, nunca!',
                    action: function(){
                        // return false;
                    }
                }
            }
        });
    });


    // Confirm: confirmar-acao-item
    jQuery('.confirmar-acao-item').click(function(e) {
        e.preventDefault();

        var item_id     = jQuery(this).data('item-id');
        var titulo =  jQuery(this).data('title');
        var mensagem = '';
        var form = jQuery(e.target).closest('form');
        confirmar_acao_form(titulo, mensagem, form);
       
    });   


  // Confirm: confirmar-hr-item - solcitar hr
    jQuery('.solicite-hr-item').click(function(e) {
        e.preventDefault();

        var item_id     = jQuery(this).data('item-id');
        var titulo =  jQuery(this).data('title');
        var mensagem = jQuery(this).data('mensagem');
        var form = jQuery(e.target).closest('form');
        confirmar_acao_form(titulo, mensagem, form);
       
    });   
    

    // Confirma conclusão do job com pergunta
    jQuery('.concluir-item').click(function(e) {
        e.preventDefault();

        var item_id     = jQuery(this).data('item-id');

        $.confirm({
            icon: 'fa fa-success',
            title: 'Confirma a conclusão do job?',
            content: '' +
                '<form action="" class="form-concluir-item">' +
                    '<div class="form-group">' +
                        '<h4 class="texto-centralizado margemT20">Digite a CONCLUIR para confirmar:</h4>' +
                        '<input type="text" name="causa" placeholder="" class="concluir form-control texto-centralizado" required autofocus />' +
                        '<i>Confirmando a ação o pagamento será liberado parao Freelancer</id>' + 
                    '</div>' +
                '</form>',
            backgroundDismiss: true,
            closeIcon: true,
            type: 'green',
            boxWidth: '30%',
            autoClose: 'cancel|30000',
            // useBootstrap: false,
            // cancelButton: 'Não, nunca!',
            buttons: {
                remover: {
                    text: 'Sim, concluir!',
                    btnClass: 'btn-green',
                    action: function(){
                        var concluir = this.$content.find('.concluir').val();
                        if(!concluir || concluir === '' || concluir != 'CONCLUIR' ){
                            erro_info('Oopss', 'Confirme a conclusão do job!');
                            return false;
                        }
                        $('#form-concluido-tipo-img').append('<input type="hidden" name="confirmar" value="'+concluir+'" />');

                        $(e.target).closest('.form-concluido').submit();
                    }
                },
                cancelar:{
                    text: 'Não!',
                    action: function(){
                        // return false;
                    }
                }
            }
        });
    });


    // Confirma conclusão do job com OK
    jQuery('.concluir-item-ok').click(function(e) {
        e.preventDefault();

        var item_id     = jQuery(this).data('item-id');

        $.confirm({
            icon: 'fa fa-success',
            title: 'Confirma a conclusão do job?',
            content: '' +
                '<form action="" class="form-concluir-item">' +
                    '<div class="form-group">' +
                    '</div>' +
                '</form>',
            backgroundDismiss: true,
            closeIcon: true,
            type: 'green',
            boxWidth: '30%',
            autoClose: 'cancel|30000',
            // useBootstrap: false,
            // cancelButton: 'Não, nunca!',
            buttons: {
                remover: {
                    text: 'Sim, concluir!',
                    btnClass: 'btn-green',
                    action: function(){
                        var concluir = this.$content.find('.concluir').val();
                        $('#form-concluido-tipo-img').append('<input type="hidden" name="confirmar" value="'+concluir+'" />');

                        $(e.target).closest('.form-concluido').submit();
                    }
                },
                cancelar:{
                    text: 'Não!',
                    action: function(){
                        // return false;
                    }
                }
            }
        });
    });



    // Encerrar a conta
    jQuery('.encerrar-item').click(function(e) {
        e.preventDefault();

        var item_id     = jQuery(this).data('item-id');

        $.confirm({
            icon: 'fa fa-success',
            title: 'Confirma a solicitação para encerrar a conta?',
            content: '' +
                '<form action="" class="form-encerrar-item">' +
                    '<div class="form-group">' +
                        '<h4 class="texto-centralizado margemT20">Digite a ENCERRAR para confirmar:</h4>' +
                        '<input type="text" name="causa" placeholder="" class="encerrar form-control texto-centralizado" required autofocus />' +
                    '</div>' +
                '</form>',
            backgroundDismiss: true,
            closeIcon: true,
            type: 'red',
            boxWidth: '30%',
            autoClose: 'cancel|30000',
            // useBootstrap: false,
            // cancelButton: 'Não, nunca!',
            buttons: {
                remover: {
                    text: 'Sim, encerrar!',
                    btnClass: 'btn-red',
                    action: function(){
                        var encerrar = this.$content.find('.encerrar').val();
                        if(!encerrar || encerrar === '' || encerrar != 'ENCERRAR' ){
                            erro_info('Oopss', 'Confirme solicitação para encerrar a conta.');
                            return false;
                        }
                        $('#form-encerrada-conta').append('<input type="hidden" name="confirmar" value="'+encerrar+'" />');

                        $(e.target).closest('.form-conta-encerrada').submit();
                    }
                },
                cancelar:{
                    text: 'Não!',
                    action: function(){
                        // return false;
                    }
                }
            }
        });
    });

})();

function erro_info(titulo, mensagem) {
    $.alert({
        icon: 'fa fa-warning',
        title: titulo,
        content: mensagem,
        backgroundDismiss: true,
        closeIcon: true,
        type: 'red',
        boxWidth: '30%',
        // useBootstrap: false,
        // cancelButton: 'Não, nunca!',
        buttons: {
            confirm: {
                text: 'OK',
                btnClass: 'btn-red',
                action: function(){
                    // return false;
                }
            },
        }
    });
}

function sucesso_info(titulo, mensagem, auto_close="3000") {
    auto_close = 'confirm|'+auto_close;
    $.alert({
        icon: 'fa fa-check',
        title: titulo,
        content: mensagem,
        backgroundDismiss: true,
        closeIcon: true,
        type: 'green',
        boxWidth: '30%',
        autoClose: auto_close,
        // useBootstrap: false,
        // cancelButton: 'Não, nunca!',
        buttons: {
            confirm: {
                text: 'OK',
                btnClass: 'btn-success',
                action: function(){
                    // return false;
                }
            },
        }
    });
}

function confirmar_acao_ajax(titulo, mensagem, url, token, http = 'GET'){

    // var item_url = jQuery(this).data('url');
    // var item_id     = jQuery(this).data('item-id');

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': token // Set token antes de CONFIRMAR
        }
    });

    $.confirm({
        icon: 'fa',
        title: titulo,
        content: mensagem,
        backgroundDismiss: true,
        closeIcon: true,
        type: 'orange',
        boxWidth: '30%',
        useBootstrap: false,
        cancelButton: 'Não',
        buttons: {
            confirmar: function(){
                $.ajax({
                    type: http,
                    url: url,
                    success: function (data) {
                        console.log('Sucesso: ' + data);
                        sucesso_info('Sucesso', 'Ação confirmada');
                        setTimeout(function(){
                            location.reload(true);
                          }, 2000);    
                        return true;
                    },
                    error: function (data) {
                        console.log('Erro: ', data);
                        erro_info('Oopss', 'Ação negada!');
                        return false;
                    }
                });
            },
            cancelar: function(){
                return false;
            }
        }
    });
}

function confirmar_acao_form(titulo, mensagem, form){

    // var item_url = jQuery(this).data('url');
    // var item_id     = jQuery(this).data('item-id');

    $.confirm({
        icon: 'fa',
        title: titulo,
        content: mensagem,
        backgroundDismiss: true,
        closeIcon: true,
        type: 'orange',
        boxWidth: '30%',
        useBootstrap: false,
        cancelButton: 'Não',
        buttons: {
            confirmar: function(){
                if(form == null) {
                    return true;
                }
                else
                {
                    form.submit();
                }
            },
            cancelar: function(){
                return false;
            }
        }
    });
}


function deletar_item(elemento){
    var item_id     = jQuery(elemento).data('item-id');

    $.confirm({
        icon: 'fa fa-warning',
        title: 'Confirma a exclusão?',
        content: '' +
            '<form action="" class="form-delete-item">' +
                '<div class="form-group">' +
                    '<h4 class="texto-centralizado margemT20">Digite DELETAR para confirmar:</h4>' +
                    '<input type="text" placeholder="" class="deletar form-control texto-centralizado" required autofocus />' +
                '</div>' +
            '</form>',
        backgroundDismiss: true,
        closeIcon: true,
        type: 'red',
        boxWidth: '30%',
        autoClose: 'cancel|30000',
        // useBootstrap: false,
        // cancelButton: 'Não, nunca!',
        buttons: {
            remover: {
                text: 'Sim, excluir!',
                btnClass: 'btn-red',
                action: function(){
                    var deletar = this.$content.find('.deletar').val();
                    if(!deletar || deletar !== 'DELETAR'){
                        erro_info('Oopss', 'Texto de confimação incorreto!');
                        return false;
                    }
                    $(elemento).closest('.form-delete').submit();
                }
            },
            cancelar:{
                text: 'Não, nunca!',
                action: function(){
                    // return false;
                }
            }
        }
    });
}

function transferir_dados(elemento){

    var item_id  = jQuery(elemento).data('item-id');

    var qtd_jobs                    = jQuery("#qtd-jobs").val();
    var qtd_avaliando               = jQuery("#qtd-avaliando").val();
    var qtd_coordenando             = jQuery("#qtd-coordenando").val();
    var qtd_coordenando_projetos    = jQuery("#qtd-coordenando-projetos").val();

    var lista_usuario_troca         = jQuery("#usuarios-troca").clone();
    console.log(lista_usuario_troca);
    // alert(qtd_jobs + " - " +qtd_avaliando +" - " + qtd_coordenando + " - " +qtd_coordenando_projetos);
    $.confirm({
        icon: 'fa fa-warning',
        title: 'Transferir dados para outro usuário?',
        content: '' +
            '<form action="" class="form-delete-item">' +
                '<div class="form-group">' +
                    '<h4 class="texto-centralizado margemT20">Detalhesse desse usuário:</h4>' +

                    '<h6 class="margemT5">' + qtd_jobs + ' jobs</h6>' +
                    '<h6 class="margemT5">' + qtd_avaliando + ' jobs que avalia:</h6>' +
                    '<h6 class="margemT5">' + qtd_coordenando +' jobs que coordena:</h6>' +
                    '<h6 class="margemT5">' + qtd_coordenando_projetos + ' projetos que coordena:</h6>' +
                    
                    '<h4 class="margemT5"><select name="usuario_troca" class="usuario-troca">' + lista_usuario_troca.html() + '</select></h4>' +
                    '<h4 class="texto-centralizado margemT5">Digite TRANSFERIR para confirmar:</h4>' +
                    '<input type="text" placeholder="" class="deletar form-control texto-centralizado" required autofocus />' +
                '</div>' +
            '</form>',
        backgroundDismiss: true,
        closeIcon: true,
        type: 'red',
        boxWidth: '30%',
        autoClose: 'cancel|30000',
        // useBootstrap: false,
        // cancelButton: 'Não, nunca!',
        buttons: {
            remover: {
                text: 'Sim, excluir!',
                btnClass: 'btn-red',
                action: function(){
                    var deletar = this.$content.find('.deletar').val();
                    var usuario_troca = this.$content.find('.usuario-troca option:selected').val();
                    jQuery("#usuarios-troca").val(usuario_troca);
                    // alert(usuario_troca);

                    if(!deletar || deletar !== 'TRANSFERIR'){
                        erro_info('Oopss', 'Texto de confimação incorreto!');
                        return false;
                    }
                    if(!usuario_troca || usuario_troca <=0){
                        erro_info('Oopss', 'Escolha um usuário para trocar os dados!');
                        return false;
                    }
                    $(elemento).closest('.form-delete').submit();
                }
            },
            cancelar:{
                text: 'Não, nunca!',
                action: function(){
                    // return false;
                }
            }
        }
    });
}

function transferir_tipo(elemento){

    var item_id  = jQuery(elemento).data('item-id');

    var qtd_tipos                    = jQuery("#qtd-tipos").val();

    var lista_troca         = jQuery("#tipos-troca").clone();
    console.log(lista_troca);
    // alert(qtd_jobs + " - " +qtd_avaliando +" - " + qtd_coordenando + " - " +qtd_coordenando_projetos);
    $.confirm({
        icon: 'fa fa-warning',
        title: 'Transferir dados para outro tipo?',
        content: '' +
            '<form action="" class="form-delete-item">' +
                '<div class="form-group">' +
                    '<h4 class="texto-centralizado margemT20">Quantidade para troca desse tipo:</h4>' +

                    '<h6 class="texto-centralizado margemT5">Qtd ' + qtd_tipos + ' </h6>' +
                    '<h4 class="margemT5"><select name="tipos_troca" class="tipos-troca">' + lista_troca.html() + '</select></h4>' +
                    '<h4 class="texto-centralizado margemT5">Digite TRANSFERIR para confirmar:</h4>' +
                    '<input type="text" placeholder="" class="deletar form-control texto-centralizado" required autofocus />' +
                '</div>' +
            '</form>',
        backgroundDismiss: true,
        closeIcon: true,
        type: 'red',
        boxWidth: '30%',
        autoClose: 'cancel|30000',
        // useBootstrap: false,
        // cancelButton: 'Não, nunca!',
        buttons: {
            remover: {
                text: 'Sim, excluir!',
                btnClass: 'btn-red',
                action: function(){
                    var deletar = this.$content.find('.deletar').val();
                    var tipos_troca = this.$content.find('.tipos-troca option:selected').val();
                    jQuery("#tipos-troca").val(tipos_troca);
                    // alert(tipos_troca);

                    if(!deletar || deletar !== 'TRANSFERIR'){
                        erro_info('Oopss', 'Texto de confimação incorreto!');
                        return false;
                    }
                    if(!tipos_troca || tipos_troca <=0){
                        erro_info('Oopss', 'Escolha um tipo para trocar os dados!');
                        return false;
                    }
                    $(elemento).closest('.form-delete').submit();
                }
            },
            cancelar:{
                text: 'Não, nunca!',
                action: function(){
                    // return false;
                }
            }
        }
    });
}
