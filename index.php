<?php

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Envío de incidencias - DPL Canarias</title>
  <link rel="stylesheet" href="/css/estilo.css">
</head>


<body>

<div class="con_principal">
  <div class="con_interior">
    <img class="logo_formulario" src="/img/logo.png" alt="DPL Canarias">
    <h1 class="txt_h1">Envío de Incidencias</h1>
    <div class="con_formulario">
      <form name="form_empresas" action="/enviar.php" method="post">
        Nombre de la empresa
        <br>
        <input type="text" class="input_txt" name="nombre" id="" value="">
        <br>
        Dirección
        <br>
        <input type="text" class="input_txt" name="direccion" id="" value="">
        <br>
        Teléfono
        <br>
        <input type="tel" class="input_txt" name="telefono" id="" value="">
        <br>
        Descripción
        <br>
        <textarea type="text" class="input_textarea" name="descripcion"></textarea>
        Email Destinatario
        <br>
        <input type="email" class="input_txt" name="email" id="" value="">
        <br>
        <br>      
        <button type="submit" class="btn1">
          Enviar
        </button>
      </form>
    </div>
  </div>
</div>


</body>
</html>
