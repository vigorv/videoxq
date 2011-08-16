<?php
header("charset: windows-1251");
$fromdate = $todate = $checkmonths = $checkdays = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    mysql_connect("localhost", "videoxq", "JJKqe8g45TCrtdqxG5Vn");
    mysql_select_db("videoxq");

    $cond = array();
    if (!empty($_POST['fromdate']))
    {
    	$fromdate = $_POST['fromdate'];
    	$cond[] = 'joindate >= "' . strtotime(mysql_real_escape_string($fromdate) . ' 00:00:00') . '"';
    }

    if (!empty($_POST['todate']))
    {
    	$todate = $_POST['todate'];
    	$cond[] = 'joindate <= "' . strtotime(mysql_real_escape_string($todate) . ' 23:59:59') . '"';
    }

    if (!empty($_POST['grp']))
    {
    	$format = "%Y-%m-%d";
    	$checkdays = "checked";
    }
    else
    {
    	$format = "%Y-%m";
    	$checkmonths = "checked";
    }

    if (!empty($cond))
    	$cond = ' where ' . implode(' and ', $cond);
    else
    	$cond = '';

	$sql= 'SELECT DATE_FORMAT(FROM_UNIXTIME(joindate), "' . $format . '") AS dt, COUNT(userid) FROM user ' . $cond . ' GROUP BY dt';
//echo $sql;
    $query = mysql_query($sql);
    $total = 0;
    echo '<table border="1"><tr><td>Period</td><td>Count</td></tr>';
    while($res = mysql_fetch_row($query))
    {
    	echo '<tr align="center"><td>' . $res[0] . '</td><td>' . $res[1] . '</td></tr>';
    	$total += $res[1];
    }
    echo '</table>';
    mysql_free_result($query);
    mysql_close();
   	echo '<h3>Total users cnt - ' . $total . '</h3>';
}
?>
<link rel="stylesheet" type="text/css" media="all" href="/css/calendar-blue.css" title="win2k-cold-1" />
<script type="text/javascript" src="/js/calendar.js"></script>
<script type="text/javascript" src="/js/calendar-en.js"></script>
<script type="text/javascript" src="/js/calendar-setup.js"></script>
<table border="1" cellpadding="3">
<tr>
<form method="post">
<td>From <input type="text" name="fromdate" id="fromdate" size="17" maxlength="16" value="<?php echo $fromdate; ?>">
<img src="/img/img.gif"  align="absmiddle" id="fromdatebut" style="cursor: pointer; border: 0" />
<script type="text/javascript">
    Calendar.setup({
	inputField     : "fromdate",	// id of the input field
	ifFormat       : "%Y-%m-%d",	// format of the input field
	button         : "fromdatebut",	// trigger for the calendar (button ID)
	align          : "Br",		// alignment
	timeFormat     : "24",
	showsTime      : true,
	singleClick    : true
    });
</script>
</td>
<td>To <input type="text" name="todate" id="todate" size="17" maxlength="16" value="<?php echo $todate; ?>">
<img src="/img/img.gif"  align="absmiddle" id="todatebut" style="cursor: pointer; border: 0" />
<script type="text/javascript">
    Calendar.setup({
	inputField     : "todate",	// id of the input field
	ifFormat       : "%Y-%m-%d",	// format of the input field
	button         : "todatebut",	// trigger for the calendar (button ID)
	align          : "Br",		// alignment
	timeFormat     : "24",
	showsTime      : true,
	singleClick    : true
    });
</script>
</td>
<td>group by <input name="grp" type="radio" value="0" <?php echo $checkmonths; ?>>months <input name="grp" type="radio" value="1" <?php echo $checkdays; ?>>days</td>
<td><input type="submit" value="Ok" /></td>
</form>
</tr>
</table>