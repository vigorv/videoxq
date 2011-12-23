<?php
class metaTagsController extends AppController {

    var $name = 'MetaTags';
    //    var $viewPath = '';
    var $uses = array('MetaTag');
    var $components = array('Email');
    var $helpers = array('Html','Form','Javascript','Autocomplete');
    var $emailto = '010289@bk.ru'; //

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
        
        //pr($this->Metatags->findCacheFilesByUrl('ddd'));
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
            if (!$url_original){$url_original = $url;}
            $url = $this->Metatags->fixUrl($url);
            $title = filter_var($this->data['MetaTag']['title'], FILTER_SANITIZE_STRING);
            $description = filter_var($this->data['MetaTag']['description'], FILTER_SANITIZE_STRING);
            $keywords = filter_var($this->data['MetaTag']['keywords'], FILTER_SANITIZE_STRING);
            $title_en = filter_var($this->data['MetaTag']['title_en'], FILTER_SANITIZE_STRING);
            $description_en = filter_var($this->data['MetaTag']['description_en'], FILTER_SANITIZE_STRING);
            $keywords_en = filter_var($this->data['MetaTag']['keywords_en'], FILTER_SANITIZE_STRING);            
            $order = intval($this->data['MetaTag']['order']);
            $isbase = intval($this->data['MetaTag']['isbase']);

            if ($validate){
                //преобразуем url в относительный если нужно
                $url = $this->MetaTag->toRelativeUrl($url);
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
                    $msg_data = array();
                    $msg_data['msg_title'] = 'Дабавлена новая запись о мета-тегах';
                    $msg_data['msg_body'] = '<pre>
                         url : ' . $new_data ['MetaTag']['url'].'
                         url_original : '. $new_data ['MetaTag']['url_original'].'
                         title : '. $new_data ['MetaTag']['title'].'
                         description : '. $new_data ['MetaTag']['description'].'
                         keywords : '. $new_data ['MetaTag']['keywords'].'
                         title_en : '. $new_data ['MetaTag']['title_en'].'
                         description_en : '. $new_data ['MetaTag']['description_en'].'
                         keywords_en : '. $new_data ['MetaTag']['keywords_en'].'
                         order : '. $new_data ['MetaTag']['order'].'
                         isbase : '. $new_data ['MetaTag']['isbase'].'
                         </pre>';
                            
                    $this->_meta_change_alert($msg_data);
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
            $url_original = filter_var($this->data['MetaTag']['url_original'], FILTER_SANITIZE_STRING);
            if (!$url_original){$url_original = $url;}
            $url = $this->Metatags->fixUrl($url);
            $title = filter_var($this->data['MetaTag']['title'], FILTER_SANITIZE_STRING);
            $description = filter_var($this->data['MetaTag']['description'], FILTER_SANITIZE_STRING);
            $keywords = filter_var($this->data['MetaTag']['keywords'], FILTER_SANITIZE_STRING);
            $title_en = filter_var($this->data['MetaTag']['title_en'], FILTER_SANITIZE_STRING);
            $description_en = filter_var($this->data['MetaTag']['description_en'], FILTER_SANITIZE_STRING);
            $keywords_en = filter_var($this->data['MetaTag']['keywords_en'], FILTER_SANITIZE_STRING);            
            $order = intval($this->data['MetaTag']['order']);
            $isbase = intval($this->data['MetaTag']['isbase']);

            if ($validate){
                //преобразуем url в относительный если нужно
                $url = $this->MetaTag->toRelativeUrl($url);
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
                $old_data = $this->MetaTag->getMetaTagById($id);
                //пишем в БД!
                if ($this->MetaTag->editMetaTagById($id, $data)) {
                    
                    //------------------------------------------------------------------

                    $msg_data = array();
                    $msg_data['msg_title'] = 'Запись о мета-тегах изменена';

                    $msg_data['msg_body'] = '<h4>Старые данные:</h4>
                        <pre>
                         url : ' . $old_data ['MetaTag']['url'].'
                         url_original : '. $old_data ['MetaTag']['url_original'].'
                         title : '. $old_data ['MetaTag']['title'].'
                         description : '. $old_data ['MetaTag']['description'].'
                         keywords : '. $old_data ['MetaTag']['keywords'].'
                         title_en : '. $old_data ['MetaTag']['title_en'].'
                         description_en : '. $old_data ['MetaTag']['description_en'].'
                         keywords_en : '. $old_data ['MetaTag']['keywords_en'].'
                         order : '. $old_data ['MetaTag']['order'].'
                         isbase : '. $old_data ['MetaTag']['isbase'].'
                         </pre>';

                    $msg_data['msg_body'] .= '<h4>Новые данные:</h4>
                        <pre>
                         url : ' . $data ['MetaTag']['url'].'
                         url_original : '. $data ['MetaTag']['url_original'].'
                         title : '. $data ['MetaTag']['title'].'
                         description : '. $data ['MetaTag']['description'].'
                         keywords : '. $data ['MetaTag']['keywords'].'
                         title_en : '. $data ['MetaTag']['title_en'].'
                         description_en : '. $data ['MetaTag']['description_en'].'
                         keywords_en : '. $data ['MetaTag']['keywords_en'].'
                         order : '. $data ['MetaTag']['order'].'
                         isbase : '. $data ['MetaTag']['isbase'].'
                         </pre>';            

                    $this->_meta_change_alert($msg_data);            
                    //------------------------------------------------------------------                    
                    
                    
                    
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
            //------------------------------------------------------------------
            $old_data = $this->MetaTag->getMetaTagById($id);
            
            $msg_data = array();
            $msg_data['msg_title'] = 'Удалена запись о мета-тегах';
            $msg_data['msg_body'] = '<pre>
                 url : ' . $old_data ['MetaTag']['url'].'
                 url_original : '. $old_data ['MetaTag']['url_original'].'
                 title : '. $old_data ['MetaTag']['title'].'
                 description : '. $old_data ['MetaTag']['description'].'
                 keywords : '. $old_data ['MetaTag']['keywords'].'
                 title_en : '. $old_data ['MetaTag']['title_en'].'
                 description_en : '. $old_data ['MetaTag']['description_en'].'
                 keywords_en : '. $old_data ['MetaTag']['keywords_en'].'
                 order : '. $old_data ['MetaTag']['order'].'
                 isbase : '. $old_data ['MetaTag']['isbase'].'
                 </pre>';
            $this->_meta_change_alert($msg_data);            
            //------------------------------------------------------------------
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
            $url = $this->MetaTag->toRelativeUrl($url);
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
    
function _meta_change_alert($data = array()){
    if (!empty($data)){
        $this->Email->reset();
        $this->Email->from = 'admin@videoxq.com';
        $this->Email->replyTo = 'admin@videoxq.com';
        $this->Email->to = $this->emailto;
        $this->Email->subject = 'Videoxq.com изменение метатегов';
        $this->Email->template = 'metatags_alert_message';
        $this->Email->sendAs = 'html';
        $this->set('msg_title', $data['msg_title']);
        $this->set('msg_body', $data['msg_body']);
        $this->Email->send();        
    }
}

//------------------------------------------------------------------------------    
    

}
?>