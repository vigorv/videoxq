<?php /* SVN FILE: $Id: filter.ctp 43 2008-03-27 13:25:19Z andy $ */ ?>
<?php
$javascript->cacheEvents();
$javascript->codeBlock(null, array('inline' => false, 'safe' => false));
?>
$(function() {
	$('div#resultFilter').hide();
	$('a#toggleFilter').text('Show Filter');
	$('a#toggleFilter').click(function(){
  		$('div#resultFilter').slideToggle('slow');
		$('a#toggleFilter').text('Hide Filter');
		return false;
	});
});
<?php $javascript->blockEnd(); ?>
<p>
<?php
echo $html->link('Toggle Filter', '#', array('id' => 'toggleFilter'));
$currentFilter = $session->read($modelClass . '.filter');
if ($currentFilter) {
	$out = ' - currently filtering for :';
		debug ($currentFilter);
	foreach ($currentFilter as $field => $filter) {
		if (is_array($filter)) {
			$filter = 'In ' . implode(', ', $filter);
		}
		$currentFilters[] = $field . ' ' . $filter;
	}
	echo $out . implode(', ', $currentFilters);
}
?>
</p>
<div id="resultFilter">
<?php
$_data = $form->data;
$form->data = $session->read($modelClass . '.filterForm');
echo $form->create(null, array('url' => '/' . $this->params['url']['url']));
foreach ($filters as $filter => $settings) {
	if (!is_array($settings)) {
		$filter = $settings;
	}
	$settings = am(array('filterOptions' => $filterOptions), $settings);
	$selectOptions = am(array('empty' => true, 'div' => false, 'label' => $filter, 'options' => $settings['filterOptions']));
	unset($settings['filterOptions']);
	$select = $form->input($filter . '_type', $selectOptions);
	$inputOptions = am(array('div' => false, 'label' => false, 'empty' => true), $settings);
	if ($filter == 'id') {
		$inputOptions['type'] = 'text';
	}
	$input = $form->input($filter, $inputOptions);
	$out = $select . $input;
	echo $html->div('input', $out);
}
echo $form->end('apply filter');
$form->data = $_data;
?>
</div>
