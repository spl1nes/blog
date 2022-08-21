<?php
declare(strict_types=1);

namespace app;

use resources\markdown\Markdown;

class FrontController
{
    public function frontView(View $view) : View
    {
        $view->template    = __DIR__ . '/../tpl/index.tpl.php';
        $view->subtemplate = __DIR__ . '/../tpl/content/default.tpl.php';
        $view->content     = __DIR__ . '/../content/front/' . ($view->data['lang'] ?? 'en') . '_front.tpl.php';

        $view->data['title'] = 'Jingga';
        $view->data['articles'] = $this->getArticleSummaries(__DIR__ . '/../content/blog', 3);

        $view->data['solutions'] = $this->getArticleSummaries(
            __DIR__ . '/../content/solutions', 0, '_' . ($view->data['lang'] ?? 'en')
        );

        \shuffle($view->data['solutions']);
        $view->data['solutions'] = [$view->data['solutions'][0]];

        $json = \json_decode(\file_get_contents(__DIR__ . '/../content/solutions/list.json'), true);

        foreach ($view->data['solutions'] as $key => $solution) {
            $splash = $solution['parent'];

            $view->data['solutions'][$key]['splash'] = $splash . '_splash.png';
            $view->data['solutions'][$key]['price']  = ($view->data['lang'] ?? 'en') === 'de'
                ? \str_replace([',', '.', ':'], [':', ',', '.'], $json[$splash]['price'] ?? '0.00') . ' EUR'
                : 'EUR ' . $json[$splash]['price'] ?? '0.00';
        }

        return $view;
    }

    public function blogListView(View $view) : View
    {
        $view->template    = __DIR__ . '/../tpl/index.tpl.php';
        $view->subtemplate = __DIR__ . '/../tpl/content/blog-list.tpl.php';

        $view->data['title'] = 'Blog';
        $view->data['articles'] = $this->getArticleSummaries(__DIR__ . '/../content/blog', 0);

        return $view;
    }

    private function getArticleSummary(string $path, string $article) : array
    {
        $f = \fopen($path . '/' . $article, 'r');
        $headline = \fgets($f);

        $start = false;
        $end   = false;

        $summary = '';

        while (!$end && ($line = \fgets($f)) !== false) {
            if (\trim($line) !== '') {
                $summary .= $line;
                $start    = true;
            } elseif ($start && !$end) {
                $end = true;
            }
        }

        \fclose($f);

        $headline = \str_replace('#', '', $headline);
        $headline = \trim($headline);

        $parser = new Markdown();

        $subpath       = \explode('/', $article);
        $subpathLength = \count($subpath);

        return [
            'headline' => $headline,
            'summary'  => \str_replace(['<p>', '</p>'], '', $parser->parse($summary)),
            'path'     => $subpath[0],
            'name'     => \str_replace('.md', '', $subpath[$subpathLength - 1]),
            'parent'   => \basename(\dirname($article)),
        ];
    }

    private function getArticleSummaries(string $path, int $limit = 0, string $l11n = '') : array
    {
        $articles = $this->getArticleList($path, '.*' . $l11n . '\.md$');
        usort($articles, function($a, $b) {
            $a = \explode('/', $a)[1];
            $b = \explode('/', $b)[1];

            return \strcmp($a, $b) > 0 ? -1 : 1;
        });

        $list = [];

        $count = 0;

        foreach ($articles as $article) {
            if (\stripos($article, '/_') !== false) {
                continue;
            }

            ++$count;
            if ($limit > 0 && $count > $limit) {
                break;
            }

            $list[] = $this->getArticleSummary($path, $article);
        }

        return $list;
    }

    private function getArticleList(string $path, string $filter = '*') : array
    {
        if (!\is_dir($path)) {
            return [];
        }

        $list = [];
        $path = \rtrim($path, '\\/');

        $iterator = true
            ? new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($path, \RecursiveDirectoryIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::SELF_FIRST)
            : new \DirectoryIterator($path);

        if ($filter !== '*') {
            $iterator = new \RegexIterator($iterator, '/' . $filter . '/i', \RecursiveRegexIterator::GET_MATCH);
        }

        /** @var \DirectoryIterator $iterator */
        foreach ($iterator as $item) {
            if (!true && !$item->isDot()) {
                continue;
            }

            $list[] = \substr(\str_replace('\\', '/', $iterator->getPathname()), \strlen($path) + 1);
        }

        /** @var string[] $list */
        return $list;
    }

    public function blogPostView(View $view) : View
    {
        $view->template    = __DIR__ . '/../tpl/index.tpl.php';
        $view->subtemplate = __DIR__ . '/../tpl/content/default.md.php';

        $paths  = \explode('/', $view->data['url']['path']);
        $length = \count($paths);

        $view->data['article'] = $this->getArticleSummary(
            __DIR__ . '/../content/blog',
            $paths[$length - 2] . '/' . $paths[$length - 1] . '.md'
        );

        $view->data['title'] = $view->data['article']['headline'];
        $view->content       = __DIR__ . '/../content/blog/'
            . $paths[$length - 2] . '/' . $paths[$length - 1] . '.md';

        return $view;
    }

