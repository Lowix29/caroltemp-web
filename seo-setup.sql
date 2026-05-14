-- ============================================================
--  CAROLTEMP — Tablas para el Agente SEO
--  Ejecutar una sola vez en phpMyAdmin
-- ============================================================

-- Tabla de informes de posicionamiento (datos de Google Search Console)
CREATE TABLE IF NOT EXISTS seo_informes (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  url         VARCHAR(500)   NOT NULL,
  clicks      INT            DEFAULT 0,
  impresiones INT            DEFAULT 0,
  posicion    DECIMAL(5,2)   DEFAULT 0,
  ctr         DECIMAL(5,2)   DEFAULT 0,
  fecha       DATE           NOT NULL,
  fuente      VARCHAR(50)    DEFAULT 'GSC',
  created_at  TIMESTAMP      DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_url   (url(255)),
  INDEX idx_fecha (fecha)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de carpetas de medios por artículo/proyecto
CREATE TABLE IF NOT EXISTS seo_media (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  slug       VARCHAR(255)                    NOT NULL,
  tipo       ENUM('articulo','proyecto')     NOT NULL,
  carpeta    VARCHAR(500)                    NOT NULL,
  archivos   TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
