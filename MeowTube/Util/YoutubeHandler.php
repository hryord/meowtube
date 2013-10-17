<?php
require_once "HTTP/Request.php";
require_once dirname(__FILE__) . "/../Common/DBConnection.php";
require_once dirname(__FILE__) . "/../Common/Logger.php";

/**
 * Youtubeの動画をWEBAPIから取得するクラス
 */
class YoutubeHandler
{
	const URL = "http://gdata.youtube.com/feeds/api/videos?";
	
	public $keyword;
	public $max_results;
	public $order_by;      // relevance:関連性, published、viewCount、rating:評価 
	public $restriction;   // JP:日本
	public $time;          // today（1 日）、this_week（7 日）、this_month（1 か月）、all_time（すべての期間）
	public $category;      // Music, Animals, Sports, Film, Autos, Travel, Games, Comedy, People, News, Entertainment, Education, Howto, Tech
	public $format;        // 1:モバイルH.263+AMR音声, 5:埋込み可能, 6:モバイルMPEG-4 SP+AAC
	  
	public function __construct() {}
/*
	private function CreateQuery( $keyword, $max_result, $orderby, $restriction, $time, $option )
	{
		// 検索クエリの作成
		$query   = $this->url
					. "vq=" . urldecode($keyword)          // 検索キーワード
					. "&max-results=" . $max_result        // 検索結果最大数
					. "&orderby=" . $orderby               // 並べ替え順
					. "&restriction=" . $restriction       // 再生可能な国(etc. JP)
					. "&time=" . $time                     // アップロードされた動画の時間範囲
					. $option;
	
		Logger::info($query);
		return $query;
	}
*/	
	
	/**
	 * 検索クエリ生成
	 * @return 検索クエリ文字列
	 */
	private function CreateQuery()
	{
		$param_ary = array( array("vq", urlencode($this->keyword)),
							array("max-results", $this->max_results),
							array("orderby", $this->orderby),
							array("restriction", $this->restriction),
							array("time", $this->time),
							array("category", $this->category),
							array("format", $this->format)
						  );
		return $this->CreateQueryArray( $param_ary );
	}

	/**
	 * 検索クエリ生成
	 * @param $param_ary 検索パラメータy
	 * @return 検索クエリ文字列
	 */
	private function CreateQueryArray( $param_ary )
	{
		$str = $this::URL;
		for( $i=0; $i<count($param_ary); $i++ )
		{
			$str .= ("&" . $param_ary[$i][0] . "=" . $param_ary[$i][1]);
		}
		
		Logger::info($str);
		return $str;
	}

	/*
	 * 検索実行
	 * @return 検索結果（XML）
	 */
	public function SearchYoutube()
	{
		return simplexml_load_file( $this->CreateQuery() );
	}
}
?>