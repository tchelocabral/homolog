

var novo      = false;
var edita     = false;
var visualiza = false;

var imgSection;

var newImgSelection;

var imagesOnCanvas = [];

var indexAlteracao = 0;

var dadosAlteracao = [[],[],[],[],[]];

var widthMaxImg = 1280;

var divAreaInput;

var imgBg;

var imgPin;

var canvas;

var canvas2D;



(function(){



// $(document).ready(function(){

    // $('#form-revisao').on('submit', function(e){
    // $("#revisao-avaliacao-submit").on('click', function(e) {        

        // e.preventDefault();


    // });

    var requestAnimationFrame =
    window.requestAnimationFrame       || window.mozRequestAnimationFrame ||
    window.webkitRequestAnimationFrame || window.msRequestAnimationFrame;

    window.requestAnimationFrame = requestAnimationFrame;

    var text_n_info = jQuery('#text-n-info') ? jQuery('#text-n-info').val() : 'Não Informado';
    var text_nv_rev = jQuery('#text-nv-rev') ? jQuery('#text-nv-rev').val() : 'Nova Revisão';

    // Cria aba de nova revisão
    function novaRevisao(e) {

        // pegar total de abas
        var tab_id = jQuery(".tab-revisao").length;
        if(tab_id > 0){
            erro_info('Oops', 'Já existe uma aba de Preview aberta.');
        
        }else {
            
            // Pega a rota padrão do endpoint que está no disparador do evento 
            var url = e.currentTarget.attributes["data-url"].value;
            var deadline = '';
            if(e.currentTarget.attributes["data-deadline"] && e.currentTarget.attributes["data-deadline"].value !== text_n_info){
                deadline = e.currentTarget.attributes["data-deadline"].value.split(' ', 1)[0];
            }
            
            // Chamada Ajax para gerar html de nova revisão
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
                        // console.log(data);
                        //Monta aba
                        //para fechar a aba <span> x </span>
                        var cont_aba = '<div id="nova-revisao-' + tab_id + '" class="tab-pane fade">' + data + '</div>';
                        var aba =
                            '<li>' +
                                '<a ' +
                                    'data-toggle="tab" ' +
                                    'href="#nova-revisao-' + tab_id + '" ' +
                                    'class="tab-revisao nav-link animated demo verde">' +
                                    text_nv_rev + ' <span class="pull-right margemL20 mouse-pointer"> x </span>' +
                                '</a> ' +
                            '</li>';
                        // adiciona aba depois da ultima li
                        jQuery('#tabs-imagem ul li:last-child').after(aba);
                        // adiciona conteúdo à tab
                        jQuery('#tabs-imagem .tab-content').append(cont_aba);
                        // mostra a tab do link da ultimo li
                        jQuery('#tabs-imagem ul li:last-child a').tab('show');
                        // adiciona clique para fechar a aba no span do a do ultimo li
                        jQuery('#tabs-imagem ul li:last-child a span').on("click", function () {
                            var anchor = $(this).siblings('a');
                            $(anchor.attr('href')).remove();
                            $(this).parent().remove();
                            $('#nova-revisao-' + tab_id).remove();
                            $(".nav-tabs li").children('a').first().click();
                        });
                        novo           = true;
                        edita          = false;
                        visualiza      = false;
                        imagesOnCanvas = [];
                        imgPin         = document.getElementById('imagem-pin');
                        revisao("new"); 
                        if(deadline != ''){
                            jQuery('#data-entrega').val(deadline);
                        }
                    }
                },
                // se der erro
                error: function (data) {
                    console.log(data)
                },
                // quando estiver tudo completo
                complete: function () {
                    $('#form-revisao').on('submit', function(e){
                        //e.preventDefault();
                        controleSubmitFormRevisao($(this));
                        console.log("passou aqui - fim");
                    });
                }
            });
        }
    }
    $('#add-revisao').on('click', function (e) {
        novaRevisao(e);
    });

    // Cria aba para visualiar revisão
    function visualizaRevisao(e) {

        // pegar total de abas
        var tab_id   = jQuery(".tab-revisao").length;
        if(tab_id > 0){
            erro_info('Oops', 'Já existe uma aba de Preview aberta.');
        }else {
            // Pega a rota padrão do endpoint com os dados do tipo de job
            var url = e.currentTarget.attributes["data-url"].value;
            // Chamada Ajax
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
                        //Monta aba
                        //para fechar a aba <span> x </span>
                        var title = e.currentTarget.attributes["data-title"].value;

                        var cont_aba = '<div id="visualiza-revisao-' + tab_id + '" class="tab-pane fade">' + data + '</div>';
                        var aba =
                            '<li>' +
                                '<a ' +
                                    'data-toggle="tab" ' +
                                    'href="#visualiza-revisao-' + tab_id + '" ' +
                                    'class="tab-revisao nav-link animated demo verde">' +
                                    // ' Revisão de Preview ' + title + ' <span class="pull-right margemL20 mouse-pointer"> x </span>' +
                                    title + ' <span class="pull-right margemL20 mouse-pointer"> x </span>' +
                                '</a> ' +
                            '</li>';

                        // pegar o container de abas
                        jQuery('#tabs-imagem ul li:last-child').after(aba);
                        jQuery('#tabs-imagem .tab-content').append(cont_aba);
                        jQuery('#tabs-imagem ul li:last-child a').tab('show');
                        jQuery('#tabs-imagem ul li:last-child a span').on("click", function () {
                            var anchor = $(this).siblings('a');
                            $(anchor.attr('href')).remove();
                            $(this).parent().remove();
                            $('#visualiza-revisao-' + tab_id).remove();
                            $(".nav-tabs li").children('a').last().click();
                            novo = false;
                            edita = false;
                            visualiza = false;
                        });
                        novo      = false;
                        edita     = false;
                        visualiza = true;
                       // imgPin    = document.getElementById('imagem-pin');
                        revisao("view");
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
    }
    $('.visualiza-revisao').on('click', function (e) {
        visualizaRevisao(e);
    });

    // Cria aba para editar revisão
    function editaRevisao(e) {

        // pegar total de abas
        var tab_id   = jQuery(".tab-revisao").length;
        if(tab_id > 0){
            erro_info('Oops', 'Já existe uma aba de Preview aberta.');
        }else {
            // Pega a rota padrão do endpoint com os dados do tipo de job
            var url = e.currentTarget.attributes["data-url"].value;
            // Chamada Ajax
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
                        // console.log(data);
                        //Monta aba
                        //para fechar a aba <span> x </span>
                        var title = e.currentTarget.attributes["data-title"].value;

                        var cont_aba = '<div id="edita-revisao-' + tab_id + '" class="tab-pane fade">' + data + '</div>';
                        var aba =
                            '<li>' +
                                '<a ' +
                                    'data-toggle="tab" ' +
                                    'href="#edita-revisao-' + tab_id + '" ' +
                                    'class="tab-revisao nav-link animated demo verde">' +
                                    ' Edita Revisão ' + title + ' <span class="pull-right margemL20 mouse-pointer"> x </span>' +
                                '</a> ' +
                            '</li>';

                        // pegar o container de abas
                        jQuery('#tabs-imagem ul li:last-child').after(aba);
                        jQuery('#tabs-imagem .tab-content').append(cont_aba);
                        jQuery('#tabs-imagem ul li:last-child a').tab('show');
                        jQuery('#tabs-imagem ul li:last-child a span').on("click", function () {
                            var anchor = $(this).siblings('a');
                            $(anchor.attr('href')).remove();
                            $(this).parent().remove();
                            $('#edita-revisao-' + tab_id).remove();
                            $(".nav-tabs li").children('a').last().click();
                        });
                        novo           = false;
                        edita          = true;
                        visualiza      = false;
                        imagesOnCanvas = [];
                        imgPin         = document.getElementById('imagem-pin');

                        revisao("edit");
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
    }
    $('.edita-revisao').on('click', function (e) {
        editaRevisao(e);
    });

// });


})();
function revisao(funcao){
    imgPin          = document.getElementById('imagem-pin');
    canvas          = document.getElementsByClassName('canvas-revisao-imagem')[0];
    canvas2D        = canvas.getContext('2d');
    divAreaInput    = document.getElementById('areaInput');
    dadosAlteracao  = [[],[],[],[],[]];
    newImgSelection = "" ;



    if(funcao=="new"){
        if(imgPin) imgPin.src = url_base + "/images/pins/azul/1.png";
        renderScene();

        $('#image-preview-clear').click(function () {
            console.log($(this));
            var img_preview = $(this).parents('#image-preview')[0];
            var preview_title = $(img_preview).find("#image-preview-input-title")[0];
            var preview_clear = $(img_preview).find('#image-preview-clear')[0];
            var preview_filename = $(img_preview).find('#image-preview-filename')[0];
            var preview_input = $(img_preview).find('#image-preview-input input:file')[0];

            $(img_preview).attr("data-content", "").popover('hide');
            $(preview_filename).val("");
            $(preview_clear).hide();
            $(preview_input).val("");
            $(preview_title).text("Procurar");
        });
        // Create the preview image
        $("#image-preview-input input:file").change(function () {
            // console.log($(this));
            // var img_input        = $(this);
            var img_preview = $(this).parents('#image-preview')[0];
            var preview_title = $(this).siblings("#image-preview-input-title")[0];
            var preview_clear = $(this).parents('#image-preview').find('#image-preview-clear')[0];
            var preview_filename = $(this).parents('#image-preview').find('#image-preview-filename')[0];
            var img = $('<img/>', {
                id: 'dynamic',
                width: 250,
                height: 200
            });
            var file = this.files[0];
            var reader = new FileReader();
            // Set preview image into the popover data-content
            reader.onload = function (e) {
                // console.log($(this));
                $(preview_title).text("Alterar"); // Vai pegar todos irmãos com as classes correspondente.
                $(preview_clear).show();
                // $(".image-preview-clear").show();
                $(preview_filename).val(file.name);
                // $(".image-preview-filename").val(file.name);
                img.attr('src', e.target.result);
                img.attr('width', e.target.result);

            //    $(img_preview).attr("data-content", $(img)[0].outerHTML).popover("show");

                var tempImg = $(img)[0];
                var cloneImg = tempImg.cloneNode(true);

                newImgSelection = e.target.result;


                requestAnimationFrame(renderScene);
            }
            reader.readAsDataURL(file);
        });

    } else if(funcao=="view"){

    	canvas          = document.getElementsByClassName('canvas-revisao-imagem')[0];
    	canvas2D        = canvas.getContext('2d');

        loadPin();
        
        renderScene();

    } else if (funcao=="edit")
    {
		imgSection = document.getElementById("img_final");
		imgBg      = new Image();
		imgBg.src  = imgSection.src;
		loadPin();

		  
		renderSceneEdit();

        function renderSceneEdit() {

        	imgBgEdit = imgBg;

        	requestAnimationFrame(renderSceneEdit);

        	canvas    = document.getElementsByClassName('canvas-revisao-imagem')[0];

    		canvas2D  = canvas.getContext('2d');

		    eventsCanvas();
		    
		    if(edita) {
		            
		        if(imgBgEdit.width > widthMaxImg){
		            var tempDifer = (imgBgEdit.width - widthMaxImg);
		            var tempPorc = tempDifer/imgBgEdit.width;
		            imgBgEdit.width = imgBgEdit.width - tempDifer;
		            imgBgEdit.height = imgBgEdit.height -  imgBgEdit.height*  tempPorc;
		        }

		        canvas.width = imgBgEdit.width;
		        canvas.height = imgBgEdit.height;

		        // limpa o canvas
		        canvas2D.clearRect(0,0,
		            canvas.width,
		            canvas.height
		        );

		        // desenha a imagem no canvas
		        canvas2D.drawImage(imgBgEdit, 4, 4, imgBgEdit.width, imgBgEdit.height);

		        for(var x = 0,len = imagesOnCanvas.length; x < len; x++) {

		            var obj = imagesOnCanvas[x];
		            canvas2D.drawImage(obj.image,obj.x,obj.y);

		        }

		    } 
		}

    }

}




