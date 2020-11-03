/*mexido ate sexta 06/12/2019 17:50


*/

////////////////////////

(function() {
  var requestAnimationFrame = window.requestAnimationFrame || window.mozRequestAnimationFrame ||
  window.webkitRequestAnimationFrame || window.msRequestAnimationFrame;
  window.requestAnimationFrame = requestAnimationFrame;
})();

var dadosAlteracao = new Array([]);

var imagesOnCanvas = [];

var indexAlteracao = 0;

//pega elemento text area pontos
  var txtarea = document.getElementById('pontos');

  //input text valor do próximo ponto
  var txtPontoAtual = document.getElementById('pontoAtual');


  txtPontoAtual.value = imagesOnCanvas.length+1;


  var divAreaInput = document.getElementById('areaInput');

// Aqui renderiza a cena e carrega a imagem do bg
function renderScene() {

  requestAnimationFrame(renderScene);
  // pega o elemento canvas
  var canvas  = document.getElementById("canvas-revisao-img");
  // chama o metodo contexto 2D
  var context = canvas.getContext('2d');

  // cria um novo obj imagem e guarda o caminho numa variavel
  var imgBg = new Image();
  imgBg.src =  'http://fullfreela.local/images/interior_teste.jpg';
  //imgBg.src = url('/images/interior_teste.jpg');

  // limpa o canvas
  context.clearRect(0,0,
    canvas.width,
    canvas.height
  );

  txtarea.value ="";
  // desenha a imagem no canvas
  context.drawImage(imgBg, 10, 10, 960,540);   

   for(var x = 0,len = imagesOnCanvas.length; x < len; x++) {

     var obj = imagesOnCanvas[x];

     context.drawImage(obj.image,obj.x,obj.y);
     txtarea.value += obj.id +" - " + obj.x + "\n";

   }

}

// executa a função renderScene
requestAnimationFrame(renderScene);

window.addEventListener("load",function() {

  //pega o elemento canvas
  var canvas = document.getElementById('canvas-revisao-img');

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
      var r = confirm("Você quer deletar esse ponto?");
      if(r==true) {
        alert("deleta item da tela " + obj.id);
        imagesOnCanvas.splice(x,1);

        destroyInputPonto(x+1);
      }

      // interrompe o FOR
       break;
    }

    txtPontoAtual.value = imagesOnCanvas.length+1;
             
  }

},false);// end load EventListener


// Move o objeto
function startMove(obj,downX,downY) {

  // pega o elemento canvas
  var canvas = document.getElementById('canvas-revisao-img');

  // pega a posição x e y do objeto
  var origX = obj.x, 
      origY = obj.y;

  // adiciona função no evento onmousemove do canvas
  canvas.onmousemove = function(e) {
    // pega as coordenadas x e y do evento
    var moveX = e.offsetX, 
        moveY = e.offsetY;

    // pega a diferença das coordenadas do objeto nos eventos de mousedown e mousemove
    var diffX = moveX-downX, 
        diffY = moveY-downY;

    // atribui ao objeto a soma da sua posição inicial mais a diferença calculada   
    obj.x = origX+diffX;
    obj.y = origY+diffY;
  }

  // adiciona função no evento onmouseup do canvas
  canvas.onmouseup = function() {
   
    canvas.onmousemove = function(){};
  }

}


// Verifica se o ponto clicado esta ao alcance do obj
function isPointInRange(x,y,obj) {
  return !(x < obj.x ||
    x > obj.x + obj.width ||
    y < obj.y ||
    y > obj.y + obj.height);
}

// Previne o funcionamento default do eleemnto clicado
function allowDrop(e) {
  e.preventDefault();
}

// salva a posição relativa do mouse com a posição da img
function drag(e) {
  //store the position of the mouse relativly to the image position
  e.dataTransfer.setData("mouse_position_x",e.clientX - e.target.offsetLeft );
  e.dataTransfer.setData("mouse_position_y",e.clientY - e.target.offsetTop  );

  e.dataTransfer.setData("image_id",e.target.id);
}

