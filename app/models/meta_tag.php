<?php
class MetaTag extends AppModel {

    var $name = 'MetaTag';
    var $hasAndBelongsToMany = array();

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
            $conditions = array('url LIKE'=>$url.'%');
        }
        else {
            $conditions = array('url'=>$url);
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
    public function getMetaTagByURL($url='', $recurse=false, $page=1, $perpage=0){
        $metatags = array();
        if ($recurse) {
            $conditions = array('url LIKE'=>$url.'%');
        }
        else {
            $conditions = array('url'=>$url);
        }
        $query_options = array('conditions'=>$conditions);
        $limit='';
        if ($perpage){
            $query_options[] = array('LIMIT' => $perpage);
            $query_options[] = array('OFFSET' => ($page-1)*$perpage);
        }
        
        $metatags = $this->find('all', $query_options);
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
            $conditions = array('id'=>intval($id));
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
        $metatags = array();
        if (!empty($id) && intval($id)){
            $metatags = $this->delete(intval($id));
        }
        return $metatags;
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
            $conditions = array('id'=>intval($id));
            $this->conditions = array('conditions'=>$conditions);
            $result = $this->save($data);
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
        return $result;
    }        
    
    
}
?>