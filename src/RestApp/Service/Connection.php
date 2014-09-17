<?php
namespace RestApp\Service;

interface Connection
{
	function connect();

	function close();
}

?>
