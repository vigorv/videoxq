<div class="polls form">
<?php echo $form->create('Poll');?>
    <fieldset>
         <legend><?php __('Add Poll');?></legend>
    <?php
        echo $form->input('title');

        for ($i = 0; $i < 10; $i++)
        {
            echo $form->input('answers][' . $i, array('label' => 'Ответ #' . ($i+1)));
        }
        echo $form->input('votes');
        echo $form->input('total_votes');
        echo $form->input('active');
        echo $form->input('multiple');
    ?>
    </fieldset>
<?php echo $form->end('Submit');?>
</div>
<div class="actions">
    <ul>
        <li><?php echo $html->link(__('List Polls', true), array('action'=>'index'));?></li>
    </ul>
</div>
