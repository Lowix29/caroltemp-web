<?php
/* ================================
   CAROLTEMP — Conexión DB
================================ */

// Detectar entorno
$is_local = in_array($_SERVER['SERVER_NAME'] ?? '', ['localhost', '127.0.0.1']);

if ($is_local) {
  // LOCAL — XAMPP
  define('DB_HOST', 'localhost');
  define('DB_NAME', 'caroltemp');
  define('DB_USER', 'root');
  define('DB_PASS', '');
} else {
  // PRODUCCIÓN — Hostinger
  define('DB_HOST', 'localhost');
  define('DB_NAME', 'u215166251_2LHFNT78'); // cambiar por el nombre real
  define('DB_USER', 'u215166251_carolYcL1ma'); // cambiar por el usuario real
  define('DB_PASS', 'X7ze4fmNQ6Z$DbJ');     // cambiar por la contraseña real
}

define('DB_CHARSET', 'utf8mb4');

try {
  $pdo = new PDO(
    'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET,
    DB_USER,
    DB_PASS,
    [
      PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES   => false,
    ]
  );
} catch (PDOException $e) {
  error_log('DB Error: ' . $e->getMessage());
  die('Error de conexión. Por favor inténtalo más tarde.');
}