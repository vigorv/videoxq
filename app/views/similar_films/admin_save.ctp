<?php
	if ($result)
		echo'
<h3>Данные сохранены</h3>
		';
	else
		echo'
<h3>Ошибка сохранения данных</h3>
		';
echo'
	<p><a href="/admin/similar_films">' . __('back', true) . '</a></p>
';