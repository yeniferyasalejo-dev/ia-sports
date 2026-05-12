<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['ok' => false, 'error' => 'Solo POST']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

$club     = htmlspecialchars($data['club'] ?? '');
$pais     = htmlspecialchars($data['pais'] ?? '');
$telefono = htmlspecialchars($data['telefono'] ?? '');
$correo   = htmlspecialchars($data['correo'] ?? '');
$red      = htmlspecialchars($data['red'] ?? '');
$whatsapp = htmlspecialchars($data['whatsapp'] ?? '');

if (empty($club) || empty($correo)) {
    echo json_encode(['ok' => false, 'error' => 'Faltan campos obligatorios']);
    exit;
}

$to = 'gaston@iasport.io';
$subject = "Nueva solicitud de demo — $club";

$message = "
=============================================
  NUEVA SOLICITUD DE DEMO - IA SPORT
=============================================

Club:        $club
País:        $pais
Teléfono:    $telefono
Correo:      $correo
Red social:  $red
Canal WhatsApp: $whatsapp

=============================================
Enviado desde iasport.io
";

$headers  = "From: IA Sport <noreply@iasport.io>\r\n";
$headers .= "Reply-To: $correo\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

$sent = mail($to, $subject, $message, $headers);

echo json_encode(['ok' => $sent]);
