<?php
// Configuration for db
$app['db.host']  	= 'localhost';
$app['db.name']  	= 'todo';
$app['db.user']  	= 'root';
$app['db.password']  	= 'pwd';

// Configuration for REST API
$app['api.endpoint'] 	= "/api";
$app['api.version'] 	= "v1";
$app['api.validtoken'] 	= "123654"; 

/*
$mock = array(
	"data" => array(
				array(
					"id" => 20,
					"name" => "TODO 1 : J'ai bien avancé today",
					"items" => array(
						array(
							"id" => 6,
							"description" => "TODO 1: ma première tache de la journée",
							"is_read" => 1,
							"is_done" => 1				
						),
						array(
							"id" => 7,
							"description" => "TODO 1: ma deuxième tache de la journée",
							"is_read" => 0,
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
				)	
		)
);*/
?>
