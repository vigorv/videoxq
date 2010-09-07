<div class="adverts index">
<h2><?php __('Adverts');?></h2>
<p>
<?php
echo $paginator->counter(array(
'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
));
?></p>
<table cellpadding="0" cellspacing="0">
<tr>
    <th><?php echo $paginator->sort('id');?></th>
    <th><?php echo $paginator->sort('user_id');?></th>
    <th><?php echo $paginator->sort('do_category_id');?></th>
    <th><?php echo $paginator->sort('username');?></th>
    <th><?php echo $paginator->sort('title');?></th>
    <th><?php echo $paginator->sort('text');?></th>
    <th><?php echo $paginator->sort('phone');?></th>
    <th><?php echo $paginator->sort('email');?></th>
    <th><?php echo $paginator->sort('icq');?></th>
    <th><?php echo $paginator->sort('created');?></th>
    <th><?php echo $paginator->sort('modified');?></th>
    <th class="actions"><?php __('Actions');?></th>
</tr>
<?php
$i = 0;
foreach ($adverts as $advert):
    $class = null;
    if ($i++ % 2 == 0) {
        $class = ' class="altrow"';
    }
?>
    <tr<?php echo $class;?>>
        <td>
            <?php echo $advert['Advert']['id']; ?>
        </td>
        <td>
            <?php echo $html->link($advert['User']['username'], array('controller'=> 'users', 'action'=>'view', $advert['User']['userid'])); ?>
        </td>
        <td>
            <?php echo $html->link($advert['DoCategory']['title'], array('controller'=> 'do', 'action'=>'view', $advert['DoCategory']['id'])); ?>
        </td>
        <td>
            <?php echo $advert['Advert']['username']; ?>
        </td>
        <td>
            <?php echo $advert['Advert']['title']; ?>
        </td>
        <td>
            <?php echo $advert['Advert']['text']; ?>
        </td>
        <td>
            <?php echo $advert['Advert']['phone']; ?>
        </td>
        <td>
            <?php echo $advert['Advert']['email']; ?>
        </td>
        <td>
            <?php echo $advert['Advert']['icq']; ?>
        </td>
        <td>
            <?php echo $advert['Advert']['created']; ?>
        </td>
        <td>
            <?php echo $advert['Advert']['modified']; ?>
        </td>
        <td class="actions">
            <?php echo $html->link(__('View', true), array('action'=>'view', $advert['Advert']['id'])); ?>
            <?php echo $html->link(__('Edit', true), array('action'=>'edit', $advert['Advert']['id'])); ?>
            <?php echo $html->link(__('Delete', true), array('action'=>'delete', $advert['Advert']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $advert['Advert']['id'])); ?>
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
        <li><?php echo $html->link(__('New Advert', true), array('action'=>'add')); ?></li>
        <li><?php echo $html->link(__('List Users', true), array('controller'=> 'users', 'action'=>'index')); ?> </li>
        <li><?php echo $html->link(__('New User', true), array('controller'=> 'users', 'action'=>'add')); ?> </li>
        <li><?php echo $html->link(__('List Do Categories', true), array('controller'=> 'do', 'action'=>'index')); ?> </li>
        <li><?php echo $html->link(__('New Do Category', true), array('controller'=> 'do', 'action'=>'add')); ?> </li>
    </ul>
</div>
