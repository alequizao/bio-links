<?php declare(strict_types=1); /*
 * Bio Links · Desenvolvido por Alequizao <alequizao.dev@gmail.com>
 * https://github.com/alequizao · © 2026 Alequizao. Todos os direitos reservados.
 */
?>
<!doctype html>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= e($adminTitle ?? 'Painel Bio') ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
  *{margin:0;padding:0;box-sizing:border-box}
  :root{--brand:#1f6dff;--brand-d:#1551c4;--ink:#0e1320;--mut:#697086;--line:#e4e8f0;--bg:#f4f7fc;--card:#fff;--ok:#16a34a;--err:#dc2626;--soft:#eaf1ff}
  body{font-family:'Poppins',system-ui,sans-serif;background:var(--bg);color:var(--ink);line-height:1.45}
  a{color:var(--brand)}
  .topbar{background:#0e1320;border-bottom:1px solid #1b2233;padding:14px 22px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:50}
  .topbar .brand{font-weight:700;font-size:18px;color:#fff;display:flex;align-items:center;gap:10px}
  .topbar .brand .dot{width:30px;height:30px;border-radius:9px;background:linear-gradient(135deg,var(--brand),var(--brand-d));display:flex;align-items:center;justify-content:center;color:#fff}
  .topbar .actions{display:flex;gap:10px;align-items:center}
  .topbar .btn.ghost{background:rgba(255,255,255,.12);color:#fff}
  .topbar .btn.ghost:hover{background:rgba(255,255,255,.22)}
  .container{max-width:1180px;margin:0 auto;padding:26px 22px}
  .btn{display:inline-flex;align-items:center;gap:7px;border:0;border-radius:10px;padding:10px 16px;font:inherit;font-weight:600;font-size:14px;cursor:pointer;text-decoration:none;transition:.15s;background:var(--brand);color:#fff}
  .btn:hover{background:var(--brand-d)}
  .btn.ghost{background:var(--soft);color:var(--brand-d)}
  .btn.ghost:hover{background:#d7e5ff}
  .btn.danger{background:#fdeaea;color:var(--err)}
  .btn.danger:hover{background:#f8d7d7}
  .btn.sm{padding:7px 12px;font-size:13px}
  .ic{display:inline-block;vertical-align:middle}
  .muted{color:var(--mut)}
  .card{background:var(--card);border:1px solid var(--line);border-radius:14px}
  input,select,textarea{font:inherit;width:100%;padding:10px 12px;border:1px solid var(--line);border-radius:9px;background:#fff;color:var(--ink)}
  input:focus,select,textarea:focus{outline:2px solid #bcd4ff;border-color:var(--brand)}
  label{font-size:13px;font-weight:600;color:var(--ink);display:block;margin-bottom:5px}
  .field{margin-bottom:14px}
  .toast{position:fixed;right:18px;bottom:18px;background:var(--ink);color:#fff;padding:13px 18px;border-radius:10px;font-size:14px;opacity:0;transform:translateY(10px);transition:.25s;z-index:200;box-shadow:0 8px 24px rgba(0,0,0,.2)}
  .toast.show{opacity:1;transform:none}
  .toast.ok{background:var(--ok)}.toast.err{background:var(--err)}
</style>
</head>
<body>
<div class="topbar">
  <div class="brand">
    <span class="dot"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M10 13a5 5 0 0 0 7 0l2-2a5 5 0 0 0-7-7l-1 1"/><path d="M14 11a5 5 0 0 0-7 0l-2 2a5 5 0 0 0 7 7l1-1"/></svg></span>
    Painel Bio
  </div>
  <div class="actions">
    <a class="btn ghost sm" href="/admin">Minhas bios</a>
    <a class="btn ghost sm" href="/admin/settings">Aparência</a>
    <a class="btn ghost sm" href="/admin/logout">Sair</a>
  </div>
</div>
