
////////////////////////

(function() {
    var requestAnimationFrame =
        window.requestAnimationFrame       || window.mozRequestAnimationFrame ||
        window.webkitRequestAnimationFrame || window.msRequestAnimationFrame;

    window.requestAnimationFrame = requestAnimationFrame;
})();

var imagesOnCanvasVisual = [];

var indexAlteracao = 0;

var widthMaxImg = 1280;


//pega o id da imagem do pin e passa o caminho dos pins em png
var imgPin = document.getElementById('imagem_pin_visual');

var imgSectionVisual;


var textPontos =document.getElementById("textPontos");
//requestAnimationFrame(renderScene);

// Aqui renderiza a cena e carrega a imagem do bg
function renderSceneVisualizar() {

    requestAnimationFrame(renderSceneVisualizar);

    // pega o elemento canvas
    var canvasVisual  = document.getElementById("canvas_revisao_visual_img");
    // chama o metodo contexto 2D
    var contextVisual = canvasVisual.getContext('2d');

    var imgBg = new Image();
    
    //imgBg.src =  url_base + "/images/" + imgSectionVisual;
    imgBg.src =   imgSectionVisual.src;

        //alert(canvas.width);
    if(imgBg.width > widthMaxImg)
    {
        var tempDifer = (imgBg.width - widthMaxImg);
        var tempPorc = tempDifer/imgBg.width;
        imgBg.width = imgBg.width - tempDifer;
        imgBg.height = imgBg.height -  imgBg.height*  tempPorc;

    }

    canvasVisual.width = imgBg.width;
    canvasVisual.height = imgBg.height;


    // limpa o canvas
    contextVisual.clearRect(0,0,
        canvasVisual.width,
        canvasVisual.height
    );

    //txtarea.value = "";
    // desenha a imagem no canvas
    contextVisual.drawImage(imgBg, 4, 4, imgBg.width, imgBg.height);

    for(var x = 0,len = imagesOnCanvasVisual.length; x < len; x++) {
        var obj = imagesOnCanvasVisual[x];

        contextVisual.drawImage(obj.image,obj.x,obj.y);
    }
}




function loadPin()
{

    var colleX = document.getElementsByName("xPin");
    var colleY = document.getElementsByName("yPin");

 
    for (i = 0; i < colleX.length; i++) {

        showPin(colleX[i].value, colleY[i].value);
    }
    requestAnimationFrame(renderSceneVisualizar);
}


function showPin(xPonto, yPonto)
{
    var pinAtual = imagesOnCanvasVisual.length+1;
   
    var pinProx = pinAtual+1;

    // pega o id da imagem
    var image = document.createElement("img");
    //  image.id = 'imagem-pin-' + pinAtual;
    image.id = pinAtual;
    image.src = url_base + "/images/pins/azul/"+(pinAtual)+".png";

    var canvasVisual  = document.getElementById("canvas_revisao_visual_img");
    // chama o metodo contexto 2D
    var ctxVisual = canvasVisual.getContext('2d');

    // Cria um array com os elementos que sao soltos no canvas
    imagesOnCanvasVisual.push({
        // contexto 2D
        context: ctxVisual,
        // imagem
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

    console.log(imagesOnCanvasVisual);

}

function loadImage()
{
    imgSectionVisual = document.getElementById("img_final_visual");
    requestAnimationFrame(renderSceneVisualizar);
    loadPin();
 
}