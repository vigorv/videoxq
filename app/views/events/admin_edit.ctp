<div class="events form">
<!-- tinyMCE -->
    <script language="javascript" type="text/javascript" src="/js/tiny_mce/tiny_mce.js"></script>
<script language="javascript" type="text/javascript" src="/js/mce.js"></script>
<!-- /tinyMCE -->

<?php echo $form->create('Event', array('type' => 'file'));?>
	<fieldset>
 		<legend><?php __('Edit Event');?></legend>
	<?php
		echo $form->error('id');
		echo $form->input('id');
		echo $form->input('event_category_id');
		echo $form->input('title');
		echo $form->input('notice');
		echo $form->input('text');
		echo $form->input('tags');
		//echo $form->error('access');
		echo $form->input('access',array('options' => array('public'=>'public','private'=>'private')));
//        echo $form->input('filename', array('class' => 'textInput', 'label' => false, 'type' => 'file', 'id' => 'ImageUpload',
//                                 'between' => '<br>', 'error' => false, 'div' => array('tag' => 'p', 'class' => '')));
		
	?>
	</fieldset>
<?php //echo $this->element('Attachment');?>
<?php echo $form->end('Готово');?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Delete', true), array('action'=>'delete', $form->value('Event.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $form->value('Event.id'))); ?></li>
		<li><?php echo $html->link(__('List Events', true), array('action'=>'index'));?></li>
	</ul>
</div>
