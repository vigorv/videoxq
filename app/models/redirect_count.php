<?php
//App::import('Model', 'MediaModel');
class RedirectCount extends appModel
{

    var $name = 'RedirectCount';
    var $useTable='redirect_clicks';
    var $belongsTo = array(
            'Redirect' => array('className' => 'Redirect',
                                'foreignKey' => 'redirect_id',
                                'dependent' => false,
                                'conditions' => '',
                                'fields' => '',
                                'order' => ''
            )
    );
    
}
?>