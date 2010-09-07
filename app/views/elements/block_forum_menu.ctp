<ul>
    <li><?php echo $html->link('Новые сообщения', Configure::read('App.forumPath') . 'search.php?do=getnew'); ?></li>
    <li><?php echo $html->link('Пользователи', Configure::read('App.forumPath') . 'memberlist.php'); ?></li>
    <li><?php echo $html->link('Календарь', Configure::read('App.forumPath') . 'calendar.php'); ?></li>
    <li><?php echo $html->link('Помощь', Configure::read('App.forumPath') . 'faq.php'); ?></li>
</ul>
