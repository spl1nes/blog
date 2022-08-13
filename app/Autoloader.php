<?php
declare(strict_types=1);

namespace app;

\spl_autoload_register('\app\Autoloader::defaultAutoloader');

final class Autoloader
{
    private static $paths = [
        __DIR__ . '/../',
        __DIR__ . '/../resources/stripe/lib',
    ];

    public static function defaultAutoloader(string $class) : void
    {
        $class = \ltrim($class, '\\');
        $class = \str_replace(['_', '\\'], '/', $class);

        foreach (self::$paths as $path) {
            if (\is_file($file = $path . $class . '.php')) {
                include $file;

                return;
            } if (\is_file($file = $path . \substr($class, \stripos($class, '/')) . '.php')) {
                include $file;

                return;
            }
        }
    }
}
