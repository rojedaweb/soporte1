<?php
//################################################################
//Inicializamos variables
//################################################################
$error= 0;

//################################################################
//PHPMailer - Cargamos la libería para enviar por SMTP
//################################################################
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

//################################################################
//Configuración local (excluida de Git)
//################################################################
$configFile = __DIR__ . '/config.local.php';
if (!is_file($configFile)) {
  die('Falta config.local.php. Copia config.example.php y completa sus datos.');
}

$config = require $configFile;
$bbdd['db_host'] = $config['database']['host'];
$bbdd['db_table'] = $config['database']['name'];
$bbdd['db_user'] = $config['database']['user'];
$bbdd['db_pass'] = $config['database']['password'];

$emailconf['use_smtp'] = $config['mail']['use_smtp'];
$emailconf['server'] = $config['mail']['host'];
$emailconf['user'] = $config['mail']['user'];
$emailconf['pass'] = $config['mail']['password'];
$emailconf['port'] = $config['mail']['port'];
$emailconf['from_address'] = $config['mail']['from_address'];
$emailconf['from_name'] = $config['mail']['from_name'];

//Conexión con la base de datos utilizando mysqli con PHP
$mysqli = @new mysqli($bbdd['db_host'], $bbdd['db_user'], $bbdd['db_pass'], $bbdd['db_table']);
$mysqli->set_charset("utf8mb4");
if(mysqli_connect_errno()) {
  printf("Error en la conexión: %s\n", mysqli_connect_error());
  exit();
}
//################################################################
//################################################################


//################################################################
//Inicializamos la variable que nos dirá si se ha enviado o no.
//################################################################
$enviado = 0;
//El email del remitente, modificarlo por la que creemos
$remitente = 'soporte@dominio.com'; //<<<

$nombre = trim($_POST['nombre'] ?? '');
$direccion = trim($_POST['direccion'] ?? '');
$telefono = trim($_POST['telefono'] ?? '');
$descripcion = trim($_POST['descripcion'] ?? '');
$email = trim($_POST['email'] ?? '');

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || $nombre === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
  header("Location: /envio_ko.php");
  exit();
}


//################################################################
//Antes de crear el email hacemos verficiación básica de variable
//Las variables vienen con el método POST
//El nombre de las variable es el del atributo name el campo
//################################################################
if(
  $nombre !== '' &&
  filter_var($email, FILTER_VALIDATE_EMAIL)
){

  //################################################################
  //Leer la imagen y codificarla en Base64
  //################################################################
  $imagePath = $_SERVER['DOCUMENT_ROOT'] . '/img/logo-iesrincon-azul.png';
  if (!file_exists($imagePath)) {
      throw new Exception('La imagen no se encuentra en: ' . $imagePath);
  }
  $imageData = file_get_contents($imagePath);
  $base64Image = base64_encode($imageData);
  $mimeType = mime_content_type($imagePath);

  //################################################################
  //Se crean las variable que se usa en la función mail de PHP
  //################################################################
  //Para el asunto del correo
  $asunto = 'Nuevo ticket de soporte';
  //Se concatena una variable HTML con el contenido del correo
  $mensaje = '';
  $mensaje .= '<br><img src="data:' . $mimeType . ';base64,' . $base64Image . '" width="100"><br><br>';
  $mensaje .= 'Hola, se ha creado el siguiente ticket de soporte.<br><br>';
  $mensaje .= '<br><strong>Empresa:</strong> '.htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8');
  $mensaje .= '<br><strong>Dirección:</strong> '.htmlspecialchars($direccion, ENT_QUOTES, 'UTF-8');
  $mensaje .= '<br><strong>Teléfono:</strong> '.htmlspecialchars($telefono, ENT_QUOTES, 'UTF-8');
  $mensaje .= '<br><strong>Email:</strong> '.htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
  $mensaje .= '<br><strong>Descripción:</strong> <br>'.nl2br(htmlspecialchars($descripcion, ENT_QUOTES, 'UTF-8'));
  $mensaje .= '<br>';
  //Añadimos como un extra la fecha y la IP del remitente
  $mensaje .= '<br><strong>Fecha:</strong> '.@date('d/m/Y H:i:s');
  $mensaje .= '<br><strong>IP:</strong> '.@$_SERVER['REMOTE_ADDR'];
      
  //Enviar el correo con PHPMailer
  //Si se manda correctamente pondrá en la variable enviado un 1 (true) 
  $mail = new PHPMailer(true);
  try {
    // Configuración del servidor SMTP
    if ($emailconf['use_smtp']) {
      $mail->isSMTP();
      $mail->Host = $emailconf['server'];
      $mail->SMTPAuth = true;
      $mail->Username = $emailconf['user'];
      $mail->Password = $emailconf['pass'];
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
      $mail->Port = $emailconf['port'];
    }

    // Configuración del correo
    $mail->setFrom($emailconf['from_address'], $emailconf['from_name']);
    $mail->addAddress($email);
    $mail->Subject = $asunto;
    //Establecer codificación UTF-8
    $mail->CharSet = 'UTF-8';
    
    // Configurar el correo como HTML
    $mail->isHTML(true); // Activar formato HTML
    $mail->Body = $mensaje;
    
    // Enviar correo
    if($mail->send()){
     $enviado = 1;  
    }else{
      $enviado = 0;
    }
            
  } catch (Exception $e) {
    $enviado = 0;
  }
  
}



//################################################################
//Guardamos el resultado en la BD
//################################################################
//Creamos la sentencia SQL para la tabla previamente creada
$sql = "INSERT INTO incidencias (empresa, direccion, descripcion, email, enviado)
  VALUES (?, ?, ?, ?, ?)";
//Guardamos en la BD mediante algunos comandos de protección básicos de sql: Prepare y execute
$sqlpre = $mysqli->prepare($sql);
if ($sqlpre === false) {
  die("Error al preparar la consulta: " . $mysqli->error);
}
$sqlpre->bind_param(
  "ssssi",
  $nombre,
  $direccion,
  $descripcion,
  $email,
  $enviado
);
if (!$sqlpre->execute()) {
  $error = 11;
}
//Cerramos la conexión SQL
$sqlpre->close();
//################################################################
//################################################################




//################################################################
//Comprobamos la variable enviado para mostrar el mensaje final
//################################################################
if($enviado){
  //echo 'Email enviado correctamente';
  header("Location: /envio_ok.php");
}else{
  //echo 'Fallo enviado email ['.$error.']';
  header("Location: /envio_ko.php");
}




?>
