<?php
declare(strict_types=1);
$adminTitle = 'Minhas bios · Painel';
require __DIR__ . '/admin_head.php';
$csrf = csrf_token();
?>
<div class="container">
  <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:22px;flex-wrap:wrap;gap:12px">
    <div>
      <h1 style="font-size:24px">Minhas bios</h1>
      <p class="muted" style="font-size:14px">Crie e gerencie páginas em <strong>bio.publishdev.com.br/nome-da-empresa</strong></p>
    </div>
    <a class="btn" href="/admin/new"><?= icon('star', 18, 2) ?> Nova bio</a>
  </div>

  <?php if (empty($bios)): ?>
    <div class="card" style="padding:48px;text-align:center">
      <p style="font-size:18px;margin-bottom:6px">Nenhuma bio ainda</p>
      <p class="muted" style="margin-bottom:20px">Comece criando a primeira página link-in-bio.</p>
      <a class="btn" href="/admin/new">Criar primeira bio</a>
    </div>
  <?php else: ?>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:16px">
      <?php foreach ($bios as $b): ?>
        <div class="card" style="padding:18px">
          <div style="display:flex;align-items:center;justify-content:space-between;gap:10px">
            <div style="min-width:0">
              <div style="font-weight:600;font-size:17px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis"><?= e($b['name'] ?: $b['slug']) ?></div>
              <a href="/<?= e($b['slug']) ?>" target="_blank" style="font-size:13px;color:var(--brand-d);text-decoration:none">/<?= e($b['slug']) ?> ↗</a>
            </div>
            <span style="font-size:11px;padding:4px 9px;border-radius:20px;background:<?= $b['active'] ? '#e6f4ec' : '#eef1f6' ?>;color:<?= $b['active'] ? 'var(--ok)' : 'var(--mut)' ?>;white-space:nowrap"><?= $b['active'] ? 'Ativa' : 'Oculta' ?></span>
          </div>
          <div style="display:flex;gap:18px;margin:14px 0;color:var(--mut);font-size:13px">
            <span><?= icon('user', 16) ?> <strong style="color:var(--ink)"><?= (int) $b['views'] ?></strong> visitas</span>
            <span class="muted">Atualizada <?= e(date('d/m/Y', strtotime($b['updated_at']))) ?></span>
          </div>
          <div style="display:flex;gap:8px;flex-wrap:wrap">
            <a class="btn sm" href="/admin/edit/<?= (int) $b['id'] ?>"><?= icon('scissors', 15) ?> Editar</a>
            <a class="btn ghost sm" href="/admin/stats/<?= (int) $b['id'] ?>"><?= icon('percent', 15) ?> Cliques</a>
            <form method="post" action="/admin/delete" onsubmit="return confirm('Excluir esta bio? Esta ação não pode ser desfeita.')" style="margin-left:auto">
              <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
              <input type="hidden" name="id" value="<?= (int) $b['id'] ?>">
              <button class="btn danger sm" type="submit"><?= icon('scissors', 15) ?> Excluir</button>
            </form>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>
</body>
</html>
