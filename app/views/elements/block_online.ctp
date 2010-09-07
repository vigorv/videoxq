<?php
//array('members' => $active['members'], 'anon' => $active['anon'],
//                     'guests' => $active['guests'], 'names' => $active['names'],
//                     'total' => $active['members'] + $active['guests'] + $active['anon']);
?>
<p>Пользователей: <?php echo $block_online['total'] ?></p>
Гостей: <?php echo $block_online['guests'] ?><br>
Скрытых: <?php echo $block_online['anon'] ?><br>
Видимых: <?php echo $block_online['members'] ?><br>
<?php
$userList = '';
foreach ($block_online['names'] as $name)
{
    $userList .= $html->link($name['username'], '/vb/member.php?u=' . $name['id'], false, false, false) . ', ';
}

echo rtrim($userList, ', ');

?>
