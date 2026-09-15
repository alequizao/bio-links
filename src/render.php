<?php
declare(strict_types=1);

/*
 * Bio Links · Desenvolvido por Alequizao <alequizao.dev@gmail.com>
 * https://github.com/alequizao · © 2026 Alequizao. Todos os direitos reservados.
 */
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/icons.php';

/** Valores padrão do tema, mesclados com o config. */
function bio_theme(array $config): array
{
    $t = $config['theme'] ?? [];
    return array_merge([
        'bg'      => '#f4ebe4',
        'bg2'     => '#efe2d8',
        'primary' => '#b98e6f',
        'accent'  => '#c9a08a',
        'text'    => '#4a3b33',
        'muted'   => '#9b8b80',
        'cardBg'  => '#e7d6ca',
        'cardBg2' => '#f0e4db',
        'font'    => 'serif',
        'bgImage' => '',
    ], array_filter($t, fn($v) => $v !== '' && $v !== null));
}

/** CSS da página, parametrizado pelo tema. */
function bio_css(array $theme): string
{
    $fontStack = $theme['font'] === 'sans'
        ? "'Poppins','Segoe UI',system-ui,sans-serif"
        : "'Cormorant Garamond','Playfair Display',Georgia,serif";
    $bodyFont = "'Poppins','Segoe UI',system-ui,sans-serif";
    return <<<CSS
*{margin:0;padding:0;box-sizing:border-box}
:root{
  --bg:{$theme['bg']};--bg2:{$theme['bg2']};--primary:{$theme['primary']};
  --accent:{$theme['accent']};--text:{$theme['text']};--muted:{$theme['muted']};
  --card:{$theme['cardBg']};--card2:{$theme['cardBg2']};
}
html,body{background:var(--bg)}
body{
  font-family:{$bodyFont};color:var(--text);line-height:1.45;
  background:linear-gradient(170deg,var(--bg) 0%,var(--bg2) 100%);
  min-height:100vh;-webkit-font-smoothing:antialiased;
}
.wrap{max-width:480px;margin:0 auto;padding:34px 22px 40px;position:relative}
.serif{font-family:{$fontStack}}
.ic{display:inline-block;vertical-align:middle}

/* Perfil */
.avatar{width:150px;height:150px;margin:0 auto 6px;display:block;object-fit:cover;
  border:3px solid var(--primary);background:#fff}
.avatar.circle{border-radius:50%}
.avatar.rounded{border-radius:26px}
.avatar.square{border-radius:6px}
.avatar-ph{display:flex;align-items:center;justify-content:center;color:var(--primary)}
.name{font-size:46px;font-weight:700;text-align:center;line-height:1.05;
  letter-spacing:.5px;margin-top:10px;color:var(--text)}
.name .nm-ic{color:var(--accent);margin-left:6px}
.subtitle{display:flex;align-items:center;justify-content:center;gap:12px;
  text-align:center;letter-spacing:3px;font-size:13px;text-transform:uppercase;
  color:var(--muted);margin:8px 0 4px;font-weight:500}
.subtitle::before,.subtitle::after{content:"";height:1px;width:34px;background:var(--accent);opacity:.5}
.bio-text{text-align:center;color:var(--muted);font-size:14px;margin-top:6px;padding:0 10px}

/* Features (linha de ícones) */
.features{display:flex;justify-content:center;gap:8px;margin:22px 0 26px;flex-wrap:wrap}
.feature{flex:1;min-width:90px;max-width:140px;text-align:center;color:var(--text)}
.feature .fic{color:var(--primary);margin-bottom:8px}
.feature p{font-size:13px;line-height:1.3;color:var(--text)}

/* Cards genéricos */
.card{background:var(--card2);border-radius:20px;padding:20px 22px;margin-bottom:18px;
  display:flex;align-items:center;gap:16px;text-decoration:none;color:inherit;
  transition:transform .18s ease,box-shadow .18s ease;position:relative;overflow:hidden;
  box-shadow:0 12px 26px -10px rgba(120,90,70,.35),0 4px 10px -6px rgba(120,90,70,.2)}
.card:hover{transform:translateY(-4px);box-shadow:0 20px 38px -12px rgba(120,90,70,.45),0 8px 16px -8px rgba(120,90,70,.28)}
.card .round{flex:none;width:54px;height:54px;border-radius:50%;background:var(--card);
  display:flex;align-items:center;justify-content:center;color:var(--primary)}
.card .body{flex:1;min-width:0}
.card .lbl{font-size:11px;letter-spacing:2px;text-transform:uppercase;color:var(--primary);font-weight:600;margin-bottom:3px}
.card .ttl{font-size:19px;font-weight:600;color:var(--text)}
.card .sub{font-size:14px;color:var(--muted);margin-top:2px}
.card .chev{flex:none;width:36px;height:36px;border-radius:50%;background:var(--card);
  display:flex;align-items:center;justify-content:center;color:var(--primary)}

/* Produto */
.card.product{align-items:stretch;padding:22px;gap:10px}
.card.product .pinfo{flex:1;display:flex;flex-direction:column;justify-content:center}
.card.product .pname{font-family:{$fontStack};font-size:30px;font-weight:700;line-height:1.02;color:var(--text)}
.card.product .pprice-lbl{font-size:13px;color:var(--muted);margin-top:10px}
.card.product .pprice{font-family:{$fontStack};font-size:30px;font-weight:700;color:var(--text)}
.card.product .pimg{flex:none;width:140px;align-self:center}
.card.product .pimg img{width:100%;border-radius:14px;object-fit:cover;display:block}

/* Instagram */
.card.insta .gal{flex:none;width:120px;display:grid;grid-template-columns:1fr 1fr;gap:4px}
.card.insta .gal img{width:100%;aspect-ratio:1;object-fit:cover;border-radius:7px;display:block}
.card.insta .gal.g1{grid-template-columns:1fr}
.card.insta .gal.g1 img{aspect-ratio:1}

/* CTA banner destaque */
.cta{background:linear-gradient(135deg,var(--accent),var(--primary));border-radius:22px;
  padding:30px 24px;text-align:center;margin-bottom:18px;text-decoration:none;display:block;
  position:relative;overflow:hidden;
  transition:transform .18s ease,box-shadow .18s ease;
  box-shadow:0 16px 32px -12px rgba(120,90,70,.5),0 6px 14px -8px rgba(120,90,70,.3)}
.cta:hover{transform:translateY(-4px);box-shadow:0 24px 44px -12px rgba(120,90,70,.55),0 10px 20px -8px rgba(120,90,70,.35)}
.cta .ct-btn{box-shadow:0 8px 18px -6px rgba(80,55,40,.4)}
.cta .ct-spark{color:#fff;opacity:.85;margin-bottom:6px}
.cta .ct-ttl{font-family:{$fontStack};color:#fff;font-size:24px;font-weight:700;
  letter-spacing:1px;line-height:1.2;text-transform:uppercase;margin-bottom:18px}
.cta .ct-btn{display:inline-flex;align-items:center;gap:10px;background:#fff;color:var(--primary);
  font-family:{$fontStack};font-size:18px;font-weight:700;letter-spacing:2px;
  padding:13px 30px;border-radius:12px;text-transform:uppercase}
.cta .ct-btn .ic{color:var(--primary)}

/* Texto / divisória livre */
.freetext{text-align:center;color:var(--text);font-size:16px;padding:8px 6px 18px}

/* Footer */
.footer{margin-top:26px;padding-top:22px;border-top:1px solid rgba(150,120,100,.25)}
.foot-top{text-align:center;color:var(--accent);margin-bottom:18px}
.badges{display:flex;justify-content:space-between;gap:10px;flex-wrap:wrap}
.badge-it{flex:1;min-width:90px;display:flex;align-items:center;gap:8px;color:var(--text);font-size:12px;line-height:1.25}
.badge-it .bic{flex:none;color:var(--primary)}
.credit{text-align:center;font-size:11px;color:var(--muted);margin-top:26px;opacity:.7}

/* ---- blocos extras ---- */
.b-heading{margin:14px 0 10px;padding:0 4px}
.b-heading .h-ttl{font-family:{$fontStack};font-size:26px;font-weight:700;color:var(--text);line-height:1.15}
.b-heading .h-sub{font-size:14px;color:var(--muted);margin-top:3px}
.b-heading.c{text-align:center}.b-heading.r{text-align:right}

.b-image{margin-bottom:18px;display:block;text-decoration:none}
.b-image img{width:100%;display:block;border-radius:18px;
  box-shadow:0 12px 26px -10px rgba(120,90,70,.35)}
.b-image .cap{text-align:center;font-size:13px;color:var(--muted);margin-top:8px}

.b-gallery{display:grid;grid-template-columns:repeat(3,1fr);gap:8px;margin-bottom:18px}
.b-gallery img{width:100%;aspect-ratio:1;object-fit:cover;border-radius:12px;display:block;
  box-shadow:0 8px 18px -10px rgba(120,90,70,.3)}

.b-video{position:relative;width:100%;aspect-ratio:16/9;margin-bottom:18px;border-radius:18px;overflow:hidden;
  box-shadow:0 12px 26px -10px rgba(120,90,70,.35);background:#000}
.b-video iframe,.b-video video{position:absolute;inset:0;width:100%;height:100%;border:0}
.b-video-ttl{font-family:{$fontStack};font-size:18px;font-weight:600;margin-bottom:8px;color:var(--text)}

.b-divider{display:flex;align-items:center;justify-content:center;gap:12px;margin:14px 0 22px;color:var(--accent)}
.b-divider::before,.b-divider::after{content:"";flex:1;height:1px;background:rgba(150,120,100,.3)}
.b-divider.empty{margin:8px 0}

.b-socials{display:flex;justify-content:center;flex-wrap:wrap;gap:12px;margin-bottom:18px}
.b-socials a{width:52px;height:52px;border-radius:50%;background:var(--card2);display:flex;align-items:center;justify-content:center;
  color:var(--primary);text-decoration:none;transition:transform .15s,box-shadow .15s;
  box-shadow:0 10px 22px -10px rgba(120,90,70,.35)}
.b-socials a:hover{transform:translateY(-3px);box-shadow:0 16px 28px -10px rgba(120,90,70,.45)}

.b-buttons{display:flex;flex-direction:column;gap:10px;margin-bottom:18px}
.b-buttons.inline{flex-direction:row;flex-wrap:wrap}
.b-buttons .bbtn{display:inline-flex;align-items:center;justify-content:center;gap:8px;flex:1;min-width:120px;
  background:var(--card2);color:var(--text);text-decoration:none;padding:14px 18px;border-radius:14px;font-weight:600;
  position:relative;overflow:hidden;
  transition:transform .15s,box-shadow .15s;box-shadow:0 10px 22px -12px rgba(120,90,70,.35)}
.b-buttons .bbtn:hover{transform:translateY(-2px);box-shadow:0 16px 28px -12px rgba(120,90,70,.45)}
.b-buttons .bbtn .ic{color:var(--primary)}

.b-pix{background:var(--card2);border-radius:18px;padding:18px 20px;margin-bottom:18px;
  box-shadow:0 12px 26px -10px rgba(120,90,70,.35)}
.b-pix .lbl{font-size:11px;letter-spacing:2px;text-transform:uppercase;color:var(--primary);font-weight:600;margin-bottom:8px}
.b-pix .pixrow{display:flex;align-items:center;gap:10px}
.b-pix .key{flex:1;min-width:0;font-family:ui-monospace,monospace;font-size:14px;background:var(--card);
  padding:11px 13px;border-radius:10px;color:var(--text);overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.b-pix .copy{flex:none;border:0;background:var(--primary);color:#fff;font:inherit;font-weight:600;font-size:13px;
  padding:11px 16px;border-radius:10px;cursor:pointer}
.b-pix .pname{font-size:13px;color:var(--muted);margin-top:8px}

.b-embed{margin-bottom:18px;border-radius:14px;overflow:hidden}
.b-embed iframe{max-width:100%}

.b-map{margin-bottom:18px;border-radius:18px;overflow:hidden;box-shadow:0 12px 26px -10px rgba(120,90,70,.35)}
.b-map iframe{width:100%;height:220px;border:0;display:block}
.b-map-ttl{font-family:{$fontStack};font-size:18px;font-weight:600;margin-bottom:8px;color:var(--text)}

/* ---- imagem de fundo nos botões/cards ---- */
.card.hasbg,.cta.hasbg,.b-buttons .bbtn.hasbg{background-size:cover;background-position:center;background-repeat:no-repeat}
.card.hasbg.tile,.cta.hasbg.tile,.b-buttons .bbtn.hasbg.tile{background-size:140px auto;background-repeat:repeat}
.card.hasbg::before,.cta.hasbg::before,.b-buttons .bbtn.hasbg::before{
  content:"";position:absolute;inset:0;z-index:0;border-radius:inherit;opacity:var(--ov,.5);pointer-events:none}
.card.hasbg::before{background:var(--card2)}
.b-buttons .bbtn.hasbg::before{background:var(--card2)}
.cta.hasbg::before{background:linear-gradient(135deg,var(--accent),var(--primary))}
.card.hasbg>*,.cta.hasbg>*,.b-buttons .bbtn.hasbg>*{position:relative;z-index:1}
CSS;
}

/** Monta a URL do WhatsApp. */
function wa_link(string $phone, string $msg = ''): string
{
    $digits = preg_replace('/\D+/', '', $phone);
    $url = 'https://wa.me/' . $digits;
    if (has($msg)) {
        $url .= '?text=' . rawurlencode($msg);
    }
    return $url;
}

/** Extrai o ID de um vídeo do YouTube a partir de várias formas de URL. */
function youtube_id(string $url): ?string
{
    if (preg_match('~(?:youtube\.com/(?:watch\?v=|embed/|shorts/)|youtu\.be/)([A-Za-z0-9_-]{6,})~', $url, $m)) {
        return $m[1];
    }
    return null;
}

/**
 * Mapa de TODOS os links rastreáveis da bio: chave => [url, label].
 * Inclui blocos de link único e itens de blocos múltiplos (socials/buttons).
 */
function bio_link_targets(array $config): array
{
    $map = [];
    foreach ($config['blocks'] ?? [] as $b) {
        $type = $b['type'] ?? '';
        if (in_array($type, ['socials', 'buttons'], true)) {
            foreach ($b['items'] ?? [] as $it) {
                if (has($it['k'] ?? '') && has($it['url'] ?? '')) {
                    $map[$it['k']] = ['url' => $it['url'], 'label' => $it['label'] ?? ($it['icon'] ?? $type)];
                }
            }
        } else {
            $url = ($type === 'whatsapp' && has($b['phone'] ?? ''))
                ? wa_link($b['phone'], $b['message'] ?? '')
                : ($b['url'] ?? '');
            if (has($b['k'] ?? '') && has($url)) {
                $map[$b['k']] = ['url' => $url, 'label' => block_label($b)];
            }
        }
    }
    return $map;
}

/**
 * Estilo de imagem de fundo para botões/cards.
 * Retorna [classeExtra, atributoStyle] com base em bgImage/bgRepeat/bgOverlay.
 */
function bg_style(array $o): array
{
    if (!has($o['bgImage'] ?? '')) {
        return ['', ''];
    }
    $cls = ' hasbg' . (!empty($o['bgRepeat']) ? ' tile' : '');
    $map = ['nenhum' => '0', 'leve' => '0.25', 'medio' => '0.5', 'forte' => '0.72'];
    $ov  = $map[$o['bgOverlay'] ?? 'medio'] ?? '0.5';
    $style = ' style="background-image:url(\'' . e($o['bgImage']) . '\');--ov:' . $ov . ';"';
    return [$cls, $style];
}

/** Monta o href: rastreado (/c/slug/key) quando em página pública. */
function track_href(string $url, string $key, ?string $trackSlug): string
{
    if (has($url) && $trackSlug !== null && has($key)) {
        return '/c/' . rawurlencode($trackSlug) . '/' . rawurlencode($key);
    }
    return $url;
}

/** Rótulo legível de um bloco, para estatísticas. */
function block_label(array $b): string
{
    foreach (['label', 'title', 'button'] as $k) {
        if (has($b[$k] ?? '')) {
            return trim(preg_replace('/\s+/', ' ', $b[$k]));
        }
    }
    return ucfirst($b['type'] ?? 'link');
}

/**
 * Renderiza o corpo HTML da página de bio (sem <html>/<head>).
 * $trackSlug: se informado, links passam por /c/{slug}/{key} para contar cliques.
 */
function render_bio_body(array $config, ?string $trackSlug = null): string
{
    $profile  = $config['profile']  ?? [];
    $features = $config['features'] ?? [];
    $blocks   = $config['blocks']   ?? [];
    $footer   = $config['footer']   ?? [];

    ob_start(); ?>
    <div class="wrap">
        <?php /* ---------- PERFIL ---------- */ ?>
        <?php if (has($profile['image'] ?? '')): ?>
            <img class="avatar <?= e($profile['imageStyle'] ?? 'circle') ?>" src="<?= e($profile['image']) ?>" alt="<?= e($profile['name'] ?? '') ?>">
        <?php elseif (has($profile['name'] ?? '')): ?>
            <div class="avatar circle avatar-ph"><?= icon('camera', 54, 1.4) ?></div>
        <?php endif; ?>

        <?php if (has($profile['name'] ?? '')): ?>
            <h1 class="name serif"><?= e($profile['name']) ?><?php if (has($profile['nameIcon'] ?? '')): ?><span class="nm-ic"><?= icon($profile['nameIcon'], 30) ?></span><?php endif; ?></h1>
        <?php endif; ?>

        <?php if (has($profile['subtitle'] ?? '')): ?>
            <div class="subtitle"><span><?= e($profile['subtitle']) ?></span></div>
        <?php endif; ?>

        <?php if (has($profile['bio'] ?? '')): ?>
            <p class="bio-text"><?= nl2br(e($profile['bio'])) ?></p>
        <?php endif; ?>

        <?php /* ---------- FEATURES ---------- */ ?>
        <?php if (!empty($features)): ?>
            <div class="features">
                <?php foreach ($features as $f): ?>
                    <?php if (!has($f['line1'] ?? '') && !has($f['line2'] ?? '') && !has($f['icon'] ?? '')) continue; ?>
                    <div class="feature">
                        <?php if (has($f['icon'] ?? '')): ?><div class="fic"><?= icon($f['icon'], 30, 1.5) ?></div><?php endif; ?>
                        <p><?= e($f['line1'] ?? '') ?><?php if (has($f['line2'] ?? '')): ?><br><?= e($f['line2']) ?><?php endif; ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php /* ---------- BLOCOS ---------- */ ?>
        <?php foreach ($blocks as $b): echo render_block($b, $trackSlug); endforeach; ?>

        <?php /* ---------- FOOTER ---------- */ ?>
        <?php $badges = $footer['badges'] ?? []; ?>
        <?php if (!empty($badges) || has($footer['text'] ?? '')): ?>
            <div class="footer">
                <div class="foot-top"><?= icon($footer['icon'] ?? 'heart', 18) ?></div>
                <?php if (!empty($badges)): ?>
                    <div class="badges">
                        <?php foreach ($badges as $bd): ?>
                            <?php if (!has($bd['text'] ?? '') && !has($bd['icon'] ?? '')) continue; ?>
                            <div class="badge-it"><?php if (has($bd['icon'] ?? '')): ?><span class="bic"><?= icon($bd['icon'], 22, 1.6) ?></span><?php endif; ?><span><?= e($bd['text'] ?? '') ?></span></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <?php if (has($footer['text'] ?? '')): ?><p class="credit"><?= e($footer['text']) ?></p><?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
    <?php
    return ob_get_clean();
}

/** Renderiza um bloco individual conforme o tipo. */
function render_block(array $b, ?string $trackSlug = null): string
{
    $type = $b['type'] ?? 'link';
    $href = '';
    if ($type === 'whatsapp') {
        $href = has($b['phone'] ?? '') ? wa_link($b['phone'], $b['message'] ?? '') : ($b['url'] ?? '#');
    } else {
        $href = $b['url'] ?? '';
    }
    $hasLink = has($href) && $href !== '#';
    // Redireciona via rastreador quando em página pública e houver chave do bloco.
    if ($hasLink && $trackSlug !== null && has($b['k'] ?? '')) {
        $href = '/c/' . rawurlencode($trackSlug) . '/' . rawurlencode($b['k']);
    }
    $tag     = $hasLink ? 'a' : 'div';
    $attrs   = $hasLink ? sprintf(' href="%s" target="_blank" rel="noopener"', e($href)) : '';

    ob_start();

    switch ($type) {
        case 'product':
            [$bgCls, $bgSty] = bg_style($b);
            ?>
            <<?= $tag ?> class="card product<?= $bgCls ?>"<?= $attrs ?><?= $bgSty ?>>
                <div class="pinfo">
                    <?php if (has($b['title'] ?? '')): ?><div class="pname serif"><?= nl2br(e($b['title'])) ?></div><?php endif; ?>
                    <?php if (has($b['priceLabel'] ?? '')): ?><div class="pprice-lbl"><?= e($b['priceLabel']) ?></div><?php endif; ?>
                    <?php if (has($b['price'] ?? '')): ?><div class="pprice serif"><?= e($b['price']) ?></div><?php endif; ?>
                </div>
                <?php if (has($b['image'] ?? '')): ?><div class="pimg"><img src="<?= e($b['image']) ?>" alt="<?= e($b['title'] ?? '') ?>"></div><?php endif; ?>
                <?php if ($hasLink): ?><div class="chev"><?= icon('link', 18) ?></div><?php endif; ?>
            </<?= $tag ?>>
            <?php
            break;

        case 'instagram':
            $gal = array_values(array_filter($b['gallery'] ?? [], fn($g) => has($g)));
            $gcls = count($gal) <= 1 ? 'g1' : '';
            [$bgCls, $bgSty] = bg_style($b);
            ?>
            <<?= $tag ?> class="card insta<?= $bgCls ?>"<?= $attrs ?><?= $bgSty ?>>
                <div class="round"><?= icon('instagram-color', 30) ?></div>
                <div class="body">
                    <?php if (has($b['label'] ?? '')): ?><div class="lbl"><?= e($b['label']) ?></div><?php endif; ?>
                    <?php if (has($b['title'] ?? '')): ?><div class="sub" style="font-size:16px;color:var(--text)"><?= nl2br(e($b['title'])) ?></div><?php endif; ?>
                </div>
                <?php if (!empty($gal)): ?>
                    <div class="gal <?= $gcls ?>"><?php foreach (array_slice($gal, 0, 4) as $g): ?><img src="<?= e($g) ?>" alt=""><?php endforeach; ?></div>
                <?php endif; ?>
            </<?= $tag ?>>
            <?php
            break;

        case 'cta':
            [$bgCls, $bgSty] = bg_style($b);
            ?>
            <<?= $tag ?> class="cta<?= $bgCls ?>"<?= $attrs ?><?= $bgSty ?>>
                <div class="ct-spark"><?= icon($b['icon'] ?? 'sparkles', 26) ?></div>
                <?php if (has($b['title'] ?? '')): ?><div class="ct-ttl"><?= nl2br(e($b['title'])) ?></div><?php endif; ?>
                <?php if (has($b['button'] ?? '')): ?>
                    <span class="ct-btn"><?= e($b['button']) ?><?php if (has($b['buttonIcon'] ?? '')): ?><?= icon($b['buttonIcon'], 20) ?><?php endif; ?></span>
                <?php endif; ?>
            </<?= $tag ?>>
            <?php
            break;

        case 'text':
            ?>
            <div class="freetext serif"><?= nl2br(e($b['title'] ?? '')) ?></div>
            <?php
            break;

        case 'heading':
            $al = ['left' => '', 'center' => 'c', 'right' => 'r'][$b['align'] ?? 'center'] ?? 'c';
            ?>
            <div class="b-heading <?= $al ?>">
                <?php if (has($b['title'] ?? '')): ?><div class="h-ttl serif"><?= e($b['title']) ?></div><?php endif; ?>
                <?php if (has($b['sub'] ?? '')): ?><div class="h-sub"><?= e($b['sub']) ?></div><?php endif; ?>
            </div>
            <?php
            break;

        case 'image':
            if (!has($b['image'] ?? '')) break;
            ?>
            <<?= $tag ?> class="b-image"<?= $attrs ?>>
                <img src="<?= e($b['image']) ?>" alt="<?= e($b['caption'] ?? '') ?>">
                <?php if (has($b['caption'] ?? '')): ?><div class="cap"><?= e($b['caption']) ?></div><?php endif; ?>
            </<?= $tag ?>>
            <?php
            break;

        case 'gallery':
            $gal = array_values(array_filter($b['gallery'] ?? [], fn($g) => has($g)));
            if (empty($gal)) break;
            ?>
            <div class="b-gallery"><?php foreach ($gal as $g): ?><img src="<?= e($g) ?>" alt=""><?php endforeach; ?></div>
            <?php
            break;

        case 'video':
            $yt = has($b['video'] ?? '') ? youtube_id($b['video']) : null;
            if (!$yt && !has($b['video'] ?? '')) break;
            if (has($b['title'] ?? '')) echo '<div class="b-video-ttl serif">' . e($b['title']) . '</div>';
            if ($yt) {
                echo '<div class="b-video"><iframe src="https://www.youtube.com/embed/' . e($yt) . '" allow="accelerometer;autoplay;clipboard-write;encrypted-media;gyroscope;picture-in-picture" allowfullscreen></iframe></div>';
            } else {
                echo '<div class="b-video"><video src="' . e($b['video']) . '" controls></video></div>';
            }
            break;

        case 'divider':
            $hasIc = has($b['icon'] ?? '');
            echo '<div class="b-divider' . ($hasIc ? '' : ' empty') . '">' . ($hasIc ? icon($b['icon'], 20) : '') . '</div>';
            break;

        case 'socials':
            $items = $b['items'] ?? [];
            if (empty($items)) break;
            ?>
            <div class="b-socials">
                <?php foreach ($items as $it):
                    if (!has($it['url'] ?? '') && !has($it['icon'] ?? '')) continue;
                    $h = track_href($it['url'] ?? '', $it['k'] ?? '', $trackSlug);
                    $linked = has($h);
                    ?>
                    <?php if ($linked): ?><a href="<?= e($h) ?>" target="_blank" rel="noopener" title="<?= e($it['label'] ?? '') ?>"><?php else: ?><span class="b-socials" style="display:inline-flex"><?php endif; ?>
                        <?= ($it['icon'] ?? '') === 'instagram-color' ? icon('instagram-color', 26) : icon($it['icon'] ?? 'link', 24) ?>
                    <?= $linked ? '</a>' : '</span>' ?>
                <?php endforeach; ?>
            </div>
            <?php
            break;

        case 'buttons':
            $items = $b['items'] ?? [];
            if (empty($items)) break;
            $inline = !empty($b['inline']) ? 'inline' : '';
            ?>
            <div class="b-buttons <?= $inline ?>">
                <?php foreach ($items as $it):
                    if (!has($it['label'] ?? '') && !has($it['url'] ?? '')) continue;
                    $h = track_href($it['url'] ?? '', $it['k'] ?? '', $trackSlug);
                    $linked = has($h);
                    $bt = $linked ? 'a' : 'span';
                    $ba = $linked ? ' href="' . e($h) . '" target="_blank" rel="noopener"' : '';
                    [$bgCls, $bgSty] = bg_style($it);
                    ?>
                    <<?= $bt ?> class="bbtn<?= $bgCls ?>"<?= $ba ?><?= $bgSty ?>><?php if (has($it['icon'] ?? '')) echo icon($it['icon'], 18); ?><span><?= e($it['label'] ?? 'Link') ?></span></<?= $bt ?>>
                <?php endforeach; ?>
            </div>
            <?php
            break;

        case 'pix':
            if (!has($b['pixkey'] ?? '')) break;
            $pid = 'pix' . substr(md5($b['pixkey']), 0, 6);
            ?>
            <div class="b-pix">
                <?php if (has($b['label'] ?? '')): ?><div class="lbl"><?= e($b['label']) ?></div><?php endif; ?>
                <div class="pixrow">
                    <span class="key" id="<?= $pid ?>"><?= e($b['pixkey']) ?></span>
                    <button class="copy" type="button" onclick="(function(b){navigator.clipboard&&navigator.clipboard.writeText(document.getElementById('<?= $pid ?>').textContent);var t=b.textContent;b.textContent='Copiado!';setTimeout(function(){b.textContent=t;},1500);})(this)">Copiar</button>
                </div>
                <?php if (has($b['pixname'] ?? '')): ?><div class="pname"><?= e($b['pixname']) ?></div><?php endif; ?>
            </div>
            <?php
            break;

        case 'embed':
            // HTML personalizado do admin (confiável). Permite "qualquer coisa".
            if (has($b['embed'] ?? '')) {
                echo '<div class="b-embed">' . $b['embed'] . '</div>';
            }
            break;

        case 'map':
            if (!has($b['address'] ?? '')) break;
            if (has($b['title'] ?? '')) echo '<div class="b-map-ttl serif">' . e($b['title']) . '</div>';
            $q = rawurlencode($b['address']);
            echo '<div class="b-map"><iframe loading="lazy" src="https://www.google.com/maps?q=' . $q . '&output=embed"></iframe></div>';
            break;

        case 'whatsapp':
        case 'link':
        default:
            $ic = $b['icon'] ?? ($type === 'whatsapp' ? 'whatsapp' : 'link');
            [$bgCls, $bgSty] = bg_style($b);
            ?>
            <<?= $tag ?> class="card<?= $bgCls ?>"<?= $attrs ?><?= $bgSty ?>>
                <?php if (has($ic)): ?><div class="round"><?= $ic === 'instagram-color' ? icon('instagram-color', 30) : icon($ic, 28) ?></div><?php endif; ?>
                <div class="body">
                    <?php if (has($b['label'] ?? '')): ?><div class="lbl"><?= e($b['label']) ?></div><?php endif; ?>
                    <?php if (has($b['title'] ?? '')): ?><div class="ttl"><?= e($b['title']) ?></div><?php endif; ?>
                    <?php if (has($b['sub'] ?? '')): ?><div class="sub"><?= nl2br(e($b['sub'])) ?></div><?php endif; ?>
                </div>
                <?php if ($hasLink): ?><div class="chev"><?= icon('link', 18) ?></div><?php endif; ?>
            </<?= $tag ?>>
            <?php
            break;
    }

    return ob_get_clean();
}

/** Página completa de bio (HTML inteiro). $trackSlug ativa contagem de cliques. */
function render_bio_page(array $config, ?string $trackSlug = null): string
{
    $theme   = bio_theme($config);
    $profile = $config['profile'] ?? [];
    $seo     = $config['seo'] ?? [];
    $title   = has($seo['title'] ?? '') ? $seo['title'] : ($profile['name'] ?? 'Bio');
    $desc    = $seo['description'] ?? ($profile['subtitle'] ?? '');
    $css     = bio_css($theme);
    $body    = render_bio_body($config, $trackSlug);
    $favicon = has($profile['image'] ?? '') ? '<link rel="icon" href="' . e($profile['image']) . '">' : '';

    return '<!doctype html><html lang="pt-br"><head>'
        . '<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">'
        . '<title>' . e($title) . '</title>'
        . '<meta name="description" content="' . e($desc) . '">'
        . '<meta property="og:title" content="' . e($title) . '">'
        . '<meta property="og:description" content="' . e($desc) . '">'
        . (has($profile['image'] ?? '') ? '<meta property="og:image" content="' . e($profile['image']) . '">' : '')
        . $favicon
        . '<link rel="preconnect" href="https://fonts.googleapis.com">'
        . '<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Playfair+Display:wght@600;700&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">'
        . '<style>' . $css . '</style></head><body>'
        . $body
        . '</body></html>';
}
