<div class="faqItems form">
<?php echo $form->create('FaqItem');?>
	<fieldset>
 		<legend><?php __('Add FaqItem');?></legend>
	<?php
		echo $form->input('faq_category_id');
		echo $form->input('title');
		echo $form->input('text');
		echo $form->input('num_comments');
	?>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('List FaqItems', true), array('action'=>'index'));?></li>
		<li><?php echo $html->link(__('List Faq Categories', true), array('controller'=> 'faq_categories', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Faq Category', true), array('controller'=> 'faq_categories', 'action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List Faq Comments', true), array('controller'=> 'faq_comments', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Faq Comment', true), array('controller'=> 'faq_comments', 'action'=>'add')); ?> </li>
	</ul>
</div>