function loadPin() {

    var colleX = document.getElementsByName("xPin");
    var colleY = document.getElementsByName("yPin");

 
    for (i = 0; i < colleX.length; i++) {

        showPin(colleX[i].value, colleY[i].value);
    }



    // imgPin         = document.getElementById('imagem-pin');
    imgPin.src = url_base + "/images/pins/azul/" + (imagesOnCanvas.length+1) + ".png";

    var tempArea =  "";

    requestAnimationFrame(renderScene);
}
function showPin(xPonto, yPonto) {
    
    var pinAtual = imagesOnCanvas.length+1;
    var pinProx = pinAtual+1;

    // cria um elemento img para o pin
    var image = document.createElement("img");

    //  image.id = 'imagem-pin-' + pinAtual;
    image.id = pinAtual;
    image.src = url_base + "/images/pins/azul/"+(pinAtual)+".png";

    // var canvasEdita  = document.getElementById("canvas-revisao");
    // chama o metodo contexto 2D
    canvas2D = canvas.getContext('2d');

    // Cria um array com os elementos que sao soltos no canvas
    imagesOnCanvas.push({
        // contexto 2D
        context: canvas2D,
        // imagem recém criada para o pin
        image: image,
        // posição x do elemento calculado pelo left do canvas e posição x do mouse
        x:xPonto,
        // posição y do elemento calculado pelo top do canvas e posição y do mouse
        y:yPonto,
        // width - tamanho 22 definido pelo tamanho do pin (png) usado
        width: 22,
        // height - tamanho 35 definido pelo tamanho do pin (png) usado
        height: 35,
        // id da imagem
        // id:e.dataTransfer.getData("image_id")
        id:pinAtual
    });        

    DownloadCanvas();
}

