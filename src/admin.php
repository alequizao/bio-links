<?php
declare(strict_types=1);

/**
 * Controlador do painel administrativo.
 * Roteia sub-caminhos sob /admin.
 */

require_once __DIR__ . '/render.php';

$sub = $path; // ex: 'admin', 'admin/edit/3'
$sub = $sub === '' ? 'admin' : $sub;
$parts = explode('/', $sub);
$action = $parts[1] ?? '';   // login, logout, new, edit, save, delete, upload, preview

// ---------------------------------------------------------------- LOGIN
if ($action === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $ok = auth_login($_POST['user'] ?? '', $_POST['pass'] ?? '');
    if ($ok) {
        redirect('/admin');
    }
    $loginError = 'Usuário ou senha incorretos.';
    require __DIR__ . '/views/admin_login.php';
    exit;
}

if ($action === 'logout') {
    auth_logout();
    redirect('/admin');
}

// Não autenticado → tela de login
if (!auth_check()) {
    $loginError = null;
    require __DIR__ . '/views/admin_login.php';
    exit;
}

// ---------------------------------------------------------------- UPLOAD (AJAX)
if ($action === 'upload' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_check($_POST['csrf'] ?? '')) {
        json_out(['error' => 'CSRF inválido'], 403);
    }
    $url = handle_upload('file');
    if ($url === null) {
        json_out(['error' => 'Falha no upload. Verifique o formato (jpg, png, webp, gif, svg) e o tamanho (máx 8MB).'], 400);
    }
    json_out(['url' => $url]);
}

// ---------------------------------------------------------------- PREVIEW (iframe)
if ($action === 'preview' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $cfg = json_decode($_POST['payload'] ?? '[]', true) ?: [];
    header('Content-Type: text/html; charset=utf-8');
    echo render_bio_page($cfg, null);
    exit;
}

// ---------------------------------------------------------------- SAVE
if ($action === 'save' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_check($_POST['csrf'] ?? '')) {
        json_out(['error' => 'Sessão expirada. Recarregue a página.'], 403);
    }
    $id     = (int) ($_POST['id'] ?? 0);
    $config = json_decode($_POST['payload'] ?? '[]', true);
    if (!is_array($config)) {
        json_out(['error' => 'Dados inválidos.'], 400);
    }
    // Garante uma chave estável por bloco e por item (para rastrear cliques)
    if (!empty($config['blocks']) && is_array($config['blocks'])) {
        foreach ($config['blocks'] as &$blk) {
            if (empty($blk['k'])) {
                $blk['k'] = substr(bin2hex(random_bytes(4)), 0, 7);
            }
            if (!empty($blk['items']) && is_array($blk['items'])) {
                foreach ($blk['items'] as &$it) {
                    if (empty($it['k'])) {
                        $it['k'] = substr(bin2hex(random_bytes(4)), 0, 7);
                    }
                }
                unset($it);
            }
        }
        unset($blk);
    }

    $name = trim((string) ($config['profile']['name'] ?? '')) ?: 'Empresa';
    $slug = slugify($_POST['slug'] ?? $name);
    $active = (int) ($_POST['active'] ?? 1);

    if (in_array($slug, RESERVED_SLUGS, true)) {
        json_out(['error' => 'Este nome de URL é reservado. Escolha outro.'], 400);
    }
    if (slug_exists($slug, $id)) {
        json_out(['error' => 'Já existe uma bio com esta URL. Escolha outro nome.'], 400);
    }

    if ($id > 0) {
        bio_update($id, $slug, $name, $config, $active);
    } else {
        $id = bio_create($slug, $name, $config);
    }
    json_out(['ok' => true, 'id' => $id, 'slug' => $slug, 'url' => '/' . $slug]);
}

// ---------------------------------------------------------------- DELETE
if ($action === 'delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_check($_POST['csrf'] ?? '')) {
        redirect('/admin');
    }
    $id = (int) ($_POST['id'] ?? 0);
    if ($id > 0) {
        bio_delete($id);
    }
    redirect('/admin');
}

// ---------------------------------------------------------------- EDITOR (new / edit)
if ($action === 'new' || $action === 'edit') {
    $editId = (int) ($parts[2] ?? 0);
    $bio = $editId > 0 ? bio_get($editId) : null;
    if ($action === 'edit' && !$bio) {
        redirect('/admin');
    }
    $isNew  = !$bio;
    $config = $bio ? $bio['config'] : bio_template();
    $slug   = $bio ? $bio['slug'] : '';
    $active = $bio ? (int) $bio['active'] : 1;
    $editId = $bio ? (int) $bio['id'] : 0;
    require __DIR__ . '/views/admin_editor.php';
    exit;
}

