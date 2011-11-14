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
        //инициализируем массив данных для вьюхи
        $metatags = array();
        //url пустой. т.к. выбираем записи метатегов для всех url'ов, и в этом
        //нам поможет 2й параметр вызова ф-ции модели = true
        $url = '';
        //общее количество записаей метатегов
        $metatags['count'] = $this->MetaTag->getMetaTagCountByURL($url,true);
        $metatags['data'] = $this->MetaTag->getMetaTagByURL($url,true);
        $this->set('metatags',$metatags);
        
        $res = $this->_url_clear('/');
    }     
    
    function admin_add(){
        //инициализируем массив данных для вьюхи
        $data = array();
        if (!empty($this->data))
        {
            $validate = true;
            //проверим входные данные для записи
            //особо проверять не будем - админы ведь рулят изнутри,
            //а не вредители :) да и пустым может быть любое поле!
            $url = filter_var($this->data['MetaTags']['url'], FILTER_SANITIZE_STRING);
            $title = filter_var($this->data['MetaTags']['title'], FILTER_SANITIZE_STRING);
            $description = filter_var($this->data['MetaTags']['description'], FILTER_SANITIZE_STRING);
            $keywords = filter_var($this->data['MetaTags']['keywords'], FILTER_SANITIZE_STRING);
            $title_en = filter_var($this->data['MetaTags']['title_en'], FILTER_SANITIZE_STRING);
            $description_en = filter_var($this->data['MetaTags']['description_en'], FILTER_SANITIZE_STRING);
            $keywords_en = filter_var($this->data['MetaTags']['keywords_en'], FILTER_SANITIZE_STRING);            
            $order = intval($this->data['MetaTags']['order']);
            $isbase = intval($this->data['MetaTags']['isbase']);

            if ($validate){
                //создадим новый массив, а то мало ли что нам там прилетело по post
                $new_data = array('Direction' => array(
                    'url' => $url,
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
            if ($this->MetaTag->save($new_data)) {
                $this->Session->setFlash('Запись о мета-тегах добавлена!', true);
                $this->redirect(array('action'=>'index'));
                    }
            }
            else{
                    $this->Session->setFlash('Ошибка. Заполните поля правильно', true);
                    //вернем юзеру его набранные строчки, 
                    //пусть делает работу над ошибками )))))
                    $data = $this->data;
                    $this->set('data',$data);
                    $this->redirect(array('action'=>'add'));
            }
        }else{        
            $this->set('data',$data);
        }
        
    }
    
    function admin_edit($id=0){
        //инициализируем массив данных для вьюхи
        $metatags = array();
        //url пустой. т.к. выбираем записи метатегов для всех url'ов, и в этом
        //нам поможет 2й параметр вызова ф-ции модели = true
        $url = '';
        //общее количество записаей метатегов
        $metatags['count'] = $this->MetaTag->getMetaTagCountByURL($url,true);
        $metatags['data'] = $this->MetaTag->getMetaTagByURL($url,true);
        $this->set('metatags',$metatags);
        
        $res = $this->_url_clear('/');
    }
        
    function admin_delete($id=0){
        //инициализируем массив данных для вьюхи
        $metatags = array();
        //url пустой. т.к. выбираем записи метатегов для всех url'ов, и в этом
        //нам поможет 2й параметр вызова ф-ции модели = true
        $url = '';
        //общее количество записаей метатегов
        $metatags['count'] = $this->MetaTag->getMetaTagCountByURL($url,true);
        $metatags['data'] = $this->MetaTag->getMetaTagByURL($url,true);
        $this->set('metatags',$metatags);
        
        $res = $this->_url_clear('/');
    }
            
//------------------------------------------------------------------------------
    /*очистка url от начальных символов "/", "http://", "www" 
     * и пробелов по краям
     * 
     * @param string $url - строка url для чистки
     * @return string $url
     */
    function _url_clear($url=''){        
/*        
        $url = str_replace('http://www.', '', trim($url));
        $url = str_replace('http://', '', $url);
        $url = str_replace($_SERVER['SERVER_NAME'], '', $url);
        //если есть начальный символ "/", удалим его
        if (strpos($url, '/')==0){
            $url = substr($url, 1, strlen($url)-1);
        }
        return $url;
 */
        return $this->Metatags->fixUrl($url);
    }

}
?>