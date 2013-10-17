<?php

	require_once "HTTP/Request.php";
	require_once dirname(__FILE__) . "/../Common/DBConnection.php";
	require_once dirname(__FILE__) . "/../Common/Logger.php";
	require_once dirname(__FILE__) . "/../Util/YoutubeHandler.php";
	require_once dirname(__FILE__) . "/../DB/MeowtubeDBAccessMgr.php";

	Logger::info("YoutubeVideo START");

	$youtube = new YoutubeHandler();

	$youtube->keyword     = "cat || 猫";
	$youtube->max_results = 50;
	$youtube->orderby     = "rating";
//	$youtube->orderby     = "relevance_lang_ja";
	$youtube->restriction = "JP";
	$youtube->time        = "this_month";
	$youtube->category    = "Animals";
	$youtube->format      = 5;
	$feed = $youtube->SearchYoutube();

	$dba = new MeowtubeDBAccessMgr();
	$dba->StoreYoutubeEntry( $feed, 1 );

	Logger::info("YoutubeVideo End");
?>