// ---------------------------------------------------------------- AJUSTES DO PAINEL
if ($action === 'settings') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!csrf_check($_POST['csrf'] ?? '')) {
            redirect('/admin/settings');
        }
        // Upload de nova imagem do login (se enviada)
        $newImg = handle_upload('login_image');
        if ($newImg !== null) {
            setting_set('login_image', $newImg);
        } elseif (!empty($_POST['remove_login_image'])) {
            setting_set('login_image', '');
        }
        setting_set('login_title', trim((string) ($_POST['login_title'] ?? '')));
        setting_set('login_subtitle', trim((string) ($_POST['login_subtitle'] ?? '')));
        $savedSettings = true;
    }
    $loginImage    = setting_get('login_image', '');
    $loginTitle    = setting_get('login_title', 'Painel Bio');
    $loginSubtitle = setting_get('login_subtitle', 'Gerador de páginas link-in-bio');
    require __DIR__ . '/views/admin_settings.php';
    exit;
}

// ---------------------------------------------------------------- ESTATÍSTICAS
if ($action === 'stats') {
    $statId = (int) ($parts[2] ?? 0);
    $bio = $statId > 0 ? bio_get($statId) : null;
    if (!$bio) {
        redirect('/admin');
    }
    // Período: 1, 7, 30 dias ou 0 (tudo)
    $days = (int) ($_GET['days'] ?? 30);
    if (!in_array($days, [1, 7, 30, 0], true)) {
        $days = 30;
    }
    $stats     = click_stats($statId);      // agregado (todos os tempos)
    $total     = click_total($statId);      // total agregado (todos os tempos)
    $periodTot = ev_total($statId, $days);
    $byHour    = ev_by_hour($statId, $days);
    $byWeekday = ev_by_weekday($statId, $days);
    $byDate    = ev_by_date($statId, $days > 0 ? $days : 90);
    $byBlock   = ev_by_block($statId, $days);
    $byDevice  = ev_by_device($statId, $days);
    $recent    = ev_recent($statId, 120, $days);
    require __DIR__ . '/views/admin_stats.php';
    exit;
}

// ---------------------------------------------------------------- DASHBOARD (lista)
$bios = bio_all();
require __DIR__ . '/views/admin_dashboard.php';
exit;

/** Template inicial sugerido para novas bios (baseado na referência). */
function bio_template(): array
{
    return [
        'theme' => [
            'bg' => '#f4ebe4', 'bg2' => '#efe2d8', 'primary' => '#b98e6f',
            'accent' => '#c9a08a', 'text' => '#4a3b33', 'muted' => '#9b8b80',
            'cardBg' => '#e7d6ca', 'cardBg2' => '#f0e4db', 'font' => 'serif',
        ],
        'profile' => [
            'image' => '', 'imageStyle' => 'circle', 'name' => '',
            'nameIcon' => 'sparkles', 'subtitle' => '', 'bio' => '',
        ],
        'features' => [
            ['icon' => 'shirt', 'line1' => 'Moda casual', 'line2' => 'e executiva'],
            ['icon' => 'handbag', 'line1' => 'Roupas e', 'line2' => 'Acessórios'],
            ['icon' => 'whatsapp', 'line1' => 'Pedidos: direct', 'line2' => 'ou whatsapp'],
        ],
        'blocks' => [
            ['type' => 'whatsapp', 'icon' => 'whatsapp', 'label' => 'FALE CONOSCO', 'title' => 'Peça agora no WhatsApp', 'phone' => '', 'message' => 'Olá! Cheguei pela sua bio e gostaria de fazer um pedido.'],
            ['type' => 'product', 'title' => '', 'priceLabel' => 'Por apenas', 'price' => '', 'image' => '', 'url' => ''],
            ['type' => 'instagram', 'label' => 'SIGA NOSSO INSTAGRAM', 'title' => 'Conteúdos, novidades e inspirações todos os dias', 'url' => '', 'gallery' => []],
            ['type' => 'link', 'icon' => 'globe', 'label' => 'CONHEÇA NOSSO SITE', 'title' => 'Confira todos os produtos e coleções completas', 'url' => ''],
            ['type' => 'cta', 'icon' => 'sparkles', 'title' => 'ESCOLHA SEU ESTILO, NÓS CUIDAMOS DO RESTO.', 'button' => 'ESCOLHA AGORA', 'buttonIcon' => 'heart', 'url' => ''],
        ],
        'footer' => [
            'icon' => 'heart',
            'badges' => [
                ['icon' => 'shield', 'text' => 'Compra segura'],
                ['icon' => 'badge', 'text' => 'Qualidade garantida'],
                ['icon' => 'box', 'text' => 'Enviamos para todo o Brasil'],
            ],
        ],
        'seo' => ['title' => '', 'description' => ''],
    ];
}
