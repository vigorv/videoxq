<div class="articleCategories index">
<h2><?php __('ArticleCategories');?></h2>
<p>
<?php
echo $paginator->counter(array(
'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
));
?></p>
<table cellpadding="0" cellspacing="0">
<tr>
    <th><?php echo $paginator->sort('id');?></th>
    <th><?php echo $paginator->sort('parent_id');?></th>
    <th><?php echo $paginator->sort('title');?></th>
    <th class="actions"><?php __('Actions');?></th>
</tr>
<?php
$i = 0;
foreach ($articleCategories as $articleCategory):
    $class = null;
    if ($i++ % 2 == 0) {
        $class = ' class="altrow"';
    }
?>
    <tr<?php echo $class;?>>
        <td>
            <?php echo $articleCategory['ArticleCategory']['id']; ?>
        </td>
        <td>
            <?php echo $html->link($articleCategory['ArticleCategoryParent']['title'], array('controller'=> 'article_categories', 'action'=>'view', $articleCategory['ArticleCategoryParent']['id'])); ?>
        </td>
        <td>
            <?php echo $articleCategory['ArticleCategory']['title']; ?>
        </td>
        <td class="actions">
            <?php echo $html->link(__('View', true), array('action'=>'view', $articleCategory['ArticleCategory']['id'])); ?>
            <?php echo $html->link(__('Edit', true), array('action'=>'edit', $articleCategory['ArticleCategory']['id'])); ?>
            <?php echo $html->link(__('Delete', true), array('action'=>'delete', $articleCategory['ArticleCategory']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $articleCategory['ArticleCategory']['id'])); ?>
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
        <li><?php echo $html->link(__('New ArticleCategory', true), array('action'=>'add')); ?></li>
        <li><?php echo $html->link(__('List Article Categories', true), array('controller'=> 'article_categories', 'action'=>'index')); ?> </li>
        <li><?php echo $html->link(__('New Article Category Parent', true), array('controller'=> 'article_categories', 'action'=>'add')); ?> </li>
        <li><?php echo $html->link(__('List Articles', true), array('controller'=> 'articles', 'action'=>'index')); ?> </li>
        <li><?php echo $html->link(__('New Article', true), array('controller'=> 'articles', 'action'=>'add')); ?> </li>
    </ul>
</div>