// solta o elemento na posição desejada 
function drop(e) {

  // Previne o evento default do elemento
  e.preventDefault();

  // pega o id da imagem
  var image = document.getElementById( e.dataTransfer.getData("image_id"));

  // verifica a posição x e y do mouse
  var mouse_position_x = e.dataTransfer.getData("mouse_position_x");
  var mouse_position_y = e.dataTransfer.getData("mouse_position_y");

  // pega o elemento canvas
  var canvas = document.getElementById('canvas-revisao-img');
  // chama o metodo contexto 2D
  var ctx = canvas.getContext('2d');

          
  // Cria um array com os elementos que sao soltos no canvas
  imagesOnCanvas.push({
    // contexto 2D
    context: ctx,
    // imagem  
    image: image,  
    // posição x do elemento calculado pelo left do canvas e posição x do mouse
    x:e.clientX - canvas.offsetLeft - mouse_position_x,
    // posição y do elemento calculado pelo top do canvas e posição y do mouse
    y:e.clientY - canvas.offsetTop - mouse_position_y,
    // width
    width: image.offsetWidth,
    // height
    height: image.offsetHeight,
    // id da imagem 
    id:e.dataTransfer.getData("image_id")
  });

  txtPontoAtual.value = imagesOnCanvas.length+1;

  //insere no array os dados de um ponto de alteracao
  var posTempX = e.clientX - canvas.offsetLeft - mouse_position_x;
  var posTempY = e.clientY - canvas.offsetTop - mouse_position_y;


  createInputPonto(imagesOnCanvas.length, posTempX, posTempY, imagesOnCanvas.length);
  //criar estrutura para salvar no banco id ponto, x, y e texto;

}

function deletePoint(e) {
  alert("teste");
}


function createInputPonto(ponto, tempX, tempY, id) {
  
  indexAlteracao++;

  // var valueToPush = new Array();
  // valueToPush["ponto"] = ponto;
  // valueToPush["tempx"] = tempX;
  // valueToPush["tempoy"] = tempY;
  // valueToPush["id"] = indexAlteracao;
  // valueToPush["desc"] = "";

  dadosAlteracao.push(ponto, tempX, tempY, indexAlteracao, "");

//  alert(dadosAlteracao.length);

  showArray(dadosAlteracao);

  areaInput.innerHtml = "";

  for(i=0;i<dadosAlteracao.length;i++) {

    var obj = dadosAlteracao[i][0];
    alert(i +   " - " + dadosAlteracao[i][0]);
   
   // if(i!=obj.ponto) {
      var divTemp = document.createElement("DIV");
      divTemp.setAttribute("id", "divPonto"+ponto);
      divAreaInput.appendChild(divTemp);  

      // var labelTemp = document.createElement("LABEL");
      // var t = document.createTextNode("Descrição Alteração " + ponto);     
      // labelTemp.appendChild(t);
      // divAreaInput.appendChild(labelTemp);   
     
      // //div dados Ponto
      //input text Ponto Desc 
      var inputTemp = document.createElement("INPUT");
      inputTemp.setAttribute("type", "text");
      inputTemp.setAttribute("id", "txtPontoDesc"+ponto);
      divTemp.appendChild(inputTemp);  

      //input Hidden X Ponto 
      var x = document.createElement("INPUT");
      x.setAttribute("type", "hidden");
      x.setAttribute("id", "hdnPontoX"+ponto);
      x.setAttribute("value", tempX);
      divTemp.appendChild(x);  

      //input Hidden Y Ponto 
      var y = document.createElement("INPUT");
      y.setAttribute("type", "hidden");
      y.setAttribute("id", "hdnPontoY"+ponto);
      y.setAttribute("value", tempY);
      divTemp.appendChild(y);  
      
      //input Hidden ID Ponto 
      var inputIDTemp = document.createElement("INPUT");
      inputIDTemp.setAttribute("type", "hidden");
      inputIDTemp.setAttribute("id", "hdnIdPonto"+ponto);
      inputIDTemp.setAttribute("value", indexAlteracao);

      divTemp.appendChild(inputIDTemp);
   // }
  }


}

function destroyInputPonto(ponto) {

  dadosAlteracao.splice(ponto,1);
  showArray(dadosAlteracao);

  for (i = 0; i < dadosAlteracao.length; i++) {
   // dadosAlteracao[i,i,i,i] = {[i, 0,0, i]};
  }

  var element = document.getElementById("divPonto"+ponto);
      element.parentNode.removeChild(element);

  showArray(dadosAlteracao);
}

function updatePontoXY(ponto)
{

}


function showArray(varArray) {
   for(i=0;i<varArray.length;i++) {

      console.log(varArray[i][0] + " / " + varArray[i][1] + " / " + varArray[i][2] + " / " + varArray[i][3]);
    }
    
}