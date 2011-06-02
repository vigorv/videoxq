
<?php
	$defaultStr = __("Search by title", true);
?>
<script type="text/javascript">
<!--
	function doClear(theText) {
//		if (theText.value == theText.defaultValue) { theText.value = "" }
		if (theText.value == "<?php echo $defaultStr; ?>") { theText.value = "" }
	}

	function doDefault(theText) {
//		if (theText.value == "") { theText.value = theText.defaultValue }
		if (theText.value == "") { theText.value = "<?php echo $defaultStr; ?>" }
	}

	function checkSearchSubmit()
	{
		if ((($("#adv-title").attr("name") == "data[Film][searchsimple]") && ($("#adv-title").val() == "")) || ($("#adv-title").val() == "<?php echo $defaultStr; ?>"))
		{
			return false;
		}
		return true;
	}
-->
</script>
<?php echo $form->create(null, array('url' => array('controller' => 'media', 'action' => 'index'), 'onsubmit' => 'return checkSearchSubmit()')); ?>
<ul id="genres" style="margin-bottom:-10px">
    <li class="search">
            <?php
            $defValue = '';
       		$searchName = 'search';
			if (empty($passedParams['ex'])) {
           		$defValue = $defaultStr;
           		$searchName = 'searchsimple';
			}
           		$onclick = "doClear(this);";
           		$onblur = 'if (($("#adv-title").attr("name") == "data[Film][searchsimple]")) doDefault(this);';
            	if (isset($this->data["Film"][$searchName]))
            	{
            		$value = $this->data["Film"][$searchName];
            	}
            	else
            	{
            		$value = $defValue;
            	}
/*
    echo $javascript->link('jquery.bgiframe.min');
    echo $javascript->link('jquery.ajaxQueue');
    echo $javascript->link('jquery.autocomplete.pack');
    $script = '
    	<script type="text/javascript">
    $(document).ready(function() { $("#adv-title").autocomplete(\'/searchWords/autoComplete\', properties = {
                                                                            matchContains: true,
                                                                            minChars: 2,
                                                                            selectFirst: true,
                                                                            intro_text: "",
                                                                            no_result: "",
                                                                            result_field: "data[Tag][Tag]",
                                                                            width: 184,
                                                                            multiple: false
                                                                        });

                                                });
		</script>
    ';
    echo $script;
    echo $html->css('jquery.autocomplete', null, array());

    echo $form->text('searchsimple', array('class' => 'textInput', 'id' => 'adv-title', 'type' => 'text',
    	'value' => $value, 'onclick' => $onclick, 'onfocus' => $onclick, 'onblur' => $onblur
    	, 'autocomplete' => 'off', 'between' => '<br />', 'error' => false, 'div' => array('tag' => 'p', 'class' => '')
    	));
//*/
    echo $form->text("Film." . $searchName, array('class' => 'textInput', 'width' => '250', 'id' => 'adv-title', 'value' => $value, 'onclick' => $onclick, 'onfocus' => $onclick, 'onblur' => $onblur));
	if ($this->params['action'] != 'view')
	{
?>
            <p class="adv2"><label for="adv-genre"><?php __("Genre"); ?>:</label><br>
            <?= $form->select('genre', $block_media_genres['genres'], null, array('class' => 'textInput', 'id' => "adv-genre"), __("All genres", true)); ?>
            </p>
            <p class="adv2"><label for="adv-country"><?php __("Country"); ?>:</label><br>
            <?= $form->select('country', $block_media_genres['countries'], null, array('class' => 'textInput', 'id' => "adv-country"), __("All countries", true)); ?>
            </p>
            <p class="adv2"><label for="adv-type"><?php __("Type"); ?>:</label><br>
            <?= $form->select('type', $block_media_genres['types'], null, array('class' => 'textInput', 'id' => "adv-type"), __("All types", true)); ?>
            </p>
            <p class="adv2 adv-term">IMDb &mdash; <?php __("from"); ?>
            <?= $form->select('imdb_start', $block_media_genres['imdb'], null, array('class' => 'textInput', 'style' => "width:40px;")); ?>
            <?php __("to"); ?> <?= $form->select('imdb_end', $block_media_genres['imdb'], null, array('class' => 'textInput', 'style' => "width:40px;")); ?>
            </p>
            <p class="adv2 adv-term"><?php __("year"); ?> &mdash; <?php __("from"); ?> <?= $form->text('year_start', array('class' => 'textInput')); ?> <?php __("to"); ?> <?= $form->text('year_end', array('class' => 'textInput')); ?></p>
<?php
/*
		if (!$isWS)//ДЛЯ WS не выводим
		{
            <p class="adv2 adv-term"><?= $form->checkbox('is_license', array('value' => 1, 'checked' => ($block_media_genres['is_license']) ? 'checked' : ''));?> <?php __("Downloadable"); ?> <a href="/pages/faq#checkbox"><span style="border: 1px solid black;">&nbsp;?&nbsp;</span></a></p>
		}
*/
?>
            <p class="adv2"><?php __("Sort by"); ?>:<br>
            <?= $form->radio('sort', $block_media_genres['sort'], array('separator' => '<br>', 'legend' => false));?>
            <?= $form->hidden('direction', array('value' => '1')); ?>
            </p>
<?php
	}
?>
            <p><input type="submit" value="<?php __("Search"); ?>" class="button">
<?php
	if ($this->params['action']!='view')
	{
?>
            <a href="#" class="dashed"><?php __("Advanced Search"); ?></a>
<?php
	}
?>
            </p>
        <script type="text/javascript">
        <!--
<?php
	if (!empty($passedParams['ex'])) {
?>
			$('p.adv2').toggle();
<?php } ?>
                $('a.dashed').click(function(){
					$("p.adv2").toggle();

					at = document.getElementById('adv-title');
                    if (at.name == 'data[Film][searchsimple]')
                        at.name = 'data[Film][search]';
                    else
                        at.name = 'data[Film][searchsimple]';
                	doClear(at);

                    return false;
                });
        -->
        </script>
    </li>
</ul>
        </form>
