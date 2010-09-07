<?php
echo $form->create('Pay',array('url'=>'edit/'.$form->value('Pay.id')));
if (empty($data))
{
	$msg = __('new_pay', true);
	echo $form->input('Pay.user_id',	array("summ" => "Pay.user_id"));
}
else
{
	$msg = __('edit_pay', true);
	echo $form->hidden('id', array("name" => "data[Pay][id]", "value" => $data["Pay"]["id"]));
	echo $form->hidden('id', array("name" => "data[Pay][user_id]", "value" => $data["Pay"]["user_id"]));
}
echo'
	<h3>' . $msg . '</h3>
' . $form->dateTime("Pay.findate", $dateFormat= 'DMY', $timeFormat= '24', $selected = $data['Pay']['findate']) . '
' . $form->input('Pay.summ',	array("summ" => "Pay.summ",		"value" => $data["Pay"]["summ"])) . '
' . $form->select('Pay.status',	array(_PAY_WAIT_ => _PAY_WAIT_STR_,_PAY_DONE_ => _PAY_DONE_STR_,_PAY_FAIL_ => _PAY_FAIL_STR_), $data['Pay']['status'], null, null) . '
' . $form->end('Submit') . '

	<p><a href="/admin/pays">' . __('back2_pay_list', true) . '</a></p>
	</form>
';