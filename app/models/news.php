<?php

class News extends AppModel {

    var $name = 'News';
    var $validate = array(
        'title' => VALID_NOT_EMPTY,
    );

    function GetCatNews($pos, &$dir=array(),$count=3) {
        if ($pos >=0) {
            App::import('model', 'Direction');
            $direction = new Direction();
            $dirs = $direction->findAll(array('Direction.hidden' => 0), null, 'Direction.srt DESC');
            reset($dirs);
            while ($pos > 0) {
                if (count($dirs) <= $pos)
                    return null;
                next($dirs);
                $pos--;
            }
            $dir = current($dirs);
            $dir = $dir['Direction'];
            $dir_id = $dir['id'];
        }
        $conditions = array('News.hidden' => 0);
        if (!empty($dir_id))
            $conditions['News.direction_id'] = $dir_id;
        return $this->findAll($conditions, null, 'News.created DESC', $count);
    }

}

?>