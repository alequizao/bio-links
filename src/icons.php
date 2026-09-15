<?php
declare(strict_types=1);

/*
 * Bio Links · Desenvolvido por Alequizao <alequizao.dev@gmail.com>
 * https://github.com/alequizao · © 2026 Alequizao. Todos os direitos reservados.
 */
/**
 * Biblioteca de ícones SVG (estilo traço, 24x24, herdam a cor via currentColor).
 * Use icon('nome') para obter o markup. icon_list() para o seletor do admin.
 */

function icon_defs(): array
{
    return [
        // Vestuário / loja
        'shirt'     => '<path d="M16 3l4 2-2 4-2-1v13H8V8L6 9 4 5l4-2 1.5 0a2.5 2.5 0 0 0 5 0z"/>',
        'dress'     => '<path d="M9 3l3 3 3-3M8 7l-2 4 3 2-2 8h10l-2-8 3-2-2-4"/>',
        'bag'       => '<path d="M6 8h12l1 12H5z"/><path d="M9 8V6a3 3 0 0 1 6 0v2"/>',
        'handbag'   => '<path d="M5 9h14l-1 11H6z"/><path d="M8 9a4 4 0 0 1 8 0"/>',
        'shoe'      => '<path d="M3 16v-4l5-2 3 3 8 1a2 2 0 0 1 2 2v2H3z"/>',
        'tag'       => '<path d="M3 12l9-9 9 9-9 9z"/><circle cx="8.5" cy="8.5" r="1.5"/>',
        'gift'      => '<rect x="3" y="8" width="18" height="13" rx="1"/><path d="M3 12h18M12 8v13M12 8S9 3 6.5 5 9 8 12 8zm0 0s3-5 5.5-3S15 8 12 8z"/>',
        'crown'     => '<path d="M3 7l4 4 5-7 5 7 4-4-2 13H5z"/>',
        'sparkles'  => '<path d="M12 3l1.8 5.2L19 10l-5.2 1.8L12 17l-1.8-5.2L5 10l5.2-1.8z"/><path d="M19 4l.7 2 2 .7-2 .7-.7 2-.7-2-2-.7 2-.7z"/>',
        'star'      => '<path d="M12 3l2.6 6.3L21 10l-5 4.3L17.5 21 12 17.3 6.5 21 8 14.3 3 10l6.4-.7z"/>',
        'heart'     => '<path d="M12 21S4 14.5 4 8.8A4.3 4.3 0 0 1 12 6a4.3 4.3 0 0 1 8 2.8C20 14.5 12 21 12 21z"/>',
        'flower'    => '<circle cx="12" cy="12" r="3"/><path d="M12 9V4M12 15v5M9 12H4M15 12h5M9.5 9.5L6 6M14.5 9.5L18 6M9.5 14.5L6 18M14.5 14.5L18 18"/>',
        'scissors'  => '<circle cx="6" cy="6" r="2.5"/><circle cx="6" cy="18" r="2.5"/><path d="M8 8l12 10M8 16L20 6"/>',

        // Comunicação
        'whatsapp'  => '<path d="M12 3a9 9 0 0 0-7.7 13.6L3 21l4.5-1.3A9 9 0 1 0 12 3z"/><path d="M8.5 8.2c-.3 0-.6.1-.8.4-.3.3-.9.9-.9 2.1s.9 2.5 1 2.6c.1.2 1.8 2.9 4.5 3.9 2.2.8 2.7.7 3.2.6.7-.1 1.5-.6 1.7-1.2.2-.6.2-1.1.1-1.2l-1.7-.8c-.2-.1-.4-.1-.6.1l-.7.9c-.1.2-.3.2-.5.1-.3-.1-1.2-.5-2-1.3-.7-.7-1.1-1.5-1.2-1.7-.1-.2 0-.4.1-.5l.4-.5c.1-.2.2-.3.2-.5l-.7-1.7c-.2-.4-.4-.4-.6-.4z"/>',
        'phone'     => '<path d="M5 4h3l2 5-2.5 1.5a11 11 0 0 0 5 5L19 13l2 4v3a1 1 0 0 1-1 1A16 16 0 0 1 4 5a1 1 0 0 1 1-1z"/>',
        'mail'      => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/>',
        'message'   => '<path d="M4 5h16v11H8l-4 4z"/>',
        'send'      => '<path d="M21 3L3 11l7 2 2 7z"/><path d="M21 3l-9 9"/>',

        // Web / social
        'globe'     => '<circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3a14 14 0 0 1 0 18 14 14 0 0 1 0-18z"/>',
        'link'      => '<path d="M10 13a5 5 0 0 0 7 0l2-2a5 5 0 0 0-7-7l-1 1"/><path d="M14 11a5 5 0 0 0-7 0l-2 2a5 5 0 0 0 7 7l1-1"/>',
        'instagram' => '<rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1"/>',
        'facebook'  => '<path d="M14 8h2V5h-2a3 3 0 0 0-3 3v2H9v3h2v8h3v-8h2.5l.5-3H14V8.5c0-.4.3-.5.6-.5z"/>',
        'youtube'   => '<rect x="3" y="6" width="18" height="12" rx="3"/><path d="M10 9l5 3-5 3z"/>',
        'tiktok'    => '<path d="M14 4v9.5a3.5 3.5 0 1 1-3-3.5"/><path d="M14 4a4 4 0 0 0 4 4"/>',
        'pin'       => '<path d="M12 21s7-6.5 7-11a7 7 0 0 0-14 0c0 4.5 7 11 7 11z"/><circle cx="12" cy="10" r="2.5"/>',
        'map'       => '<path d="M9 4L3 6v14l6-2 6 2 6-2V4l-6 2z"/><path d="M9 4v14M15 6v14"/>',

        // Confiança / e-commerce
        'shield'    => '<path d="M12 3l8 3v6c0 5-3.5 8-8 9-4.5-1-8-4-8-9V6z"/><path d="M9 12l2 2 4-4"/>',
        'badge'     => '<circle cx="12" cy="9" r="6"/><path d="M9 13l-2 8 5-3 5 3-2-8"/><path d="M9.5 9l1.5 1.5L15 7"/>',
        'box'       => '<path d="M3 7l9-4 9 4v10l-9 4-9-4z"/><path d="M3 7l9 4 9-4M12 11v10"/>',
        'truck'     => '<path d="M3 6h11v9H3z"/><path d="M14 9h4l3 3v3h-7z"/><circle cx="7" cy="18" r="1.8"/><circle cx="17" cy="18" r="1.8"/>',
        'cart'      => '<path d="M3 4h2l2 12h11l2-8H6"/><circle cx="9" cy="20" r="1.5"/><circle cx="17" cy="20" r="1.5"/>',
        'wallet'    => '<rect x="3" y="6" width="18" height="13" rx="2"/><path d="M3 10h18M16 14h2"/>',
        'card'      => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 9h18"/>',
        'check'     => '<circle cx="12" cy="12" r="9"/><path d="M8 12l3 3 5-6"/>',
        'clock'     => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
        'percent'   => '<path d="M5 19L19 5"/><circle cx="7.5" cy="7.5" r="2.5"/><circle cx="16.5" cy="16.5" r="2.5"/>',
        'fire'      => '<path d="M12 3c1 3-1 4-1 6a3 3 0 0 0 6 0c0-1 0-2-1-3 2 1 4 4 4 7a8 8 0 0 1-16 0c0-3 2-5 4-6 0 2 1 3 2 3 0-3-1-5 2-7z"/>',
        'user'      => '<circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 4-6 8-6s8 2 8 6"/>',
        'home'      => '<path d="M3 11l9-7 9 7"/><path d="M5 10v10h14V10"/>',
        'camera'    => '<rect x="3" y="7" width="18" height="13" rx="2"/><circle cx="12" cy="13" r="3.5"/><path d="M8 7l2-3h4l2 3"/>',
        'play'      => '<circle cx="12" cy="12" r="9"/><path d="M10 9l5 3-5 3z"/>',
        'calendar'  => '<rect x="3" y="5" width="18" height="16" rx="2"/><path d="M3 9h18M8 3v4M16 3v4"/>',
    ];
}

