/**
 * Arquivo que gera o popup para o freela pegar o job
 */
var j               = "";
var job_id          = "";
var valor           = "";
var nome            = "";
var descricao       = "";
var entrega         = "";
var url             = "";
var rota            = "";
var token           = "";
var thumb           = "";
var conta_paypal    = "";
var limite_job      = "";
var pega_job        = true;
var conta_url       = "";
var tasks_attr      = "";
var tasks_spans     = "";
var rota_termos     = '';
var tasks           = [];
var proposta 		= "";
var candidatura		= "";
var nome_status		= "";
var proposta_enviada = false;

// Messages
var mod_tit        = "";
var mod_tit_tasks  = "";
var mod_btn_ok     = "";
var mod_btn_cancel = "";
var mod_tit_valor  = "";
var mod_tit_prazo  = "";
var mod_li_termos  = "";
var mod_termos     = "";
var mod_no_termos  = "";
var mod_btn_paypal = "";
var mod_job_and    = "";
var mod_details    = "";
var mod_valor_proposta = "";
var mod_propor_valor = "";
var mod_job_candidatura = "";

jQuery(document).ready(function($) {
	
	jQuery('.acao-pega-job').on('click', function(event) {
		event.preventDefault();

		// coloca as informações vinda home nas variaveis para mostra o o job no modal
		// console.log('carregar job: ' + jQuery(this).attr('data-id'));
		job_id        = jQuery(this).attr('data-id');
		j             = jQuery('#job-freela-' + job_id);
		valor         = j.attr('data-valor');
		nome          = j.attr('data-nome');
		descricao     = j.attr('data-descricao');
		entrega       = j.attr('data-entrega');
		mensagem_confirm       = j.attr('data-mensagem-confirm');
		//proposta = 1 (recebe proposta) 0 (valor definido pelo publicador)
		proposta 	  = j.attr('data-proposta');
		candidatura	  = j.attr('data-candidatura');
		url           = j.attr('data-url');
		token         = j.attr('data-token');
		tasks_attr    = j.attr('data-tasks');
		rota          = jQuery(this).attr('data-rota');
		thumb         = jQuery(this).attr('data-thumb-url');
		pega_job      = jQuery(this).attr('data-pega-job');
		
		// !conta_paypal ? mod_btn_paypal : "OK";
		// manda_proposta      = jQuery(this).attr('data-manda-proposta');

		proposta_enviada = jQuery(this).attr('data-proposta-enviada');

		nome_status   = j.attr('data-status');
		
		conta_url     = jQuery(this).attr('data-conta-url');
		conta_paypal  = jQuery(this).attr('data-paypal');
		limite_job    = jQuery(this).attr('data-limite-job');
		tasks_spans   = jQuery('.task-job-' + job_id);
		rota_termos   = jQuery('#rota-termos').val();
		tasks = [];
		mod_tit         	= 		jQuery("#mod-tit").val();
		mod_tit_tasks   	= 		jQuery("#mod-tit-tasks").val();
		mod_btn_ok      	= 		jQuery("#mod-btn-ok").val();
		mod_btn_cancel  	= 		jQuery("#mod-btn-cancel").val();
		mod_tit_valor   	= 		jQuery("#mod-tit-valor").val();
		mod_tit_prazo   	= 		jQuery("#mod-tit-prazo").val();
		mod_li_termos   	= 		jQuery("#mod-li-termos").val();
		mod_termos      	= 		jQuery("#mod-termos").val();
		mod_no_termos   	= 		jQuery("#mod-no-termos").val();
		mod_propor_valor   	= 		jQuery("#mod-propor-valor").val();
		mod_btn_paypal  	= 		jQuery("#mod-btn-paypal").val();
		mod_job_and     	= 		jQuery("#mod-job-and").val();
		mod_pro_and			= 		jQuery("#mod-pro-and").val();
		mod_details     	= 		jQuery("#mod-details").val();
		mod_valor_proposta 	= 		jQuery("#mod-valor-proposta").val();
		mod_job_candidatura = 		jQuery("#mod-job-candidatura").val();
		
		//chama função que monta o Modal
		montaModalPegaJob(job_id, valor, nome, descricao, entrega, tasks, pega_job, proposta_enviada, nome_status);
		//função que formata o input moeda do valor de proposta
		formataInputMoedaReal('valor-proposta-modal');

	});

});

