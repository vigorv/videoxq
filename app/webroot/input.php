<?php
DEFINE(_STK0_, 1);
DEFINE(_OMSK_, 2);
$zIds = array(
_OMSK_ => 'Omsk',
_STK0_ => 'STK0',
);

$params = array(
12 => 'Абакан',
11 => 'Иркутск',
10 => 'Томск',
9 => 'Чита',
8 => 'кемерово',
7 => 'Красноярск',
6 => 'Бурятия',
5 => 'омск',
4 => 'Новосибирск',
1 => 'Барнаул',
);

$zones = array();
$zones[_OMSK_] = array(
	'87.103.176.0/20','87.103.224.0/21','90.188.128.0/18','90.189.64.0/19',
	'92.124.128.0/18','92.126.192.0/19','95.189.128.0/17','195.162.32.0/19'
	);

$zones[_STK0_] = array(
	'87.103.128.0/17','90.188.0.0/15','92.124.0.0/14','95.188.0.0/14',
	'178.184.0.0/14','2.60.0.0/14','194.150.128.0/19','195.162.32.0/19',
	'195.46.96.0/19','195.112.224.0/19','212.20.0.0/18','212.94.96.0/19',
	'212.164.0.0/16','213.210.64.0/18','213.228.64.0/18','217.18.128.0/19',
	'217.70.96.0/19','217.116.128.0/19',
	);

	function netMatch ($CIDR,$IP)
	{
	    list ($net, $mask) = explode ('/', $CIDR);
	    return (ip2long($IP) & ~((1 << (32 - $mask)) - 1)) == ip2long($net);
	}

if (($_SERVER['REQUEST_METHOD']=='GET') && empty($_GET['proc']))
{
$b_info=$_SERVER['HTTP_USER_AGENT'];//запоминаем browser info
//echo "<br>";
$user_ip=$_SERVER['REMOTE_ADDR'];// Запоминаем IP
//страница откуда пришел юзер если он это сделал нажав с сайта нерабочую ссылку
//echo "<br>";
$event= (empty($_SERVER['HTTP_REFERER'])) ? '' : $_SERVER['HTTP_REFERER'];//ссылка на новость

$param= (empty($_GET['param'])) ? 0 : intval($_GET['param']);//доп параметр

function to_base($user_ip,$event,$info,$param)
{
	global $zones;

	$zone = 0;//NOT DETECTED
	foreach($zones as $key => $zns)
	{
		if (!empty($zns))
		{
			foreach ($zns as $z)
			{
				if (netMatch($z, $user_ip))
				{
					$zone = $key;
					break;
				}
			}
		}
		if (!empty($zone))
			break;
	}

    mysql_connect("localhost", "videoxq", "JJKqe8g45TCrtdqxG5Vn");
    mysql_select_db("videoxq");

    $event      = mysql_real_escape_string($event);
    $info       = mysql_real_escape_string($info);

    $sql= "insert into `input` (dt, user_ip, event, info, param, zone) values ('" . date('Y-m-d H:i:s') . "', '{$user_ip}', '{$event}', '{$info}', '{$param}', '{$zone}')";
    $result = mysql_query($sql);
    mysql_close();
}

to_base($user_ip,$event,$b_info, $param);

header('location: /');
}
else
{
        mysql_connect("localhost", "videoxq", "JJKqe8g45TCrtdqxG5Vn");
        mysql_select_db("videoxq");

        $event      = mysql_real_escape_string($event);
        $info       = mysql_real_escape_string($info);

        $param = $fromdate = $todate = '';
        $cond = array();
        if (!empty($_POST['fromdate']))
        {
        	$fromdate = $_POST['fromdate'];
        	$cond[] = 'dt >= "' . mysql_real_escape_string($fromdate) . ' 00:00:00"';
        }

        if (!empty($_POST['todate']))
        {
        	$todate = $_POST['todate'];
        	$cond[] = 'dt <= "' . mysql_real_escape_string($todate) . ' 23:59:59"';
        }

        if (!empty($_POST['param']))
        {
        	$param = intval($_POST['param']);
        	$cond[] = 'param = ' . $param;
        }

        if (!empty($cond))
        	$cond = ' where ' . implode(' and ', $cond);
        else
        	$cond = '';

        $cnt = 0;
	$sql= "select count(id) from input";
    $query = mysql_query($sql);
	$res = mysql_fetch_row($query);
	$total = 0;
	if ($res)
	{
		$total = $res[0];
	}
        mysql_free_result($query);

        $sql= "select user_ip from `input`" . $cond;
//echo $sql;
        $query = mysql_query($sql);
        $cnt = array();
        while($res = mysql_fetch_row($query))
        {
			$zone = 0;//NOT DETECTED
			foreach($zones as $key => $zns)
			{
				if (!empty($zns))
				{
					foreach ($zns as $z)
					{
						if (netMatch($z, $res[0]))
						{
							$zone = $key;
							break;
						}
					}
				}
				if (!empty($zone))
				{
//echo '<p>detect ' . $res[0] . ' IN ' . $z;
					break;
				}
			}
			if (empty($cnt[$zone]))
				$cnt[$zone] = 1;
			else
				$cnt[$zone]++;
        }
        mysql_free_result($query);
        mysql_close();
        foreach ($zIds as $z => $nm)
        {
        	$c = (empty($cnt[$z]) ? 0 : $cnt[$z]);
	       	echo '<h3>' . $nm . ' users - ' . $c . ' (' . round($c / $total * 100, 2) . '%)</h3>';
		}
       	echo '<h3>Total users cnt - ' . $total . '</h3>';

?>
<link rel="stylesheet" type="text/css" media="all" href="/css/calendar-blue.css" title="win2k-cold-1" />
<script type="text/javascript" src="/js/calendar.js"></script>
<script type="text/javascript" src="/js/calendar-en.js"></script>
<script type="text/javascript" src="/js/calendar-setup.js"></script>
<h3>Period</h3>
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
<td>Param <select name="param"><option value="">default</option>
<?php
	foreach ($params as $i => $nm)
	{
		echo '<option value="' . $i . '"';
		if ($param == $i)
			echo' selected="selected"';
		echo '>' . $nm . '</option>';
	}
?>
</select></td>
<td><input type="submit" value="Ok" /></td>
</form>
</tr>
</table>
<?php
}