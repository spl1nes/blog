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

namespace src;

use phpOMS\Log\FileLogger;
use phpOMS\Message\Http\Request;
use phpOMS\Message\Http\Response;
use phpOMS\Uri\UriFactory;
use phpOMS\Views\View;
use phpOMS\Router\Router;
use phpOMS\Dispatcher\Dispatcher;
use phpOMS\Autoloader;

/**
 * Application class.
 *
 * @package    Web
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
class WebApp
{
    /**
     * Constructor.
     *
     * @since  1.0.0
     */
    public function __construct()
    {
        //$this->setupHandlers();

        $request  = $this->initRequest();
        $response = $this->initResponse($request);

        UriFactory::setupUriBuilder($request->getUri());

        $pageView = new View(null, $request, $response);
        $pageView->setTemplate('/src/tpl/index');
        $response->set('Content', $pageView);

        $router = new Router();
        $router->importFromFile(__DIR__ . '/Routes.php');

        $dispatcher = new Dispatcher();

        $dispatched = $dispatcher->dispatch(
            $router->route(
                $request->getUri()->getRoute(),
                $request->getRouteVerb()
            ),
            $request, 
            $response
        );
        $pageView->addData('dispatch', $dispatched);

        /** @var \phpOMS\Message\Http\Header $header */
        $header = $response->getHeader();
        $header->push();

        echo $response->getBody();
    }

    /**
     * Setup general handlers for the application.
     *
     * @return void
     *
     * @since  1.0.0
     */
    private function setupHandlers() : void
    {
        \set_exception_handler(['\phpOMS\UnhandledHandler', 'exceptionHandler']);
        \set_error_handler(['\phpOMS\UnhandledHandler', 'errorHandler']);
        \register_shutdown_function(['\phpOMS\UnhandledHandler', 'shutdownHandler']);
        \mb_internal_encoding('UTF-8');
    }

    /**
     * Initialize current application request
     *
     * @return Request Initial client request
     *
     * @since  1.0.0
     */
    private function initRequest() : Request
    {
        $request = Request::createFromSuperglobals();

        $request->createRequestHashs(0);
        $request->getUri()->setRootPath('/');
        UriFactory::setupUriBuilder($request->getUri());

        return $request;
    }

    /**
     * Initialize basic response
     *
     * @param Request $request   Client request
     *
     * @return Response Initial client request
     *
     * @since  1.0.0
     */
    private function initResponse(Request $request) : Response
    {
        $response = new Response();
        $response->getHeader()->set('content-type', 'text/html; charset=utf-8');
        $response->getHeader()->set('x-xss-protection', '1; mode=block');
        $response->getHeader()->set('x-content-type-options', 'nosniff');
        $response->getHeader()->set('x-frame-options', 'SAMEORIGIN');
        $response->getHeader()->set('referrer-policy', 'same-origin');

        if ($request->isHttps()) {
            $response->getHeader()->set('strict-transport-security', 'max-age=31536000');
        }

        return $response;
    }
}
