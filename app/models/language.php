<?php
App::import('Model', 'MediaModel');
class Language extends MediaModel {

    var $name = 'Language';

    //The Associations below have been created with all possible keys, those that are not needed can be removed
/*    var $hasMany = array(
            'AudioBookFile' => array('className' => 'AudioBookFile',
                                'foreignKey' => 'language_id',
                                'dependent' => false,
                                'conditions' => '',
                                'fields' => '',
                                'order' => '',
                                'limit' => '',
                                'offset' => '',
                                'exclusive' => '',
                                'finderQuery' => '',
                                'counterQuery' => ''
            ),
            'BookFile' => array('className' => 'BookFile',
                                'foreignKey' => 'language_id',
                                'dependent' => false,
                                'conditions' => '',
                                'fields' => '',
                                'order' => '',
                                'limit' => '',
                                'offset' => '',
                                'exclusive' => '',
                                'finderQuery' => '',
                                'counterQuery' => ''
            ),
            'Track' => array('className' => 'Track',
                                'foreignKey' => 'language_id',
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
*/
    var $languages = array('Русский', 'Оригинал');

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