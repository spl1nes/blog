<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    Web
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types=1);

namespace src\controller;

use phpOMS\Message\RequestAbstract;
use phpOMS\Message\ResponseAbstract;
use phpOMS\Views\View;

use phpOMS\System\File\Local\Directory;
use phpOMS\Utils\Parser\Markdown\Markdown;

/**
 * Controller class.
 *
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
class MainController
{
    public static function viewHome(RequestAbstract $request, ResponseAbstract $response, $data = null)
    {
        $view = new View(null, $request, $response);
        $view->setTemplate('/src/tpl/content/home');

        $articles = Directory::list(__DIR__ . '/../tpl/content/blog', '.*\.md$');
        usort($articles, function($a, $b) {
            $a = \explode('/', $a)[1];
            $b = \explode('/', $b)[1];

            return \strcmp($a, $b) > 0 ? -1 : 1;
        });

        $list = [];

        foreach ($articles as $article) {
            if (($request->getUri()->getPathElement(0) !== '' 
                && \stripos($article, $request->getUri()->getPathElement(0)) !== 0)
                || \stripos($article, '/_') !== false
            ) {
                continue;
            }

            $f = \fopen(__DIR__ . '/../tpl/content/blog/' . $article, 'r');
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

        $view->setData('articles', $list);

        return $view;
    }

    public static function viewArticle(RequestAbstract $request, ResponseAbstract $response, $data = null)
    {
        $view = new View(null, $request, $response);
        $view->setTemplate('/src/tpl/content/article');

        $content = \file_get_contents(__DIR__ . '/../tpl/content/blog/' . $request->getUri()->getPathElement(1) . '/' . $request->getUri()->getPathElement(2) . '.md');
        $view->setData('article', Markdown::parse($content));

        return $view;
    }

    public static function viewCV(RequestAbstract $request, ResponseAbstract $response, $data = null)
    {
        $view = new View(null, $request, $response);
        $view->setTemplate('/src/tpl/content/cv');

        return $view;
    }

    public static function viewContact(RequestAbstract $request, ResponseAbstract $response, $data = null)
    {
        $view = new View(null, $request, $response);
        $view->setTemplate('/src/tpl/content/contact');

        return $view;
    }
}