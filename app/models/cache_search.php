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

    
    
    public function getDataCrossSearchCache($search = '', $order_by = '', $genres = ''){
        $records = array();
          //$records = Cache::read('Catalog.crossSearch', 'crossSearch');
        if (!$records) {
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
            
            //Cache::write('Catalog.crossSearch', $records, 'crossSearch');
    	}
        return $records;      
    }
    
    
}
