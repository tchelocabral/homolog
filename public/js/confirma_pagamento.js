/**
 * Arquivo que gera o popup para o confirmar pagamento de seviço
*/
var pgto         = "";
var pgto_id      = "";
var de           = "";
var para         = "";
var pagador      = "";
var taxa         = "";
var cc_id        = "";
var cat_custo_id = "";
var m_type       = "";
var m_id         = "";
var m_nome       = "";
var conta        = "";
var valor_de     = "";
var valor_para   = "";
var valor_taxa   = "";
var observacoes  = "";
var doc          = "";
var rota         = "";
var token        = "";
var tipo_modal   = "";

jQuery(document).ready(function($) {

    data_texto_titulo           = document.getElementById("texto-titulo").value;
    texto_confirme              = document.getElementById("texto-confirme").value;
    texto_data_pagamento        = document.getElementById("texto-data-pagamento").value;
    texto_confirmando_pagamento = document.getElementById("texto-confirmando-pagamento").value;
    texto_de                    = document.getElementById("texto-de").value;              
    texto_para                  = document.getElementById("texto-para").value;            
    texto_total_freelancer      = document.getElementById("texto-total-freelancer").value;
    texto_pago_em               = document.getElementById("texto-pago-em").value;     
    texto_pagmento_de           = document.getElementById("texto-pagamento-de").value;   
    texto_valor                 = document.getElementById("texto-valor").value;  
    texto_taxas                 = document.getElementById("texto-taxas").value;  
    texto_confirmar_liberacao   = document.getElementById("texto-confirmar-liberacao").value;  
    texto_recibo_liberacao      = document.getElementById("texto-recibo-liberacao").value; 
    texto_confirmar_pagamento   = document.getElementById("texto-confirmar-pagamento").value; 

	jQuery('.confirmar-pagamento').on('click', function(event) {
		event.preventDefault();
        console.log('carregar pagamento: ' + jQuery(this));
        pgto       = jQuery(this);

		pgto_id             = pgto.attr('data-id');
		de                  = pgto.attr('data-de');
		para                = pgto.attr('data-para');
		pagador             = pgto.attr('data-pagador');
		taxa                = pgto.attr('data-taxa');
		cc_id               = pgto.attr('data-cc');
		cat_custo_id        = pgto.attr('data-cat-custo');
		m_type              = pgto.attr('data-m-type');
		m_id                = pgto.attr('data-m-id');
		m_nome              = pgto.attr('data-m-nome');
		conta               = pgto.attr('data-conta');
		valor_de            = pgto.attr('data-valor-de');
		valor_para          = pgto.attr('data-valor-para');
        valor_taxa          = pgto.attr('data-valor-taxa');
        job_pago_em         = pgto.attr('data-job-pago-em');
        job_pago_original   = pgto.attr('data-job-pago-original');
		rota                = pgto.attr('data-rota');
		token               = pgto.attr('data-token');
        tipo                = pgto.attr('data-tipo-modal');
        texto_titulo        = pgto.attr('data-texto-titulo');
        
        
		// thumb        = pgto.attr('data-thumb-url');
        if(tipo == 'detalhes'){
            montaModalDetalhesPgto();
        }else{
            montaModalConfirmaPgto();
        }

	});


	
	jQuery('.confirmar-pagamento-freelancer').on('click', function(event) {
		event.preventDefault();
        console.log('carregar pagamento: ' + jQuery(this));
        pgto       = jQuery(this);

		pgto_id             = pgto.attr('data-id');
		de                  = pgto.attr('data-de');
		para                = pgto.attr('data-para');
		pagador             = pgto.attr('data-pagador');
		taxa                = pgto.attr('data-taxa');
		cc_id               = pgto.attr('data-cc');
		cat_custo_id        = pgto.attr('data-cat-custo');
		m_type              = pgto.attr('data-m-type');
		m_id                = pgto.attr('data-m-id');
		m_nome              = pgto.attr('data-m-nome');
		conta               = pgto.attr('data-conta');
		valor_de            = pgto.attr('data-valor-de');
		valor_para          = pgto.attr('data-valor-para');
        valor_taxa          = pgto.attr('data-valor-taxa');
        job_pago_em         = pgto.attr('data-job-pago-em');
        job_para_pago_em    = pgto.attr('data-job-para-pago-em');
		rota                = pgto.attr('data-rota');
		token               = pgto.attr('data-token');
        tipo                = pgto.attr('data-tipo-modal');
        data_texto_titulo   = pgto.attr('data-texto-titulo');

		// thumb        = pgto.attr('data-thumb-url');
        if(tipo == 'detalhes'){
            montaModalDetalhesPgtoFreelancer();
        }else{
            montaModalConfirmaPgtoFreelancer();
        }

	});


    
});

