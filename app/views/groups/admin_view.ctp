<div class="groups view">
<h2><?php  __('Group');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $group['Group']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $group['Group']['created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Modified'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $group['Group']['modified']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Active'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $group['Group']['active']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Title'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $group['Group']['title']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Parent Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $group['Group']['parent_id']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Edit Group', true), array('action'=>'edit', $group['Group']['id'])); ?> </li>
		<li><?php echo $html->link(__('Delete Group', true), array('action'=>'delete', $group['Group']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $group['Group']['id'])); ?> </li>
		<li><?php echo $html->link(__('List Groups', true), array('action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Group', true), array('action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List Vbgroups', true), array('controller'=> 'vbgroups', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Vbgroup', true), array('controller'=> 'vbgroups', 'action'=>'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php __('Related Vbgroups');?></h3>
	<?php if (!empty($group['Vbgroup'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Usergroupid'); ?></th>
		<th><?php __('Title'); ?></th>
		<th><?php __('Description'); ?></th>
		<th><?php __('Usertitle'); ?></th>
		<th><?php __('Passwordexpires'); ?></th>
		<th><?php __('Passwordhistory'); ?></th>
		<th><?php __('Pmquota'); ?></th>
		<th><?php __('Pmsendmax'); ?></th>
		<th><?php __('Opentag'); ?></th>
		<th><?php __('Closetag'); ?></th>
		<th><?php __('Canoverride'); ?></th>
		<th><?php __('Ispublicgroup'); ?></th>
		<th><?php __('Forumpermissions'); ?></th>
		<th><?php __('Pmpermissions'); ?></th>
		<th><?php __('Calendarpermissions'); ?></th>
		<th><?php __('Wolpermissions'); ?></th>
		<th><?php __('Adminpermissions'); ?></th>
		<th><?php __('Genericpermissions'); ?></th>
		<th><?php __('Genericoptions'); ?></th>
		<th><?php __('Signaturepermissions'); ?></th>
		<th><?php __('Attachlimit'); ?></th>
		<th><?php __('Avatarmaxwidth'); ?></th>
		<th><?php __('Avatarmaxheight'); ?></th>
		<th><?php __('Avatarmaxsize'); ?></th>
		<th><?php __('Profilepicmaxwidth'); ?></th>
		<th><?php __('Profilepicmaxheight'); ?></th>
		<th><?php __('Profilepicmaxsize'); ?></th>
		<th><?php __('Sigpicmaxwidth'); ?></th>
		<th><?php __('Sigpicmaxheight'); ?></th>
		<th><?php __('Sigpicmaxsize'); ?></th>
		<th><?php __('Sigmaximages'); ?></th>
		<th><?php __('Sigmaxsizebbcode'); ?></th>
		<th><?php __('Sigmaxchars'); ?></th>
		<th><?php __('Sigmaxrawchars'); ?></th>
		<th><?php __('Sigmaxlines'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($group['Vbgroup'] as $vbgroup):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $vbgroup['usergroupid'];?></td>
			<td><?php echo $vbgroup['title'];?></td>
			<td><?php echo $vbgroup['description'];?></td>
			<td><?php echo $vbgroup['usertitle'];?></td>
			<td><?php echo $vbgroup['passwordexpires'];?></td>
			<td><?php echo $vbgroup['passwordhistory'];?></td>
			<td><?php echo $vbgroup['pmquota'];?></td>
			<td><?php echo $vbgroup['pmsendmax'];?></td>
			<td><?php echo $vbgroup['opentag'];?></td>
			<td><?php echo $vbgroup['closetag'];?></td>
			<td><?php echo $vbgroup['canoverride'];?></td>
			<td><?php echo $vbgroup['ispublicgroup'];?></td>
			<td><?php echo $vbgroup['forumpermissions'];?></td>
			<td><?php echo $vbgroup['pmpermissions'];?></td>
			<td><?php echo $vbgroup['calendarpermissions'];?></td>
			<td><?php echo $vbgroup['wolpermissions'];?></td>
			<td><?php echo $vbgroup['adminpermissions'];?></td>
			<td><?php echo $vbgroup['genericpermissions'];?></td>
			<td><?php echo $vbgroup['genericoptions'];?></td>
			<td><?php echo $vbgroup['signaturepermissions'];?></td>
			<td><?php echo $vbgroup['attachlimit'];?></td>
			<td><?php echo $vbgroup['avatarmaxwidth'];?></td>
			<td><?php echo $vbgroup['avatarmaxheight'];?></td>
			<td><?php echo $vbgroup['avatarmaxsize'];?></td>
			<td><?php echo $vbgroup['profilepicmaxwidth'];?></td>
			<td><?php echo $vbgroup['profilepicmaxheight'];?></td>
			<td><?php echo $vbgroup['profilepicmaxsize'];?></td>
			<td><?php echo $vbgroup['sigpicmaxwidth'];?></td>
			<td><?php echo $vbgroup['sigpicmaxheight'];?></td>
			<td><?php echo $vbgroup['sigpicmaxsize'];?></td>
			<td><?php echo $vbgroup['sigmaximages'];?></td>
			<td><?php echo $vbgroup['sigmaxsizebbcode'];?></td>
			<td><?php echo $vbgroup['sigmaxchars'];?></td>
			<td><?php echo $vbgroup['sigmaxrawchars'];?></td>
			<td><?php echo $vbgroup['sigmaxlines'];?></td>
			<td class="actions">
				<?php echo $html->link(__('View', true), array('controller'=> 'vbgroups', 'action'=>'view', $vbgroup['usergroupid'])); ?>
				<?php echo $html->link(__('Edit', true), array('controller'=> 'vbgroups', 'action'=>'edit', $vbgroup['usergroupid'])); ?>
				<?php echo $html->link(__('Delete', true), array('controller'=> 'vbgroups', 'action'=>'delete', $vbgroup['usergroupid']), null, sprintf(__('Are you sure you want to delete # %s?', true), $vbgroup['usergroupid'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $html->link(__('New Vbgroup', true), array('controller'=> 'vbgroups', 'action'=>'add'));?> </li>
		</ul>
	</div>
</div>
