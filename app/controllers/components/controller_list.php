<?php
class ControllerListComponent extends Object
{

    /**
     * Получаем список контроллеров с их методами
     *
     * @return unknown
     */
    function get()
    {
        $controllerClasses = Configure::listObjects('controller');

        foreach ($controllerClasses as $controller)
        {
            if ($controller != 'App')
            {
                $fileName = Inflector::underscore($controller) . '_controller.php';
                $file = CONTROLLERS . $fileName;
                require_once ($file);
                $className = $controller . 'Controller';
                $actions = get_class_methods($className);
                foreach ($actions as $k => $v)
                {
                    if ($v{0} == '_')
                    {
                        unset($actions[$k]);
                    }
                }
                $parentActions = get_class_methods('AppController');
                $controllers[$controller] = array_diff($actions, $parentActions);
                //$controllers[$controller][]='admin_index';
            }
        }

        return $controllers;
    }
}
?>