<?php
/* =============================================
   CAROLTEMP — Agente de Páginas
   Gestión de páginas estructurales del sitio
============================================= */
ob_start();
error_reporting(0);
ini_set('display_errors', 0);

session_start();
if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {
  header('Location: login.php');
  exit;
}
require_once '../includes/db.php';

$base_url = 'http://localhost/';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Agente de Páginas — CarolTemp Admin</title>
  <meta name="robots" content="noindex, nofollow">
  <?php include '../includes/admin_style.php'; ?>
  <style>
    /* ── Layout ── */
    .ap-wrap {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1.5rem;
      align-items: start;
    }
    @media (max-width: 1100px) {
      .ap-wrap { grid-template-columns: 1fr; }
    }

    /* ── Paneles ── */
    .ap-panel {
      background: #fff;
      border: 1px solid #e2e8f0;
      border-radius: 14px;
      overflow: hidden;
    }
    .ap-panel-head {
      padding: 1.25rem 1.5rem;
      border-bottom: 1px solid #f1f5f9;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 1rem;
    }
    .ap-panel-title {
      font-size: 12px;
      font-weight: 700;
      color: #8FA3B8;
      letter-spacing: .07em;
      text-transform: uppercase;
    }
    .ap-panel-body { padding: 1.5rem; }

    /* ── Tabs ── */
    .ap-tabs {
      display: flex;
      gap: 0;
      border-bottom: 2px solid #f1f5f9;
      padding: 0 1.5rem;
      background: #fafbfc;
    }
    .ap-tab-btn {
      padding: .75rem 1.25rem;
      font-size: 13px;
      font-weight: 600;
      color: #8FA3B8;
      background: none;
      border: none;
      border-bottom: 2px solid transparent;
      margin-bottom: -2px;
      cursor: pointer;
      transition: color .15s, border-color .15s;
      white-space: nowrap;
    }
    .ap-tab-btn:hover { color: #1976D2; }
    .ap-tab-btn.active {
      color: #1976D2;
      border-bottom-color: #1976D2;
    }
    .ap-tab-content { display: none; }
    .ap-tab-content.active { display: block; }

    /* ── Tabla matriz ── */
    .matriz-table {
      width: 100%;
      border-collapse: collapse;
      font-size: 13px;
    }
    .matriz-table th {
      background: #F8FAFC;
      color: #576574;
      font-size: 11px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: .06em;
      padding: .625rem .875rem;
      text-align: center;
      border: 1px solid #E8EFF8;
      white-space: nowrap;
    }
    .matriz-table th:first-child { text-align: left; }
    .matriz-table td {
      padding: .5rem .875rem;
      border: 1px solid #F1F5F9;
      text-align: center;
      vertical-align: middle;
    }
    .matriz-table td:first-child {
      text-align: left;
      font-weight: 600;
      color: #0B2447;
      white-space: nowrap;
    }
    .matriz-table tr:hover td { background: #FAFBFC; }

    /* ── Estado celdas ── */
    .cell-ok {
      display: inline-flex;
      align-items: center;
      gap: .25rem;
      color: #16a34a;
      font-size: 12px;
      font-weight: 600;

    }
    .cell-ok-icon { font-size: 14px; }

    .cell-prov {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: .3rem;
    }
    .cell-prov-lbl {
      font-size: 11px;
      color: #D97706;
      font-weight: 700;
    }

    .cell-falta {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: .3rem;
    }
    .cell-falta-lbl {
      font-size: 11px;
      color: #DC2626;
      font-weight: 700;
    }

    .btn-accion {
      display: inline-block;
      font-size: 11px;
      font-weight: 600;
      padding: 3px 10px;
      border-radius: 100px;
      border: none;
      cursor: pointer;
      white-space: nowrap;
      transition: opacity .15s;
      line-height: 1.5;
    }
    .btn-accion:hover { opacity: .78; }
    .btn-mejorar {
      background: #FEF3C7;
      color: #92400E;
    }
    .btn-crear {
      background: #FEE2E2;
      color: #B91C1C;
    }
    .btn-regen {
      background: #EEF4FF;
      color: #1976D2;
    }
    .cell-ok-wrap {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: .3rem;
    }

    /* ── Plan del auditor ── */
    .plan-info-bar {
      background: #f8fafc;
      border-bottom: 1px solid #f1f5f9;
      padding: .75rem 1.5rem;
      font-size: 12px;
      color: #576574;
      display: flex;
      gap: 1rem;
      flex-wrap: wrap;
      align-items: center;
    }
    .plan-info-bar strong { color: #0B2447; }

    .plan-group-header {
      font-size: 10px;
      font-weight: 700;
      letter-spacing: .1em;
      text-transform: uppercase;
      color: #8FA3B8;
      padding: 1rem 1.5rem .375rem;
    }

    .plan-accion-card {
      display: flex;
      align-items: flex-start;
      gap: .875rem;
      padding: .875rem 1.5rem;
      border-bottom: 1px solid #f8fafc;
      transition: background .1s;
      border-left: 3px solid transparent;
    }
    .plan-accion-card:hover { background: #fafbff; }
    .plan-accion-card.completado { opacity: .45; }
    .plan-accion-card.ignorado  { opacity: .3; }

    .plan-accion-left { flex: 1; min-width: 0; }

    .plan-accion-badges {
      display: flex;
      align-items: center;
      gap: .375rem;
      margin-bottom: .3rem;
      flex-wrap: wrap;
    }
    .plan-accion-badge {
      font-size: 10px;
      font-weight: 700;
      padding: 2px 8px;
      border-radius: 100px;
      text-transform: uppercase;
      letter-spacing: .05em;
    }
    .badge-tipo {
      background: #f1f5f9;
      color: #576574;
      font-size: 10px;
      font-weight: 600;
      padding: 2px 7px;
      border-radius: 100px;
    }
    .plan-accion-filepath {
      font-family: monospace;
      font-size: 11.5px;
      color: #1e3a5f;
      margin-bottom: .25rem;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      max-width: 280px;
    }
    .plan-accion-card.completado .plan-accion-filepath {
      text-decoration: line-through;
      color: #94a3b8;
    }
    .plan-accion-motivo {
      font-size: 11.5px;
      color: #8FA3B8;
      line-height: 1.4;
    }

    .plan-accion-right {
      display: flex;
      flex-direction: column;
      align-items: flex-end;
      gap: .375rem;
      flex-shrink: 0;
    }
    .btn-plan {
      font-size: 11.5px;
      font-weight: 700;
      padding: 4px 12px;
      border-radius: 100px;
      border: none;
      cursor: pointer;
      transition: opacity .15s;
      white-space: nowrap;
    }
    .btn-plan:hover { opacity: .8; }
    .btn-plan-crear    { background: #dcfce7; color: #15803d; }
    .btn-plan-mejorar  { background: #fef3c7; color: #b45309; }
    .btn-plan-redirigir { background: #dbeafe; color: #1d4ed8; }
    .btn-plan-eliminar { background: #fee2e2; color: #b91c1c; }
    .btn-plan-ok       {
      font-size: 11px; color: #94a3b8;
      background: none; border: none; cursor: default; padding: 4px 8px;
    }
    .btn-plan-rehacer  { background: #f1f5f9; color: #475569; }

    /* ── Plan empty state ── */
    .plan-empty {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      min-height: 260px;
      text-align: center;
      gap: .75rem;
      padding: 2rem;
    }
    .plan-empty-icon { font-size: 2.5rem; opacity: .25; }
    .plan-empty p { font-size: 14px; color: #8FA3B8; line-height: 1.5; }
    .btn-ir-auditor {
      display: inline-flex;
      align-items: center;
      gap: .4rem;
      background: #1976D2;
      color: #fff;
      text-decoration: none;
      padding: .625rem 1.25rem;
      border-radius: 8px;
      font-size: 13.5px;
      font-weight: 600;
      transition: background .15s;
    }
    .btn-ir-auditor:hover { background: #1565C0; }

    /* ── Loading spinner ── */
    .ap-loading {
      display: none;
      padding: 3.5rem 1.5rem;
      text-align: center;
    }
    .spinner {
      width: 40px;
      height: 40px;
      border: 3px solid #E8EFF8;
      border-top-color: #1976D2;
      border-radius: 50%;
      animation: spin .75s linear infinite;
      margin: 0 auto 1.25rem;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
    .ap-loading p { color: #576574; font-size: 14px; font-weight: 600; margin: 0 0 .375rem; }
    .ap-loading small { font-size: 12px; color: #8FA3B8; }

    /* ── Empty state editor ── */
    .ap-empty {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      min-height: 320px;
      color: #8FA3B8;
      text-align: center;
      gap: .625rem;
      padding: 2rem;
    }
    .ap-empty-icon { font-size: 3rem; opacity: .3; }
    .ap-empty p { font-size: 14px; line-height: 1.6; }
    .ap-empty strong { color: #576574; }

    /* ── Resultado editor ── */
    .ap-result { display: none; padding: 1.5rem; }

    .result-context {
      background: #EEF4FF;
      border: 1px solid #BFDBFE;
      border-radius: 10px;
      padding: .875rem 1.125rem;
      margin-bottom: 1.25rem;
      font-size: 13px;
      color: #1E3A5F;
      line-height: 1.5;
    }
    .result-context strong { color: #1976D2; }

    /* ── Campos de preview ── */
    .pv-field { margin-bottom: 1rem; }
    .pv-field label {
      display: flex;
      align-items: center;
      justify-content: space-between;
      font-size: 11px;
      font-weight: 700;
      color: #8FA3B8;
      text-transform: uppercase;
      letter-spacing: .06em;
      margin-bottom: .375rem;
    }
    .pv-field input, .pv-field textarea {
      width: 100%;
      padding: .625rem .875rem;
      border: 1.5px solid #D6E2F0;
      border-radius: 8px;
      font-size: 13.5px;
      color: #0B2447;
      font-family: inherit;
      box-sizing: border-box;
    }
    .pv-field input:focus, .pv-field textarea:focus {
      outline: none;
      border-color: #1976D2;
    }
    .pv-field textarea {
      resize: vertical;
      font-family: monospace;
      font-size: 12.5px;
      line-height: 1.55;
    }
    .char-info { font-size: 11px; font-weight: 600; }
    .char-info.ok   { color: #16a34a; }
    .char-info.warn { color: #DC2626; }
    .char-info.neu  { color: #8FA3B8; }

    /* ── Campo imagen destacada ── */
    .pv-img-row {
      display: flex;
      gap: .5rem;
      align-items: stretch;
    }
    .pv-img-row input { flex: 1; }
    .btn-img-clear {
      padding: .4rem .75rem;
      background: #f1f5f9;
      border: 1.5px solid #D6E2F0;
      border-radius: 8px;
      font-size: 12px;
      cursor: pointer;
      color: #64748b;
      white-space: nowrap;
    }
    .btn-img-clear:hover { background: #e2e8f0; }
    .pv-img-preview {
      margin-top: .5rem;
      border-radius: 8px;
      overflow: hidden;
      max-height: 90px;
      display: none;
    }
    .pv-img-preview img {
      width: 100%;
      height: 90px;
      object-fit: cover;
      display: block;
    }

    /* ── Botón guardar ── */
    .btn-guardar-disco {
      width: 100%;
      padding: .875rem;
      background: #16a34a;
      color: #fff;
      border: none;
      border-radius: 10px;
      font-size: 15px;
      font-weight: 700;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: .5rem;
      transition: background .15s;
      margin-top: .75rem;
    }
    .btn-guardar-disco:hover:not(:disabled) { background: #15803d; }
    .btn-guardar-disco:disabled { opacity: .55; cursor: not-allowed; }

    /* ── Chat refinamiento ── */
    .refinar-box {
      margin-top: 1rem;
      border: 1.5px solid #D6E2F0;
      border-radius: 10px;
      overflow: hidden;
    }
    .refinar-header {
      display: flex;
      align-items: center;
      gap: .5rem;
      padding: .625rem 1rem;
      background: #EEF4FF;
      cursor: pointer;
      font-size: 13px;
      font-weight: 700;
      color: #1976D2;
      user-select: none;
    }
    .refinar-header:hover { background: #DBEAFE; }
    .refinar-body { padding: .875rem 1rem; display: none; }
    .refinar-box.open .refinar-body { display: block; }
    .refinar-box.open .refinar-chevron { transform: rotate(180deg); }
    .refinar-chevron { margin-left: auto; transition: transform .2s; }
    .refinar-historial {
      max-height: 220px;
      overflow-y: auto;
      margin-bottom: .75rem;
      display: flex;
      flex-direction: column;
      gap: .5rem;
    }
    .refinar-msg {
      padding: .5rem .75rem;
      border-radius: 8px;
      font-size: 12.5px;
      line-height: 1.4;
    }
    .refinar-msg.user { background: #EEF4FF; color: #0B2447; align-self: flex-end; max-width: 85%; }
    .refinar-msg.ai   { background: #F0FDF4; color: #14532d; border: 1px solid #BBF7D0; }
    .refinar-input-row {
      display: flex;
      gap: .5rem;
    }
    .refinar-input {
      flex: 1;
      padding: .5rem .75rem;
      border: 1.5px solid #D6E2F0;
      border-radius: 8px;
      font-size: 13px;
      font-family: inherit;
      color: #0B2447;
    }
    .refinar-input:focus { outline: none; border-color: #1976D2; }
    .btn-refinar-send {
      padding: .5rem .875rem;
      background: #1976D2;
      color: #fff;
      border: none;
      border-radius: 8px;
      font-size: 13px;
      font-weight: 700;
      cursor: pointer;
      white-space: nowrap;
      transition: background .15s;
    }
    .btn-refinar-send:hover:not(:disabled) { background: #1565C0; }
    .btn-refinar-send:disabled { opacity: .5; cursor: not-allowed; }

    /* ── Redirect result ── */
    .redirect-code {
      background: #1e293b;
      color: #7dd3fc;
      font-family: monospace;
      font-size: 13px;
      padding: 1rem 1.25rem;
      border-radius: 10px;
      margin: .875rem 0;
      word-break: break-all;
      position: relative;
    }
    .btn-copy {
      display: inline-flex;
      align-items: center;
      gap: .375rem;
      background: #f1f5f9;
      color: #576574;
      border: 1.5px solid #D6E2F0;
      border-radius: 7px;
      font-size: 12.5px;
      font-weight: 600;
      padding: .45rem .875rem;
      cursor: pointer;
      transition: all .15s;
    }
    .btn-copy:hover { background: #e2e8f0; border-color: #8FA3B8; color: #0B2447; }
    .redirect-info {
      font-size: 12.5px;
      color: #576574;
      margin: .625rem 0 1rem;
      line-height: 1.5;
    }
    .btn-marcar-aplicada {
      width: 100%;
      padding: .75rem;
      background: #1976D2;
      color: #fff;
      border: none;
      border-radius: 10px;
      font-size: 14px;
      font-weight: 700;
      cursor: pointer;
      transition: background .15s;
      margin-top: .5rem;
    }
    .btn-marcar-aplicada:hover { background: #1565C0; }

    /* ── Delete warning ── */
    .delete-warning {
      background: #FEF2F2;
      border: 1.5px solid #FCA5A5;
      border-radius: 10px;
      padding: 1.125rem 1.25rem;
      margin-bottom: 1.25rem;
    }
    .delete-warning h3 {
      color: #991B1B;
      font-size: 15px;
      font-weight: 700;
      margin-bottom: .5rem;
    }
    .delete-warning p {
      font-size: 13px;
      color: #B91C1C;
      line-height: 1.5;
    }
    .delete-filepath {
      font-family: monospace;
      font-size: 12px;
      background: #FEE2E2;
      padding: .375rem .625rem;
      border-radius: 6px;
      margin: .5rem 0;
      color: #7f1d1d;
    }
    .delete-backup-note {
      font-size: 11.5px;
      color: #9CA3AF;
      margin-top: .5rem;
    }
    .delete-actions {
      display: flex;
      gap: .75rem;
      margin-top: 1rem;
    }
    .btn-cancelar {
      flex: 1;
      padding: .75rem;
      background: #f1f5f9;
      color: #576574;
      border: 1.5px solid #D6E2F0;
      border-radius: 8px;
      font-size: 14px;
      font-weight: 600;
      cursor: pointer;
      transition: all .15s;
    }
    .btn-cancelar:hover { background: #e2e8f0; }
    .btn-eliminar-confirm {
      flex: 1;
      padding: .75rem;
      background: #DC2626;
      color: #fff;
      border: none;
      border-radius: 8px;
      font-size: 14px;
      font-weight: 700;
      cursor: pointer;
      transition: background .15s;
    }
    .btn-eliminar-confirm:hover { background: #b91c1c; }
    .btn-eliminar-confirm:disabled { opacity: .55; cursor: not-allowed; }

    /* ── Notificación flash ── */
    .ap-flash {
      display: none;
      padding: .75rem 1.125rem;
      border-radius: 8px;
      font-size: 13.5px;
      font-weight: 600;
      margin-bottom: 1rem;
      align-items: center;
      gap: .5rem;
    }
    .ap-flash.ok  { background: #D1FAE5; border: 1px solid #A7F3D0; color: #065F46; display: flex; }
    .ap-flash.err { background: #FEE2E2; border: 1px solid #FCA5A5; color: #991B1B; display: flex; }

    /* ── Loading overlay tabla ── */
    .matriz-loading {
      padding: 3rem 1.5rem;
      text-align: center;
      color: #8FA3B8;
      font-size: 14px;
    }

    /* ── Leyenda ── */
    .leyenda {
      display: flex;
      gap: 1.25rem;
      flex-wrap: wrap;
      padding: .875rem 1.5rem;
      border-top: 1px solid #F1F5F9;
      font-size: 12px;
      color: #576574;
    }
    .leyenda-item { display: flex; align-items: center; gap: .375rem; }

    /* ── Sección corporativas ── */
    .corp-section-head {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin: 2rem 0 .875rem;
      gap: 1rem;
      padding-left:10px;
      padding-right:10px;
    }
    .corp-section-title {
      font-size: 11px;
      font-weight: 700;
      color: #8FA3B8;
      text-transform: uppercase;
      letter-spacing: .08em;
    }
    .btn-add-corp {
      display: inline-flex;
      align-items: center;
      gap: .3rem;
      font-size: 12px;
      font-weight: 600;
      color: #1976D2;
      background: #EEF4FF;
      border: none;
      border-radius: 100px;
      padding: 4px 12px;
      cursor: pointer;
      transition: background .15s;
    }
    .btn-add-corp:hover { background: #dbeafe; }
    .corp-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(190px, 1fr));
      gap: .75rem;
      padding: 10px;
    }
    .corp-card {
      border-radius: 10px;
      border: 1px solid #e2e8f0;
      background: #fff;
      padding: .875rem 1rem;
      display: flex;
      flex-direction: column;
      gap: .5rem;
    }
    .corp-card-top {
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      gap: .375rem;
    }
    .corp-card-label {
      font-size: 13px;
      font-weight: 700;
      color: #0B2447;
      line-height: 1.3;
    }
    .corp-card-fp {
      font-family: monospace;
      font-size: 10px;
      color: #94a3b8;
    }
    .corp-card-status-ok   { font-size: 11px; color: #16a34a; font-weight: 700; }
    .corp-card-status-miss { font-size: 11px; color: #DC2626; font-weight: 700; }
    .corp-card-btns {
      display: flex;
      gap: .375rem;
      flex-wrap: wrap;
      margin-top: .125rem;
    }
    .corp-card-btns .btn-accion { font-size: 11px; }
    .btn-corp-del {
      font-size: 10px;
      background: none;
      border: none;
      color: #94a3b8;
      cursor: pointer;
      padding: 2px 4px;
      border-radius: 4px;
      line-height: 1;
    }
    .btn-corp-del:hover { color: #DC2626; background: #fee2e2; }

    /* ── Formulario nueva corporativa ── */
    .corp-add-form {
      display: none;
      background: #f8fafc;
      border: 1px solid #e2e8f0;
      border-radius: 10px;
      padding: 1rem;
      margin-bottom: .75rem;
    }
    .corp-add-form.open { display: block; }
    .corp-add-form label {
      font-size: 12px;
      font-weight: 600;
      color: #576574;
      display: block;
      margin-bottom: .25rem;
    }
    .corp-add-form input {
      width: 100%;
      border: 1px solid #e2e8f0;
      border-radius: 6px;
      padding: .4rem .65rem;
      font-size: 13px;
      margin-bottom: .625rem;
      box-sizing: border-box;
    }
    .corp-add-row {
      display: flex;
      gap: .5rem;
    }
    .corp-add-row button {
      font-size: 12px;
      font-weight: 600;
      padding: .4rem .875rem;
      border-radius: 6px;
      border: none;
      cursor: pointer;
    }
    .btn-corp-save  { background: #1976D2; color: #fff; }
    .btn-corp-cancel { background: #f1f5f9; color: #576574; }

    /* ── Error matriz ── */
    .matriz-error {
      background: #FEF2F2;
      border: 1px solid #fecaca;
      border-radius: 10px;
      padding: 1rem 1.25rem;
      color: #dc2626;
      font-size: 13.5px;
      margin: 1rem 1.5rem;
    }

    /* ── Botón recargar ── */
    .btn-reload {
      background: none;
      border: 1.5px solid #D6E2F0;
      border-radius: 7px;
      padding: .375rem .875rem;
      font-size: 12px;
      color: #576574;
      cursor: pointer;
      font-weight: 600;
      transition: all .15s;
    }
    .btn-reload:hover { border-color: #8FA3B8; color: #0B2447; }

    /* ── No disponible mensaje ── */
    .tipo-no-disponible {
      background: #f8fafc;
      border: 1px solid #e2e8f0;
      border-radius: 10px;
      padding: 1.125rem 1.25rem;
      font-size: 13px;
      color: #576574;
      line-height: 1.5;
      margin-bottom: 1rem;
    }
  </style>
</head>
<body>

<?php include '../includes/admin_sidebar.php'; ?>

<main class="main">

  <div class="topbar">
    <div>
      <h1 style="font-size:20px;font-weight:700;color:#0f172a;letter-spacing:-.01em">🗂️ Agente de Páginas</h1>
      <p style="color:#64748b;font-size:13.5px;margin-top:.25rem">Gestión y mejora de páginas estructurales del sitio</p>
    </div>
  </div>

  <div class="ap-wrap">

    <!-- ══════════════════════════════════════════════════════ -->
    <!-- PANEL IZQUIERDO: COLA DE TRABAJO                       -->
    <!-- ══════════════════════════════════════════════════════ -->
    <div class="ap-panel">
      <div class="ap-panel-head">
        <span class="ap-panel-title">🗂️ Cola de trabajo</span>
        <button class="btn-reload" id="btn-reload-panel" onclick="recargarTab()">↻ Actualizar</button>
      </div>

      <!-- Tabs -->
      <div class="ap-tabs">
        <button class="ap-tab-btn active" id="tab-btn-plan" onclick="cambiarTab('plan')">📋 Plan del Auditor</button>
        <button class="ap-tab-btn" id="tab-btn-mapa" onclick="cambiarTab('mapa')">🗺️ Mapa de páginas</button>
      </div>

      <!-- Tab 1: Plan del Auditor -->
      <div class="ap-tab-content active" id="tab-plan">
        <div id="plan-wrap">
          <div class="matriz-loading">
            <div class="spinner" style="margin:0 auto .875rem"></div>
            Cargando plan del auditor...
          </div>
        </div>
      </div>

      <!-- Tab 2: Mapa de páginas -->
      <div class="ap-tab-content" id="tab-mapa">
        <div id="matriz-wrap">
          <div class="matriz-loading" style="color:#8FA3B8">
            Activa esta pestaña para cargar el mapa de páginas...
          </div>
        </div>

        <div class="leyenda">
          <div class="leyenda-item">
            <span style="color:#16a34a;font-size:14px">✓</span>
            <span>Contenido OK</span>
          </div>
          <div class="leyenda-item">
            <span style="background:#FEF3C7;color:#92400E;font-size:10px;font-weight:700;padding:2px 6px;border-radius:4px">⚠</span>
            <span>Provisional</span>
          </div>
          <div class="leyenda-item">
            <span style="background:#FEE2E2;color:#B91C1C;font-size:10px;font-weight:700;padding:2px 6px;border-radius:4px">✗</span>
            <span>No existe</span>
          </div>
        </div>
      </div>
    </div>

    <!-- ══════════════════════════════════════════════════════ -->
    <!-- PANEL DERECHO: EDITOR                                  -->
    <!-- ══════════════════════════════════════════════════════ -->
    <div class="ap-panel">
      <div class="ap-panel-head">
        <span class="ap-panel-title">✨ Editor IA</span>
        <span id="editor-ctx-badge" style="display:none;font-size:11px;background:#EEF4FF;color:#1976D2;font-weight:700;padding:3px 10px;border-radius:100px"></span>
      </div>

      <!-- Loading -->
      <div class="ap-loading" id="ap-loading">
        <div class="spinner"></div>
        <p>El agente está generando el contenido...</p>
        <small id="ap-loading-tip">Esto puede tardar 20-30 segundos</small>
      </div>

      <!-- Empty state -->
      <div class="ap-empty" id="ap-empty">
        <div class="ap-empty-icon">📄</div>
        <p><strong>Selecciona una acción del plan</strong><br>o una página del mapa para empezar</p>
        <p style="font-size:12px">Usa las pestañas de la izquierda para navegar<br>entre el plan del auditor y el mapa de páginas</p>
      </div>

      <!-- Resultado -->
      <div class="ap-result" id="ap-result">

        <div id="ap-flash" class="ap-flash"></div>

        <!-- Contenido CREAR / MEJORAR -->
        <div id="result-crear-mejorar" style="display:none">
          <div class="result-context" id="result-context-cm"></div>

          <div class="pv-field">
            <label>
              Meta Title
              <span class="char-info neu" id="mt-info"></span>
            </label>
            <input type="text" id="pv-meta-title" maxlength="80"
                   oninput="contarChars(this,'mt-info',60)">
          </div>

          <div class="pv-field">
            <label>
              Meta Description
              <span class="char-info neu" id="md-info"></span>
            </label>
            <input type="text" id="pv-meta-desc" maxlength="200"
                   oninput="contarChars(this,'md-info',160)">
          </div>

          <div class="pv-field">
            <label>Imagen destacada <span style="font-weight:400;color:#B0C4D8;text-transform:none;letter-spacing:0">— se pone de fondo en el hero (opcional)</span></label>
            <div class="pv-img-row">
              <input type="text" id="pv-img-destacada"
                     placeholder="/uploads/foto-pinoso.jpg  o  https://…"
                     oninput="inyectarImagenHero(this.value)">
              <button type="button" class="btn-img-clear"
                      onclick="document.getElementById('pv-img-destacada').value='';inyectarImagenHero('')">✕ Quitar</button>
            </div>
            <div class="pv-img-preview" id="pv-img-preview"></div>
          </div>

          <div class="pv-field">
            <label>
              Contenido PHP generado <span style="font-weight:400;color:#B0C4D8;text-transform:none;letter-spacing:0">(editable antes de guardar)</span>
              <button type="button" onclick="abrirPreview()" style="margin-left:auto;background:#EEF4FF;color:#1976D2;border:1.5px solid #BFDBFE;border-radius:6px;padding:.2rem .65rem;font-size:11px;font-weight:700;cursor:pointer;text-transform:none;letter-spacing:0">👁 Preview visual</button>
            </label>
            <textarea id="pv-php-content" rows="24"></textarea>
          </div>

          <input type="hidden" id="pv-filepath">
          <input type="hidden" id="pv-plan-accion-id" value="">

          <button class="btn-guardar-disco" id="btn-guardar" onclick="guardar()">
            💾 Guardar en disco
          </button>

          <!-- Chat refinamiento -->
          <div class="refinar-box" id="refinar-box">
            <div class="refinar-header" onclick="toggleRefinar()">
              <span>💬</span>
              <span>Refinar con IA</span>
              <span style="font-size:11px;font-weight:400;color:#4B6CB7">— pide cambios al agente</span>
              <span class="refinar-chevron">▾</span>
            </div>
            <div class="refinar-body">
              <div class="refinar-historial" id="refinar-historial"></div>
              <div class="refinar-input-row">
                <input type="text" class="refinar-input" id="refinar-input"
                       placeholder="Ej: quita todo lo de climatización, cambia el título del hero..."
                       onkeydown="if(event.key==='Enter' && !event.shiftKey){event.preventDefault();enviarRefinar();}">
                <button class="btn-refinar-send" id="btn-refinar-send" onclick="enviarRefinar()">Enviar</button>
              </div>
            </div>
          </div>
        </div>

        <!-- Contenido REDIRIGIR -->
        <div id="result-redirigir" style="display:none">
          <div class="result-context" id="result-context-red"></div>
          <p style="font-size:13px;color:#576574;margin-bottom:.25rem">Regla .htaccess a añadir:</p>
          <div class="redirect-code" id="redirect-code-block"></div>
          <div style="display:flex;gap:.75rem;margin-bottom:.625rem">
            <button class="btn-copy" onclick="copiarRegla()">📋 Copiar regla</button>
          </div>
          <div class="redirect-info" id="redirect-instruccion"></div>
          <button class="btn-marcar-aplicada" id="btn-marcar-aplicada" onclick="marcarRedireccionAplicada()">
            ✓ Marcar como aplicada
          </button>
        </div>

        <!-- Contenido ELIMINAR -->
        <div id="result-eliminar" style="display:none">
          <div class="delete-warning">
            <h3>⚠️ ¿Seguro que quieres eliminar esta página?</h3>
            <div class="delete-filepath" id="delete-filepath-display"></div>
            <p class="delete-backup-note">📦 Se creará una copia de seguridad (.bak) antes de eliminar</p>
          </div>
          <div class="delete-actions">
            <button class="btn-cancelar" onclick="mostrarEstadoEditor('empty')">Cancelar</button>
            <button class="btn-eliminar-confirm" id="btn-eliminar-confirm" onclick="ejecutarEliminar()">
              🗑️ Eliminar página
            </button>
          </div>
        </div>

      </div>
    </div>

  </div>

</main>

<!-- ── Modal Preview ───────────────────────────────────────────────── -->
<div id="preview-modal" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,.7);flex-direction:column">
  <div style="display:flex;align-items:center;justify-content:space-between;padding:.75rem 1.25rem;background:#0B2447;color:#fff;flex-shrink:0">
    <span style="font-weight:700;font-size:14px">👁 Preview visual — <span id="preview-label" style="font-weight:400;color:#B0C4D8"></span></span>
    <div style="display:flex;gap:.5rem;align-items:center">
      <span style="font-size:11px;color:#8FA3B8">Solo se muestra la zona editable (sin proyectos/artículos dinámicos)</span>
      <button onclick="cerrarPreview()" style="background:#DC2626;color:#fff;border:none;border-radius:6px;padding:.35rem .875rem;font-size:13px;font-weight:700;cursor:pointer">✕ Cerrar</button>
    </div>
  </div>
  <iframe id="preview-frame" style="flex:1;border:none;background:#fff" sandbox="allow-same-origin allow-scripts"></iframe>
</div>

<script>
// ── Estado global ────────────────────────────────────────────────────────────
let filepathActual     = '';
let planAccionIdActual = 0;
let deleteFilepathActual = '';
let deleteAccionId       = 0;
let redirectAccionId     = 0;
let redirectReglaActual  = '';
let mapaYaCargado = false;
let tabActual = 'plan';

// ── Mapa de ciudades para parsear filepath del plan ──────────────────────────
const CIUDADES_MAP = {
  'elda':     { nombre: 'Elda',             cp: '03600' },
  'petrer':   { nombre: 'Petrer',           cp: '03610' },
  'novelda':  { nombre: 'Novelda',          cp: '03660' },
  'monovar':  { nombre: 'Monóvar',          cp: '03640' },
  'sax':      { nombre: 'Sax',              cp: '03630' },
  'pinoso':   { nombre: 'Pinoso',           cp: '03650' },
  'monforte': { nombre: 'Monforte del Cid', cp: '03670' },
  'salinas':  { nombre: 'Salinas',          cp: '03688' },
  'aspe':     { nombre: 'Aspe',             cp: '03680' },
};

// Búsqueda tolerante: admite mayúsculas, acentos y guiones distintos
// p.ej. "Monforte-del-Cid" → { slug:'monforte', nombre:'Monforte del Cid', cp:'...' }
function encontrarCiudad(str) {
  if (!str) return null;
  if (CIUDADES_MAP[str]) return { slug: str, ...CIUDADES_MAP[str] };
  const norm = function(s) {
    return s.toLowerCase()
      .normalize('NFD').replace(/[̀-ͯ]/g, '') // quitar acentos
      .replace(/[\s\-_]+/g, '');                         // quitar separadores
  };
  const key = norm(str);
  for (const [slug, info] of Object.entries(CIUDADES_MAP)) {
    if (norm(slug) === key || norm(info.nombre) === key) {
      return { slug, ...info };
    }
  }
  return null;
}
const PREFIJOS_MAP = {
  'fugas':      'deteccion-fugas-',
  'desatascos': 'desatascos-',
  'fontanero':  'fontanero-',
};

// ── Colores por acción ────────────────────────────────────────────────────────
const ACCION_COLORS = {
  'CREAR':     '#16a34a',
  'MEJORAR':   '#d97706',
  'REDIRIGIR': '#1976D2',
  'ELIMINAR':  '#dc2626',
  'MANTENER':  '#94a3b8',
};
const ACCION_BG = {
  'CREAR':     '#dcfce7',
  'MEJORAR':   '#fef3c7',
  'REDIRIGIR': '#dbeafe',
  'ELIMINAR':  '#fee2e2',
  'MANTENER':  '#f1f5f9',
};

// ── Auto-carga al arrancar ────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function() {
  cargarPlan();
});

// ── Cambiar pestaña ───────────────────────────────────────────────────────────
function cambiarTab(tab) {
  tabActual = tab;
  document.getElementById('tab-btn-plan').classList.toggle('active', tab === 'plan');
  document.getElementById('tab-btn-mapa').classList.toggle('active', tab === 'mapa');
  document.getElementById('tab-plan').classList.toggle('active', tab === 'plan');
  document.getElementById('tab-mapa').classList.toggle('active', tab === 'mapa');

  if (tab === 'mapa' && !mapaYaCargado) {
    cargarInventario();
  }
}

function recargarTab() {
  if (tabActual === 'plan') {
    cargarPlan();
  } else {
    mapaYaCargado = false;
    cargarInventario();
  }
}

// ── Cargar plan del auditor ───────────────────────────────────────────────────
async function cargarPlan() {
  const wrap = document.getElementById('plan-wrap');
  wrap.innerHTML = '<div class="matriz-loading"><div class="spinner" style="margin:0 auto .875rem"></div>Cargando plan...</div>';

  try {
    const fd = new FormData();
    fd.append('accion', 'cargar_plan');
    const r    = await fetch('agente-paginas-api.php', { method: 'POST', body: fd });
    const data = await r.json();

    if (!data.ok) {
      // Mostrar empty state con botón al auditor
      wrap.innerHTML =
        '<div class="plan-empty">' +
          '<div class="plan-empty-icon">📋</div>' +
          '<p>No hay ningún plan activo.<br>Genera uno desde el Auditor Estratégico.</p>' +
          '<a href="auditor-estrategico" class="btn-ir-auditor">🔍 Ir al Auditor Estratégico</a>' +
        '</div>';
      return;
    }

    renderPlan(data.plan);
  } catch (e) {
    wrap.innerHTML = '<div class="matriz-error">⚠️ Error de conexión al cargar el plan.</div>';
  }
}

// ── Renderizar plan ───────────────────────────────────────────────────────────
function renderPlan(plan) {
  const wrap = document.getElementById('plan-wrap');

  // Barra de info
  const fechaStr = plan.fecha_generacion
    ? new Date(plan.fecha_generacion).toLocaleDateString('es-ES', { day: '2-digit', month: '2-digit', year: 'numeric' })
    : '—';
  const objetivo = plan.objetivo || '';

  let html = '<div class="plan-info-bar">';
  html += '<span>📅 <strong>' + escp(fechaStr) + '</strong></span>';
  if (objetivo) html += '<span style="flex:1;min-width:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap" title="' + escp(objetivo) + '">' + escp(objetivo.substring(0, 80)) + (objetivo.length > 80 ? '…' : '') + '</span>';
  // estadísticas
  const st = plan.estadisticas || {};
  if (st.paginas_total_ideal) {
    html += '<span style="margin-left:auto;white-space:nowrap">';
    html += '✓ ' + (st.paginas_ok || 0) + ' · ⚠ ' + (st.paginas_provisional || 0) + ' · ✗ ' + (st.paginas_faltantes || 0);
    html += '</span>';
  }
  html += '</div>';

  // Agrupar por prioridad
  const acciones = plan.plan || [];
  const grupos = { alta: [], media: [], baja: [] };
  acciones.forEach(function(a) {
    const p = (a.prioridad || 'baja').toLowerCase();
    if (grupos[p]) grupos[p].push(a);
    else grupos.baja.push(a);
  });

  const labelGrupo = { alta: 'Prioridad Alta', media: 'Prioridad Media', baja: 'Prioridad Baja' };
  ['alta', 'media', 'baja'].forEach(function(prio) {
    if (!grupos[prio].length) return;
    html += '<div class="plan-group-header">' + labelGrupo[prio] + ' <span style="font-size:10px;font-weight:400;color:#b0c4d8">(' + grupos[prio].length + ')</span></div>';
    grupos[prio].forEach(function(accion) {
      html += renderAccionCard(accion);
    });
  });

  if (!acciones.length) {
    html += '<div class="plan-empty"><p>El plan no tiene acciones.</p></div>';
  }

  wrap.innerHTML = html;
}

// ── Renderizar tarjeta de acción ──────────────────────────────────────────────
function renderAccionCard(accion) {
  const accionKey = (accion.accion || '').toUpperCase();
  const estado    = accion.estado || 'pendiente';
  const color     = ACCION_COLORS[accionKey] || '#94a3b8';
  const bgColor   = ACCION_BG[accionKey]     || '#f1f5f9';
  const filepath  = accion.pagina            || '';
  const motivo    = accion.motivo            || '';
  const tipo      = accion.tipo              || '';

  const isPendiente = estado === 'pendiente';

  let card = '<div class="plan-accion-card ' + escp(estado) + '" id="card-' + escp(String(accion.id)) + '" style="border-left-color:' + color + '">';
  card += '<div class="plan-accion-left">';

  // Badges
  card += '<div class="plan-accion-badges">';
  card += '<span class="plan-accion-badge" style="background:' + bgColor + ';color:' + color + '">' + escp(accionKey) + '</span>';
  if (tipo) card += '<span class="badge-tipo">' + escp(tipo) + '</span>';
  card += '</div>';

  // Filepath
  card += '<div class="plan-accion-filepath" title="' + escp(filepath) + '">' + escp(filepath) + '</div>';

  // Motivo
  if (motivo) card += '<div class="plan-accion-motivo">' + escp(motivo) + '</div>';

  card += '</div>'; // left

  // Right: botones de acción (sin dropdown de estado)
  card += '<div class="plan-accion-right">';

  const accionJSON = JSON.stringify(accion);
  const esCompletado = estado === 'completado';

  if (accionKey === 'CREAR' || accionKey === 'MEJORAR') {
    if (esCompletado) {
      card += '<button class="btn-plan btn-plan-rehacer" onclick="ejecutarAccion(' + accionJSON + ')">↻ Rehacer</button>';
    } else {
      if (accionKey === 'CREAR') {
        card += '<button class="btn-plan btn-plan-crear" onclick="ejecutarAccion(' + accionJSON + ')">Crear</button>';
      } else {
        card += '<button class="btn-plan btn-plan-mejorar" onclick="ejecutarAccion(' + accionJSON + ')">Mejorar</button>';
      }
    }
  } else if (accionKey === 'REDIRIGIR') {
    if (esCompletado) {
      card += '<button class="btn-plan btn-plan-rehacer" onclick="verReglaRedireccion(' + accionJSON + ')">↻ Regla</button>';
    } else {
      card += '<button class="btn-plan btn-plan-redirigir" onclick="verReglaRedireccion(' + accionJSON + ')">Ver regla</button>';
    }
  } else if (accionKey === 'ELIMINAR') {
    if (!esCompletado) {
      card += '<button class="btn-plan btn-plan-eliminar" onclick="confirmarEliminar(' + accionJSON + ')">Eliminar</button>';
    }
  }

  card += '</div>'; // right
  card += '</div>'; // card

  return card;
}

// ── ejecutarAccion — desde tarjeta del plan ───────────────────────────────────
async function ejecutarAccion(accionObj) {
  const filepath = accionObj.pagina || '';
  const accionId = accionObj.id     || 0;
  const tipoCard = (accionObj.tipo  || '').toLowerCase();

  const sinExt = filepath.replace(/\.php$/, '');
  const partes = sinExt.split('/');

  // Páginas corporativas (index, contacto, sobre-nosotros…): ruta raíz o tipo explícito
  if (partes.length < 2 || tipoCard === 'corporativa') {
    const accionCorp = (accionObj.accion || '').toLowerCase() === 'crear' ? 'crear' : 'mejorar';
    await lanzarAccion(accionCorp, 'corporativa', '', '', '', filepath, accionId);
    return;
  }

  let ciudadInfo, subTipo;

  if (partes.length >= 3) {
    // Silo sub-page: fontanero/{ciudad}/{servicio}
    ciudadInfo = encontrarCiudad(partes[1]);
    subTipo    = partes[2];
  } else {
    const dir      = partes[0];
    const filename = partes[1];

    if (dir === 'zonas') {
      ciudadInfo = encontrarCiudad(filename);
      subTipo    = 'zona';
    } else if (dir === 'fontanero') {
      ciudadInfo = encontrarCiudad(filename);
      subTipo    = 'hub_ciudad';
    } else {
      const prefijo = PREFIJOS_MAP[dir];
      const slug    = (prefijo && filename.startsWith(prefijo))
        ? filename.substring(prefijo.length)
        : filename;
      ciudadInfo = encontrarCiudad(slug);
      subTipo    = dir;
    }
  }

  if (!ciudadInfo) { mostrarNoDisponible(accionId); return; }

  const apiAccion = (accionObj.accion || '').toLowerCase() === 'crear' ? 'crear' : 'mejorar';
  await lanzarAccion(apiAccion, subTipo, ciudadInfo.nombre, ciudadInfo.slug, ciudadInfo.cp, filepath, accionId);
}

// ── mostrarNoCorporativa ──────────────────────────────────────────────────────
function mostrarNoCorporativa(accionId, filepath) {
  mostrarEstadoEditor('result');
  document.getElementById('result-crear-mejorar').style.display = 'none';
  document.getElementById('result-redirigir').style.display     = 'none';
  document.getElementById('result-eliminar').style.display      = 'none';
  ocultarFlash();

  const prev = document.getElementById('no-disponible-msg');
  if (prev) prev.remove();

  const div = document.createElement('div');
  div.id = 'no-disponible-msg';
  div.className = 'tipo-no-disponible';
  div.innerHTML =
    '<strong>Página corporativa</strong><br>' +
    '<span style="font-family:monospace;font-size:11px">' + escp(filepath) + '</span><br><br>' +
    'Las páginas corporativas (home, contacto, quiénes somos) se editan directamente ' +
    'en su archivo PHP. El agente no las genera automáticamente porque su estructura ' +
    'es única.<br><br>' +
    '<span style="color:#16a34a;font-weight:600">✓ Márcala como completada</span> ' +
    'una vez la hayas revisado manualmente.';
  document.getElementById('ap-result').prepend(div);
}

// ── mostrarNoDisponible ───────────────────────────────────────────────────────
function mostrarNoDisponible(accionId) {
  mostrarEstadoEditor('result');
  document.getElementById('result-crear-mejorar').style.display = 'none';
  document.getElementById('result-redirigir').style.display     = 'none';
  document.getElementById('result-eliminar').style.display      = 'none';
  ocultarFlash();

  const prev = document.getElementById('no-disponible-msg');
  if (prev) prev.remove();

  const div = document.createElement('div');
  div.id = 'no-disponible-msg';
  div.className = 'tipo-no-disponible';
  div.innerHTML = '⚠️ No se pudo identificar la ciudad en la ruta <strong>' + escp(accionId) + '</strong>. ' +
    'Revisa que el plan use slugs de ciudad correctos (elda, petrer, novelda, monovar, sax, pinoso, monforte, salinas, aspe).';
  document.getElementById('ap-result').prepend(div);
}

// ── verReglaRedireccion ───────────────────────────────────────────────────────
async function verReglaRedireccion(accionObj) {
  redirectAccionId = accionObj.id || 0;
  const desde = accionObj.desde || '';
  const hacia  = accionObj.hacia || accionObj.pagina || '';

  if (!desde || !hacia) {
    mostrarEstadoEditor('result');
    mostrarFlash('err', '⚠️ Esta acción no tiene campos desde/hacia definidos en el plan.');
    return;
  }

  try {
    const fd = new FormData();
    fd.append('accion', 'redireccion_htaccess');
    fd.append('desde', desde);
    fd.append('hacia', hacia.startsWith('/') ? hacia : '/' + hacia.replace('.php',''));
    const r    = await fetch('agente-paginas-api.php', { method: 'POST', body: fd });
    const data = await r.json();

    if (!data.ok) {
      mostrarEstadoEditor('result');
      mostrarFlash('err', '⚠️ ' + (data.error || 'Error al generar regla'));
      return;
    }

    mostrarResultadoRedireccion(data, desde, hacia.replace('.php',''));
  } catch (e) {
    mostrarEstadoEditor('result');
    mostrarFlash('err', '⚠️ Error de conexión.');
  }
}

function mostrarResultadoRedireccion(data, desde, hacia) {
  redirectReglaActual = data.regla || '';
  mostrarEstadoEditor('result');
  ocultarFlash();

  document.getElementById('result-crear-mejorar').style.display = 'none';
  document.getElementById('result-redirigir').style.display     = 'block';
  document.getElementById('result-eliminar').style.display      = 'none';

  const noDisp = document.getElementById('no-disponible-msg');
  if (noDisp) noDisp.remove();

  document.getElementById('result-context-red').innerHTML =
    '<strong>Redirección 301:</strong> ' + escp(desde) + ' → ' + escp(hacia);
  document.getElementById('redirect-code-block').textContent = data.regla || '';
  document.getElementById('redirect-instruccion').textContent = data.instruccion || '';

  const badge = document.getElementById('editor-ctx-badge');
  badge.textContent = 'REDIRIGIR';
  badge.style.display = '';
}

function copiarRegla() {
  if (!redirectReglaActual) return;
  navigator.clipboard.writeText(redirectReglaActual).then(function() {
    const btn = document.querySelector('.btn-copy');
    const orig = btn.innerHTML;
    btn.innerHTML = '✓ ¡Copiado!';
    setTimeout(function() { btn.innerHTML = orig; }, 1800);
  });
}

function marcarRedireccionAplicada() {
  if (redirectAccionId > 0) {
    actualizarEstado(redirectAccionId, 'completado');
  }
  mostrarEstadoEditor('empty');
}

// ── confirmarEliminar ─────────────────────────────────────────────────────────
function confirmarEliminar(accionObj) {
  deleteFilepathActual = accionObj.pagina || '';
  deleteAccionId       = accionObj.id     || 0;

  mostrarEstadoEditor('result');
  ocultarFlash();

  document.getElementById('result-crear-mejorar').style.display = 'none';
  document.getElementById('result-redirigir').style.display     = 'none';
  document.getElementById('result-eliminar').style.display      = 'block';

  const noDisp = document.getElementById('no-disponible-msg');
  if (noDisp) noDisp.remove();

  document.getElementById('delete-filepath-display').textContent = deleteFilepathActual;

  const badge = document.getElementById('editor-ctx-badge');
  badge.textContent = 'ELIMINAR';
  badge.style.display = '';
}

function confirmarEliminarMapa(filepath) {
  deleteFilepathActual = filepath;
  deleteAccionId       = 0;
  mostrarEstadoEditor('result');
  document.getElementById('result-crear-mejorar').style.display = 'none';
  document.getElementById('result-redirigir').style.display     = 'none';
  document.getElementById('result-eliminar').style.display      = 'block';
  document.getElementById('delete-filepath-display').textContent = filepath;
  ocultarFlash();
}

async function ejecutarEliminar() {
  if (!deleteFilepathActual) return;

  const btn = document.getElementById('btn-eliminar-confirm');
  btn.disabled    = true;
  btn.textContent = '⏳ Eliminando...';

  try {
    const fd = new FormData();
    fd.append('accion',   'eliminar_pagina');
    fd.append('filepath', deleteFilepathActual);
    const r    = await fetch('agente-paginas-api.php', { method: 'POST', body: fd });
    const data = await r.json();

    btn.disabled    = false;
    btn.innerHTML   = '🗑️ Eliminar página';

    if (data.ok) {
      if (deleteAccionId > 0) {
        actualizarEstado(deleteAccionId, 'completado', false); // false = no recargar plan aquí
      }
      mostrarEstadoEditor('result');
      document.getElementById('result-eliminar').style.display = 'none';
      document.getElementById('result-crear-mejorar').style.display = 'none';
      document.getElementById('result-redirigir').style.display     = 'none';
      mostrarFlash('ok', '✓ Página eliminada. Backup: ' + escp(data.backup || ''));
      // Refrescar mapa y plan siempre
      mapaYaCargado = false;
      setTimeout(function() { cargarInventario(); cargarPlan(); }, 900);
    } else {
      mostrarFlash('err', '⚠️ ' + (data.error || 'Error al eliminar'));
    }
  } catch (e) {
    btn.disabled  = false;
    btn.innerHTML = '🗑️ Eliminar página';
    mostrarFlash('err', '⚠️ Error de conexión al eliminar');
  }
}

// ── actualizarEstado ──────────────────────────────────────────────────────────
async function actualizarEstado(id, estado, recargar) {
  if (recargar === undefined) recargar = true;
  try {
    const fd = new FormData();
    fd.append('accion',  'actualizar_estado_accion');
    fd.append('plan_id', id);
    fd.append('estado',  estado);
    const r    = await fetch('agente-paginas-api.php', { method: 'POST', body: fd });
    const data = await r.json();

    if (data.ok && recargar) {
      cargarPlan();
    } else if (!data.ok) {
      mostrarFlash('err', '⚠️ ' + (data.error || 'Error al actualizar estado'));
    }
  } catch (e) {
    mostrarFlash('err', '⚠️ Error de conexión');
  }
}

// ── Cargar inventario (tab mapa) ──────────────────────────────────────────────
async function cargarInventario() {
  const wrap = document.getElementById('matriz-wrap');
  wrap.innerHTML = '<div class="matriz-loading"><div class="spinner" style="margin:0 auto .875rem"></div>Cargando inventario...</div>';

  try {
    const fd = new FormData();
    fd.append('accion', 'inventario');
    const r    = await fetch('agente-paginas-api.php', { method: 'POST', body: fd });
    const data = await r.json();

    if (!data.ok) {
      wrap.innerHTML = '<div class="matriz-error">⚠️ ' + escp(data.error || 'Error desconocido') + '</div>';
      return;
    }

    mapaYaCargado = true;
    renderMatriz(data.matriz, data.cols || [], data.corporativas || []);
  } catch (e) {
    wrap.innerHTML = '<div class="matriz-error">⚠️ Error de conexión al cargar el inventario.</div>';
  }
}

// ── Renderizar matriz ─────────────────────────────────────────────────────────
function renderMatriz(matriz, cols, corporativas) {
  let html = '<div style="overflow-x:auto"><table class="matriz-table"><thead><tr>';
  html += '<th>Ciudad</th>';
  cols.forEach(function(c) { html += '<th>' + escp(c.label) + '</th>'; });
  html += '</tr></thead><tbody>';

  matriz.forEach(function(fila) {
    html += '<tr>';
    html += '<td>' + escp(fila.ciudad) + '</td>';
    cols.forEach(function(col) {
      const svc = fila.servicios[col.key];
      if (!svc) { html += '<td>—</td>'; return; }

      const tipo       = escp(svc.tipo || col.key);
      const ciudadE    = escp(fila.ciudad);
      const slugE      = escp(fila.slug);
      const cpE        = escp(fila.cp);
      const filepathE  = escp(svc.filepath);

      const onMejorar = 'onclick="lanzarAccion(\'mejorar\',\'' + tipo + '\',\'' + ciudadE + '\',\'' + slugE + '\',\'' + cpE + '\',\'' + filepathE + '\',0)"';
      const onCrear   = 'onclick="lanzarAccion(\'crear\',\''   + tipo + '\',\'' + ciudadE + '\',\'' + slugE + '\',\'' + cpE + '\',\'' + filepathE + '\',0)"';

      if (svc.existe && !svc.provisional) {
        const onDel = 'onclick="confirmarEliminarMapa(\'' + filepathE + '\')"';
        html += '<td><div class="cell-ok-wrap">';
        html += '<span class="cell-ok"><span class="cell-ok-icon">✓</span> OK</span>';
        html += '<button class="btn-accion btn-regen" ' + onMejorar + '>↻ Rehacer</button>';
        html += '<button class="btn-accion" style="background:#fee2e2;color:#b91c1c;border-color:#fca5a5" ' + onDel + '>🗑 Eliminar</button>';
        html += '</div></td>';
      } else if (svc.existe && svc.provisional) {
        html += '<td><div class="cell-prov">';
        html += '<span class="cell-prov-lbl">⚠ Provisional</span>';
        html += '<button class="btn-accion btn-mejorar" ' + onMejorar + '>Mejorar</button>';
        html += '</div></td>';
      } else {
        html += '<td><div class="cell-falta">';
        html += '<span class="cell-falta-lbl">✗ Falta</span>';
        html += '<button class="btn-accion btn-crear" ' + onCrear + '>Crear</button>';
        html += '</div></td>';
      }
    });
    html += '</tr>';
  });

  html += '</tbody></table></div>';

  // ── Sección páginas corporativas ──────────────────────────────────
  html += '<div class="corp-section-head">';
  html += '<span class="corp-section-title">Páginas corporativas</span>';
  html += '<button class="btn-add-corp" onclick="toggleCorpForm()">+ Añadir página</button>';
  html += '</div>';
  html += '<div class="corp-add-form" id="corp-add-form">';
  html += '<label>Nombre de la página</label>';
  html += '<input type="text" id="corp-new-label" placeholder="Ej: Política de privacidad">';
  html += '<label>Archivo (.php) relativo a la raíz</label>';
  html += '<input type="text" id="corp-new-filepath" placeholder="politica-privacidad.php">';
  html += '<div class="corp-add-row">';
  html += '<button class="btn-corp-save" onclick="guardarNuevaCorp()">Guardar</button>';
  html += '<button class="btn-corp-cancel" onclick="toggleCorpForm()">Cancelar</button>';
  html += '</div>';
  html += '</div>';

  if (corporativas && corporativas.length) {
    html += '<div class="corp-grid">';
    corporativas.forEach(function(corp) {
      const lbl      = escp(corp.label);
      const fp       = escp(corp.filepath);
      const rutaWeb  = escp(corp.ruta_web);
      const onCrear  = 'onclick="lanzarAccion(\'crear\',\'corporativa\',\'\',\'\',\'\',\'' + fp + '\',0)"';
      const onRehacer= 'onclick="lanzarAccion(\'mejorar\',\'corporativa\',\'\',\'\',\'\',\'' + fp + '\',0)"';
      const onDel    = corp.custom ? 'onclick="eliminarCorp(\'' + fp + '\')"' : '';

      html += '<div class="corp-card">';
      html += '<div class="corp-card-top">';
      html += '<span class="corp-card-label">' + lbl + '</span>';
      if (corp.custom) html += '<button class="btn-corp-del" title="Eliminar" ' + onDel + '>✕</button>';
      html += '</div>';
      html += '<span class="corp-card-fp">' + fp + '</span>';
      if (corp.existe) {
        html += '<span class="corp-card-status-ok">✓ Existe</span>';
        html += '<div class="corp-card-btns">';
        html += '<button class="btn-accion btn-regen" ' + onRehacer + '>↻ Rehacer</button>';
        html += '<a href="' + rutaWeb + '" target="_blank" class="btn-accion btn-regen" style="text-decoration:none">Ver →</a>';
        html += '</div>';
      } else {
        html += '<span class="corp-card-status-miss">✗ No existe</span>';
        html += '<div class="corp-card-btns">';
        html += '<button class="btn-accion btn-crear" ' + onCrear + '>Crear</button>';
        html += '</div>';
      }
      html += '</div>';
    });
    html += '</div>';
  }

  document.getElementById('matriz-wrap').innerHTML = html;
}

// ── Formulario nueva corporativa ─────────────────────────────────────────────
function toggleCorpForm() {
  const f = document.getElementById('corp-add-form');
  if (f) f.classList.toggle('open');
}

async function guardarNuevaCorp() {
  const label    = (document.getElementById('corp-new-label')?.value    || '').trim();
  const filepath = (document.getElementById('corp-new-filepath')?.value || '').trim();
  if (!label || !filepath) { alert('Rellena nombre y archivo'); return; }
  const fd = new FormData();
  fd.append('accion',   'add_corporativa');
  fd.append('label',    label);
  fd.append('filepath', filepath);
  const r = await fetch('agente-paginas-api.php', { method: 'POST', body: fd });
  const d = await r.json();
  if (d.ok) {
    toggleCorpForm();
    cargarInventario(true);
  } else {
    alert(d.error || 'Error al guardar');
  }
}

async function eliminarCorp(filepath) {
  if (!confirm('¿Eliminar "' + filepath + '" de la lista?')) return;
  const fd = new FormData();
  fd.append('accion',   'remove_corporativa');
  fd.append('filepath', filepath);
  const r = await fetch('agente-paginas-api.php', { method: 'POST', body: fd });
  const d = await r.json();
  if (d.ok) cargarInventario(true);
  else alert(d.error || 'Error');
}

// ── lanzarAccion — desde mapa o ejecutarAccion ────────────────────────────────
async function lanzarAccion(accion, tipo, ciudad, ciudadSlug, ciudadCp, filepath, planAccionId) {
  mostrarEstadoEditor('loading');
  filepathActual     = filepath;
  planAccionIdActual = planAccionId || 0;

  const TIPO_LABELS = {
    desatascos:    'Desatascos',
    fontanero:     'Fontanero',
    hub_ciudad:    'Hub Ciudad',
    urgencias:     'Urgencias',
    busqueda_fugas:'Búsqueda de fugas',
    corporativa:   'Corporativa',
  };
  const badge = document.getElementById('editor-ctx-badge');
  const accionLabel = accion === 'mejorar' ? 'Mejorando' : 'Creando';
  const tipoLabel   = TIPO_LABELS[tipo] || tipo;
  const ctxLabel    = ciudad ? accionLabel + ' · ' + tipoLabel + ' · ' + ciudad : accionLabel + ' · ' + tipoLabel + ' · ' + filepath;
  badge.textContent = ctxLabel;
  badge.style.display = '';
  const isCityType = ['hub_ciudad','busqueda_fugas','urgencias','desatascos','fontanero'].includes(tipo);
  const tips = isCityType ? [
    '🔍 Paso 1: analizando estrategia SEO para esta ciudad...',
    '🎯 Definiendo ángulo diferenciador y estructura ganadora...',
    '✍️ Paso 2: generando HTML con estructura libre...',
    '🏙️ Adaptando contenido a las características de ' + ciudad + '...',
    '📐 Aplicando meta title y description con reglas estrictas...',
    '⏳ Casi listo...',
  ] : [
    'Analizando el contenido actual...',
    'Investigando keywords locales...',
    'Redactando contenido optimizado para SEO...',
    'Revisando meta title y description...',
    'Casi listo...',
  ];
  let tipIdx = 0;
  const tipEl = document.getElementById('ap-loading-tip');
  tipEl.textContent = tips[0];
  const tipInterval = setInterval(function() {
    tipIdx = Math.min(tipIdx + 1, tips.length - 1);
    tipEl.textContent = tips[tipIdx];
  }, 4500);

  try {
    const fd = new FormData();
    fd.append('accion',      accion);
    fd.append('tipo',        tipo);
    fd.append('ciudad',      ciudad);
    fd.append('ciudad_slug', ciudadSlug);
    fd.append('ciudad_cp',   ciudadCp);
    fd.append('filepath',    filepath || '');
    fd.append('label',       TIPO_LABELS[tipo] || tipo);

    const controller = new AbortController();
    const fetchTimer = setTimeout(function() { controller.abort(); }, isCityType ? 150000 : 100000);
    const r    = await fetch('agente-paginas-api.php', { method: 'POST', body: fd, signal: controller.signal });
    clearTimeout(fetchTimer);
    const data = await r.json();
    clearInterval(tipInterval);

    if (!data.ok) {
      mostrarEstadoEditor('result');
      badge.style.display = 'none';
      document.getElementById('result-crear-mejorar').style.display = 'none';
      document.getElementById('result-redirigir').style.display     = 'none';
      document.getElementById('result-eliminar').style.display      = 'none';
      mostrarFlash('err', '⚠️ ' + (data.error || 'Error desconocido del agente'));
      return;
    }

    mostrarResultado(data, accion, tipoLabel, ciudad, filepath, planAccionId);

  } catch (e) {
    clearInterval(tipInterval);
    mostrarEstadoEditor('result');
    badge.style.display = 'none';
    document.getElementById('result-crear-mejorar').style.display = 'none';
    document.getElementById('result-redirigir').style.display     = 'none';
    document.getElementById('result-eliminar').style.display      = 'none';
    const msg = e.name === 'AbortError'
      ? '⏱ Tiempo de espera agotado (>100s). La API de Claude no respondió. Revisa tu conexión a internet y que la API key sea válida.'
      : '⚠️ Error de conexión: ' + e.message;
    mostrarFlash('err', msg);
  }
}

// ── mostrarResultado CREAR/MEJORAR ────────────────────────────────────────────
function mostrarResultado(data, accion, tipoLabel, ciudad, filepath, planAccionId) {
  mostrarEstadoEditor('result');
  ocultarFlash();

  document.getElementById('result-crear-mejorar').style.display = 'block';
  document.getElementById('result-redirigir').style.display     = 'none';
  document.getElementById('result-eliminar').style.display      = 'none';

  const noDisp = document.getElementById('no-disponible-msg');
  if (noDisp) noDisp.remove();

  const accionLabel = accion === 'mejorar' ? 'Mejorando' : 'Creando';
  document.getElementById('result-context-cm').innerHTML =
    '<strong>' + accionLabel + ':</strong> ' + escp(tipoLabel) + ' en ' + escp(ciudad) +
    (filepath ? ' <span style="font-size:11px;color:#8FA3B8;font-family:monospace">→ ' + escp(filepath) + '</span>' : '');

  const metaTitle = data.data ? (data.data.meta_title || '') : (data.meta_title || '');
  const metaDesc  = data.data ? (data.data.meta_desc  || '') : (data.meta_desc  || '');

  document.getElementById('pv-meta-title').value    = metaTitle;
  document.getElementById('pv-meta-desc').value     = metaDesc;
  document.getElementById('pv-php-content').value   = data.php_contenido || '';
  document.getElementById('pv-img-destacada').value = '';
  const _prev = document.getElementById('pv-img-preview');
  if (_prev) { _prev.style.display = 'none'; _prev.innerHTML = ''; }
  document.getElementById('pv-filepath').value    = filepath;
  document.getElementById('pv-plan-accion-id').value = planAccionId || '';
  filepathActual     = filepath;
  planAccionIdActual = planAccionId || 0;

  contarChars(document.getElementById('pv-meta-title'), 'mt-info', 60);
  contarChars(document.getElementById('pv-meta-desc'),  'md-info', 160);

  // Sincronizar campo imagen con lo que ya tenga el contenido generado
  sincronizarCampoImagen();

  // Resetear chat de refinamiento
  document.getElementById('refinar-historial').innerHTML = '';
  document.getElementById('refinar-box').classList.remove('open');
}

// ── Imagen destacada en hero ──────────────────────────────────────────────────

// Inyecta (o elimina) la imagen de fondo en <section class="hz-dark"...>
function inyectarImagenHero(url) {
  const ta = document.getElementById('pv-php-content');
  if (!ta) return;

  // Reemplaza o añade style con background-image en la sección del hero
  const estiloImg = url
    ? ' style="background-image:url(\'' + url.replace(/'/g, "\\'") + '\');background-size:cover;background-position:center top"'
    : '';

  // Quita cualquier style de background-image existente, luego añade el nuevo
  ta.value = ta.value.replace(
    /(<section\s+class="hz-dark")(\s+style="[^"]*")?(\s*>)/,
    '$1' + estiloImg + '$3'
  );

  // Preview
  const prev = document.getElementById('pv-img-preview');
  if (!prev) return;
  if (url) {
    prev.style.display = 'block';
    prev.innerHTML = '<img src="' + url + '" alt="preview" onerror="this.parentElement.style.display=\'none\'">';
  } else {
    prev.style.display = 'none';
    prev.innerHTML = '';
  }
}

// Lee la imagen que ya tiene el contenido y la muestra en el campo
function sincronizarCampoImagen() {
  const ta       = document.getElementById('pv-php-content');
  const imgInput = document.getElementById('pv-img-destacada');
  if (!ta || !imgInput) return;

  const m = ta.value.match(/<section\s+class="hz-dark"[^>]+background-image:url\('([^']+)'\)/);
  const url = m ? m[1] : '';
  imgInput.value = url;

  const prev = document.getElementById('pv-img-preview');
  if (!prev) return;
  if (url) {
    prev.style.display = 'block';
    prev.innerHTML = '<img src="' + url + '" alt="preview" onerror="this.parentElement.style.display=\'none\'">';
  } else {
    prev.style.display = 'none';
    prev.innerHTML = '';
  }
}

// ── Guardar en disco ──────────────────────────────────────────────────────────
async function guardar() {
  const contenido      = document.getElementById('pv-php-content').value.trim();
  const filepath       = document.getElementById('pv-filepath').value.trim() || filepathActual;
  const planAccionId   = document.getElementById('pv-plan-accion-id').value || '';

  if (!contenido) {
    mostrarFlash('err', '⚠️ El contenido PHP está vacío');
    return;
  }
  if (!filepath) {
    mostrarFlash('err', '⚠️ No hay ruta de archivo definida');
    return;
  }

  const btn = document.getElementById('btn-guardar');
  btn.disabled    = true;
  btn.textContent = '⏳ Guardando...';

  try {
    const fd = new FormData();
    fd.append('accion',          'guardar');
    fd.append('filepath',        filepath);
    fd.append('contenido',       contenido);
    if (planAccionId) fd.append('plan_accion_id', planAccionId);

    const r    = await fetch('agente-paginas-api.php', { method: 'POST', body: fd });
    const data = await r.json();

    btn.disabled  = false;
    btn.innerHTML = '💾 Guardar en disco';

    if (data.ok) {
      mostrarFlash('ok', '✓ Guardado correctamente en ' + escp(data.filepath || filepath));
      if (mapaYaCargado) setTimeout(cargarInventario, 800);
      if (planAccionId) setTimeout(cargarPlan, 900);
    } else {
      mostrarFlash('err', '⚠️ ' + (data.error || 'Error al guardar'));
    }
  } catch (e) {
    btn.disabled  = false;
    btn.innerHTML = '💾 Guardar en disco';
    mostrarFlash('err', '⚠️ Error de conexión al guardar');
  }
}

// ── Helpers de estado del panel editor ───────────────────────────────────────
function mostrarEstadoEditor(estado) {
  document.getElementById('ap-loading').style.display = estado === 'loading' ? 'block' : 'none';
  document.getElementById('ap-empty').style.display   = estado === 'empty'   ? 'flex'  : 'none';
  document.getElementById('ap-result').style.display  = estado === 'result'  ? 'block' : 'none';
}

// ── Helpers de flash ──────────────────────────────────────────────────────────
function mostrarFlash(tipo, msg) {
  const el = document.getElementById('ap-flash');
  el.className     = 'ap-flash ' + tipo;
  el.innerHTML     = msg;
  el.style.display = 'flex';
}
function ocultarFlash() {
  const el = document.getElementById('ap-flash');
  el.style.display = 'none';
}

// ── Contador de caracteres ────────────────────────────────────────────────────
function contarChars(el, infoId, limite) {
  const n    = el.value.length;
  const span = document.getElementById(infoId);
  if (!span) return;
  span.textContent = n + '/' + limite;
  if (n > limite) {
    span.className = 'char-info warn';
  } else if (n >= limite - 8) {
    span.className = 'char-info ok';
  } else {
    span.className = 'char-info neu';
  }
}

// ── Escape HTML helper ────────────────────────────────────────────────────────
function escp(str) {
  return String(str || '')
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#39;');
}

// ── Preview visual ────────────────────────────────────────────────────────────
function abrirPreview() {
  const fullContent = document.getElementById('pv-php-content').value;
  if (!fullContent.trim()) {
    mostrarFlash('err', '⚠️ No hay contenido generado todavía.');
    return;
  }

  // Extraer solo la zona HTML editable
  const MARKER    = '<!-- /editable -->';
  const phpEndIdx = fullContent.indexOf('?>');
  const markerIdx = fullContent.indexOf(MARKER);

  let htmlEditable;
  if (phpEndIdx !== -1 && markerIdx !== -1 && markerIdx > phpEndIdx) {
    htmlEditable = fullContent.substring(phpEndIdx + 2, markerIdx);
  } else if (phpEndIdx !== -1) {
    htmlEditable = fullContent.substring(phpEndIdx + 2);
  } else {
    htmlEditable = fullContent;
  }

  const pageCssMatch = fullContent.match(/\$page_css\s*=\s*['"]([^'"]+)['"]/);
  const pageCss      = pageCssMatch ? pageCssMatch[1] : 'zona';

  const filepath = document.getElementById('pv-filepath').value || '';
  document.getElementById('preview-label').textContent = filepath || 'nueva página';

  // Enviar al endpoint PHP que hace el render real con los CSS del sitio
  const fd = new FormData();
  fd.append('accion',   'preview');
  fd.append('html',     htmlEditable.trim());
  fd.append('page_css', pageCss);

  const modal = document.getElementById('preview-modal');
  const frame = document.getElementById('preview-frame');
  modal.style.display = 'flex';
  frame.src = '';

  fetch('agente-paginas-api.php', { method: 'POST', body: fd })
    .then(r => r.text())
    .then(html => {
      frame.srcdoc = html;
    })
    .catch(() => {
      frame.srcdoc = '<p style="padding:2rem;color:red">Error al cargar el preview</p>';
    });
}

function cerrarPreview() {
  document.getElementById('preview-modal').style.display = 'none';
  document.getElementById('preview-frame').srcdoc = '';
  document.getElementById('preview-frame').src = '';
}

// Cerrar con Escape
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') cerrarPreview();
});

// ── Chat de refinamiento ──────────────────────────────────────────────────────
function toggleRefinar() {
  document.getElementById('refinar-box').classList.toggle('open');
}

function addMsgRefinar(tipo, texto) {
  const hist = document.getElementById('refinar-historial');
  const div  = document.createElement('div');
  div.className = 'refinar-msg ' + tipo;
  div.textContent = texto;
  hist.appendChild(div);
  hist.scrollTop = hist.scrollHeight;
}

async function enviarRefinar() {
  const input   = document.getElementById('refinar-input');
  const btn     = document.getElementById('btn-refinar-send');
  const instruc = input.value.trim();
  if (!instruc) return;

  const fullContent = document.getElementById('pv-php-content').value;
  if (!fullContent.trim()) {
    addMsgRefinar('ai', '⚠️ Primero genera el contenido de la página.');
    return;
  }

  // Extraer solo la zona HTML editable (entre ?> y <!-- /editable -->)
  const MARKER = '<!-- /editable -->';
  const phpEndIdx = fullContent.indexOf('?>');
  const markerIdx = fullContent.indexOf(MARKER);

  let htmlEditable, prefijo, sufijo;
  if (phpEndIdx !== -1 && markerIdx !== -1 && markerIdx > phpEndIdx) {
    prefijo      = fullContent.substring(0, phpEndIdx + 2);
    htmlEditable = fullContent.substring(phpEndIdx + 2, markerIdx);
    sufijo       = fullContent.substring(markerIdx);
  } else {
    prefijo      = '';
    htmlEditable = fullContent;
    sufijo       = '';
  }

  addMsgRefinar('user', instruc);
  addMsgRefinar('ai', '⏳ Procesando...');
  input.value     = '';
  btn.disabled    = true;
  btn.textContent = '⏳';

  const fd = new FormData();
  fd.append('accion',      'refinar');
  fd.append('html_actual', htmlEditable.trim());
  fd.append('instruccion', instruc);
  fd.append('filepath',    document.getElementById('pv-filepath').value || '');

  try {
    const r    = await fetch('agente-paginas-api.php', { method: 'POST', body: fd });
    const text = await r.text();
    let data;
    try { data = JSON.parse(text); }
    catch(e) {
      // Quitar el último mensaje "Procesando..."
      const hist = document.getElementById('refinar-historial');
      hist.lastChild && hist.removeChild(hist.lastChild);
      addMsgRefinar('ai', '⚠️ Respuesta inválida del servidor: ' + text.substring(0, 200));
      btn.disabled = false; btn.textContent = 'Enviar'; return;
    }

    // Quitar el mensaje "Procesando..."
    const hist = document.getElementById('refinar-historial');
    hist.lastChild && hist.removeChild(hist.lastChild);

    if (data.ok && data.html != null && data.html !== '') {
      const nuevoContenido = prefijo + '\n' + data.html.trim() + '\n' + sufijo;
      document.getElementById('pv-php-content').value = nuevoContenido;
      addMsgRefinar('ai', '✓ Listo. Abriendo preview para que veas el resultado...');
      setTimeout(abrirPreview, 300);
    } else if (data.error) {
      addMsgRefinar('ai', '⚠️ Error: ' + data.error + (data.raw ? '\n\nDetalle: ' + data.raw.substring(0, 300) : ''));
    } else {
      addMsgRefinar('ai', '⚠️ Sin respuesta útil. Respuesta recibida: ' + JSON.stringify(data).substring(0, 200));
    }
  } catch (e) {
    const hist = document.getElementById('refinar-historial');
    hist.lastChild && hist.removeChild(hist.lastChild);
    addMsgRefinar('ai', '⚠️ Error de conexión: ' + e.message);
  }

  btn.disabled    = false;
  btn.textContent = 'Enviar';
}
</script>
</body>
</html>