function montaModalConfirmaPgto(){
	// event.preventDefault();
	text_botao =texto_confirmar_pagamento;
	class_btn = "btn-success";
	$.confirm({
        icon: '',
        title: '',
        content: geraHTMLModalConfirmaPgto(),
        backgroundDismiss: true,
        closeIcon: true,
        type: '',
        boxWidth: '30%',
        autoClose: 'cancel|30000',
        useBootstrap: false,
        cancelButton: 'Cancelar',
        buttons: {
            remover: {
                text: text_botao,
                btnClass: class_btn,
                action: function(){
                    // var termos = document.getElementById('termos').checked;
                    // console.log("termos: " + termos);
                    // if(!termos){
                    //     erro_info('Oopss', 'Você deve concordar com os termos de uso da plataforma para pegar um Job.');
                    //     return false;
                    // }
                    document.getElementById("form-confirma-pgto-" + pgto_id).submit();
                    pgto_id      = "";
                    de           = "";
                    para         = "";
                    pagador      = "";
                    taxa         = "";
                    cc_id        = "";
                    cat_custo_id = "";
                    m_type       = "";
                    m_id         = "";
                    m_nome       = "";
                    conta        = "";
                    valor_de     = "";
                    valor_para   = "";
                    valor_taxa   = "";
                    rota         = "";
                    token         = "";
                }
            },
            cancelar: function(){
                // return false;
            }
        }
    });
}

function geraHTMLModalConfirmaPgto(){
	var retorno = '<form action="' + rota + '" class="form-confirma-pgto" id="form-confirma-pgto-' + pgto_id + '" method="POST" enctype="multipart/form-data"> ' +
		            '<input type="hidden" name="_token" value="' + token + '">'+
                    '<input type="hidden" name="pgto_id" value="' + pgto_id + '">' + 
                    '<input type="hidden" name="job_id" value="' + m_id + '">' + 
		            '<div class="col-md-12">' + 
		            	'<h2 class="titulo-modal-pega-job">'+ texto_titulo + '</b></h2>' +
                    '</div>' +
                    '<hr class="col-md-11">' +
	                // '<div class="col-md-4 col-md-offset-1 margemT20">' + 
	                // 	'<h4 class="">De: ' + de + '</h4>' +
                    // '</div>' +
                    '<div class="col-md-12 margemB30">' + 
	                	'<h4 class="">' + texto_confirme +' ' + data_texto_titulo +' <br>do <b>' + m_type + ' ' + m_nome + '?</b></h4>' +
                	'</div>' +

                   '<div class="col-md-12 margemB30">' + 
	                	'<h4 class="">'+ texto_pago_em +' <b>' + job_pago_em +'</b></h4>' +
	                	'<h4 class="">' + texto_confirme + ' Data <input type="date" id="date" name="novo_pago_em" value="' + job_pago_original +'"> </h4>' +
                	'</div>' +                   
                	// '<div class="col-md-12 displayFlex flexSpaceBetween">' +
	                	// '<h4 class=""><b>Para:</b> '  + para + '</h4>' +
	            	// '</div>' +
	            	// '<div class="col-md-4">' +
	                // 	'<h4 class=""><b>Paypal:</b> ' + conta + '</h4>' +
	            	// '</div>' +
		            // '<div class="col-md-12 margemB20">' + 
                    //     '<h4 class=""><b>Pagamento de:</b> R$' + valor_de + '</h4>'+
                    // '</div>' +
		            // '<div class="col-md-12">' + 
                    //     '<h4 class=""><b>Taxas:</b> R$' + valor_taxa + '</h4>'+
                    // '</div>' +
                    // '<div class="col-md-12">' + 
                    //     '<h4 class=""><b>Total Freelancer:</b> R$' + valor_para + '</h4>'+
                    // '</div>'+

                    // '<div class="col-md-12 margemT20 margemB30">' + 
                    //     '<h4>Enviar Comprovante</h4>' + 
                    //     '<input type="file" name="doc_pgto" >'
                    // '</div>' + 

	            	
               '</form>';
    return retorno;
}

