<p>
<?php echo $form->create('Feedback', array('url' => '/admin/feedbacks/reply'));
echo $form->input('subj');
echo $form->input('to', array('value' => implode(',', $emails)));
echo $form->input('text', array('type' => 'textarea'));
echo $form->end('Ответить');
?>
</p>
<div class="actions">
    <ul>
        <li><?php echo $html->link(__('Delete Feedback', true), array('action'=>'delete', $feedback['Feedback']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $feedback['Feedback']['id'])); ?> </li>
        <li><?php echo $html->link(__('List Feedbacks', true), array('action'=>'index')); ?> </li>
    </ul>
</div>
