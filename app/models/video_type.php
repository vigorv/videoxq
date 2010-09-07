<?php
class VideoType extends MediaModel {

    var $name = 'VideoType';

    //The Associations below have been created with all possible keys, those that are not needed can be removed
    var $hasMany = array(
            'FilmVariant' => array('className' => 'FilmVariant',
                                'foreignKey' => 'video_type_id',
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


    function migrate($date)
    {
        set_time_limit(50000000000);
        $this->useDbConfig = 'migration';

        $sql = 'SELECT Quality from films '. $date . ' GROUP BY Quality';

        $objects = $this->query($sql);

        $this->useDbConfig = $this->defaultConfig;

        foreach ($objects as $object)
        {
            extract($object['films']);
            $Quality = iconv('windows-1251', 'utf-8', $Quality);

            if ($this->findByTitle($Quality))
                continue;
            $save = array($this->name => array('title' => $Quality, 'dir' => $Quality));
            $this->create();
            $this->save($save);
        }
    }

    /**
     * Получает список типов видео фильмов с кол-вом фильмов для каждого типа
     *
     * @return unknown
     */
    function getVideoTypesWithFilmCount()
    {
    	if (!$res = Cache::read('Catalog.vtypes_w_count', 'searchres'))
    	{
	        $sql =
	        'select vt.id, vt.title, count(distinct f.id) as count
	         from video_types as vt
	         inner join film_variants as fv on (fv.video_type_id=vt.id)
	         inner join films as f on (f.id=fv.film_id AND f.active = 1)
	         group by vt.id order by vt.title ASC';

	        $records = $this->query($sql);
	        $res = array();
	        foreach ($records as $record)
	        {
	            $res[$record['vt']['id']]["id"] = $record['vt']['id'];
	            $res[$record['vt']['id']]["title"] = $record['vt']['title'];
	            $res[$record['vt']['id']]["count"] = $record[0]['count'];
	        }
		   	Cache::write('Catalog.vtypes_w_count', $res, 'searchres');
    	}

        return $res;

    }
}
?>