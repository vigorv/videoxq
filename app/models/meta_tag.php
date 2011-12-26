<?php
class MetaTag extends AppModel {

    var $name = 'MetaTag';
    var $hasAndBelongsToMany = array();

    /*
     * 
     * 
     * 
     * 
     */
    public function getEmailTo(){
        $email = '';
        $conditions = array('url' => 'emailto');
        $query_options = array('conditions'=>$conditions);
        $data = $this->find('all', $query_options);
        
        if (!empty($data)){
            $email= $data[0]['MetaTag']['url_original'];
        }
        else{
            //если записи emailto не существует то создадим ее
            $data = array('MetaTag' => array(
                'url' => 'emailto',
                'url_original' => ''
                ));
            $this->conditions = array();
            $result = $this->save($data);
        }
        return $email;
    }
    /*
     * 
     * 
     * 
     * 
     */
    public function setEmailTo($email = ''){
        $conditions = array('url' => 'emailto');
        /*
        $data = array('MetaTag' => array(
            'url_original' => $email
        ));
        $this->conditions = array('conditions'=>$conditions);
        $result = $this->save($data);  
        */
        /*
         $sql = 'UPDATE meta_tags SET url_original=`' . $email
                . '` WHERE url = `emailto`';
        $result = $this->query($sql);
         */
        $data = array(
            'url_original' => '"'.$email.'"'
            );
        $result = $this->updateAll($data, $conditions);
        return $result;        
    }    
    
    /* возвращает коли-во записей метатегов для указанного URL, если
     * $recurse=true, то смотрит вхождение данного URL в более длинные URL )))
     *
     * @param string $url - строка адреса без http://videoxq.com/
     * @param boolean $recurse - рекурсия,(искать ли вхождение в более длинных
     *                          URL)
     * @return int $metatags_count - кол-во найденых записей
     */    
    public function getMetaTagCountByURL($url='', $recurse=false){

        $metatags_count = 0;
        if ($recurse) {
            $conditions = array('url LIKE'=>$url.'%','NOT' => array('url' => 'emailto'));
        }
        else {
            $conditions = array('url'=>$url,'NOT' => array('url' => 'emailto'));
        }
        $query_options = array('conditions'=>$conditions);
        $metatags_count = $this->find('count', $query_options);
        return $metatags_count;
    }

    /* возвращает записи метатегов для указанного URL, если $recurse=true, то смотрит
     * вхождение данного URL в более длинные URL )))
     *
     * @param string $url - строка адреса без http://videoxq.com/
     * @param boolean $recurse - рекурсия,(искать ли вхождение в более длинных
     *                          URL)
     * @return mixed $metatags -
     */
    public function getMetaTagByURL($url='', $page=1, $perpage=0){
	$cacheprofile='meta';
	if($page==1)$cacheprofile='metafp';
        //$hash_url = md5($url);
        $hash_url = $this->cacheFileNameGenerate($url);
        $cacheprofile = 'meta';
        if (!($metatags = Cache::read('Metatags.'.$hash_url, $cacheprofile)))
        {
            $metatags = array();
            $conditions = array('url'=>$url, 'NOT' => array('url' => 'emailto') );
            $query_options = array('conditions'=>$conditions);
            $limit='';
            if ($perpage){
                $query_options[] = array('LIMIT' => $perpage);
                $query_options[] = array('OFFSET' => ($page-1)*$perpage);
            }
            $metatags = $this->find('all', $query_options);
            Cache::write('Metatags.'.$hash_url, $metatags, $cacheprofile);
            /*
            echo 'cache writed!';
            $fh = fopen('/log.txt', 'a');
            $data =  date("m.d.y H:i:s")." - by URL\r\n".'url: '.$url ."\r\n".'md5: '.$md5_url."\r\n";
            fwrite($fh, $data);
            fclose($fh);
            */
        }
        return $metatags;
    }


    /* удаление записи метатегов по совпадению с $url
     *
     * @param string $url - строка адреса без http://videoxq.com/
     * @param boolean $recurse - рекурсия,(искать ли вхождение в более длинных
     *                          URL)
     * @return boolean $result
     */
    public function delMetaTagByURL($url='', $recurse=false){
        $result = false;
        return $result;
    }

