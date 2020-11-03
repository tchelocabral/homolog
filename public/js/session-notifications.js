/**
 * @011Brasil
 * para funções específicas para o box de Session Message
 */

(function(){
    
    jQuery('#fechar-session-message').on('click', function (e) {
        jQuery(this).closest('.session-message').css({
            "right": "-14px",
            "opacity": "0"
        });
        jQuery(this).closest('.session-message').removeClass('session-notification-efeito');
    });

    //marca como lidas as notificações do usuário
    //deve ter a classe todas para habilitar ler todas as notificações
    //quando clicado no sino
    jQuery('.notification-clear.todas').click(function (e) {

        rota = jQuery(this).attr("data-route");
        $.ajax({
            method: 'GET', // Type of response and matches what we said in the route
            url:  rota, // This is the url we gave in the route
            success: function(response){ // What to do if we succeed
                console.log(response); 
                alert("marcado");
            },
            error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                console.log(JSON.stringify(jqXHR));
                console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
            }
        });

    });

    jQuery('.user-notification').click(function (e) {

        rota = jQuery(this).attr("data-route");
        $.ajax({
            method: 'GET', // Type of response and matches what we said in the route
            url:  rota, // This is the url we gave in the route
            success: function(response){ // What to do if we succeed
                console.log(response); 
            },
            error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                console.log(JSON.stringify(jqXHR));
                console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
            }
        });

    });    

})();


// notificações pusher