function montaModalDetalhesPgto(){
	// event.preventDefault();
	text_botao ="ok";
	class_btn = "btn-info";
	$.confirm({
        icon: '',
        title: '',
        content: geraHTMLModalDetalhesPgto(),
        backgroundDismiss: true,
        closeIcon: true,
        type: '',
        boxWidth: '30%',
        autoClose: 'cancel|30000',
        useBootstrap: false,
        // cancelButton: '',
        buttons: {
            remover: {
                text: text_botao,
                btnClass: class_btn,
                action: function(){
                }
            },
            // cancelar: function(){
                // return false;
            // }
        }
    });
}

function geraHTMLModalDetalhesPgto(){
	var retorno = '<div class="col-md-12">' + 
		            	'<h2 class="titulo-modal-pega-job">'+texto_confirme+' <b>' + m_type + ' ' + m_id + '</b></h2>' +
                    '</div>' +
                    '<hr class="col-md-11">' +
	                '<div class="col-md-6 ">' + 
	                	'<h4 class=""><b>' + texto_de +': </b>' + de + '</h4>' +
                	'</div>' +
                	'<div class="col-md-6 ">' +
	                	'<h4 class=""><b> ' + texto_para +':</b> ' + para + '</h4>' +
	            	'</div>' +
	            	// '<div class="col-md-6 ">' +
	                // 	'<h4 class=""><b>Por</b>: ' + pagador + '</h4>' +
	            	// '</div>' +
		            '<div class="col-md-12">' + 
                        '<h4 class="negrito">'+ texto_pagmento_de +': R$' + valor_de + '</h4>'+
                    '</div>' +
		            '<div class="col-md-12">' + 
                        '<h4 class="negrito">' + texto_taxas +': R$' + valor_taxa + '</h4>'+
                    '</div>' +
                    '<div class="col-md-12">' + 
                        '<h4 class="negrito">' + texto_total_freelancer +': R$' + valor_para + '</h4>'+
                    '</div>'+
                    '<div class="col-md-12">' + 
                        '<h4 class="negrito">' + texto_pago_em +': ' + job_pago_em + '</h4>'+
                    '</div>';
                    
    return retorno;
}



function montaModalConfirmaPgtoFreelancer(){
	event.preventDefault();
	text_botao =texto_confirmar_liberacao;
	class_btn = "btn-success";
	$.confirm({
        icon: '',
        title: '',
        content: geraHTMLModalConfirmaPgtoFreelancer(),
        backgroundDismiss: true,
        closeIcon: true,
        type: '',
        boxWidth: '30%',
        autoClose: 'cancel|30000',
        useBootstrap: false,
        cancelButton: 'Cancelar',
        buttons: {
            remover: {
                text: text_botao,
                btnClass: class_btn,
                action: function(){
                    // var termos = document.getElementById('termos').checked;
                    // console.log("termos: " + termos);
                    // if(!termos){
                    //     erro_info('Oopss', 'Você deve concordar com os termos de uso da plataforma para pegar um Job.');
                    //     return false;
                    // }
                    document.getElementById("form-confirma-pgto-" + pgto_id).submit();
                    pgto_id      = "";
                    de           = "";
                    para         = "";
                    pagador      = "";
                    taxa         = "";
                    cc_id        = "";
                    cat_custo_id = "";
                    m_type       = "";
                    m_id         = "";
                    m_nome       = "";
                    conta        = "";
                    valor_de     = "";
                    valor_para   = "";
                    valor_taxa   = "";
                    rota         = "";
                    token         = "";
                }
            },
            cancelar: function(){
                // return false;
            }
        }
    });
}

