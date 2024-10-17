<?php
$tokenB = '';
$whatsappApiUrl = '';

/*
 * VERIFICACION DEL WEBHOOK
*/
//TOQUEN QUE QUERRAMOS PONER 
$token = '';
//RETO QUE RECIBIREMOS DE FACEBOOK
$palabraReto = $_GET['hub_challenge'];
//TOQUEN DE VERIFICACION QUE RECIBIREMOS DE FACEBOOK
$tokenVerificacion = $_GET['hub_verify_token'];
//SI EL TOKEN QUE GENERAMOS ES EL MISMO QUE NOS ENVIA FACEBOOK RETORNAMOS EL RETO PARA VALIDAR QUE SOMOS NOSOTROS
if ($token === $tokenVerificacion) {
    echo $palabraReto;
    exit;
}

/*
 * RECEPCION DE MENSAJES
 */
//LEEMOS LOS DATOS ENVIADOS POR WHATSAPP
$respuesta = file_get_contents("php://input");
//CONVERTIMOS EL JSON EN ARRAY DE PHP
$respuesta = json_decode($respuesta, true);
//EXTRAEMOS EL MENSAJE DEL ARRAY
$mensaje=$respuesta['entry'][0]['changes'][0]['value']['messages'][0]['text']['body'];
//EXTRAEMOS EL TELEFONO DEL ARRAY
$telefonoCliente=$respuesta['entry'][0]['changes'][0]['value']['messages'][0]['from'];
//EXTRAEMOS EL ID DE WHATSAPP DEL ARRAY
$id=$respuesta['entry'][0]['changes'][0]['value']['messages'][0]['id'];
//EXTRAEMOS EL TIEMPO DE WHATSAPP DEL ARRAY
$timestamp=$respuesta['entry'][0]['changes'][0]['value']['messages'][0]['timestamp'];
//SI HAY UN MENSAJE
if($mensaje!=null){
    //guarda el mensaje recibido en un txt
    file_put_contents("text.txt", $mensaje );
    file_put_contents("telefono.txt", $telefonoCliente );

        //enviar el mensaje
         //telefono de envio
         /**????????????????????????????????????????????????????????????????????? */
         ///$telefono = '    '; //Este telefono cambiarlo por el que esta en el panel de Meta y luego hacer una prueba con el $telefonoCliente
         /**????????????????????????????????????????????????????????????????????? */
         //CONFIGURACION DEL MENSAJE
         $mensajeS = ''
                 . '{'
                 . '"messaging_product": "whatsapp", '
                 . '"recipient_type": "individual",'
                 . '"to": "' . $telefonoCliente . '", '
                 . '"type": "text", '
                 . '"text": '
                 . '{'
                 . '     "body":"' . 'SIMON' . '",'
                 . '     "preview_url": true, '
                 . '} '
                 . '}';
         //DECLARAMOS LAS CABECERAS
         $header = array("Authorization: Bearer " . $tokenB, "Content-Type: application/json",);
         //INICIAMOS EL CURL
         $curl = curl_init();
         curl_setopt($curl, CURLOPT_URL, $whatsappApiUrl);
         curl_setopt($curl, CURLOPT_POSTFIELDS, $mensajeS);
         curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
         curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
         //OBTENEMOS LA RESPUESTA DEL ENVIO DE INFORMACION
         $response = json_decode(curl_exec($curl), true);
         //OBTENEMOS EL CODIGO DE LA RESPUESTA
         $status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
         //CERRAMOS EL CURL
         curl_close($curl);

}