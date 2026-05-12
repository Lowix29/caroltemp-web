<?php
session_start();
if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {
  header('Location: login.php');
  exit;
}
require_once '../includes/db.php';

$mensaje = '';
$error   = '';

// Función generar slug
function generarSlug($texto) {
  $map  = ['á'=>'a','é'=>'e','í'=>'i','ó'=>'o','ú'=>'u','ñ'=>'n','ü'=>'u','Á'=>'a','É'=>'e','Í'=>'i','Ó'=>'o','Ú'=>'u','Ñ'=>'n'];
  $slug = strtr(strtolower($texto), $map);
  $slug = preg_replace('/[^a-z0-9\s\-]/', '', $slug);
  $slug = preg_replace('/[\s\-]+/', '-', trim($slug));
  return $slug;
}

// Función subir imagen
function subirImagenCat($file, $carpeta) {
  $permitidos = ['image/jpeg','image/png','image/webp','image/gif'];
  if (!in_array($file['type'], $permitidos)) return ['error' => 'Formato no permitido.'];
  if ($file['size'] > 3 * 1024 * 1024) return ['error' => 'Máximo 3MB.'];
  $ext      = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
  $nombre   = uniqid('cat_') . '.' . $ext;
  $ruta_abs = dirname(__DIR__) . '/img/' . $carpeta . '/' . $nombre;
  $ruta_web = '/img/' . $carpeta . '/' . $nombre;
  if (!move_uploaded_file($file['tmp_name'], $ruta_abs)) return ['error' => 'Error al guardar.'];
  return ['ruta' => $ruta_web];
}

// ================================
// CATEGORÍAS BLOG
// ================================

if (isset($_POST['add_cat'])) {
  $nombre = trim($_POST['cat_nombre']      ?? '');
  $slug   = trim($_POST['cat_slug']        ?? '') ?: generarSlug($nombre);
  $desc   = trim($_POST['cat_descripcion'] ?? '');
  $mtitle = trim($_POST['cat_meta_title']  ?? '');
  $mdesc  = trim($_POST['cat_meta_desc']   ?? '');
  $icono  = trim($_POST['cat_icono']       ?? '');
  $imagen = trim($_POST['cat_imagen']      ?? '');

  if (!empty($_FILES['cat_imagen_file']['name'])) {
    $res = subirImagenCat($_FILES['cat_imagen_file'], 'blog');
    if (isset($res['error'])) { $error = $res['error']; }
    else { $imagen = $res['ruta']; }
  }

  if (!$error) {
    if (!$nombre) {
      $error = 'El nombre es obligatorio.';
    } else {
      try {
        $orden = $pdo->query('SELECT MAX(orden) FROM categorias_blog')->fetchColumn() + 1;
        $pdo->prepare('INSERT INTO categorias_blog (nombre, slug, descripcion, meta_title, meta_desc, imagen, icono, orden) VALUES (?,?,?,?,?,?,?,?)')
            ->execute([$nombre, $slug, $desc, $mtitle, $mdesc, $imagen, $icono, $orden]);
        $mensaje = '✅ Categoría añadida correctamente.';
      } catch (PDOException $e) {
        $error = 'El nombre o slug ya existe.';
      }
    }
  }
}

if (isset($_POST['edit_cat'])) {
  $id     = $_POST['cat_id']           ?? '';
  $nombre = trim($_POST['cat_nombre']      ?? '');
  $slug   = trim($_POST['cat_slug']        ?? '') ?: generarSlug($nombre);
  $desc   = trim($_POST['cat_descripcion'] ?? '');
  $mtitle = trim($_POST['cat_meta_title']  ?? '');
  $mdesc  = trim($_POST['cat_meta_desc']   ?? '');
  $icono  = trim($_POST['cat_icono']       ?? '');
  $imagen = trim($_POST['cat_imagen']      ?? '');

  if (!empty($_FILES['cat_imagen_file']['name'])) {
    $res = subirImagenCat($_FILES['cat_imagen_file'], 'blog');
    if (isset($res['error'])) { $error = $res['error']; }
    else { $imagen = $res['ruta']; }
  }

  if (!$error && $id && $nombre) {
    try {
      $pdo->prepare('UPDATE categorias_blog SET nombre=?, slug=?, descripcion=?, meta_title=?, meta_desc=?, imagen=?, icono=? WHERE id=?')
          ->execute([$nombre, $slug, $desc, $mtitle, $mdesc, $imagen, $icono, $id]);
      $mensaje = '✅ Categoría actualizada.';
    } catch (PDOException $e) {
      $error = 'El nombre o slug ya existe en otra categoría.';
    }
  }
}

if (isset($_GET['del_cat']) && is_numeric($_GET['del_cat'])) {
  $pdo->prepare('DELETE FROM categorias_blog WHERE id = ?')->execute([$_GET['del_cat']]);
  $mensaje = '✅ Categoría eliminada.';
}

// ================================
// SERVICIOS PROYECTOS
// ================================