    /* получение данных записи метатегов с id = $id
     *
     * @param integer $id - id метатега
     * @return mixed $metatags
     */
    public function getMetaTagById($id=null){
        $metatags = array();
        if (!empty($id) && intval($id)){
            $conditions = array('id'=>intval($id), 'NOT' => array('url' => 'emailto'));
            $query_options = array('conditions'=>$conditions);
            $metatags = $this->find('first', $query_options);
        }
        return $metatags;
    }

    /* удаление записи метатегов с id = $id
     *
     * @param integer $id - id метатега
     * @return boolean $result
     */
    public function delMetaTagById($id=null){
        $result = array();
        if (!empty($id) && intval($id)){
            $result = $this->delete(intval($id));
//--            $result = true;
        }
        //если удаление записи было успешно, то почистим кэш!!!
        if ($result){
            //нам нужен url соответствующий этой записи
            $data = $this->find('first',array('fields'=>array('url'),'conditions'=>array('id'=>$id, 'NOT' => array('url' => 'emailto'))));
            /*
            $hash = md5($data['MetaTag']['url']);
            Cache::delete('Metatags.'.$hash, 'meta');
            Cache::delete('Metatags.'.$hash, 'metafp');
            */
            $hash_url = $this->cacheFileNameGenerate($data['MetaTag']['url']);
            Cache::delete('Metatags.'.$hash_url, 'meta');
        }
        return $result;
    }

    /* изменение данных для записи метатегов с id = $id
     *
     * @param integer $id - id метатега
     * @param mixed $data - массив с данными для внесения изменений
     * @return boolean $result
     */
    public function editMetaTagById($id=null, $data=null){
        $result = false;
        if(!empty($id) && intval($id) && !empty($data)){
            $conditions = array('id'=>intval($id), 'NOT' => array('url' => 'emailto'));
            $this->conditions = array('conditions'=>$conditions);
            $result = $this->save($data);
        }
        //если редактирование записи было успешно, то почистим кэш!!!
        if ($result){
            //нам нужен url соответствующий этой записи
            $data = $this->find('first',array('fields'=>array('url'),'conditions'=>array('id'=>$id, 'NOT' => array('url' => 'emailto'))));
            /*
            $hash = md5($data['MetaTag']['url']);
            Cache::delete('Metatags.'.$hash, 'meta');
            Cache::delete('Metatags.'.$hash, 'metafp');*/
            $hash_url = $this->cacheFileNameGenerate($data['MetaTag']['url']);
            Cache::delete('Metatags.'.$hash_url, 'meta');
        }
        return $result;
    }

    /* добавление новой записи метатегов с данными из массива $data
     *
     * @param mixed $data - массив с данными для новой записи
     * @return boolean $result
     */
    public function newMetaTag($data=null){
        $result = false;
        if(!empty($data)){
            $result = $this->save($data);
        }
        //если вставка записи была успешной, то почистим кэш!!!
        if ($result){
            //нам нужен url соответствующий этой записи, для этого узнаем id,
            //только что вставленной записи
            $id = $this->getLastInsertID();
            $data = $this->find('first',array('fields'=>array('url'),'conditions'=>array('id'=>$id, 'NOT' => array('url' => 'emailto'))));
            /*
            $hash = md5($data['MetaTag']['url']);
            Cache::delete('Metatags.'.$hash, 'meta');
            Cache::delete('Metatags.'.$hash, 'metafp');*/
            $hash_url = $this->cacheFileNameGenerate($data['MetaTag']['url']);
            Cache::delete('Metatags.'.$hash_url, 'meta');
        }
        return $result;
    }

