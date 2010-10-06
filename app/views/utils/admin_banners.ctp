<table cellpadding="0" cellspacing="0">
<tr>
    <th><?php echo $paginator->sort('name');?></th>
    <th><?php echo $paginator->sort('place');?></th>
    <th><?php echo $paginator->sort('start');?></th>
    <th><?php echo $paginator->sort('stop');?></th>
    <th>actions</th>
</tr>
<?php
$i = 0;
foreach ($banners as $banner):
    $class = null;
    if ($i++ % 2 == 0) {
        $class = ' class="altrow"';
    }
?>
    <tr <?php echo $class;?>>
        <td>
            <?php echo $banner['Banner']['name']; ?>
        </td>
        <td>
            <?php
            	echo $banner['Banner']['place'];
            	if (!empty($banner['Banner']['is_webstream'])) echo ' для WebStream';
            	if (!empty($banner['Banner']['is_internet'])) echo ' для Internet';
            ?>
        </td>
        <td>
            <?php if ($banner['Banner']['forever']) echo 'бессрочный'; else echo $banner['Banner']['start']; ?>
        </td>
        <td>
            <?php if ($banner['Banner']['forever']) echo 'бессрочный'; else echo $banner['Banner']['stop']; ?>
        </td>
        <td class="actions">
            <?php echo $html->link(__('Edit', true), array('action'=>'banners_edit', $banner['Banner']['id'])); ?>
            <?php echo $html->link(__('Delete', true), array('action'=>'banners_delete', $banner['Banner']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $banner['Banner']['id'])); ?>
        </td>
    </tr>
<?php endforeach; ?>
</table>
<div class="actions">
    <ul>
        <li><?php echo $html->link(__('List Banners', true), array('action'=>'banners'));?></li>
        <li><?php echo $html->link(__('New Banner', true), array('action'=>'banners_edit')); ?> </li>
    </ul>
</div>
