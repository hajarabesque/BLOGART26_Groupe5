<?php
//define ROOT_PATH
define('ROOT', $_SERVER['DOCUMENT_ROOT']);

// Compute project base path (so ROOT_URL works even when project isn't at host root)
$basePath = str_replace($_SERVER['DOCUMENT_ROOT'], '', __DIR__);
$basePath = str_replace('\\', '/', $basePath);
$basePath = rtrim($basePath, '/');
define('ROOT_URL', 'http://' . $_SERVER['HTTP_HOST'] . $basePath);

//Load env
require_once ROOT . '/includes/libs/DotEnv.php';
(new DotEnv(ROOT.'/.env'))->load();

//defines
require_once ROOT . '/config/defines.php';

//debug
if (getenv('APP_DEBUG') == 'true') {
    require_once ROOT . '/config/debug.php';
}

//load functions
require_once ROOT . '/functions/global.inc.php';

//load security
require_once ROOT . '/config/security.php';