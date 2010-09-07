<?php
App::import('component', 'BlocksParent');

class BlockEventsComponent extends BlocksParentComponent
{
    /**
     * 
     *
     * @return unknown
     */
    function Categories()
    {
        if ($filter = Cache::read('Event.Categories', 'default'))
        {
            return $filter;
        }
        
         $model = ClassRegistry::init('EventCategory');
       
//        $categories = $model->find('all', array('order' => 'title',
//                                                            'fields' => array('title'),
//                                                            'contain' => array()
//                                                            )
//        );
        //$categories = $model->generateList(null,'title ASC',null,'{n}.EventCategory.id','{n}.EventCategory.title' );
        $categories=$model->query("SELECT `EventCategory`.`title`, `EventCategory`.`id` FROM `news_categories` AS `EventCategory` INNER JOIN `news_categories` AS `EventCategoryParent` ON (`EventCategory`.`parent_id` = `EventCategoryParent`.`id`) where `EventCategory`.id<>0 ORDER BY `id` ASC");
        $categories=SET::combine($categories,'{n}.EventCategory.id','{n}.EventCategory.title');
        Cache::write('Event.Categories', $categories, array('config' => 'default', 'duration' => '+1 day'));
        return $categories;
    }
}


?>