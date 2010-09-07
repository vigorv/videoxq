<div class="users index">
<h2><?php __('Users');?></h2>
<p>
<?php
echo $paginator->counter(array(
'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
));
	if (!empty($args['search']))
	{
		echo 'serach exists';
		$paginator->options['url']['search'] = $args['search'];
	}
?></p>
<table cellpadding="0" cellspacing="0">
<tr>
    <th><?php echo $paginator->sort('userid');?></th>
    <th><?php echo $paginator->sort('usergroupid');?></th>
    <th><?php echo $paginator->sort('membergroupids');?></th>
    <th><?php echo $paginator->sort('username');?></th>
    <th><?php echo $paginator->sort('email');?></th>
    <th><?php echo $paginator->sort('joindate');?></th>
    <th><?php echo $paginator->sort('lastvisit');?></th>
    <th><?php echo $paginator->sort('lastactivity');?></th>
    <th><?php echo $paginator->sort('lastpost');?></th>
    <th><?php echo $paginator->sort('lastpostid');?></th>
    <th><?php echo $paginator->sort('ipaddress');?></th>
    <th><?php echo $paginator->sort('warnings');?></th>
    <th class="actions"><?php __('Actions');?></th>
</tr>
<?php
$i = 0;
foreach ($users as $user):
    $class = null;
    if ($i++ % 2 == 0) {
        $class = ' class="altrow"';
    }
?>
    <tr<?php echo $class;?>>
        <td>
            <?php echo $user['User']['userid']; ?>
        </td>
        <td>
            <?php echo $user['User']['usergroupid']; ?>
        </td>
        <td>
            <?php echo $user['User']['membergroupids']; ?>
        </td>
        <td>
            <?php echo $user['User']['username']; ?>
        </td>
        <td>
            <?php echo $user['User']['email']; ?>
        </td>
        <td>
            <?php echo $user['User']['joindate']; ?>
        </td>
        <td>
            <?php echo $user['User']['lastvisit']; ?>
        </td>
        <td>
            <?php echo $user['User']['lastactivity']; ?>
        </td>
        <td>
            <?php echo $user['User']['lastpost']; ?>
        </td>
        <td>
            <?php echo $user['User']['lastpostid']; ?>
        </td>
        <td>
            <?php echo $user['User']['ipaddress']; ?>
        </td>
        <td>
            <?php echo $user['User']['warnings']; ?>
        </td>
        <td class="actions">
            <?php echo $html->link(__('View', true), array('action'=>'view', $user['User']['userid'])); ?>
            <?php echo $html->link(__('Edit', true), array('action'=>'edit', $user['User']['userid'])); ?>
            <?php echo $html->link(__('Delete', true), array('action'=>'delete', $user['User']['userid']), null, sprintf(__('Are you sure you want to delete # %s?', true), $user['User']['userid'])); ?>
        </td>
    </tr>
<?php endforeach; ?>
</table>
</div>
<div class="paging">
    <?php echo $paginator->prev('<< '.__('previous', true), array(), null, array('class'=>'disabled'));?>
 | 	<?php echo $paginator->numbers();?>
    <?php echo $paginator->next(__('next', true).' >>', array(), null, array('class'=>'disabled'));?>
</div>
<?php
	$params = '';
	foreach ($args as $key => $val)
	{
		if ($key == 'search') continue;
		$params .= '/' . $key . ':' . $val;
	}
	$params .= '/search:';

	echo $form->input('Поиск по имени (email)', array('name' => 'search', 'id' => 'search_id', 'onkeyup' => 'if ((event.which == 13) || (event.keyCode == 13)) location.href=\'/admin/users/index' . $params . '\' + $(\'input:text\').get(0).value;'));
	echo '<input type="button" value="Найти" class="button" onclick="location.href=\'/admin/users/index' . $params . '\' + $(\'input:text\').get(0).value;">';
?>
<div class="actions">
    <ul>
        <li><?php echo $html->link(__('WhereIs', true), array('action'=>'whereis')); ?></li>
        <li><?php echo $html->link(__('Service', true), array('action'=>'service')); ?></li>
        <li><?php echo $html->link(__('Users IP stats', true), array('action'=>'stat')); ?></li>
        <li><?php echo $html->link(__('New User', true), array('action'=>'add')); ?></li>
        <li><?php echo $html->link(__('List Groups', true), array('controller'=> 'groups', 'action'=>'index')); ?> </li>
        <li><?php echo $html->link(__('New Group', true), array('controller'=> 'groups', 'action'=>'add')); ?> </li>
    </ul>
</div>
