<div class="users form">
<script type="text/javascript">
<!--
tinyMCE = null;
-->
</script>
<?php echo $form->create('Banner', array('url' => '/admin/utils/banners_edit'));?>
    <fieldset>
         <legend><?php (empty($this->data['Banner']['id'])) ? __('Add Banner') : __('Edit Banner');?></legend>
    <?php
        echo $form->input('id');
        echo $form->input('name');
        echo $form->input('place');
        echo $form->input('start');
        echo $form->input('stop');
        echo $form->input('forever');
        echo $form->input('fixed');
        echo $form->input('srt');
        echo $form->input('code');
        echo $form->input('tail');
        echo $form->input('priority');
    ?>
    </fieldset>
<?php echo $form->end('Submit');?>
</div>
<div class="actions">
    <ul>
        <li><?php echo $html->link(__('List Banners', true), array('action'=>'banners'));?></li>
        <li><?php echo $html->link(__('New Banner', true), array('action'=>'banners_edit')); ?> </li>
    </ul>
</div>
