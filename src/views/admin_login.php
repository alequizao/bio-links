<?php
declare(strict_types=1);
// Configurações da tela de login (definidas em /admin/settings)
$loginImage    = setting_get('login_image', '');
$loginTitle    = setting_get('login_title', 'Painel Bio') ?: 'Painel Bio';
$loginSubtitle = setting_get('login_subtitle', 'Gerador de páginas link-in-bio') ?: '';
?>
<!doctype html>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Entrar · <?= e($loginTitle) ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
  *{margin:0;padding:0;box-sizing:border-box}
  body{font-family:'Poppins',system-ui,sans-serif;color:#0e1320}
  .split{display:flex;min-height:100vh}
  /* Lado esquerdo — imagem (60%) */
  .hero{flex:0 0 60%;position:relative;overflow:hidden;
    background:linear-gradient(150deg,#0e1320 0%,#15224a 55%,#1f6dff 140%)}
  <?php if (!empty($loginImage)): ?>
  .hero{background-image:url('<?= e($loginImage) ?>');background-size:cover;background-position:center}
  <?php endif; ?>
  .hero::after{content:"";position:absolute;inset:0;background:linear-gradient(180deg,rgba(10,15,30,.15),rgba(10,15,30,.45))}
  .hero .brandmark{position:absolute;top:30px;left:34px;z-index:2;display:flex;align-items:center;gap:12px;color:#fff}
  .hero .brandmark .dot{width:42px;height:42px;border-radius:12px;background:rgba(255,255,255,.18);backdrop-filter:blur(4px);
    display:flex;align-items:center;justify-content:center;border:1px solid rgba(255,255,255,.3)}
  .hero .brandmark .nm{font-weight:700;font-size:20px;text-shadow:0 1px 4px rgba(0,0,0,.4)}
  /* Lado direito — formulário (40%) */
  .panel{flex:0 0 40%;display:flex;align-items:center;justify-content:center;padding:40px 30px;background:#fff}
  .box{width:100%;max-width:360px}
  .logo{width:56px;height:56px;border-radius:16px;background:linear-gradient(135deg,#1f6dff,#1551c4);margin-bottom:18px;
    display:flex;align-items:center;justify-content:center;color:#fff}
  h1{font-size:26px;margin-bottom:4px}
  p.sub{color:#697086;font-size:14px;margin-bottom:28px}
  label{font-size:13px;font-weight:600;display:block;margin-bottom:6px}
  input{width:100%;padding:13px 14px;border:1px solid #e4e8f0;border-radius:10px;font:inherit;margin-bottom:16px;background:#fbfcfe}
  input:focus{outline:2px solid #bcd4ff;border-color:#1f6dff;background:#fff}
  button{width:100%;padding:14px;border:0;border-radius:10px;background:#1f6dff;color:#fff;font:inherit;font-weight:600;font-size:15px;cursor:pointer;transition:.15s}
  button:hover{background:#1551c4}
  .err{background:#fdeaea;color:#dc2626;padding:11px 14px;border-radius:9px;font-size:14px;margin-bottom:18px;text-align:center}
  @media(max-width:860px){
    .split{flex-direction:column}
    .hero{flex:none;min-height:180px}
    .panel{flex:1;padding:34px 24px}
    .hero .brandmark{top:22px;left:22px}
  }
</style>
</head>
<body>
  <div class="split">
    <div class="hero">
      <div class="brandmark">
        <span class="dot"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M10 13a5 5 0 0 0 7 0l2-2a5 5 0 0 0-7-7l-1 1"/><path d="M14 11a5 5 0 0 0-7 0l-2 2a5 5 0 0 0 7 7l1-1"/></svg></span>
        <span class="nm"><?= e($loginTitle) ?></span>
      </div>
    </div>
    <div class="panel">
      <form class="box" method="post" action="/admin/login">
        <div class="logo"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M10 13a5 5 0 0 0 7 0l2-2a5 5 0 0 0-7-7l-1 1"/><path d="M14 11a5 5 0 0 0-7 0l-2 2a5 5 0 0 0 7 7l1-1"/></svg></div>
        <h1>Entrar</h1>
        <p class="sub"><?= e($loginSubtitle) ?></p>
        <?php if (!empty($loginError)): ?><div class="err"><?= e($loginError) ?></div><?php endif; ?>
        <label>Usuário</label>
        <input type="text" name="user" autocomplete="username" autofocus required>
        <label>Senha</label>
        <input type="password" name="pass" autocomplete="current-password" required>
        <button type="submit">Entrar</button>
      </form>
    </div>
  </div>
</body>
</html>
