<?php
	switch ($site)
	{
		case"videoxq":
			extract($film);
			$desc = '&laquo;' . $Film['title'] . '&raquo;<br />' . $app->implodeWithParams(', ', $Country) . ', ' . $Film['year'];
			if ($Film['imdb_rating'] != 0)
			{
				$desc .= '<br /><strong>IMDb: </strong>' . $Film['imdb_rating'];
			}
            $directors = array();
            $story     = array();
            $actors    = array();
            foreach ($persons as $personRow)
            {
                extract($personRow);
                if (!empty($Person['name']))
                    $pName = $Person['name'];
                else
                    $pName = $Person['name_en'];

                $pLink = '<a href="' . '/people/view/' . $Person['id'] . '">' . $pName . '</a>';
                if (isset($Profession[1]))
                    $directors[] = $pLink;
                if (isset($Profession[2])
                    || isset($Profession[22]))
                    $story[] = $pLink;
                if (isset($Profession[3])
                    || isset($Profession[4]))
                $actors[] = $pLink;
            }

            if ($authUser['userid'] == 0)
            {
                $actors = array_slice($actors, 0, 2);
            }

			if (!empty($directors))
			{
				$desc .= '<br /><strong>Режиссёр: </strong>' . implode(', ', $directors);
			}

			if (!empty($story))
			{
				$desc .= '<br /><strong>Сценарий: </strong>' . implode(', ', $story);
			}

			if (!empty($actors))
			{
				$desc .= '<br /><strong>В ролях: </strong>' . implode(', ', $actors);
			}

    		if (!empty($Genre))
    		{
    			$desc .= '<br /><strong>Жанр: </strong>' . $app->implodeWithParams(', ', $Genre);
	    	}
	    	$description = $desc;

		break;
	}

	switch ($detail)
	{
		case "all":
			echo'
				<table>
				<tr valign="top">
					<td>
						<a href="' . $link . '"><img height="150" width="80" src="' . $picture. '" /></a>
					</td>
					<td>
					' . $description . '
					</td>
				</tr>
				</table>
			';
		break;

		case "poster":
			echo '
				<a title="'.$title.'" href="#" onclick="return ' . $site . '.openPoster(this)" id="' . time() . '_' . $id . '"><img height="150" width="80" src="' . $picture. '" /></a>
			';
		break;
	}