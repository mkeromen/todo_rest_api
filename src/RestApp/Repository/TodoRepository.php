<?php 
namespace RestApp\Repository;

class TodoRepository
{
    private $connection;

    public function __construct(\RestApp\Service\Connection $connection)
    {
	$this->connection = $connection;		
    }

    /**
     * Fetch todo list and items
     *
     * @return array $data An array of todo and items
     */
    public function getAllTodo() 
    {
        // TODO : Implements try/catch block
        $openConnection = $this->connection->connect();
	
	$stmt = $openConnection->prepare("SELECT * FROM todo_list LEFT JOIN todo_items ON (todo_list.id = todo_items.todo_list_id) ");
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
                "id"            => $row['id'],
		"description" 	=> $row['description'],
		"is_read" 	=> $row['is_read'],
		"is_done" 	=> $row['is_done']	
            );	
			
	}

	unset($todoIds);
	return $data;		
    }

    /**
     * Save todo list and items
     *
     * @param array $data An array of todo and items
     */
    public function saveTodo($data)
    {		
	// TODO : Implements try/catch block		
	$openConnection = $this->connection->connect();
		
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

    /**
     * Update todo list and items
     *
     * @param array $data An array of todo and items
     */
    public function updateTodo($data)
    {
        // TODO : Implements try/catch block		
        $openConnection = $this->connection->connect();

        foreach($data as $todo) {

            $currentTime = time();
            $stmt = $openConnection->prepare("UPDATE todo_list SET name=:name, date_update=:date_update WHERE id=:id");

            $stmt->bindParam(':name', $todo['name']);
            $stmt->bindParam(':date_update', $currentTime);
            $stmt->bindParam(':id', $todo['id']);

            $stmt->execute();

            if(is_array($todo['items'])) {
                foreach($todo['items'] as $item) {

                    $stmt = $openConnection->prepare("UPDATE todo_items SET description=:description, is_read=:is_read, is_done=:is_done WHERE id=:id");
					
                    $stmt->bindParam(':id', $item['id']);
                    $stmt->bindParam(':is_read', $item['is_read']);
                    $stmt->bindParam(':is_done', $item['is_done']);
                    $stmt->bindParam(':description', $item['description']);
                    $stmt->execute();
                }
            }	
        }
		
        $this->connection->close();	
    }

    /**
     * Update todo list and items
     *
     * @param array $data An array of todo ids
     */
    public function deleteTodo($data)
    {
        // TODO : Implements try/catch block		
        $openConnection 	= $this->connection->connect();
        $todoIdsToDelete 	= array_map('intval', $data);

        $stmt = $openConnection->prepare("DELETE FROM todo_list WHERE id IN (".implode(",",$todoIdsToDelete).")");	
        $stmt->execute();

        $this->connection->connect();
    }
}
?>
