<?php
class Blog extends AppModel {

    var $name = 'Blog';
    var $validate = array(
        'title' => array('notempty')
    );

    //The Associations below have been created with all possible keys, those that are not needed can be removed
    var $belongsTo = array(
            'User' => array('className' => 'User',
                                'foreignKey' => 'user_id',
                                'conditions' => '',
                                'fields' => '',
                                'order' => ''
            )
    );

    var $hasMany = array(
            'Post' => array('className' => 'Post',
                                'foreignKey' => 'blog_id',
                                'dependent' => false,
                                'conditions' => '',
                                'fields' => '',
                                'order' => '',
                                'limit' => '',
                                'offset' => '',
                                'exclusive' => '',
                                'finderQuery' => '',
                                'counterQuery' => ''
            ),
    );

    var $hasAndBelongsToMany = array(
            'Tag' => array('className' => 'Tag',
                        'joinTable' => 'blogs_tags',
                        'foreignKey' => 'blog_id',
                        'associationForeignKey' => 'tag_id',
                        'unique' => true,
                        'conditions' => '',
                        'fields' => '',
                        'order' => '',
                        'limit' => '',
                        'offset' => '',
                        'finderQuery' => '',
                        'deleteQuery' => '',
                        'insertQuery' => ''
            )
    );

    var $actsAs = array('Containable');

    /**
     * Получает список последних постов с комментариями
     *
     * @param int|null $blogId
     * @return unknown
     */
    function getActivity($blogId = null)
    {


        $condition = '';
        if ($blogId)
        {
            $db = $this->getDataSource();
            $condition = 'WHERE `Post`.`blog_id` = ' . $db->value($blogId, 'integer');
        }

        $sql = 'SELECT `Post`.`title`, `Post`.`id`,  IFNULL(`Comment`.`created`, `Post`.`created`) AS created
                FROM `posts` AS `Post`
                LEFT JOIN `comments` AS `Comment` ON (`Comment`.`post_id` = `Post`.`id`)'
                . $condition
                . ' GROUP BY `Post`.`title` ORDER BY created DESC LIMIT 10';
        return $this->query($sql);
    }



}
?>