// Verifica se o ponto clicado esta ao alcance do obj
function isPointInRange(x,y,obj) {
    return !(x < obj.x ||
        x > obj.x + obj.width ||
        y < obj.y ||
        y > obj.y + obj.height);
}
function startMove(obj,downX,downY) {

    // pega o elemento canvas
    // var canvas = document.getElementsByClassName('canvas-revisao-imagem')[0];

    // pega a posição x e y do objeto
    var origX = parseInt(obj.x),
        origY = parseInt(obj.y);

    // adiciona função no evento onmousemove do canvas
    canvas.onmousemove = function(e) {
        // pega as coordenadas x e y do evento
        var moveX = e.offsetX,
            moveY = e.offsetY;

        // pega a diferença das coordenadas do objeto nos eventos de mousedown e mousemove
        var diffX = moveX-downX,
            diffY = moveY-downY;

 		obj.x = origX+diffX;
        obj.y = origY+diffY;

        var tempPinX = document.getElementById("hdnPontoX"+obj.id);
        var tempPinY = document.getElementById("hdnPontoY"+obj.id);
        tempPinX.value = obj.x;
        tempPinY.value = obj.y;

    }

    // adiciona função no evento onmouseup do canvas
    canvas.onmouseup = function(obj) {
        canvas.onmousemove = function(){};
    }
}
function eventsCanvas() {
    canvas.addEventListener('click', function(e) {
       console.log('canvas click');
    });
    
    // adiciona função no evento onmousedown do canvas
    canvas.onmousedown = function(e) {

        //pega as coordenadas x e y do evento
        var downX = e.offsetX,
            downY = e.offsetY;

        // percorre todas as imagens do canvas
        for(var x = 0,len = imagesOnCanvas.length; x < len; x++) {

            // pega o obj da posiçao x
            var obj = imagesOnCanvas[x];

            // verifica se o click nao esta no alcance de algum obj
            if(!isPointInRange(downX,downY,obj)) {
                // passa para a proxima iteração sem fazer o resto do código
                continue;
            }

            // senao caiu no IF, inicia o movimento
            startMove(obj,downX,downY);

            // interrompe o FOR
            break;

        }

    }

    canvas.ondblclick = function(e) {
        // pega a posição de x e y
        var downX = e.offsetX;
        downY = e.offsetY;

        // percorre todas as imagens do canvas
         for(var x = 0,len = imagesOnCanvas.length; x < len; x++) {

           // pega o obj da posiçao x
            var obj = imagesOnCanvas[x];

            console.log(obj);
            console.log(obj.toString());

            // verifica se o click nao esta no alcance de algum obj
            if(!isPointInRange(downX,downY,obj)) {
                // passa para a proxima iteração sem fazer o resto do código
                continue;
            }
            // senao caiu no IF, inicia o movimento
            var r = confirm("Você quer deletar esse ponto?");
            if(r==true) {

                if(obj.image.id < imagesOnCanvas.length)
                {
                    erro_info("Deletar Pin","Você não pode deletar o pin " + obj.image.id + " sem deletar os mais altos antes!" );
                }
                else
                {
                    var pinAtual = document.getElementById("pin-flag-"+obj.id);
                    var dataOrigin = pinAtual.getAttribute("data-origin");
                    var msgSuccess = true;
                    if(dataOrigin=="banco")
                    {
                        var dataUrl = pinAtual.getAttribute("data-url");
                        msgSuccess = false;
                        //ajax para deletar o pin

                         $.ajaxSetup({
                            headers: {
                            }
                        });

                        $.ajax({
                            type: 'GET',
                            url: dataUrl,
                            success: function (data) {
                                console.log('Sucesso: ' + data);
                                sucesso_info("Deletando Pin","deletando pin " + obj.id + " da tela");
                                return true;
                            },
                            error: function (data) {
                                console.log('Erro: ', data);
                                erro_info("Deletando Pin","deletando pin " + obj.id + " da tela");
                                return false;
                            }
                        });

                    }
                    if(msgSuccess) {
                        sucesso_info("Deletando Pin","deletando pin " + obj.id + " da tela");
                    }
                    imagesOnCanvas.splice(x,1);
                    destroyInputPonto(x+1);
                }
                DownloadCanvas();

            }

            // interrompe o FOR
            break;
        }

 //       txtPontoAtual.value = imagesOnCanvas.length+1;
        imgPin         = document.getElementById('imagem-pin');
        imgPin.src = url_base + "/images/pins/azul/" + (imagesOnCanvas.length+1) + ".png";

    }
}