if (isset($_POST['add_serv'])) {
  $nombre = trim($_POST['serv_nombre']      ?? '');
  $slug   = trim($_POST['serv_slug']        ?? '') ?: generarSlug($nombre);
  $desc   = trim($_POST['serv_descripcion'] ?? '');
  $mtitle = trim($_POST['serv_meta_title']  ?? '');
  $mdesc  = trim($_POST['serv_meta_desc']   ?? '');
  $icono  = trim($_POST['serv_icono']       ?? '');
  $imagen = trim($_POST['serv_imagen']      ?? '');

  if (!empty($_FILES['serv_imagen_file']['name'])) {
    $res = subirImagenCat($_FILES['serv_imagen_file'], 'proyectos');
    if (isset($res['error'])) { $error = $res['error']; }
    else { $imagen = $res['ruta']; }
  }

  if (!$error) {
    if (!$nombre) {
      $error = 'El nombre es obligatorio.';
    } else {
      try {
        $orden = $pdo->query('SELECT MAX(orden) FROM servicios_proyectos')->fetchColumn() + 1;
        $pdo->prepare('INSERT INTO servicios_proyectos (nombre, slug, descripcion, meta_title, meta_desc, imagen, icono, orden) VALUES (?,?,?,?,?,?,?,?)')
            ->execute([$nombre, $slug, $desc, $mtitle, $mdesc, $imagen, $icono, $orden]);
        $mensaje = '✅ Servicio añadido correctamente.';
      } catch (PDOException $e) {
        $error = 'El nombre o slug ya existe.';
      }
    }
  }
}

if (isset($_POST['edit_serv'])) {
  $id     = $_POST['serv_id']           ?? '';
  $nombre = trim($_POST['serv_nombre']      ?? '');
  $slug   = trim($_POST['serv_slug']        ?? '') ?: generarSlug($nombre);
  $desc   = trim($_POST['serv_descripcion'] ?? '');
  $mtitle = trim($_POST['serv_meta_title']  ?? '');
  $mdesc  = trim($_POST['serv_meta_desc']   ?? '');
  $icono  = trim($_POST['serv_icono']       ?? '');
  $imagen = trim($_POST['serv_imagen']      ?? '');

  if (!empty($_FILES['serv_imagen_file']['name'])) {
    $res = subirImagenCat($_FILES['serv_imagen_file'], 'proyectos');
    if (isset($res['error'])) { $error = $res['error']; }
    else { $imagen = $res['ruta']; }
  }

  if (!$error && $id && $nombre) {
    try {
      $pdo->prepare('UPDATE servicios_proyectos SET nombre=?, slug=?, descripcion=?, meta_title=?, meta_desc=?, imagen=?, icono=? WHERE id=?')
          ->execute([$nombre, $slug, $desc, $mtitle, $mdesc, $imagen, $icono, $id]);
      $mensaje = '✅ Servicio actualizado.';
    } catch (PDOException $e) {
      $error = 'El nombre o slug ya existe en otro servicio.';
    }
  }
}

if (isset($_GET['del_serv']) && is_numeric($_GET['del_serv'])) {
  $pdo->prepare('DELETE FROM servicios_proyectos WHERE id = ?')->execute([$_GET['del_serv']]);
  $mensaje = '✅ Servicio eliminado.';
}

// Cargar datos
$categorias = $pdo->query('SELECT * FROM categorias_blog ORDER BY orden ASC')->fetchAll();
$servicios  = $pdo->query('SELECT * FROM servicios_proyectos ORDER BY orden ASC')->fetchAll();

// Contar uso
$uso_cats  = [];
$uso_servs = [];
foreach ($pdo->query('SELECT categoria, COUNT(*) as total FROM articulos GROUP BY categoria')->fetchAll() as $row) {
  $uso_cats[$row['categoria']] = $row['total'];
}
foreach ($pdo->query('SELECT servicio, COUNT(*) as total FROM proyectos GROUP BY servicio')->fetchAll() as $row) {
  $uso_servs[$row['servicio']] = $row['total'];
}

// Elemento activo para edición
$edit_cat  = isset($_GET['edit_cat'])  && is_numeric($_GET['edit_cat'])  ? (int)$_GET['edit_cat']  : null;
$edit_serv = isset($_GET['edit_serv']) && is_numeric($_GET['edit_serv']) ? (int)$_GET['edit_serv'] : null;

$cat_edit_data  = null;
$serv_edit_data = null;

