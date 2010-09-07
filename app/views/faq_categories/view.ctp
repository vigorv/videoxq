<div class="faqCategories view">
<h2><?php  __('FaqCategory');?></h2>
    <dl><?php $i = 0; $class = ' class="altrow"';?>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Faq Category Parent'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php echo $html->link($faqCategory['FaqCategoryParent']['title'], array('controller'=> 'faq_categories', 'action'=>'view', $faqCategory['FaqCategoryParent']['id'])); ?>
            &nbsp;
        </dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Title'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php echo $faqCategory['FaqCategory']['title']; ?>
            &nbsp;
        </dd>
    </dl>
</div>
<div class="related">
    <h3><?php __('Related Faq Items');?></h3>
    <?php if (!empty($faqCategory['FaqItem'])):?>
    <table cellpadding = "0" cellspacing = "0">
    <tr>
        <th><?php __('Title'); ?></th>
        <th><?php __('Num Comments'); ?></th>
    </tr>
    <?php
        $i = 0;
        foreach ($faqCategory['FaqItem'] as $faqItem):
            $class = null;
            if ($i++ % 2 == 0) {
                $class = ' class="altrow"';
            }
        ?>
        <tr<?php echo $class;?>>
            <td><?php echo $html->link($faqItem['title'], array('controller'=> 'faq_items', 'action'=>'view', $faqItem['id']));?></td>
            <td><?php echo $faqItem['num_comments'];?></td>
        </tr>
    <?php endforeach; ?>
    </table>
<?php endif; ?>
</div>