function renderScene() {

    requestAnimationFrame(renderScene);

	canvas    = document.getElementsByClassName('canvas-revisao-imagem')[0];

    canvas2D  = canvas.getContext('2d');


    // download_img = function(el) {
    //     var image_gerada = canvas.toDataURL("image/jpg");
    //     image_gerada.name = "exemplo.jpg"; 
    //     el.href = image_gerada;

    //     document.getElementById("foto-gerada").value = image_gerada;
    // };

    // download_input = function(el) {
    //     var input_new = canvas.toDataURL("image/jpg");
    //     //input_new.name = "exemplo.jpg"; 
    //     input_new.value = input_new;
    // };


	eventsCanvas();

    if(edita) {
        
        imgSection = document.getElementById("img_final");
        imgBg      = new Image();
        imgBg.src  = imgSection.src;
        if(imgBg.width > widthMaxImg){
            var tempDifer = (imgBg.width - widthMaxImg);
            var tempPorc = tempDifer/imgBg.width;
            imgBg.width = imgBg.width - tempDifer;
            imgBg.height = imgBg.height -  imgBg.height*  tempPorc;
        }

        canvas.width = imgBg.width;
        canvas.height = imgBg.height;

        // limpa o canvas
        canvas2D.clearRect(0,0,
            canvas.width,
            canvas.height
        );

        // desenha a imagem no canvas
        canvas2D.drawImage(imgBg, 4, 4, imgBg.width, imgBg.height);

        for(var x = 0,len = imagesOnCanvas.length; x < len; x++) {

            var obj = imagesOnCanvas[x];
            canvas2D.drawImage(obj.image,obj.x,obj.y);

        }

    } else if(novo) {

        // cria um novo obj imagem e guarda o caminho numa variavel
        var srcImg = document.getElementById("img-revisao-final");
        var imgBg = new Image();
        
        //imgBg.src =  url_base + "/images/" + newImgSelection;
        imgBg.src = newImgSelection;

        if(imgBg.width > widthMaxImg)
        {
            var tempDifer = (imgBg.width - widthMaxImg);
            var tempPorc = tempDifer/imgBg.width;
            imgBg.width = imgBg.width - tempDifer;
            imgBg.height = imgBg.height -  imgBg.height*  tempPorc;

        }

        canvas.width = imgBg.width;
        canvas.height = imgBg.height;

        // limpa o canvas
        canvas2D.clearRect(0,0,
            canvas.width,
            canvas.height
        );

        //txtarea.value = "";
        // desenha a imagem no canvas
        canvas2D.drawImage(imgBg, 4, 4, imgBg.width, imgBg.height);

        for(var x = 0,len = imagesOnCanvas.length; x < len; x++) {

            var obj = imagesOnCanvas[x];

            canvas2D.drawImage(obj.image,obj.x,obj.y);
          //  txtarea.value += obj.id +" - " + obj.x + "\n";
        }
    
    }else if(visualiza){
        imgSection = document.getElementById("img_final");
        imgBg      = new Image();
        imgBg.src  = imgSection.src;

        if(imgBg.width > widthMaxImg){
            var tempDifer = (imgBg.width - widthMaxImg);
            var tempPorc = tempDifer/imgBg.width;
            imgBg.width = imgBg.width - tempDifer;
            imgBg.height = imgBg.height -  imgBg.height*  tempPorc;
        }

        canvas.width = imgBg.width;
        canvas.height = imgBg.height;

        // limpa o canvas
        canvas2D.clearRect(0,0,
            canvas.width,
            canvas.height
        );

        //txtarea.value = "";
        // desenha a imagem no canvas
        canvas2D.drawImage(imgBg, 4, 4, imgBg.width, imgBg.height);

        for(var x = 0,len = imagesOnCanvas.length; x < len; x++) {
            var obj = imagesOnCanvas[x];
            canvas2D.drawImage(obj.image,obj.x,obj.y);
        }
    }    
}

function allowDrop(e) {
    e.preventDefault();
}

// salva a posição relativa do mouse com a posição da img
function drag(e) {
    //store the position of the mouse relativly to the image position
    e.dataTransfer.setData("mouse_position_x",e.clientX - e.target.offsetLeft);
    e.dataTransfer.setData("mouse_position_y",e.clientY - e.target.offsetTop);

    e.dataTransfer.setData("image_id",e.target.id);
}