function formataInputMoedaReal(elem){
	if(elem !=null) { var valor =  elem.value; }
}

function montaModalPegaJob(){
	event.preventDefault();
	text_botao = mod_btn_ok;
	text_rodape = "";
	class_btn = "btn-success";

	if(!conta_paypal) {
		text_botao  = mod_btn_paypal;
	}
	else {
		//alert(proposta_enviada);
		if(proposta_enviada)
		{
			text_rodape = mod_pro_and;
			text_botao  = "OK";
		}
		else if((!pega_job && candidatura==0)) {
			text_rodape = mod_job_and;
			text_botao  = "OK";
		}
	}

	$.confirm({
        icon: '',
        title: '',
		content: montHTMLModalPegaJob(),
		onContentReady: function () {
			// when content is fetched & rendered in DOM
			setCurrencyInputEvents();
		},
		onAction: function (btnName) {
			// when a button is clicked, with the button name
			if(btnName == mod_btn_ok.toLowerCase()) {
				// alert('onAction: ' + btnName + " nome botao" + mod_btn_ok);

				// if(proposta==1) {
				// 	var valor_proposta_modal = document.getElementById('valor-proposta-modal').value;
				// 	if(parseInt(valor_proposta_modal)<1 || valor_proposta_modal == ""){
				// 		erro_info('Oopss', mod_propor_valor);
				// 		return false;
				// 	}	
				// }

				// return teste();

			}
		},

        backgroundDismiss: true,
        closeIcon: true,
        type: '',
        boxWidth: '30%',
        autoClose: 'cancel|30000',
        useBootstrap: false,
        cancelButton: mod_details,
        buttons: {
			details: {
				text:     mod_details,
				btnClass: "btn-primary",
				action:   function(){
					document.location.href = rota;
				}
			},
			accept: {
                text: text_botao,
                btnClass: class_btn,
                action: function(){
					

					if(conta_paypal && !proposta_enviada){
						if((pega_job && conta_paypal ) || (proposta==1) || (candidatura==1)){
							if(proposta==1) {
								var valor_proposta_modal = document.getElementById('valor-proposta-modal').value;
								if(parseInt(valor_proposta_modal)<1 || valor_proposta_modal == ""){
									erro_info('Oopss', mod_propor_valor);
									return false;
								}	
							}						

							var termos = document.getElementById('termos').checked;
							if(!termos){
								erro_info('Oopss', mod_no_termos);
								return false;
							}

							if(document.getElementById("valor-proposta-modal")) {
								var valor_proposta = document.getElementById("valor-proposta-modal").value;
								valor_proposta_limpo = parseFloat(valor_proposta.replace('R$ ',''));
								if(valor_proposta_limpo>0) {
									var titulo = "Confirmar Proposta?";
									var mensagem = mensagem_confirm;
									var form = jQuery('#valor-proposta-modal').closest('form');
									//var confirm = confirmar_acao_form(titulo, mensagem, form);

									if(confirm(mensagem)) {
										document.getElementById("form-pega-job-" + job_id).submit();
									} else {
										return false;							
									}

								} else {
									document.getElementById("form-pega-job-" + job_id).submit();
								}
							}
						
						document.getElementById("form-pega-job-" + job_id).submit();
					}

						//document.getElementById("form-pega-job-" + job_id).submit();
					}else if(!conta_paypal) {
						window.location.href = conta_url;

					}else if(conta_paypal && !pega_job){
					 	//return  false;
					}else{
						//return false;
					}
                }
            }		
            
        }
	});
	

}

