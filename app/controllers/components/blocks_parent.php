<?php
/**
 * Родительский компонент для всех блоков
 *
 */
class BlocksParentComponent extends Object
{
    /**
     * Fake
     *
     * @var AppController
     */
    var $controller;

    function startup(&$controller)
    {
        $this->data =& $controller->data;
        $this->controller = $controller;

        //return parent::initialize($controller);
    }
}
?>