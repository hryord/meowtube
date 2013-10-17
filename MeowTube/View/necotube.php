<?php

	include_once "./DBConnection.php";
	include_once "./Logger.php";

	$db = DBConnection::get()->handle();

	$sql = 'SELECT id FROM youtube';
	try
	{
		$stmt = $db->prepare( $sql );
		$stmt->execute();
    	while( $result = $stmt->fetch(PDO::FETCH_ASSOC) )
    	{
        	print($result['id'].'<br>');
    	}
	}
	catch( PDOException $e )
	{
		var_dump($e->getMessage());
	}
	
?>