<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Max\HexletSlimExample\Users;
use Max\HexletSlimExample\Validator;
use Slim\Factory\AppFactory;
use DI\Container;
use Slim\Psr7\Message;
use Slim\Views\PhpRenderer;
use Slim\Middleware\MethodOverrideMiddleware;

session_start();

$container = new Container();
$container->set(PhpRenderer::class, function () {
    return new PhpRenderer(__DIR__ . '/../templates');
});
$container->set(Message::class, fn() => new \Slim\Flash\Messages());

$app = AppFactory::createFromContainer($container);
$app->addErrorMiddleware(true, true, true);
$app->add(MethodOverrideMiddleware::class);

$app->get('/', function($request, $response) {
    $response->getBody()->write('Welcome to Slim!');
    return $response;
});


$courses = [
    1 => ['id' => 1, 'name' => 'PHP'],
    2 => ['id' => 2, 'name' => 'Go'],
    3 => ['id' => 3, 'name' => 'Ruby'],
    4 => ['id' => 4, 'name' => 'Java'],
    5 => ['id' => 5, 'name' => 'Kotlin']
];

$app->get('/courses', function ($request, $response, $args) use ($courses) {
    $params = ['courses' => $courses];
    return $this->get(PhpRenderer::class)->render($response, '/courses/index.phtml', $params);
});

$app->get('/courses/{slug}', function ($request, $response, $args) {
    $slug = $args['slug'];
    $params = ['slug' => $slug];
    return $this->get(PhpRenderer::class)->render($response, '/courses/id.phtml', $params);
});


$app->get('/users/new', function ($request, $response, $args) {
    $params = [
        'user' => ['id' => '', 'nickname' => '', 'email' => ''],
        'errors' => []
    ];
    return $this->get(PhpRenderer::class)->render($response, '/users/new.phtml', $params);

})->setName('newUsers');

$app->get('/users', callable: function ($request, $response) {
    $users = new Users();
    $messages = $this->get(Message::class)->getMessages();
    $params = [
        'users' => $users->getUsers(),
        'flash' => $messages
    ];
    return $this->get(PhpRenderer::class)->render($response, '/users/index.phtml', $params);
})->setName('users');

$app->get('/users/{id}', function ($request, $response, $args) {
    $users = new Users();
    $needleId = $args['id'];
    $user = $users->getUserById($needleId);

    if (is_null($user)) {
        $params = ['id' => $needleId];
        return $this->get(PhpRenderer::class)->render($response->withStatus(404), '/users/404.phtml', $params);
    }

    $params = ['user' => $user];

    return $this->get(PhpRenderer::class)->render($response, '/users/show.phtml', $params);
});

$router = $app->getRouteCollector()->getRouteParser();

$app->post('/users', function ($request, $response) use ($router) {
    $validator = new Validator();
    $user = $request->getParsedBodyParam('user');
    $errors = $validator->validate($user);
    $users = new Users();

    if (count($errors) === 0) {
        $users->save($user);
        $this->get(Message::class)->addMessage('success', 'User was added successfully');
        return $response->withRedirect($router->urlFor('users'), 302);
    }

    $params = [
        'user' => $user,
        'errors' => $errors
    ];

    return $this->get(PhpRenderer::class)->render($response, '/users/new.phtml', $params);
});


$app->get('/users/{id}/edit', function ($request, $response, $args) {
    $users = new Users();
    $id = $args['id'];
    $user = $users->getUserById($id);
    $params = [
        'user' => $user,
        'errors' => []
    ];
    return $this->get(PhpRenderer::class)->render($response, '/users/edit.phtml', $params);
})->setName('editUser');


$app->patch('/users/{id}', function ($request, $response, $args) use ($router) {
    $users = new Users();
    $id = $args['id'];
    // $validator = new Validator();
    $data = $request->getParsedBodyParam('user');
    // $errors = $validator->validate($data);
    $errors = [];

    if (count($errors) === 0) {
        $nickname = $data['nickname'];
        $email = $data['email'];
        $users->editUser($id, $nickname, $email);
        $this->get(Message::class)->addMessage('success', 'User data has been updates!');
        $url = $router->urlFor('users');
        return $response->withRedirect($url, 302);
    }

    $params = [
        'user' => $data,
        'errors' => [],
    ];

    $response = $response->withStatus(422);

    return $this->get(PhpRenderer::class)->render($response, '/users/edit.phtml', $params);
})->setName('editUserHandler');


$app->get('/users/{id}/delete', function ($request, $response, $args) use ($router) {
    $id = $args['id'];
    $users = new Users();
    $user = $users->getUserById($id);
    $params = [
        'user' => $user,
        'errors' => []
    ];
    return $this->get(PhpRenderer::class)->render($response, '/users/delete.phtml', $params);
})->setName('deleteForm');

$app->delete('/users/{id}', function ($request, $response, $args) use ($router) {
    $users = new Users();
    $id = $args['id'];
    $isDeleted = $users->deleteUser($id);

    if ($isDeleted) {
        $this->get(Message::class)->addMessage('success', 'User has been deleted');
    }

    return $response->withRedirect($router->urlFor('users'));
})->setName('delete');

$app->run();
