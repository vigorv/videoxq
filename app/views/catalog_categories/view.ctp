<div class="catalogCategories view">
<h2><?php  __('CatalogCategory');?></h2>
    <dl><?php $i = 0; $class = ' class="altrow"';?>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Catalog Category Parent'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php echo $html->link($catalogCategory['CatalogCategoryParent']['title'], array('controller'=> 'catalog_categories', 'action'=>'view', $catalogCategory['CatalogCategoryParent']['id'])); ?>
            &nbsp;
        </dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Title'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php echo $catalogCategory['CatalogCategory']['title']; ?>
            &nbsp;
        </dd>
    </dl>
</div>
<div class="actions">
    <ul>
        <li><?php echo $html->link(__('List CatalogCategories', true), array('action'=>'index')); ?> </li>
        <li><?php echo $html->link(__('New Catalog Item', true), array('controller'=> 'catalog_items', 'action'=>'add')); ?> </li>
    </ul>
</div>
<div class="related">
    <h3><?php __('Related Catalog Items');?></h3>
    <?php if (!empty($catalogCategory['CatalogItem'])):?>
    <table cellpadding = "0" cellspacing = "0">
    <tr>
        <th><?php __('Catalog Category Id'); ?></th>
        <th><?php __('Title'); ?></th>
        <th><?php __('Text'); ?></th>
        <th><?php __('Url'); ?></th>
        <th class="actions"><?php __('Actions');?></th>
    </tr>
    <?php
        $i = 0;
        foreach ($catalogCategory['CatalogItem'] as $catalogItem):
            $class = null;
            if ($i++ % 2 == 0) {
                $class = ' class="altrow"';
            }
        ?>
        <tr<?php echo $class;?>>
            <td><?php echo $catalogItem['catalog_category_id'];?></td>
            <td><?php echo $catalogItem['title'];?></td>
            <td><?php echo $catalogItem['text'];?></td>
            <td><?php echo $catalogItem['url'];?></td>
            <td class="actions">
                <?php echo $html->link(__('View', true), array('controller'=> 'catalog_items', 'action'=>'view', $catalogItem['id'])); ?>
                <?php echo $html->link(__('Edit', true), array('controller'=> 'catalog_items', 'action'=>'edit', $catalogItem['id'])); ?>
                <?php echo $html->link(__('Delete', true), array('controller'=> 'catalog_items', 'action'=>'delete', $catalogItem['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $catalogItem['id'])); ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </table>
<?php endif; ?>

    <div class="actions">
        <ul>
            <li><?php echo $html->link(__('New Catalog Item', true), array('controller'=> 'catalog_items', 'action'=>'add'));?> </li>
        </ul>
    </div>
</div>
