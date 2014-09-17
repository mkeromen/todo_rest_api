<?php 
namespace RestApp\Repository;

class TodoRepository
{
	private $connection;

	public function __construct(\RestApp\Service\Connection $connection)
	{
		$this->connection = $connection;		
	}

	public function getAllTodo() 
	{
		$openConnection = $this->connection->connect();
		// TODO : Implements try/catch block
		$stmt = $openConnection->prepare("SELECT * FROM todo_list 
        LEFT JOIN todo_items ON (todo_list.id = todo_items.todo_list_id) ");
		$stmt->execute();

		$data = array();
		$todoIds = array();
		while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
			
			$todo_list_id = $row['todo_list_id'];
			if(!in_array($todo_list_id, $todoIds)) {
				$data['data'][$todo_list_id] = array(
					'id' 	=> $todo_list_id,
					'name' 	=> $row['name']			
				);
				$todoIds[] = $todo_list_id;			
			} 
			
			$data['data'][$todo_list_id]['items'][] = array(
				"id" 			=> $row['id'],
				"description" 	=> $row['description'],
				"is_read" 		=> $row['is_read'],
				"is_done" 		=> $row['is_done']	
			);		
			
		}		
	}

	public function saveTodo($data)
	{
		$openConnection = $this->connection->connect();
		// TODO : Implements try/catch block
		foreach($data as $todo) {
			
			$currentTime = time();
			$stmt = $openConnection->prepare("INSERT INTO 
				todo_list (id, name, date_create, date_update) 
				VALUES ('', :name, :date_create, :date_update)");

			$stmt->bindParam(':name', $todo['name']);
			$stmt->bindParam(':date_create', $currentTime);
			$stmt->bindParam(':date_update', $currentTime);
			
			$stmt->execute();
			$todo_id = $openConnection->lastInsertId();

			if(is_array($todo['items'])) {
				foreach($todo['items'] as $item) {

						$stmt = $openConnection->prepare("INSERT INTO 
						todo_items (id, todo_list_id, description, is_read, is_done) VALUES ('', :todo_list_id, :description, 0, 0)");
					
						$stmt->bindParam(':todo_list_id', $todo_id);
						$stmt->bindParam(':description', $item['description']);
						$stmt->execute();
				}
			}	
		}
		
		$this->connection->close();
	}

	public function updateTodo($id, Request $request)
	{
		$openConnection = $this->connection->connect();
		// TODO : Implements try/catch block
		foreach($data as $todo) {
			
			$currentTime = time();
			$stmt = $openConnection->prepare("UPDATE 
				todo_list SET name=:name, date_update=:date_update
				WHERE id=:id");

			$stmt->bindParam(':name', $todo['name']);
			$stmt->bindParam(':date_update', $currentTime);
			$stmt->bindParam(':id', $todo['id']);
			
			$stmt->execute();

			if(is_array($todo['items'])) {
				foreach($todo['items'] as $item) {

						$stmt = $openConnection->prepare("UPDATE 
				todo_items SET description=:description, date_update=:date_update
				WHERE todo_list_id=:todo_list_id");
					
						$stmt->bindParam(':todo_list_id', $item['id']);
						$stmt->bindParam(':description', $item['description']);
						$stmt->execute();
				}
			}	
		}
		
		$this->connection->close();	
	}
}
?>
