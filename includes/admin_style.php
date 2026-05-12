<style>
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap');
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Outfit',sans-serif;background:#f1f5f9;color:#1e293b;min-height:100vh}

/* SIDEBAR */
.sidebar{position:fixed;top:0;left:0;width:260px;height:100vh;background:#1c2434;display:flex;flex-direction:column;z-index:100;overflow-y:auto}
.sidebar-logo{padding:1.5rem 1.75rem;border-bottom:1px solid rgba(255,255,255,.06)}
.sidebar-logo img{height:38px;width:auto;filter:brightness(0) invert(1);opacity:.9}
.sidebar-logo span{display:block;color:#64748b;font-size:11px;font-weight:500;letter-spacing:.08em;text-transform:uppercase;margin-top:6px}
.sidebar-nav{flex:1;padding:1.25rem 0;display:flex;flex-direction:column;gap:2px}
.sidebar-label{color:#4b5e78;font-size:10px;font-weight:600;letter-spacing:.1em;text-transform:uppercase;padding:.75rem 1.75rem .25rem}
.sidebar-nav a{display:flex;align-items:center;gap:10px;padding:.65rem 1.75rem;color:#8899aa;font-size:13.5px;text-decoration:none;transition:all .15s;border-radius:0;margin:0 .5rem;border-radius:6px}
.sidebar-nav a:hover{background:rgba(255,255,255,.05);color:#fff}
.sidebar-nav a.active{background:#3b5bdb;color:#fff}
.sidebar-nav a span{font-size:15px;flex-shrink:0}
.sidebar-bottom{padding:1rem 1.25rem;border-top:1px solid rgba(255,255,255,.06)}
.sidebar-bottom a{color:#64748b;font-size:13px;text-decoration:none;display:flex;align-items:center;gap:8px;padding:.6rem .5rem;border-radius:6px;transition:all .15s}
.sidebar-bottom a:hover{color:#fff;background:rgba(255,255,255,.05)}

/* MAIN */
.main{margin-left:260px;padding:2rem;min-height:100vh}

/* TOPBAR */
.topbar{display:flex;align-items:center;justify-content:space-between;margin-bottom:2rem;flex-wrap:wrap;gap:1rem}
.topbar h1{color:#0f172a;font-size:20px;font-weight:700;letter-spacing:-.01em}
.topbar a{color:#64748b;font-size:13.5px;text-decoration:none;transition:color .15s}
.topbar a:hover{color:#1e3a5f}
.btn-new{background:#3b5bdb!important;color:#fff!important;padding:9px 20px!important;border-radius:7px!important;font-size:13.5px!important;font-weight:600!important;border:none!important;cursor:pointer;text-decoration:none!important;display:inline-flex;align-items:center;gap:6px}
.btn-new:hover{opacity:.9!important}

/* CARDS */
.card{background:#fff;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,.04)}
.card-header{display:flex;align-items:center;justify-content:space-between;padding:1rem 1.5rem;border-bottom:1px solid #f1f5f9}
.card-header h2{color:#0f172a;font-size:14px;font-weight:600}
.card-header a{background:#3b5bdb;color:#fff;padding:7px 16px;border-radius:6px;font-size:12.5px;font-weight:500;text-decoration:none;transition:opacity .15s}
.card-header a:hover{opacity:.88}
.card-header span{color:#64748b;font-size:13px}
.card-body{padding:1.5rem}

/* FORM */
.form-wrap{background:#fff;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,.04)}
.form-section{padding:1.75rem 2rem;border-bottom:1px solid #f1f5f9}
.form-section:last-child{border-bottom:none}
.form-section h2{color:#3b5bdb;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;margin-bottom:1.25rem}
.form-grid{display:grid;grid-template-columns:1fr 1fr;gap:1rem}
.form-group{display:flex;flex-direction:column;gap:5px}
.form-group.full{grid-column:1/-1}
label{color:#374151;font-size:13px;font-weight:500}
.label-hint{color:#94a3b8;font-weight:400;font-size:12px}
input[type="text"],input[type="password"],input[type="url"],input[type="file"],select,textarea{border:1.5px solid #e2e8f0;border-radius:8px;padding:10px 14px;font-size:14px;color:#0f172a;font-family:inherit;width:100%;transition:border-color .15s;background:#fff}
input:focus,select:focus,textarea:focus{outline:none;border-color:#3b5bdb;box-shadow:0 0 0 3px rgba(59,91,219,.08)}
textarea{resize:vertical;min-height:120px}
textarea.grande{min-height:320px;font-family:monospace;font-size:13px;line-height:1.6}
.char-count{font-size:11px;text-align:right;margin-top:4px;color:#94a3b8}
.char-count.ok{color:#16a34a}
.char-count.warn{color:#dc2626}
.check-wrap{display:flex;align-items:center;gap:10px}
.check-wrap input[type="checkbox"]{width:18px;height:18px;accent-color:#3b5bdb;cursor:pointer}
.check-wrap label{font-size:14px;font-weight:500;cursor:pointer}
.form-actions{display:flex;gap:1rem;align-items:center;flex-wrap:wrap}
.btn-save{background:#3b5bdb;color:#fff;border:none;border-radius:8px;padding:12px 28px;font-size:14.5px;font-weight:600;cursor:pointer;transition:opacity .15s}
.btn-save:hover{opacity:.88}
.btn-preview{background:#f8fafc;color:#1e3a5f;border:1.5px solid #e2e8f0;border-radius:8px;padding:11px 22px;font-size:14px;font-weight:500;cursor:pointer;text-decoration:none;transition:all .15s}
.btn-preview:hover{border-color:#3b5bdb;color:#3b5bdb}

/* TABLA */
table{width:100%;border-collapse:collapse}
th{background:#f8fafc;color:#64748b;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.06em;padding:.65rem 1.25rem;text-align:left;white-space:nowrap;border-bottom:1px solid #f1f5f9}
td{padding:.9rem 1.25rem;border-bottom:1px solid #f8fafc;font-size:13.5px;color:#374151;vertical-align:middle}
tr:last-child td{border-bottom:none}
tr:hover td{background:#fafbfc}
.td-titulo{color:#0f172a;font-weight:600;max-width:280px}
.td-titulo small{display:block;color:#94a3b8;font-size:11.5px;font-weight:400;margin-top:2px;font-family:monospace}

/* BADGES */
.badge-pub{display:inline-flex;align-items:center;gap:5px;padding:4px 10px;border-radius:20px;font-size:11.5px;font-weight:500;cursor:pointer;text-decoration:none;transition:opacity .15s}
.badge-pub.si{background:#dcfce7;color:#16a34a}
.badge-pub.no{background:#fef9c3;color:#854d0e}
.badge-pub:hover{opacity:.75}
.badge-zona{background:#eff6ff;color:#1d4ed8;font-size:11px;padding:3px 10px;border-radius:20px;font-weight:500}
.badge-cat{background:#f5f3ff;color:#6d28d9;font-size:11px;padding:3px 10px;border-radius:20px;font-weight:500}

/* ACCIONES */
.td-acciones{display:flex;gap:.5rem;white-space:nowrap}
.td-acciones a{font-size:12.5px;font-weight:500;text-decoration:none;padding:5px 12px;border-radius:6px;transition:opacity .15s}
.btn-editar{background:#eff6ff;color:#1d4ed8}
.btn-ver{background:#f8fafc;color:#475569}
.btn-eliminar{background:#fff1f2;color:#dc2626}
.td-acciones a:hover{opacity:.75}
.empty-row td{text-align:center;color:#94a3b8;padding:3rem;font-size:14px}

/* FILTROS */
.filtros{background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:1.25rem 1.5rem;margin-bottom:1.5rem;display:flex;gap:1rem;flex-wrap:wrap;align-items:flex-end;box-shadow:0 1px 3px rgba(0,0,0,.04)}
.filtro-group{display:flex;flex-direction:column;gap:5px}
.filtro-group label{font-size:12px;color:#64748b;font-weight:500;text-transform:uppercase;letter-spacing:.05em}
.filtro-group input,.filtro-group select{min-width:160px;padding:8px 12px;border:1.5px solid #e2e8f0;border-radius:7px;font-size:13.5px;font-family:inherit}
.filtro-group input:focus,.filtro-group select:focus{outline:none;border-color:#3b5bdb}
.btn-filtrar{background:#3b5bdb;color:#fff;border:none;border-radius:7px;padding:9px 20px;font-size:13.5px;font-weight:600;cursor:pointer;transition:opacity .15s}
.btn-filtrar:hover{opacity:.88}
.btn-limpiar{background:#f8fafc;color:#64748b;border:1.5px solid #e2e8f0;border-radius:7px;padding:9px 16px;font-size:13.5px;cursor:pointer;text-decoration:none;transition:all .15s}
.btn-limpiar:hover{border-color:#94a3b8}

/* MENSAJES */
.mensaje{background:#dcfce7;border:1px solid #bbf7d0;border-radius:8px;padding:.85rem 1.25rem;color:#15803d;font-size:14px;margin-bottom:1.5rem;display:flex;align-items:center;gap:8px}
.error-msg{background:#fff1f2;border:1px solid #fecdd3;border-radius:8px;padding:.85rem 1.25rem;color:#dc2626;font-size:14px;margin-bottom:1.5rem}

/* SLUG */
.slug-preview{font-size:11px;color:#94a3b8;margin-top:4px;font-family:monospace}

/* CONFIRM */
.confirm-overlay{display:none;position:fixed;inset:0;background:rgba(15,23,42,.5);z-index:999;align-items:center;justify-content:center;backdrop-filter:blur(2px)}
.confirm-overlay.open{display:flex}
.confirm-box{background:#fff;border-radius:14px;padding:2rem;max-width:400px;width:90%;text-align:center;box-shadow:0 20px 60px rgba(0,0,0,.15)}
.confirm-box h3{color:#0f172a;font-size:18px;font-weight:700;margin-bottom:.75rem}
.confirm-box p{color:#64748b;font-size:14px;margin-bottom:1.5rem;line-height:1.6}
.confirm-btns{display:flex;gap:.75rem;justify-content:center}
.confirm-btns a{padding:10px 24px;border-radius:8px;font-size:14px;font-weight:600;text-decoration:none}
.btn-cancelar{background:#f1f5f9;color:#374151}
.btn-confirmar{background:#dc2626;color:#fff}
</style>