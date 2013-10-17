<?php
require_once "HTTP/Request.php";
require_once dirname(__FILE__) . "/Common/DBConnection.php";
require_once dirname(__FILE__) . "/Common/Logger.php";
require_once dirname(__FILE__) . "/DB/MeowtubeDBAccessMgr.php";

    $dbm = new MeowtubeDBAccessMgr();

	if( isset($_GET['id']) )
	{
    	// アクセスカウントの更新
	    $dbm->CountUpAccess($_GET['id']);

		// 今日の猫動画取得
		$result = $dbm->GetYoutubeById($_GET['id']);
	}

	// 最新３件の猫動画取得
	$new_results = $dbm->GetYoutube("getdate DESC", 0, 3);

?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="description" content="description">
  <meta name="keywords" content="猫,cat,猫動画,meow,mew" />
  <title>Today's Cat</title>
  <!--[if lt IE 9]><script type="text/javascript" src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
  <script type="text/javascript" src="js/pagetop.js"></script>
  <script type="text/javascript" src="js/tipsy.js"></script>
  <script type="text/javascript" src="js/slider.min.js"></script>
  <script type="text/javascript" src="js/pull.out.js"></script>
  <link rel="stylesheet" type="text/css" href="css/styles.css"/>
  <?php include 'analyticsTracking.php';?>
</head>
<body id="back">


<header>
	<?php include './header.php';?>
</header>



<section class="social">
	<?php include "SocialButton.php"?>
</section>


<div class="wrapper">

	<section class="blog_post_one_title">
		<p><img src="img/common/todayscat.png"/></p>
	</section>
	<section class="blog_post_new_title">
		<img src="img/common/recentcats.png" />
	</section>

	<section class="blog_post_one">
		<time datetime="<?=$result['getdate'];?>"><?=date('Y年m月d日', strtotime($result['getdate']));?></time>
		<h2><?=$result['title'];?></h2>
		<iframe src="<?="http://www.youtube.com/embed/" . $result['id'] . "?&rel=0&theme=light&showinfo=0&autohide=1&color=white";?>" width="640" height="360" ></iframe>
		<div class="example-twitter"><p><?=$result['description'];?></p></div>
		<!-- Youtubeの投稿コメントを入れる -->
	</section>

	<section class="blog_post_new">
		<?php foreach ($new_results as $new_result) : ?>
    	<time datetime="<?=$new_result['getdate'];?>"><?=date('Y年m月d日', strtotime($new_result['getdate']));?></time>
		<h2><a href="?id=<?=$new_result['id'];?>"><?=mb_strimwidth($new_result['title'], 0, 20, '…', 'UTF-8');?></a></h2>
		<a href="?id=<?=$new_result['id'];?>"><img src="<?=$new_result['thumbnail'];?>" alt="thumbnail" width="160"/></a>
    	<?php endforeach;?>
    </section>

    <section class="blog_post_one_title">
        <a href="#" onClick="history.back(); return false;" class="text_btn" style="margin: 0 auto;">Back</a>
    </section>

    <div style="margin-top: 700px">
    <script type="text/javascript">var a8='a13100744030_25ZXNC_1ELV76_249K_BUB81';var rankParam='aeWyEHF5U2NSpQlJEHWBkYNRvYm1F_jJzKWcEkFSiYlEiQk1RcknCnb4Ack4FlFSzK4JCeWK.YlXzYwGJYbUCnk1Wnh4.nblR';var trackingParam='Oi7_qZSUA6oi_TDPqGJPqToTA82VPktQWkJ3Xkjxx';var bannerType='1';var bannerKind='item.variable.kind2';var vertical='1';var horizontal='4';var alignment='1';var frame='1';var ranking='1';var category='ペット用品';</script><script type="text/javascript" src="http://amz-ad.a8.net/amazon/amazon_ranking.js"></script>
    </div>
</div>


<footer>
	<?php include './footer.php';?>
</footer>

</body>
</html>