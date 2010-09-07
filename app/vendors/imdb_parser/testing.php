<?php
# testcases for imdb-parser with simpletest
# http://simpletest.sourceforge.net

require_once('simpletest/autorun.php');
require_once('class.imdb_parser.php');


class Test_IMDB_Parser extends UnitTestCase {
   
    function testMoviePI() {
        $imdb_website = file_get_contents ('http://imdb.com/title/tt0138704/'); //movie PI
        $IMDB_Parser = new IMDB_Parser;
        $this->assertEqual(trim($IMDB_Parser->getMovieTitle($imdb_website)), "Pi (1998)");
        $this->assertEqual(trim($IMDB_Parser->getMovieTagline($imdb_website)), "faith in chaos");
        $this->assertEqual(trim($IMDB_Parser->getMoviePlot($imdb_website)), "A paranoid mathematician searches for a key number that will unlock the universal patterns found in nature.");
        $a = $IMDB_Parser->getMovieActors($imdb_website);
        $this->assertEqual(trim($a[0]), "Sean Gullette");
        $this->assertEqual(trim($IMDB_Parser->getMovieRating($imdb_website)), "7.5/10");
        $a = $IMDB_Parser->getMovieLanguage($imdb_website);
        $this->assertEqual(trim($a[0]), "English");
        $this->assertEqual(trim($IMDB_Parser->getMovieColor($imdb_website)), "Black and White");
        $a = $IMDB_Parser->getMovieCountry($imdb_website);
        $this->assertEqual(trim($a[0]), "USA");
        $this->assertEqual(trim($IMDB_Parser->getMovieDirectedBy($imdb_website)), "Darren Aronofsky");
        $this->assertEqual(trim($IMDB_Parser->getMoviePicture($imdb_website)), "http://ia.media-imdb.com/images/M/MV5BMTg4NTc1MjMzM15BMl5BanBnXkFtZTcwMTA3MjcyMQ@@._V1._SY140_SX100_.jpg");
        $this->assertEqual(trim($IMDB_Parser->getMovieYear($imdb_website)), "1998");
        $this->assertEqual(trim($IMDB_Parser->getMovieRuntime($imdb_website)), "84 min");
        $this->assertEqual(trim($IMDB_Parser->getMovieStars($imdb_website)), "7.5");
        $this->assertEqual(trim($IMDB_Parser->getMovieAka($imdb_website)), "3,14159265358 (USA)&#32;(working title)");
        #$this->assertEqual(trim($IMDB_Parser->getMovieVotes($imdb_website)), "38,055 votes");
        $this->assertEqual(trim($IMDB_Parser->getMovieMPAA($imdb_website)), "Rated R for language and some disturbing images.");
        $this->assertEqual(trim($IMDB_Parser->getMovieAspectRatio($imdb_website)), "1.66 : 1");
        $this->assertEqual(trim($IMDB_Parser->getMovieAwards($imdb_website)), "9 wins\n&amp;\n7 nominations");
        $this->assertEqual(trim($IMDB_Parser->getMovieSoundMix($imdb_website)), "Dolby");
        $this->assertEqual(trim($IMDB_Parser->getMovieReleaseDate($imdb_website)), "10 July 1998 (USA)");
        $this->assertEqual(trim($IMDB_Parser->getMovieCompany($imdb_website)), "Harvest Filmworks");
    }

}
?>
