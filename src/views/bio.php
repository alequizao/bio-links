<?php
declare(strict_types=1);
/*
 * Bio Links · Desenvolvido por Alequizao <alequizao.dev@gmail.com>
 * https://github.com/alequizao · © 2026 Alequizao. Todos os direitos reservados.
 */
require_once __DIR__ . '/../render.php';
echo render_bio_page($config, $bio['slug']);
