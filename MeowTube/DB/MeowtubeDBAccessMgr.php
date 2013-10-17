<?php
require_once dirname(__FILE__) . "/../Common/DBConnection.php";
require_once dirname(__FILE__) . "/../Common/Logger.php";

class MeowtubeDBAccessMgr 
{
    private $dbh;

    public function __construct()
    {
        $this->dbh = DBConnection::get()->handle();
    }

	/**
	 * @param $feed YoutubeAPIから返却された検索結果XML
	 * @return 登録した件数
	 */
	public function StoreYoutubeEntry( $feed, $max_count )
	{
		$count = 0;
		foreach ($feed->entry as $entry)
		{
			// *** 取得したXMLデータから必要な項目を抜き出す ***
			$media = $entry->children('http://search.yahoo.com/mrss/');

			// ID
			$id = end( explode('/',$entry->id) );
			// タイトル
			$title = $entry->title;
			// 作者
			$credit = $entry->author->name;
			// 動画URL
			$player = $media->group->content->attributes()->url;
//			$player = $media->group->player->attributes()->url;
			// サムネイルのURL
			$attrs = $media->group->thumbnail[0]->attributes();
	        $thumbnail = $attrs['url'];
			// 概要説明
			$description = $media->group->description;
			// 日付
			$getdate = date('Y-m-d');
			// 取得したXML文字列（デバッグ用）
			$feed_str = $feed->asXML();

			Logger::info("id($id), title($title), credit($credit), player($player), thumnail($thumbnail), description($description), date($getdate)");

			if( $this->StoreYoutube( $id, $title, $credit, $player, $thumbnail, $description, $getdate, $feed_str ) )
			{
				$count++;
				if( $count === $max_count )
				{
					Logger::info("break($count)");
					break;
				}
			}
		}

		return $count;
	}

	/**
	 * youtubeテーブルへのINSERT
	 * @return 登録成功／失敗
	 */
	public function StoreYoutube( $id, $title, $credit, $player, $thumbnail, $description, $getdate, $feed_str )
	{
		try
		{
			$sql = 'INSERT INTO youtube '
					. '(id, title, credit, player, thumbnail, description, getdate, feed) '
					. 'VALUES (:id, :title, :credit, :player, :thumbnail, :description, :getdate, :feed) ';
			$stmt = $this->dbh->prepare( $sql );
			$stmt->bindParam(':id', $id);
			$stmt->bindParam(':title', $title);
			$stmt->bindParam(':credit', $credit);
			$stmt->bindParam(':player', $player);
			$stmt->bindParam(':thumbnail', $thumbnail);
			$stmt->bindParam(':description', $description);
			$stmt->bindParam(':getdate', $getdate);
			$stmt->bindParam(':feed', $feed_str);

			if( $stmt->execute() )
			{
				Logger::info("youtubeテーブルへのINSERT成功");
				return true;
			}
			else {
				Logger::info("youtubeテーブルへのINSERT失敗");
				return false;
			}
		}
		catch( PDOException $e )
		{
			var_dump($e->getMessage());
		}
	}

	/**
	 * youtubeテーブルのデータ取得
	 * @return 取得結果の配列（FETCH_ASSOC)
	 */
	public function GetYoutube( $orderby, $offset, $row_count )
	{
	    Logger::info("GetYoutube START");

	    $sql = 'SELECT * FROM youtube ';
	    if($orderby) $sql .= 'ORDER BY ' . $orderby;
	    $sql .= ' LIMIT :offset, :row_count ';

	    try
	    {
	        $stmt = $this->dbh->prepare( $sql );
	        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
	        $stmt->bindValue(':row_count', $row_count, PDO::PARAM_INT);
	        $stmt->execute();
	        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    	    Logger::info("GetYoutube END");
	        return $result;
	    }
	    catch( PDOException $e )
	    {
	        echo 'エラーが発生しました。管理者にご連絡ください';
	        Logger::error($e->getMessage());
	    }
	}

	/**
	 * youtubeテーブルのデータ取得(id指定)
	 * @return 取得結果の配列（FETCH_ASSOC)
	 */
	public function GetYoutubeById( $id )
	{
	    $sql = 'SELECT * FROM youtube WHERE id = :id ';

	    try
	    {
	        $stmt = $this->dbh->prepare( $sql );
	        $stmt->bindValue(':id', $id);
	        $stmt->execute();
	        $result = $stmt->fetch(PDO::FETCH_ASSOC);

	        return $result;
	    }
	    catch( PDOException $e )
	    {
	        echo 'エラーが発生しました。管理者にご連絡ください';
	        Logger::error($e->getMessage());
	    }
	}

	/**
	 * youtubeテーブルのデータ件数取得
	 * @return 件数 
	 */
	public function GetYoutubeCount()
	{
	    $sql = 'SELECT COUNT(*) AS count FROM youtube';

	    try
	    {
    	    $stmt = $this->dbh->query( $sql );
	        $result = $stmt->fetch();

    	    return $result['count'];
	    }
	    catch( PDOException $e )
	    {
	        echo 'エラーが発生しました。管理者にご連絡ください';
	        Logger::error($e->getMessage());
	    }
	}

	/**
	 * youtubeテーブルのデータ取得
	 * @return 取得結果の配列（FETCH_ASSOC)
	 */
	public function GetAccessYoutube($orderby, $offset, $row_count)
	{
	    $sql = 'SELECT * FROM youtube '
	          . 'INNER JOIN access ON youtube.id = access.id ';
	    if($orderby) $sql .= 'ORDER BY ' . $orderby;
	    $sql .= ' LIMIT :row_count OFFSET :offset ';

    	try
	    {
	        $stmt = $this->dbh->prepare( $sql );
	        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
	        $stmt->bindValue(':row_count', $row_count, PDO::PARAM_INT);
    	    $stmt->execute();
	        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

	        return $result;
	    }
    	catch( PDOException $e )
	    {
	        echo "エラーが発生しました。管理者にご連絡ください";
	        Logger::error($e->getMessage());
	    }
	}


	/**
	 * accessテーブルのカウントアップ	 */
	public function CountUpAccess( $id )
	{
	   $insert_sql = 'INSERT INTO access '
	                . '( id, count, last_access ) '
	                . 'VALUES ( :id , 1, now() )'
	                . 'on duplicate key update id=:id, count=count+1, last_access=now()';

    	try
	    {
	        $insert_stmt = $this->dbh->prepare( $insert_sql );
	        $insert_stmt->bindValue(':id', $id, PDO::PARAM_INT);
	        $insert_stmt->execute();
	    }
    	catch( PDOException $e )
	    {
	        echo "エラーが発生しました。管理者にご連絡ください";
	        Logger::error($e->getMessage());
	    }
	}
}

?>