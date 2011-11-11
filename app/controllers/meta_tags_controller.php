<?php
class metaTagsController extends AppController {

    var $name = 'MetaTags';
    //    var $viewPath = '';
    var $uses = array('MetaTag');

    var $helpers = array('Html','Form','Javascript','Autocomplete');
    /*    
    var $components = array('Phonetics', 'ExcelImport' => array(
                                                'param1' => '111111',
                                                'param2' => '222222'
                                             ));

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
    }     
        
//------------------------------------------------------------------------------
    /*очистка url от начальных символов "/", "http://", "www" 
     * и пробелов по краям
     * 
     * @param string $url - строка url для чистки
     * @return string $url
     */
    function _url_clear($url=''){        
        $url = str_replace('http://www.', '', trim($url));
        $url = str_replace('http://', '', $url);
        $url = str_replace($_SERVER['SERVER_NAME'], '', $url);
        //если есть начальный символ "/", удалим его
        if (strpos($url, '/')==0){
            $url = substr($url, 1, strlen($url)-1);
        }
        return $url;
    }

}
?>