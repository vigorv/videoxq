<?php
App::import('Model', 'MediaModel');
class FilmLink extends MediaModel {

    var $name = 'FilmLink';

    var $belongsTo = array(
            'FilmVariant' => array('className' => 'FilmVariant',
                                'foreignKey' => 'film_variant_id',
                                'conditions' => '',
                                'fields' => '',
                                'order' => ''
            )
    );

    var $order = 'link';

    var $actsAs = array('Containable');
}
?>