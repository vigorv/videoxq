<?php
class Profession extends MediaModel {

    var $name = 'Profession';

    //The Associations below have been created with all possible keys, those that are not needed can be removed
    var $hasAndBelongsToMany = array(
            'Person' => array('className' => 'Person',
                        'joinTable' => 'persons_professions',
                        'foreignKey' => 'profession_id',
                        'associationForeignKey' => 'person_id',
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

        $sql = 'SELECT * from roles' . $date;

        $objects = $this->query($sql);

        $this->useDbConfig = $this->defaultConfig;

        foreach ($objects as $object)
        {
            extract($object['roles']);
            $Role = iconv('windows-1251', 'utf-8', $Role);

            $save = array($this->name => array('title' => $Role, 'id' => $ID));
            $this->create();
            $this->save($save);
        }
    }

	/**
	 * обновить список ролей согласно списку фильмов
	 *
	 * @param mixed $ids - список идентификаторов фильмов для обновления
	 */
    function migrateByFilmList($ids)
    {
        set_time_limit(50000000000);
        $this->useDbConfig = 'migration';

        $idsSQL = ' IN (' . implode(',', $ids) . ')';
        $sql = 'SELECT DISTINCT roles.* FROM roles INNER JOIN filmpersones ON (filmpersones.RoleID=roles.ID AND filmpersones.FilmID ' . $idsSQL . ')';
        $roles = $this->query($sql);

        $this->useDbConfig = $this->defaultConfig;

        $this->cacheQueries = false;
        foreach ($roles as $role)
        {
            extract($role['roles']);
            $Role = iconv('windows-1251', 'utf-8', $Role);
            $save = array($this->name => array('title' => $Role, 'id' => $ID));
            $this->create();
            $this->save($save);
        }
    }

}
?>