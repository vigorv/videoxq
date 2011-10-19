<?
echo $this->element('maina/paginate');
$html->addCrumb(__('MyPage', true), '');
$html->addCrumb(__('Friends', true), '');
echo $html->getCrumbs(' > ', 'Home');
?>
<br/>
<a onclick="return xLoad(this);"  
<?
if (isset($userMessages)) {
    echo 'class="active"';
    $sent = false;
}
?>
   href="/<?= $controller; ?>/im/in">Полученные</a>

<a onclick="return xLoad(this);" 
<?
if (isset($userSent)) {
    echo 'class="active"';
    $userMessages = &$userSent;
    $sent = true;
}
?>
   href="/<?= $controller; ?>/im/out">Отправленные</a>

<ul class="messages_list">
    <? if (!$sent):
        foreach ($userMessages as $message): ?>
            <li>From: <label><?= $message['user']['username']; ?></label><br/>
                <?= $message['usermessages']['txt']; ?>
            <a onclick="return xLoad(this);"  href="/<?= $controller ?>/im/new?user=<?= $message['user']['userid']; ?>">Ответить</a>
            </li>
        <?
        endforeach;
    else:
        foreach ($userMessages as $message):
            ?>
            <li>To: <label><?= $message['tou']['username']; ?></label><br/>
            <?= $message['usermessages']['txt']; ?>
            </li>
    <? endforeach;
endif; ?>
</ul>