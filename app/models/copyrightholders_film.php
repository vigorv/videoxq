<?php
App::import('Model', 'MediaModel');
class CopyrightholdersFilm extends MediaModel {

    var $name = 'CopyrightholdersFilm';

    var $belongsTo =    array(
            'Copyrightholder' => array('className' => 'Copyrightholder',
                                'foreignKey' => 'copyrightholder_id',
                                'conditions' => '',
                                'fields' => '',
                                'order' => ''
                                ),
                      'Film' => array('className' => 'Film',
                                'foreignKey' => 'film_id',
                                'conditions' => '',
                                'fields' => '',
                                'order' => ''
                                )
                        );

/*
    function setCopyrightholdersFilmsLinks($cfid_arr=null){
        if (!empty($cfid_arr) && $cfid_arr){
//
        }
    }
*/
}
?>