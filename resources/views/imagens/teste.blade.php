<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <title>Testando o Canvas</title>
    <meta charset="utf-8" />
    

    <style type="text/css">
      
        body {
          background: #111;
          color: #f8f8f8;
        }
        canvas {
          background: #f8f8f8;
          padding: 0;
          margin: 0 auto;
          margin-bottom: 1rem;
          display: block;
        }

    </style>
  </head>

  <body style='background-color:#3a6d90;'>
      
      <div>
        <div>
            <img id="img1" draggable="true" ondragstart="drag(event)" onclick="deletePoint(event)" src='{{ asset('images/pin.png') }}'>
            <input type="tex" name="pontoAtual" value="0" id="pontoAtual">

        </div>

          <canvas id="canvas-revisao-img" width="1000" height="600" ondrop="drop(event)" ondragover="allowDrop(event)"></canvas>

          <textarea id="pontos" rows="10">teste</textarea>
          <div id="areaInput">

          </div> 

      </div>
        
      <script type="text/javascript" src="{{ asset('js/funcoes_revisao.js') }}"></script>
  </body>

</html>