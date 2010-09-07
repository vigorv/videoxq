<div class="faqItems view">
<h2><?php  __('FaqItem');?></h2>
    <dl><?php $i = 0; $class = ' class="altrow"';?>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Faq Category'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php echo $html->link($faqItem['FaqCategory']['title'], array('controller'=> 'faq_categories', 'action'=>'view', $faqItem['FaqCategory']['id'])); ?>
            &nbsp;
        </dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Title'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php echo $faqItem['FaqItem']['title']; ?>
            &nbsp;
        </dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Text'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php echo $faqItem['FaqItem']['text']; ?>
            &nbsp;
        </dd>
    </dl>
</div>
<div class="related">
    <h3><?php __('Related Faq Comments');?></h3>
    <?php if (!empty($faqComments)):?>
    <table cellpadding = "0" cellspacing = "0">
    <tr>
        <th><?php __('Username'); ?></th>
        <th><?php __('Text'); ?></th>
    </tr>
    <?php
        $i = 0;
        foreach ($faqComments as $faqComment):
            $class = null;
            if ($i++ % 2 == 0) {
                $class = ' class="altrow"';
            }
        ?>
        <tr<?php echo $class;?>>
            <td><?php echo $faqComment['User']['username'];?></td>
            <td><?php echo $faqComment['FaqComment']['text'];?></td>
        </tr>
    <?php endforeach; ?>
    </table>
<?php endif; ?>
    <div class="actions">
        <ul>
            <li><?php echo $html->link(__('New Faq Comment', true), array('controller'=> 'faq_comments', 'action'=>'add', $faqItem['FaqItem']['id']));?> </li>
        </ul>
    </div>
</div>
