 <li>
    <h3><?=__('Hello',true);?> <?= $user['username']; ?></h3>
</li>
<li><a class="href_li" href="/mobile/profile?sub=history"  onClick="myPager.nextScreen(this,1); return false;"><?= __('History', true); ?></a></li>
<li><a class="href_li" href="/mobile/profile?sub=settings"  onClick="myPager.nextScreen(this,1); return false;"><?= __('Settings', true); ?></a></li>
<li>
    <form action="/mobile/logout" method="post">
        <input name="logout" type="submit" value="<?= __('Logout', true); ?>"/>
    </form>
</li>