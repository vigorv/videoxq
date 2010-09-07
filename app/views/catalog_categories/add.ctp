<div class="catalogCategories form">
<?php echo $form->create('CatalogCategory');?>
    <fieldset>
         <legend><?php __('Add CatalogCategory');?></legend>
    <?php
        echo $form->input('parent_id');
        echo $form->input('title');
    ?>
    </fieldset>
<?php echo $form->end('Submit');?>
</div>
<div class="actions">
    <ul>
        <li><?php echo $html->link(__('List CatalogCategories', true), array('action'=>'index'));?></li>
        <li><?php echo $html->link(__('New Catalog Item', true), array('controller'=> 'catalog_items', 'action'=>'add')); ?> </li>
    </ul>
</div>
