<?php
class DirectionsController extends AppController {

    var $name = 'Directions';
    var $components = array('RequestHandler','Security');
    var $helpers = array('Html', 'Form', 'Javascript','Tree2');
    var $uses = array('Direction','Categoriez');

/*
 *   убрал т.к. смысла нет в ней (на всякий оставил ее мало ли, кто-то же воткнул ее суда :))
      var $Directions;
 */


    function admin_index() {

        $tree_arr = $this->Direction->generatetreelist(null, null, null, '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
        $this->set('tree_arr', $tree_arr);
    }

//------------------------------------------------------------------------------

    function admin_up($id = null) {
        if (!empty($id) && $id) {
            $info = $this->Direction->moveup($id,1);
            $this->Session->setFlash('Категория перемещена вверх!', true);
        }
        else{
            $this->Session->setFlash('Ошибка. Куда делcя id?', true);
        }
        $this->redirect(array('action'=>'index'));
    }

//------------------------------------------------------------------------------

    function admin_down($id = null) {
        if (!empty($id) && $id) {
            $info = $this->Direction->movedown($id,1);
            $this->Session->setFlash('Категория перемещена вниз!', true);
        }
        else{
            $this->Session->setFlash('Ошибка. Куда делcя id?', true);
        }
        $this->redirect(array('action'=>'index'));
    }

//------------------------------------------------------------------------------

    function admin_add($id = null) {
        $data = array();
        if (!empty($this->data))
        {
            $validate = true;
            //проверим входные данные для записи
            //особо проверять не будем - админы ведь рулят изнутри,
            //а не вредители :) + внутренняя проверка кейка есть воде бы.
            $parent_id = intval($this->data['Direction']['parent_id']);
            $title = $this->data['Direction']['title'];
            $caption = $this->data['Direction']['caption'];
            $txt = $this->data['Direction']['txt'];
            $hidden = $this->data['Direction']['hidden'];
            if (!$title) $validate = false;

            if ($validate){
                //создадим новый массив, а то мало ли что нам там прилетело по post
                $new_data = array('Direction' => array(
                    'parent_id' => ($parent_id)? $parent_id : null,
                    'title' => $title,
                    'caption' => $caption,
                    'txt' => $txt,
                    'hidden' => $hidden
                ));

            //пишем в БД!
            if ($this->Direction->save($new_data)) {
                $this->Session->setFlash('Категория добавлена!', true);
                $this->redirect(array('action'=>'index'));
                    }
            }
            else{
                    $this->Session->setFlash('Ошибка. Заполните поля правильно', true);

                    $directions_list = $this->Direction->generatetreelist(null, null, null, '');
                    $data['directions_list'] = $directions_list;
                    $this->set('data',$data);
                    $this->redirect(array('action'=>'add'));
            }

        }else{
            if (!empty($id) && $id){
                $data['parent_id'] = $id;
            }

            $directions_list = $this->Direction->generatetreelist(null, null, null, '');
            $data['directions_list'] = $directions_list;
            $this->set('data',$data);
        }
    }

//------------------------------------------------------------------------------

    function admin_edit($id = null) {
        $data = array();
        if (!empty($this->data))
        {

            $validate = true;
            //проверим входные данные для записи
            //особо проверять не будем - админы ведь рулят изнутри,
            //а не вредители :) + внутренняя проверка кейка есть воде бы.
            $id = intval($this->data['Direction']['id']);
            $parent_id = intval($this->data['Direction']['parent_id']);
            $title = $this->data['Direction']['title'];
            $caption = $this->data['Direction']['caption'];
            $txt = $this->data['Direction']['txt'];
            $hidden = $this->data['Direction']['hidden'];
            if(!$id){
                $this->Session->setFlash('Ошибка. Куда делcя id?', true);
                $this->redirect(array('action'=>'index'));
            }
            if (!$title) $validate = false;

            if ($validate){
                //создадим новый массив, а то мало ли что нам там прилетело по post
                $new_data = array('Direction' => array(
                    'id' => $id,
                    'parent_id' => ($parent_id)? $parent_id : null,
                    'title' => $title,
                    'caption' => $caption,
                    'txt' => $txt,
                    'hidden' => $hidden
                ));
                //проверим сменили ли parent_id, если не сменили то уберем его
                //из массива, да бы не напрягать высоконагруженный сервер :)))
                //бесполезной переиндексацией дерева (так советуют в манулае
                //кейка :))
                if ($this->data['Direction']['parent_id'] == $this->data['Direction']['old_parent_id']){
                    unset($data['parent_id']);
                }

            //пишем в БД!
            if ($this->Direction->save($new_data)) {
                $this->Session->setFlash('Категория изменена!', true);
                $this->redirect(array('action'=>'index'));
                    }
               else{
                    $this->Session->setFlash('Произошла ошибка', true);
                    $data = $this->data['Direction'];
                    $directions_list = $this->Direction->generatetreelist(null, null, null, '');
                    $data['directions_list'] = $directions_list;
                    $data['old_parent_id'] = $data['parent_id'];
                    $this->set('data',$data);
                    //$this->redirect(array('action'=>'index'));
               }
            }
            else{
                    $this->Session->setFlash('Ошибка. Заполните поля правильно', true);
                    $data = $this->data['Direction'];
                    $directions_list = $this->Direction->generatetreelist(null, null, null, '');
                    $data['directions_list'] = $directions_list;
                    $data['old_parent_id'] = $data['parent_id'];
                    $this->set('data',$data);
//                    $this->redirect(array('action'=>'edit'));
            }

        }else{
            if (!empty($id) && $id){

                //вытащим данные для редактирования
                $result = $this->Direction->find('first', array(
                        'conditions' => array('id' => $id),
                        'fields' => array()
                    ));
                $data = $result['Direction'];
                //заполним вспомогательные поля
                $directions_list = $this->Direction->generatetreelist(null, null, null, '');
                $data['directions_list'] = $directions_list;
                $data['old_parent_id'] = $data['parent_id'];
                //вперед и с песней!
                $this->set('data',$data);
            }
            else{
                $this->Session->setFlash('Ошибка. Куда делcя id?', true);
                $this->redirect(array('action'=>'index'));
            }
        }
    }

//------------------------------------------------------------------------------

    function admin_delete($id = null) {
        if (!empty($id) && $id) {
            //проверим есть ли в этой категории новости
            //если есть то низя удалять :)
            $no_news_in_directions = false;
            // узнаем idшки вложенных категорий
            //$directions = $this->Direction->children(2, false, array('id'));
            //так как вложенные категории не удаляются, а переносятся в parent,
            //массив нам не нужен обойдемся одним текущим id, который удаляем
            $direction_ids = array($id);
            foreach($directions as $d){
                $direction_ids[] = $d['Direction']['id'];
            }
            $no_news_in_directions = $this->Direction->checkNoNewsInDirections();
            if ($no_news_in_directions){
                $info = $this->Direction->removefromtree($id,true);
                $this->Session->setFlash('Категория удалена.', true);
            }
            else {
                $this->Session->setFlash('Ошибка. Категория не пуста, в ней есть новости', true);
            }
        }
        else{
            $this->Session->setFlash('Ошибка. Куда делcя id?', true);
        }

        $this->redirect(array('action'=>'index'));
    }

//------------------------------------------------------------------------------

    function admin_reorder(){
        //$result = $this->Direction->reorder();
        $this->Session->setFlash('Переиндексация сруктуры категорий прошло успешно!', true);
	$this->redirect(array('action'=>'index'));
    }

//------------------------------------------------------------------------------

    function admin_recover(){
        //$result = $this->Direction->recover();
        $this->Session->setFlash('Восстановление сруктуры категорий прошло успешно!', true);
	$this->redirect(array('action'=>'index'));
    }

//------------------------------------------------------------------------------

    function admin_verify(){
        $result = $this->Direction->verify();
        if ($result && !is_array($result)){
            $this->Session->setFlash('Проверка сруктуры категорий прошло успешно!', true);
            $this->redirect(array('action'=>'index'));
        }
        else{
            $data = array(
                'message' => 'Ошибки в структуре категорий:',
                'result' => $result
            );
            $this->set('data',$data);
        }

    }
//------------------------------------------------------------------------------


}
?>