<div class="related">
    <h3><?php __('Related Film Variants');?></h3>
<?php
echo $paginator->counter(array(
'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
));
?></p>
    
    <table cellpadding = "0" cellspacing = "0">
    <tr>
        <th><?php __('Id'); ?></th>
        <th><?php __('Film Id'); ?></th>
        <th><?php __('Video Type Id'); ?></th>
        <th><?php __('Resolution'); ?></th>
        <th><?php __('Duration'); ?></th>
        <th><?php __('Active'); ?></th>
        <th><?php __('Created'); ?></th>
        <th><?php __('Modified'); ?></th>
        <th class="actions"><?php __('Actions');?></th>
    </tr>
    <?php
        $i = 0;
        //pr($FilmVariants);
        //die();
        foreach ($FilmVariants as $filmVariant):
        $filmVariant=$filmVariant['FilmVariant'];
            $class = null;
            if ($i++ % 2 == 0) {
                $class = ' class="altrow"';
            }
        ?>
        <tr<?php echo $class;?>>
            <td><?php echo $filmVariant['id'];?></td>
            <td><?php echo $filmVariant['film_id'];?></td>
            <td><?php echo $filmVariant['video_type_id'];?></td>
            <td><?php echo $filmVariant['resolution'];?></td>
            <td><?php echo $filmVariant['duration'];?></td>
            <td><?php echo $filmVariant['active'];?></td>
            <td><?php echo $filmVariant['created'];?></td>
            <td><?php echo $filmVariant['modified'];?></td>
            <td class="actions">
                <?php echo $html->link(__('View', true), array('controller'=> 'film_variants', 'action'=>'view', $filmVariant['id'])); ?>
                <?php echo $html->link(__('Edit', true), array('controller'=> 'film_variants', 'action'=>'edit', $filmVariant['id'])); ?>
                <?php echo $html->link(__('Delete', true), array('controller'=> 'film_variants', 'action'=>'delete', $filmVariant['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $filmVariant['id'])); ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </table>
<div class="paging">
    <?php echo $paginator->prev('<< '.__('previous', true), array(), null, array('class'=>'disabled'));?>
 | 	<?php echo $paginator->numbers();?>
    <?php echo $paginator->next(__('next', true).' >>', array(), null, array('class'=>'disabled'));?>
</div>
    <div class="actions">
        <ul>
            <li><?php echo $html->link(__('New Film Variant', true), array('controller'=> 'film_variants', 'action'=>'add'));?> </li>
        </ul>
    </div>
</div>
