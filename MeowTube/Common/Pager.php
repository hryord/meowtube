<?php
define("MAX_DISP", "10");

class Pager 
{
    var $query;             // ページを示すキー
    var $rowCount;          // 1ページあたりの件数
    var $max;               // 総件数
    private $get = array(); // 引数の配列
    private $page;          // 現在のページ番号
    private $href;          // 引数なしのurl部分
    private $pageCount;     // 総ページ数
 
    /*--------------------------------------------------
    *
    * コンストラクタ
    * @param    ページを示すキー    : string
    *           1ページあたりの件数  : int
    *           総件数             : int
    *
    --------------------------------------------------*/
    public function __construct( $query, $rowCount, $max ) 
    {
        $this->query = $query;
        $this->rowCount = $rowCount;
        $this->max = $max;
 
        // URLとクエリを設定
        $this->setRequestUri();
 
        $this->page = ( isset( $this->get[$query] ) ) ? $this->get[$query] : '1';
        $this->pageCount = ceil( $this->max / $this->rowCount );
    }
 
    /*--------------------------------------------------
    *
    * URLとクエリを取得、設定
    *
    --------------------------------------------------*/
    protected function setRequestUri () 
    {
        $requestUri = explode( '?', $_SERVER['REQUEST_URI'] );
        $this->href = $requestUri[0];
        $this->get = $_GET;
    }
 
    /*--------------------------------------------------
    *
    * ページャhtml生成
    * @param    array(
    *               'size' => 表示する数字の個数, : int
    *               'firstMark' => 最初の記号,        : string
    *               'prevMark' => 前の記号,          : string
    *               'nextMark' => 次の記号,          : strng
    *               'lastMark' => 最後の記号 )        : string
    * @return   html : string
    *
    --------------------------------------------------*/
    public function getPager( $_arr = array() ) 
    {
        $_ret = '';
        if ( $this->rowCount ) 
        {
            // 引数の初期値
            $default = array(
                'size' => ($this->pageCount < MAX_DISP) ? $this->pageCount : MAX_DISP,
                'firstMark' => '<<',
                'prevMark' => '<',
                'nextMark' => '>',
                'lastMark' => '>>',
            );
            // パラメータを結合し決定
            $_arr = array_merge( $default, $_arr );
            $diff = floor( $_arr['size'] / 2 );

            // 1ページしか無いときは出さない
            if ( $this->pageCount > 1 )
			{
                // 最初のページ、前のページ
                if ( $this->page > 1 ) 
                {
                    // 最初のページ
                    $_ret .= '<span class="prev">'.$this->getLink ( 1, $_arr['firstMark'] )."</span>";
                    // 前のページ
                    $_ret .= '<span class="prev">'.$this->getLink ( $this->page - 1, $_arr['prevMark'] )."</span>";
                }

                // 繰り返し部分
                if ( $this->page + $diff > $this->pageCount ) 
                {
                    $start = $this->pageCount - $_arr['size'] + 1;
                    $start = ( $start < 0 ) ? 1 : $start;
                    $end = $this->pageCount;
                }
				elseif ( $this->page <= $diff ) 
				{
                    $start = 1;
                    $end = $start + $_arr['size'] - 1;
                } 
                else 
                {
                    $start = $this->page - $diff;
                    $end = $start + $_arr['size'] - 1;
                }

                for ( $i = $start; $i <= $end; $i ++ ) 
                {
                    if ( $this->page == $i ) 
                    {
                        $_ret .= '<span class="current">'.$i.'</span> ';
                    } 
                    else 
                    {
                        $_ret .= '<span class="numbers">'.$this->getLink ( $i, $i ).'</span>';
                    }
                }

                // 次のページ、最後のページ
                if( $this->page < $this->pageCount )
                {
                    // 次のページ
                    $_ret .= '<span class="next">'.$this->getLink ( $this->page + 1, $_arr['nextMark'] ).'</span>';
                    // 最後のページ
                    $_ret .= '<span class="next">'.$this->getLink ( $this->pageCount, $_arr['lastMark'] ).'</span>';
                }
                // 出力タグ
                $_ret = '
                    <p>
                        '.$_ret.'
                    </p>
                ';
            }
        }
        return $_ret;
    }
 
    /*--------------------------------------------------
    *
    * リンク要素を生成
    * @param    ページ番号：num
    *           リンクさせる文字列：string
    * @return   リンク要素
    *
    --------------------------------------------------*/
    protected function getLink ( $pageNum, $disp ) 
    {
    	$_ret = '';
        $get = $this->get;
        $get[$this->query] = $pageNum;
        $_ret .= '<a href="'.$this->href.'?'.http_build_query( $get ).'">'.$disp .'</a> ';
        return $_ret;
    }
}
?>