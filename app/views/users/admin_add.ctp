<div class="users form">
<?php echo $form->create('User');?>
	<fieldset>
 		<legend><?php __('Add User');?></legend>
	<?php
		echo $form->input('usergroupid');
		echo $form->input('membergroupids');
		echo $form->input('displaygroupid');
		echo $form->input('username');
		echo $form->input('password');
		echo $form->input('passworddate');
		echo $form->input('email');
		echo $form->input('styleid');
		echo $form->input('parentemail');
		echo $form->input('homepage');
		echo $form->input('icq');
		echo $form->input('aim');
		echo $form->input('yahoo');
		echo $form->input('msn');
		echo $form->input('skype');
		echo $form->input('showvbcode');
		echo $form->input('showbirthday');
		echo $form->input('usertitle');
		echo $form->input('customtitle');
		echo $form->input('joindate');
		echo $form->input('daysprune');
		echo $form->input('lastvisit');
		echo $form->input('lastactivity');
		echo $form->input('lastpost');
		echo $form->input('lastpostid');
		echo $form->input('posts');
		echo $form->input('reputation');
		echo $form->input('reputationlevelid');
		echo $form->input('timezoneoffset');
		echo $form->input('pmpopup');
		echo $form->input('avatarid');
		echo $form->input('avatarrevision');
		echo $form->input('profilepicrevision');
		echo $form->input('sigpicrevision');
		echo $form->input('options');
		echo $form->input('birthday');
		echo $form->input('birthday_search');
		echo $form->input('maxposts');
		echo $form->input('startofweek');
		echo $form->input('ipaddress');
		echo $form->input('referrerid');
		echo $form->input('languageid');
		echo $form->input('emailstamp');
		echo $form->input('threadedmode');
		echo $form->input('autosubscribe');
		echo $form->input('pmtotal');
		echo $form->input('pmunread');
		echo $form->input('salt');
		echo $form->input('ipoints');
		echo $form->input('infractions');
		echo $form->input('warnings');
		echo $form->input('infractiongroupids');
		echo $form->input('infractiongroupid');
		echo $form->input('adminoptions');
		echo $form->input('Group');
	?>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('List Users', true), array('action'=>'index'));?></li>
		<li><?php echo $html->link(__('List Groups', true), array('controller'=> 'groups', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Group', true), array('controller'=> 'groups', 'action'=>'add')); ?> </li>
	</ul>
</div>
