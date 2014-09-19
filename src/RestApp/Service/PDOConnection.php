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

	/**
     * Connect to the database
     *
	 * @param \PDO $this->connection PDO resource connexion
     */
	public function connect()
	{	
		try {
			$this->connection = new \PDO($this->dns, $this->user, $this->password);
			return $this->connection;
		} catch(\Exception $e) {
			throw new \Exception(e);
		}	
	}

	/**
     * Close connexion to the database
     */
	public function close()
	{
		$this->connection = null;
	}
}
?>
