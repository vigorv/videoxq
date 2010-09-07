<div class="users index">
<h2><?php __('Users');?></h2>
<p>
<?php
echo $paginator->counter(array(
'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
));
?></p>
<table cellpadding="0" cellspacing="0">
<tr>
	<th><?php echo $paginator->sort('userid');?></th>
	<th><?php echo $paginator->sort('usergroupid');?></th>
	<th><?php echo $paginator->sort('membergroupids');?></th>
	<th><?php echo $paginator->sort('displaygroupid');?></th>
	<th><?php echo $paginator->sort('username');?></th>
	<th><?php echo $paginator->sort('password');?></th>
	<th><?php echo $paginator->sort('passworddate');?></th>
	<th><?php echo $paginator->sort('email');?></th>
	<th><?php echo $paginator->sort('styleid');?></th>
	<th><?php echo $paginator->sort('parentemail');?></th>
	<th><?php echo $paginator->sort('homepage');?></th>
	<th><?php echo $paginator->sort('icq');?></th>
	<th><?php echo $paginator->sort('aim');?></th>
	<th><?php echo $paginator->sort('yahoo');?></th>
	<th><?php echo $paginator->sort('msn');?></th>
	<th><?php echo $paginator->sort('skype');?></th>
	<th><?php echo $paginator->sort('showvbcode');?></th>
	<th><?php echo $paginator->sort('showbirthday');?></th>
	<th><?php echo $paginator->sort('usertitle');?></th>
	<th><?php echo $paginator->sort('customtitle');?></th>
	<th><?php echo $paginator->sort('joindate');?></th>
	<th><?php echo $paginator->sort('daysprune');?></th>
	<th><?php echo $paginator->sort('lastvisit');?></th>
	<th><?php echo $paginator->sort('lastactivity');?></th>
	<th><?php echo $paginator->sort('lastpost');?></th>
	<th><?php echo $paginator->sort('lastpostid');?></th>
	<th><?php echo $paginator->sort('posts');?></th>
	<th><?php echo $paginator->sort('reputation');?></th>
	<th><?php echo $paginator->sort('reputationlevelid');?></th>
	<th><?php echo $paginator->sort('timezoneoffset');?></th>
	<th><?php echo $paginator->sort('pmpopup');?></th>
	<th><?php echo $paginator->sort('avatarid');?></th>
	<th><?php echo $paginator->sort('avatarrevision');?></th>
	<th><?php echo $paginator->sort('profilepicrevision');?></th>
	<th><?php echo $paginator->sort('sigpicrevision');?></th>
	<th><?php echo $paginator->sort('options');?></th>
	<th><?php echo $paginator->sort('birthday');?></th>
	<th><?php echo $paginator->sort('birthday_search');?></th>
	<th><?php echo $paginator->sort('maxposts');?></th>
	<th><?php echo $paginator->sort('startofweek');?></th>
	<th><?php echo $paginator->sort('ipaddress');?></th>
	<th><?php echo $paginator->sort('referrerid');?></th>
	<th><?php echo $paginator->sort('languageid');?></th>
	<th><?php echo $paginator->sort('emailstamp');?></th>
	<th><?php echo $paginator->sort('threadedmode');?></th>
	<th><?php echo $paginator->sort('autosubscribe');?></th>
	<th><?php echo $paginator->sort('pmtotal');?></th>
	<th><?php echo $paginator->sort('pmunread');?></th>
	<th><?php echo $paginator->sort('salt');?></th>
	<th><?php echo $paginator->sort('ipoints');?></th>
	<th><?php echo $paginator->sort('infractions');?></th>
	<th><?php echo $paginator->sort('warnings');?></th>
	<th><?php echo $paginator->sort('infractiongroupids');?></th>
	<th><?php echo $paginator->sort('infractiongroupid');?></th>
	<th><?php echo $paginator->sort('adminoptions');?></th>
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
			<?php echo $user['User']['displaygroupid']; ?>
		</td>
		<td>
			<?php echo $user['User']['username']; ?>
		</td>
		<td>
			<?php echo $user['User']['password']; ?>
		</td>
		<td>
			<?php echo $user['User']['passworddate']; ?>
		</td>
		<td>
			<?php echo $user['User']['email']; ?>
		</td>
		<td>
			<?php echo $user['User']['styleid']; ?>
		</td>
		<td>
			<?php echo $user['User']['parentemail']; ?>
		</td>
		<td>
			<?php echo $user['User']['homepage']; ?>
		</td>
		<td>
			<?php echo $user['User']['icq']; ?>
		</td>
		<td>
			<?php echo $user['User']['aim']; ?>
		</td>
		<td>
			<?php echo $user['User']['yahoo']; ?>
		</td>
		<td>
			<?php echo $user['User']['msn']; ?>
		</td>
		<td>
			<?php echo $user['User']['skype']; ?>
		</td>
		<td>
			<?php echo $user['User']['showvbcode']; ?>
		</td>
		<td>
			<?php echo $user['User']['showbirthday']; ?>
		</td>
		<td>
			<?php echo $user['User']['usertitle']; ?>
		</td>
		<td>
			<?php echo $user['User']['customtitle']; ?>
		</td>
		<td>
			<?php echo $user['User']['joindate']; ?>
		</td>
		<td>
			<?php echo $user['User']['daysprune']; ?>
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
			<?php echo $user['User']['posts']; ?>
		</td>
		<td>
			<?php echo $user['User']['reputation']; ?>
		</td>
		<td>
			<?php echo $user['User']['reputationlevelid']; ?>
		</td>
		<td>
			<?php echo $user['User']['timezoneoffset']; ?>
		</td>
		<td>
			<?php echo $user['User']['pmpopup']; ?>
		</td>
		<td>
			<?php echo $user['User']['avatarid']; ?>
		</td>
		<td>
			<?php echo $user['User']['avatarrevision']; ?>
		</td>
		<td>
			<?php echo $user['User']['profilepicrevision']; ?>
		</td>
		<td>
			<?php echo $user['User']['sigpicrevision']; ?>
		</td>
		<td>
			<?php echo $user['User']['options']; ?>
		</td>
		<td>
			<?php echo $user['User']['birthday']; ?>
		</td>
		<td>
			<?php echo $user['User']['birthday_search']; ?>
		</td>
		<td>
			<?php echo $user['User']['maxposts']; ?>
		</td>
		<td>
			<?php echo $user['User']['startofweek']; ?>
		</td>
		<td>
			<?php echo $user['User']['ipaddress']; ?>
		</td>
		<td>
			<?php echo $user['User']['referrerid']; ?>
		</td>
		<td>
			<?php echo $user['User']['languageid']; ?>
		</td>
		<td>
			<?php echo $user['User']['emailstamp']; ?>
		</td>
		<td>
			<?php echo $user['User']['threadedmode']; ?>
		</td>
		<td>
			<?php echo $user['User']['autosubscribe']; ?>
		</td>
		<td>
			<?php echo $user['User']['pmtotal']; ?>
		</td>
		<td>
			<?php echo $user['User']['pmunread']; ?>
		</td>
		<td>
			<?php echo $user['User']['salt']; ?>
		</td>
		<td>
			<?php echo $user['User']['ipoints']; ?>
		</td>
		<td>
			<?php echo $user['User']['infractions']; ?>
		</td>
		<td>
			<?php echo $user['User']['warnings']; ?>
		</td>
		<td>
			<?php echo $user['User']['infractiongroupids']; ?>
		</td>
		<td>
			<?php echo $user['User']['infractiongroupid']; ?>
		</td>
		<td>
			<?php echo $user['User']['adminoptions']; ?>
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
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('New User', true), array('action'=>'add')); ?></li>
		<li><?php echo $html->link(__('List Groups', true), array('controller'=> 'groups', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Group', true), array('controller'=> 'groups', 'action'=>'add')); ?> </li>
	</ul>
</div>
