  <ul>
  	<!-- twitter -->
  	<li><a href="https://twitter.com/share" class="twitter-share-button" data-lang="en" data-count="none">Tweet</a>
		<script>!function(d,s,id){
					var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';
					if(!d.getElementById(id)){js=d.createElement(s);
						js.id=id;js.src=p+'://platform.twitter.com/widgets.js';
					fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');
		</script>
	</li>
	<!-- facebook -->
	<li>
        <div id="fb-root"></div>
        <script>
        (function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
            fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));</script>
            <div class="fb-like" data-href="http://<?=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];?>"
                 data-width="The pixel width of the plugin" data-height="The pixel height of the plugin"
                 data-colorscheme="light" data-layout="button_count" data-action="like" data-show-faces="false" data-send="false"></div>

	</li>
	<!-- hatena bookmark -->
	<li>
        <a href="http://b.hatena.ne.jp/entry/<?='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];?>"
           class="hatena-bookmark-button" data-hatena-bookmark-layout="standard-noballoon" data-hatena-bookmark-lang="en"
           title="このエントリーをはてなブックマークに追加">
        <img src="http://b.st-hatena.com/images/entry-button/button-only@2x.png" alt="このエントリーをはてなブックマークに追加"
             width="20" height="20" style="border: none;" /></a>
        <script type="text/javascript" src="http://b.st-hatena.com/js/bookmark_button.js" charset="utf-8" async="async"></script>
	</li>
  </ul>