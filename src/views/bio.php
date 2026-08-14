<?php
declare(strict_types=1);
require_once __DIR__ . '/../render.php';
echo render_bio_page($config, $bio['slug']);
