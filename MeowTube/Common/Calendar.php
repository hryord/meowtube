<?php
//---------------------------------------------------------------------
// Calendar_Month_Weeks を利用する場合
//---------------------------------------------------------------------
require_once 'Calendar/Month/Weeks.php';

# 曜日に表示する文字列とCSSクラス名を設定します。
$weekdayDefines = array(
    array(
        '日',
        'sunday'),
    array(
        '月',
        'monday'),
    array(
        '火',
        'tuesday'),
    array(
        '水',
        'wednesday'),
    array(
        '木',
        'thursday'),
    array(
        '金',
        'friday'),
    array(
        '土',
        'saturday'));

# カレンダーの左側に表示させる曜日を設定します。
$weekdayBase = 0; // 0:日曜～6:土曜


# カレンダーに表示する年月を取得します。
// デフォルトの年月を設定
$year = (int)date('Y');
$month = (int)date('n');

// GETパラメータが指定されている場合は値を検証してから表示年月を取得

if( isset($_GET['year_month']) )
{
    $yyyymm = trim($_GET['year_month']);

    // YYYYMM形式であれば年月を取得
    if( preg_match('/^([12]\d{3})(1[012]|0[1-9])$/', $yyyymm, $match) )
    {
        $year = (int)$match[1];
        $month = (int)$match[2];
    }
}

# カレンダーデータを生成します。
$calendar = new Calendar_Month_Weeks($year, $month, $weekdayBase);
$calendar->build();

# カレンダーの曜日部分を表示します。
$thisMonth = $calendar->thisMonth(TRUE); //今月
$prevMonth = $calendar->prevMonth(TRUE); //先月
$nextMonth = $calendar->nextMonth(TRUE); //来月


if( isset($_GET['id']) )
{
    $id_str = '&id=' . urldecode($_GET['id']);
}
else
{
    $id_str = "";
}

$prevMonthUrl = '?year_month=' . date('Ym', $prevMonth) . $id_str;
$nextMonthUrl = '?year_month=' . date('Ym', $nextMonth) . $id_str;
$thisMonthText = date('Y/m', $thisMonth);
?>
<table border="1">
	<thead>
		<tr>
			<td><a href="<?php echo $prevMonthUrl;?>">&lt;</a></td>
			<th colspan="5"><?php echo $thisMonthText;?></th>
			<td><a href="<?php echo $nextMonthUrl;?>">&gt;</a></td>
		</tr>
		<tr>
<?php
for( $i = 0; $i < 7; $i ++ )
{
    $weekday = ($weekdayBase + $i) % 7;
    $weekdayText = $weekdayDefines[$weekday][0];
    $weekdayClass = $weekdayDefines[$weekday][1];

    echo '<th class="' . $weekdayClass . '">', $weekdayText, '</th>';
}
?>
    </tr>
	</thead>
	<tbody>
<?php
# 今月の動画データを検索します
$db = DBConnection::get()->handle();

// 猫動画データ取得（idと日付）
$sql = 'SELECT id, DATE_FORMAT(getdate, "%e") as getdate FROM youtube ' . 'WHERE getdate LIKE :month';

try
{
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':month', date('Y-m', $thisMonth) . '%');
    $stmt->execute();
    $videoList = $stmt->fetchAll();
}
catch( PDOException $e )
{
    var_dump($e->getMessage());
}

# カレンダーの日付部分を表示します。
while( $days = $calendar->fetch() )
{
    $days->build();
    $weekday = 0;

    echo '<tr>';
    while( $day = $days->fetch() )
    {
        $weekdayClass = $weekdayDefines[$weekday][1];
        if( $day->isEmpty() )
        {
            $dayText = "&nbsp;";
        }
        else
        {
            $dayText = $day->thisDay();
        }

        foreach( $videoList as $video )
        {
            if( $video['getdate'] == $dayText )
            {
                $dayText = '<a href="./TodaysCat.php?id=' . $video['id'] . '&year_month=' . date('Ym', $thisMonth) . '">' . $video['getdate'] . '</a>';
            }
        }

        echo '<td class="' . $weekdayClass . '">', $dayText, '</td>';

        $weekday ++;
    }
    echo '</tr>';
}
?>
  </tbody>
</table>