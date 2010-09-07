<div class="filmVariant view">
<h2><?php  __('filmVariant');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $filmVariant['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Film Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $filmVariant['film_id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Video Type Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $filmVariant['video_type_id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Resolution'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $filmVariant['resolution']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Duration'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $filmVariant['duration']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($filmVariant['active']) { if ($i % 2 == 0) echo $class;?>><?php __('Active'); } ?></dt>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Modified'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $filmVariant['modified']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($filmVariant['flag_catalog']) { if ($i % 2 == 0) echo $class;?>><?php __('Flag Catalog'); } ?></dt>
	</dl>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Add', true), array('action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('Edit', true), array('action'=>'edit', $filmVariant['id'])); ?> </li>
		<li><?php echo $html->link(__('Back', true), array('controller' => 'media', 'action'=>'view', 'id' => $filmVariant["film_id"])); ?> </li>
	</ul>
</div>

<div class="related">
    <h3><?php __('Related Film Files');?></h3>
    <?php if (!empty($filmFiles)):?>
    <table cellpadding = "0" cellspacing = "0">
    <tr>
        <th><?php __('Id'); ?></th>
        <th><?php __('Film Variant Id'); ?></th>
        <th><?php __('File Name'); ?></th>
        <th><?php __('Size'); ?></th>
        <th><?php __('Dcpp Link'); ?></th>
        <th><?php __('Ed2k Link'); ?></th>
        <th><?php __('Server Id'); ?></th>
        <th class="actions"><?php __('Actions');?></th>
    </tr>
    <?php
        $i = 0;
        foreach ($filmFiles as $filmFile):
            $class = null;
            if ($i++ % 2 == 0) {
                $class = ' class="altrow"';
            }
        ?>
        <tr<?php echo $class;?>>
            <td><?php echo $filmFile['id'];?></td>
            <td><?php echo $filmFile['film_variant_id'];?></td>
            <td><?php echo $filmFile['file_name'];?></td>
            <td><?php echo $filmFile['size'];?></td>
            <td><?php echo $filmFile['dcpp_link'];?></td>
            <td><?php echo $filmFile['ed2k_link'];?></td>
            <td><?php echo $filmFile['server_id'];?></td>
            <td class="actions">
            <?php
            if ($filmVariant["flag_catalog"]) //ЕСЛИ ДОБАВЛЕН ИЗ КАТАЛОГА
            {
                echo $html->link(__('Edit', true), array('controller'=> 'film_files', 'action'=>'edit', $filmFile['id']));
                echo $html->link(__('Delete', true), array('controller'=> 'film_files', 'action'=>'delete', $filmFile['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $filmFile['id']));
			}
			?>
            </td>
        </tr>
    <?php endforeach; ?>
    </table>
<?php endif; ?>

<?php
	if ($filmVariant["flag_catalog"]) //ЕСЛИ ДОБАВЛЕН ИЗ КАТАЛОГА
	{
?>
    <div class="actions">
        <ul>
            <li><?php echo $html->link(__('New Film File', true), array('controller'=> 'film_files', 'action'=>'add'));?> </li>
        </ul>
    </div>
</div>
<?php
	}
?>

<div class="related">
    <h3><?php __('Related Film Tracks');?></h3>
    <?php if (!empty($filmTrack["id"])):?>
    <table cellpadding = "0" cellspacing = "0">
    <tr>
        <th><?php __('Id'); ?></th>
        <th><?php __('Film Variant Id'); ?></th>
        <th><?php __('Language Id'); ?></th>
        <th><?php __('Translation Id'); ?></th>
        <th><?php __('Audio Info'); ?></th>
        <th class="actions"><?php __('Actions');?></th>
    </tr>
    <?php
        $i = 0;
        $class = null;
        ?>
        <tr<?php echo $class;?>>
            <td><?php echo $filmTrack['id'];?></td>
            <td><?php echo $filmTrack['film_variant_id'];?></td>
            <td><?php echo $filmTrack['language_id'];?></td>
            <td><?php echo $filmTrack['translation_id'];?></td>
            <td><?php echo $filmTrack['audio_info'];?></td>
            <td class="actions">
            <?php
            if ($filmVariant["flag_catalog"]) //ЕСЛИ ДОБАВЛЕН ИЗ КАТАЛОГА
            {
                echo $html->link(__('Edit', true), array('controller'=> 'tracks', 'action'=>'edit', $filmTrack['id']));
                echo $html->link(__('Delete', true), array('controller'=> 'tracks', 'action'=>'delete', $filmTrack['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $filmTrack['id']));
			}
			?>
            </td>
        </tr>
    </table>
<?php endif; ?>

<?php
	if ($filmVariant["flag_catalog"]) //ЕСЛИ ДОБАВЛЕН ИЗ КАТАЛОГА
	{
?>
    <div class="actions">
        <ul>
            <li><?php echo $html->link(__('New Film Track', true), array('controller'=> 'tracks', 'action'=>'add'));?> </li>
        </ul>
    </div>
</div>
<?php
	}
