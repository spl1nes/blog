<?php
declare(strict_types=1);

namespace app;

class View
{
    public array  $data        = [];
    public string $template    = '';
    public string $subtemplate = '';
    public string $content     = '';

    public function render() : string
    {
        $ob = '';

        try {
            \ob_start();

            $path = $this->template;
            if (!\is_file($path)) {
                return '';
            }

            $includeData = include $path;

            $ob = (string) \ob_get_clean();
        } catch (\Throwable $e) {
            \ob_end_clean();

            $ob = '';
        }

        return $ob;
    }
}
