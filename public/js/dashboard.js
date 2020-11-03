jQuery(document).ready(function(){
    item('projeto-andamento');
    item('projetos_coordenando');
    item('jobs_freelas');
});


function item(tipo)
{
    var url = jQuery('#'+tipo).attr('data-url');;
    
    $.ajax({
        url: url,
        type: 'GET',
        // antes de chamar o ajax
        beforeSend: function (xhr) {
        },
        // se a requisicao for bem-sucedida
        success: function (data) {
            if (data.length === 0) {

            } else {
                console.log(data);

                var div_item = jQuery('#'+tipo); 

                jQuery(div_item).append(data);
            }
        },
        // se der erro
        error: function (data) {
            console.log(data)
        },
        // quando estiver tudo completo
        complete: function () {
        }
    });
}