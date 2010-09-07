<pre>
<?php
	if (!empty($lst))
	{
		echo '<table width="100%">';
		echo'<tr>
			<td>' . $paginator->sort('№ оплаты', 'Pay.id') . '</td>
			<td>' . $paginator->sort('login', 'User.username') . '</td>
			<td>' . $paginator->sort('Создано', 'Pay.created') . '</td>
			<td>' . $paginator->sort('Сумма', 'Pay.summ') . '</td>
			<td>' . $paginator->sort('Оплачено с', 'Pay.paydare') . '</td>
			<td>' . $paginator->sort('Оплачено по', 'Pay.findate') . '</td>
			<td>' . $paginator->sort('статус', 'Pay.status') . '</td>
			<td>Информация</td>
			<td>Управление</td>
		</tr>';
		foreach ($lst as $l)
		{
			switch($l['Pay']['status'])
			{
				case _PAY_DONE_:
					$status = _PAY_DONE_STR_;
					break;
				case _PAY_FAIL_:
					$status = _PAY_FAIL_STR_;
					break;
				default:
					$status = _PAY_WAIT_STR_;
			}
			echo'<tr>
				<td>' . $l["Pay"]["id"] . '</td>
				<td>' . $l["User"]['username'] . '</td>
				<td>' . date('d.m.y H:i', $l["Pay"]["created"]) . '</td>
				<td>' . $l["Pay"]["summ"] . '</td>
				<td>' . ((empty($l["Pay"]["paydate"])) ? "" : date('d.m.y H:i', $l["Pay"]["paydate"])) . '</td>
				<td>' . ((empty($l["Pay"]["findate"])) ? "" : date('d.m.y H:i', $l["Pay"]["findate"])) . '</td>
				<td>' . $status . '</td>
				<td>' . $l["Pay"]["info"] . '</td>
				<td><a href="/admin/pays/edit/' . $l["Pay"]["id"] . '">редактировать</a></td>
			</tr>';
		}
		echo'</table>';
	}
	echo'<div class="paging">';
    echo $paginator->prev('< ', null, null, array('class' => 'disabled'));
	//echo $paginator->counter();
	echo $paginator->numbers(array('modulus' => 3));
    echo $paginator->next(' >', null, null, array('class' => 'disabled'));
	echo'</div>';
?>
</pre>
<?php
echo $html->link('Внести оплату', array('action'=>'edit', 'controller' => 'pays', Configure::read('Routing.admin') => true));
?>
 | <a href="/admin/pays/stat">Статистика</a><br />