<div class="faqCategories view">
<h2><?php  __('FaqCategory');?></h2>
    <dl><?php $i = 0; $class = ' class="altrow"';?>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php echo $faqCategory['FaqCategory']['id']; ?>
            &nbsp;
        </dd>
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
<div class="actions">
    <ul>
        <li><?php echo $html->link(__('Edit FaqCategory', true), array('action'=>'edit', $faqCategory['FaqCategory']['id'])); ?> </li>
        <li><?php echo $html->link(__('Delete FaqCategory', true), array('action'=>'delete', $faqCategory['FaqCategory']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $faqCategory['FaqCategory']['id'])); ?> </li>
        <li><?php echo $html->link(__('List FaqCategories', true), array('action'=>'index')); ?> </li>
        <li><?php echo $html->link(__('New FaqCategory', true), array('action'=>'add')); ?> </li>
        <li><?php echo $html->link(__('List Faq Items', true), array('controller'=> 'faq_items', 'action'=>'index')); ?> </li>
        <li><?php echo $html->link(__('New Faq Item', true), array('controller'=> 'faq_items', 'action'=>'add')); ?> </li>
    </ul>
</div>
<div class="related">
    <h3><?php __('Related Faq Items');?></h3>
    <?php if (!empty($faqCategory['FaqItem'])):?>
    <table cellpadding = "0" cellspacing = "0">
    <tr>
        <th><?php __('Id'); ?></th>
        <th><?php __('Faq Category Id'); ?></th>
        <th><?php __('Title'); ?></th>
        <th><?php __('Text'); ?></th>
        <th><?php __('Num Comments'); ?></th>
        <th class="actions"><?php __('Actions');?></th>
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
            <td><?php echo $faqItem['id'];?></td>
            <td><?php echo $faqItem['faq_category_id'];?></td>
            <td><?php echo $faqItem['title'];?></td>
            <td><?php echo $faqItem['text'];?></td>
            <td><?php echo $faqItem['num_comments'];?></td>
            <td class="actions">
                <?php echo $html->link(__('View', true), array('controller'=> 'faq_items', 'action'=>'view', $faqItem['id'])); ?>
                <?php echo $html->link(__('Edit', true), array('controller'=> 'faq_items', 'action'=>'edit', $faqItem['id'])); ?>
                <?php echo $html->link(__('Delete', true), array('controller'=> 'faq_items', 'action'=>'delete', $faqItem['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $faqItem['id'])); ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </table>
<?php endif; ?>

    <div class="actions">
        <ul>
            <li><?php echo $html->link(__('New Faq Item', true), array('controller'=> 'faq_items', 'action'=>'add'));?> </li>
        </ul>
    </div>
</div>
