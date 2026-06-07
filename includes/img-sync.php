<?php
/* ============================================================
   CAROLTEMP — Sincronización automática de imágenes a producción
   Cuando se sube una imagen en local, se envía a producción
   automáticamente vía cURL para que esté disponible al instante.
============================================================ */

define('IMG_SYNC_TOKEN', 'ct_sync_K7mxP9_caroltemp');
define('IMG_SYNC_URL',   'https://caroltemp.com/admin/img-receive.php');

function syncImgToProduction(string $rutaAbs, string $rutaRel): bool {
  global $is_local;
  if (!$is_local)           return true; // En producción no hay nada que sincronizar
  if (!file_exists($rutaAbs)) return false;

  // Normalizar: sin barra inicial, debe empezar por img/
  $rutaRel = ltrim($rutaRel, '/');
  if (!str_starts_with($rutaRel, 'img/')) return false;

  $ch = curl_init(IMG_SYNC_URL);
  curl_setopt_array($ch, [
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => [
      'token'  => IMG_SYNC_TOKEN,
      'ruta'   => $rutaRel,
      'imagen' => new CURLFile($rutaAbs, mime_content_type($rutaAbs) ?: 'image/jpeg', basename($rutaAbs)),
    ],
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT        => 30,
    CURLOPT_SSL_VERIFYPEER => true,
  ]);

  $resp = curl_exec($ch);
  $err  = curl_errno($ch);
  curl_close($ch);

  if ($err) return false;
  $data = json_decode($resp, true);
  return !empty($data['ok']);
}
