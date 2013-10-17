<?php
require_once "HTTP/Request.php";
require_once dirname(__FILE__) . "/Common/DBConnection.php";
require_once dirname(__FILE__) . "/Common/Logger.php";
require_once dirname(__FILE__) . "/Common/Pager.php";
require_once dirname(__FILE__) . "/DB/MeowtubeDBAccessMgr.php";

define("VIDEOS_PER_PAGE", 5);

    $dbm = new MeowtubeDBAccessMgr();

    // 動画のデータ取得
    $blog_post_lr = array("blog_post left", "blog_post right");

    if( isset($_GET['page']) and preg_match('/^[1-9][0-9]*$/', $_GET['page']) )
    {
        $page = (int)$_GET['page'];
    }
    else
    {
        $page = 1;
    }

    $offset = VIDEOS_PER_PAGE * ($page - 1);
    $result = $dbm->GetYoutube( "getdate DESC", $offset, VIDEOS_PER_PAGE);

    // アクセストップ5の猫動画データ取得
    $top_result = $dbm->GetAccessYoutube("access.count DESC", 0, 5);

    // ページングのリンクデータ取得
    $count = $dbm->GetYoutubeCount();
    $page_navi = new Pager('page', VIDEOS_PER_PAGE, $count);

?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="description" content="今日も１日１猫動画。このサイトは毎日猫に関する動画を紹介します。" />
  <meta name="keywords" content="猫,cat,猫動画,meow,mew" />
  <meta name="author" content="MeowTube">
  <meta name="copyright" content="Copyright &copy; MeowTube">
  <meta name="robots" content="index,follow" />

  <title>MeowTube</title>
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
  <script type="text/javascript" src="js/pagetop.js"></script>
  <script type="text/javascript" src="js/tipsy.js"></script>
  <script type="text/javascript" src="js/slider.min.js"></script>
  <script type="text/javascript" src="js/pull.out.js"></script>
  <link rel="stylesheet" type="text/css" href="css/styles.css"/>
  <link rel="sshortcut icon" href="favicon.ico"/>
  <link rel="home" href="http://www.meowtube.net" title="meowtube" />
  <?php include 'analyticsTracking.php';?>
</head>
<body id="back">


<header>
    <?php include './header.php';?>
</header>


<section class="keyvisual">
  <div id="slider">
    <img class="image" src="img/key/visual01.jpg" alt="イメージ" />
    <img class="image" src="img/key/visual02.jpg" alt="イメージ" />
    <img class="image" src="img/key/visual03.jpg" alt="イメージ" />
    <img class="image" src="img/key/visual04.jpg" alt="イメージ" />
    <img class="image" src="img/key/visual05.jpg" alt="イメージ" />
    <img class="image" src="img/key/visual06.jpg" alt="イメージ" />
  </div>
  <hgroup>
    <h1><img src="img/common/logo.png" width="80" alt="sample logo" /></h1>
    <h2>今日も１日１猫動画。このサイトは毎日猫に関する動画を紹介します。</h2>
  </hgroup>
</section>


<section class="social">
    <?php include 'SocialButton.php';?>
</section>


<div class="wrapper">

    <section class="blog_post_new">
        <!-- 画像（右上） -->
        <a href="http://px.a8.net/svt/ejp?a8mat=25ZXNC+EJXH4I+2T0E+6AJV5" target="_blank">
        <img border="0" width="200" height="200" alt="" src="http://www22.a8.net/svt/bgt?aid=131007000880&wid=001&eno=01&mid=s00000013091001057000&mc=1"></a>
        <img border="0" width="1" height="1" src="http://www17.a8.net/0.gif?a8mat=25ZXNC+EJXH4I+2T0E+6AJV5" alt="">

        <!-- カレンダー -->
        <div class="calendar">
            <img src="img/common/archives.png"/>
            <?php include './Common/Calendar.php'; ?>
        </div>
        <br>

        <!-- 人気５件 -->
        <img src="img/common/popular_videos.png"/>
        <?php foreach($top_result as $top):?>
        <div class="item">
        <time datetime="<?=$top['getdate'];?>"><?=date('Y年m月d日', strtotime($top['getdate']));?></time>
        <p><a href="?id=<?=$top['id'];?>"><?=mb_strimwidth($top['title'], 0, 20, "…", "UTF-8");?></a></p>
        <a href="./TodaysCat.php?id=<?=$top['id'];?>"><img src="<?=$top['thumbnail'];?>" alt="thumbnail" width="180"/></a>
        </div>
        <?php endforeach;?>

        <!-- 画像（右下） -->
        <div style="margin: 10px 0px auto">
        <a href="http://px.a8.net/svt/ejp?a8mat=25ZXNC+EGYB3M+CHG+TWLPT" target="_blank">
        <img border="0" width="160" height="600" alt="" src="http://www22.a8.net/svt/bgt?aid=131007000875&wid=001&eno=01&mid=s00000001618005023000&mc=1"></a>
        <img border="0" width="1" height="1" src="http://www19.a8.net/0.gif?a8mat=25ZXNC+EGYB3M+CHG+TWLPT" alt="">
        </div>
    </section>

    <?php for( $i=0; $i<count($result); $i++) :?>
    <section class="blog_post_one">
        <time datetime="<?=$result[$i]['getdate'];?>"><?=date('Y年m月d日', strtotime($result[$i]['getdate']));?></time>
        <h2><a href="./TodaysCat.php?id=<?=$result[$i]['id'];?>"><?=$result[$i]['title'];?></a></h2>
        <a href="./TodaysCat.php?id=<?=$result[$i]['id'];?>"><img src="<?=$result[$i]['thumbnail'];?>" alt="thumbnail" width="640" height="360"/></a>
        <!--
        <p><?=mb_strimwidth($result[$i]['description'], 0, 150, "…", "UTF-8");?></p>
        -->
        <a href="./TodaysCat.php?id=<?=$result[$i]['id'];?>" class="text_btn">View Video</a>
    </section>
    <?php endfor; ?>

    <!-- 画像（リスト下段） -->
    <section class="affiliate_2">
    </section>

</div>

<div class="pagenavi">
    <?=$page_navi->getPager();?>
</div>


<footer>
    <?php include './footer.php';?>
</footer>

</body>
</html>