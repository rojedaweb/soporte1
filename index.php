<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Envío de incidencias - DPL Canarias</title>
  <link rel="stylesheet" href="/css/estilo.css?v=3">
</head>
<body class="pagina_formulario">
  <main class="con_principal">
    <section class="con_interior tarjeta_formulario" aria-labelledby="titulo_formulario">
      <header class="cabecera_formulario">
        <img class="logo_formulario" src="/img/logo.png" width="112" height="112" alt="DPL Canarias">
        <div>
          <p class="nombre_servicio">Soporte técnico</p>
          <h1 id="titulo_formulario" class="txt_h1">Envío de incidencias</h1>
          <p class="texto_intro">Completa los datos para registrar y enviar el aviso.</p>
        </div>
      </header>

      <form class="formulario_con" name="form_empresas" action="/enviar.php" method="post">
        <div class="rejilla_formulario">
          <div class="campo_formulario">
            <label for="nombre">Nombre de la empresa <span aria-hidden="true">*</span></label>
            <input type="text" class="input_txt" name="nombre" id="nombre" autocomplete="organization" required>
          </div>

          <div class="campo_formulario">
            <label for="telefono">Teléfono</label>
            <input type="tel" class="input_txt" name="telefono" id="telefono" autocomplete="tel">
          </div>

          <div class="campo_formulario campo_completo">
            <label for="direccion">Dirección</label>
            <input type="text" class="input_txt" name="direccion" id="direccion" autocomplete="street-address">
          </div>

          <div class="campo_formulario campo_completo">
            <label for="descripcion">Descripción de la incidencia</label>
            <textarea class="input_textarea" name="descripcion" id="descripcion" rows="5"></textarea>
          </div>

          <div class="campo_formulario campo_completo">
            <label for="email">Email destinatario <span aria-hidden="true">*</span></label>
            <input type="email" class="input_txt" name="email" id="email" autocomplete="email" aria-describedby="ayuda_email" required>
            <small id="ayuda_email">Aquí se enviará la notificación de la incidencia.</small>
          </div>
        </div>

        <div class="pie_formulario">
          <p><span aria-hidden="true">*</span> Campos obligatorios</p>
          <button type="submit" class="btn1">Enviar incidencia</button>
        </div>
      </form>
    </section>
  </main>
</body>
</html>
