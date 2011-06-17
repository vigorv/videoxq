<div class="pages form">
<?php
	//DEFINE("_UPLOADDIR_", $_SERVER['DOCUMENT_ROOT'] . '/app/webroot/files/news');
	DEFINE("_UPLOADDIR_", $uploadDir);
	$dir = $_SERVER['DOCUMENT_ROOT'] . _UPLOADDIR_;
	if (empty($info['News']['img']))
	{
		$uploadedFile = '';
		if (is_dir($dir)) {
		    if ($dh = opendir($dir)) {
		        while (($file = readdir($dh)) !== false) {
		            if (ereg('^temp_' . $authUser['userid'], $file))
		            {
		            	$uploadedFile = $file;
		            	break;
		            }
		        }
		        closedir($dh);
		    }
		}
	}
	else
	{
		$uploadedFile = $info['News']['img'];
	}

	$javascript->link('ui.core', false);
	$javascript->link('ui.datepicker', false);
	$html->css('ui.datepicker', null, array(), false);

	echo $form->create('News', array('action' => 'edit', 'name' => 'newsform'));
?>
    <fieldset>
         <legend><?php __('Edit New');?></legend>
    <?php
        echo $form->input('id', array('value' => (!empty($info) ? $info['News']['id'] : '')));
        echo $form->input('created', array('type' => 'text', 'id' => 'createdid',  'label' => 'Дата', 'value' => (!empty($info) ? $info['News']['created'] : date('Y-m-d'))));
        echo $form->input('title', array('label' => 'Название', 'value' => (!empty($info) ? $info['News']['title'] : '')));
        echo $form->input('stxt', array('rows' => 5, 'label' => 'Короткий текст', 'value' => (!empty($info) ? $info['News']['stxt'] : '')));
        echo $form->input('txt', array('rows' => 15, 'label' => 'Полный текст', 'value' => (!empty($info) ? $info['News']['txt'] : '')));
        echo $form->input('matchesinfo', array('id' => 'matchesinfoid', 'type' => 'text', 'label' => 'Названия матчей', 'value' => (!empty($info) ? $info['News']['matchesinfo'] : '')));
        echo $form->input('hidden', array('label' => 'Скрыть новость', 'value' => 1, 'checked' => (!empty($info['News']['hidden']) ? 'checked' : '')));
        $dirsVal = array(0 => 'Укажите категорию (направление)');//, 'empty' => false);
        if (!empty($dirs))
        {
        	foreach ($dirs as $d)
        	{
        		$dirsVal[$d['Direction']['id']] = (empty($d['Direction']['caption']) ? $d['Direction']['title'] : $d['Direction']['caption']);
        	}
        }

        //echo $form->select('News.direction_id', $dirsVal, null);
        echo $form->input('News.direction_id', array('label' => 'Категория', 'type' => 'select', 'options' => $dirsVal, 'value' => $info['News']['direction_id']));
//<script type="text/javascript" src="/uploadify/jquery-1.4.2.min.js"></script>
    ?>
    <br /><br /><input type="hidden" id="picture" name="data[picture]" value="<?php echo $uploadedFile; ?>" />
<link rel="stylesheet" type="text/css" href="/uploadify/uploadify.css">
<script type="text/javascript" src="/uploadify/swfobject.js"></script>
<script type="text/javascript" src="/uploadify/jquery.uploadify.v2.1.4.min.js"></script>
<script type="text/javascript">

      $(document).ready(function() {

		$("#createdid").datepicker({
	    dateFormat: $.datepicker.ATOM,
	    firstDay: 1,
	    changeFirstDay: false
		});

	       $('#file_upload').uploadify({
			'uploader'		: '/uploadify/uploadify.swf',
			'script'		: '/pictureupload.php',
			'scriptAccess'	: 'always',
			'cancelImg'		: '/uploadify/x.gif',
			'folder'		: '<?php echo _UPLOADDIR_;?>',
			'multi'			: false,
			'method'		: 'post',
			'buttonImg'		: '/uploadify/select.gif',
			'fileExt'		: '*.gif;*.jpg;*.jpeg;*.jpe;*.png',
			'onComplete'	: function(event, ID, fileObj, response, data) {

		  if (response)
		  {
			data = $.ajax({
				url: "/preview.php",
				type: "POST",
				data: "filename="+response+"&<?php echo 'userid=' . (empty($authUser['userid']) ? 0 : $authUser['userid']); ?>",
  				async: false
 			}).responseText;
	  		if (data != '')
	  		{
	  			//ОТОБРАЖАЕМ ИМЯ ФАЙЛА В ФОРМЕ ДОБАВЛЕНИЯ НОВОСТИ
	  			o = document.getElementById('picture')
	  			o.value = data;
	  			$('#pictureRow').html('<img src="/files/news/small/' + data + '">');
	  		}
	  		else
	  			alert('<?php __('Preview create error');?>');
		  }
		  else
		  {
		  	alert('<?php __('Upload picture error');?>');
		  }
	  },
          'auto'      : false
        });
      });

      function submitUpload()
      {
        $('#file_upload').uploadifyUpload();
		return false;
      }
</script>
<?php
        __('Attach image');
?>
	<table><tr>
		<td width="100%">
			<input id="file_upload" name="file_upload" type="file" />
		</td>
		<td>
			<a href="#" onclick="return submitUpload();"><?php __('Upload'); ?></a></div>
		</td>
		</tr>
		<tr><td colspan="2" id="pictureRow"><?php if ($uploadedFile) echo '<img src="/files/news/small/' . $uploadedFile . '" />'; ?></td>
	</tr></table>
<?php
	echo $form->submit();
?>
    </fieldset>
    </form>
		<br />
<div class="actions">
    <ul>
<?php
	if (!empty($info["News"]["id"]))
	{
?>
        <li><?php echo $html->link(__('Delete', true), array('action'=>'delete', $form->value('News.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $form->value('News.id'))); ?></li>
<?php
	}
?>
        <li><?php echo $html->link(__('List News', true), array('action'=>'index'));?></li>
    </ul>
</div>
<?php

