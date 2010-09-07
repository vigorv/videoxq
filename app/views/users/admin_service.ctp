<?php
switch ($action)
{
	case "restoregroups":
		if (count($users))
		{
			echo'<h4>Принадлежность к группе восстановлена для ' . count($users) . ' пользователей</h4>';
		}
		else
		{
			echo'<h4>Все пользователи принадлежат к группам</h4>';
		}
	break;
	default:
		echo 'Количество незакрепленных за группами пользователей: ' . count($users);
		if (count($users) > 0)
			echo ' (<a href="/admin/users/restoregroups">закрепить</a>)';

}