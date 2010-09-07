<ul class="subMenu">
<?php if($authUser['userid']
        && $authUser['userid'] != $this->data['Blocks']['Blog']['user_id']): ?>
<li class="addPost"><a href="/blogs/view">Мой блог</a></li>
<?php endif;?>
<?php
if ($this->params['controller'] == 'posts'
        && $this->params['action'] == 'view')
{
    echo '<li class="userBlog"><a href="/blogs/view/'.$this->data['Blocks']['Blog']['id'].'">
          Блог «'.h($this->data['Blocks']['Blog']['title']).'»</a></li>';
}
if ($this->params['controller'] == 'blogs'
        && $this->params['action'] == 'view')
{
    echo '<li class="userBlog"><a href="/blogs/view/'.$this->data['Blocks']['Blog']['id'].'">
          Блог «'.h($this->data['Blocks']['Blog']['title']).'»</a></li>';
}
if ((($this->params['controller'] == 'posts'
    && $this->params['action'] != 'add')
    || ($this->params['controller'] == 'blogs'
    && !empty($this->data['Blocks']['Blog']['user_id'])
    && $authUser['userid'] == $this->data['Blocks']['Blog']['user_id']))
    && $authUser['userid'])
{
	echo '<li class="addPost"><a href="/posts/add">Создать запись</a></li>';
}
elseif ($this->params['controller'] == 'posts'
        && $this->params['action'] == 'add')
{
    echo '<li class="addPost">Создать запись</li>';
}

?>
</ul>