// solta o elemento na posição desejada 
function drop(e) {

    // Previne o evento default do elemento
    e.preventDefault();
    var pinAtual = imagesOnCanvas.length+1;
    var pinProx = pinAtual+1;

    // pega o id da imagem
    var image = document.createElement("img");
    //  image.id = 'imagem-pin-' + pinAtual;
    image.id = pinAtual;
    image.src = url_base + "/images/pins/azul/"+(pinAtual)+".png";

    // verifica a posição x e y do mouse
    var mouse_position_x = e.dataTransfer.getData("mouse_position_x");
    var mouse_position_y = e.dataTransfer.getData("mouse_position_y");

    // pega o elemento canvas
    // var canvas = document.getElementById('canvas-revisao-img');
    var canvas = e.currentTarget;
    // chama o metodo contexto 2D
    var ctx = canvas.getContext('2d');

    //insere no array os dados de um ponto de alteracao
    var posTempX = e.clientX - canvas.offsetLeft - mouse_position_x;   
    var posTempY = e.clientY - canvas.offsetTop - mouse_position_y;
    // Cria um array com os elementos que sao soltos no canvas
    imagesOnCanvas.push({
        // contexto 2D
        context: ctx,
        // imagem
        image: image,
        // posição x do elemento calculado pelo left do canvas e posição x do mouse
        x:posTempX,
        // posição y do elemento calculado pelo top do canvas e posição y do mouse
        y:posTempY,
        // width - tamanho 22 definido pelo tamanho do pin (png) usado
        width: 22,
        // height - tamanho 35 definido pelo tamanho do pin (png) usado
        height: 35,
        // id da imagem
        id:pinAtual
    });

    // Atualiza o valor do pin atual
     //    txtPontoAtual.value = pinProx;
    imgPin.src = url_base + "/images/pins/azul/" + pinProx + ".png";
    
    

    //criar estrutura para salvar no banco id ponto, x, y e texto;
    createInputPonto(imagesOnCanvas.length, posTempX, posTempY, imagesOnCanvas.length);

    DownloadCanvas();
}

// end function drop


 // função que cria os inputs e botoes ao arrastar pin para o canvas
function createInputPonto(ponto, tempX, tempY, id) {

    indexAlteracao++;

    dadosAlteracao = [[ponto, tempX, tempY, indexAlteracao, ""]];

    //    showArray(dadosAlteracao);

    // cria a div container que vai ficar dentro da div AreaInput(row)
    var containerInputPin = document.createElement("div");
    containerInputPin.setAttribute("class", "col-md-12");
    containerInputPin.setAttribute("id", "divPonto" + ponto);
    containerInputPin.setAttribute("data-pin", 0);

    divAreaInput.appendChild(containerInputPin);

    var linhaPinImgText = document.createElement("div");
    linhaPinImgText.setAttribute("class", "row");
    containerInputPin.appendChild(linhaPinImgText);

    // cria a coluna para o input
    var colunaInputText = document.createElement("div");
    colunaInputText.setAttribute("class", "col-md-12");
    linhaPinImgText.appendChild(colunaInputText);


    //inseri pin relativo a caixa
    var image = document.createElement("img");
    image.id = "pin"+ponto;
    image.setAttribute("class", "image-preview-filename pin");
    image.src = url_base + "/images/pins/azul/"+(ponto)+".png";
    colunaInputText.appendChild(image);

    //inseri campo id pin 
    var idInputHiddenMarcador = document.createElement("input");
    idInputHiddenMarcador.type = "Hidden";
    idInputHiddenMarcador.setAttribute("name", "pins[" + ponto+"][marcador_id]");
    //irmi
    idInputHiddenMarcador.id = "marcadorId-"+ponto;
    idInputHiddenMarcador.value = "0";
    colunaInputText.appendChild(idInputHiddenMarcador);

    var linhaPinText = document.createElement("div");
    linhaPinText.setAttribute("class", "row");
    linhaPinText.setAttribute("id", "RowPinX" + ponto);
    containerInputPin.appendChild(linhaPinText);


    // cria a coluna para o input
    var colunaInputText = document.createElement("div");
    colunaInputText.setAttribute("class", "col-md-3");
    linhaPinText.appendChild(colunaInputText);

    var colunaTxtPonto = document.createElement("div");
    colunaTxtPonto.setAttribute("class", "largura95 divTxtPonto");
    colunaInputText.appendChild(colunaTxtPonto);

    // cria input tipo texto para escrever as notas
    var inputText = document.createElement("textarea");
    inputText.setAttribute("class", "form-control image-preview-filename required-revisao");
    inputText.setAttribute("name", "pins[" + ponto+"][texto]");
    inputText.setAttribute("id", "txtPontoDesc" + ponto);
    inputText.setAttribute("required", "true");
    // inputText.setAttribute("style", "height:100px");
    inputText.setAttribute("rows", "10");
    colunaTxtPonto.appendChild(inputText);

    var spanAviso = document.createElement("span");
    //spanAviso.setAttribute("class", "image-preview-span-num");
    // spanAviso.innerText = " Máximo de 190 caracteres por PIN ";
    colunaTxtPonto.appendChild(spanAviso);


    //inpunt flag origin pin (banco/front)
    var inputHiddenPingFlag = document.createElement("input");
    inputHiddenPingFlag.setAttribute("type", "hidden");
    inputHiddenPingFlag.setAttribute("name", "pin_flag");
    inputHiddenPingFlag.setAttribute("id", "pin-flag-" + ponto);
    inputHiddenPingFlag.setAttribute("value", "front");
    inputHiddenPingFlag.setAttribute("data-url", "");
    inputHiddenPingFlag.setAttribute("data-origin", "front");
    colunaTxtPonto.appendChild(inputHiddenPingFlag);


    // // cria botao + para add input tipo file ao inserir pin no canvas
    // var btAdd = document.createElement("button");
    // btAdd.setAttribute("class", "btn btn-success");
    // btAdd.setAttribute("title", "Adicionar Arquivo");
    // btAdd.setAttribute("id", "bt-add-arquivo");
    // btAdd.innerText = " + ";
    // btAdd.addEventListener('click', function(e) {
    //   e.preventDefault();
    //   containerInputPin.appendChild(criarInputFile(ponto));
    //   // transformaFilePreview();
    // });
    // colunaBtn.appendChild(btAdd);
    

    var colunaDoInput = document.createElement("div");
    colunaDoInput.setAttribute("class", "col-md-3");
    colunaDoInput.setAttribute("id", "colPinX" + ponto);
    linhaPinText.appendChild(colunaDoInput);
    
    //cria um input do tipo file assim que arrastar um pin
    //old
    tira_arquivos = document.getElementById("tira-arquivos").value;
    if(tira_arquivos!=1) {
        linhaPinText.appendChild(criarInputFile(ponto, true));
    }
    var colunaDoPreview = document.createElement("div");
    colunaDoPreview.setAttribute("class", "col-md-6");
    colunaDoPreview.setAttribute("id", "colpreview"+ponto);
    linhaPinText.appendChild(colunaDoPreview);

    // Roda o método para add funções ao input recém criado: fonte arquivos.js
    //transformaFilePreview();

    // cria o input hidden ao inserir pin no canvas
    var x = document.createElement("input");
    x.setAttribute("type", "hidden");
    x.setAttribute("id", "hdnPontoX" + ponto);
    x.setAttribute("name", "pins[" + ponto+"][x]");
    x.setAttribute("value", tempX);
    containerInputPin.appendChild(x);

    //input Hidden Y Ponto
    var y = document.createElement("input");
    y.setAttribute("type", "hidden");
    y.setAttribute("id", "hdnPontoY" + ponto);
    y.setAttribute("name", "pins[" + ponto+"][y]");
    y.setAttribute("value", tempY);
    containerInputPin.appendChild(y);

    //input Hidden ID Ponto
    var inputIDTemp = document.createElement("input");
    inputIDTemp.setAttribute("type", "hidden");
    inputIDTemp.setAttribute("id", "hdnIdPonto" + ponto);
    inputIDTemp.setAttribute("name", "pins[" + ponto+"][ponto]");
    inputIDTemp.setAttribute("value", ponto);

    containerInputPin.appendChild(inputIDTemp);

    var hrInput = document.createElement("hr");
    containerInputPin.appendChild(hrInput);
} // end createInputPonto

