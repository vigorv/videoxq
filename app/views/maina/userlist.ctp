<?
//if(!$ajaxmode)
 echo   $this->element('maina/text_filter');

echo $this->element('maina/paginate');


$html->addCrumb(__('Community', true), '');
$html->addCrumb(__('Userlist', true), '');
echo $html->getCrumbs(' > ', 'Home');

?>
<style type="text/css">
    .user a.img { float:left; height:50px;width:40px; border:1px dashed #111; margin:0 5px;}

    .userInfo label{ width:60px;float:left;}
    .userInfo span { width:90px; height:20px;  float:left; overflow:hidden;}
    .user_actions{ float:right;}
    li.user { border-bottom: 1px solid #262626; width:230px;float:left;padding:3px 5px; margin:3px 2px; height:60px;}
</style>
<div>
    <h3>Users</h3>
    <ul id="Users">        
        <? foreach ($users as $user): ?>
            <li class="user">
                <a onclick="return xLoad(this);"  class="ajaxhref" href="/<?= $controller ?>/userlist/<?=$user['user']['userid']; ?>" class="img"><img src="#"/></a>
                <div class="userInfo">
                    <label>Username: </label>
                    <a onclick="return xLoad(this);"  class="ajaxhref" href="/<?= $controller ?>/userlist/<?=$user['user']['userid']; ?>"><span><?= $user['user']['username']; ?></span></a>
                </div>
                <div class="user_actions">
                    <a onclick="return xLoad(this);" href="/<?= $controller ?>/im/new?user=<?= $user['user']['userid']; ?>" ><img height="16px" src="/img/main/<?=$theme_id;?>/user_sendmsg.png"/>Написать сообщение</a><br/>
                    <a onclick="return xLoad(this);"  href="/<?= $controller ?>/friends/add?user=<?= $user['user']['userid']; ?>" > <img height="16px" src="/img/main/<?=$theme_id;?>/user_addtofriends.png"/>Добавить в друзья</a>
                </div>
                <div style="clear:left"></div>
            </li>        
        <? endforeach; ?>
    </ul>
</div>
