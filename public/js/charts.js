/**
 * @CriaçãoCia
 * para funções específicas e criação de Gráficos
 */

(function(){

    $.each( jQuery('.graficos'), function (index, value) {

        atualiza_grafico(value);
       

    });
    /* Inicializa Gráficos */

})();

function atualiza_grafico(grafico) {
    var value2d     = grafico.getContext('2d');
    var valor       = jQuery('#' + grafico.id).data('valor');
    var statusJob   = jQuery('#' + grafico.id).data('status');
    var valor_fundo = 100 - valor;

    var cor         = "#800000";   

    if(statusJob == "8") {
        cor  =  '#800000';
    }
    else if(valor < 34) {
        cor  =  '#F0E68C';
    }
    else if(valor < 67) {
        cor  =  '#FFA500';
    }
    else if(valor <= 100){
        cor  =  '#006400';
    }
    

    
//    var cor         = valor < 50 ? '#F0E68C' : '#FFA500';
    var cor_fundo   = '#ecf0f5';

    var graf_config = {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [ valor, valor_fundo ],
                backgroundColor: [ cor ],
                label: 'Andamento'
            }],
            labels: [
                'Concluído',
                'A Fazer'
            ]
        },
        options: {
            responsive: true,
            legend: {
                display: false
            }
        }
    };

    var graf = new Chart(value2d, graf_config);
    // var graf = new Chart(value2d).Doughnut(graf_config);
    // return grafico;
}
