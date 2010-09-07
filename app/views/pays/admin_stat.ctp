<div align="left">
<script type="text/javascript">
	function statSubmit(step)
	{
		href = "<?php echo "http://" . $_SERVER['HTTP_HOST'] . "/admin/pays/stat"; ?>";
		href = href + "/" + document.paystatform.paysystem.value;
		href = href + "/" + document.paystatform.period.value;
		href = href + "/" + step;
		location.href = href;
		return false;
	}
</script>
<form name="paystatform" id="paystatform">
<?php

echo 'Выберите период: <br />';

	echo '<select name="period" onchange = "return statSubmit(0);">';
foreach ($periodList as $p)
{
	$selected = '';
	if ($p['id'] == $period)
	{
		$selected = ' selected="selected"';
	}
	echo '<option value="' . $p['id'] . '"' . $selected . '>' . $p['nm'] . '</option>';
}
	echo '</select>';

echo '<br />Выберите систему оплаты: <br />';

	echo '<select name="paysystem" onchange = "return statSubmit(' . $step . ');">';
foreach ($paysystemList as $p)
{
	$selected = '';
	if ($p['id'] == $paysystem)
	{
		$selected = ' selected="selected"';
	}
	echo '<option value="' . $p['id'] . '"' . $selected . '>' . $p['nm'] . '</option>';
}
	echo '</select>';
?>
</form>
<?php
$wanted = 0; $payed = 0;
foreach ($lst as $l)
{
	if (!empty($l['Pay']['paydate']))
	{
		$payed += $l['Pay']['summ'];
	}
	else
	{
		$wanted += $l['Pay']['summ'];
	}
}
echo '<h3>Период c ' . date('d.m.y', $start) . ' по ' . date('d.m.y', $fin) . ' ( <a href="/admin/pays/stat/' . $paysystem . '/' . $period . '/' . ($step - 1) . '"><- Назад</a> | <a href="/admin/pays/stat/' . $paysystem . '/' . $period . '/' . ($step + 1) . '">Вперед -></a> )</h3>';
echo '<h3>Хотели оплатить: ' . ($wanted + $payed) . ' ' . $paysystemList[$paysystem]['vl'] . '<br />Оплатили: ' . $payed . ' ' . $paysystemList[$paysystem]['vl'] . '</h3>';
?>
</div>
<a href="/admin/pays">Все платежи</a>