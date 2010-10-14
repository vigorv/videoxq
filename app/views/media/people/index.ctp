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
    ksort($people);
    foreach (array_keys($people) as $letter)
    {
    	if ($lang == _ENG_)
    	{
    		if ((strtolower($letter) < 'a') || (strtolower($letter) > 'z')) continue;
    	}
        echo '<li><a href="/people/letter/' . $letter . '">' . $letter . '</a></li>';
        if (strtolower($letter) == 'z')
            echo '</ul><ul>';
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
    <form class="searchName" action="/people/index" method="post">
        <input type="text" class="textInput" name="data[Person][search]">
        <input type="submit" class="button" value="<?php __('Search for person'); ?>">
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
    	usort($people, "sortsize");
    }
    foreach ($people as $letter => $persons)
    {
    	if ($lang == _ENG_)
    	{
    		if ((strtolower($letter) < 'a') || (strtolower($letter) > 'z')) continue;
    	}

    	if (!empty($isSearch))
    		$letter = mb_substr($persons[0]['Person']['name'], 0, 1);
        ?>
        <div>
            <h2><a href="/people/letter/<?= $letter ?>"><?= $letter ?></a></h2>
            <ul>
        <?php
        foreach ($persons as $person)
        {
        	if ($lang == _ENG_)
        	{
            	if (empty($person['Person']['name' . $langFix])) continue;
            	$name = $person['Person']['name' . $langFix];
        	}
            else
            	$name = $person['Person']['name'] ? $person['Person']['name'] : $person['Person']['name_en'];
            echo '<li><a href="/people/view/' . $person['Person']['id'] . '">' . $name . '</a></li>';
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