function montHTMLModalPegaJob(){
	var retorno = '';

	//alert(manda_proposta + ' - ' + pega_job);
	if(proposta_enviada || (proposta==0 && !pega_job && candidatura==0)) {
		retorno +='<div class="col-md-12 btn-warning margemT10 ">'+text_rodape+'</div>';
	}
	
	
	retorno +='<form action="' + url + '" class="form-pega-job" id="form-pega-job-' + job_id + '" >' +
		            '<input type="hidden" name="_token" value="' + token + '">'+
		            '<input type="hidden" name="job_id" value="' + job_id + '">' + 
		            '<div class="col-md-12 displayFlex flexCentralizado flexSpaceBetween">' + 
						'<h2 class="titulo-modal-pega-job">' + mod_tit + ' <span class="nome-job-modal-destaque"><b>' + nome + '</b></span></h2>' +
					'</div>';
					
	if(pega_job || candidatura==1 || proposta==0 ){
		retorno += '<div class="col-md-12 margemT20">' +
						'<h3 class="negrito valor-modal-destaque">' + valor + '</h3>';

		if(nome_status == "Novo") {
			retorno += '<input type="hidden" name="job_em_candidatura" value="0">' ;
		} 
		
		else if((nome_status == "Em Candidatura")) {
			retorno += '<h5 class="negrito valor-modal-destaque">' + mod_job_candidatura +'</h5>' +
				'<input type="hidden" name="job_em_candidatura" value="1">' ;	
		}
		retorno += '<input type="hidden" name="valor_proposta_job" value="' + valor + '">' ;	

		retorno += '</div>';
	}

	if((proposta==1 && !proposta_enviada)){
		retorno += '<div class="col-md-6 margemT20">' +
						'<h5 class="">'+mod_valor_proposta+'</h5>' +
						'<h3 class="negrito valor-modal-destaque"><input id="valor-proposta-modal" onkeyup="formataInputMoedaReal(this);" type="text" data-type="currency" '+
							'class="form-control required autofocus" name="valor_proposta_job" step="0.01" placeholder="R$ 0,00" /></h3>' +
						'<input type="hidden" name="job_em_proposta" value="1">'+
					'</div>';

	}else if(proposta_enviada){
		retorno += '<div class="col-md-6 margemT20">' +
						'<h5 class="">'+mod_valor_proposta+'</h5>' +
						'<h3 class="negrito valor-modal-destaque">' + valor + '</h3>' +
						'<input type="hidden" name="job_em_proposta" value="1">'+
					'</div>';
	}

	retorno +=		'<div class="col-md-6 margemT20 margemB20">' +
	                	'<h5 class="">' + mod_tit_prazo + '</h5>' +
	                	'<h4 class="negrito">' + entrega + '</h4>' +
					'</div>' +
		        	'<hr class="col-md-11">' +
	            	'<div class="col-md-6 margemT20">' + 
			        	'<h5 class="margemT20">' + mod_tit_tasks + ':</h5>'
					'</div>'
					'<div class="col-md-6 displayFlex flexSpaceBetween flexWrap">';
						tasks_spans.each(function(index, el) {
							retorno += '<h5 class="negrito">- ' +  $(el).attr('data-nome') + '</h5>';
						});
	retorno += '</div>'+
				'<div class="col-md-6"><img src="' + thumb + '" class="img-responsive card-job-thumb" /></div>';
	//&& 
	
	if(conta_paypal && !proposta_enviada ){
		if((proposta==1 ) || (pega_job && proposta==0 )  || (candidatura==1 )) {
			retorno += 
				'<div class="col-md-12"><input type="checkbox" id="termos" class="termos required autofocus margemT40" > ' + mod_li_termos + 
				' <a href="'+ rota_termos + '" target="_blank">' + mod_termos + '</a></div>' +
				'</form>';
		}
	}
		

		
    return retorno;
}

