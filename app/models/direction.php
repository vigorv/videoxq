<?php
class Direction extends AppModel {

    var $name = 'Direction';
    var $actsAs = array('Tree');
    var $validate = array(
        'title' => VALID_NOT_EMPTY,
    );
    var $order = 'Direction.lft ASC';


    //проверка наличия новостей в списке категорий (массив id категорий)
    function checkNoNewsInDirections($direction_ids = array()){
        App::import('model', 'News');
        $no_news_in_directions = false;
        $news = new News();
        $conditions['News.direction_id'] = $direction_ids;
        $count = $news->findCount($conditions);
        if (!$count) {$no_news_in_directions = true;}

        return $no_news_in_directions;
    }
}
?>