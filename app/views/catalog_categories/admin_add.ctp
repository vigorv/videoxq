<div class="catalogCategories form">
<?php echo $form->create('CatalogCategory');?>
	<fieldset>
 		<legend><?php __('Add CatalogCategory');?></legend>
	<?php
		echo $form->input('parent_id');
		echo $form->input('title');
		echo $form->input('lft');
		echo $form->input('rght');
	?>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('List CatalogCategories', true), array('action'=>'index'));?></li>
		<li><?php echo $html->link(__('List Catalog Categories', true), array('controller'=> 'catalog_categories', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Catalog Category Parent', true), array('controller'=> 'catalog_categories', 'action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List Faq Items', true), array('controller'=> 'faq_items', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Catalog Item', true), array('controller'=> 'faq_items', 'action'=>'add')); ?> </li>
	</ul>
</div>
