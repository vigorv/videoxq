<?php
$action = @$_POST["action"];
$search = trim(@$_POST['search']);
if (!empty($_SERVER['QUERY_STRING']))
{
	$search = trim(rawurldecode($_SERVER['QUERY_STRING']));
	$action = 'search';
}
echo'
<html>
	<head>
		<title>Filmex - ищите и обрящете</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf8" />
	</head>
	<body>
		<table width="100%" height="99%" border="0"><tr valign="middle"><td>
		<center>
			<form method="post" action="/filmex.php">
				<font color="blue" size="10">F</font>ильмекс
				<input type="hidden" name="action" value="search" />
				<input type="text" name="search" value="' . $search . '" />
				<input type="submit" value="Fаs" />
			</form>
		</center>
';
switch ($action)
{
	case "search":
		echo '<p>Ищем "' . $search . '"...</p>';
		include_once($_SERVER['DOCUMENT_ROOT'] . '/app/vendors/sphinxapi.php');
		$sphinx = new SphinxClient();
		$sphinx->setLimits(0, 1000);

		$sphinxResult = $sphinx->Query(iconv('utf8', 'windows-1251', $search), 'media1');

		$ids = array();
		if (!empty($sphinxResult["matches"]))
		{
			$ids = array_keys($sphinxResult["matches"]);
		}
		$count_result = count($ids);
		$sql = "SELECT ID, Name from films";
		if (empty($ids))
			$ids = array(-1);
		$sql .= " WHERE ID in (" . implode(', ', $ids) . ")";
		$media1 = mysql_connect("media1.itd", "root", "vig2orv115");
		mysql_select_db('lms');
		mysql_query('set NAMES cp1251');
		$lis = array();
		$res = mysql_query($sql);
		if (mysql_num_rows($res))
		{
			while($row = mysql_fetch_assoc($res))
			{
				$lis[] = iconv('windows-1251', 'utf8', '<li><a target="_blank" href="http://media1.itd/#film:' . $row['ID'] . ':1:0">' . $row['Name'] . '</a></li>');
			}
			mysql_free_result($res);
		}

		$sphinxResult = $sphinx->Query(iconv('utf8', 'windows-1251', $search), 'media1persons');

		$ids = array();
		if (!empty($sphinxResult["matches"]))
		{
			$ids = array_keys($sphinxResult["matches"]);
		}
		$count_result = count($ids);
		$sql = "SELECT ID, RusName from persones";
		if (empty($ids))
			$ids = array(-1);
		$sql .= " WHERE ID in (" . implode(', ', $ids) . ")";
		$res = mysql_query($sql);
		if (mysql_num_rows($res))
		{
			while($row = mysql_fetch_assoc($res))
			{
				$lis[] = iconv('windows-1251', 'utf8', '<li><a target="_blank" href="http://flux.itd/people/view/' . $row['ID'] . '">' . $row['RusName'] . '</a></li>');
			}
			mysql_free_result($res);
		}

		if (count($lis) > 0)
		{
			echo'<ul>Рзультаты поиска на media1 (всего найдено ' . $count_result . '):';
			foreach ($lis as $l)
				echo $l;
			echo'</ul>';
		}
		else
		{
			echo '<p>На media1 ничего не найдено</p>';
		}

		mysql_close($media1);

	default:

}

echo'
		</td></tr></table>
		<center>
			&copy; ' . date('Y') . ' Fильмекс капуригхт
		</center>
	</body>
</html>
';