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

        $view->data['articles'] = $this->getArticleSummaries(__DIR__ . '/../content/blog', 4);

        return $view;
    }

    public function blogListView(View $view) : View
    {
        $view->template    = __DIR__ . '/../tpl/index.tpl.php';
        $view->subtemplate = __DIR__ . '/../tpl/content/blog-list.tpl.php';

        $view->data['articles'] = $this->getArticleSummaries(__DIR__ . '/../content/blog', 0);

        return $view;
    }

    private function getArticleSummaries(string $path, int $limit = 0) : array
    {
        $articles = $this->getArticleList($path, '.*\.md$');
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

            $list[] = [
                'headline' => $headline,
                'summary'  => \str_replace(['<p>', '</p>'], '', Markdown::parse($summary)),
                'path'     => \explode('/', $article)[0],
                'name'     => \str_replace('.md', '', \explode('/', $article)[1]),
            ];
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

        $view->content     = __DIR__ . '/../content/blog/' . $paths[$length - 2] . '/' . $paths[$length - 1] . '.md';

        return $view;
    }

    public function solutionListView(View $view) : View
    {
        $view->template    = __DIR__ . '/../tpl/index.tpl.php';
        $view->subtemplate = __DIR__ . '/../tpl/content/solution-list.tpl.php';

        $view->data['solutions'] = $this->getArticleSummaries(__DIR__ . '/../content/solutions', 0);

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
        $view->subtemplate = __DIR__ . '/../tpl/content/?.tpl.php';
        $view->content     = __DIR__ . '/../content/?.' . ($view->data['lang'] ?? 'en') . '.tpl.php';

        return $view;
    }

    public function aboutView(View $view) : View
    {
        $view->template    = __DIR__ . '/../tpl/index.tpl.php';
        $view->subtemplate = __DIR__ . '/../tpl/content/default.tpl.php';
        $view->content     = __DIR__ . '/../content/about/' . ($view->data['lang'] ?? 'en') . '_about.tpl.php';

        return $view;
    }

    public function imprintView(View $view) : View
    {
        $view->template    = __DIR__ . '/../tpl/index.tpl.php';
        $view->subtemplate = __DIR__ . '/../tpl/content/?.tpl.php';
        $view->content     = __DIR__ . '/../content/?.' . ($view->data['lang'] ?? 'en') . '.tpl.php';

        return $view;
    }

    public function termsView(View $view) : View
    {
        $view->template    = __DIR__ . '/../tpl/index.tpl.php';
        $view->subtemplate = __DIR__ . '/../tpl/content/?.tpl.php';
        $view->content     = __DIR__ . '/../content/?.' . ($view->data['lang'] ?? 'en') . '.tpl.php';

        return $view;
    }

    public function privacyView(View $view) : View
    {
        $view->template    = __DIR__ . '/../tpl/index.tpl.php';
        $view->subtemplate = __DIR__ . '/../tpl/content/?.tpl.php';
        $view->content     = __DIR__ . '/../content/?.' . ($view->data['lang'] ?? 'en') . '.tpl.php';

        return $view;
    }

    public function contactView(View $view) : View
    {
        $view->template    = __DIR__ . '/../tpl/index.tpl.php';
        $view->subtemplate = __DIR__ . '/../tpl/content/?.tpl.php';
        $view->content     = __DIR__ . '/../content/?.' . ($view->data['lang'] ?? 'en') . '.tpl.php';

        return $view;
    }

}
