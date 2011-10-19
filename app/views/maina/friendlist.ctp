<?
echo $this->element('maina/paginate');
$html->addCrumb(__('MyPage', true), '');
$html->addCrumb(__('Friends', true), '');
echo $html->getCrumbs(' > ', 'Home');
?>

<div class="tabs">
    <a onclick="return xLoad(this);" href="/<?= $controller; ?>/friends/list">My Friends</a>
    <a onclick="return xLoad(this);" href="/<?= $controller; ?>/friends/in">Want be my friend</a>
    <a onclick="return xLoad(this);" href="/<?= $controller; ?>/friends/out">I'm want to be friend</a>
</div>
<div>
    <? if (isset($friends)): ?>
        <h3>My Friends</h3>
        <ul id="Friends">        
            <? foreach ($friends as $friend): ?>
                <li class="user">
                    <a class="img"><img src="#"/></a>
                    <div class="userInfo">
                        <?= $friend['user']['username']; ?>

                    </div>
                    <div class="user_actions"></div>
                </li>
            <? endforeach; ?>
        </ul>
    <? endif; ?>
    <? if (isset($friendsInReq)): ?>
        <ul id="FriendsRequests">
            Заявки ко мне
            <? foreach ($friendsInReq as $friend): ?>
                <li class="user">
                    <a class="img"><img src="#"/></a>
                    <div class="userInfo">
                        <?= $friend['user']['username']; ?>
                    </div>
                    <div class="user_actions">
                        <a onclick="return xLoad(this);" href="/<?=$controller ?>/friends/add?user=<?=$friend['user']['userid']; ?>">Согласиться</a>
                        <a onclick="return xLoad(this);" href="/<?=$controller ?>/friends/req_del?user=<?=$friend['user']['userid']; ?>">Отказаться</a>
                    </div>
                </li>
            <? endforeach; ?>
        </ul>
    <? endif; ?>
    <? if (isset($friendsOutReq)): ?>
        <ul id="FriendsOutRequests">
            Мои заявки
            <? foreach ($friendsOutReq as $friend): ?>
                <li class="user">
                    <a class="img"><img src="#"/></a>
                    <div class="userInfo">
                        <?= $friend['user']['username']; ?>
                    </div>
                    <div class="user_actions">
                        <a onclick="return xLoad(this);"  href="/<?= $controller; ?>/friends/del_req?userid=<?= $friend['user']['userid']; ?>">Отменить заявку</a>
                    </div>
                </li>
            <? endforeach; ?>
        </ul>
    <? endif; ?>
</div>