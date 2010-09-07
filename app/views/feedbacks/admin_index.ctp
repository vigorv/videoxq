<div class="feedbacks index">
<h2><?php __('Feedbacks');?></h2>
<p>
<?php
echo $paginator->counter(array(
'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
));
?></p>
<script type="text/javascript">
<!--
	function switchBoxes(c)
	{
		checked="";
		if (c.checked) checked="checked";
		$("input:checkbox").attr("checked", checked);
	}
-->
</script>
<table cellpadding="0" cellspacing="0">
<tr>
	<th><a noref><input type="checkbox" name="chkSwitch" onclick="switchBoxes(this);" /></th>
    <th><?php echo $paginator->sort('film');?></th>
    <th><?php echo $paginator->sort('created');?></th>
    <th class="actions"><?php __('Actions');?></th>
</tr>
<script>
	function torrentsRuFormSubmit(nm)
	{
		document.torrentsruform.nm.value=nm;
		document.torrentsruform.submit();
		return false;
	}
	function rutrackerFormSubmit(nm)
	{
		document.rutrackerform.nm.value=nm;
		document.rutrackerform.submit();
		return false;
	}
</script>
<form target="_blank" id="rutrackerform" name="rutrackerform" method="post" action="">
	<input type="hidden" name="tracker" value="rutrackerru" />
	<input type="hidden" name="nm" value="" />
</form>
<form target="_blank" id="torrentsruform" name="torrentsruform" method="post" action="">
	<input type="hidden" name="tracker" value="rutrackerorg" />
	<input type="hidden" name="nm" value="" />
</form>
<form name="fbForm" action="" method="post">
<?php
$i = 0;
foreach ($feedbacks as $feedback):
    $class = null;
    if ($i++ % 2 == 0) {
        $class = ' class="altrow"';
    }
?>
    <tr<?php echo $class;?>>
        <td>
            <input type="checkbox" name="data[]" value="<?php echo $feedback['Feedback']['id']; ?>" />
        </td>
        <td>
            <?php
            	echo h($feedback['Feedback']['film']);
            ?>
        </td>
        <td>
            <?php echo h($feedback['Feedback']['created']); ?>
        </td>
        <td class="actions">
        	<a target="_blank" title="поиск в видеокаталоге" href="http://flux.itd/filmex.php?<?php echo rawurlencode($feedback['Feedback']['film']); ?>"><font color="blue">F</font></a>
        	<!--
				<a target="_blank" title="поиск в google" href="http://www.google.com/search?q=<?php echo rawurlencode('site:torrents.ru ' . $feedback['Feedback']['film']); ?>">google</a>
			-->
        	<!--
        	<a target="_blank" title="поиск на rutracker.org" href="#" onclick="return torrentsRuFormSubmit('<?php echo strtr($feedback['Feedback']['film'], "'", "\'"); ?>');"><img src="http://static.rutracker.org/favicon.ico" /></a>
        	-->
        	<a target="_blank" title="поиск на rutracker.org" href="http://rutracker.org/forum/tracker.php?nm=<?php echo rawurlencode($feedback['Feedback']['film']); ?>&f[]=-1"><img src="http://static.rutracker.org/favicon.ico" /></a>
        	<a title="поиск на rutracker.ru" href="#" onclick="return rutrackerFormSubmit('<?php echo strtr($feedback['Feedback']['film'], "'", "\'"); ?>');"><img src="http://rutracker.ru/favicon.ico" /></a>
        	<a target="_blank" title="поиск на kinozal.tv" href="http://kinozal.tv/browse.php?s=<?php echo rawurlencode(iconv('utf-8','windows-1251', $feedback['Feedback']['film'])); ?>"><img src="http://kinozal.tv/favicon.ico" /></a>
        	<a target="_blank" title="поиск на kinopoisk.ru" href="http://www.kinopoisk.ru/index.php?kp_query=<?php echo rawurlencode(iconv('utf-8','windows-1251', $feedback['Feedback']['film'])); ?>"><img src="http://www.kinopoisk.ru/images/favicon.ico" /></a>
        	<a target="_blank" title="поиск на kinobaza.tv" href="http://kinobaza.tv/search?query=<?php echo rawurlencode($feedback['Feedback']['film']) . "&search_type=films"; ?>"><img src="http://www.kinobaza.tv/img/fav.png" /></a>
            <?php echo $html->link('Ответить', array('action'=>'view', $feedback['Feedback']['id'])); ?>
            <?php echo $html->link(__('Edit', true), array('action'=>'edit', $feedback['Feedback']['id'])); ?>
            <?php
            	$str = 'Delete';
	            echo $html->link($str, array('action'=>'delete', $feedback['Feedback']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $feedback['Feedback']['id']));
            ?>
            <?php
            	if ($feedback['Feedback']['deleted'])
		            echo $html->link('Restore', array('action'=>'restore', $feedback['Feedback']['id']), null, null);
            ?>
        </td>
    </tr>
<?php endforeach; ?>
</form>
</table>
</div>
<div class="paging">
    <?php echo $paginator->prev('<< '.__('previous', true), array(), null, array('class'=>'disabled'));?>
 | 	<?php echo $paginator->numbers();?>
    <?php echo $paginator->next(__('next', true).' >>', array(), null, array('class'=>'disabled'));?>
</div>
<div class="actions">
    <ul>
        <li><?php echo $html->link(__('New Feedback', true), array('action'=>'add')); ?></li>
        <li><?php echo $html->link(__('Delete Checked', true), '#', array('onclick' => 'if (confirm(\'This will delete all checked records. Are you sure?\')) { document.fbForm.action=\'/admin/feedbacks/delete_all\'; document.fbForm.submit(); } return false;'), false, false, false); ?></li>
        <li><?php echo $html->link(__('Restore Checked', true), '#', array('onclick' => 'if (confirm(\'This will restore all checked records. Are you sure?\'))  { document.fbForm.action=\'/admin/feedbacks/restore_all\'; document.fbForm.submit(); } return false;'), false, false, false); ?></li>
    </ul>
</div>
