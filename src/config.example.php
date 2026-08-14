<?php
/**
 * Configuração central do sistema Bio.
 * Banco, credenciais admin e caminhos.
 */
declare(strict_types=1);

date_default_timezone_set('America/Maceio');

const DB_HOST = 'SEU_VALOR_AQUI';
const DB_NAME = 'SEU_VALOR_AQUI';
const DB_USER = 'SEU_VALOR_AQUI';
const DB_PASS = 'SEU_VALOR_AQUI';
const DB_CHARSET = 'SEU_VALOR_AQUI';

// Credenciais do administrador
const ADMIN_USER = 'SEU_VALOR_AQUI';
const ADMIN_PASS = 'SEU_VALOR_AQUI';

// Domínio público das páginas (usado para montar links de compartilhamento)
const PUBLIC_HOST = 'SEU_VALOR_AQUI';

// Caminhos
define('BASE_PATH', dirname(__DIR__));
define('PUBLIC_PATH', BASE_PATH . '/public');
define('UPLOAD_PATH', PUBLIC_PATH . '/uploads');
define('UPLOAD_URL', '/uploads');

// Slugs reservados que não podem ser usados como nome de empresa
const RESERVED_SLUGS = ['admin', 'uploads', 'assets', 'api', 'login', 'logout', 'public', 'src'];
