<div><h2 style="text-align: center">Закладка</h2></div>
    <div style="padding: 5px; text-align: center">
<?php
if (empty($this->data['Bookmark']['saved'])):
echo $form->create('Bookmark', array('action' => 'add', 'type' => 'post'));
//echo $html->formTag('/Bookmarks/edit', 'post', array('id' => 'bookmarkForm'));
//echo $errorMessage;
echo $form->hidden('Bookmark/saved');
?>
<p>
<?php
echo $form->input('title', array('legend'=>'Описание закладки:','size'=>50));
?>
</p>
<p>
<?php
echo $form->submit('Add bookmark', array('onclick' => 'addBookmark(\''.$this->here.'\'); return false;', 'type' => 'button', 'value' => 'Добавить'));
?>
&nbsp;<img id="bookmarkAddLoder" src="/img/loading.gif" style="display: none;" />&nbsp;
<?php
echo $form->button('Close', array('onclick' => '$("div#bookmarkPlaceHolder").hide("slow");', 'type' => 'button', 'value' => 'Закрыть'));
?>
</p>
</form>
<?php
else:
echo "Закладка добавлена!<br />";
echo $form->button('Close', array('onclick' => '$("div#bookmarkPlaceHolder").hide("slow");', 'type' => 'button', 'value' => 'Закрыть'));
?>
<script type="text/javascript">
$("div#bookmarkPlaceHolder").hide("slow");
$('#addBookmarkLink').text('Убрать из закладок');
$("#addBookmarkLink").attr({onclick: "delBookmark();return false;", href: "<?php $html->url('/bookmarks/delete'); ?>", id: "delBookmarkLink"});
</script>
<?php
endif;
?>
</div>