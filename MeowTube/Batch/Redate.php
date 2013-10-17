<?php

	require_once "HTTP/Request.php";
	require_once dirname(__FILE__) . "/../Common/DBConnection.php";
	require_once dirname(__FILE__) . "/../Common/Logger.php";
	require_once dirname(__FILE__) . "/../Util/YoutubeHandler.php";
	require_once dirname(__FILE__) . "/../DB/NecotubeDBAccessMgr.php";

	$db = DBConnection::get()->handle();

	$select_sql = 'SELECT id FROM youtube';
	$stmt = $db->query($select_sql);
	$ids = $stmt->fetchAll(PDO::FETCH_COLUMN);

	$update_sql =    'UPDATE youtube '
					.'SET getdate = date_sub(curdate(), interval :day day) '
					.'WHERE id = :id';

	for( $i=0; $i<count($ids); $i++ )
	{
		$stmt = $db->prepare( $update_sql );
		$stmt->bindValue(':day', $i      , PDO::PARAM_INT);
		$stmt->bindValue(':id' , $ids[$i], PDO::PARAM_INT);
		$stmt->execute();
	}
?>