    /* возвращает записи метатегов для указанного URL, если $recurse=true, то смотрит
     * вхождение данного URL в более длинные URL )))
     *
     * @param string $url - строка адреса без http://videoxq.com/
     * @param boolean $recurse - рекурсия,(искать ли вхождение в более длинных
     *                          URL)
     * @return mixed $metatags -
     */
    public function getMetaTagsByURLMask($url='', $page=1, $perpage=0){
	$cacheprofile='meta';
	if($page==1)$cacheprofile='metafp';
        //$hash_url = md5($url);
        $hash_url = $this->cacheFileNameGenerate($url);
        /*pr('getMetaTagsByURLMask_md5("'.$url.'"): '.$md5_url);*/
        $cacheprofile = 'meta';
        if (!($metatags = Cache::read('Metatags.'.$hash_url , $cacheprofile)))
        {
            
            $metatags = array();
            $conditions = '"' . $url . '" LIKE `MetaTag`.`url` AND `url` != "emailto"';

            $query_options = array('conditions'=>$conditions);
            $limit='';
            if ($perpage){
                $query_options[] = array('LIMIT' => $perpage);
                $query_options[] = array('OFFSET' => ($page-1)*$perpage);
            }
            $metatags = $this->find('all', $query_options);
            Cache::write('Metatags.'.$hash_url, $metatags, $cacheprofile);
            /*
            echo 'cache writed!';
            $fh = fopen('/log.txt', 'a');
            $data =  date("m.d.y H:i:s")." - by URL Mask\r\n".'url: '.$url ."\r\n".'md5: '.$md5_url."\r\n";
            fwrite($fh, $data);
            fclose($fh); 
            */
        }
        return $metatags;
    }

        /* преобразование входного url в относительный без начального '/',
         * т.е. очистка url от начальных символов "/", "http://", "www" 
         * пробелов и '/' по краям
         * 
         * @param string $url - строка url для чистки
         * @return string $url
         */ 
       public function toRelativeUrl($url=''){        
            $url = str_replace('http://www.', '', trim($url));
            $url = str_replace('http://', '', $url);
            $url = str_replace($_SERVER['SERVER_NAME'], '', $url);
            //$url = str_replace(Config::read('App.siteUrl'), '', $url);    
            //если есть начальный символ "/", удалим его
            if (strpos($url, '/')==0){
                $url = substr($url, 1, strlen($url)-1);
            }
            //удалим символ '/' в еконце строки если он есть
            if (strpos($url, '/') == (strlen($url)-1)){
                $url = substr($url, 0, strlen($url)-1);
            }
            return $url;
        }
        /* Формирование имени файла кэша на основе url без md5, максимально 
         * приближенное к маске
         * например:
         * "http://www.videoxq.gt/news/index/10" -> "news@index@10"
         * 
         * @param string $url - входной url
         * @param boolean $is_base 
         * @return string $url - выходной url
         */
        public function cacheFileNameGenerate($url = '', $is_base = true){
            if ($url){
                //для начала преобразуем в относительный путь
                $url = $this->toRelativeUrl($url);
                //заменяем '/' на '@';
                $url = str_replace('/', '@', $url);
                //заменяем '#' на '@';
                $url = str_replace('#', '@', $url);
                //заменяем '?' на '@';
                $url = str_replace('?', '@', $url);
                //заменяем ':' на '#';
                $url = str_replace(':', '#', $url);
                //заменяем ',' на '#';
                $url = str_replace(',', '#', $url);
                //если текущий урл является базовым то укажем это, добавив в 
                //конец строки '@'
                if ($is_base){
                    //$url .= '@';
                }
                //ссылки вида "Controller", "Controller@index" (прчем "@index" -
                //это крайний элемент url) будем считать 
                //идентичными, поэтому отсекаем от "@index"
                if (strpos($url, '@index')==(strlen($url)-6) ){
                    $url = trim (str_replace('@index', '', $url)); 
                }
                //и аналогично 
                //ссылки вида "Controller", "Controller@view" (прчем "@view" -
                //это крайний элемент url) будем считать 
                //идентичными, поэтому отсекаем от "@view"
                if (strpos($url, '@view')==(strlen($url)-5) ){
                    $url = trim (str_replace('@view', '', $url)); 
                }
                    
            }
            return $url;
        }
        
        /* возвращает массив с именами найденых файлов кэша, для указанного url 
         * 
         * @param string $url - входной url
         * @param 
         * @return array $cacheFileNames - найденые файлы кэша
         * 
         */ 
        public function findCacheFilesByUrl($url = ''){
            uses('Folder');
            $Folder =& new Folder();
            
            $cacheFileNames = array();
            if ($url){
                $cacheCfg = Cache::config('meta');
                $path = $cacheCfg['settings']['path'];
                $Folder->cd($path);
                $cacheFileNames = $Folder->find('/cache_metatags'.$url.'/');
            }
            return $cacheFileNames;
        }    
    
}
?>