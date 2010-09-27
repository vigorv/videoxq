<div class="contentCol">
<h2>Регистрация</h2>
<?php
		$cnt = 1;
		$js = '
		<script type="text/javascript">
		<!--
		function registerSubmit()
		{
			http_request' . $cnt . ' = null;
			if (window.XMLHttpRequest) {
			        try {
			            http_request' . $cnt . ' = new XMLHttpRequest();
						if (http_request' . $cnt . ' != null)
						{
							http_request' . $cnt . '.overrideMimeType("text/xml");
						}
			        } catch (e){}
			    } else if (window.ActiveXObject) {
			        try {
			            http_request' . $cnt . ' = new ActiveXObject("Msxml2.XMLHTTP");
			        } catch (e){
			          try {
			              http_request' . $cnt . ' = new ActiveXObject("Microsoft.XMLHTTP");
			          } catch (e){}
			        }
			    }

			if (http_request' . $cnt . ' != null)
			{
				http_request' . $cnt . '.open("GET", "/users/scode", false);
				http_request' . $cnt . '.send(null);

			//http_request' . $cnt . '.onreadystatechange = function () {
				if (http_request' . $cnt . '.readyState == 4) {
					if (http_request' . $cnt . '.status == 200) {
						//var xmldoc = http_request' . $cnt . '.responseXML;
						//var code = xmldoc.getElementsByTagName("code").item(0).firstChild.data;
						var code = http_request' . $cnt . '.responseText;
						var fs = document.forms;
						for (i = 0; i < fs.length; i++)
						{
							if (fs[i].scode != null)
								fs[i].scode.value = code;
						}
					}
				}
			//}
			}
			return true
			;
		}
		-->
		</script>
		';
		echo $js;

//$html->css('style', null, array(), false);
echo $form->create('User', array('action' => 'register' , 'class' => 'reg', 'onSubmit' => "return registerSubmit();"));
?>
<p><label for="UserUsername">Логин<em class="required">*</em> :</label><br>
<input type="hidden" name="scode" value="">
<?php echo $form->error('username'); ?>
<?php echo $form->text('username', array('class' => 'textInput')); ?>
</p>
<p><label for="UserPassword">Пароль<em class="required">*</em> :</label><br>
<?php echo $form->error('password'); ?>
<?php echo $form->password('password', array('class' => 'textInput')); ?>
</p>
<p><label for="UserPassword2">Повторите пароль<em class="required">*</em> :</label><br>
<?php //echo $form->error('password2'); ?>
<?php echo $form->password('password2', array('class' => 'textInput')); ?></p>
<p><label for="UserEmail">E-mail<em class="required">*</em> :</label><br>
<?php echo $form->error('email'); ?>
<?php echo $form->text('email', array('class' => 'textInput')); ?></p>
<p><label for="UserCaptcha">Проверочный код<em class="required">*</em> :</label><br>
<?php //echo $form->error('captcha'); ?>
<?php echo $form->text('captcha', array('class' => 'textInput')); ?>
<!--<p>Можно писать как ПРОПИСНЫМИ, так и строчными буквами — как пожелаете.</p>-->
<p><img src="<?php echo $html->url('/users/captcha'); ?>" /> </p>
<!--
<?php echo $form->checkbox('agreement'); ?> Я принимаю <a target="_blank" href="/pages/agreement">пользовательское соглашение</a>
-->
<p>регистрируясь в системе, я соглашаюсь с <a target="_blank" href="/pages/agreement">пользовательским соглашением</a></p>
<?php
echo $form->end('Зарегистрироваться');
?>
<p><a href=/users/restore>Потерял пароль?</a></p>
</div>