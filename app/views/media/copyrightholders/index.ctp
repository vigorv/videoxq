<div id="alphFaces">
<?php

if (!empty($search_words))
{
   foreach ($search_words as $searchWord)
   {
       echo '<h3>' . __('Perhaps what you are looking for is', true) . ' ';
       echo '<a href="'. $searchWord['SearchWord']['url'] . '">' . __('here', true) . '</a>';
       echo '</h3><br>';
   }
}
?>

    <ul>

    <?php

    ksort($copyrightholders);
    $rus_trigger=0;
    foreach (array_keys($copyrightholders) as $letter)
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
        echo '<li><a href="/copyrightholders/letter/' . $letter . '">' . $letter . '</a></li>';



    }
    ?>
    </ul>
</div>
<?php
    if (!empty($isSearch))
		echo '<div id="searchfaces">';
	else
		echo '<div id="faces">';
?>
    <form class="searchName" action="/copyrightholders/index" method="post" id="SearchCopyrightholderName">
        <table><tr><td>
        <input type="text" class="textInput" name="data[Copyrightholder][search]" id="CopyrightholderName" style="width: 300px"/>
        </td><td>
        <input type="submit" class="button" value="<?php __('Search for copyrightholders'); ?>"/>
        </td></tr></table>
    </form>
    <div class="column">
    <?php
function sortsize($a, $b)
{
	if (count($a) > count($b))
		return 1;
	else
		return -1;
}
    if (!empty($isSearch))
    {
    	usort($copyrightholders, "sortsize");
    }
    foreach ($copyrightholders as $letter => $copyrightholders_items)
    {

    	if ($lang == _ENG_)
    	{
    		if ((strtolower($letter) < 'a') || (strtolower($letter) > 'z')) continue;
    	}

    	if (!empty($isSearch))
    		$letter = mb_substr($copyrightholders_items[0]['Copyrightholder']['name'], 0, 1);
        ?>
        <div>
            <h2><a href="/copyrightholders/letter/<?= $letter ?>"><?= $letter ?></a></h2>
            <ul>
        <?php
        foreach ($copyrightholders_items as $copyrightholders_item)
        {
        	if ($lang == _ENG_)
        	{
            	if (empty($copyrightholders_item['Copyrightholder']['name' . $langFix])) continue;
            	$name = $copyrightholders_item['Copyrightholder']['name' . $langFix];
        	}
            else
            	$name = $copyrightholders_item['Copyrightholder']['name'] ? $copyrightholders_item['Copyrightholder']['name'] : $copyrightholders_item['Copyrightholder']['name_en'];
            $title_name = $name;
            if (mb_strlen ($name)>20){
                $name = mb_substr($name, 0, 17).'...';
            }
            echo '<li><a href="/copyrightholders/view/' . $copyrightholders_item['Copyrightholder']['id'] . '" title="'.$title_name.'">' . $name . '</a></li>';
        }
    ?>
            </ul>
        </div>
<?php
        if (strtolower($letter) == 'z'):
        ?>
    </div>
    <div class="spacer"></div>
	<div class="column">
        <?php
        endif;
    }
    ?>
    </div>
    <div class="spacer"></div>
</div>
<?php echo $autocomplete->autocomplete('CopyrightholderName', 'Copyrightholder/name', null, 30, 3); ?>
<?php $html->css('autocomplete','',array(),false);?>
