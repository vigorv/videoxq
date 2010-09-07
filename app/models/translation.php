<?php
App::import('Model', 'MediaModel');
class Translation extends MediaModel {

    var $name = 'Translation';

    //The Associations below have been created with all possible keys, those that are not needed can be removed
    var $hasMany = array(
            'Track' => array('className' => 'Track',
                                'foreignKey' => 'translation_id',
                                'dependent' => false,
                                'conditions' => '',
                                'fields' => '',
                                'order' => '',
                                'limit' => '',
                                'offset' => '',
                                'exclusive' => '',
                                'finderQuery' => '',
                                'counterQuery' => ''
            )
    );


    function migrate($objects)
    {
        set_time_limit(50000000000);
//        $this->useDbConfig = 'migration';
//
//        $sql = 'SELECT TypeOfMovie from films GROUP BY TypeOfMovie';
//
//        $objects = $this->query($sql);

        $this->useDbConfig = $this->defaultConfig;

        foreach ($objects as $object)
        {
//            extract($object['films']);
//            $TypeOfMovie = iconv('windows-1251', 'utf-8', $TypeOfMovie);

            $save = array($this->name => array('title' => $object));
            $this->create();
            $this->save($save);
        }
    }

}
?>