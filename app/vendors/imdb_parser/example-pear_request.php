<?php
/*
* Example using with PEAR Package HTTP_Request
* http://pear.php.net/package/HTTP_Request
*/

//get a video detail site from imdb.com
require_once "HTTP/Request.php";
$req =& new HTTP_Request('http://imdb.com/title/tt0138704/'); // movie PI

if (!PEAR::isError($req->sendRequest())) 
{
    $imdb_website = $req->getResponseBody();
}

//include the parser-class
include_once("class.imdb_parser.php");

//init the class
$IMDB_Parser = new IMDB_Parser;

// output movie-picture (html)
print "<p>".$IMDB_Parser->getMoviePictureHtml($imdb_website)."</p>";

// output movie-title
print "<br />Title: ".$IMDB_Parser->getMovieTitle($imdb_website);

// output movie-tagline
print "<br />Tagline: ".$IMDB_Parser->getMovieTagline($imdb_website);

// output movie-plot
print "<br />Plot: ".$IMDB_Parser->getMoviePlot($imdb_website);

// output movie-actors (array)
print "<br />Actors: ";
foreach($IMDB_Parser->getMovieActors($imdb_website) as $value)
{
    print $value." | ";
}

// output movie-actors (array) (name and ID)
print "<br />Actors (link, ID, name): ";
$a_actors = $IMDB_Parser->getMovieActors($imdb_website, $name_and_id=True);
for ($i=0; $i<count($a_actors[1]); $i++)
{
    print $a_actors[0][$i].", ";
    print $a_actors[1][$i].", ";
    print $a_actors[2][$i].", ";
    print ' | ';
}

// output movie-user rating
print "<br />User Rating: ".$IMDB_Parser->getMovieRating($imdb_website);

// output movie-languages (array)
print "<br />Languages: ";
foreach($IMDB_Parser->getMovieLanguage($imdb_website) as $value)
{
    print $value." | ";
}

// output movie-color
print "<br />Color: ".$IMDB_Parser->getMovieColor($imdb_website);

// output movie-country (array)
print "<br />Country: ";
foreach($IMDB_Parser->getMovieCountry($imdb_website) as $value)
{
    print $value." | ";
}

// output movie-directed by
print "<br />Directed by: ".$IMDB_Parser->getMovieDirectedBy($imdb_website);

// output movie-picture path
print "<br />Picture Path: ".$IMDB_Parser->getMoviePicture($imdb_website);

// output movie-genres (array)
print "<br />Genres: | ";
foreach($IMDB_Parser->getMovieGenres($imdb_website) as $value)
{
    print $value." | ";
}

// output movie-year
print "<br />Year: ".$IMDB_Parser->getMovieYear($imdb_website);

// output runtime
print "<br />Runtime: ".$IMDB_Parser->getMovieRuntime($imdb_website);

// output moviestars
print "<br />Stars: ".$IMDB_Parser->getMovieStars($imdb_website);

// output also known as
print "<br />Also known as: ".$IMDB_Parser->getMovieAka($imdb_website);

// output votes
print "<br />Votes: ".$IMDB_Parser->getMovieVotes($imdb_website);

// output MPAA
print "<br />MPAA: ".$IMDB_Parser->getMovieMPAA($imdb_website);

// output Aspect Ratio
print "<br />Aspect Ratio: ".$IMDB_Parser->getMovieAspectRatio($imdb_website);

// output Awards
print "<br />Awards: ".$IMDB_Parser->getMovieAwards($imdb_website);

// output Sound Mix
print "<br />Sound Mix: ".$IMDB_Parser->getMovieSoundMix($imdb_website);

// ouput Release Date
print "<br />Release Date: ".$IMDB_Parser->getMovieReleaseDate($imdb_website);

// ouput Company 
print "<br />Company: ".$IMDB_Parser->getMovieCompany($imdb_website);
?>
