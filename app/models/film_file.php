<?php
App::import('Model', 'MediaModel');
class FilmFile extends MediaModel {

    var $name = 'FilmFile';

    var $belongsTo = array(
            'FilmVariant' => array('className' => 'FilmVariant',
                                'foreignKey' => 'film_variant_id',
                                'conditions' => '',
                                'fields' => '',
                                'order' => ''
            )
    );

    var $order = 'file_name';

    var $actsAs = array('Containable');
}
?>