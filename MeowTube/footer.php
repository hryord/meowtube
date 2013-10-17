<?php
require_once "HTTP/Request.php";
require_once dirname(__FILE__) . "/Common/DBConnection.php";
require_once dirname(__FILE__) . "/Common/Logger.php";

	$db = DBConnection::get()->handle();

	// アクセストップ5の猫動画データ取得
	$sql = 'SELECT youtube.id, thumbnail FROM youtube '
			. 'INNER JOIN access ON youtube.id = access.id '
			. 'ORDER BY access.count DESC '
			. 'LIMIT 5';

	try
	{
		$stmt = $db->prepare( $sql );
		$stmt->execute();
    	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	catch( PDOException $e )
	{
		echo "エラーが発生しました。管理者にご連絡ください";
		Logger::error($e->getMessage());
	}
?>
<section class="footer_top" id="footer">
  <div class="footer_top_in">

    <form name="searchform" id="searchform" method="get" action="http://www.google.co.jp/search">
    	<input name="q" id="keywords" value="" type="text" />
		<input type="hidden" name="hl" value="ja">
		<input type="hidden" name="as_sitesearch" value="<?="http://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];?>">
      <input id="searchbtn" type="image" src="img/common/btn_search.gif" alt="検索" />
	</form>

    <p class="copyright">Copyright © 2013 MeowTube.</p>
  </div>
</section>


<section class="footer_bottom">
  <div class="footer_bottom_in">
    <div class="left">
      <div class="title">ABOUT</div>
      <p><img src="img/common/img_about.jpg" onmouseover="this.src='img/common/img_about2.jpg'" onmouseout="this.src='img/common/img_about.jpg'" alt="corona" width="250"/></p>
      <p>このサイトは私「コロナ」が運営しています</p>
    </div>


    <div class="center">
      <div class="title">Advertise</div>
        <a href="http://px.a8.net/svt/ejp?a8mat=25ZXNE+5R6VAQ+2DUU+62MDD" target="_blank">
        <img border="0" width="350" height="160" alt="" src="http://www23.a8.net/svt/bgt?aid=131007002348&wid=001&eno=01&mid=s00000011127001020000&mc=1"></a>
        <img border="0" width="1" height="1" src="http://www10.a8.net/0.gif?a8mat=25ZXNE+5R6VAQ+2DUU+62MDD" alt="">
    </div>


    <div class="right">
      <div class="title">LIKE SITE</div>

        <div class="img_box"><a class="north" href="http://www.youtube.co.jp/" title="Youtube"><img alt="Youtube" src="img/site/youtube.jpg" /></a></div>
        <div class="img_box"><a class="north" href="http://www.nekobiyori.jp/" title="猫びより"><img alt="猫びより" src="http://www.nekobiyori.jp/nekobiyori/60/header1.jpg" /></a></div>
        <!--
        <div class="img_box lasted"><a class="north" href="URL" title="サイト名"><img alt="サイト名" src="img/site/site_sample.jpg" /></a></div>
        <div class="img_box"><a class="north" href="URL" title="サイト名"><img alt="サイト名" src="img/site/site_sample.jpg" /></a></div>
        <div class="img_box"><a class="north" href="URL" title="サイト名"><img alt="サイト名" src="img/site/site_sample.jpg" /></a></div>
        -->
    </div>
  </div>
</section>

<section class="footer_design">
  <div class="footer_design_in">
    <!--↓この囲いは消さないでください。消したい場合はご連絡ください。↓-->
    <a class="north" href="http://creators-manual.com/" title="Webクリエイターズマニュアル"><img src="img/common/design.png" alt="Webクリエイターズマニュアル" /></a>
    <!--↑この囲いは消さないでください。消したい場合はご連絡ください。↑-->
  </div>
</section>

<!--
<section id="interview">
    <div class="label">
	    <div class="title">
            <div class="text">Popular videos TOP5</div>
            <div class="close"><img title="Close" alt="Close" src="img/common/close.gif" /></div>
        </div>
    </div>


	<div class="wrapper">
		<?php foreach($result as $thumbnail):?>
	    <div class="item">
			<a href="./TodaysCat.php?id=<?=$thumbnail['id'];?>"><img src="<?=$thumbnail['thumbnail'];?>" alt="thumbnail" /></a>
		</div>
		<?php endforeach;?>    </div>

</section>
-->


<div id="page-top" class="page_back"><a href="#back"><img src="img/common/btn_pagetop.png" alt="page-top" /></a></div>