/**
 * Arquivo que gera o popup para o freela pegar o job
 */
var job_id      		= "";
var valor       		= "";
var taxa 				= "";
var nome        		= "";
var descricao   		= "";
var entrega     		= "";
var url         		= "";
var token       		= "";
var tasks_attr  		= "";
var tasks_spans 		= "";
var rota_termos 		= '';
var tasks       		= [];
var solicita_proposta 	= false;
// Messages
var mod_tit        		= "";
var mod_tit_tasks  		= "";
var mod_btn_ok     		= "";
var mod_btn_cancel 		= "";
var mod_tit_valor  		= "";
var mod_tit_prazo  		= "";
var mod_li_termos  		= "";
var mod_termos     		= "";
var mod_no_termos  		= "";
var url_modal 	   		= "";
var mod_btn_pagamento 	= "";
var button_publicar 	= "";

jQuery(document).ready(function($) {
	
	jQuery('.acao-avulso-job').on('click', function(event) {
		event.preventDefault();

		
		//		.log('carregar job: ' + jQuery(this).attr('data-id'));
		job_id      		= jQuery(this).attr('data-id');
		solicita_proposta   = jQuery('#solicita-proposta').val() != '0';
		valor       		= !solicita_proposta ? jQuery('#valor-job').val() : '';
		taxa        		= !solicita_proposta ? jQuery('#taxa-real').val() : '';
		solicita_proposta   = solicita_proposta || jQuery('#avaliar-perfil').val() != '0';
		nome        		= jQuery('#job-nome').val(); 
		descricao   		= jQuery('#job-descricao').val();
		entrega     		= jQuery('#data_revisao').val();
		data_limite     	= jQuery('#data-limite').val();
		url         		= jQuery('#job-url').val();
		token       		= jQuery('input[name="_token"]').val();
		tasks_attr  		= jQuery('#job-freela-' + job_id).attr('data-tasks');
		tasks_spans 		= jQuery('.select2-selection__choice');
		rota_termos 		= jQuery('#rota-termos').val();
		tasks 				= [];
		mod_tit         	= jQuery("#mod-tit").val();
		mod_tit_tasks   	= jQuery("#mod-tit-tasks").val();
		mod_btn_ok      	= jQuery("#mod-btn-ok").val();
		mod_btn_pagamento   = jQuery("#mod-btn-pagamento").val();
		button_publicar 	= jQuery('#publicar-job');
		

		var solicita_proposta_temp   = jQuery('#solicita-proposta').val();
		var solicita_perfil_temp   = jQuery('#avaliar-perfil').val();

		if((solicita_proposta_temp==0 && solicita_perfil_temp == 0) || solicita_perfil_temp ==1)
		{
			mod_btn_ok = mod_btn_pagamento;
		}
		mod_btn_cancel  	= jQuery("#mod-btn-cancel").val();
		mod_tit_valor   	= jQuery("#mod-tit-valor").val();
		mod_tit_proposta   	= solicita_proposta ? jQuery("#mod-tit-proposta").val() : '';
		mod_tit_prazo   	= jQuery("#mod-tit-prazo").val();
		mod_li_termos   	= jQuery("#mod-li-termos").val();
		mod_termos      	= jQuery("#mod-termos").val();
		mod_no_termos   	= jQuery("#mod-no-termos").val();

		// var solicita_proposta_temp   = jQuery('#solicita-proposta').val();
		// var solicita_perfil_temp   = jQuery('#avaliar-perfil').val();

		// if((solicita_proposta_temp==0 && solicita_perfil_temp == 0) || solicita_perfil_temp ==1)
		// {
		// 	mod_btn_ok = mod_btn_pagamento;
        // }
		
		
		url_modal		   	= jQuery("#url-modal").val();
		// console.log(tasks_spans);
		// tasks_spans.forEach(function(element, index, tasks_spans){
		// 	tasks.push(value);
		// });

		montaModalPublicaJob(job_id, valor, taxa, nome, descricao, entrega, tasks);

	});

});
url_modal
function montaModalPublicaJob(){
	event.preventDefault();
	$.confirm({
        icon: '',
        title: '',
		content: montHTMLModalPublicaJob(),
        backgroundDismiss: true,
        closeIcon: true,
        type: '',
        boxWidth: '30%',
        autoClose: 'cancel|30000',
        useBootstrap: false,
        cancelButton: mod_btn_cancel,
        buttons: {
            remover: {
                text: mod_btn_ok,
                btnClass: 'btn-info',
                action: function(){
                    var termos = document.getElementById('termos').checked;
                    // console.log("termos: " + termos);
                    if(!termos){
                        erro_info('Oopss', mod_no_termos);
                        return false;
                    }
					button_publicar.hide();
                    document.getElementById("form-novo-job").submit();
                }
            // },
            // cancelar: function(){
                // return false;
            }
        }
    });
}

function montHTMLModalPublicaJob(){

	var novaData = "";
	if(entrega.length != 0) {
		novaData = getFormattedDate(entrega);		
	}
	
	var retorno = '<form action="' + url + '" class="form-pega-job" id="form-pega-job" name="teste" >' +
		            '<input type="hidden" name="_token" value="' + token + '">'+
		            '<div class="col-md-12">' + 
		            	'<h2 class="titulo-modal-pega-job">Job <span class="nome-job-modal-destaque"><b> ' + nome +' </b></span></h2>' +
	            	'</div>' +
	                '<div class="col-md-10 col-md-offset-1 margemT15">' + 
	                	//removido em 26.07.20 '<h4 class="">' + descricao + '</h4>' +
                	'</div>' +
                	'<div class="col-md-6 margemT20">' +
	                	'<h5 class="">' + mod_tit_valor + '</h4>' +
						'<h3 class="negrito valor-modal-destaque">' + valor + '</h3>' ;
						// '<h5 class="">' + taxa  + '</h5>' ;

					if(solicita_proposta) {
						retorno +='<h4 class=""><b>'+ mod_tit_proposta + '</b> ' + getFormattedDate(data_limite) + ' </h4>' ;
					}

	    retorno += '</div>' +
	            	'<div class="col-md-6 margemT20 margemB20">' +
	                	'<h5 class="">' + mod_tit_prazo + '</h4>' +
	                	'<h4 class="negrito">' + novaData + '</h4>' +
	            	'</div>' +
		        	'<hr class="col-md-11">' +
	            	'<div class="col-md-6 margemT20s">' + 
			        	'<h5 class="margemT20">' + mod_tit_tasks + ':</h5>'
		        	'</div>';
	tasks_spans.each(function(index, el) {
		retorno += '<div class="col-md-12"><h5 class="negrito">- ' +  $(el).attr('title') + '</h5></div>';
	});
	retorno += 
		'<input type="checkbox" id="termos" class="termos required autofocus margemT40" > ' + mod_li_termos + '<a href="' + rota_termos + '" target="_blank"> ' + mod_termos + '</a>' +
			   '</form>';
			   
	retorno += '<div id="paypal-button-container"></div>';

	return retorno;
}