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
	 * обновить список жанров согласно списку фильмов
	 *
	 * @param mixed $ids - список идентификаторов фильмов для обновления
	 */
    function migrateByFilmList($ids)
    {
        set_time_limit(50000000000);
        $this->useDbConfig = 'migration';

        $idsSQL = ' IN (' . implode(',', $ids) . ')';
        $sql = 'SELECT DISTINCT genres.* FROM genres INNER JOIN filmgenres ON (filmgenres.GenreID=genres.ID AND filmgenres.FilmID ' . $idsSQL . ')';
        $genres = $this->query($sql);

        $this->useDbConfig = $this->defaultConfig;

        $this->cacheQueries = false;
        foreach ($genres as $genre)
        {
            extract($genre['genres']);
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
    function getGenresWithFilmCount($lang = 0)
    {
    	if (empty($lang))
			$lang = Configure::read('Config.language');
		$langFix = '';
		if ($lang == _ENG_) $langFix = '_imdb';

    	$res = Cache::read('Genre.withCount' . $langFix, 'media');
    	if (empty($res))
    	{
	        $sql =
	        'select g.id, g.title' . $langFix . ', count(fg.film_id) as count
	         from genres as g
	         join films_genres as fg on (fg.genre_id=g.id)
	         join films as f on (fg.film_id = f.id AND f.active = 1)
	         where g.is_delete = 0
	         group by g.title' . $langFix . ' order by g.title' . $langFix . ' ASC';

	        $records = $this->query($sql);
	        $res = array();
	        foreach ($records as $record)
	        {
	            $res[$record['g']['id']] = $record['g']['title' . $langFix] . ' (' . $record['0']['count'] . ')';
	        }
			Cache::write('Genre.withCount' . $langFix, $res, 'media');
    	}

        return $res;
    }

    /**
     * Получает список жанров с кол-вом лицензионных фильмов
     *
     * @return unknown
     */
    function getGenresWithLicFilmCount($lang = 0)
    {
    	if (empty($lang))
			$lang = Configure::read('Config.language');
		$langFix = '';
		if ($lang == _ENG_) $langFix = '_imdb';

    	$res = Cache::read('Genre.withLicCount' . $langFix, 'media');
    	if (empty($res))
    	{
	        $sql =
	        'select g.id, g.title' . $langFix . ', count(fg.film_id) as count
	         from genres as g
	         join films_genres as fg on (fg.genre_id=g.id)
	         join films as f on (fg.film_id = f.id AND f.active = 1 AND f.is_license = 1)
	         where g.is_delete = 0
	         group by g.title' . $langFix . ' order by g.title' . $langFix . ' ASC';

	        $records = $this->query($sql);
	        $res = array();
	        foreach ($records as $record)
	        {
	            $res[$record['g']['id']] = $record['g']['title' . $langFix] . ' (' . $record['0']['count'] . ')';
	        }
			Cache::write('Genre.withLicCount' . $langFix, $res, 'media');
    	}

        return $res;
    }
}
?>