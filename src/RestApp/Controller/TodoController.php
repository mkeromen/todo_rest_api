<?php
namespace RestApp\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class TodoController 
{

    private $todoRepository;

    public function __construct(\RestApp\Repository\TodoRepository $todoRepository) 
    {	
        $this->todoRepository = $todoRepository;			
    }
	
    /**
     * Get all todo list in database with associated items
     *
     * @return JsonResponse
     */
    public function getAllTodo() 
    {
        $data = $this->todoRepository->getAllTodo();
	return new JsonResponse($data, 200);		
    }

    /**
     * Save and persist todo and items in database
     * Request has JSON data
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function saveTodo(Request $request) 
    {	
        $this->todoRepository->saveTodo($this->getDataFromRequest($request));
	return new JsonResponse(array(
            'statusCode' 	=> 1,
            'message' 		=> 'Items has been stored successfully.'
        ));
    }

    /**
     * Update and persist todo and items in database
     * Request has JSON data
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updateTodo(Request $request)
    {
        $this->todoRepository->updateTodo($this->getDataFromRequest($request));
	return new JsonResponse(array(
            'statusCode' 	=> 200,
            'message' 		=> 'Item(s) has been updated successfully.'
        ));
    }

    /**
     * Delete and persist todo and items in database
     * Request has JSON data
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteTodo(Request $request)
    {
        $this->todoRepository->deleteTodo($this->getDataFromRequest($request));		
	return new JsonResponse(array(
            'statusCode' 	=> 200,
            'message' 		=> 'Item(s) has been deleted successfully.'
        ));
    }
	
    private function getDataFromRequest(Request $request)
    {
	$requestData = $request->request->get('data');
	return $requestData;
    }
}
?>
