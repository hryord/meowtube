<?php
require_once dirname(__FILE__) . '/../Common/Logger.php';
require_once dirname(__FILE__) . '/../Common/Constant.php';

class DBConnection
{
    private $_handle = null;

	// ----------------------------------------------------------------
	// Summary:コンストラクタ
	//         PDOのインスタンスを生成する
    private function __construct()
    {
	    try
	    {
	        $this->_handle = & new PDO(MYSQL_CONNECT, DB_USER, DB_PASSWORD);
	        $this->_handle->query("SET NAMES utf8");
	    }
		catch( PDOException $e )
		{
    	    var_dump($e->getMessage());
    	}
	}

    public static function get()
    {
        static $db = null;
        if( $db == null )
		{
			 $db = new DBConnection();
		}
        return $db;
    }

    public function handle()
	{
        return $this->_handle;
    }
}
?>