/**
 * Summary : javascript for necovideo site
 * Author  : Ayumi Tagawa
 */
//-------------------------------------------------------------------------
// Function: search()
// Summary : GoogleAPIに検索を依頼する
// Return  :
// Args    :
function search()
{
	console.log("test3");
	// (1) 表示領域を初期化します。
	var videos = document.getElementById("videos");
	videos.innerHTML = "Loading...";

	// (2) 入力されたキーワードを取得し，クエリを生成します。
	var keyword = encodeURIComponent("cat");
	var query = "http://gdata.youtube.com/feeds/api/videos?"
	          + "vq=" + keyword
	          + "&max-results=10"
	          + "&alt=json-in-script"
	          + "&callback=view";
	          

	// (3) script要素を生成します。
	var script = document.createElement("script");
	script.type = "text/javascript";
	script.src = query;

	// (4) script要素を追加し，リクエストします。
	videos.appendChild(script);
};

//-------------------------------------------------------------------------
// Function: view(data)
// Summary : GoogleAPIに検索を依頼する
// Return  :
// Args    : data - 
function view(data)
{
	// (1) 表示領域を初期化します。
	var videos = document.getElementById("videos");
	videos.innerHTML = '';

	// (2) 検索結果のエントリのサムネイル画像に，プレーヤのページへリンクを張った要素を生成し，表示領域に追加します。
	var es = data.feed.entry;
	for (var i=0; i < es.length; i++) 
	{
		var group = es[i].media$group;

		// (3) リンク要素の生成
		var a = document.createElement("a");
		a.href = group.media$player[0].url;

		// サムネイル画像要素の生成
		var img = document.createElement("img");
		img.src = group.media$thumbnail[0].url;
		
		// タイトルの生成
		var label = document.createElement("label");
		label.appendChild(document.createTextNode(group.media$title.$t));

		// (4) 表示領域に追加
		videos.appendChild(label);
		videos.appendChild(document.createElement('br'));
		a.appendChild(img);
		videos.appendChild(a);
		videos.appendChild(document.createElement('br'));
	}
};

