<?php
class Direction extends AppModel {

    var $name = 'Direction';
    var $actsAs = array('Tree');
    var $validate = array(
        'title' => VALID_NOT_EMPTY,
    );
    var $order = 'Direction.lft ASC';


    /**
     * проверка наличия новостей в списке категорий (массив id категорий)
     *
     * @param integer $direction_ids - массив идентификаторов направления
     *
     * @return boolean $no_news_in_directions - нет новостей в этом направлении?
     */
    function checkNoNewsInDirections($direction_ids = array()){
        App::import('model', 'News');
        $no_news_in_directions = false;
        $news = new News();
        $conditions['News.direction_id'] = $direction_ids;
        $count = $news->findCount($conditions);
        if (!$count) {$no_news_in_directions = true;}

        return $no_news_in_directions;
    }

    /**
     * проверка скрытости категории (направления)
     *
     * @param integer $direction_id - идентификатор направления
     *
     * @return boolean $visible - нет новостей в этом направлении?
     */
    function checkDirectionIsHidden($direction_id = null){
        $is_hidden = false;
        if (!empty($direction_id) && $direction_id){
            $conditions = array ('hidden' => 1);
            $result = $this->find('first',$conditions,array('id'));
            if ($result) {$is_hidden = true;}
        }
        return $is_hidden;
    }


    /**
     * подсчет количества новостей в списке категорий (массив id категорий)
     *
     * @param integer $direction_ids - массив идентификаторов направления
     * @param boolean $do_count_hidden - учитывать ли в подсчете скрытые
     * направления
     *
     * @return integer $count_news_in_direction - количество новостей в
     * направлении
     */
    function countNewsInDirections($direction_ids = null, $do_count_hidden = false){
        App::import('model', 'News');
        $count_news_in_direction = 0;
        if (!empty ($direction_ids)){
            $news = new News();
            $conditions = array ('News.direction_id' => $direction_ids);
            //если не вести подсчет скрытых новостей то добавим условие
            if (!$do_count_hidden){
                $conditions[] = array ('hidden' => 0);
            }
            $count_news_in_direction = $news->findCount($conditions);
        }
        return $count_news_in_direction;
    }

    /**
     * подсчет количества новостей в направлении c id = $direction_id
     *
     * @param integer $direction_id - идентификатор направления
     * @param boolean $do_count_hidden - учитывать ли в подсчете скрытые
     * направления
     * @return integer $count_news_in_direction - количество новостей в
     * направлении
     */
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
     * @param boolean $get_hidden - включить в выборку скрытые направления
     * 
     * @return mixed - массив идентификаторов направлений-потомков
     */
    function getSubDirections($directionId = 0, $get_hidden = true)
    {
    	$directions = array();

    	if (empty($directionId))
    	{
            return $directions;
    	}
    	else
    	{
            $conditions = array();
            if (!$get_hidden){
                $conditions = array('hidden' => 0);
            }

            $direction = $this->read(array('Direction.lft', 'Direction.rght'), $directionId);
            if (!empty($direction))
            {
                    $directions = $this->findAll(array('Direction.lft >' => $direction['Direction']['lft'], 'Direction.rght <' => $direction['Direction']['rght'], $conditions));
            }
    	}

    	return $directions;
    }



}
?>