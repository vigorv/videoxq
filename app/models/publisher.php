<?php
class Publisher extends MediaModel {

    var $name = 'Publisher';

    //The Associations below have been created with all possible keys, those that are not needed can be removed
    var $hasAndBelongsToMany = array(
            'Film' => array('className' => 'Film',
                        'joinTable' => 'films_publishers',
                        'foreignKey' => 'publisher_id',
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

        $limit = ' LIMIT %s, %s';
        $page = 1;
        $perPage = 100;

        $sql = 'SELECT * from companies' . $date;
        $query = $sql . sprintf($limit, $page - 1, $perPage);

        while ($objects = $this->query($query))
        {
            $this->useDbConfig = $this->defaultConfig;
            foreach ($objects as $object)
            {
                App::import('Vendor', 'Utils');
                $object['companies'] = Utils::iconvRecursive($object['companies']);
                extract($object['companies']);
                $save = array($this->name => array('title' => $Name, 'id' => $ID));
                $this->create();
                $this->save($save);
            }
            $this->useDbConfig = 'migration';
            $page++;
            $query = $sql . sprintf($limit, ($page - 1) * $perPage, $perPage);
        }
    }

	/**
	 * обновить список издателей согласно списку фильмов
	 *
	 * @param mixed $ids - список идентификаторов фильмов для обновления
	 */
    function migrateByFilmList($ids)
    {
        set_time_limit(50000000000);
        $this->useDbConfig = 'migration';

        $idsSQL = ' IN (' . implode(',', $ids) . ')';
        $sql = 'SELECT DISTINCT companies.* FROM companies INNER JOIN filmcompanies ON (filmcompanies.CompanyID=companies.ID AND filmcompanies.FilmID ' . $idsSQL . ')';
        $companies = $this->query($sql);

        $this->useDbConfig = $this->defaultConfig;

        $this->cacheQueries = false;
        foreach ($companies as $company)
        {
            extract($company['companies']);
            $Role = iconv('windows-1251', 'utf-8', $Role);
            $save = array($this->name => array('title' => $Role, 'id' => $ID));
            $this->create();
            $this->save($save);
        }
    }
}
?>