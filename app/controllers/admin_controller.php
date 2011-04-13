<?php
class AdminController extends AppController
{

    var $name = 'Admin';
    var $helpers = array('Html' , 'Form');
    var $components = array('ControllerList');
    var $uses = array('User' , 'Group' , 'Block');

    function admin_home()
    {

    }


    /**
     * Если вдруг все стало плохо с АЦЛями, можно попробовать пофиксать этим
     *
     */
    function admin_fix_acl()
    {
        $aro = new Aro();
        $aro->recover();
        $aco = new Aco();
        $aco->recover();
    }


    function admin_acl_controllers($controller = null)
    {
        if (!empty($this->data))
        {
            $this->Group->restrict();

            foreach ($this->data['User'] as $key => $group)
            {
                foreach ($group as $action => $permission)
                {
                    $group = $this->Group->read(null, $key);
                    $permission = $permission ? 'allow' : 'deny';
                    $this->_setPermissions($group, $controller, $action, $permission);
                }

            }
        }

        if ($controller == null)
        {
            $controllerList = array_keys($this->ControllerList->get());
            sort($controllerList);
            $controllerPerms = '';
            $this->set('controllerList', $controllerList);
            Configure::write('debug', '2');
            return;
        }

        $fileName = Inflector::underscore($controller) .'_controller.php';
        $file = CONTROLLERS.$fileName;
        require_once($file);
        $className = $controller . 'Controller';
        $actions = get_class_methods($className);
        foreach($actions as $k => $v) {
            if ($v{0} == '_') {
                unset($actions[$k]);
            }
        }
        $parentActions = get_class_methods('AppController');
        $actions = array_diff($actions, $parentActions);
        $actions[]='admin_index';
        $actions[]='admin_view';
        $actions[]='admin_add';
        $actions[]='admin_edit';
        $actions[]='admin_delete';
        $this->set('actions', $actions);
        $this->Group->restrict();
        $groups = $this->Group->findAll();
        $this->set('groups', $groups);

        Configure::write('debug', '0');
		set_time_limit(600000);
        ini_set('memory_limit', '1G');
        $perms = array();
        foreach ($groups as $group)
        {
            foreach ($actions as $action)
            {
                $perms[$group['Group']['id']][$action] = $this->Acl->check($group, $controller . '/' . $action, '*');
            }
        }
        $this->set('perms', $perms);
        $this->set('ctlName', $controller);
        Configure::write('debug', 2);
    }


    function admin_acl($groupId = null, $controller = null, $action = null, $permission = null)
    {
        if ($groupId === null)
        {
            $this->Group->restrict('Vbgroup');
            $this->set('groups', $this->Group->findAll());
        }
        else
            if ($controller == null)
            {
                // Prevents a heap-load of error messages from coming up if DEBUG = 2.
                Configure::write('debug', '0');
                $group = $this->Group->read(null, $groupId);
                // See http://cakebaker.42dh.com/2006/07/21/how-to-list-all-controllers/
                $controllerList = $this->ControllerList->get();
                $controllerPerms = '';
                $aco = new Aco();
                foreach ($controllerList as $controller => $actions)
                {
                    foreach ($actions as $key => $action)
                        $controllerPerms[$controller][$action] = $this->Acl->check($group, $controller . '/' . $action, '*');
                }
                $this->set('controllerList', $controllerList);
                $this->set('controllerPerms', $controllerPerms);
                $this->set('group', $group);
                Configure::write('debug', '2');
            }
            else
            {
                $this->Group->restrict('Vbgroup');
                $group = $this->Group->read(null, $groupId);

                if ($action == 'all')
                {
                    $controllerList = $this->ControllerList->get();
                    foreach ($controllerList[$controller] as $caction)
                        $this->_setPermissions($group, $controller, $caction, $permission);
                }
                else
                    $this->_setPermissions($group, $controller, $action, $permission);

                $this->Session->setFlash($group['Group']['title'] . ' has been granted/denied access to ' . $controller . '/' . $action);
                Configure::write('debug', '2');
                $this->redirect(array('action' => 'acl' , $group['Group']['id']));
            }
    }

    private function _setPermissions($group, $controller, $action, $permission)
    {
        // First check to make sure that the controller is already set up as an ACO
        $aco = new Aco();
        $rootAco = $aco->findByAlias('ROOT');
        // Set up $controllerAco if it's not present.
        $controllerAco = $aco->findByAlias($controller); //$this->Administrator->query( 'SELECT Aco.* From acos AS Aco LEFT JOIN acos AS Aco0 ON Aco0.alias = "'.$controller.'" LEFT JOIN acos AS Aco1 ON Aco1.lft > Aco0.lft && Aco1.rght < Aco0.rght AND Aco1.alias = "ROOT" WHERE Aco.lft <= Aco0.lft AND Aco.rght >= Aco0.rght ORDER BY Aco.lft DESC' ) );
        if (empty($controllerAco))
        {
            $aco->create();
            $aco->save(array('alias' => $controller , 'parent_id' => $rootAco['Aco']['id']));
            $controllerAco = $aco->findByAlias($controller); //$this->Administrator->query( 'SELECT Aco.* From acos AS Aco LEFT JOIN acos AS Aco0 ON Aco0.alias = "'.$controller.'" LEFT JOIN acos AS Aco1 ON Aco1.lft > Aco0.lft && Aco1.rght < Aco0.rght AND Aco1.alias = "ROOT" WHERE Aco.lft <= Aco0.lft AND Aco.rght >= Aco0.rght ORDER BY Aco.lft DESC' ) );
        }

        // Set up $actionAcoif it's not present.
        $actionAco = $aco->find(array('parent_id' => $controllerAco['Aco']['id'] , 'alias' => $action));
        if (empty($actionAco))
        {
            $aco->create();
            $aco->save(array('alias' => $action , 'parent_id' => $controllerAco['Aco']['id']));
            $actionAco = $aco->find(array('parent_id' => $controllerAco['Aco']['id'] , 'alias' => $action));
        }

        // Set up perms now.
        if ($permission == 'allow')
            $this->Acl->allow(array('model' => 'Group' , 'foreign_key' => $group['Group']['id']), $controller . '/' . $action);
        else
            $this->Acl->deny(array('model' => 'Group' , 'foreign_key' => $group['Group']['id']), $controller . '/' . $action);
    }
}
?>