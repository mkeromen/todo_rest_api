<?php
namespace RestApp\Service;

class PDOConnection implements Connection
{
	
	private $dns;

	private $user;

	private $password;

	private $connection = null;


	public function __construct(\Silex\Application $app)
	{	
		$this->dns 			= sprintf('mysql:host=%s;dbname=%s', $app['db.host'], $app['db.name']);
		$this->user 		= $app['db.user'];
		$this->password 	= $app['db.password'];	
	}

	public function connect()
	{	
		try {
			$this->connection = new \PDO($this->dns, $this->user, $this->password);
			return $this->connection;
		} catch(\Exception $e) {
			throw new \Exception(e);
		}	
	}

	public function close()
	{
		$this->connection = null;
	}
}
?>
