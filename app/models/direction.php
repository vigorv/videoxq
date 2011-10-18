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

    //подсчет количества новостей в категории c id = $direction_id
    function countNewsInDirection($direction_id = null, $do_count_hidden = false){
        App::import('model', 'News');
        $count_news_in_direction = 0;
        if (!empty ($direction_id)){
            $news = new News();
            $conditions = array ('News.direction_id' => $direction_id);
            //если не вести подсчет скрытых новостей то добавим условие
            if (!$do_count_hidden){
                $conditions[] = array ('hidden' => 0);
            }
            $count_news_in_direction = $news->findCount($conditions);
        }
        return $count_news_in_direction;
    }

    /**
     * получить список направлений-потомков по идентификатору предка
     *
     * @param integer $directionId - идентификатор направления-предка
     * @return mixed - массив идентификаторов направлений-потомков
     */
    function getSubDirections($directionId = 0)
    {
    	$directions = array();

    	if (empty($directionId))
    	{
    		return $directions;
    	}
    	else
    	{
    		$direction = $this->read(array('Direction.lft', 'Direction.rght'), $directionId);
    		if (!empty($direction))
    		{
    			$directions = $this->findAll(array('Direction.lft >' => $direction['Direction']['lft'], 'Direction.rght <' => $direction['Direction']['rght']));
    		}
    	}

    	return $directions;
    }
}
?>