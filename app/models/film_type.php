<?php
class FilmType extends MediaModel {

    var $name = 'FilmType';
    var $validate = array(
        'title' => array('/.+/')
    );

    //The Associations below have been created with all possible keys, those that are not needed can be removed
    var $hasMany = array(
            'Film' => array('className' => 'Film',
                                'foreignKey' => 'film_type_id',
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

        $this->useDbConfig = $this->defaultConfig;

        foreach ($objects as $object)
        {
            $save = array($this->name => array('title' => $object));
            $this->create();
            $this->save($save);
        }
    }


    /**
     * Получает список типов фильмов с кол-вом фильмов для каждого типа
     *
     * @return unknown
     */
    function getFilmTypesWithFilmCount()
    {
		$lang = Configure::read('Config.language');
		$langFix = '';
		if ($lang == _ENG_) $langFix = '_' . _ENG_;
        $sql =
        'select ft.id, ft.title' . $langFix . ', count(f.id) as count
         from film_types as ft
         join films as f on (f.film_type_id=ft.id AND f.active = 1)
         group by ft.id order by ft.title ASC';

        $records = $this->query($sql);
        $res = array();
        foreach ($records as $record)
        {
            $res[$record['ft']['id']] = $record['ft']['title' . $langFix] . ' (' . $record['0']['count'] . ')';
        }
        return $res;

    }
}
?>