<?php

function sendPIN($toEmail, $pin) {
    $subject = "Tu código de verificación";
    $message = "Tu código de verificación es: $pin";
    
    // Debes configurar un correo de envío que funcione en tu servidor. 
    // Cambia 'no-reply@tu-dominio.com' por tu correo de origen.
    $headers = "From: ticorreagro@outlook.com";
    
    

    if (mail($toEmail, $subject, $message, $headers)) {
        return true;
    } else {
        return false;
    }
}
?>