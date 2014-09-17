<?php
ini_set("display_errors", true);

use Silex\Provider\ServiceControllerServiceProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

require_once __DIR__ . "/../vendor/autoload.php";

$app = new Silex\Application();

$app['db.host']  		= 'localhost';
$app['db.name']  		= 'todo';
$app['db.user']  		= 'root';
$app['db.password']  	= 'cachou';


// Accept and decode JSON data before the call to todo controller
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

// Call this from json config/config.json
$app['api.endpoint'] 	= "/api";
$app['api.version'] 	= "v1";

$app->register(new ServiceControllerServiceProvider());

$connection = new RestApp\Service\PDOConnection($app);
/*var_dump($connection);
exit;*/

$app['todo.repo'] = $app->share(function () use ($connection) {
	return new RestApp\Repository\TodoRepository($connection);
});

// Maybe find a way to move this out 
$app['todo.controller'] = $app->share(function () {
	return new RestApp\Controller\TodoController();
});

$todoCtrl = $app['todo.controller'];

$todoRepo = $app['todo.repo'];

//$todoRepo->saveTodo($todoCtrl::$data['data']);
$todoRepo->getAllTodo();
exit;

$app['converter.user'] = $app->share(function () {
    return new RestApp\Service\UserConverter();
});

$api = $app["controllers_factory"];

$api->get('/{token}/todo/get', "todo.controller:getAllTodo")
	->convert('token', 'converter.user:authorize');

$api->post('/{token}/todo/save', "todo.controller:saveTodo");
$api->put('/{token}/todo/update/{id}', "todo.controller:updateTodo");
$api->delete('/{token}/todo/delete/{id}', "todo.controller:deleteTodo");

$app->mount($app["api.endpoint"].'/'.$app["api.version"], $api);

// Proper way to handler error in app
$app->error(function (\Exception $e, $code) use ($app) {
	return new JsonResponse(array(
		"statusCode" 	=> $code, 
		"message" 		=> $e->getMessage()
	));
});

$app->run();
?>