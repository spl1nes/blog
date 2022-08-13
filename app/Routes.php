<?php
declare(strict_types=1);

return [
    '^/*$'            => 'FrontController:frontView',
    '.*?/blog/list' => 'FrontController:blogListView',
    '.*?/blog/post/.*' => 'FrontController:blogPostView',
    '.*?/solution/list' => 'FrontController:solutionListView',
    '.*?/solution/item' => 'FrontController:solutionItemView',
    '.*?/about'        => 'FrontController:aboutView',
    '.*?/imprint'   => 'FrontController:imprintView',
    '.*?/terms'     => 'FrontController:termsView',
    '.*?/privacy'   => 'FrontController:privacyView',
    '.*?/contact'   => 'FrontController:contactView',
];
