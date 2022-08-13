<?php
declare(strict_types=1);

namespace app;

class Application
{
    private array $url         = [];
    private array $routes      = [];
    private array $controllers = [];

    public function __construct()
    {
        $this->url         = \parse_url($_SERVER['REQUEST_URI']);
        $this->routes      = include __DIR__ . '/Routes.php';
        $this->controllers = [
            'FrontController' => new FrontController(),
            'ApiController'   => new ApiController(),
        ];
    }

    public function run() : string
    {
        $dispatch = $this->route($this->url);

        $lang = \str_ends_with($this->url['path'], '/de') || \str_contains($this->url['path'], '/de/') ? 'de' : 'en';

        $this->url['path'] = \str_replace(['/en/', '/de/'], '', $this->url['path'] ?? '');
        $this->url['path'] = '/' . \trim($this->url['path'], '/') . '/';

        // Fixes bug where /en/de or /en/de etc. gets produced as a url
        $this->url['path'] = \str_replace(['/en/', '/de/'], '', $this->url['path'] ?? '');
        $this->url['path'] = '/' . \trim($this->url['path'], '/');

        $l11n = include __DIR__ . '/../tpl/lang/' . $lang . '.php';

        $view = new View();
        $view->data['lang'] = $lang;
        $view->data['l11n'] = $l11n;
        $view->data['url']  = $this->url;

        $result = $this->controllers[$dispatch[0]]->{$dispatch[1]}($view);

        return $result->render();
    }

    private function route(array $url) : array
    {
        $dispatch = [];
        foreach ($this->routes as $route => $endpoint) {
            if (!((bool) \preg_match('~' . $route . '~', $url['path'] ?? ''))) {
                continue;
            }

            return \explode(':', $endpoint);
        }

        if (empty($dispatch)) {
            $dispatch = ['FrontController', 'frontView'];
        }

        return $dispatch;
    }
}
