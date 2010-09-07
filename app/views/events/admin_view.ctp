<div class="events view">
<h2><?php  __('Event');?></h2>
    <dl><?php $i = 0; $class = ' class="altrow"';?>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php echo $event['Event']['id']; ?>
            &nbsp;
        </dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Title'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php echo $event['Event']['title']; ?>
            &nbsp;
        </dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Text'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php echo $event['Event']['text']; ?>
            &nbsp;
        </dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Created'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php echo $event['Event']['created']; ?>
            &nbsp;
        </dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Modified'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php echo $event['Event']['modified']; ?>
            &nbsp;
        </dd>
    </dl>
</div>
<div class="related">
    <h3><?php echo sprintf(__('Related %s', true), __('Attachments', true));?></h3>
    <?php if (!empty($event['Attachment'])):?>
    <table cellpadding = "0" cellspacing = "0">
    <tr>
        <th><?php __('Id') ?></th>
        <th><?php __('Dirname') ?></th>
        <th><?php __('Basename') ?></th>
        <th><?php __('Checksum') ?></th>
        <th><?php __('Mimetype') ?></th>
        <th><?php __('Created') ?></th>
        <th><?php __('Modified') ?></th>
        <th class="actions"><?php __('Actions');?></th>
    </tr>

    <?php
        $i = 0;

        //foreach ($event['Attachment'] as $attachment):
         $attachment=$event['Attachment'];
         //pr($attachment);
            $class = null;
            if ($i++ % 2 == 0) {
                $class = ' class="altrow"';
            }
        ?>
        <tr<?php echo $class;?>>
            <td><?php echo $attachment['id'];?></td>
            <td><?php echo $attachment['dir'];?></td>
            <td><?php echo $attachment['filename'];?></td>
            <td><?php echo $attachment['filesize'];?></td>
            <td><?php echo $attachment['mimetype'];?></td>
            <td><?php echo $attachment['created'];?></td>
            <td><?php echo $attachment['modified'];?></td>
            <td class="actions">
                <?php echo $html->link(__('View', true), array('controller'=>'files','action'=> ''.$attachment['dir']."/".$attachment['filename'], Configure::read('Routing.admin') => false)) ?>
                <?php //echo $html->link(__('Delete', true), $example['Event']['id'].'/attachments/delete/'.$attachment['basename'], null, sprintf(__('Are you sure you want to delete # %s?', true), $attachment['id'])); ?>
            </td>
        </tr>

    <?php //endforeach; ?>
    </table>
    <?php endif; ?>

</div>

<div class="actions">
    <ul>
        <li><?php echo $html->link(__('Edit Event', true), array('action'=>'edit', $event['Event']['id'])); ?> </li>
        <li><?php echo $html->link(__('Delete Event', true), array('action'=>'delete', $event['Event']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $event['Event']['id'])); ?> </li>
        <li><?php echo $html->link(__('List Events', true), array('action'=>'index')); ?> </li>
        <li><?php echo $html->link(__('New Event', true), array('action'=>'add')); ?> </li>
    </ul>
</div>
