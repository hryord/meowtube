/**
 * @Summary :
 */

/**
 * メイン処理
 */
var main = function()
{
	//	ここにメイン処理を記述する
	console.log("test2");
	var youtubeClient = require('./YoutubeClient.js');
	youtubeClient.search();
};

/**
 * mainファンクションを呼び出す
 */
if(require.main === module)
{
	try 
	{
		main();
	}
	catch(error)
	{
		var sys = require('sys');
		sys.print(error);
	}
}