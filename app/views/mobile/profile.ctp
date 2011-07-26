 <li>
    <h3>Hello <?= $user['username']; ?></h3>
</li>
<li><a href=""><?= __('My Collection', true); ?></a></li>
<li><a href=""><?= __('My Settings', true); ?></a></li>
<li>
    <form action="/mobile/logout" method="post">
        <input name="logout" type="submit" value="<?= __('Logout', true); ?>"/>
    </form>
</li>