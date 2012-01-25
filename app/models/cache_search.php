<?php

class CacheSearch extends AppModel {

/*
 таблица "cache_searches"
--------------------------------------------------------------------------------
id                  int(10)
modified            datetime
created_original    datetime
modified_original   datetime
hidden              tinyint(1)
title               varchar(255)
title_original      varchar(255)
year                int(10)
country             varchar(30)
directors           varchar(255)
actors              varchar(255)
id_original         int(10)
site_id             int(10)
poster              varchar(255)
url                 varchar(255)
is_license          tinyint(4) 	
media_rating        float
imdb_rating         float(3,1) 	
-------------------------------------------------------------------------------- 
*/    
    public $name = 'CacheSearch';
    public $alias = 'CS_Film';
/*
 var $hasAndBelongsToMany = array(
        'Genre' => array('className' => 'Genre',
            'joinTable' => 'films_genres',
            'foreignKey' => 'film_id',
            'associationForeignKey' => 'genre_id',
            'unique' => true,
            'conditions' => array('Genre.is_delete' => 0),
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'finderQuery' => '',
            'deleteQuery' => '',
            'insertQuery' => ''
        )
    );
    var $belongsTo = array(
        'FilmType' => array('className' => 'FilmType',
            'foreignKey' => 'film_type_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Thread' =>
        array('className' => 'Thread',
            'foreignKey' => 'thread_id'
        )
        
    );
*/


    public function getDataCrossSearchCache($search = '', $order_by = '', $genres = ''){
        $records = array();
          //$records = Cache::read('Catalog.crossSearch', 'crossSearch');
        if (!$records) {
        $limit = 30;    
        $or = array ('OR' => array ( 
                                'title LIKE'=>'%'.$search.'%', 
                                'title_original LIKE'=>'%'.$search.'%'
                             )
                    );
        
        $and = array('AND'=>array('hidden'=>0, $or ));
        $conditions = $and ;
        
        $query_options = array(
            'contain' => array(
                'FilmType',
                'Genre',
                'FilmVariant' => array('VideoType')
                ),
            'conditions'=>$conditions,
            'limit'=>$limit);
        $records = $this->find('all', $query_options);
        //pr($records);
        //exit;
        
            
/*            
            $where = '';
                
            if (!empty($search)){
                $where = ' WHERE 
                            CS_Film.title LIKE "%'.$search.'%" OR
                            CS_Film.title_original LIKE "%'.$search.'%"';
            }
            if (!empty($sort_by)){
                $order_by = 'ORDER BY ' . $sort_by;
            }
            
            
            $sql =  'SELECT  
                        *
                     FROM 
                        cache_searches CS_Film 
                     ' . 
                    $where. $order_by . ' LIMIT 30';
	    $records = $this->query($sql);
            
            
*/            
            //Cache::write('Catalog.crossSearch', $records, 'crossSearch');
    	}
        return $records;      
    }
    
    
}
