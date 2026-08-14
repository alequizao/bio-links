<?php
declare(strict_types=1);

/**
 * Front controller do sistema Bio.
 *  /            -> painel admin (login)
 *  /admin       -> painel admin
 *  /{slug}      -> página pública da empresa
 */

require_once __DIR__ . '/../src/config.php';
require_once __DIR__ . '/../src/helpers.php';
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../src/icons.php';
require_once __DIR__ . '/../src/render.php';

// Caminho solicitado (sem querystring)
$uri  = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
$path = trim(rawurldecode($uri), '/');

// Rotas administrativas
if ($path === '' || $path === 'admin' || str_starts_with($path, 'admin/')) {
    require __DIR__ . '/../src/admin.php';
    exit;
}

// Rastreador de cliques: /c/{slug}/{chave} -> conta e redireciona
if (str_starts_with($path, 'c/')) {
    $seg  = explode('/', $path);
    $cSlug = strtolower($seg[1] ?? '');
    $cKey  = $seg[2] ?? '';
    $b     = bio_find($cSlug);
    if ($b && $cKey !== '') {
        $targets = bio_link_targets($b['config']);
        if (isset($targets[$cKey]) && has($targets[$cKey]['url'])) {
            $ua     = $_SERVER['HTTP_USER_AGENT'] ?? '';
            $device = preg_match('/Mobile|Android|iPhone|iPad|iPod/i', $ua) ? 'mobile' : 'desktop';
            $ref    = '';
            if (!empty($_SERVER['HTTP_REFERER'])) {
                $ref = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST) ?: '';
            }
            click_log((int) $b['id'], $cKey, $targets[$cKey]['label'], $device, $ref);
            redirect($targets[$cKey]['url']);
        }
    }
    http_response_code(404);
    require __DIR__ . '/../src/views/404.php';
    exit;
}

// Página pública: primeiro segmento é o slug da empresa
$slug = strtolower(explode('/', $path)[0]);
$bio  = bio_find($slug);

if (!$bio || (int) $bio['active'] !== 1) {
    http_response_code(404);
    require __DIR__ . '/../src/views/404.php';
    exit;
}

bio_inc_views((int) $bio['id']);
$config = $bio['config'];
require __DIR__ . '/../src/views/bio.php';
