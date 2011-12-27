<div class="adm_content">
<h2><?php __($this->name);?></h2>
<br/>


<?php
$html->css('adm_peoples','',array(),false);
$javascript->link('adm_peoples.js', false);
$cur_page = $paginator->current();
$sort_dir = ($paginator->sortDir()== 'asc')? array('class'=>'asc') : array('class'=>'desc') ;
$sort_key = $paginator->sortKey();
?>
<?php echo $form->create(null, array('id' => 'PeopleFilter', 'action' => 'index', 'enctype' => 'multipart/form-data'));?>
<table class="list_rows" cellspacing="1px">
    <tr><th>№</th>
        
        <?php $class = ($sort_key=='id')? $sort_dir : array();?>
        <th><?=$paginator->sort('Id', 'id', $class);?></th>
        
        <?php $class = ($sort_key=='name')? $sort_dir : array();?>
        <th><?=$paginator->sort( 'Имя', 'name', $class);?></th>
        
        <?php $class = ($sort_key=='name_en')? $sort_dir : array();?>
        <th><?=$paginator->sort('Имя(англ.)', 'name_en', $class);?></th>
        
        <?php $class = ($sort_key=='description')? $sort_dir : array();?>
        <th><?=$paginator->sort('Описание', 'description', $class);?></th>
        
        <th>&nbsp;</th>
    </tr>    
<!--    
    <tr><th>№</th>
        <th><?=$html->link('Id', array('action'=>'index/sort:id/page:'.$cur_page));?></th>
        <th><?=$html->link('Имя', array('action'=>'index/sort:name/page:'.$cur_page));?></th>
        <th><?=$html->link('Имя(англ.)', array('action'=>'index/sort:name_en/page:'.$cur_page));?></th>
        <th><?=$html->link('Описание', array('action'=>'index/sort:description/page:'.$cur_page));?></th>
        <th>&nbsp;</th>
    </tr>
-->
    <tr>

        <td><?='';?></td>
        <td><?=$form->input('PeopleFilter.id',  array('default'=> (!empty($PeopleFilter['id'])? $PeopleFilter['id'] : ''), 'label' => false,'size'=>3));?></td>
        <td><?=$form->input('PeopleFilter.name',  array('default'=> (!empty($PeopleFilter['name'])? $PeopleFilter['name'] : ''), 'label' => false));?></td>
        <td><?=$form->input('PeopleFilter.name_en',  array('default'=> (!empty($PeopleFilter['name_en'])? $PeopleFilter['name_en'] : ''), 'label' => false));?></td>
        <td><?=$form->input('PeopleFilter.description',  array('default'=> (!empty($PeopleFilter['description'])? $PeopleFilter['description'] : ''), 'label' => false));?></td>
        <td><?=$form->submit('Фильтр');?></td>
    </tr>    
<?php
foreach ($rows_list as $k => $row){
?>
    <tr>
        <td><?=(($paginator->current()-1)*$rows_per_page)+($k+1);?></td>
        <td><?=!empty($row['Person']['id'])? $row['Person']['id'] : '&nbsp;';?></td>
        <td><?=!empty($row['Person']['name'])? $row['Person']['name'] : '&nbsp;';?></td>
        <td><?=!empty($row['Person']['name_en'])? $row['Person']['name_en']: '&nbsp;';?></td>
        <td><?=!empty($row['Person']['description'])? $row['Person']['description']: '&nbsp;';?></td>
        
        <td nowrap >
            <div class="hidden_actions">
            <a href="/admin/people/edit/<?=$row['Person']['id']?>" title="Редактировать" ><img src="/img/copyrightholders/adm/edit-icon2_32x32.png" class="icon" /></a>
            <a href="/admin/people/delete/<?=$row['Person']['id']?>" class="delete" title="Удалить `<?=htmlspecialchars($row['Person']['name']);?>` (id: <?=htmlspecialchars($row['Person']['id']);?>)?"><img src="/img/copyrightholders/adm/delete-icon_32x32.png" class="icon"/></a>
            </div>
        </td>
    </tr>
<?php
}
?>

</table>
<?php echo $form->end();?>        
<div class="ch_btm_menu" style="position: relative; overflow: hidden">
<div style="width: 330px; float:right;">
Добавить запись <a href="/admin/people/add" title="Добавить запись" ><img src="/img/copyrightholders/adm/add-icon_32x32.png" class="icon" /></a>
</div>
    <div style="width: 330px; float:left;">
<?php
    echo $form->create('People', array('id' => 'People_rows_per_page', 'enctype' => 'multipart/form-data', 'action' => '/', 'style'=>'padding:0; margin:0'));
    $numeration = array('10'=>'10','20'=>'20','30'=>'30','40'=>'40','50'=>'50','100'=>'100');
    echo $form->input('rows_per_page', array('label'=>'записей на страницу','type'=>'select' ,'empty'=>false, 'options'=>$numeration, 'selected'=> $rows_per_page, 'onchange'=>'this.form.submit();'));
    echo $form->end();
?>
   </div>

<div style="padding: 60px 0 0 5px;text-align: left;">Всего записей: <b style="font-size:18px"><?=$total_rows_count;?></b></div>


</div>

<div class="pages">
<?php echo $this->element('paging'); ?>
</div>

<div id="logo"></div>
</div>