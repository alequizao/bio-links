<?php
declare(strict_types=1);
/*
 * Bio Links · Desenvolvido por Alequizao <alequizao.dev@gmail.com>
 * https://github.com/alequizao · © 2026 Alequizao. Todos os direitos reservados.
 */
$adminTitle = 'Aparência do painel';
require __DIR__ . '/admin_head.php';
$csrf = csrf_token();
?>
<div class="container" style="max-width:760px">
  <a href="/admin" class="muted" style="font-size:13px;text-decoration:none">← Voltar</a>
  <h1 style="font-size:24px;margin:8px 0 4px">Aparência do painel</h1>
  <p class="muted" style="font-size:14px;margin-bottom:20px">Personalize a tela de login do administrador.</p>

  <?php if (!empty($savedSettings)): ?>
    <div style="background:#e6f4ec;color:var(--ok);padding:12px 16px;border-radius:10px;font-size:14px;margin-bottom:18px">✓ Alterações salvas com sucesso.</div>
  <?php endif; ?>

  <form class="card" method="post" action="/admin/settings" enctype="multipart/form-data" style="padding:22px">
    <input type="hidden" name="csrf" value="<?= e($csrf) ?>">

    <div class="field">
      <label>Imagem do login (lado esquerdo — 60% da tela no desktop)</label>
      <p class="muted" style="font-size:13px;margin-bottom:10px">Recomendado: imagem na vertical, pelo menos 1000×1400px (jpg, png ou webp).</p>
      <?php if (!empty($loginImage)): ?>
        <div style="margin-bottom:12px">
          <img src="<?= e($loginImage) ?>" style="max-width:260px;width:100%;border-radius:12px;border:1px solid var(--line)">
          <div style="margin-top:8px">
            <label style="display:inline-flex;align-items:center;gap:7px;font-weight:400;font-size:13px;cursor:pointer">
              <input type="checkbox" name="remove_login_image" value="1" style="width:auto"> Remover imagem atual
            </label>
          </div>
        </div>
      <?php endif; ?>
      <input type="file" name="login_image" accept="image/*">
    </div>

    <div class="field">
      <label>Título do login</label>
      <input type="text" name="login_title" value="<?= e($loginTitle ?? '') ?>" placeholder="Painel Bio">
    </div>

    <div class="field">
      <label>Subtítulo do login</label>
      <input type="text" name="login_subtitle" value="<?= e($loginSubtitle ?? '') ?>" placeholder="Gerador de páginas link-in-bio">
    </div>

    <div style="display:flex;gap:10px;margin-top:6px">
      <button class="btn" type="submit"><?= icon('check', 18) ?> Salvar</button>
      <a class="btn ghost" href="/admin/logout" target="_blank">Ver tela de login ↗</a>
    </div>
  </form>
</div>
</body>
</html>
