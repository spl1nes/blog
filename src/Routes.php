<?php

use phpOMS\Router\RouteVerb;

return [
    '^/*(dev|business)*$' => [
        [
            'dest' => '\src\controller\MainController::viewHome',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^article/.*$' => [
        [
            'dest' => '\src\controller\MainController::viewArticle',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^cv.*$' => [
        [
            'dest' => '\src\controller\MainController::viewCV',
            'verb' => RouteVerb::GET,
        ],
    ],
];