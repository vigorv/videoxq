<?php
class BlocksController extends AppController {

    var $name = 'Blocks';
    var $helpers = array('Html', 'Form');

    var $blockPositions = array('left' => 'left', 'right' => 'right',
                                'top' => 'top', 'bottom' => 'bottom',
                                'external' => 'external', 'header' => 'header');
    var $blockTypes = array('text' => 'text', 'php' => 'php',
                            'component' => 'component', 'element' => 'element',
                            'component+element' => 'component+element');


    function external()
    {
        $this->layout = 'external';
    }


    function admin_index() {
        $this->Block->recursive = 0;
        $condition=array();
        $condition=$this->passedArgs;
        if(isset($condition['page']))unset($condition['page']);
        if(isset($condition['direction']))unset($condition['direction']);
        if(isset($condition['sort']))unset($condition['sort']);
        $this->paginate = array(
         						'conditions' => $condition
        						,'limit' => 10000,
        						'page' => 1,
        						'order' => 'position ASC, order ASC');
        $this->set('blocks', $this->paginate());

    }
    function admin_view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid Block.', true));
            $this->redirect(array('action'=>'index'));
        }
        $this->set('block', $this->Block->read(null, $id));
    }

    function admin_add() {
        if (!empty($this->data)) {
            $this->Block->create();
            if ($this->Block->save($this->data)) {
                $this->Session->setFlash(__('The Block has been saved', true));
                //Cache::delete('Block.blockList', 'default');
                Cache::clear(false, 'block');
                $this->redirect(array('action'=>'index'));
            } else {
                $this->Session->setFlash(__('The Block could not be saved. Please, try again.', true));
            }
        }
        $this->_setComponents();
    }

    function admin_edit($id = null) {
        //Cache::delete('Block.blockList', 'default');
        Cache::clear(false, 'block');
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid Block', true));
            $this->redirect(array('action'=>'index'));
        }
        if (!empty($this->data)) {
            if ($this->Block->save($this->data)) {
                $this->Session->setFlash(__('The Block has been saved', true));
                $this->redirect(array('action'=>'index'));
            } else {
                $this->Session->setFlash(__('The Block could not be saved. Please, try again.', true));
            }
        }
        if (empty($this->data)) {
            $this->data = $this->Block->read(null, $id);
            $this->_setComponents();
        }
    }

    function admin_delete($id = null) {
        //Cache::delete('Block.blockList', 'default');
        Cache::clear(false, 'block');
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for Block', true));
            $this->redirect(array('action'=>'index'));
        }
        if ($this->Block->del($id)) {
            $this->Session->setFlash(__('Block deleted', true));
            $this->redirect(array('action'=>'index'));
        }
    }

    function _setComponents()
    {
        $componentClasses = Configure::listObjects('component');
        $components = array(''=>'');
        $methods = array(''=>'');

        foreach($componentClasses as $component)
        {
            if (stripos($component, 'block') !== 0)
                continue;
            $fileName = Inflector::underscore($component) . '.php';
            $file = COMPONENTS . $fileName;
            require_once($file);
            $className = $component . 'Component';
            $actions = get_class_methods($className);
            foreach($actions as $k => $v)
            {
                if ($v{0} == '_')
                {
                    unset($actions[$k]);
                }
            }
            $parentActions = get_class_methods('Object');
            $ownActions = array_diff($actions, $parentActions);
            if (!empty($ownActions))
                foreach ($ownActions as $action)
                {
                    if ($action != 'startup' && $action != 'initialize')
                        $methods[$component][$action] = $action;
                }

            $components[$component] = $component;
        }

        App::import('Folder');
        $items = array();
        $Folder =& new Folder(ELEMENTS);
        $contents = $Folder->read(false, false);
        $elements = array(''=>'');
        foreach ($contents[1] as $block)
        {
            if (stripos($block, 'block_') === 0)
                $elements[basename($block, '.ctp')] = basename($block, '.ctp');
        }

        //$blockList = Configure::listObjects('element', ELEMENTS);
        //pr($contents);
        $this->set('elements', $elements);
        $this->set('methods', $methods);
        $this->set('controllers', $components);
        $this->set('positions', $this->blockPositions);
        $this->set('types', $this->blockTypes);

    }


}
?>