    public function solutionListView(View $view) : View
    {
        $view->template    = __DIR__ . '/../tpl/index.tpl.php';
        $view->subtemplate = __DIR__ . '/../tpl/content/solution-list.tpl.php';

        $view->data['title'] =  $view->data['l11n']['Solutions'];
        $view->data['solutions'] = $this->getArticleSummaries(
            __DIR__ . '/../content/solutions', 0, '_' . ($view->data['lang'] ?? 'en')
        );

        \shuffle($view->data['solutions']);

        $json = \json_decode(\file_get_contents(__DIR__ . '/../content/solutions/list.json'), true);

        foreach ($view->data['solutions'] as $key => $solution) {
            $splash = $solution['parent'];

            $view->data['solutions'][$key]['splash'] = $splash . '_splash.png';
            $view->data['solutions'][$key]['price']  = ($view->data['lang'] ?? 'en') === 'de'
                ? \str_replace([',', '.', ':'], [':', ',', '.'], $json[$splash]['price'] ?? '0.00') . ' EUR'
                : 'EUR ' . $json[$splash]['price'] ?? '0.00';
        }

        /*
        $stripe = new \Stripe\StripeClient(
            ''
          );
        $s =  $stripe->prices->retrieve(
            '',
            []
          );
        */

        return $view;
    }

    public function solutionItemView(View $view) : View
    {
        $view->template    = __DIR__ . '/../tpl/index.tpl.php';
        $view->subtemplate = __DIR__ . '/../tpl/content/default.md.php';

        $paths  = \explode('/', $view->data['url']['path']);
        $length = \count($paths);

        $view->data['solution'] = $this->getArticleSummary(
            __DIR__ . '/../content/solutions',
            $paths[$length - 2] . '/' . \substr($paths[$length - 1], 9) . '/' . $paths[$length - 1] . '_' . ($view->data['lang'] ?? 'en') . '.md'
        );

        $view->data['title']  = $view->data['solution']['headline'];

        $hasJsonLd = \is_file($jsonldPath = __DIR__ . '/../content/solutions/'
            . $paths[$length - 2] . '/' . \substr($paths[$length - 1], 9) . '/'
            . $paths[$length - 1] . '_' . ($view->data['lang'] ?? 'en') . '.jsonld.php');

        if ($hasJsonLd) {
            $view->data['jsonld'] = include $jsonldPath;
        }

        $view->content = __DIR__ . '/../content/solutions/'
            . $paths[$length - 2] . '/' . \substr($paths[$length - 1], 9) . '/'
            . $paths[$length - 1] . '_' . ($view->data['lang'] ?? 'en') . '.md';

        return $view;
    }

    public function aboutView(View $view) : View
    {
        $view->template       = __DIR__ . '/../tpl/index.tpl.php';
        $view->subtemplate    = __DIR__ . '/../tpl/content/default.tpl.php';
        $view->content        = __DIR__ . '/../content/about/' . ($view->data['lang'] ?? 'en') . '_about.tpl.php';
        $view->data['title']  = $view->data['l11n']['About'];
        $view->data['jsonld'] = include __DIR__ . '/../content/contact/contact.jsonld.php';

        return $view;
    }

    public function imprintView(View $view) : View
    {
        $view->template      = __DIR__ . '/../tpl/index.tpl.php';
        $view->subtemplate   = __DIR__ . '/../tpl/content/default.md.php';
        $view->content       = __DIR__ . '/../content/imprint/' . ($view->data['lang'] ?? 'en') . '_imprint.md';
        $view->data['title'] = $view->data['l11n']['Imprint'];

        return $view;
    }

    public function termsView(View $view) : View
    {
        $view->template      = __DIR__ . '/../tpl/index.tpl.php';
        $view->subtemplate   = __DIR__ . '/../tpl/content/default.md.php';
        $view->content       = __DIR__ . '/../content/terms/' . ($view->data['lang'] ?? 'en') . '_terms.md';
        $view->data['title'] = $view->data['l11n']['Terms'];

        return $view;
    }

    public function privacyView(View $view) : View
    {
        $view->template      = __DIR__ . '/../tpl/index.tpl.php';
        $view->subtemplate   = __DIR__ . '/../tpl/content/default.md.php';
        $view->content       = __DIR__ . '/../content/privacy/' . ($view->data['lang'] ?? 'en') . '_privacy.md';
        $view->data['title'] = $view->data['l11n']['Privacy'];

        return $view;
    }

    public function contactView(View $view) : View
    {
        $view->template       = __DIR__ . '/../tpl/index.tpl.php';
        $view->subtemplate    = __DIR__ . '/../tpl/content/default.tpl.php';
        $view->content        = __DIR__ . '/../content/contact/contact.tpl.php';
        $view->data['title']  = $view->data['l11n']['Contact'];
        $view->data['jsonld'] = include __DIR__ . '/../content/contact/contact.jsonld.php';

        return $view;
    }

}
