<?php
class FilmsPerson extends MediaModel {

    var $name = 'FilmsPerson';
    var $useTable = 'films_persons';

    var $belongsTo = array('Film', 'Person', 'Profession');
    var $actsAs = array('Containable');
}
?>