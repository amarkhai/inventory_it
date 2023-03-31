<?php

declare(strict_types=1);

use App\Application\Actions\Auth\Token\AccessTokenByPasswordAction;
use App\Application\Actions\Item\CreateItemAction;
use App\Application\Actions\Item\ListItemsAction;
use App\Application\Actions\Item\ViewItemAction;
use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use App\Application\Middleware\CheckJWTTokenMiddleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write('Hello world!');
        return $response;
    });

    $app->group('/auth', function (Group $group) {
        $group->post('/access-token', AccessTokenByPasswordAction::class);
    });

    $app->group('/users', function (Group $group) {
        $group->get('', ListUsersAction::class);
        $group->get('/{id}', ViewUserAction::class);
    })->add(CheckJWTTokenMiddleware::class);

    $app->group('/items', function (Group $group) {
        $group->get('', ListItemsAction::class);
        $group->post('', CreateItemAction::class);
        $group->get('/{id}', ViewItemAction::class);
    })->add(CheckJWTTokenMiddleware::class);
};
