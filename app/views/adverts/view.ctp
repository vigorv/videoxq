<div class="adverts view">
<h2><?php  __('Advert');?></h2>
    <dl><?php $i = 0; $class = ' class="altrow"';?>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php echo $advert['Advert']['id']; ?>
            &nbsp;
        </dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('User'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php echo $html->link($advert['User']['username'], array('controller'=> 'users', 'action'=>'view', $advert['User']['userid'])); ?>
            &nbsp;
        </dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Do Category'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php echo $html->link($advert['DoCategory']['title'], array('controller'=> 'do', 'action'=>'view', $advert['DoCategory']['id'])); ?>
            &nbsp;
        </dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Username'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php echo $advert['Advert']['username']; ?>
            &nbsp;
        </dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Title'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php echo $advert['Advert']['title']; ?>
            &nbsp;
        </dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Text'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php echo $advert['Advert']['text']; ?>
            &nbsp;
        </dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Phone'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php echo $advert['Advert']['phone']; ?>
            &nbsp;
        </dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Email'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php echo $advert['Advert']['email']; ?>
            &nbsp;
        </dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Icq'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php echo $advert['Advert']['icq']; ?>
            &nbsp;
        </dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Created'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php echo $advert['Advert']['created']; ?>
            &nbsp;
        </dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Modified'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php echo $advert['Advert']['modified']; ?>
            &nbsp;
        </dd>
    </dl>
</div>
<div class="actions">
    <ul>
        <li><?php echo $html->link(__('Edit Advert', true), array('action'=>'edit', $advert['Advert']['id'])); ?> </li>
        <li><?php echo $html->link(__('Delete Advert', true), array('action'=>'delete', $advert['Advert']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $advert['Advert']['id'])); ?> </li>
        <li><?php echo $html->link(__('List Adverts', true), array('action'=>'index')); ?> </li>
        <li><?php echo $html->link(__('New Advert', true), array('action'=>'add')); ?> </li>
        <li><?php echo $html->link(__('List Users', true), array('controller'=> 'users', 'action'=>'index')); ?> </li>
        <li><?php echo $html->link(__('New User', true), array('controller'=> 'users', 'action'=>'add')); ?> </li>
        <li><?php echo $html->link(__('List Do Categories', true), array('controller'=> 'do', 'action'=>'index')); ?> </li>
        <li><?php echo $html->link(__('New Do Category', true), array('controller'=> 'do', 'action'=>'add')); ?> </li>
    </ul>
</div>
