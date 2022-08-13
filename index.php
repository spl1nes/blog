<?php
declare(strict_types=1);

\ob_start();

require_once __DIR__ . '/app/Autoloader.php';

$App = new \app\Application();
echo $App->run();

if (\ob_get_level() > 0) {
    \ob_end_flush();
}
