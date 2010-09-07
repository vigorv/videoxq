<?php
	$curGenre = 0; $closeLi = '';
	echo'<ul>';
	foreach ($films as $film)
	{
		if ($film['g']['id'] <> $curGenre)
		{
			$curGenre = $film['g']['id'];
			echo $closeLi . '<li>' . $genres[$curGenre];
			$closeLi = '</li>';
		}
		echo '<br /><a href="/media/view/' . $film['Film']['id'] . '">' . $film['Film']['title'] . '</a>';
	}
	echo'</ul>';