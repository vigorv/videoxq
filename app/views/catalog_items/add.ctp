<div class="catalogItems form">
<?php echo $form->create('CatalogItem');?>
	<fieldset>
 		<legend><?php __('Add CatalogItem');?></legend>
	<?php
		echo $form->input('catalog_category_id');
		echo $form->input('title');
		echo $form->input('text');
		echo $form->input('url');
	?>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('List CatalogItems', true), array('action'=>'index'));?></li>
		<li><?php echo $html->link(__('List Catalog Categories', true), array('controller'=> 'catalog_categories', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Catalog Category', true), array('controller'=> 'catalog_categories', 'action'=>'add')); ?> </li>
	</ul>
</div>
