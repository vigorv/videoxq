                <!-- Фильтр -->
                <table border="0" cellspacing="0" cellpadding="0" width="260">
                  <tbody>
                    <tr>
                      <td class="corner1" width="25"> </td>
                      <td class="border3"> </td>
                      <td class="corner2" id="c23" width="25"> </td>
                    </tr>
                    <tr>
                      <td class="border1" width="25"> </td>
                      <td>



<?php
	$defaultStr = __("Search by title", true);
?>
<script type="text/javascript">
<!--
	function checkSearchSubmit()
	{
		if ((($('#adv-title').attr('name') == 'data[Film][searchsimple]') && ($('#adv-title').val() == "")) || ($('#adv-title').val() == "<?php echo $defaultStr; ?>"))
		{
			return false;
		}
		return true;
	}
-->
</script>
<?php echo $form->create(null, array('url' => array('controller' => 'media', 'action' => 'index'), 'onsubmit' => 'return checkSearchSubmit()')); ?>
<ul style="margin:0 0 0 0; padding-left:0px">
    <li class="search">
            <?php
            $defValue = '';
       		$searchName = 'search';
			if (empty($passedParams['ex'])) {
           		$defValue = $defaultStr;
           		$searchName = 'searchsimple';
			}
           		$onclick = "if (this.value=='{$defValue}') this.value='';";
           		$onblur = "if (($('#adv-title').attr('name') == 'data[Film][searchsimple]') && (this.value=='')) this.value='{$defValue}';";
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
            <p class="adv2 adv-term"><?= $form->checkbox('is_license', array('value' => 1, 'checked' => ($block_media_genres['is_license']) ? 'checked' : ''));?> <?php __("Downloadable"); ?> <a href="/pages/faq#checkbox"><span style="border: 1px solid black;">&nbsp;?&nbsp;</span></a></p>
            <p class="adv2"><?php __("Sort by"); ?>:<br>
            <?= $form->radio('sort', $block_media_genres['sort'], array('separator' => '<br>', 'legend' => false));?>
            <?= $form->hidden('direction', array('value' => '1')); ?>
            </p>
            <p>111<input type="submit" value="<?php __("Search"); ?>" class="button"> <a href="#" class="dashed"><?php __("Advanced Search"); ?></a></p>

        <script type="text/javascript">
        <!--
		//$('p.adv').toggle();
<?php
	if (!empty($passedParams['ex'])) {
?>
			$('p.adv2').toggle();
<?php } ?>
            $(document).ready(function(){
                $('a.dashed').click(function(){
					$("p.adv2").toggle();

					at = document.getElementById('adv-title');
                    if (at.name == 'data[Film][searchsimple]')
                        at.name = 'data[Film][search]';
                    else
                        at.name = 'data[Film][searchsimple]';
                	$('#adv-title').click();
                    return false;
                });
            });
        -->
        </script>
    </li>
</ul>
        </form>
                      </td>
                      <td class="border2" width="25">&nbsp;</td>
                    </tr>
                    <tr>
                      <td class="corner3" width="25"> </td>
                      <td class="border4"> </td>
                      <td class="corner4" id="c24" width="25"> </td>
                    </tr>
                  </tbody>
                </table>
                <br />
                <!-- /Фильтр -->
<?php
$placeNamePrefix = '';
if ($isWS)
	$placeNamePrefix = 'WS';

$placeName = $placeNamePrefix . 'left1';
echo $BlockBanner->getBanner($placeName);
?>