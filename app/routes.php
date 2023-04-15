<?php

declare(strict_types=1);

use App\Application\Actions\Auth\Token\AccessTokenByPasswordAction;
use App\Application\Actions\DefaultAction;
use App\Application\Actions\Item\CreateItemAction;
use App\Application\Actions\Item\ListItemsAction;
use App\Application\Actions\Item\SearchItemsAction;
use App\Application\Actions\Item\UpdateItemAction;
use App\Application\Actions\Item\ViewItemAction;
use App\Application\Actions\Right\CreateRightAction;
use App\Application\Actions\Right\DeleteRightAction;
use App\Application\Actions\Right\ListRightsByItemAction;
use App\Application\Actions\Right\UpdateRightAction;
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

    $app->get('/', DefaultAction::class);

    $app->group('/auth', function (Group $group) {
        $group->post('/access-token', AccessTokenByPasswordAction::class);
    });

    $app->group('/items', function (Group $group) {
        $group->get('', ListItemsAction::class);
        $group->get('/search', SearchItemsAction::class);
        $group->post('', CreateItemAction::class);
        $group->get('/{id}', ViewItemAction::class);
        $group->put('/{id}', UpdateItemAction::class);
    })->add(CheckJWTTokenMiddleware::class);

    $app->group('/rights', function (Group $group) {
        $group->get('/by-item', ListRightsByItemAction::class);
        $group->post('', CreateRightAction::class);
        $group->put('/{id}', UpdateRightAction::class);
        $group->delete('/{id}', DeleteRightAction::class);
    })->add(CheckJWTTokenMiddleware::class);
};
