<?php

class FilmFast extends AppModel {

    var $name = 'FilmFast';
    var $useTable = 'films';

    /**
     * Получение списка фильмов
     * @param int $lic      0 - всё /1  - только лиц./2 - всё что не лиц.     флаг лицензии
     * @param int $page     - тут должана быть страница, начало с 1
     * @param int $per_page - результатов на странице 
     * @return array $films -  массив с фильмами
     */
    private function GetPersons($id, $count=0) {
        $limit = '';
        if ($count > 0)
            $limit = ' Limit ' . (int) $count;
        $persons = $this->query('SELECT * FROM films_persons AS FilmsPerson 
                INNER JOIN persons  AS Person ON Person.id = FilmsPerson.person_id
                INNER JOIN professions ON FilmsPerson.profession_id = professions.id
                Where FilmsPerson.film_id=' . $id . $limit);
        return$persons;
    }

    private function GetGenres($id, $count=0) {
        $limit = '';
        if ($count > 0)
            $limit = ' Limit ' . (int) $count;
        $genres = $this->query('SELECT genres.title,genres.title_imdb  FROM films_genres 
                            LEFT JOIN genres on genres.id = films_genres.genre_id
                            WHERE films_genres.film_id =' . $id . $limit);
        return $genres;
    }

    function GetFilms($conditions= array('lic' => 1, 'variant' => 0), $page=1, $per_page=20) {
        $license = '';
        $variant = '';
        $var_join = '';
        $direction = '';
        $order = 'Film.year';
        $fields = 'Film.*, FilmPicture.*, MediaRating.*';
        if ($page > 1)
            $offset = ($page - 1) * $per_page;
        else
            $offset = 0;
        $cache_name = implode("_", array_keys($conditions));
        $cache_name .= implode("_", $conditions);
        if (!$films = Cache::read('Catalog.film_fast_' . $cache_name . '_' . $page . '_' . $per_page, 'searchres')) {
            if ($conditions['lic'] == 1)
                $license = ' and Film.is_license = 1';
            else if ($conditions['lic'] == 2)
                $license = ' and Film.is_license = 0';
            if (isset($conditions['variant'])) {
                $var_join .= 'INNER JOIN film_variants  as FilmVariant ON FilmVariant.film_id = Film.id';
                $variant = ' and FilmVariant.video_type_id =' . $conditions['variant'];
            }
            if (isset($conditions['genre_id'])) {
                $var_join.=' INNER JOIN films_genres as FilmGenre ON FilmGenre.film_id = Film.id ';
                $variant .=' and FilmGenre.genre_id =' . $conditions['genre_id'];
            }
            if (isset($conditions['order'])) {
                $order = $conditions['order'];
            }
            if (isset($conditions['fields'])) {
                $fields = $conditions['fields'];
            }
            if (isset($conditions['direction'])) {
                $direction = $conditions['direction'];
            }
            if (isset($condition['title'])) {
                $variant .=" AND (Film.title LIKE '%" . $condition['title'] . "%' OR
                    Film.title_en LIKE '%" . $condition['title'] . "%')";
            }

            $films = $this->query('SELECT ' . $fields . ' FROM films as Film ' .
                            $var_join . '  
                            LEFT JOIN film_pictures as FilmPicture ON FilmPicture.film_id = Film.id
                            LEFT JOIN media_ratings as MediaRating on MediaRating.object_id = Film.id
                           Where Film.active = 1 ' . $license . ' ' . $variant . ' 
                           and FilmPicture.type = "smallposter"
                           and MediaRating.type = "film"
                            GROUP BY Film.id                           
                           ORDER BY ' . $order . ' ' . $direction . ' Limit  ' . $offset . ',' . $per_page);

            foreach ($films as &$film) {
                $genres = $this->GetGenres($film['Film']['id']);
                if (!empty($genres))
                    $film['Genre'] = $genres;
                //foreach($genres as $genre) $film['Genre'][] = $genre['genres'];
                //$persons = $this->GetPersons($film['Film']['id'], 3);
                if (!empty($persons))
                    $film['Person'] = $persons;
            }
            Cache::write('Catalog.film_fast_' . $cache_name . '_' . $page . '_' . $per_page, $films, 'searchres');
        }
        return $films;
    }

    function GetFilmsCount($conditions= array('lic' => 1, 'variant' => 0)) {
        $license = '';
        $variant = '';
        $var_join = '';
        if ($conditions['lic'] == 1)
            $license = ' and Film.is_license = 1';
        else if ($conditions['lic'] == 2)
            $license = ' and Film.is_license = 0';
        if (isset($conditions['variant'])) {
            $var_join .= 'INNER JOIN film_variants  as FilmVariant ON FilmVariant.film_id = Film.id';
            $variant = ' and FilmVariant.video_type_id =' . $conditions['variant'];
        }
        if (isset($conditions['title'])) {
            $variant .=" AND (Film.title LIKE '%" . $conditions['title'] . "%' OR
                    Film.title_en LIKE '%" . $conditions['title'] . "%')";
        }
        if (isset($conditions['genre_id'])) {
            $var_join.=' INNER JOIN films_genres as FilmGenre ON FilmGenre.film_id = Film.id ';
            $variant .=' and FilmGenre.genre_id =' . $conditions['genre_id'];
        }
        $films = $this->query('SELECT Count(*) FROM films as Film ' .
                        $var_join . '  
                           Where Film.active = 1 ' . $license . ' ' . $variant);
        if (!empty($films))
            return $films[0][0]['Count(*)'];
        else
            return 0;
    }

    /**
     * same as GetFilms with minor changes for api
     * 
     */
    function GetFilmsA($conditions= array('lic' => 1, 'variant' => 0), $page=1, $per_page=20) {
        $license = '';
        $variant = '';
        $var_join = '';
        $direction = '';
        $order = 'Film.year';
        $fields = 'Film.*, FilmPicture.*, MediaRating.*';
        if ($page > 1)
            $offset = ($page - 1) * $per_page;
        else
            $offset = 0;
        $cache_name = implode("_", array_keys($conditions));
        $cache_name .= implode("_", $conditions);
        if (!$films = Cache::read('Catalog.film_fast_' . $cache_name . '_' . $page . '_' . $per_page, 'searchres')) {
            if ($conditions['lic'] == 1)
                $license = ' and Film.is_license = 1';
            else if ($conditions['lic'] == 2)
                $license = ' and Film.is_license = 0';
            if (isset($conditions['variant'])) {
                $var_join .= 'INNER JOIN film_variants  as FilmVariant ON FilmVariant.film_id = Film.id';
                $variant = ' and FilmVariant.video_type_id =' . $conditions['variant'];
            }
            if (isset($conditions['genre_id'])) {
                $var_join.=' INNER JOIN films_genres as FilmGenre ON FilmGenre.film_id = Film.id ';
                $variant .=' and FilmGenre.genre_id =' . $conditions['genre_id'];
            }
            if (isset($conditions['order']))
                $order = $conditions['order'];
            if (isset($conditions['fields'])) {
                $fields = $conditions['fields'];
            }
            if (isset($conditions['direction'])) {
                $direction = $conditions['direction'];
            }
            if (isset($conditions['title'])) {
                $variant .=" AND (Film.title LIKE '%" . $conditions['title'] . "%' OR
                    Film.title_en LIKE '%" . $conditions['title'] . "%')";
            }
            $films = $this->query('SELECT ' . $fields . ' FROM films as Film ' .
                            $var_join . '  
                            LEFT JOIN film_pictures as FilmPicture ON (FilmPicture.film_id = Film.id and FilmPicture.type = "smallposter")
                            LEFT JOIN media_ratings as MediaRating on (MediaRating.object_id = Film.id AND  MediaRating.type = "film" )
                           Where Film.active = 1 ' . $license . ' ' . $variant . ' 
                           GROUP BY Film.id                            
                           ORDER BY ' . $order . ' ' . $direction . ' Limit  ' . $offset . ',' . $per_page);
            Cache::write('Catalog.film_fast_' . $cache_name . '_' . $page . '_' . $per_page, $films, 'searchres');
        }
        return $films;
    }

    /*
     *  Get  film for api
     */

    function GetFilmA($id =0, $conditions= array('lic' => 1)) {
        $license = '';
        $variant = '';
        $var_join = '';
        $fields = 'Film.*, FilmPicture.*, MediaRating.*';

        if ($conditions['lic'] == 1)
            $license = ' and Film.is_license = 1';
        else if ($conditions['lic'] == 2)
            $license = ' and Film.is_license = 0';
        if (isset($conditions['variant'])) {
            $var_join .= 'INNER JOIN film_variants  as FilmVariant
                ON (FilmVariant.film_id = Film.id and FilmVariant.video_type_id =' . $conditions['variant'].')
                   LEFT JOIN film_files as FilmFile ON FilmFile.film_variant_id =FilmVariant.id';
        }
        if (isset($conditions['fields'])) {
            $fields = $conditions['fields'];
        }

        $films = $this->query('SELECT ' . $fields . ' FROM films as Film '.
                            $var_join.'
                            LEFT JOIN film_pictures as FilmPicture ON (FilmPicture.film_id = Film.id and FilmPicture.type = "poster")
                            LEFT JOIN media_ratings as MediaRating on (MediaRating.object_id = Film.id AND  MediaRating.type = "film" )
                           Where Film.active = 1 ' . $license . ' 
                           and Film.id = ' . $id . ' Limit  1');
        if (!empty($films)){
            /*
        $genres = $this->GetGenres($films[0]['Film']['id']);
        if (!empty($genres))
            $films[0]['Genre'] = $genres;
        //foreach($genres as $genre) $film['Genre'][] = $genre['genres'];
        $persons = $this->GetPersons($films[0]['Film']['id'], 3);
        if (!empty($persons))
            $films['Person'] = $persons;
*/
       
        return $films;
        } 
        return NULL;
    }

    function GetFilmOv($id=0, $lic=1) {
        $license = '';
        if ($lic == 1)
            $license = ' and Film.is_license = 1';
        else if ($lic == 2)
            $license = ' and Film.is_license = 0';

        $films = $this->query('SELECT Film.* , FilmPicture.*, MediaRating.* FROM films as Film 
                            LEFT JOIN film_pictures as FilmPicture ON FilmPicture.film_id = Film.id
                            LEFT JOIN media_ratings as MediaRating on MediaRating.object_id = Film.id
                           Where Film.active = 1 ' . $license . '  
                           and FilmPicture.type = "smallposter"
                           and MediaRating.type = "film"       
                           and Film.id = ' . $id . ' Limit  1');
        $genres = $this->GetGenres($films[0]['Film']['id']);
        if (!empty($genres))
            $films[0]['Genre'] = $genres;
        //foreach($genres as $genre) $film['Genre'][] = $genre['genres'];
        $persons = $this->GetPersons($films[0]['Film']['id'], 3);
        if (!empty($persons))
            $films['Person'] = $persons;
        return $films[0];
    }

    function GetFilm($id =0, $lic =1) {
        App::import('Film');
        if ($id > 0) {
            if (!$films = Cache::read('Catalog.film_view_fast_' . $id, 'searchres')) {
                $this->Film = new Film;
                $this->Film->recursive = 1;
                $params = array();
                if ($lic == 1)
                    $params['conditions']['is_license'] = 1;
                else if ($lic == 2)
                    $params['conditions']['is_license'] = 0;
                $params['conditions']['active'] = 1;
                $params['conditions']['Film.id'] = $id;
                $this->Film->contain(array('FilmType',
                    'Genre',
                    'Thread',
                    'FilmPicture' => array('conditions' => array('type <>' => 'smallposter')),
                    'Country',
                    'FilmVariant' =>
                    array('conditions' => array('video_type_id' => 13),
                        'FilmLink', 'FilmFile' => array('order' => 'file_name'), 'VideoType', 'Track' => array('Language', 'Translation')),
                    'MediaRating',
                        )
                );
                $film = $this->Film->find('first', $params);
                Cache::write('Catalog.film_view_' . $id, $film, 'media');
            }
            return $film;
        }
        return null;
    }

    function SearchByTitle($title, $lic=1, $page=0, $per_page=20) {
        $films = array();
//pr($searchFor);
        App::import('Film');
        $this->Film = new Film;
        if (!$films = Cache::read('Catalog.film_search_' . $title, 'searchres')) {
            $this->Film->contain(array(
                'FilmType', 'Genre',
                'Thread',
                'FilmPicture' => array('conditions' => array('type <>' => 'smallposter')),
                'Country',
                'FilmVariant' => array('FilmFile' => array('order' => 'file_name'), 'VideoType', 'Track' => array('Language', 'Translation')),
                'MediaRating',
                    )
            );
            $this->Film->recursive = 1;
            $pagination['Film']['conditions'] = array('is_license' => '1');
            $pagination['Film']['limit'] = 20;
            $pagination['Film']['sphinx']['matchMode'] = SPH_MATCH_ALL;
            $pagination['Film']['sphinx']['index'] = array('films'); //ИЩЕМ ПО ИНДЕКСУ ФИЛЬМОВ
            $pagination['Film']['sphinx']['sortMode'] = array(SPH_SORT_EXTENDED => '@relevance DESC');
            $pagination['Film']['search'] = $title;
            $films = $this->Film->find('all', $pagination["Film"]);
            Cache::write('Catalog.film_search_' . $title, 'searchres');
        }
        return $films;
    }

    function GetFullGenresList($lic=1) {
        $genres = $this->query("SELECT id,title,title_imdb FROM genres");
        $data = array();
        foreach ($genres as &$genre) {
            if (isset($genre['genres']['id']) && $genre['genres']['id']) {
                $genre_count = $this->query("SELECT COUNT('films_genres.id') as count from films_genres 
                        INNER JOIN films ON films_genres.film_id = films.id
                        WHERE films_genres.genre_id =" . $genre['genres']['id'] . " AND films.is_license=$lic");
                $genre_count = (int) $genre_count[0][0]['count'];
                if (($genre_count > 0)) {
                    $genre['genres']['count'] = $genre_count;
                    $data[] = $genre;
                }
            }
        }
        return $data;
    }

}

?>
