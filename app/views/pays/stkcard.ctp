<script type="text/javascript">
<!--
	function checkstkform()
	{
		return (
			(document.stkform.card.value != '')
			&&
			(document.stkform.card.value != '')
			&&
			(document.stkform.card.value != '')
		);
	}
-->
</script>
<div class="contentColumns">
<h2><?php __('STK card payment');?></h2>
<form name="stkform" method="post" action="/pays/stk" onsubmit="return checkstkform();">
	<h4><?php __('STK card number'); ?></h4>
	<input type="text" name="card" value="2674774" />
	<h4><?php __('Card pin-code'); ?></h4>
	<input type="password" name="pin" value="6692981683" />
	<h4><?php __('Pay sum'); echo ', ' . Configure::read('STK.currency'); ?></h4>
	<input type="text" name="sum" value="10" /><br />
	<input type="submit" value="<?php __('Process payment'); ?>">
</form>
</div><br />