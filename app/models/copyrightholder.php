<?php
App::import('Model', 'MediaModel');
class Copyrightholder extends MediaModel {

    var $name = 'Copyrightholder';
    var $hasMany = array(
            'CopyrightholdersPicture' => array('className' => 'CopyrightholdersPicture',
                                'foreignKey' => 'copyrightholder_id',
                                'dependent' => true,
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
    var $hasAndBelongsToMany = array(

            'Film' => array('className' => 'Film',
                        'joinTable' => 'copyrightholders_films',
                        'foreignKey' => 'copyrightholder_id',
                        'associationForeignKey' => 'film_id',
                        'unique' => true,
                        'conditions' => '',
                        'fields' => '',
                        'order' => '',
                        'limit' => '',
                        'offset' => '',
                        'finderQuery' => '',
                        'deleteQuery' => '',
                        'insertQuery' => '',
                        'with' => 'copyrightholders_films'
            )


    );

    var $actsAs = array('Containable', 'Sphinx');

//------------------------------------------------------------------------------

    /**
     * Получает список букв для правообладателей
     *
     * @return unknown
     */
    function getAlphabet()
    {
/* пока отключим кэш*/
        if (!($alphabet = Cache::read('Catalog.CopyrightholdersAlphabet', 'default')))
        {
/**/
            $sql = "select left(if (c.name <> '', c.name, c.name_en), 1) as letter
                    from copyrightholders as c
                    where c.hidden=0
                    group by letter
                    HAVING letter REGEXP '[[:alpha:]]+'
                    ORDER BY letter";
            $result = $this->query($sql);
            $alphabet = Set::extract('/0/letter', $result);

/* /пока отключим кэш*/
            Cache::write('Catalog.CopyrightholdersAlphabet', $alphabet, 'default');
        }
 /**/
        return $alphabet;
    }

//------------------------------------------------------------------------------

    /**
     * Получает список букв + 3 правообладателя для каждой буквы
     *
     * @return unknown
     */
    function getCopyrightholdersIndex()
    {

        $letters = $this->getAlphabet();
        $sql = "SELECT Copyrightholder.name, Copyrightholder.name_en, Copyrightholder.id
                FROM copyrightholders AS Copyrightholder
                WHERE (name LIKE '%s%%' OR name_en LIKE '%s%%') AND hidden=0
                ORDER BY RAND() LIMIT 3";
        $copyrightholders = array();
        foreach ($letters as $letter)
        {
            $copyrightholders[$letter] = $this->query(sprintf($sql, strtolower($letter), strtolower($letter)));
        }
        return $copyrightholders;
    }

//------------------------------------------------------------------------------

    function getCopyrightholdersFilms($id, $order = 'CF.copyrightholder_id ASC, Film.year ASC', $sqlCond = '')
    {
        $db =& ConnectionManager::getDataSource($this->useDbConfig);
        $sqlCondition = '`CF`.`copyrightholder_id` = ' . $db->value($id, 'integer');
        if (!empty($sqlCond))
        {
            $sqlCondition .= ' AND ' . $sqlCond;
        }
        $sql = '
        SELECT `C`.`id`, `C`.`name`, Film.year, Film.title, Film.id
        FROM `copyrightholders_films` AS `CF`
        LEFT JOIN `copyrightholders` AS `C` ON (`CF`.`copyrightholder_id` = `C`.`id`)
        LEFT JOIN `films` AS `Film` ON (`CF`.`film_id` = `Film`.`id` AND `Film`.`active` = 1)
        WHERE ' . $sqlCondition . ' AND C.hidden = 0 GROUP BY CF.copyrightholder_id, Film.title, Film.year ORDER BY ' . $order;
        return $this->query($sql);
    }

//------------------------------------------------------------------------------

    function getCholdersList()
    {
        $cholders_list = array();
/*
        $sql = "SELECT C.id, C.name, C.name_en, C.description
                FROM copyrightholders AS C
                ORDER BY name, name_en, description";

        $cholders_list = $this->query($sql);
*/

        $cholders_list = $this->find('all',array(
            'conditions' => array(),
            'fields' => array('`Copyrightholder`.`id`', '`Copyrightholder`.`name`', '`Copyrightholder`.`name_en`', '`Copyrightholder`.`hidden`', '`Copyrightholder`.`description`'),
            'order' => 'name, name_en, description',
            'recursive' => 1
        ));

        //$cholders_list = $this->find('all');
        return $cholders_list;
    }

//------------------------------------------------------------------------------

    function getCholderData($id=null){
        $cholder_data = array();
        if (empty($id) && intval($id)){return array();}
        $cholder_data = $this->find('first',array(
            'conditions' => array('`Copyrightholder`.`id`'=>$id),
            'fields' => array(  '`Copyrightholder`.`id`',
                                '`Copyrightholder`.`name`',
                                '`Copyrightholder`.`name_en`',
                                '`Copyrightholder`.`hidden`',
                                '`Copyrightholder`.`description`'),
                                'recursive' => 1
        ));
        return $cholder_data;
    }

//------------------------------------------------------------------------------

    function getImgFilenameByCopyrightholderId($id=null){
        $file_name='';
        if (!empty($id) && $id){
            $sql = "select cp.file_name as file_name
                    from copyrightholders_pictures as cp
                    where cp.copyrightholder_id=".$id.
                    " limit 1";
            $result = $this->query($sql);
            if ($result){
                $file_name = $result[0]['cp']['file_name'];
            }
       }
       return $file_name;
    }

//------------------------------------------------------------------------------

    function deleteLinkCopyrightholderFilm($fid=null, $cid=null){
        $result = false;
        if (!empty($fid) && !empty($cid) && $fid && $cid ){
            $sql = "delete  from copyrightholders_films
                            where
                            copyrightholders_films.copyrightholder_id=".$cid.
                            " and ".
                            " copyrightholders_films.film_id=".$fid;
            $result = $this->query($sql);
       }
       return $result;
    }

//------------------------------------------------------------------------------

    function addLinkCopyrightholderFilm($fid=null, $cid=null){
        $result = false;
        if (!empty($fid) && !empty($cid) && $fid && $cid ){
            $sql = "insert into copyrightholders_films
                    (copyrightholders_films.film_id,
                     copyrightholders_films.copyrightholder_id)
                    values(".$fid.",".$cid.")";
            $result = $this->query($sql);
       }
       return $result;
    }

//------------------------------------------------------------------------------

    function getCopyrightholderIdByName($cname = null ){
        $cid = 0;
        $new = false;
        if (!empty($cname) && $cname){

            $result = $this->find('first',array(
                                            'fields'=>'Copyrightholder.id',
                                            'recursive' => 0,
                                            'conditions'=>array('Copyrightholder.name'=>$cname),
                                            'limit'=>1));
            //pr($result);

            if ($result){
                $cid = $result['Copyrightholder']['id'];
            }
            else{

                $this->saveAll(array('name'=>$cname));
                $cid = $this->id;
                $new = true;

            }

        }
        $res = array('new'=>$new,'cid'=>$cid);
        return $res;
    }

//------------------------------------------------------------------------------

    function deleleLinksForCopyrightholder($cid = null){
        $result = false;
        if (!empty($cid) && $cid ){
            $sql = "delete  from copyrightholders_films
                            where
                            copyrightholders_films.copyrightholder_id=".$cid;
            $result = $this->query($sql);
       }
        return $result;
    }

//------------------------------------------------------------------------------

//------------------------------------------------------------------------------

}
?>