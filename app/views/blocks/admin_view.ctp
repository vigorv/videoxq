<div class="blocks view">
<h2><?php  __('Block');?></h2>
    <dl><?php $i = 0; $class = ' class="altrow"';?>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php echo $block['Block']['id']; ?>
            &nbsp;
        </dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Title'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php echo $block['Block']['title']; ?>
            &nbsp;
        </dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Controller'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php echo $block['Block']['controller']; ?>
            &nbsp;
        </dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Method'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php echo $block['Block']['method']; ?>
            &nbsp;
        </dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Element'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php echo $block['Block']['element']; ?>
            &nbsp;
        </dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Arguments'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php echo $block['Block']['arguments']; ?>
            &nbsp;
        </dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Content'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php echo $block['Block']['content']; ?>
            &nbsp;
        </dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Type'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php echo $block['Block']['type']; ?>
            &nbsp;
        </dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Position'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php echo $block['Block']['position']; ?>
            &nbsp;
        </dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Order'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php echo $block['Block']['order']; ?>
            &nbsp;
        </dd>
    </dl>
</div>
<div class="actions">
    <ul>
        <li><?php echo $html->link(__('Edit Block', true), array('action'=>'edit', $block['Block']['id'])); ?> </li>
        <li><?php echo $html->link(__('Delete Block', true), array('action'=>'delete', $block['Block']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $block['Block']['id'])); ?> </li>
        <li><?php echo $html->link(__('List Blocks', true), array('action'=>'index')); ?> </li>
        <li><?php echo $html->link(__('New Block', true), array('action'=>'add')); ?> </li>
    </ul>
</div>
