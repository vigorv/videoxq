<?php
if (empty($user)) {
    echo "No User Found";
    return;
}
?>
<h1>User <?= $user[0]['user']['username']; ?></h1>
<a onclick="return xLoad(this);"  href="/<?= $controller ?>/im/new?user=<?=$user[0]['user']['userid']; ?>" >Написать сообщение</a>

<?  
if ($friend){
    
} else{?>
<a onclick="return xLoad(this);"  href="/<?= $controller ?>/friends/add?user=<?=$user[0]['user']['userid']; ?>">Добавить в друзья</a>
<?}?>