if ($edit_cat) {
  $stmt = $pdo->prepare('SELECT * FROM categorias_blog WHERE id = ? LIMIT 1');
  $stmt->execute([$edit_cat]);
  $cat_edit_data = $stmt->fetch();
}
if ($edit_serv) {
  $stmt = $pdo->prepare('SELECT * FROM servicios_proyectos WHERE id = ? LIMIT 1');
  $stmt->execute([$edit_serv]);
  $serv_edit_data = $stmt->fetch();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Categorías y servicios — CarolTemp Admin</title>
  <meta name="robots" content="noindex, nofollow">
  <?php include '../includes/admin_style.php'; ?>
  <style>
    .cats-layout { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
    .cat-item { border: 1px solid #eaeff4; border-radius: 8px; padding: 1rem; margin-bottom: 0.75rem; background: #fff; transition: border-color 0.15s; }
    .cat-item:hover { border-color: #c0d0e0; }
    .cat-item.active { border-color: #1e3a5f; background: #f4f7fb; }
    .cat-item-top { display: flex; align-items: center; gap: 0.75rem; }
    .cat-icono { font-size: 22px; flex-shrink: 0; width: 36px; text-align: center; }
    .cat-icono-placeholder { width: 36px; height: 36px; background: #e8f0fa; border-radius: 6px; display: flex; align-items: center; justify-content: center; color: #8aa5bc; font-size: 16px; flex-shrink: 0; }
    .cat-info { flex: 1; min-width: 0; }
    .cat-nombre { color: #0d1f33; font-size: 14px; font-weight: 600; }
    .cat-slug { color: #a0b5c8; font-size: 11.5px; font-family: monospace; }
    .cat-desc { color: #7a95b0; font-size: 12.5px; margin-top: 4px; line-height: 1.4; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .cat-uso { background: #e8f0fa; color: #1e3a5f; font-size: 11px; font-weight: 600; padding: 3px 10px; border-radius: 20px; white-space: nowrap; flex-shrink: 0; }
    .cat-uso.vacio { background: #f0f0f0; color: #aaa; }
    .cat-imagen-thumb { width: 40px; height: 40px; border-radius: 6px; object-fit: cover; border: 1px solid #dde6f0; flex-shrink: 0; }
    .cat-acciones { display: flex; gap: 0.5rem; margin-top: 0.75rem; padding-top: 0.75rem; border-top: 1px solid #eaeff4; }
    .cat-acciones a, .cat-acciones button { font-size: 12px; padding: 5px 14px; border-radius: 5px; font-weight: 500; cursor: pointer; border: none; text-decoration: none; transition: opacity 0.15s; }
    .btn-edit-cat { background: #1e3a5f; color: #fff; }
    .btn-del-cat { background: #fdf0f0; color: #c0392b; }
    .cat-acciones a:hover, .cat-acciones button:hover { opacity: 0.75; }
    .form-panel { background: #fff; border: 1px solid #dde6f0; border-radius: 10px; overflow: hidden; }
    .form-panel-header { padding: 1rem 1.5rem; border-bottom: 1px solid #eaeff4; display: flex; align-items: center; justify-content: space-between; }
    .form-panel-header h2 { color: #1e3a5f; font-size: 15px; font-weight: 600; }
    .form-panel-header a { color: #7a95b0; font-size: 13px; text-decoration: none; }
    .form-panel-body { padding: 1.5rem; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem; }
    .form-row.full { grid-template-columns: 1fr; }
    .form-group { display: flex; flex-direction: column; gap: 5px; }
    .form-group label { font-size: 12.5px; font-weight: 500; color: #3a4a5c; }
    .form-group input, .form-group textarea, .form-group select { border: 1px solid #dde6f0; border-radius: 6px; padding: 9px 12px; font-size: 13.5px; font-family: inherit; color: #0d1f33; width: 100%; transition: border-color 0.15s; }
    .form-group input:focus, .form-group textarea:focus { outline: none; border-color: #1e3a5f; }
    .form-group textarea { resize: vertical; min-height: 80px; }
    .char-count { font-size: 11px; text-align: right; color: #a0b5c8; }
    .char-count.warn { color: #e74c3c; }
    .slug-preview { font-size: 11px; color: #7a95b0; font-family: monospace; margin-top: 3px; }
    .upload-mini { border: 2px dashed #dde6f0; border-radius: 6px; padding: 1rem; text-align: center; cursor: pointer; transition: all 0.15s; }
    .upload-mini:hover { border-color: #1e3a5f; background: #f4f7fb; }
    .upload-mini input { display: none; }
    .upload-mini p { color: #7a95b0; font-size: 12.5px; }
    .upload-mini p strong { color: #1e3a5f; }
    .img-preview-mini { width: 100%; max-height: 120px; object-fit: cover; border-radius: 6px; margin-top: 0.75rem; border: 1px solid #dde6f0; display: none; }
    .img-actual { width: 100%; max-height: 100px; object-fit: cover; border-radius: 6px; border: 1px solid #dde6f0; margin-bottom: 0.5rem; }
    .google-preview-mini { background: #f9fbfd; border: 1px solid #dde6f0; border-radius: 6px; padding: 1rem; }
    .gp-url  { color: #006621; font-size: 12px; }
    .gp-title { color: #1a0dab; font-size: 16px; font-weight: 400; line-height: 1.3; }
    .gp-desc  { color: #545454; font-size: 12.5px; line-height: 1.5; }
    .gp-title.warn, .gp-desc.warn { color: #e74c3c; }
    .btn-guardar { background: #1e3a5f; color: #fff; border: none; border-radius: 7px; padding: 11px 28px; font-size: 14px; font-weight: 500; cursor: pointer; transition: opacity 0.15s; }
    .btn-guardar:hover { opacity: 0.88; }
    .empty-cats { text-align: center; color: #a0b5c8; padding: 2rem; font-size: 13.5px; }
    .tabs { display: flex; gap: 0; border-bottom: 1px solid #eaeff4; margin-bottom: 1.5rem; }
    .tab { padding: 0.75rem 1.5rem; font-size: 14px; font-weight: 500; color: #7a95b0; cursor: pointer; border-bottom: 2px solid transparent; transition: all 0.15s; text-decoration: none; }
    .tab.active { color: #1e3a5f; border-bottom-color: #1e3a5f; }
  </style>
</head>
<body>

<?php include '../includes/admin_sidebar.php'; ?>

<main class="main">

  <div class="topbar">
    <h1>Categorías y servicios</h1>
  </div>

  <?php if ($mensaje): ?>
    <div class="mensaje"><?php echo $mensaje; ?></div>
  <?php endif; ?>
  <?php if ($error): ?>
    <div class="error-msg"><?php echo htmlspecialchars($error); ?></div>
  <?php endif; ?>

  <!-- TABS -->
  <div class="tabs">
    <a href="?tab=cats"  class="tab <?php echo (($_GET['tab'] ?? 'cats') === 'cats')  ? 'active' : ''; ?>">✏️ Categorías del blog</a>
    <a href="?tab=servs" class="tab <?php echo (($_GET['tab'] ?? 'cats') === 'servs') ? 'active' : ''; ?>">🔧 Servicios de proyectos</a>
  </div>

  <?php if (($_GET['tab'] ?? 'cats') === 'cats'): ?>
  <!-- ================================ -->
  <!-- CATEGORÍAS BLOG                  -->
  <!-- ================================ -->
  <div class="cats-layout">

    <!-- LISTADO -->
    <div>
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem">
        <h2 style="color:#1e3a5f;font-size:16px;font-weight:600">Categorías <span style="color:#a0b5c8;font-size:13px;font-weight:400">(<?php echo count($categorias); ?>)</span></h2>
        <a href="?tab=cats&new_cat=1" style="background:#1e3a5f;color:#fff;padding:8px 18px;border-radius:6px;font-size:13px;font-weight:500;text-decoration:none">+ Nueva categoría</a>
      </div>

      <?php if (empty($categorias)): ?>
        <div class="empty-cats">No hay categorías todavía</div>
      <?php else: ?>
        <?php foreach ($categorias as $cat): ?>
          <?php $uso = $uso_cats[$cat['nombre']] ?? 0; ?>
          <div class="cat-item <?php echo $edit_cat === (int)$cat['id'] ? 'active' : ''; ?>">
            <div class="cat-item-top">
              <?php if ($cat['icono']): ?>
                <span class="cat-icono"><?php echo $cat['icono']; ?></span>
              <?php else: ?>
                <div class="cat-icono-placeholder">🏷️</div>
              <?php endif; ?>
              <?php if ($cat['imagen']): ?>
                <img src="<?php echo htmlspecialchars($cat['imagen']); ?>" class="cat-imagen-thumb" alt="">
              <?php endif; ?>
              <div class="cat-info">
                <div class="cat-nombre"><?php echo htmlspecialchars($cat['nombre']); ?></div>
                <div class="cat-slug">/blog/categoria/<?php echo htmlspecialchars($cat['slug']); ?></div>
                <?php if ($cat['descripcion']): ?>
                  <div class="cat-desc"><?php echo htmlspecialchars($cat['descripcion']); ?></div>
                <?php endif; ?>
              </div>
              <span class="cat-uso <?php echo $uso === 0 ? 'vacio' : ''; ?>">
                <?php echo $uso; ?> art.
              </span>
            </div>
            <div class="cat-acciones">
              <a href="?tab=cats&edit_cat=<?php echo $cat['id']; ?>" class="btn-edit-cat">✏️ Editar</a>
              <a href="/blog/categoria/<?php echo $cat['slug']; ?>" target="_blank" style="background:#f0f0f0;color:#555">Ver →</a>
              <?php if ($uso === 0): ?>
                <a href="?tab=cats&del_cat=<?php echo $cat['id']; ?>"
                   class="btn-del-cat"
                   onclick="return confirm('¿Eliminar \'<?php echo htmlspecialchars(addslashes($cat['nombre'])); ?>\'?')">
                  Eliminar
                </a>
              <?php else: ?>
                <button class="btn-del-cat" style="opacity:0.4;cursor:not-allowed" title="Tiene artículos asociados">Eliminar</button>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <!-- FORMULARIO -->
    <div>
      <?php
      $modo_cat  = isset($_GET['new_cat']) ? 'nuevo' : ($cat_edit_data ? 'editar' : 'ninguno');
      $datos_cat = $cat_edit_data ?? ['id'=>'','nombre'=>'','slug'=>'','descripcion'=>'','meta_title'=>'','meta_desc'=>'','imagen'=>'','icono'=>''];
      ?>

      <?php if ($modo_cat !== 'ninguno'): ?>
      <div class="form-panel">
        <div class="form-panel-header">
          <h2><?php echo $modo_cat === 'editar' ? 'Editar categoría' : 'Nueva categoría'; ?></h2>
          <a href="?tab=cats">✕ Cancelar</a>
        </div>
        <div class="form-panel-body">
          <form method="POST" action="?tab=cats" enctype="multipart/form-data">
            <?php if ($modo_cat === 'editar'): ?>
              <input type="hidden" name="cat_id" value="<?php echo $datos_cat['id']; ?>">
            <?php endif; ?>

            <div class="form-row">
              <div class="form-group">
                <label>Nombre *</label>
                <input type="text" name="cat_nombre"
                  value="<?php echo htmlspecialchars($datos_cat['nombre']); ?>"
                  placeholder="Ej: Preguntas frecuentes"
                  required oninput="autoSlugCat(this.value)">
              </div>
              <div class="form-group">
                <label>Icono (emoji)</label>
                <input type="text" name="cat_icono"
                  value="<?php echo htmlspecialchars($datos_cat['icono']); ?>"
                  placeholder="💧"
                  maxlength="10">
              </div>
            </div>

            <div class="form-row full">
              <div class="form-group">
                <label>Slug (URL)</label>
                <input type="text" id="cat-slug-input" name="cat_slug"
                  value="<?php echo htmlspecialchars($datos_cat['slug']); ?>"
                  placeholder="preguntas-frecuentes"
                  oninput="actualizarPreviewCat()">
                <span class="slug-preview" id="cat-slug-preview">
                  caroltemp.com/blog/categoria/<?php echo htmlspecialchars($datos_cat['slug'] ?: 'tu-slug'); ?>
                </span>
              </div>
            </div>

            <div class="form-row full">
              <div class="form-group">
                <label>Descripción <span style="color:#a0b5c8;font-weight:400;font-size:11px">— aparece en la página de categoría</span></label>
                <textarea name="cat_descripcion" rows="3"
                  placeholder="Breve descripción de esta categoría..."
                ><?php echo htmlspecialchars($datos_cat['descripcion']); ?></textarea>
              </div>
            </div>

            <div class="form-row full">
              <div class="form-group">
                <label>Imagen</label>
                <?php if ($datos_cat['imagen']): ?>
                  <img src="<?php echo htmlspecialchars($datos_cat['imagen']); ?>" class="img-actual" alt="">
                <?php endif; ?>
                <div class="upload-mini" onclick="document.getElementById('cat-img-file').click()">
                  <input type="file" id="cat-img-file" name="cat_imagen_file" accept="image/*" onchange="previewMini(this,'cat-img-preview')">
                  <p><strong>Subir imagen</strong> · JPG, PNG, WebP · Máx 3MB</p>
                </div>
                <img id="cat-img-preview" class="img-preview-mini" src="" alt="">
                <input type="text" name="cat_imagen"
                  value="<?php echo htmlspecialchars($datos_cat['imagen']); ?>"
                  placeholder="O pega la URL de la imagen"
                  style="margin-top:0.5rem">
              </div>
            </div>

            <div class="form-row full">
              <div class="form-group">
                <label>Meta title <span style="color:#a0b5c8;font-weight:400;font-size:11px">— 50-60 caracteres</span></label>
                <input type="text" name="cat_meta_title" id="cat-meta-title"
                  value="<?php echo htmlspecialchars($datos_cat['meta_title']); ?>"
                  placeholder="Ej: Preguntas frecuentes sobre fontanería — CarolTemp"
                  oninput="contarCat(this,'cat-count-title',60); actualizarPreviewCat()">
                <span class="char-count" id="cat-count-title"><?php echo strlen($datos_cat['meta_title']); ?>/60</span>
              </div>
            </div>

            <div class="form-row full">
              <div class="form-group">
                <label>Meta description <span style="color:#a0b5c8;font-weight:400;font-size:11px">— 140-160 caracteres</span></label>
                <textarea name="cat_meta_desc" id="cat-meta-desc" rows="3"
                  placeholder="Descripción de esta categoría para Google..."
                  oninput="contarCat(this,'cat-count-desc',160); actualizarPreviewCat()"
                ><?php echo htmlspecialchars($datos_cat['meta_desc']); ?></textarea>
                <span class="char-count" id="cat-count-desc"><?php echo strlen($datos_cat['meta_desc']); ?>/160</span>
              </div>
            </div>

            <!-- PREVIEW GOOGLE -->
            <div class="form-row full">
              <div class="google-preview-mini">
                <p style="font-size:11px;color:#7a95b0;font-weight:600;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:0.5rem">Vista previa Google</p>
                <p class="gp-url">caroltemp.com › blog › categoria › <span id="cat-gp-slug"><?php echo htmlspecialchars($datos_cat['slug'] ?: 'tu-slug'); ?></span></p>
                <p class="gp-title" id="cat-gp-title"><?php echo htmlspecialchars($datos_cat['meta_title'] ?: $datos_cat['nombre'] ?: 'Título de la categoría'); ?></p>
                <p class="gp-desc"  id="cat-gp-desc"><?php echo htmlspecialchars($datos_cat['meta_desc'] ?: 'Descripción de la categoría que aparecerá en Google...'); ?></p>
              </div>
            </div>

            <div style="margin-top:1.5rem">
              <button type="submit" name="<?php echo $modo_cat === 'editar' ? 'edit_cat' : 'add_cat'; ?>" class="btn-guardar">
                <?php echo $modo_cat === 'editar' ? 'Guardar cambios' : 'Crear categoría'; ?>
              </button>
            </div>

          </form>
        </div>
      </div>
      <?php else: ?>
        <div style="background:#f4f7fb;border:2px dashed #dde6f0;border-radius:10px;padding:3rem;text-align:center">
          <p style="color:#a0b5c8;font-size:14px;margin-bottom:1rem">Selecciona una categoría para editar<br>o crea una nueva</p>
          <a href="?tab=cats&new_cat=1" style="background:#1e3a5f;color:#fff;padding:10px 24px;border-radius:7px;font-size:14px;font-weight:500;text-decoration:none">+ Nueva categoría</a>
        </div>
      <?php endif; ?>
    </div>

  </div>

  <?php else: ?>
  <!-- ================================ -->
  <!-- SERVICIOS PROYECTOS              -->
  <!-- ================================ -->
  <div class="cats-layout">

    <!-- LISTADO -->
    <div>
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem">
        <h2 style="color:#1e3a5f;font-size:16px;font-weight:600">Servicios <span style="color:#a0b5c8;font-size:13px;font-weight:400">(<?php echo count($servicios); ?>)</span></h2>
        <a href="?tab=servs&new_serv=1" style="background:#1e3a5f;color:#fff;padding:8px 18px;border-radius:6px;font-size:13px;font-weight:500;text-decoration:none">+ Nuevo servicio</a>
      </div>

      <?php if (empty($servicios)): ?>
        <div class="empty-cats">No hay servicios todavía</div>
      <?php else: ?>
        <?php foreach ($servicios as $serv): ?>
          <?php $uso = $uso_servs[$serv['nombre']] ?? 0; ?>
          <div class="cat-item <?php echo $edit_serv === (int)$serv['id'] ? 'active' : ''; ?>">
            <div class="cat-item-top">
              <?php if ($serv['icono']): ?>
                <span class="cat-icono"><?php echo $serv['icono']; ?></span>
              <?php else: ?>
                <div class="cat-icono-placeholder">🔧</div>
              <?php endif; ?>
              <?php if ($serv['imagen']): ?>
                <img src="<?php echo htmlspecialchars($serv['imagen']); ?>" class="cat-imagen-thumb" alt="">
              <?php endif; ?>
              <div class="cat-info">
                <div class="cat-nombre"><?php echo htmlspecialchars($serv['nombre']); ?></div>
                <div class="cat-slug">/proyectos/servicio/<?php echo htmlspecialchars($serv['slug']); ?></div>
                <?php if ($serv['descripcion']): ?>
                  <div class="cat-desc"><?php echo htmlspecialchars($serv['descripcion']); ?></div>
                <?php endif; ?>
              </div>
              <span class="cat-uso <?php echo $uso === 0 ? 'vacio' : ''; ?>">
                <?php echo $uso; ?> proy.
              </span>
            </div>
            <div class="cat-acciones">
              <a href="?tab=servs&edit_serv=<?php echo $serv['id']; ?>" class="btn-edit-cat">✏️ Editar</a>
              <a href="/proyectos/servicio/<?php echo $serv['slug']; ?>" target="_blank" style="background:#f0f0f0;color:#555">Ver →</a>
              <?php if ($uso === 0): ?>
                <a href="?tab=servs&del_serv=<?php echo $serv['id']; ?>"
                   class="btn-del-cat"
                   onclick="return confirm('¿Eliminar \'<?php echo htmlspecialchars(addslashes($serv['nombre'])); ?>\'?')">
                  Eliminar
                </a>
              <?php else: ?>
                <button class="btn-del-cat" style="opacity:0.4;cursor:not-allowed" title="Tiene proyectos asociados">Eliminar</button>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <!-- FORMULARIO -->
    <div>
      <?php
      $modo_serv  = isset($_GET['new_serv']) ? 'nuevo' : ($serv_edit_data ? 'editar' : 'ninguno');
      $datos_serv = $serv_edit_data ?? ['id'=>'','nombre'=>'','slug'=>'','descripcion'=>'','meta_title'=>'','meta_desc'=>'','imagen'=>'','icono'=>''];
      ?>

      <?php if ($modo_serv !== 'ninguno'): ?>
      <div class="form-panel">
        <div class="form-panel-header">
          <h2><?php echo $modo_serv === 'editar' ? 'Editar servicio' : 'Nuevo servicio'; ?></h2>
          <a href="?tab=servs">✕ Cancelar</a>
        </div>
        <div class="form-panel-body">
          <form method="POST" action="?tab=servs" enctype="multipart/form-data">
            <?php if ($modo_serv === 'editar'): ?>
              <input type="hidden" name="serv_id" value="<?php echo $datos_serv['id']; ?>">
            <?php endif; ?>

            <div class="form-row">
              <div class="form-group">
                <label>Nombre *</label>
                <input type="text" name="serv_nombre"
                  value="<?php echo htmlspecialchars($datos_serv['nombre']); ?>"
                  placeholder="Ej: Ósmosis inversa"
                  required oninput="autoSlugServ(this.value)">
              </div>
              <div class="form-group">
                <label>Icono (emoji)</label>
                <input type="text" name="serv_icono"
                  value="<?php echo htmlspecialchars($datos_serv['icono']); ?>"
                  placeholder="🔧"
                  maxlength="10">
              </div>
            </div>

            <div class="form-row full">
              <div class="form-group">
                <label>Slug (URL)</label>
                <input type="text" id="serv-slug-input" name="serv_slug"
                  value="<?php echo htmlspecialchars($datos_serv['slug']); ?>"
                  placeholder="osmosis-inversa"
                  oninput="actualizarPreviewServ()">
                <span class="slug-preview" id="serv-slug-preview">
                  caroltemp.com/proyectos/servicio/<?php echo htmlspecialchars($datos_serv['slug'] ?: 'tu-slug'); ?>
                </span>
              </div>
            </div>

            <div class="form-row full">
              <div class="form-group">
                <label>Descripción</label>
                <textarea name="serv_descripcion" rows="3"
                  placeholder="Breve descripción de este servicio..."
                ><?php echo htmlspecialchars($datos_serv['descripcion']); ?></textarea>
              </div>
            </div>

            <div class="form-row full">
              <div class="form-group">
                <label>Imagen</label>
                <?php if ($datos_serv['imagen']): ?>
                  <img src="<?php echo htmlspecialchars($datos_serv['imagen']); ?>" class="img-actual" alt="">
                <?php endif; ?>
                <div class="upload-mini" onclick="document.getElementById('serv-img-file').click()">
                  <input type="file" id="serv-img-file" name="serv_imagen_file" accept="image/*" onchange="previewMini(this,'serv-img-preview')">
                  <p><strong>Subir imagen</strong> · JPG, PNG, WebP · Máx 3MB</p>
                </div>
                <img id="serv-img-preview" class="img-preview-mini" src="" alt="">
                <input type="text" name="serv_imagen"
                  value="<?php echo htmlspecialchars($datos_serv['imagen']); ?>"
                  placeholder="O pega la URL de la imagen"
                  style="margin-top:0.5rem">
              </div>
            </div>

            <div class="form-row full">
              <div class="form-group">
                <label>Meta title <span style="color:#a0b5c8;font-weight:400;font-size:11px">— 50-60 caracteres</span></label>
                <input type="text" name="serv_meta_title" id="serv-meta-title"
                  value="<?php echo htmlspecialchars($datos_serv['meta_title']); ?>"
                  placeholder="Ej: Instalación de ósmosis inversa en el Vinalopó — CarolTemp"
                  oninput="contarCat(this,'serv-count-title',60); actualizarPreviewServ()">
                <span class="char-count" id="serv-count-title"><?php echo strlen($datos_serv['meta_title']); ?>/60</span>
              </div>
            </div>

            <div class="form-row full">
              <div class="form-group">
                <label>Meta description <span style="color:#a0b5c8;font-weight:400;font-size:11px">— 140-160 caracteres</span></label>
                <textarea name="serv_meta_desc" id="serv-meta-desc" rows="3"
                  placeholder="Descripción de este servicio para Google..."
                  oninput="contarCat(this,'serv-count-desc',160); actualizarPreviewServ()"
                ><?php echo htmlspecialchars($datos_serv['meta_desc']); ?></textarea>
                <span class="char-count" id="serv-count-desc"><?php echo strlen($datos_serv['meta_desc']); ?>/160</span>
              </div>
            </div>

            <!-- PREVIEW GOOGLE -->
            <div class="form-row full">
              <div class="google-preview-mini">
                <p style="font-size:11px;color:#7a95b0;font-weight:600;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:0.5rem">Vista previa Google</p>
                <p class="gp-url">caroltemp.com › proyectos › servicio › <span id="serv-gp-slug"><?php echo htmlspecialchars($datos_serv['slug'] ?: 'tu-slug'); ?></span></p>
                <p class="gp-title" id="serv-gp-title"><?php echo htmlspecialchars($datos_serv['meta_title'] ?: $datos_serv['nombre'] ?: 'Título del servicio'); ?></p>
                <p class="gp-desc"  id="serv-gp-desc"><?php echo htmlspecialchars($datos_serv['meta_desc'] ?: 'Descripción del servicio que aparecerá en Google...'); ?></p>
              </div>
            </div>

            <div style="margin-top:1.5rem">
              <button type="submit" name="<?php echo $modo_serv === 'editar' ? 'edit_serv' : 'add_serv'; ?>" class="btn-guardar">
                <?php echo $modo_serv === 'editar' ? 'Guardar cambios' : 'Crear servicio'; ?>
              </button>
            </div>

          </form>
        </div>
      </div>
      <?php else: ?>
        <div style="background:#f4f7fb;border:2px dashed #dde6f0;border-radius:10px;padding:3rem;text-align:center">
          <p style="color:#a0b5c8;font-size:14px;margin-bottom:1rem">Selecciona un servicio para editar<br>o crea uno nuevo</p>
          <a href="?tab=servs&new_serv=1" style="background:#1e3a5f;color:#fff;padding:10px 24px;border-radius:7px;font-size:14px;font-weight:500;text-decoration:none">+ Nuevo servicio</a>
        </div>
      <?php endif; ?>
    </div>

  </div>
  <?php endif; ?>

</main>

<script>
// Slug automático
function slugify(texto) {
  const map = {'á':'a','é':'e','í':'i','ó':'o','ú':'u','ñ':'n','ü':'u','Á':'a','É':'e','Í':'i','Ó':'o','Ú':'u','Ñ':'n'};
  return texto.toLowerCase()
    .replace(/[áéíóúñüÁÉÍÓÚÑ]/g, m => map[m] || m)
    .replace(/[^a-z0-9\s\-]/g, '')
    .trim().replace(/\s+/g, '-').replace(/-+/g, '-');
}

function autoSlugCat(val) {
  const slug = slugify(val);
  document.getElementById('cat-slug-input').value = slug;
  actualizarPreviewCat();
}

function autoSlugServ(val) {
  const slug = slugify(val);
  document.getElementById('serv-slug-input').value = slug;
  actualizarPreviewServ();
}

// Preview Google categoría
function actualizarPreviewCat() {
  const slug  = document.getElementById('cat-slug-input')?.value  || 'tu-slug';
  const title = document.getElementById('cat-meta-title')?.value  || 'Título de la categoría';
  const desc  = document.getElementById('cat-meta-desc')?.value   || 'Descripción de la categoría...';

  const gt = document.getElementById('cat-gp-title');
  const gd = document.getElementById('cat-gp-desc');
  const gs = document.getElementById('cat-gp-slug');

  if (gt) gt.textContent = title.length > 60 ? title.substring(0,60) + '...' : title;
  if (gd) gd.textContent = desc.length  > 160? desc.substring(0,160) + '...' : desc;
  if (gs) gs.textContent = slug;

  if (gt) gt.className = 'gp-title' + (title.length > 60 ? ' warn' : '');
  if (gd) gd.className = 'gp-desc'  + (desc.length  > 160? ' warn' : '');
}

// Preview Google servicio
function actualizarPreviewServ() {
  const slug  = document.getElementById('serv-slug-input')?.value  || 'tu-slug';
  const title = document.getElementById('serv-meta-title')?.value  || 'Título del servicio';
  const desc  = document.getElementById('serv-meta-desc')?.value   || 'Descripción del servicio...';

  const gt = document.getElementById('serv-gp-title');
  const gd = document.getElementById('serv-gp-desc');
  const gs = document.getElementById('serv-gp-slug');

  if (gt) gt.textContent = title.length > 60 ? title.substring(0,60) + '...' : title;
  if (gd) gd.textContent = desc.length  > 160? desc.substring(0,160) + '...' : desc;
  if (gs) gs.textContent = slug;

  if (gt) gt.className = 'gp-title' + (title.length > 60 ? ' warn' : '');
  if (gd) gd.className = 'gp-desc'  + (desc.length  > 160? ' warn' : '');
}

// Contador caracteres
function contarCat(el, id, max) {
  const len  = el.value.length;
  const span = document.getElementById(id);
  if (!span) return;
  span.textContent = len + '/' + max;
  span.className   = 'char-count' + (len > max ? ' warn' : '');
}

// Preview imagen mini
function previewMini(input, previewId) {
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = e => {
      const img = document.getElementById(previewId);
      if (img) { img.src = e.target.result; img.style.display = 'block'; }
    };
    reader.readAsDataURL(input.files[0]);
  }
}

// Inicializar contadores
document.addEventListener('DOMContentLoaded', function() {
  ['cat-meta-title','serv-meta-title'].forEach(id => {
    const el = document.getElementById(id);
    if (el && el.value) contarCat(el, id.replace('meta-title','count-title').replace('cat-','cat-').replace('serv-','serv-'), 60);
  });
  ['cat-meta-desc','serv-meta-desc'].forEach(id => {
    const el = document.getElementById(id);
    if (el && el.value) contarCat(el, id.replace('meta-desc','count-desc').replace('cat-','cat-').replace('serv-','serv-'), 160);
  });
});
</script>

</body>
</html>