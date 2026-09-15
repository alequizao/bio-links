<?php
declare(strict_types=1);
/*
 * Bio Links · Desenvolvido por Alequizao <alequizao.dev@gmail.com>
 * https://github.com/alequizao · © 2026 Alequizao. Todos os direitos reservados.
 */
$adminTitle = 'Análises · ' . ($bio['name'] ?: $bio['slug']);
require __DIR__ . '/admin_head.php';

$views = (int) $bio['views'];
$ctr   = $views > 0 ? round($total / $views * 100) : 0;

// Ranking de links: usa eventos do período; para "Tudo" usa o agregado (inclui histórico antigo).
$blocks = $bio['config']['blocks'] ?? [];
$rows = [];
if ($days === 0) {
    foreach ($stats as $k => $r) {
        $rows[] = ['label' => $r['label'] ?: $k, 'clicks' => (int) $r['clicks'], 'last' => $r['last_click']];
    }
} else {
    foreach ($byBlock as $k => $r) {
        $rows[] = ['label' => $r['label'] ?: $k, 'clicks' => (int) $r['c'], 'last' => $r['last']];
    }
}
usort($rows, fn($a, $b) => $b['clicks'] <=> $a['clicks']);

// Helpers de gráfico
$maxArr = fn(array $a) => max(1, ...(array_values($a) ?: [0]));

// Picos
$hourMax = array_keys($byHour, max($byHour ?: [0]))[0] ?? null;
$wdNames = [1 => 'Dom', 2 => 'Seg', 3 => 'Ter', 4 => 'Qua', 5 => 'Qui', 6 => 'Sex', 7 => 'Sáb'];
$wdFull  = [1 => 'Domingo', 2 => 'Segunda', 3 => 'Terça', 4 => 'Quarta', 5 => 'Quinta', 6 => 'Sexta', 7 => 'Sábado'];
arsort($byWeekday);
$topWd = array_key_first($byWeekday);
ksort($byWeekday);

