<?php
class Country extends MediaModel {

    var $name = 'Country';

    //The Associations below have been created with all possible keys, those that are not needed can be removed
    var $hasAndBelongsToMany = array(
            'Film' => array('className' => 'Film',
                        'joinTable' => 'countries_films',
                        'foreignKey' => 'country_id',
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

        $sql = 'SELECT * from countries' . $date;

        $countries = $this->query($sql);

        $this->useDbConfig = $this->defaultConfig;

        foreach ($countries as $country)
        {
            extract($country['countries']);
            $Name = iconv('windows-1251', 'utf-8', $Name);
            $imdbCountry = iconv('windows-1251', 'utf-8', $imdbCountry);
            $save = array($this->name => array('title' => $Name, 'title_imdb' => $imdbCountry, 'id' => $ID));
            $this->create();
            $this->save($save);

        }
    }

	/**
	 * обновить список стран согласно списку фильмов
	 *
	 * @param mixed $ids - список идентификаторов фильмов для обновления
	 */
    function migrateByFilmList($ids)
    {
        set_time_limit(50000000000);
        $this->useDbConfig = 'migration';

        $idsSQL = ' IN (' . implode(',', $ids) . ')';
        $sql = 'SELECT DISTINCT countries.* FROM countries INNER JOIN filmcountries ON (filmcountries.CountryID=countries.ID AND filmcountries.FilmID ' . $idsSQL . ')';

        $countries = $this->query($sql);

        $this->useDbConfig = $this->defaultConfig;

        $this->cacheQueries = false;
        foreach ($countries as $country)
        {
            extract($country['countries']);
            $Name = iconv('windows-1251', 'utf-8', $Name);
            $imdbCountry = iconv('windows-1251', 'utf-8', $imdbCountry);
            $save = array($this->name => array('title' => $Name, 'title_imdb' => $imdbCountry, 'id' => $ID));
            $this->create();
            $this->save($save);
        }
    }

    /**
     * Получает список стран с кол-вом фильмов для каждой страны
     *
     * @return unknown
     */
    function getCountriesWithFilmCount()
    {
		$lang = Configure::read('Config.language');
		$langFix = '';
		if ($lang == _ENG_) $langFix = '_imdb';

		$res = Cache::read('Countries.filmCount' . $langFix, 'media');
		if (!$res)
		{
	        $sql =
	        'select c.id, c.title' . $langFix . ', count(cf.film_id) as count
	         from countries as c
	         join countries_films as cf on (cf.country_id=c.id)
	         join films as f on (cf.film_id = f.id AND f.active = 1)
	         group by c.id order by c.title ASC';

	        $records = $this->query($sql);
	        $res = array();
	        foreach ($records as $record)
	        {
	            $res[$record['c']['id']] = $record['c']['title' . $langFix] . ' (' . $record['0']['count'] . ')';
	        }
			Cache::write('Countries.filmCount' . $langFix, $res, array('config' => 'media'));
		}

        return $res;

    }

}
?>