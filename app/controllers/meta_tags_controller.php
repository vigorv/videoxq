<?php
class metaTagsController extends AppController {

    var $name = 'MetaTags';
    //    var $viewPath = '';
    var $uses = array('MetaTag');

    var $helpers = array('Html','Form','Javascript','Autocomplete');

     /*
     * управление мета-тегами
     * 
     * 
     * 
     */
    function index(){
        $data = $this->MetaTag->find('all');
        $this->set('data', $data);
    }        

    /*
     * управление мета-тегами
     * 
     * 
     * 
     */
    function admin_index(){
        $default_rows_per_page = 30;
        if (!empty($this->data['MetaTag']['rows_per_page'])){
            $this->Session->write("MetaTagRowsPerPage", $this->data['MetaTag']['rows_per_page']);
        }
        if (!$this->Session->read("MetaTagRowsPerPage")){
            $this->Session->write("MetaTagRowsPerPage", $default_rows_per_page);
        }
        $rows_per_page = $this->Session->read("MetaTagRowsPerPage");
        
        
        //инициализируем массив данных для вьюхи
        $metatags = array();
        //url пустой. т.к. выбираем записи метатегов для всех url'ов, и в этом
        //нам поможет 2й параметр вызова ф-ции модели = true
        $url = '';
        //общее количество записаей метатегов
        $metatags['count'] = $this->MetaTag->getMetaTagCountByURL($url,true);

        
        $this->paginate = array(
        'limit' => $rows_per_page,
        'order' => array(
            'MetaTag.url' => 'asc'
            )
        );        
/*        
        $metatags['data'] = $this->MetaTag->getMetaTagByURL($url,true);
 */     $metatags['data'] = $this->paginate('MetaTag');
        $this->set('metatags',$metatags);
        
        
        
        
        $this->set('rows_per_page', $rows_per_page);
        $this->set('total_rows_count', $metatags['count']);
    }     
    /*
     * Добавление новой записи о метатегах
     * 
     * @param POST DATA данные формы при отправке запроса post 
     * 
     */
    function admin_add(){
        //инициализируем массив данных для вьюхи
        $data = array();
        if (!empty($this->data))
        {
            $validate = true;
            //проверим входные данные для записи
            //особо проверять не будем - админы ведь рулят изнутри,
            //а не вредители :) да и пустым может быть любое поле!
            $url = filter_var($this->data['MetaTag']['url'], FILTER_SANITIZE_STRING);
            $url = $this->Metatags->fixUrl($url);
            $url_original = filter_var($this->data['MetaTag']['url_original'], FILTER_SANITIZE_STRING);
            $title = filter_var($this->data['MetaTag']['title'], FILTER_SANITIZE_STRING);
            $description = filter_var($this->data['MetaTag']['description'], FILTER_SANITIZE_STRING);
            $keywords = filter_var($this->data['MetaTag']['keywords'], FILTER_SANITIZE_STRING);
            $title_en = filter_var($this->data['MetaTag']['title_en'], FILTER_SANITIZE_STRING);
            $description_en = filter_var($this->data['MetaTag']['description_en'], FILTER_SANITIZE_STRING);
            $keywords_en = filter_var($this->data['MetaTag']['keywords_en'], FILTER_SANITIZE_STRING);            
            $order = intval($this->data['MetaTag']['order']);
            $isbase = intval($this->data['MetaTag']['isbase']);

            if ($validate){
                //создадим новый массив, а то мало ли что нам там прилетело 
                //по post
                $new_data = array('MetaTag' => array(
                    'url' => $url,
                    'url_original' => $url_original,
                    'title' => $title,
                    'description' => $description,
                    'keywords' => $keywords,
                    'title_en' => $title_en,
                    'description_en' => $description_en,
                    'keywords_en' => $keywords_en,
                    'order' => $order,
                    'isbase' => $isbase
                ));

                //пишем в БД!
                if ($this->MetaTag->newMetaTag($new_data)) {
                    $this->Session->setFlash('Запись о мета-тегах добавлена!', true);
                    $this->redirect(array('action'=>'index'));
                    }
            }
            else{
                    $this->Session->setFlash('Ошибка. Заполните поля правильно', true);
                    //вернем юзеру его набранные строчки, 
                    //пусть делает работу над ошибками )))))
                    $data = $this->data['MetaTag'];
                    $this->set('data',$data);
                    //$this->redirect(array('action'=>'add'));
            }
        }else{        
            $this->set('data',$data);
        }
        
    }
    /*
     * Изменение записи о метатегах
     * 
     * @param POST DATA данные формы при отправке запроса post 
     * 
     */
    function admin_edit($id=0){
        //инициализируем массив данных для вьюхи
        $data = array();
        if (!empty($this->data))
        {
            $validate = true;
            //проверим входные данные для записи
            //особо проверять не будем - админы ведь рулят изнутри,
            //а не вредители :) да и пустым может быть любое поле!
            $id = intval($this->data['MetaTag']['id']);
            //если id неизвестен, или 0 то облом ((, данные непонятно к какой 
            //записи относятся, запись в БД отменяется :(
            if (!$id) {$validate = false;} 
            $url = filter_var($this->data['MetaTag']['url'], FILTER_SANITIZE_STRING);
            $url = $this->Metatags->fixUrl($url);
            $url_original = filter_var($this->data['MetaTag']['url_original'], FILTER_SANITIZE_STRING);
            $title = filter_var($this->data['MetaTag']['title'], FILTER_SANITIZE_STRING);
            $description = filter_var($this->data['MetaTag']['description'], FILTER_SANITIZE_STRING);
            $keywords = filter_var($this->data['MetaTag']['keywords'], FILTER_SANITIZE_STRING);
            $title_en = filter_var($this->data['MetaTag']['title_en'], FILTER_SANITIZE_STRING);
            $description_en = filter_var($this->data['MetaTag']['description_en'], FILTER_SANITIZE_STRING);
            $keywords_en = filter_var($this->data['MetaTag']['keywords_en'], FILTER_SANITIZE_STRING);            
            $order = intval($this->data['MetaTag']['order']);
            $isbase = intval($this->data['MetaTag']['isbase']);

            if ($validate){
                //создадим новый массив, а то мало ли что нам там прилетело по post
                $data = array('MetaTag' => array(
                    'id' => $id,
                    'url' => $url,
                    'url_original' => $url_original,
                    'title' => $title,
                    'description' => $description,
                    'keywords' => $keywords,
                    'title_en' => $title_en,
                    'description_en' => $description_en,
                    'keywords_en' => $keywords_en,
                    'order' => $order,
                    'isbase' => $isbase
                ));
                //пишем в БД!
                if ($this->MetaTag->editMetaTagById($id, $data)) {
                    $this->Session->setFlash('Запись о мета-тегах изменена!', true);
                    $this->redirect(array('action'=>'index'));
                    }
            }
            else{
                    $this->Session->setFlash('Ошибка. Заполните поля правильно', true);
                    //вернем юзеру его набранные строчки, 
                    //пусть делает работу над ошибками )))))
                    $data = $this->data;
                    $this->set('data',$data);
            }
        }else{
            //если id неизвестно, то валим отсюда, редактировтаь то нечего )))
            if (!intval($id)){$this->redirect(array('action'=>'index'));}
            $data = $this->MetaTag->getMetaTagById($id);
            $this->set('data',$data['MetaTag']);
        }
    }
    /*
     * удаление записи мета-тегов
     * 
     */    
    function admin_delete($id=0){
        if (!empty($id) && intval($id)){
            $result = $this->MetaTag->delMetaTagById($id);
            $msg = 'Запись о мета-тегах удалена!';
        }
        else{
            $msg = 'Ошибка невозможно удалить запись о мета-тегах. (не задан id записи)';
        }
        $this->Session->setFlash($msg, true);
        $this->redirect(array('action'=>'index'));
    }
    /*
     * Проверка результата выборки метатегов для URL
     * 
     */    
    function admin_check(){
        //инициализируем массив данных для вьюхи
        $data = array();
        if (!empty($this->data))
        {
            $validate = true;
            //проверим входные данные для записи
            //особо проверять не будем - админы ведь рулят изнутри,
            //а не вредители :) да и пустым может быть любое поле!
            
            $url = filter_var($this->data['MetaTag']['url'], FILTER_SANITIZE_STRING);
            $data['url'] = $url;
            $url = $this->_to_relative_url($url);
            $url = $this->Metatags->fixUrl($url);
            
            if ($validate){
                

           
                $this->Metatags->titleTag = '';
		$this->Metatags->keywordsTag = '';
		$this->Metatags->descriptionTag = '';
                $this->Metatags->get($url);
                $data['metatags_ru'] = array(
                    'title'=>$this->Metatags->titleTag,
                    'keywords'=>$this->Metatags->keywordsTag,
                    'description'=>$this->Metatags->descriptionTag);
                $this->Metatags->titleTag = '';
		$this->Metatags->keywordsTag = '';
		$this->Metatags->descriptionTag = '';
                $this->Metatags->get($url, 'en');
                
                $data['metatags_en'] = array(
                    'title'=>$this->Metatags->titleTag,
                    'keywords'=>$this->Metatags->keywordsTag,
                    'description'=>$this->Metatags->descriptionTag);
                
                
                $this->set('data',$data);
                
            }
            else{
                $this->Session->setFlash('Ошибка. Заполните поля правильно', true);
                //вернем юзеру его набранные строчки, 
                //пусть делает работу над ошибками )))))
                $data = $this->data;
                $this->set('data',$data);
            }
        }
    }

//------------------------------------------------------------------------------
    /*очистка url от начальных символов "/", "http://", "www" 
     * и пробелов по краям
     * 
     * @param string $url - строка url для чистки
     * @return string $url
     */
    function _to_relative_url($url=''){        
        $url = str_replace('http://www.', '', trim($url));
        $url = str_replace('http://', '', $url);
        $url = str_replace($_SERVER['SERVER_NAME'], '', $url);
        //$url = str_replace(Config::read('App.siteUrl'), '', $url);    
        //если есть начальный символ "/", удалим его
        if (strpos($url, '/')==0){
            $url = substr($url, 1, strlen($url)-1);
        }
        return $url;
    }

}
?>