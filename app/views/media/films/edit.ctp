<?php /* SVN FILE: $Id: form.ctp 21 2008-03-13 23:32:20Z andy $ */ ?>
<?php 
$action = in_array($this->action, array('add', 'admin_add'))?__('Add', true):__('Edit', true); 
$action = Inflector::humanize($action); 
echo $form->create(); 
echo $form->inputs(array( 
	'legend' => $action . ' Film', 
	'id',
	'title',
	'description',
	'film_type_id' => array('empty' => true),
	'active',
	'year',
	'dir',
	'title_en',
	'Country',
	'Emotion',
	'Genre',
	'Person',
	'Publisher',
	'Theme',
)); 
echo $form->end(__('Submit', true)); 
?>