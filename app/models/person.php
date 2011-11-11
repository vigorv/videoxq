<?php
App::import('Model', 'MediaModel');
class Person extends MediaModel {

    var $name = 'Person';
    var $useTable = 'persons';

    //The Associations below have been created with all possible keys, those that are not needed can be removed
    var $hasMany = array(
            /*'PersonComment' => array('className' => 'PersonComment',
                                'foreignKey' => 'person_id',
                                'dependent' => false,
                                'conditions' => '',
                                'fields' => '',
                                'order' => '',
                                'limit' => '',
                                'offset' => '',
                                'exclusive' => '',
                                'finderQuery' => '',
                                'counterQuery' => ''
            ),*/
            'PersonPicture' => array('className' => 'PersonPicture',
                                'foreignKey' => 'person_id',
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

    var $hasAndBelongsToMany = array(
/*            'AudioBook' => array('className' => 'AudioBook',
                        'joinTable' => 'audio_books_persons',
                        'foreignKey' => 'person_id',
                        'associationForeignKey' => 'audio_book_id',
                        'unique' => true,
                        'conditions' => '',
                        'fields' => '',
                        'order' => '',
                        'limit' => '',
                        'offset' => '',
                        'finderQuery' => '',
                        'deleteQuery' => '',
                        'insertQuery' => ''
            ),
            'AudioGroup' => array('className' => 'AudioGroup',
                        'joinTable' => 'audio_groups_persons',
                        'foreignKey' => 'person_id',
                        'associationForeignKey' => 'audio_group_id',
                        'unique' => true,
                        'conditions' => '',
                        'fields' => '',
                        'order' => '',
                        'limit' => '',
                        'offset' => '',
                        'finderQuery' => '',
                        'deleteQuery' => '',
                        'insertQuery' => ''
            ),
            'Book' => array('className' => 'Book',
                        'joinTable' => 'books_persons',
                        'foreignKey' => 'person_id',
                        'associationForeignKey' => 'book_id',
                        'unique' => true,
                        'conditions' => '',
                        'fields' => '',
                        'order' => '',
                        'limit' => '',
                        'offset' => '',
                        'finderQuery' => '',
                        'deleteQuery' => '',
                        'insertQuery' => ''
            ),*/
            'Film' => array('className' => 'Film',
                        'joinTable' => 'films_persons',
                        'foreignKey' => 'person_id',
                        'associationForeignKey' => 'film_id',
                        'unique' => true,
                        'conditions' => '',
                        'fields' => '',
                        'order' => '',
                        'limit' => '',
                        'offset' => '',
                        'finderQuery' => '',
                        'deleteQuery' => '',
                        'insertQuery' => '',
                        'with' => 'FilmsPerson'
            ),
            'Profession' => array('className' => 'Profession',
                        'joinTable' => 'persons_professions',
                        'foreignKey' => 'person_id',
                        'associationForeignKey' => 'profession_id',
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


    var $actsAs = array('Containable', 'Sphinx');

    function getPersonFilms($id, $order = 'FilmsPerson.profession_id ASC, Film.year ASC', $sqlCond = '')
    {
        $db =& ConnectionManager::getDataSource($this->useDbConfig);
        $sqlCondition = '`FilmsPerson`.`person_id` = ' . $db->value($id, 'integer');
        if (!empty($sqlCond))
        {
        	$sqlCondition .= ' AND ' . $sqlCond;
        }
        $sql = '
        SELECT `FilmsPerson`.`person_id`, `FilmsPerson`.`role`, `FilmsPerson`.`profession_id`,
               `Profession`.`id`, `Profession`.`title`, Film.year, Film.title, Film.id
        FROM `films_persons` AS `FilmsPerson`
        LEFT JOIN `professions` AS `Profession` ON (`FilmsPerson`.`profession_id` = `Profession`.`id`)
        LEFT JOIN `films` AS `Film` ON (`FilmsPerson`.`film_id` = `Film`.`id` AND `Film`.`active` = 1)
        WHERE ' . $sqlCondition . '  GROUP BY FilmsPerson.profession_id, Film.title, Film.year ORDER BY ' . $order;
        return $this->query($sql);
    }

    /**
     * Получает список букв для персон
     *
     * @return unknown
     */
    function getAlphabet()
    {
        if (!($alphabet = Cache::read('Catalog.peopleAlphabet', 'default')))
        {
            $sql = "select left(if (p.name <> '', p.name, p.name_en), 1) as letter
                    from persons as p
                    group by letter
                    HAVING letter REGEXP '[[:alpha:]]+'
                    ORDER BY letter";
            $result = $this->query($sql);
            $alphabet = Set::extract('/0/letter', $result);

            Cache::write('Catalog.peopleAlphabet', $alphabet, 'default');
        }
        return $alphabet;
    }


    /**
     * Получает список букв + 3 персоны для каждой буквы
     *
     * @return unknown
     */
    function getPeopleIndex()
    {
        $letters = $this->getAlphabet();
        $sql = "SELECT Person.name, Person.name_en, Person.id
                FROM persons AS Person
                WHERE name LIKE '%s%%'
                OR name_en LIKE '%s%%'
                ORDER BY RAND() LIMIT 3";
        $people = array();
        foreach ($letters as $letter)
        {
            $people[$letter] = $this->query(sprintf($sql, strtolower($letter), strtolower($letter)));
        }
        return $people;
    }

    function migrate($date)
    {
        ini_set('memory_limit', '1G');
        set_time_limit(50000000000);
        $this->useDbConfig = 'migration';

        $limit = ' LIMIT %s, %s';
        $page = 1;
        $perPage = 100;

        $sql = 'SELECT * from persones' . $date;
        $query = $sql . sprintf($limit, $page - 1, $perPage);

        $picturesCmd = '';

        $picturesFrom = 'cp -R -v "c:\\server\\vhosts\\media\\www\\';
        $picturesTo = ' "c:\\asdasd\\';
        $picturesCmd .= "@echo off\r\n";
        $picturesCmd .= 'md '.$picturesTo."photos\"\r\n";

        while ($people = $this->query($query))
        {

            $this->useDbConfig = $this->defaultConfig;
            foreach ($people as $person)
            {

                App::import('Vendor', 'Utils');
                $person = Utils::iconvRecursive($person);
                extract($person['persones']);

                $this->useDbConfig = 'migration';
                $professions = $this->query('select filmpersones.RoleID, filmpersones.PersonID from filmpersones LEFT JOIN roles ON (filmpersones.RoleID=roles.ID) where PersonID = ' . $ID . ' GROUP BY RoleID');
                $professions = array('Profession' =>
                               array('Profession' => $this->getHabtm($professions, array('filmpersones'),
                                               array('person_id' => 'PersonID', 'profession_id' => 'RoleID'))));


                $this->useDbConfig = $this->defaultConfig;
                $save = array('Person' => array('name' => $RusName, 'name_en' => $OriginalName,
                                                'url' => $OzonUrl, 'description' => $Description,
                                                'id' => $ID)
                              );
                $save = am($save, $professions);

                $this->create();
                $this->save($save);

            	Cache::delete('Catalog.person_'.$ID.'_film', 'people');
            	Cache::delete('Catalog.person_'.$ID, 'people');

                $this->bindModel(array('hasMany' => array('PersonPicture')), false);

                $this->PersonPicture->deleteAll(array('person_id' => $ID));
                $Images = explode("\n", $Images);

                $imgSql = 'INSERT INTO person_pictures (file_name, person_id) VALUES ';
                $values = array();
                foreach ($Images as $image)
                {
                    $image = trim($image);
                    if (empty($image))
                        continue;

                    $picturesCmd .= $picturesFrom . $image . '" ' . $picturesTo . $image . "\"\r\n";

                    $values[] = '(\'' . $image . '\', \'' . $ID . '\')';
                }
                if (!empty($values))
                {
                    $imgSql .= implode(', ', $values);
                    $this->query($imgSql);
                }
            }

            $this->useDbConfig = 'migration';
            $page++;
            $query = $sql . sprintf($limit, ($page - 1) * $perPage, $perPage);
        }

        file_put_contents(APP . 'migration_people_pics.cmd', $picturesCmd);
        unset($picturesCmd);
    }

	/**
	 * обновить список ролей согласно списку фильмов
	 *
	 * @param mixed $ids - список идентификаторов фильмов для обновления
	 */
    function migrateByFilmList($ids)
    {
        ini_set('memory_limit', '1G');
        set_time_limit(50000000000);
        $this->useDbConfig = 'migration';

        $limit = ' LIMIT %s, %s';
        $page = 1;
        $perPage = 100;

        $idsSQL = ' IN (' . implode(',', $ids) . ')';
        $sql = 'SELECT DISTINCT persones.* FROM persones INNER JOIN filmpersones ON (filmpersones.PersonID=persones.ID AND filmpersones.FilmID ' . $idsSQL . ')';
        $query = $sql . sprintf($limit, $page - 1, $perPage);

        while ($people = $this->query($query))
        {
            $this->useDbConfig = $this->defaultConfig;
	        $this->cacheQueries = false;
            foreach ($people as $person)
            {
                App::import('Vendor', 'Utils');
                $person = Utils::iconvRecursive($person);
                extract($person['persones']);

                $this->useDbConfig = 'migration';
                $professions = $this->query('select filmpersones.RoleID, filmpersones.PersonID from filmpersones LEFT JOIN roles ON (filmpersones.RoleID=roles.ID) where PersonID = ' . $ID . ' GROUP BY RoleID');
                $professions = array('Profession' =>
                               array('Profession' => $this->getHabtm($professions, array('filmpersones'),
                                               array('person_id' => 'PersonID', 'profession_id' => 'RoleID'))));

                $this->useDbConfig = $this->defaultConfig;
                $save = array('Person' => array('name' => $RusName, 'name_en' => $OriginalName,
                                                'url' => $OzonUrl, 'description' => $Description,
                                                'id' => $ID)
                              );
                $save = am($save, $professions);

                $this->create();
                $this->save($save);

            	Cache::delete('Catalog.person_'.$ID.'_film', 'people');
            	Cache::delete('Catalog.person_'.$ID, 'people');

                $this->bindModel(array('hasMany' => array('PersonPicture')), false);

                $this->PersonPicture->deleteAll(array('person_id' => $ID));
                $Images = explode("\n", $Images);

                $imgSql = 'INSERT INTO person_pictures (file_name, person_id) VALUES ';
                $values = array();
                foreach ($Images as $image)
                {
                    $image = trim($image);
                    if (empty($image))
                        continue;

                    $values[] = '(\'' . $image . '\', \'' . $ID . '\')';
                }
                if (!empty($values))
                {
                    $imgSql .= implode(', ', $values);
                    $this->query($imgSql);
                }
            }

            $this->useDbConfig = 'migration';
            $page++;
            $query = $sql . sprintf($limit, ($page - 1) * $perPage, $perPage);
        }
    }
}
?>