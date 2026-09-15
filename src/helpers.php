<?php
declare(strict_types=1);

/*
 * Bio Links · Desenvolvido por Alequizao <alequizao.dev@gmail.com>
 * https://github.com/alequizao · © 2026 Alequizao. Todos os direitos reservados.
 */
/** Escapa para HTML. */
function e(?string $s): string
{
    return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');
}

/** Gera slug amigável a partir de um texto. */
function slugify(string $text): string
{
    $text = trim($text);
    if (function_exists('iconv')) {
        $conv = @iconv('UTF-8', 'ASCII//TRANSLIT', $text);
        if ($conv !== false) {
            $text = $conv;
        }
    }
    $text = strtolower($text);
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    $text = trim($text, '-');
    return $text === '' ? 'empresa' : $text;
}

/** Lê um valor aninhado do config com fallback. */
function cfg(array $config, string $key, $default = '')
{
    return $config[$key] ?? $default;
}

/** Verifica se uma string tem conteúdo. */
function has(?string $v): bool
{
    return $v !== null && trim($v) !== '';
}

/**
 * Trata upload de imagem. Retorna URL pública ou null.
 * $field = nome do campo <input type=file>.
 */
function handle_upload(string $field): ?string
{
    if (empty($_FILES[$field]) || $_FILES[$field]['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    $f = $_FILES[$field];
    if ($f['error'] !== UPLOAD_ERR_OK) {
        return null;
    }
    if ($f['size'] > 8 * 1024 * 1024) { // 8 MB
        return null;
    }
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime  = finfo_file($finfo, $f['tmp_name']);
    finfo_close($finfo);
    $allowed = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
        'image/gif'  => 'gif',
        'image/svg+xml' => 'svg',
    ];
    if (!isset($allowed[$mime])) {
        return null;
    }
    $ext  = $allowed[$mime];
    $name = bin2hex(random_bytes(8)) . '.' . $ext;
    $dest = UPLOAD_PATH . '/' . $name;
    if (!move_uploaded_file($f['tmp_name'], $dest)) {
        return null;
    }
    @chmod($dest, 0644);
    return UPLOAD_URL . '/' . $name;
}

/** Redireciona e encerra. */
function redirect(string $to): void
{
    header('Location: ' . $to);
    exit;
}

function json_out($data, int $code = 200): void
{
    http_response_code($code);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}
