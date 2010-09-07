<?php
class Group extends AppModel
{
    var $name = 'Group';
    var $actsAs = array('Acl', 'Bindable' => array('notices' => true));
    var $hasAndBelongsToMany = array(
        'Vbgroup' =>
            array('className'            => 'Vbgroup',
                'joinTable'              => 'groups_usergroup',
                'foreignKey'             => 'group_id',
                'associationForeignKey'  => 'usergroup_usergroupid',
                'conditions'             => '',
                'order'                  => '',
                'limit'                  => '',
                'uniq'                   => true,
                'finderQuery'            => '',
                'deleteQuery'            => '',
                'insertQuery'            => ''
            ),
        'User' =>
        array('className'            => 'User',
            'joinTable'              => 'groups_users',
            'foreignKey'             => 'group_id',
            'associationForeignKey'  => 'user_id',
            'conditions'             => '',
            'order'                  => '',
            'limit'                  => '',
            'uniq'                   => true,
            'finderQuery'            => '',
            'deleteQuery'            => '',
            'insertQuery'            => ''
        )
        );


    /**
     * Group model instance
     *
     * @var Vbgroup
     */
    var $Vbgroup;
    /**
     * User model instance
     *
     * @var User
     */
    var $User;

//    function __construct($assoc, $className = null)
//    {
//        parent::__construct($assoc, $className);
//        //$this->restrict('Vbgroup');
//        //$this->Vbgroup = ClassRegistry::init('Vbgroup','model');
//        //$this->User = ClassRegistry::init('User','model');
//
//    }

    /**
     * При редактировании группы добавляем все нужные права для всех юзеров на форуме,
     * если добавили связь портальной и форумной группы
     *
     * @return void
     */
    function beforeSave()
    {
        $vbgroups = $this->data['Vbgroup']['Vbgroup'];
        if (!empty($this->data['Group']['id']))
        	$users = $this->find(array('Group.id' => $this->data['Group']['id']));
        else
        	$users = array();
        $userIds = array();

	if (!empty($users['User']))
        foreach ($users['User'] as $user)
        {
            $this->User->recursive = 2;
            $fullUser = $this->User->find(array('User.userid' => $user['userid']));
            $this->User->recursive = 1;
            $groupIds = array();

            foreach ($fullUser['Group'] as $group)
            {
                $groupIds[] = $group['id'];
            }

            $userIds[] = $user['userid'];
            if (empty($user['membergroupids']))
            {
                if (($key = array_search($user['usergroupid'], $vbgroups)) !== false)
                {
                    unset($vbgroups[$key]);
                }

                $user['membergroupids'] = implode(',', $vbgroups);
                $saveUser = array('User' =>
                                        array('membergroupids' => $user['membergroupids'],
                                              'userid' => $user['userid']),
                                  'Group' => array('Group' => $groupIds));
                //pr($saveUser);
                $this->User->save($saveUser);
//                $this->User->save($fullUser);
                continue;
            }

            $vbGroupIds = array();
            foreach ($fullUser['Group'] as $group)
            {
                foreach ($group['Vbgroup'] as $vbgroup)
                {
                    $vbGroupIds[] = $vbgroup['usergroupid'];
                }
            }

            $vbGroupIds = array_unique($vbGroupIds);
            natsort($vbGroupIds);

            $memberGroups = explode(',', $user['membergroupids']);
            array_walk($memberGroups, 'arrayTrim');

            $diff = array_diff($memberGroups, $vbGroupIds);

            $vbGroupIds = array();
            foreach ($fullUser['Group'] as $group)
            {
                if ($group['id'] == $this->id)
                    continue;
                foreach ($group['Vbgroup'] as $vbgroup)
                {
                    $vbGroupIds[] = $vbgroup['usergroupid'];
                }
            }
            $vbGroupIds = array_merge($vbGroupIds, $vbgroups, $diff);
            $vbGroupIds = array_unique($vbGroupIds);
            natsort($vbGroupIds);

            if (($key = array_search($user['usergroupid'], $vbGroupIds)) !== false)
            {
                unset($vbGroupIds[$key]);
            }

            $user['membergroupids'] = implode(',', $vbGroupIds);
            $saveUser = array('User' =>
                                    array('membergroupids' => $user['membergroupids'],
                                          'userid' => $user['userid']),
                              'Group' => array('Group' => $groupIds));
            //pr($saveUser);
            $this->User->save($saveUser);
        }

        $this->data['User']['User'] = $userIds;
        //pr($this->data);
        return true;
    }


//    function afterSave($created)
//    {
//        die();
//    }


    function parentNode()
    {
        if (!$this->id)
        {
            return null;
        }
        $data = $this->read();

        if (!$data['Group']['parent_id'])
        {
            return null;
        } else
        {
            return $data['Group']['parent_id'];
        }
    }

}
?>