# CarolTemp Web — Notas de trabajo

## IMPORTANTE: Flujo de trabajo

- La web está en **Hostinger** (producción). Los cambios en GitHub NO se despliegan automáticamente.
- El usuario aplica los cambios **manualmente** pegando el código en el File Manager de Hostinger.
- **Siempre proporcionar el código completo del archivo** (no solo diffs), para que el usuario pueda pegarlo entero.
- No asumir que los cambios del repo están en producción.

## Estructura

- PHP puro, sin framework
- `includes/head.php` define `$base_url` y la función `imgUrl()`
- `includes/db.php` define `$pdo`
- Admin en `/admin/`
- CSS en `/css/pages/` (una hoja por página)

## Función imgUrl()

Definida en `includes/head.php`. Evita el bug de URL doble cuando la BD guarda rutas absolutas:

```php
function imgUrl(string $imagen, string $base_url): string {
  if (empty($imagen)) return '';
  if (str_starts_with($imagen, 'http://') || str_starts_with($imagen, 'https://')) return $imagen;
  $raw = ltrim($imagen, '/');
  if (str_starts_with($raw, 'caroltemp/')) $raw = substr($raw, 10);
  return $base_url . $raw;
}
```

## Fix TinyMCE (admin)

TinyMCE corrompe el HTML al guardar (elimina SVGs, escapa PHP, mete `&nbsp;`).

**Archivos afectados:**
- `admin/nuevo-articulo.php`
- `admin/nuevo-proyecto.php`
- `admin/nueva-pagina.php`

**Añadir estas opciones a `tinymce.init({...})`** (antes del cierre `}`):

```js
  verify_html: false,
  cleanup: false,
  valid_elements: '*[*]',
  extended_valid_elements: '*[*]',
  allow_script_urls: true
```

## Rutas de URLs

- Artículos: `/noticias/` (no `/blog/`)
- Proyectos: `/proyectos/`
- Filtros por zona: `/noticias/zona/elda`, `/proyectos/zona/elda`
- Filtros por categoría: `/noticias/categoria/nombre`
- Filtros por servicio: `/proyectos/servicio/nombre`