// cria o input file ao inserir um pin no canvas
function criarInputFile(ponto, criaBotaoAdd) {
    // Cria a linha do Input e dos seus elementos
    // var linhaDoInput = document.createElement("div");
    // linhaDoInput.setAttribute("class", "row");

    // Coluna geral do input pegando 100% da largura da linha

    var ColPin = document.getElementById("colPinX" + ponto);

    // Cria o container do input file estilizado
    var divInputFile = document.createElement("div");
    divInputFile.setAttribute("class", "input-group image-preview margemB5");
    ColPin.appendChild(divInputFile);

    var nextNameNum = 0;
    var addDiv = document.getElementById('divPonto'+ponto);
    var addNum = addDiv.getAttribute("data-pin");

    var sumNum = parseInt(addNum) +1;
    nextNameNum = ponto +"-"+sumNum;
    addDiv.setAttribute("data-pin", sumNum);

    //inserie span com numeiro do input
    var spanNumInput = document.createElement("span");
    spanNumInput.setAttribute("class", "image-preview-span-num");
    spanNumInput.innerText = sumNum + "  ";
    divInputFile.appendChild(spanNumInput);

    // cria input text do tipo disabled para o nome do arquivo

    var inputFile = document.createElement("input");
     //    inputFile.setAttribute("disabled", "disabled");
    inputFile.setAttribute("class", "form-control image-preview-filename largura90");
    inputFile.setAttribute("type", "text");
    inputFile.setAttribute("placeholder", "Nenhum arquivo selecionado");
    inputFile.setAttribute("id", "arquivo-preview-" + nextNameNum);
    inputFile.setAttribute("data-title", nextNameNum);
    inputFile.setAttribute("name", "caminho_arquivo["+ponto+"][]");
    inputFile.setAttribute("data-url", "");
    inputFile.setAttribute("data-origin", "front");

    divInputFile.appendChild(inputFile);


    // cria o span de container dos botões e do input file em si
    var spanInput = document.createElement("span");
    spanInput.setAttribute("class", "input-group-btn largura ");
    divInputFile.appendChild(spanInput);

    // Cria o preview da imagem que é disparado quando carrega um arquivo
    var divImagemPreview = document.createElement("div");
    divImagemPreview.setAttribute("class", "btn btn-default image-preview-input");
    spanInput.appendChild(divImagemPreview);

    // cria button procurar arquivo e o file input
    var spanInputBtn = document.createElement("span");
    spanInputBtn.setAttribute("class", "glyphicon glyphicon-folder-open");
    divImagemPreview.appendChild(spanInputBtn);

    var spanGlyphicon = document.createElement("span");
    spanGlyphicon.setAttribute("class", "image-preview-input-title");
    spanGlyphicon.innerText = " Procurar";
    divImagemPreview.appendChild(spanGlyphicon);


    //data-pin

    // cria input tipo file
    var fileArquivo = document.createElement("input");
    fileArquivo.setAttribute("type", "file");
    fileArquivo.setAttribute("id", "input-arquivo-" + nextNameNum);
    fileArquivo.setAttribute("name", "input-arquivo["+ponto+"][]");
    fileArquivo.setAttribute("accept", "*");

    //add evento change no file criado.

    fileArquivo.addEventListener("change",function () {
        // console.log($(this));
        // var img_input        = $(this);
        var img_preview = $(this).parents('.image-preview')[0];
        var preview_title = $(this).siblings(".image-preview-input-title")[0];
        var preview_clear = $(this).parents('.image-preview').find('.image-preview-clear')[0];
        var preview_filename = $(this).parents('.image-preview').find('.image-preview-filename')[0];
        var img = $('<img/>', {
            id: 'dynamic',
            width: 250,
            height: 200
        });
        var file = this.files[0];
        var reader = new FileReader();
        // Set preview image into the popover data-content
        reader.onload = function (e) {
            // console.log($(this));
            $(preview_title).text(" Alterar"); // Vai pegar todos irmãos com as classes correspondente.
            $(preview_clear).show();
            // $(".image-preview-clear").show();
            var txtPontoDesc =  document.getElementById("txtPontoDesc" + ponto);
            if(txtPontoDesc !="")
            {
            //  txtPontoDesc.value = txtPontoDesc.value +"\n"+ file.size;
            }
            else
            {
                //txtPontoDesc.value = file.size;
            }

            $(preview_filename).val(file.name);
            
            // $(".image-preview-filename").val(file.name);
            console.log(e.target.result);
            img.attr('src', e.target.result);
            //$(img_preview).attr("data-content", $(img)[0].outerHTML).popover("show");


            var divImagemPreviewFixo = document.createElement("div");
            divImagemPreviewFixo.setAttribute("class", "btn btn-default div-img-preview");
            divImagemPreviewFixo.setAttribute("id", "div_img_preview" + ponto +"-"+sumNum);

            var spanNumInput = document.createElement("span");
            spanNumInput.setAttribute("class", "image-preview-span-num-title");
            spanNumInput.setAttribute("id", "img_preview-title" + ponto +"-"+sumNum);
            
            spanNumInput.innerText = sumNum;

            var tempImg = $(img)[0];
            var cloneImg = tempImg.cloneNode(true);
            if(document.getElementById("div_img_preview" + ponto +"-"+sumNum) != null)
            {
                document.getElementById("div_img_preview" + ponto +"-"+sumNum).remove();
            }
            cloneImg.setAttribute("id", "img_preview" + ponto +"-"+sumNum);
            cloneImg.setAttribute("class", "image-preview-fixo");
            cloneImg.setAttribute("style", "width:200px; height: 200px");

            //pega colpreview e adiciona a div, numero e imagem de preview
            var colPreview = document.getElementById("colpreview" + ponto);
            colPreview.appendChild(divImagemPreviewFixo);
            divImagemPreviewFixo.appendChild(spanNumInput);
            divImagemPreviewFixo.appendChild(cloneImg);
            
        }
        reader.readAsDataURL(file);
    });
    
    divImagemPreview.appendChild(fileArquivo);


    // Cria botão de limpar a seleção
    var buttonImage = document.createElement("button");
    buttonImage.setAttribute("class", "btn btn-default image-preview-clear");
    buttonImage.setAttribute("type", "button");
    spanInput.appendChild(buttonImage);

    //add event clear no botão limpar

    // Cria span com icone para remover
    var spanGlyphiconRemove = document.createElement("span");
    spanGlyphiconRemove.setAttribute("class", "glyphicon glyphicon-remove");
    spanGlyphiconRemove.innerText = " Limpar";
    buttonImage.appendChild(spanGlyphiconRemove);


    buttonImage.addEventListener("click",function () {
        console.log($(this));
        var img_preview = $(this).parents('.image-preview')[0];
        var preview_title = $(img_preview).find(".image-preview-input-title")[0];
        var preview_clear = $(img_preview).find('.image-preview-clear')[0];
        var preview_filename = $(img_preview).find('.image-preview-filename')[0];
        var preview_input = $(img_preview).find('.image-preview-input input:file')[0];
        var file = this.files[0];
        
        var divImagePreviewTemp = "div_img_preview" + $(preview_filename).attr("data-title");
        if(document.getElementById(divImagePreviewTemp)) {
            document.getElementById(divImagePreviewTemp).remove();
        }
        $(img_preview).attr("data-content", "").popover('hide');
        $(preview_filename).val("");
        $(preview_clear).hide();
        $(preview_input).val("");
        $(preview_title).text(" Procurar");
    });


    //get div containerInputPin
    var ctnInputPin = document.getElementById("colPinX" + ponto);

    if(criaBotaoAdd) {

        // cria botao + para add input tipo file ao inserir pin no canvas
        var btAdd = document.createElement("button");
        btAdd.setAttribute("type", "button");
        btAdd.setAttribute("class", "btn btn-success");
        btAdd.setAttribute("style", "margin-left: 0px;");
        btAdd.setAttribute("title", "Adicionar Arquivo");
        btAdd.setAttribute("id", "bt-add-arquivo");
        btAdd.innerText = " + ";
        btAdd.addEventListener('click', function(e) {
            e.preventDefault();
            criarInputFile(ponto, false);
            // transformaFilePreview();
        });
        spanInput.appendChild(btAdd);
    }

    $(document).on('click', '.close', function () {
        console.log($(this));
        var popover = $(this).parents('.popover')[0];
        var image_preview = $(popover).siblings('.image-preview')[0];
        $(image_preview).popover('hide');
        // Hover befor close the preview
        $(image_preview).hover(
            function () {
            //    $(image_preview).popover('show');
            },
            function () {
                //$(image_preview).popover('hide');
            }
        );
    });

    // Create the close button
    var closebtn = $('<button/>', {
        type: "button",
        text: 'x',
        id: 'close-preview',
        style: 'font-size: initial;',
        class: 'close' 
    });
    closebtn.attr("class", "close pull-right");

   
     // Set the popover default content
    $(divInputFile).popover({
        trigger: 'manual',
        html: true,
        title: "<strong>Preview</strong>" + $(closebtn)[0].outerHTML,
        content: "Nenhuma imagem selecionada.",
        placement: 'bottom'
    });

    return ColPin;
}