/** Renderiza um ícone SVG. $name pode incluir variante "instagram" colorido. */
function icon(string $name, int $size = 24, float $stroke = 1.8, string $extraClass = ''): string
{
    $name = trim($name);
    if ($name === '' || $name === 'none') {
        return '';
    }
    if ($name === 'instagram-color') {
        return icon_instagram_color($size, $extraClass);
    }
    $defs = icon_defs();
    if (!isset($defs[$name])) {
        $name = 'star';
    }
    $cls = 'ic' . ($extraClass ? ' ' . $extraClass : '');
    return sprintf(
        '<svg class="%s" width="%d" height="%d" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="%s" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">%s</svg>',
        e($cls),
        $size,
        $size,
        $stroke,
        $defs[$name]
    );
}

/** Logo colorido do Instagram (gradiente), como na referência. */
function icon_instagram_color(int $size = 40, string $extraClass = ''): string
{
    $id = 'ig' . substr(md5((string) $size . $extraClass), 0, 6);
    $cls = 'ic-ig' . ($extraClass ? ' ' . $extraClass : '');
    return sprintf(
        '<svg class="%s" width="%d" height="%d" viewBox="0 0 24 24" aria-hidden="true">
            <defs><radialGradient id="%s" cx="0.3" cy="1" r="1.1">
                <stop offset="0" stop-color="#FED576"/><stop offset="0.25" stop-color="#F47133"/>
                <stop offset="0.5" stop-color="#BC3081"/><stop offset="0.75" stop-color="#4C63D2"/>
                <stop offset="1" stop-color="#4C63D2"/></radialGradient></defs>
            <rect x="2" y="2" width="20" height="20" rx="6" fill="url(#%s)"/>
            <circle cx="12" cy="12" r="4.2" fill="none" stroke="#fff" stroke-width="1.8"/>
            <circle cx="17.3" cy="6.7" r="1.3" fill="#fff"/>
        </svg>',
        e($cls),
        $size,
        $size,
        $id,
        $id
    );
}

/** Lista de nomes para o seletor de ícones do admin. */
function icon_list(): array
{
    return array_merge(['instagram-color'], array_keys(icon_defs()));
}