function geraHTMLModalConfirmaPgtoFreelancer(){
	var retorno = '<form action="' + rota + '" class="form-confirma-pgto" id="form-confirma-pgto-' + pgto_id + '" method="POST" enctype="multipart/form-data"> ' +
		            '<input type="hidden" name="_token" value="' + token + '">'+
		            '<input type="hidden" name="pgto_id" value="' + pgto_id + '">' + 
		            '<div class="col-md-12">' + 
		            	'<h2 class="titulo-modal-pega-job">' + data_texto_titulo +'</b></h2>' +
                    '</div>' +
                    '<hr class="col-md-11">' +
	                // '<div class="col-md-4 col-md-offset-1 margemT20">' + 
	                // 	'<h4 class="">De: ' + de + '</h4>' +
                    // '</div>' +
                    '<div class="col-md-12 margemB30">' + 
	                	'<h4 class="">' + texto_confirme + ' ' + data_texto_titulo+ ' <br>do <b>' + m_type + ' ' + m_nome + '</b> para <b>' + para + '?</b></h4>' +
                	'</div>' +

                    '<div class="col-md-12">' + 
                        '<h4 class="negrito">' + texto_total_freelancer + ': R$' + valor_para + '</h4>'+
                    '</div>'+                   
     
                    '<div class="col-md-12 margemB30">' + 
                        '<h4 class="">Confirmar ' + texto_data_pagamento+ '<input type="date" id="date" name="pago_em" value="' + job_para_pago_em +'"> </h4>' +
                    '</div>' +                   

                    // '<div class="col-md-12 displayFlex flexSpaceBetween">' +
	                	// '<h4 class=""><b>Para:</b> '  + para + '</h4>' +
	            	// '</div>' +
	            	// '<div class="col-md-4">' +
	                // 	'<h4 class=""><b>Paypal:</b> ' + conta + '</h4>' +
	            	// '</div>' +
		            // '<div class="col-md-12 margemB20">' + 
                    //     '<h4 class=""><b>Pagamento de:</b> R$' + valor_de + '</h4>'+
                    // '</div>' +
		            // '<div class="col-md-12">' + 
                    //     '<h4 class=""><b>Taxas:</b> R$' + valor_taxa + '</h4>'+
                    // '</div>' +
                    // '<div class="col-md-12">' + 
                    //     '<h4 class=""><b>Total Freelancer:</b> R$' + valor_para + '</h4>'+
                    // '</div>'+

                    // '<div class="col-md-12 margemT20 margemB30">' + 
                    //     '<h4>Enviar Comprovante</h4>' + 
                    //     '<input type="file" name="doc_pgto" >'
                    // '</div>' + 

	            	
               '</form>';
    return retorno;
}

function montaModalDetalhesPgtoFreelancer(){
	// event.preventDefault();
	text_botao ="ok";
	class_btn = "btn-info";
	$.confirm({
        icon: '',
        title: '',
        content: geraHTMLModalDetalhesPgtoFreelancer(),
        backgroundDismiss: true,
        closeIcon: true,
        type: '',
        boxWidth: '30%',
        autoClose: 'cancel|30000',
        useBootstrap: false,
        // cancelButton: '',
        buttons: {
            remover: {
                text: text_botao,
                btnClass: class_btn,
                action: function(){
                }
            },
            // cancelar: function(){
                // return false;
            // }
        }
    });
}

function geraHTMLModalDetalhesPgtoFreelancer(){
	var retorno = '<div class="col-md-12">' + 
		            	'<h2 class="titulo-modal-pega-job">' + texto_recibo_liberacao +' <b>' + m_type + ' ' + m_id + '</b></h2>' +
                    '</div>' +
                    '<hr class="col-md-11">' +
	                '<div class="col-md-6 ">' + 
	                	'<h4 class=""><b>'+ texto_de +': </b>' + de + '</h4>' +
                	'</div>' +
                	'<div class="col-md-6 ">' +
	                	'<h4 class=""><b>' + texto_para + ':</b> ' + para + '</h4>' +
	            	'</div>' +
                    '<div class="col-md-12">' + 
                        '<h4 class="negrito">' + texto_total_freelancer + ': R$' + valor_para + '</h4>'+
                    '</div>'+
                    '<div class="col-md-12">' + 
                        '<h4 class="negrito"> ' + texto_pago_em + ': ' + job_para_pago_em + '</h4>'+
                    '</div>';
    return retorno;
}