<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';

function db(): PDO
{
    static $pdo = null;
    if ($pdo === null) {
        $dsn = sprintf('mysql:host=%s;dbname=%s;charset=%s', DB_HOST, DB_NAME, DB_CHARSET);
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
    }
    return $pdo;
}

/** Busca um bio pelo slug. */
function bio_find(string $slug): ?array
{
    $st = db()->prepare('SELECT * FROM bios WHERE slug = ? LIMIT 1');
    $st->execute([$slug]);
    $row = $st->fetch();
    if (!$row) {
        return null;
    }
    $row['config'] = json_decode($row['config'], true) ?: [];
    return $row;
}

/** Busca por id. */
function bio_get(int $id): ?array
{
    $st = db()->prepare('SELECT * FROM bios WHERE id = ? LIMIT 1');
    $st->execute([$id]);
    $row = $st->fetch();
    if (!$row) {
        return null;
    }
    $row['config'] = json_decode($row['config'], true) ?: [];
    return $row;
}

/** Lista todos os bios. */
function bio_all(): array
{
    return db()->query('SELECT id, slug, name, views, active, updated_at FROM bios ORDER BY updated_at DESC')->fetchAll();
}

/** Cria um novo bio. Retorna o id. */
function bio_create(string $slug, string $name, array $config): int
{
    $st = db()->prepare('INSERT INTO bios (slug, name, config) VALUES (?, ?, ?)');
    $st->execute([$slug, $name, json_encode($config, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)]);
    return (int) db()->lastInsertId();
}

/** Atualiza um bio existente. */
function bio_update(int $id, string $slug, string $name, array $config, int $active): void
{
    $st = db()->prepare('UPDATE bios SET slug = ?, name = ?, config = ?, active = ? WHERE id = ?');
    $st->execute([$slug, $name, json_encode($config, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), $active, $id]);
}

function bio_delete(int $id): void
{
    db()->prepare('DELETE FROM bios WHERE id = ?')->execute([$id]);
}

function bio_inc_views(int $id): void
{
    db()->prepare('UPDATE bios SET views = views + 1 WHERE id = ?')->execute([$id]);
}

/** Registra um clique em um bloco. */
function click_register(int $bioId, string $key, string $label): void
{
    $st = db()->prepare(
        'INSERT INTO bio_clicks (bio_id, block_key, label, clicks, last_click)
         VALUES (?, ?, ?, 1, NOW())
         ON DUPLICATE KEY UPDATE clicks = clicks + 1, last_click = NOW(), label = VALUES(label)'
    );
    $st->execute([$bioId, $key, mb_substr($label, 0, 180)]);
}

/** Registra um clique completo: agregado + evento detalhado (data/hora). */
function click_log(int $bioId, string $key, string $label, string $device = '', string $referrer = ''): void
{
    click_register($bioId, $key, $label);
    $st = db()->prepare(
        'INSERT INTO click_events (bio_id, block_key, label, device, referrer, clicked_at)
         VALUES (?, ?, ?, ?, ?, NOW())'
    );
    $st->execute([$bioId, $key, mb_substr($label, 0, 180), mb_substr($device, 0, 10), mb_substr($referrer, 0, 180)]);
}

/** Cláusula de período para os eventos. $days=0 => tudo. */
function ev_where(int $days): string
{
    $days = (int) $days;
    return $days > 0 ? " AND clicked_at >= (NOW() - INTERVAL {$days} DAY)" : '';
}

/** Total de eventos no período. */
function ev_total(int $bioId, int $days = 0): int
{
    $st = db()->prepare('SELECT COUNT(*) FROM click_events WHERE bio_id = ?' . ev_where($days));
    $st->execute([$bioId]);
    return (int) $st->fetchColumn();
}

/** Cliques por hora do dia (0..23). */
function ev_by_hour(int $bioId, int $days = 0): array
{
    $out = array_fill(0, 24, 0);
    $st = db()->prepare('SELECT HOUR(clicked_at) h, COUNT(*) c FROM click_events WHERE bio_id = ?' . ev_where($days) . ' GROUP BY h');
    $st->execute([$bioId]);
    foreach ($st->fetchAll() as $r) {
        $out[(int) $r['h']] = (int) $r['c'];
    }
    return $out;
}