$periods = [1 => 'Hoje', 7 => '7 dias', 30 => '30 dias', 0 => 'Tudo'];
$bid = (int) $bio['id'];
?>
<style>
  .seg{display:inline-flex;background:#eef1f6;border-radius:10px;padding:3px}
  .seg a{padding:7px 14px;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;color:var(--mut)}
  .seg a.on{background:#fff;color:var(--brand-d);box-shadow:0 1px 4px rgba(0,0,0,.08)}
  .grid-cards{display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:14px;margin:18px 0 24px}
  .stat .n{font-size:30px;font-weight:700;margin-top:4px}
  .panel{margin-bottom:22px}
  .panel h2{font-size:15px;margin-bottom:4px}
  .panel .hint{font-size:12px;color:var(--mut);margin-bottom:14px}
  /* gráfico de barras verticais */
  .vbars{display:flex;align-items:flex-end;gap:3px;height:150px;padding-top:10px}
  .vbars .col{flex:1;display:flex;flex-direction:column;align-items:center;gap:5px;height:100%;justify-content:flex-end;min-width:0}
  .vbars .bar{width:78%;max-width:26px;border-radius:5px 5px 0 0;background:linear-gradient(180deg,var(--brand),var(--brand-d));min-height:2px;transition:.2s}
  .vbars .bar.zero{background:#e4e8f0}
  .vbars .bar.peak{background:linear-gradient(180deg,#0e1320,#1551c4)}
  .vbars .vl{font-size:9px;color:var(--mut);white-space:nowrap}
  .vbars .vv{font-size:10px;font-weight:600;color:var(--ink);height:12px}
  /* barras horizontais */
  .hbar-row{display:flex;align-items:center;gap:12px;padding:9px 0;border-bottom:1px solid var(--line)}
  .hbar-row:last-child{border-bottom:0}
  .hbar-row .hl{flex:none;width:74px;font-size:13px;color:var(--ink)}
  .hbar-row .track{flex:1;height:9px;background:#eef1f6;border-radius:6px;overflow:hidden}
  .hbar-row .fill{height:100%;background:linear-gradient(90deg,var(--brand),var(--brand-d));border-radius:6px}
  .hbar-row .hv{flex:none;width:44px;text-align:right;font-weight:600;font-size:13px}
  table.hist{width:100%;border-collapse:collapse;font-size:13px}
  table.hist th{text-align:left;color:var(--mut);font-weight:600;font-size:12px;padding:8px 10px;border-bottom:1px solid var(--line)}
  table.hist td{padding:9px 10px;border-bottom:1px solid #f1f3f7}
  table.hist tr:hover td{background:#f8fafd}
  .chip{font-size:11px;padding:2px 8px;border-radius:20px;background:#eef1f6;color:var(--mut)}
  .chip.mobile{background:#e7f0ff;color:var(--brand-d)}.chip.desktop{background:#eafaf0;color:var(--ok)}
  .twocol{display:grid;grid-template-columns:1fr 1fr;gap:18px}
  @media(max-width:760px){.twocol{grid-template-columns:1fr}}
</style>

<div class="container">
  <a href="/admin" class="muted" style="font-size:13px;text-decoration:none">← Voltar</a>
  <div style="display:flex;align-items:center;justify-content:space-between;margin:8px 0 6px;flex-wrap:wrap;gap:12px">
    <div>
      <h1 style="font-size:24px">Análises — <?= e($bio['name'] ?: $bio['slug']) ?></h1>
      <a href="/<?= e($bio['slug']) ?>" target="_blank" style="font-size:14px">/<?= e($bio['slug']) ?> ↗</a>
    </div>
    <a class="btn" href="/admin/edit/<?= $bid ?>">Editar bio</a>
  </div>

  <!-- seletor de período -->
  <div class="seg">
    <?php foreach ($periods as $d => $lbl): ?>
      <a class="<?= $days === $d ? 'on' : '' ?>" href="/admin/stats/<?= $bid ?>?days=<?= $d ?>"><?= e($lbl) ?></a>
    <?php endforeach; ?>
  </div>

  <!-- resumo -->
  <div class="grid-cards">
    <div class="card stat" style="padding:18px"><div class="muted" style="font-size:13px"><?= icon('user', 15) ?> Visitas (total)</div><div class="n"><?= $views ?></div></div>
    <div class="card stat" style="padding:18px"><div class="muted" style="font-size:13px"><?= icon('link', 15) ?> Cliques no período</div><div class="n"><?= $periodTot ?></div></div>
    <div class="card stat" style="padding:18px"><div class="muted" style="font-size:13px"><?= icon('check', 15) ?> Cliques (total)</div><div class="n"><?= $total ?></div></div>
    <div class="card stat" style="padding:18px"><div class="muted" style="font-size:13px"><?= icon('percent', 15) ?> Cliques por visita</div><div class="n"><?= $ctr ?>%</div></div>
  </div>

  <?php if ($periodTot === 0): ?>
    <div class="card" style="padding:30px;text-align:center;color:var(--mut)">
      Ainda não há cliques registrados <?= $days === 0 ? '' : 'neste período' ?>. O histórico detalhado começa a partir de agora — cada clique nos links será registrado com data e hora.
    </div>
  <?php else: ?>

  <!-- POR HORA DO DIA -->
  <div class="panel card" style="padding:20px">
    <h2><?= icon('clock', 16) ?> Cliques por horário do dia</h2>
    <p class="hint"><?php if ($hourMax !== null && $byHour[$hourMax] > 0): ?>Horário de pico: <strong><?= sprintf('%02dh', $hourMax) ?></strong> (<?= $byHour[$hourMax] ?> cliques).<?php else: ?>Distribuição por hora (0–23h).<?php endif; ?></p>
    <?php $hm = $maxArr($byHour); ?>
    <div class="vbars">
      <?php for ($h = 0; $h < 24; $h++): $v = $byHour[$h]; $pct = round($v / $hm * 100); ?>
        <div class="col" title="<?= sprintf('%02dh', $h) ?> — <?= $v ?> cliques">
          <div class="vv"><?= $v ?: '' ?></div>
          <div class="bar <?= $v === 0 ? 'zero' : ($h === $hourMax ? 'peak' : '') ?>" style="height:<?= max($v ? 4 : 2, $pct) ?>%"></div>
          <div class="vl"><?= $h % 2 === 0 ? sprintf('%02d', $h) : '' ?></div>
        </div>
      <?php endfor; ?>
    </div>
  </div>

  <div class="twocol">
    <!-- POR DIA DA SEMANA -->
    <div class="panel card" style="padding:20px">
      <h2><?= icon('calendar', 16) ?> Cliques por dia da semana</h2>
      <p class="hint"><?php if ($topWd && $byWeekday[$topWd] > 0): ?>Dia mais ativo: <strong><?= e($wdFull[$topWd]) ?></strong>.<?php else: ?>Distribuição por dia.<?php endif; ?></p>
      <?php $wm = $maxArr($byWeekday); ?>
      <div class="vbars" style="height:130px">
        <?php foreach ($wdNames as $i => $nm): $v = $byWeekday[$i] ?? 0; $pct = round($v / $wm * 100); ?>
          <div class="col" title="<?= e($wdFull[$i]) ?> — <?= $v ?>">
            <div class="vv"><?= $v ?: '' ?></div>
            <div class="bar <?= $v === 0 ? 'zero' : ($i === $topWd ? 'peak' : '') ?>" style="height:<?= max($v ? 4 : 2, $pct) ?>%;max-width:34px"></div>
            <div class="vl" style="font-size:11px"><?= e($nm) ?></div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- DISPOSITIVO -->
    <div class="panel card" style="padding:20px">
      <h2><?= icon('phone', 16) ?> Dispositivo</h2>
      <p class="hint">De onde vieram os cliques.</p>
      <?php $dm = $maxArr($byDevice ?: ['x' => 0]); $devNames = ['mobile' => 'Celular', 'desktop' => 'Computador', 'outros' => 'Outros']; ?>
      <?php if (empty($byDevice)): ?><p class="muted" style="font-size:13px">Sem dados.</p><?php endif; ?>
      <?php foreach ($byDevice as $dev => $c): $pct = round($c / $dm * 100); ?>
        <div class="hbar-row">
          <div class="hl"><?= e($devNames[$dev] ?? $dev) ?></div>
          <div class="track"><div class="fill" style="width:<?= max(3, $pct) ?>%"></div></div>
          <div class="hv"><?= $c ?></div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- POR DATA -->
  <?php if (!empty($byDate)): ?>
  <div class="panel card" style="padding:20px">
    <h2><?= icon('calendar', 16) ?> Cliques por dia</h2>
    <p class="hint">Evolução diária no período.</p>
    <?php $dmax = $maxArr($byDate); ?>
    <div class="vbars" style="height:140px;gap:2px">
      <?php foreach ($byDate as $d => $v): $pct = round($v / $dmax * 100); ?>
        <div class="col" title="<?= e(date('d/m/Y', strtotime($d))) ?> — <?= $v ?> cliques">
          <div class="vv" style="font-size:9px"><?= $v ?></div>
          <div class="bar" style="height:<?= max(4, $pct) ?>%"></div>
          <div class="vl"><?= e(date('d/m', strtotime($d))) ?></div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>

  <!-- RANKING DE LINKS -->
  <div class="panel card" style="padding:20px">
    <h2><?= icon('link', 16) ?> Cliques por botão / link</h2>
    <p class="hint">Os links mais clicados no período.</p>
    <?php $rmax = max(1, ...(array_map(fn($r) => $r['clicks'], $rows) ?: [0])); ?>
    <?php if (empty($rows)): ?><p class="muted" style="font-size:13px">Nenhum clique no período.</p><?php endif; ?>
    <?php foreach ($rows as $r): if ($r['clicks'] === 0 && $days !== 0) continue; $pct = round($r['clicks'] / $rmax * 100); ?>
      <div class="hbar-row">
        <div class="hl" style="width:160px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis"><?= e($r['label']) ?></div>
        <div class="track"><div class="fill" style="width:<?= max(3, $pct) ?>%"></div></div>
        <div class="hv"><?= $r['clicks'] ?></div>
      </div>
    <?php endforeach; ?>
  </div>

  <!-- HISTÓRICO DETALHADO -->
  <div class="panel card" style="padding:20px 20px 8px">
    <h2><?= icon('clock', 16) ?> Histórico de cliques</h2>
    <p class="hint">Cada clique com data e hora exatas (<?= count($recent) ?> mais recentes).</p>
    <div style="overflow-x:auto">
      <table class="hist">
        <thead><tr><th>Data e hora</th><th>Link clicado</th><th>Dispositivo</th><th>Origem</th></tr></thead>
        <tbody>
          <?php foreach ($recent as $ev): ?>
            <tr>
              <td style="white-space:nowrap"><?= e(date('d/m/Y H:i:s', strtotime($ev['clicked_at']))) ?></td>
              <td><?= e($ev['label'] ?: '—') ?></td>
              <td><span class="chip <?= e($ev['device']) ?>"><?= e(['mobile' => 'Celular', 'desktop' => 'Computador'][$ev['device']] ?? '—') ?></span></td>
              <td class="muted"><?= e($ev['referrer'] ?: 'direto') ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <?php endif; ?>
</div>
</body>
</html>
