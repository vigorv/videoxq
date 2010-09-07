<div class="doCategories view">
<h2><?php  __('DoCategory');?></h2>
    <dl><?php $i = 0; $class = ' class="altrow"';?>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php echo $doCategory['DoCategory']['id']; ?>
            &nbsp;
        </dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Parent'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php echo $html->link($doCategory['Parent']['title'], array('controller'=> 'do', 'action'=>'view', $doCategory['Parent']['id'])); ?>
            &nbsp;
        </dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Title'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php echo $doCategory['DoCategory']['title']; ?>
            &nbsp;
        </dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Url'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php echo $doCategory['DoCategory']['url']; ?>
            &nbsp;
        </dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Lft'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php echo $doCategory['DoCategory']['lft']; ?>
            &nbsp;
        </dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Rght'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php echo $doCategory['DoCategory']['rght']; ?>
            &nbsp;
        </dd>
    </dl>
</div>
<div class="actions">
    <ul>
        <li><?php echo $html->link(__('Edit DoCategory', true), array('action'=>'edit', $doCategory['DoCategory']['id'])); ?> </li>
        <li><?php echo $html->link(__('Delete DoCategory', true), array('action'=>'delete', $doCategory['DoCategory']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $doCategory['DoCategory']['id'])); ?> </li>
        <li><?php echo $html->link(__('List DoCategories', true), array('action'=>'index')); ?> </li>
        <li><?php echo $html->link(__('New DoCategory', true), array('action'=>'add')); ?> </li>
        <li><?php echo $html->link(__('List Do Categories', true), array('controller'=> 'do', 'action'=>'index')); ?> </li>
        <li><?php echo $html->link(__('New Parent', true), array('controller'=> 'do', 'action'=>'add')); ?> </li>
        <li><?php echo $html->link(__('List Adverts', true), array('controller'=> 'adverts', 'action'=>'index')); ?> </li>
        <li><?php echo $html->link(__('New Advert', true), array('controller'=> 'adverts', 'action'=>'add')); ?> </li>
    </ul>
</div>
<div class="related">
    <h3><?php __('Related Adverts');?></h3>
    <?php if (!empty($doCategory['Advert'])):?>
    <table cellpadding = "0" cellspacing = "0">
    <tr>
        <th><?php __('Id'); ?></th>
        <th><?php __('User Id'); ?></th>
        <th><?php __('Do Category Id'); ?></th>
        <th><?php __('Username'); ?></th>
        <th><?php __('Title'); ?></th>
        <th><?php __('Text'); ?></th>
        <th><?php __('Phone'); ?></th>
        <th><?php __('Email'); ?></th>
        <th><?php __('Icq'); ?></th>
        <th><?php __('Created'); ?></th>
        <th><?php __('Modified'); ?></th>
        <th class="actions"><?php __('Actions');?></th>
    </tr>
    <?php
        $i = 0;
        foreach ($doCategory['Advert'] as $advert):
            $class = null;
            if ($i++ % 2 == 0) {
                $class = ' class="altrow"';
            }
        ?>
        <tr<?php echo $class;?>>
            <td><?php echo $advert['id'];?></td>
            <td><?php echo $advert['user_id'];?></td>
            <td><?php echo $advert['do_category_id'];?></td>
            <td><?php echo $advert['username'];?></td>
            <td><?php echo $advert['title'];?></td>
            <td><?php echo $advert['text'];?></td>
            <td><?php echo $advert['phone'];?></td>
            <td><?php echo $advert['email'];?></td>
            <td><?php echo $advert['icq'];?></td>
            <td><?php echo $advert['created'];?></td>
            <td><?php echo $advert['modified'];?></td>
            <td class="actions">
                <?php echo $html->link(__('View', true), array('controller'=> 'adverts', 'action'=>'view', $advert['id'])); ?>
                <?php echo $html->link(__('Edit', true), array('controller'=> 'adverts', 'action'=>'edit', $advert['id'])); ?>
                <?php echo $html->link(__('Delete', true), array('controller'=> 'adverts', 'action'=>'delete', $advert['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $advert['id'])); ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </table>
<?php endif; ?>

    <div class="actions">
        <ul>
            <li><?php echo $html->link(__('New Advert', true), array('controller'=> 'adverts', 'action'=>'add'));?> </li>
        </ul>
    </div>
</div>
