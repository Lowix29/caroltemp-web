<?php
function getHeroImagen(string $slug): string {
  global $pdo;
  static $cache = [];
  if (array_key_exists($slug, $cache)) return $cache[$slug];
  try {
    $stmt = $pdo->prepare('SELECT imagen FROM paginas_config WHERE slug = ? AND imagen != "" LIMIT 1');
    $stmt->execute([$slug]);
    $row = $stmt->fetch();
    $cache[$slug] = $row ? (string)$row['imagen'] : '';
  } catch (Exception $e) {
    $cache[$slug] = '';
  }
  return $cache[$slug];
}
