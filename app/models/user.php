<?php
class User extends AppModel
{
   var $name = 'User';
   var $transactional = true;
   var $primaryKey = 'userid';
   var $useTable = 'user';

   var $actsAs = array('Acl2');
   var $displayField = 'username';

   public $hasMany = array(
            'Pay' => array('className' => 'Pay',
                                'foreignKey' => 'user_id',
                                'dependent' => false,
                                'conditions' => '',
                                'fields' => '',
                                'order' => '',
                                'limit' => '',
                                'offset' => '',
                                'exclusive' => '',
                                'finderQuery' => '',
                                'counterQuery' => ''
            )
   );

    var $hasOne = array(
            'Useragreement' => array('className' => 'Useragreement',
                                'foreignKey' => 'user_id',
                                'dependent' => false,
                                'conditions' => '',
                                'fields' => '',
                                'order' => ''
            )
    );

   var $hasAndBelongsToMany = array(
    'Group' =>
        array('className'            => 'Group',
            'joinTable'              => 'groups_users',
            'foreignKey'             => 'user_id',
            'associationForeignKey'  => 'group_id',
            'conditions'             => '',
            'order'                  => '',
            'limit'                  => '',
            'uniq'                   => true,
            'finderQuery'              => '',
            'deleteQuery'            => '',
            'insertQuery'             => ''
        )
    );

   function __construct($id = false, $table = null, $ds = null)
   {
       parent::__construct($id, $table, $ds);
   }


    function parentNode()
    {
        if (!$this->id)
        {
            return null;
        }

        $data = $this->read();

        if (!isset($data['Group'][0]))
        {
            return null;
        } else
        {
            return array('model' => 'Group', 'foreign_key' => $data['Group'][0]['id']);
        }
    }


    /**
     * Данный метод выставляет юзеры нужные группы на форуме
     * в зависимости от групп на портале
     *
     * @return void
     */
    function beforeSave()
    {
        if (empty($this->data['Group']['Group']))
            return true;
        $groups = $this->data['Group']['Group'];//НАЗНАЧАЕМЫЕ ДЛЯ ЮЗЕРА ГРУППЫ ПОРТАЛА

        $this->recursive = 2;
        $fullUser = $this->find(array('User.userid' => $this->data['User']['userid']));
        $groupIds = array();
        $this->recursive = 1;
        $vbGroupIds = array();
        foreach ($fullUser['Group'] as $group)
        {
            foreach ($group['Vbgroup'] as $vbgroup)
            {
                $vbGroupIds[] = $vbgroup['usergroupid'];
            }
        }

        $vbGroupIds = array_unique($vbGroupIds);// НАЗНАЧЕННЫЕ ДЛЯ ЮЗЕРА ГРУППЫ ФОРУМА, СООТВЕТСТВУЮЩИЕ ГРУППАМ ПОРТАЛА
        natsort($vbGroupIds);

        if (($key = array_search($this->data['User']['usergroupid'], $vbGroupIds)) !== false)
        {
            unset($vbGroupIds[$key]);
        }
/*
        if (empty($fullUser['User']['membergroupids']))
        {
            $this->data['User']['membergroupids'] = implode(',', $vbGroupIds);
            return true;
        }
*/
        $memberGroups = explode(',', $fullUser['User']['membergroupids']);
        array_walk($memberGroups, 'arrayTrim');

        //ОТЛИЧАЮЩИЕСЯ ГРУППЫ ФОРУМА И КАТАЛОГА, СОГЛАСНО СООТВЕТСТВИЯМ
        $diff = array_diff($memberGroups, $vbGroupIds);

        $vbGroupIds = array();

        if (!empty($groups))
        {
            $this->Group->restrict('Vbgroup');
            //ИЩЕМ ГРУППЫ, ФОРУМА, СООТВЕТСТВУЮЩИЕ ГРУППАМ ПОРТАЛА, УСТАНАВЛИВАЕМЫМ СЕЙЧАС ДЛЯ ЮЗЕРА ЧЕРЕЗ ФОРМУ РЕДАКТИРОВАНИЯ
            $newGroups = $this->Group->find('all', array(
                                           'conditions' => array('Group.id IN (' . implode(',', $groups) . ')'),
                                           'recursive'  => 1));

            foreach ($newGroups as $group)
            {
            	if (empty($group['Vbgroup']))
            		continue;

            	foreach ($group['Vbgroup'] as $vbgroup)
                {
                   	$vbGroupIds[] = $vbgroup['usergroupid'];
                }
            }
            //ИЩЕМ ГРУППЫ ФОРУМА, СООТВЕТСТВУЮЩИЕ ГРУППАМ ПОРТАЛА, КОТОРЫЕ НЕ ОТМЕЧЕНЫ В ФОРМЕ РЕДАКТИРОВАНИЯ
            $nonGroups = $this->Group->find('all', array(
                                           'conditions' => array('Group.id NOT IN (' . implode(',', $groups) . ')'),
                                           'recursive'  => 1));
			$nonGroupIds = array();
            foreach ($nonGroups as $group)
            {
            	if (empty($group['Vbgroup']))
            		continue;

                foreach ($group['Vbgroup'] as $vbgroup)
                {
                	if (!in_array($vbgroup['usergroupid'], $vbGroupIds))
                	{
                    	$nonGroupIds[] = $vbgroup['usergroupid'];
                	}
                }
            }
            foreach($diff as $dk => $dv)
            {
            	if (in_array($dv, $nonGroupIds))
            	{
            		unset($diff[$dk]);
            	}
            }

        }
        foreach($diff as $d)
        	$vbGroupIds[] = $d;
        $vbGroupIds = array_unique($vbGroupIds);
        natsort($vbGroupIds);

        if (($key = array_search($this->data['User']['usergroupid'], $vbGroupIds)) !== false)
        {
            unset($vbGroupIds[$key]);
        }

        $this->data['User']['membergroupids'] = implode(',', $vbGroupIds);

        return true;
    }
}
?>