function destroyInputPonto(ponto) {

    dadosAlteracao.splice(ponto,1);
    showArray(dadosAlteracao);

    // for (i = 0; i < dadosAlteracao.length; i++) {
        // dadosAlteracao[i,i,i,i] = {[i, 0,0, i]};
    // }

    var element = document.getElementById("divPonto" + ponto);
    element.parentNode.removeChild(element);

    showArray(dadosAlteracao);

    DownloadCanvas();
}

function updatePontoXY(ponto){
}


function showArray(varArray) {

    for(i=0;i<varArray.length;i++){
        console.log(varArray[i][0] + " / " + varArray[i][1] + " / " + varArray[i][2] + " / " + varArray[i][3]);
    }
}


function AddFilePin(ponto, pontoMidia, addButton) {
   
    var fileArquivo = document.getElementById("input-arquivo-"+ponto+"-" +pontoMidia);
    fileArquivo.addEventListener("change",function ()
    {
        // console.log($(this));
        // var img_input        = $(this);
        var img_preview = $(this).parents('.image-preview')[0];
        var preview_title = $(this).siblings(".image-preview-input-title")[0];
        var preview_clear = $(this).parents('.image-preview').find('.image-preview-clear')[0];
        var preview_filename = $(this).parents('.image-preview').find('.image-preview-filename')[0];
        var img = $('<img/>', {
            id: 'dynamic',
            width: 250,
            height: 200
        });
        var file = this.files[0];
        var reader = new FileReader();
        // Set preview image into the popover data-content
        reader.onload = function (e) {
            // console.log($(this));
            $(preview_title).text(" Alterar"); // Vai pegar todos irmãos com as classes correspondente.
            $(preview_clear).show();
            // $(".image-preview-clear").show();
            var txtPontoDesc =  document.getElementById("txtPontoDesc" + ponto);

            $(preview_filename).val(file.name);
            
            // $(".image-preview-filename").val(file.name);
            console.log(e.target.result);
            img.attr('src', e.target.result);
            //$(img_preview).attr("data-content", $(img)[0].outerHTML).popover("show");


            var divImagemPreviewFixo = document.createElement("div");
                divImagemPreviewFixo.setAttribute("class", "btn btn-default div-img-preview");
                divImagemPreviewFixo.setAttribute("id", "div_img_preview" + ponto +"-"+pontoMidia);

            var spanNumInput = document.createElement("span");
                spanNumInput.setAttribute("class", "image-preview-span-num-title");
                spanNumInput.setAttribute("id", "img_preview-title" + ponto +"-"+pontoMidia);
            
            spanNumInput.innerText = pontoMidia;

            var tempImg = $(img)[0];
            var cloneImg = tempImg.cloneNode(true);
            if(document.getElementById("div_img_preview" + ponto +"-"+pontoMidia) != null)
            {
                document.getElementById("div_img_preview" + ponto +"-"+pontoMidia).remove();
            }
            cloneImg.setAttribute("id", "img_preview" + ponto +"-"+pontoMidia);
            cloneImg.setAttribute("class", "image-preview-fixo");
            cloneImg.setAttribute("style", "width:200px; height: 200px");

            //pega colpreview e adiciona a div, numero e imagem de preview
            var colPreview = document.getElementById("colpreview" + ponto);
            colPreview.appendChild(divImagemPreviewFixo);
            divImagemPreviewFixo.appendChild(spanNumInput);
            divImagemPreviewFixo.appendChild(cloneImg);
            
        }
        reader.readAsDataURL(file);

        
    });
    
    if(addButton) {
        var btAdd = document.getElementById("bt-add-arquivo-"+ponto+"-"+pontoMidia);

        btAdd.addEventListener('click', function(e) {
            e.preventDefault();
            criarInputFile(ponto, false);
            // transformaFilePreview();
        });
    }

    var buttonImage = document.getElementById("buttom_image_pin_clear-"+ponto+"-" +pontoMidia);
    buttonImage.addEventListener("click",function () {
        console.log($(this));
        var img_preview = $(this).parents('.image-preview')[0];
        var preview_title = $(img_preview).find(".image-preview-input-title")[0];
        var preview_clear = $(img_preview).find('.image-preview-clear')[0];
        var preview_filename = $(img_preview).find('.image-preview-filename')[0];
        var preview_input = $(img_preview).find('.image-preview-input input:file')[0];

        var pinAtual = document.getElementById("arquivo-preview-"+ponto+"-"+pontoMidia);
        var dataOrigin = pinAtual.getAttribute("data-origin");
        if(dataOrigin=="banco")
        {
            var dataUrl = pinAtual.getAttribute("data-url");
            //ajax para deletar o pin
             $.ajaxSetup({
                headers: {
                }
            });

            $.ajax({
                type: 'GET',
                url: dataUrl,
                success: function (data) {
                    console.log('Sucesso: ' + data);
                    sucesso_info("Deletando Imagem"," Imagem deletada");
                    return true;
                },
                error: function (data) {
                    console.log('Erro: ', data);
                    erro_info("Erro deletar imagem","Erro ao deletar a imagem");
                    return false;
                }
            });

        }


        if(document.getElementById("div_img_preview" + ponto +"-"+pontoMidia) != null)
        {
                document.getElementById("div_img_preview" + ponto +"-"+pontoMidia).remove();
        }

        $(img_preview).attr("data-content", "").popover('hide');
        $(preview_filename).val("");
        $(preview_clear).hide();
        $(preview_input).val("");
        $(preview_title).text(" Procurar");
    });

}


