<?php
class Post extends AppModel {

    var $name = 'Post';
    var $validate = array(
        'text' => array('notempty')
    );

    //The Associations below have been created with all possible keys, those that are not needed can be removed
    var $belongsTo = array(
            'User' => array('className' => 'User',
                                'foreignKey' => 'user_id',
                                'conditions' => '',
                                'fields' => '',
                                'order' => ''
            ),
            'Blog' => array('className' => 'Blog',
                                'foreignKey' => 'blog_id',
                                'conditions' => '',
                                'fields' => '',
                                'order' => ''
            ),
            'UserPicture' => array('className' => 'UserPicture',
                                'foreignKey' => 'user_picture_id',
                                'dependent' => false,
                                'conditions' => '',
                                'fields' => '',
                                'order' => ''
            )
    );

    var $hasMany = array(
            'Comment' => array('className' => 'Comment',
                                'foreignKey' => 'post_id',
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

    var $hasAndBelongsToMany = array(
            'Tag' => array('className' => 'Tag',
                        'joinTable' => 'posts_tags',
                        'foreignKey' => 'post_id',
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

    public $accessLevels = array('public' => 'для всех' , 'friends' => 'для друзей' , 'private' => 'для себя');

    /**
     * Что-то жуткое:)
     *
     * @param array $conditions
     * @param unknown_type $fields
     * @param unknown_type $order
     * @param unknown_type $limit
     * @param unknown_type $page
     * @param unknown_type $recursive
     * @param unknown_type $extra
     * @return unknown
     */
    function paginate($conditions, $fields, $order, $limit, $page = 1, $recursive = null, $extra = array())
    {
        $page  = $page ? $page : 1;
        $limit = 'LIMIT ' . (($page - 1) * $limit) . ', ' . $limit;
        $cond = $join = '';
        $db = $this->getDataSource();

        if (isset($conditions['tag']))
        {
            $join = ' JOIN `posts_tags` AS `Tag` ON (`Tag`.`post_id` = `Post`.`id`
                     AND `Tag`.`tag_id` = '.$db->value($conditions['tag'], 'integer').') ';
            unset($conditions['tag']);
        }

        $cond = $db->conditions($conditions, true, true, $this);

        $sql = 'SELECT `Post`.*, `Blog`.`title`, `Blog`.`id`, `UserPicture`.`file_name`,
                 COUNT(`Comment`.`id`) as commentCount, `User`.`userid`, `User`.`username`
                FROM `posts` AS `Post`
                LEFT JOIN `user` AS `User` ON (`Post`.`user_id` = `User`.`userid`)
                LEFT JOIN `blogs` AS `Blog` ON (`Post`.`blog_id` = `Blog`.`id`)
                LEFT JOIN `user_pictures` AS `UserPicture` ON (`Post`.`user_picture_id` = `UserPicture`.`id`)
                LEFT JOIN `comments` AS `Comment` ON (`Comment`.`post_id` = `Post`.`id`)
                ' . $join . '
                ' . $cond . '
                GROUP BY `Post`.`id`
                ORDER BY `Post`.`created` desc
                ' . $limit;

        $posts = $this->query($sql);

        if (!empty($posts))
        {
            $postIds = Set::extract('/Post/id', $posts);
            //pr($postIds);
            $this->PostsTag->bindModel(array('belongsTo' => array('Tag')), false);

            $tags = $this->PostsTag->find('all', array('conditions' => array('post_id' => $postIds)));


            foreach ($posts as &$post)
            {
                foreach ($tags as $tag)
                {
                    if ($tag['PostsTag']['post_id'] == $post['Post']['id'])
                       $post['Tag'][] = $tag['Tag']['title'];
                }
            }
        }
        return $posts;
    }


    function paginateCount($conditions = null, $recursive = 0, $extra = array())
    {
        $cond = $join = '';
        $db = $this->getDataSource();

        if (isset($conditions['tag']))
        {
            $join = ' JOIN `posts_tags` AS `Tag` ON (`Tag`.`post_id` = `Post`.`id`
                     AND `Tag`.`tag_id` = '.$db->value($conditions['tag'], 'integer').') ';
            unset($conditions['tag']);
        }

        $cond = $db->conditions($conditions, true, true, $this);

        $sql = 'SELECT COUNT(*) AS `count` FROM `posts` AS `Post` ' . $join . $cond;
        $res = $this->query($sql);
        return $res[0][0]['count'];
    }
}
?>