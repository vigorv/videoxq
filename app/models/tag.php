<?php
class Tag extends AppModel {

    var $name = 'Tag';
    var $validate = array(
        'title' => array('notempty')
    );

    var $hasAndBelongsToMany = array(
            'Post' => array('className' => 'Post',
                        'joinTable' => 'posts_tags',
                        'foreignKey' => 'tag_id',
                        'associationForeignKey' => 'post_id',
                        'unique' => true,
                        'conditions' => '',
                        'fields' => '',
                        'order' => '',
                        'limit' => '',
                        'offset' => '',
                        'finderQuery' => '',
                        'deleteQuery' => '',
                        'insertQuery' => ''
            ),

    );



    /**
     * Получает список тэгов для нужной нам сущности + частоту
     *
     * @param unknown_type $conditions
     * @param unknown_type $type
     * @param unknown_type $limit
     * @return unknown
     */
    function getTagFrequency($conditions = null, $type = 'Post', $limit = 40)
    {
        $cond = '';
        $db = $this->getDataSource();
        if (!empty($conditions))
        {
            $cond = array();
            foreach ($conditions as $key => $value)
            {
                $cond[] = $key . ' = ' . $db->value($value);
            }
            $cond = ' WHERE ' . implode(' AND ', $cond);
        }

        $sql = 'SELECT COUNT(`'.$type.'sTag`.`id`) as count, `Tag`.`title`
                FROM `'.strtolower($type).'s_tags` AS `'.$type.'sTag`
                LEFT JOIN `tags` AS `Tag` ON (`'.$type.'sTag`.`tag_id` = `Tag`.`id`)
                '.$cond.'
                GROUP BY `Tag`.`title`
                ORDER BY `count` desc,rand()
                LIMIT ' . $limit;

        $tags = $this->query($sql);
        if ($tags)
        {
	        $counts = Set::extract('/0/count', $tags);
	        $tags = Set::extract('/Tag/title', $tags);
	        return array_combine($tags, $counts);
        }
        else return array();
    }

    function getBlogTagFrequency($blogId, $limit = 40)
    {
        $db = $this->getDataSource();
        $sql = 'SELECT COUNT(`PostsTag`.`id`) as count, `Tag`.`title`
                FROM `posts_tags` AS `PostsTag`
                JOIN `tags` AS `Tag` ON (`PostsTag`.`tag_id` = `Tag`.`id`)
                JOIN `posts` AS `Post` ON (`PostsTag`.`post_id` = `Post`.`id` AND `Post`.`blog_id` = '.$db->value($blogId, 'integer').')
                GROUP BY `Tag`.`title`
                ORDER BY `count` desc,rand()
                LIMIT ' . $limit;

        $tags = $this->query($sql);
        if (empty($tags))
            return array();
        $counts = Set::extract('/0/count', $tags);
        $tags = Set::extract('/Tag/title', $tags);
        return array_combine($tags, $counts);
    }

    /**
     * Gets a comma-separated list of tags,
     * returns a list of tag ids,
     * saves new tags if needed
     *
     * @param string $tags
     * @return array
     */
    function parseTags($tagList = '')
    {
        $tagList = mb_strtolower($tagList);
        $tagArray = array_map('trim', explode(',', $tagList));
        $conditions = array('title' => $tagArray);
        $this->recursive = -1;
        $dbTags = $this->find('all', compact('conditions'));
        $extrTags = Set::extract('/Tag/title', $dbTags);
        $oldIds = Set::extract('/Tag/id', $dbTags);

        $newTags = array_diff($tagArray, $extrTags);

        $newIds = array();
        if (!empty($newTags))
        {
            foreach ($newTags as $tag)
            {
                $this->create();
                $this->save(array('Tag' => array('title' => $tag)));
                $newIds[] = $this->getInsertID();
            }

        }

        return am($oldIds, $newIds);
    }


}
?>