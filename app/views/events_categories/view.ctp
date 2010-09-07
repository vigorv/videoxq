<div class="faqCategories view">
<h2><?php  __('NewsCategory');?></h2>
    <dl><?php $i = 0; $class = ' class="altrow"';?>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Faq Category Parent'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php echo $html->link($NewsCategory['NewsCategoryParent']['title'], array('controller'=> 'faq_categories', 'action'=>'view', $NewsCategory['NewsCategoryParent']['id'])); ?>
            &nbsp;
        </dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Title'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php echo $NewsCategory['NewsCategory']['title']; ?>
            &nbsp;
        </dd>
    </dl>
</div>
<div class="related">
    <h3><?php __('Related Faq Items');?></h3>
    <?php if (!empty($NewsCategory['News'])):?>
    <table cellpadding = "0" cellspacing = "0">
    <tr>
        <th><?php __('Title'); ?></th>
        <th><?php __('Num Comments'); ?></th>
    </tr>
    <?php
        $i = 0;
        foreach ($NewsCategory['News'] as $News):
            $class = null;
            if ($i++ % 2 == 0) {
                $class = ' class="altrow"';
            }
        ?>
        <tr<?php echo $class;?>>
            <td><?php echo $html->link($News['title'], array('controller'=> 'events', 'action'=>'view', $News['id']));?></td>
            <td><?php echo $News['num_comments'];?></td>
        </tr>
    <?php endforeach; ?>
    </table>
<?php endif; ?>
</div>
