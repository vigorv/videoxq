<div id="alphFaces">
    <ul>
    <?php
    //pr($this->params);
    $rus_trigger=0;
    foreach ($alphabet as $letter)
    {
    	if ($lang == _ENG_)
    	{
    		if ((strtolower($letter) < 'a') || (strtolower($letter) > 'z')) continue;
    	}
/* 2011-09-22
 * добавил для корректного разделения по строкам англ и русских букв при
 * неполном англ. алфавите, раньше переход был только на букве "z"
 *
 *
 */
        if (preg_match("/[^a-z]+/ui",strtolower($letter)) && !$rus_trigger){
            $rus_trigger=1;
            echo '</ul><ul>';
        }
        if (strtolower($this->params['pass'][0]) == strtolower($letter))
            echo '<li><strong>' . $letter . '</strong></li>';
        else
            echo '<li><a href="/copyrightholders/letter/' . $letter . '">' . $letter . '</a></li>';

    }
    ?>
    </ul>
</div>

<div id="faces">
    <form class="searchName" action="/copyrightholders/index" method="post" id="SearchCopyrightholderName">
        <table><tr><td>
        <input type="text" class="textInput" name="data[Copyrightholder][search]" id="CopyrightholderName" style="width: 300px"/>
        </td><td>
        <input type="submit" class="button" value="<?php __('Search for copyrightholders'); ?>"/>
        </td></tr></table>
    </form>
    <div class="column">
            <h2><?= $this->params['pass'][0] ?></h2>

            <?php
            for ($col = 0; $col < 3; $col++ ):
            ?>
            <div class="letterColumn">
                <ul>
                <?php
                for ($row = 0; $row < ceil(count($copyrightholders)/3); $row++):
                    $idx = $col + $row * 3;
                    if (isset($copyrightholders[$idx])):

                    if ($lang == _ENG_)
                    {
                    	if (empty($copyrightholders[$idx]['Copyrightholder']['name' . $langFix]))
                    		continue;
                    	$name = $copyrightholders[$idx]['Copyrightholder']['name' . $langFix];
                    }
                    else
                    	$name = $copyrightholders[$idx]['Copyrightholder']['name'] ? $copyrightholders[$idx]['Copyrightholder']['name'] : $copyrightholders[$idx]['Copyrightholder']['name_en'];
                ?>
                    <li><a href="/copyrightholders/view/<?= $copyrightholders[$idx]['Copyrightholder']['id'] ?>"><?= $name ?></a></li>
                <?php
                    endif;
                endfor; ?>
                </ul>
            </div>
            <?php
            endfor;
            ?>
    </div>
    <div class="spacer"></div>
<div class="pages">
<?php
$paginator->options(array('url'=>$this->params['pass']));
?>
<?php echo $this->element('paging'); ?>
</div>
</div>
<?php echo $autocomplete->autocomplete('CopyrightholderName', 'Copyrightholder/name', null, 30, 3); ?>
<?php $html->css('autocomplete','',array(),false);?>