<?php

	require_once "HTTP/Request.php";
	require_once dirname(__FILE__) . "/../Common/DBConnection.php";
	require_once dirname(__FILE__) . "/../Common/Logger.php";
	require_once dirname(__FILE__) . "/../Common/YoutubeHandler.php";
	require_once dirname(__FILE__) . "/../DB/NecotubeDBAccessMgr.php";

	Logger::info("get_todayscat.php START");

	$dbm = new MeowtubeDBAccessMgr();
	$result = $dbm->GetAccessYoutube("getdate DESC", 0, 1);

	if( $result )
	{
    	$youtube_array = array(
	        'id'          => $result[0]['id'],
	        'title'       => $result[0]['title'],
    	    'credit'      => $result[0]['credit'],
	        'player'      => $result[0]['player'],
	        'thumbnail'   => $result[0]['thumbnail'],
    	    'description' => $result[0]['description'],
	        'getdate'     => $result[0]['getdate']
	     );

    	$json_value = json_encode( $youtube_array );
    	header( 'Content-Type: text/javascript; charset=utf-8' );
    	echo $json_value;
	}


	Logger::info("get_todayscat.php END");
?>