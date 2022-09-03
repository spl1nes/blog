<?php
declare(strict_types=1);

namespace app;

class ApiController
{
    public function downloadView(View $view) : View
    {
        $view->template    = __DIR__ . '/../tpl/download.tpl.php';
        $view->subtemplate = \base64_decode($_GET['download']);

        $levels = \ob_get_level();
        for ($i = 0; $i < $levels; ++$i) {
            \ob_end_clean();
        }

        \header("Cache-Control: no-cache, must-revalidate");
        \header("Pragma: no-cache");
        \header("Content-type: application/octet-stream");
        \header("Content-Disposition: attachment; filename= ". $view->subtemplate . '.zip');

        return $view;
    }
}