/** Cliques por dia da semana (1=Dom .. 7=Sáb, padrão MySQL DAYOFWEEK). */
function ev_by_weekday(int $bioId, int $days = 0): array
{
    $out = array_fill(1, 7, 0);
    $st = db()->prepare('SELECT DAYOFWEEK(clicked_at) d, COUNT(*) c FROM click_events WHERE bio_id = ?' . ev_where($days) . ' GROUP BY d');
    $st->execute([$bioId]);
    foreach ($st->fetchAll() as $r) {
        $out[(int) $r['d']] = (int) $r['c'];
    }
    return $out;
}

/** Cliques por data (YYYY-MM-DD => total) no período. */
function ev_by_date(int $bioId, int $days = 30): array
{
    $st = db()->prepare('SELECT DATE(clicked_at) d, COUNT(*) c FROM click_events WHERE bio_id = ?' . ev_where($days) . ' GROUP BY d ORDER BY d');
    $st->execute([$bioId]);
    $out = [];
    foreach ($st->fetchAll() as $r) {
        $out[$r['d']] = (int) $r['c'];
    }
    return $out;
}

/** Cliques por bloco/link no período (key => [label, c]). */
function ev_by_block(int $bioId, int $days = 0): array
{
    $st = db()->prepare('SELECT block_key, MAX(label) label, COUNT(*) c, MAX(clicked_at) last FROM click_events WHERE bio_id = ?' . ev_where($days) . ' GROUP BY block_key ORDER BY c DESC');
    $st->execute([$bioId]);
    $out = [];
    foreach ($st->fetchAll() as $r) {
        $out[$r['block_key']] = $r;
    }
    return $out;
}

/** Cliques por dispositivo no período. */
function ev_by_device(int $bioId, int $days = 0): array
{
    $st = db()->prepare("SELECT IF(device='','outros',device) dev, COUNT(*) c FROM click_events WHERE bio_id = ?" . ev_where($days) . ' GROUP BY dev ORDER BY c DESC');
    $st->execute([$bioId]);
    $out = [];
    foreach ($st->fetchAll() as $r) {
        $out[$r['dev']] = (int) $r['c'];
    }
    return $out;
}

/** Histórico recente de cliques (eventos individuais). */
function ev_recent(int $bioId, int $limit = 100, int $days = 0): array
{
    $limit = max(1, min(500, $limit));
    $st = db()->prepare('SELECT label, device, referrer, clicked_at FROM click_events WHERE bio_id = ?' . ev_where($days) . " ORDER BY clicked_at DESC LIMIT {$limit}");
    $st->execute([$bioId]);
    return $st->fetchAll();
}

/** Cliques agregados de um bio (key => [clicks, label, last_click]). */
function click_stats(int $bioId): array
{
    $st = db()->prepare('SELECT block_key, label, clicks, last_click FROM bio_clicks WHERE bio_id = ?');
    $st->execute([$bioId]);
    $out = [];
    foreach ($st->fetchAll() as $r) {
        $out[$r['block_key']] = $r;
    }
    return $out;
}

/** Total de cliques de um bio. */
function click_total(int $bioId): int
{
    $st = db()->prepare('SELECT COALESCE(SUM(clicks),0) FROM bio_clicks WHERE bio_id = ?');
    $st->execute([$bioId]);
    return (int) $st->fetchColumn();
}

/** Lê uma configuração global do painel. */
function setting_get(string $key, ?string $default = null): ?string
{
    $st = db()->prepare('SELECT v FROM settings WHERE k = ? LIMIT 1');
    $st->execute([$key]);
    $v = $st->fetchColumn();
    return $v === false ? $default : $v;
}

/** Grava uma configuração global do painel. */
function setting_set(string $key, ?string $value): void
{
    $st = db()->prepare('INSERT INTO settings (k, v) VALUES (?, ?) ON DUPLICATE KEY UPDATE v = VALUES(v)');
    $st->execute([$key, $value]);
}

function slug_exists(string $slug, int $exceptId = 0): bool
{
    $st = db()->prepare('SELECT id FROM bios WHERE slug = ? AND id <> ? LIMIT 1');
    $st->execute([$slug, $exceptId]);
    return (bool) $st->fetch();
}
