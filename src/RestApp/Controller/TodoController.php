<?php
namespace RestApp\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class TodoController 
{
	public static $data = array();

	public function __construct() 
	{
		self::$data = array(
			"data" => array(
				array(
					"id" => 1,
					"name" => "TODO 1",
					"items" => array(
						array(
							"id" => 1,
							"description" => "TODO 1: ma première tache de la journée",
							"is_read" => 0,
							"is_done" => 0				
						),
						array(
							"id" => 2,
							"description" => "TODO 1: ma deuxième tache de la journée",
							"is_read" => 1,
							"is_done" => 1				
						),								
					)
				),
				array(
					"id" => 1,
					"name" => "TODO 2",
					"items" => array(
						array(						
							"id" => 0,
							"description" => "TODO 2: ma première tache de la journée",
							"is_read" => 0,
							"is_done" => 0
						),
						array(
							"id" => 2,
							"description" => "TODO 2: ma deuxième tache de la journée",
							"is_read" => 1,
							"is_done" => 1				
						),					
					)				
				),
				array(
					"id" => 2,
					"name" => "TODO 3",
					"items" => array(
						array(
							"id" => 0,
							"description" => "TODO 3 : ma première tache de la journée",
							"is_read" => 0,
							"is_done" => 0
						)			
					)				
				)	)
			);		
	}
	
	/**
     * Get all todo list in database with associated items
     *
     * @return JsonResponse
     */
	public function getAllTodo() 
	{
		// TODO : plug getAll with repo	
		return new JsonResponse($this->data, 200);		
	}

	/**
     * Save and persist todo and items in database
     *
	 * @param Request $request
     * @return JsonResponse
     */
	public function saveTodo(Request $request) 
	{
		$todoData = $request->request->get("data");
		// TODO : plug save with repo
		return new JsonResponse(array(
			'statusCode' 	=> 1,
			'message' 		=> 'Items has been stored successfully'), 
		200);
	}

	public function updateTodo($id, Request $request)
	{
		$todoData = $request->request->get("data");
		// update par l'id avec les data passé
		return new JsonResponse(array('ok'));
	}

	public function deleteTodo($id, Request $request)
	{
		return new JsonResponse(array('ok'));
	}
}
?>
