<?php
App::import('component', 'BlocksParent');

class BlockMediaComponent extends BlocksParentComponent
{
    /**
     * Получаем список жанров и кол-вом фильмов,
     * а также выставляем значения для расширенного поиска
     *
     * @param unknown_type $args
     * @return unknown
     */
    function indexGenres($args)
    {
        $this->data['Film']['ex'] = !empty($this->controller->params['named']['ex']) ? true : false;
        $this->data['Film']['direction'] = !empty($this->controller->params['named']['direction']) && $this->controller->params['named']['direction'] == 'desc' ? 1 : 0;
        $this->data['Film']['sort'] = !empty($this->controller->params['named']['sort']) ? $this->controller->params['named']['sort'] : null;
        $this->data['Film']['type'] = !empty($this->controller->params['named']['type']) ? $this->controller->params['named']['type'] : null;
        $this->data['Film']['country'] = !empty($this->controller->params['named']['country']) ? $this->controller->params['named']['country'] : null;
        $this->data['Film']['genre'] = !empty($this->controller->params['named']['genre']) ? $this->controller->params['named']['genre'] : null;
        $this->data['Film']['is_license'] = !empty($this->controller->params['named']['is_license']) ? $this->controller->params['named']['is_license'] : null;
        $this->data['Film']['imdb_start'] = !empty($this->controller->params['named']['imdb_start']) ? $this->controller->params['named']['imdb_start'] : null;
        $this->data['Film']['imdb_end'] = !empty($this->controller->params['named']['imdb_end']) ? $this->controller->params['named']['imdb_end'] : null;
        $this->data['Film']['year_start'] = !empty($this->controller->params['named']['year_start']) ? $this->controller->params['named']['year_start'] : null;
        $this->data['Film']['year_end'] = !empty($this->controller->params['named']['year_end']) ? $this->controller->params['named']['year_end'] : null;
        $this->data['Film']['searchsimple'] = $this->data['Film']['search'] = !empty($this->controller->params['named']['search']) ? $this->controller->params['named']['search'] : null;


        $cache = '+1 day';

        $postFix = '';
        if (!$this->controller->isWS)
        {
        	$postFix = 'Licensed';
        }

        $filter = Cache::read('Catalog.indexFilter' . $postFix, 'block');
        if (empty($filter))
        {
        	$filter = array();
	        $filter['allowDownload'] = checkAllowedMasks(Configure::read('Catalog.allowedIPs'), $_SERVER['REMOTE_ADDR']);

			//$lang = Configure::read('Config.language');
			$lang = $this->controller->Session->read('language');
			if ($lang == _ENG_)
			{
		        $filter['sort']['Film.modified'] = 'by date';
		        $filter['sort']['Film.year'] = 'by year';
		        $filter['sort']['Film.hits'] = 'by popularity';
		        $filter['sort']['MediaRating.rating'] = 'by rating';
		        $filter['sort']['Film.imdb_rating'] = 'by imdb.com rating';
			}
			else
			{
		        $filter['sort']['Film.modified'] = 'по дате добавления';
		        $filter['sort']['Film.year'] = 'по году выпуска';
		        $filter['sort']['Film.hits'] = 'по популярности';
		        $filter['sort']['MediaRating.rating'] = 'по рейтингу';
		        $filter['sort']['Film.imdb_rating'] = 'по рейтингу imdb.com';
			}
/*
			if ($this->controller->isWS)
			{
	        	$filter['genres'] = $this->controller->Film->Genre->getGenresWithFilmCount($lang);
	    	}
	        else
	        {
	        	$filter['genres'] = $this->controller->Film->Genre->getGenresWithLicFilmCount($lang);
	    	}
//*/
//*
        	$filter['genres'] = $this->controller->Film->Genre->getGenresWithFilmCount($lang);
//*/
	        $filter['countries'] = $this->controller->Film->Country->getCountriesWithFilmCount();
	        $filter['types'] = $this->controller->Film->FilmType->getFilmTypesWithFilmCount();
	        $filter['imdb'] = range(0, 10);
	        $filter['is_license'] = range(0, 1);
	        $filter['allowDownload'] = checkAllowedMasks(Configure::read('Catalog.allowedIPs'), $_SERVER['REMOTE_ADDR']);

	        Cache::write('Catalog.indexFilter' . $postFix, $filter, array('config' => 'block'));
    	}
        return $filter;
    }

    /**
     * Получаем список самых популярных фильмов
     *
     * @return unknown
     */
    function topList()
    {
        if ($filter = Cache::read('Catalog.topFilms', 'block'))
        {
            return $filter;
        }
        $films = $this->controller->Film->find('all', array('order' => 'hits DESC',
                                                            'limit' => 10, 'fields' => array('Film.id, Film.title, Film.title_en'),
                                                            'contain' => array(),
                                                            'callbacks' => false,
                                                            'group' => 'id'));

        Cache::write('Catalog.topFilms', $films, array('config' => 'block', 'duration' => '+1 hour'));

        return $films;
    }

    /**
     * Последние комменты к фильмам
     *
     * @return unknown
     */
    function lastComments()
    {
        $this->controller->set('activity_view_link', '/media/view/');
        if ($comments = Cache::read('Catalog.lastComments', 'block'))
        {
            return $comments;
        }

        $comments = $this->controller->Film->getActivity();

        Cache::write('Catalog.lastComments', $comments, 'block');

        return $comments;
    }

    function rocketBlock()
    {

    }
}
?>