function DownloadCanvas()
{
   
    tira_arquivos = document.getElementById("tira-arquivos").value;
    //Tirado o if de tira_arquivos - 20/20/2020
    // if(tira_arquivos==1) {
        var canvas = document.getElementsByClassName('canvas-revisao-imagem')[0];
        var image_gerada = canvas.toDataURL("image/jpg");
            document.getElementById("foto-gerada").href=image_gerada;
            document.getElementById("foto-gerada").value = image_gerada;
        console.log(image_gerada);
     
    // }
    
}


function textCounter(field, countfield, maxlimit) {
    if (field.value.length > maxlimit)
    field.value = field.value.substring(0, maxlimit);
    else 
    countfield.value = maxlimit - field.value.length;
}

function ocultarSubmit(form) {
    var ocultarBotao = false;
    $(form).find('.required-revisao').each(function(){
        // console.log($(this));
        // alert($(this).attr('class') + " - " + $(this).val());
        if(!$(this).val()){
            ocultarBotao = false;
            return false;
        }
        ocultarBotao = true;
    });

    if(ocultarBotao){
        var x = document.getElementById("revisao-avaliacao-submit");
        x.style.display = "none";
        return true;
    }
    else{
        return false;
    }
}

function controleSubmitFormRevisao(form) {
    console.log("passou aqui");
    
    if(ocultarSubmit(form)) {
        DownloadCanvas();
    }
}
