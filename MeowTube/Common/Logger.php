<?php
//----------------------------------------------------------------------
// Summary: ログ出力クラス
class Logger
{
	private static $outputDir = "C:\Users\ayumi\Documents\php.log";

	//------------------------------------------------------------------
	// Summary :コンストラクタ
	/*
	public function __construct( $output_dir )
	{
		if( $output_dir )
		{
			$outputDir = $output_dir;
		}
	}
	*/
	
	public static function info( $msg )
	{
		Logger::outputLog( "INFO ", $msg );
	}
	
	public static function error( $msg )
	{
		Logger::outputLog( "ERROR", $msg );
	}
	
	public static function setOutputDir( $output_dir )
	{
		Logger::$outputDir = $output_dir;
	}
	
	//------------------------------------------------------------------
	// Summary :ログ出力
	private static function outputLog( $level, $msg )
	{
		$datetime    = date( "Y/m/d (D) H:i:s", time() );
		$client_ip   = $_SERVER["REMOTE_ADDR"];
		$request_url = $_SERVER["REQUEST_URI"];
		$out         = "[{$datetime}][$level][client {$client_ip}][url {$request_url}]{$msg}";
		error_log( $out."\n", 3, Logger::$outputDir );
	}
}
//----------------------------------------------------------------------

?>