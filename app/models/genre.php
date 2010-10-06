<?php
class Genre extends MediaModel {

    var $name = 'Genre';

    //The Associations below have been created with all possible keys, those that are not needed can be removed
    var $hasAndBelongsToMany = array(
            'Film' => array('className' => 'Film',
                        'joinTable' => 'films_genres',
                        'foreignKey' => 'genre_id',
                        'associationForeignKey' => 'film_id',
                        'unique' => true,
                        'conditions' => '',
                        'fields' => '',
                        'order' => '',
                        'limit' => '',
                        'offset' => '',
                        'finderQuery' => '',
                        'deleteQuery' => '',
                        'insertQuery' => ''
            )
    );


    function migrate($date)
    {
        set_time_limit(50000000000);
        $this->useDbConfig = 'migration';

        $sql = 'SELECT * from genres' . $date;

        $objects = $this->query($sql);

        $this->useDbConfig = $this->defaultConfig;

        foreach ($objects as $object)
        {
            extract($object['genres']);
            $Name = iconv('windows-1251', 'utf-8', $Name);
            $imdbGenre = iconv('windows-1251', 'utf-8', $imdbGenre);

            $save = array($this->name => array('title' => $Name, 'title_imdb' => $imdbGenre, 'id' => $ID));
            $this->create();
            $this->save($save);
        }
    }


    /**
     * Получает список жанров с кол-вом фильмов
     *
     * @return unknown
     */
    function getGenresWithFilmCount()
    {
		$lang = Configure::read('Config.language');
		$langFix = '';
		if ($lang == _ENG_) $langFix = '_imdb';

        $sql =
        'select g.id, g.title' . $langFix . ', count(fg.film_id) as count
         from genres as g
         join films_genres as fg on (fg.genre_id=g.id)
         join films as f on (fg.film_id = f.id AND f.active = 1)
         where g.is_delete = 0
         group by g.title order by g.title ASC';

        $records = $this->query($sql);
        $res = array();
        foreach ($records as $record)
        {
            $res[$record['g']['id']] = $record['g']['title' . $langFix] . ' (' . $record['0']['count'] . ')';
        }
        return $res;
    }
}
?>