<?php
App::import('Sanitize');
class BasketController extends AppController {

    var $name = 'Basket';
    var $helpers = array('Html', 'Form');
    var $components = array('RequestHandler');
    var $viewPath = 'media/baskets';
    //var $uses = array('FilmVariant');


    function flush()
    {
        $this->Basket->deleteAll(array('user_id' => $this->authUser['userid']));
        $this->redirect('/basket');
    }



    function index()
    {
        $this->pageTitle = __('Video catalog', true) . ' - ' . __('Downloads', true);
        $baskets = $this->Basket->getBasketList($this->authUser['userid']);
        $this->set('baskets', $baskets);
    }


    function add($id, $type = 'file')
    {

        $this->data['Basket']['type'] = $type;
        $this->data['Basket']['object_id'] = $id;

        if ($this->RequestHandler->isAjax())
        {
            $this->layout = 'ajax';
            if (empty($id))
            {
                $this->render('add_ajax');
                return;
            }
        }

		$lang = Configure::read('Config.language');
		$langFix = '';
		if ($lang == _ENG_) $langFix = '_en';
        if ($type == 'variant')
        {
            $variant = $this->Basket->FilmFile->FilmVariant->read(null, $id);
            foreach ($variant['FilmFile'] as $file)
            {
                $res = $this->Basket->find(array('user_id' => $this->authUser['userid'],
                                                 'film_file_id' => $file['id']));
                if ($res)
                    continue;
                $this->Basket->create();
                $this->data['Basket']['user_id'] = $this->authUser['userid'];
                $this->data['Basket']['film_file_id'] = $file['id'];
                $this->data['Basket']['film_variant_id'] = $variant['FilmVariant']['id'];
                $this->data['Basket']['film_id'] = $variant['Film']['id'];
                $this->data['Basket']['title'] = $variant['Film']['title' . $langFix] . ' / ' . $variant['VideoType']['title'];

                $this->Basket->save($this->data);
            }

            $this->_finishAdd();
            return;
        }

        //чтобы ниче лишнего не захотели:)
        $type = 'file';

        $res = $this->Basket->find(array('user_id' => $this->authUser['userid'],
                                         'film_file_id' => $id));
        if ($res)
        {
            $this->data['Basket']['film_variant_id'] = $res['Basket']['film_variant_id'];;
            $this->_finishAdd();
            return;
        }

        $this->Basket->FilmFile->recursive = 2;
        $file = $this->Basket->FilmFile->find('first', array('conditions' => array('FilmFile.id' => $id),
                                                             'contain' => array('FilmVariant' => array('Film', 'VideoType'))));
        $this->Basket->create();
        $this->data['Basket']['user_id'] = $this->authUser['userid'];
        $this->data['Basket']['film_file_id'] = $file['FilmFile']['id'];
        $this->data['Basket']['film_variant_id'] = $file['FilmVariant']['id'];
        $this->data['Basket']['film_id'] = $file['FilmVariant']['Film']['id'];
        $this->data['Basket']['title'] = $file['FilmVariant']['Film']['title' . $langFix] . ' / ' . $file['FilmVariant']['VideoType']['title'];


        if ($this->Basket->save($this->data))
        {
            $this->_finishAdd();
            return;
        }
    }

    function delete($id = null, $type = 'variant') {
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for Bookmark', true));
            $this->redirect(array('action'=>'index'));
        }

        if ($type == 'variant')
        {
            $this->Basket->deleteAll(array('Basket.film_variant_id' => $id, 'user_id' => $this->authUser['userid']));
            $basket['Basket']['film_variant_id'] = $id;
        }
        elseif ($type == 'file')
        {
            $basket = $this->Basket->find(array('film_file_id' => $id, 'user_id' => $this->authUser['userid']));
            $this->Basket->deleteAll(array('film_file_id' => $id, 'user_id' => $this->authUser['userid']));
        }
        $this->data['Basket']['saved'] = null;
        $this->data['Basket']['type'] = $type;
        $this->data['Basket']['object_id'] = $id;
        $this->data['Basket']['film_variant_id'] = $basket['Basket']['film_variant_id'];

        Cache::delete('Catalog.basket_' . $this->authUser['userid'],'basket');

        if ($this->RequestHandler->isAjax())
        {
            $this->layout = 'ajax';
            $this->render('add_ajax');
            return;
        }

