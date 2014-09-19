<?php
//ini_set("display_errors", true);

use Silex\Provider\ServiceControllerServiceProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

require_once __DIR__ . "/../vendor/autoload.php";

$app = new Silex\Application();

require_once __DIR__ . "/../resources/config.php";

// Accept and decode JSON data before the call to controllers
$app->before(function (Request $request) {
    if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {	
        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : array());
    }
});

// Add correct headers before it is sent to the client
$app->after(function (Request $request, Response $response) {
    $response->headers->set("Access-Control-Allow-Origin","*");
    $response->headers->set("Access-Control-Allow-Methods","GET,POST,PUT,DELETE,OPTIONS");
});

$app->register(new ServiceControllerServiceProvider());

$connection = new RestApp\Service\PDOConnection($app);

$app['todo.repo'] = $app->share(function () use ($connection) {
    return new RestApp\Repository\TodoRepository($connection);
});

$app['todo.controller'] = $app->share(function () use ($app) {
    return new RestApp\Controller\TodoController($app['todo.repo']);
});

$app['converter.user'] = $app->share(function () use ($app) {
    return new RestApp\Service\UserAuthentication($app['api.validtoken']);
});

$api = $app["controllers_factory"];

$api->get('/todo/get', "todo.controller:getAllTodo")
    ->convert('token', 'converter.user:authorize');

$api->post('/todo/save', "todo.controller:saveTodo")
    ->convert('token', 'converter.user:authorize');

$api->put('/todo/update', "todo.controller:updateTodo")
    ->convert('token', 'converter.user:authorize');

$api->delete('/todo/delete', "todo.controller:deleteTodo")
    ->convert('token', 'converter.user:authorize');

$app->mount($app["api.endpoint"].'/'.$app["api.version"] . '/{token}', $api);

// Proper way to handler error in app
$app->error(function (\Exception $e, $code) use ($app) {
    return new JsonResponse(array(
        "statusCode" 	=> $code, 
        "message" 	=> $e->getMessage()
    ));
});

$app->run();
?>
