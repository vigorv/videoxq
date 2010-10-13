<?php

class IMDB_Parser2 extends IMDB_Parser
{
    function getMovieActors($imdb_website, $name_and_id=False)
    {
    	$hit = array();
        //if (preg_match('/<table class="cast_list">(.*){1,}<\/table>/imsU', $imdb_website, $hit))
        if (eregi('<table class="cast_list">(.*){1,}</table>', $imdb_website, $hit))
        {
//echo $imdb_website;
//var_dump($hit);
//exit;
            if ($name_and_id === True)
            {
                #name and id
                if (preg_match_all('/href="\/name\/(nm\d{1,8})\/">([^<]+)<\/a>/',$hit[0],$results, PREG_PATTERN_ORDER))
                {
                    return $results;
                }
                else
                {
                    return False;
                }
            }
            else
            {
                #only name, old version
                if (preg_match_all('/href="\/name\/nm\d{1,8}\/">([^<]+)<\/a>/',$hit[0],$results, PREG_PATTERN_ORDER))
                {
                    return $results[1];
                }
                else
                {
                    return False;
                }
            }
        }

        return False;
    }

    function getMovieDirectedBy($imdb_website, $all_directors=False)
    {
    	$hit = array();
        if (preg_match('/Director(.*)?<\/div>/imsU', $imdb_website, $hit))
        {
	        if (preg_match_all('/href="\/name\/[a-z0-9]+\/">(.+)<\/a>/simU', $hit[1], $hit, PREG_SET_ORDER))
	        {
	            return $hit;
	        }
        }
		return False;
    }

    function getMovieWriters($imdb_website)
    {
    	$hit = array();
        if (preg_match('/Writer(.*)?<\/div>/imsU', $imdb_website, $hit))
        {
	        if (preg_match_all('/href="\/name\/[a-z0-9]+\/">(.+)<\/a>/simU', $hit[1], $hit, PREG_SET_ORDER))
	        {
	            return $hit;
	        }
        }
		return False;
    }

    function getMovieCountry($imdb_website)
    {
    	$hit = array();
        if (preg_match('/Country(.*)?<\/div>/imsU', $imdb_website, $hit))
        {
//var_dump($hit);
//exit;
	        if (preg_match_all('/href="\/country\/[\S]{1,}">(.+)<\/a>/misU', $hit[1], $hit, PREG_SET_ORDER))
	        {
	            return $hit;
	        }
        }
		return False;
    }

	function getMovieGenres($imdb_website)
    {
        if (preg_match('/Genres?:(.*)?<\/div>/imsU', $imdb_website, $hit))
        {
//var_dump($hit);
//exit;
	        if (preg_match_all('/href="\/genre\/[\S]{1,}">(.+)<\/a>/misU', $hit[1], $hit, PREG_SET_ORDER))
	        {
	            return $hit;
	        }
        }
		return False;
    }

    function getMovieTitle($imdb_website)
    {
        if (preg_match('/<h1 class="header">(.*)?<span>/imsU', $imdb_website, $hit))
        {
//var_dump($hit);
//exit;
            return strip_tags($hit[1]);
        }
		return False;
    }

    function getMovieStars($imdb_website)
    {
        if (preg_match('/([0-9]{1,2}\.[0-9]{1,2})<span>\/10/imsU', $imdb_website, $hit))
        {
            return $hit[1];
        }
        else
        {
            return False;
        }
    }

    function getMovieStory($imdb_website)
    {
        if (preg_match('/Storyline<\/h2>(.*)?<span/imsU', $imdb_website, $hit))
        {
            return strip_tags($hit[1]);
        }
        else
        {
            return False;
        }
    }
}
