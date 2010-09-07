<div class="NewsCategories index">
<h2><?php __('NewsCategories');?></h2>
<p>
<?php
echo $paginator->counter(array(
'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
));
?></p>
<table cellpadding="0" cellspacing="0">
<tr>
    <th><?php echo $paginator->sort('id');?></th>
    <th><?php __('Faq Category Parent')?></th>
    <th><?php echo $paginator->sort('title');?></th>
</tr>
<?php
$i = 0;
foreach ($NewsCategories as $NewsCategory):
    $class = null;
    if ($i++ % 2 == 0) {
        $class = ' class="altrow"';
    }
?>
    <tr<?php echo $class;?>>
        <td>
            <?php echo $NewsCategory['FaqCategory']['id']; ?>
        </td>
        <td>
            <?php echo $html->link($NewsCategory['FaqCategoryParent']['title'], array('controller'=> 'news_categories', 'action'=>'view', $NewsCategory['FaqCategoryParent']['id'])); ?>
        </td>
        <td>
            <?php echo $html->link($NewsCategory['FaqCategory']['title'], array('controller'=> 'news_categories', 'action'=>'view', $NewsCategory['FaqCategory']['id'])); ?>
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
