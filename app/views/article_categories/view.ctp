<div class="articleCategories view">
<h2><?php  __('ArticleCategory');?></h2>
    <dl><?php $i = 0; $class = ' class="altrow"';?>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php echo $articleCategory['ArticleCategory']['id']; ?>
            &nbsp;
        </dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Article Category Parent'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php echo $html->link($articleCategory['ArticleCategoryParent']['title'], array('controller'=> 'article_categories', 'action'=>'view', $articleCategory['ArticleCategoryParent']['id'])); ?>
            &nbsp;
        </dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Title'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php echo $articleCategory['ArticleCategory']['title']; ?>
            &nbsp;
        </dd>
    </dl>
</div>
<div class="actions">
    <ul>
        <li><?php echo $html->link(__('Edit ArticleCategory', true), array('action'=>'edit', $articleCategory['ArticleCategory']['id'])); ?> </li>
        <li><?php echo $html->link(__('Delete ArticleCategory', true), array('action'=>'delete', $articleCategory['ArticleCategory']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $articleCategory['ArticleCategory']['id'])); ?> </li>
        <li><?php echo $html->link(__('List ArticleCategories', true), array('action'=>'index')); ?> </li>
        <li><?php echo $html->link(__('New ArticleCategory', true), array('action'=>'add')); ?> </li>
        <li><?php echo $html->link(__('List Article Categories', true), array('controller'=> 'article_categories', 'action'=>'index')); ?> </li>
        <li><?php echo $html->link(__('New Article Category Parent', true), array('controller'=> 'article_categories', 'action'=>'add')); ?> </li>
        <li><?php echo $html->link(__('List Articles', true), array('controller'=> 'articles', 'action'=>'index')); ?> </li>
        <li><?php echo $html->link(__('New Article', true), array('controller'=> 'articles', 'action'=>'add')); ?> </li>
    </ul>
</div>
<div class="related">
    <h3><?php __('Related Articles');?></h3>
    <?php if (!empty($articleCategory['Article'])):?>
    <table cellpadding = "0" cellspacing = "0">
    <tr>
        <th><?php __('Id'); ?></th>
        <th><?php __('User Id'); ?></th>
        <th><?php __('Article Category Id'); ?></th>
        <th><?php __('Title'); ?></th>
        <th><?php __('Text'); ?></th>
        <th><?php __('Created'); ?></th>
        <th><?php __('Modified'); ?></th>
        <th class="actions"><?php __('Actions');?></th>
    </tr>
    <?php
        $i = 0;
        foreach ($articleCategory['Article'] as $article):
            $class = null;
            if ($i++ % 2 == 0) {
                $class = ' class="altrow"';
            }
        ?>
        <tr<?php echo $class;?>>
            <td><?php echo $article['id'];?></td>
            <td><?php echo $article['user_id'];?></td>
            <td><?php echo $article['article_category_id'];?></td>
            <td><?php echo $article['title'];?></td>
            <td><?php echo $article['text'];?></td>
            <td><?php echo $article['created'];?></td>
            <td><?php echo $article['modified'];?></td>
            <td class="actions">
                <?php echo $html->link(__('View', true), array('controller'=> 'articles', 'action'=>'view', $article['id'])); ?>
                <?php echo $html->link(__('Edit', true), array('controller'=> 'articles', 'action'=>'edit', $article['id'])); ?>
                <?php echo $html->link(__('Delete', true), array('controller'=> 'articles', 'action'=>'delete', $article['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $article['id'])); ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </table>
<?php endif; ?>

    <div class="actions">
        <ul>
            <li><?php echo $html->link(__('New Article', true), array('controller'=> 'articles', 'action'=>'add'));?> </li>
        </ul>
    </div>
</div>
