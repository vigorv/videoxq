<?php
	foreach($block_poll as $bp)
	{
		extract($bp);
if (!empty($Poll))
{
//	pr($Poll);
?>


<div class="polls" id="polls">
<p style="text-align: center;"><strong><?php echo $Poll['title']?></strong></p>
<div class="polls-ans" id="polls-ans">
<ul class="polls-ul">


<?php

if ($main_voting_voted[$Poll['id']]
    || ($authUser['userid'] && strpos($Poll['voters'], ',' . $authUser['userid'] . ',') !== false)):

    foreach ($Poll['data'] as $answer)
    {
    ?>
        <li>
        <?php echo $answer['answer'];?>(<?php echo $answer['percent']?>%, <?php echo $app->pluralForm($answer['voters'], array('голос', 'голоса', 'голосов'))?>)
        <div style="width: <?php echo $answer['width']?>%;" class="pollbar" /></div>
        </li>
    <?php
    }
else:
echo $form->create('Poll', array('action' => 'vote'));
echo $form->hidden('redirect', array('value' => $this->here));
echo $form->hidden('id', array('value' => $Poll['id']));
echo '<li>';
if ($Poll["multiple"])
{
	foreach ($Poll['answers'] as $key => $answer)
	{
		echo $form->input($answer, array('name' => 'data[Poll][vote][' . $key . ']', 'type' => 'checkbox', 'value' => 1)) . '</li><li>';
	}
}
else
	echo $form->radio('vote', $Poll['answers'], array('legend' => false, 'separator' => '</li><li>'));
echo '</li>';
//echo $form->input('vote', array('legend' => false, 'separator' => '<br>',
//                                'options' => $Poll['answers'], 'type' => 'radio'));
echo $form->end('Голосовать');
endif;
?>
</ul>
<p style="text-align: center;">Всего проголосовало: <strong><?php echo $Poll['total_votes']?></strong></p>
</div>
</div>
<?php
}
//pr($block_poll);
//echo $html->css('polls', null, array(), false);
/*
 foreach ($Poll['answers'] as $key => $answer)
{
?>
<li><input type="radio" value="25" name="poll_6" id="poll-answer-25"/>
<label for="poll-answer-<?php echo $key?>"><?php echo $answer?></label>
</li>
<?php
}

 */
break;
	}