        $this->Session->setFlash(__('Bookmark deleted', true));
        $this->redirect(array('action'=>'index'));
    }



    function downloadXML()
    {
        $this->layout = 'ajax';
        Configure::write('debug', 0);
        if (empty($this->data))
            return;

        $elements = str_replace('id_', '', $this->data['Basket']['elements']);

        $elements = explode(',', $elements);
        $this->Basket->recursive = 1;
        $this->Basket->contain(array('Film', 'FilmVariant' => 'VideoType', 'FilmFile'));
        $baskets = $this->Basket->getBasketsByVariantIds($elements);
        //$this->Basket->find('all', array('conditions' => array('FilmVariant.id' => $elements),
        //                                            'order' => 'find_in_set(FilmVariant.id, "'.implode(',', $elements).'")'));
//        pr($baskets);
//        die();
//        $filmIds = Set::extract('/Film/id', $baskets);
//        $options = array('conditions' => array('FilmVariant.film_id' => $filmIds),
//                         'order' => 'find_in_set(FilmVariant.film_id, "'.implode(',', $filmIds).'")');
//        $files = $this->Basket->Film->FilmVariant->find('all', $options);

        $xml = '<?xml version="1.0" encoding="UTF-8"?>
<DownloadList  Version="4" NextID="%s">
%s
</DownloadList>
        ';
        $xmlelement = '
<DownloadFile>
         <ID>%s</ID>
         <URL>%s</URL>
         <FileName></FileName>
         <State>3</State>
         <Size>0</Size>
         <SaveDir>%s</SaveDir>
         <MaxSections>1</MaxSections>
         <Date>%s</Date>
         <DownloadTime>0</DownloadTime>
 </DownloadFile>';
        $data = '';
        $id = 1;

        foreach ($baskets as $basket)
        {
            extract($basket);
            $data .= sprintf($xmlelement, $id, FILM::set_input_server($Film['dir']) . $Film['dir'] . '/' . $FilmFile['file_name'],
                                              $this->data['Basket']['download_dir'] . '\\' . $VideoType['dir'] . '\\' . $Film['dir'] . '\\',
                                              date('m/d/Y H:i:s'));
            $id++;
        }

        $data = sprintf($xml, $id, $data);
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=" . date('Y-m-d_H_i_s') . '.xml');
        header("Content-Type: application/octet-stream");
        header("Content-Transfer-Encoding: binary");
        echo $data;
    }


    function download()
    {
    	//pr($this->data);
    	//die();
        $this->layout = 'playlist';
        //Configure::write('debug', 2);

        if (empty($this->data))
            return;

        $elements = str_replace('id_', '', $this->data['Basket']['elements']);

        $elements = explode(',', $elements);
        $this->Basket->recursive = 1;
        $this->Basket->contain(array('Film', 'FilmVariant' => 'VideoType', 'FilmFile'));
        $baskets = $this->Basket->getBasketsByVariantIds($elements, $this->authUser['userid']);

        $data = '';
        foreach ($baskets as $basket)
        {
            extract($basket);

            //$data .= FILM::set_input_server($Film['dir']) . $Film['dir'] . '/' . $FilmFile['file_name'] . "\r\n";
            $data .= FILM::set_input_server($Film['dir']) . '/' . $FilmFile['file_name'] . "\r\n";
        }
        $this->set('data', $data);
    }



    function _finishAdd()
    {
        Cache::delete('Catalog.basket_' . $this->authUser['userid'],'basket');

        $this->data['Basket']['saved'] = 'yes';
        if ($this->RequestHandler->isAjax())
        {
            $this->render('add_ajax');
            return;
        }
        $this->redirect($this->referer('/media'));
    }


    function admin_index() {
        $this->Bookmark->recursive = 0;
        $this->set('bookmarks', $this->paginate());
    }

    function admin_view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid Bookmark.', true));
            $this->redirect(array('action'=>'index'));
        }
        $this->set('bookmark', $this->Bookmark->read(null, $id));
    }

    function admin_add() {
        if (!empty($this->data)) {
            $this->Bookmark->create();
            if ($this->Bookmark->save($this->data)) {
                $this->Session->setFlash(__('The Bookmark has been saved', true));
                $this->redirect(array('action'=>'index'));
            } else {
                $this->Session->setFlash(__('The Bookmark could not be saved. Please, try again.', true));
            }
        }
        $users = $this->Bookmark->User->find('list');
        $this->set(compact('users'));
    }

    function admin_edit($id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid Bookmark', true));
            $this->redirect(array('action'=>'index'));
        }
        if (!empty($this->data)) {
            if ($this->Bookmark->save($this->data)) {
                $this->Session->setFlash(__('The Bookmark has been saved', true));
                $this->redirect(array('action'=>'index'));
            } else {
                $this->Session->setFlash(__('The Bookmark could not be saved. Please, try again.', true));
            }
        }
        if (empty($this->data)) {
            $this->data = $this->Bookmark->read(null, $id);
        }
        $users = $this->Bookmark->User->find('list');
        $this->set(compact('users'));
    }

    function admin_delete($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for Bookmark', true));
            $this->redirect(array('action'=>'index'));
        }
        if ($this->Bookmark->del($id)) {
            $this->Session->setFlash(__('Bookmark deleted', true));
            $this->redirect(array('action'=>'index'));
        }
    }

}
?>