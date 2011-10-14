<div class="pages form">
<?php

	$javascript->link('ui.core', false);
	$javascript->link('ui.datepicker', false);
	$html->css('ui.datepicker', null, array(), false);

	echo $form->create('Directions', array('action' => 'edit', 'name' => 'directionform'));
?>
    <fieldset>
         <legend><?php __('Edit Directions');?></legend>
    <?php
        echo $form->input('id', array('value' => (!empty($info) ? $info['Direction']['id'] : '')));
        echo $form->input('created', array('type' => 'text', 'id' => 'createdid',  'label' => 'Дата', 'value' => (!empty($info) ? $info['Direction']['created'] : date('Y-m-d'))));
        echo $form->input('title', array('label' => 'Название полное', 'value' => (!empty($info) ? $info['Direction']['title'] : '')));
        echo $form->input('caption', array('label' => 'Название краткое', 'value' => (!empty($info) ? $info['Direction']['caption'] : '')));
        echo $form->input('txt', array('rows' => 15, 'label' => 'Описание', 'value' => (!empty($info) ? $info['Direction']['txt'] : '')));
        echo $form->input('hidden', array('label' => 'Скрыть категорию', 'value' => 1, 'checked' => (!empty($info['Direction']['hidden']) ? 'checked' : '')));
        echo $form->input('srt', array('type' => 'text', 'label' => 'Сортировка', 'value' => (!empty($info) ? $info['Direction']['srt'] : 0)));
		echo $form->submit();
?>
    </fieldset>
    </form>
		<br />
<div class="actions">
    <ul>
<?php
	if (!empty($info["Direction"]["id"]))
	{
?>
        <li><?php echo $html->link(__('Delete', true), array('action'=>'delete', $form->value('Direction.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $form->value('Direction.id'))); ?></li>
<?php
	}
?>
        <li><?php echo $html->link(__('List Directions', true), array('action'=>'index'));?></li>
    </ul>
</div>
<script type="text/javascript">

      $(document).ready(function() {

 		$("#createdid").datepicker({
	    dateFormat: $.datepicker.ATOM,
	    firstDay: 1,
	    changeFirstDay: false
		});
      });

</script>
