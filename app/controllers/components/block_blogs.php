<?php
App::import('component', 'BlocksParent');

class BlockBlogsComponent extends BlocksParentComponent
{

    /**
     * Получаем данные для отображения облака тегов
     *
     * @param unknown_type $args
     * @return array список тегов с кол-вом
     */
    function tagCloud($args)
    {
//        $key = $this->controller->params['controller'] . '_'
//               . $this->controller->params['action'] . implode('_', $this->controller->params['pass']);
//
//        if ($stats = Cache::read('App.tagCloud_' . $key, 'default'))
//            return $stats;

        $time = 900;
        if (isset($args['time']))
            $time = $args['time'];

        $cache = '+1 minute';
        if (isset($args['cache']))
            $cache = $args['cache'];

        $objectId = !empty($this->data['Blocks']['objectId']) ? $this->data['Blocks']['objectId'] : null;
        $type = !empty($this->data['Blocks']['type']) ? $this->data['Blocks']['type'] : 'Post';

        $model = ClassRegistry::init('Tag');
        if (!empty($this->data['Blocks']['Blog']['id']))
            $tags = $model->getBlogTagFrequency($this->data['Blocks']['Blog']['id']);
        else
            $tags = $model->getTagFrequency(null, 'Post');

        return $tags;
//        Cache::config('default');
//        Cache::write('Block.onlineUsers', $stats, $cache);
//        return $stats;
    }


    /**
     * Enter description here...
     *
     * @return array список послеедних постов с комментами
     */
    function activity()
    {
        $blogId = !empty($this->data['Blocks']['Blog']['id']) ? $this->data['Blocks']['Blog']['id'] : null;
        $model = ClassRegistry::init('Blog');
        $posts = $model->getActivity($blogId);
        return $posts;
    }
}
?>
