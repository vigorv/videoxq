<?php
App::import('Model', 'MediaModel');
class Feedback extends MediaModel {

    var $name = 'Feedback';
    var $validate = array(
        //'email' => array('rule' => 'email', 'message' => 'Вы не указали email или email неправильный.'),
        'film' => array('rule' => 'notempty', 'message' => 'Вы не указали название фильма.')
    );


    function paginateCount($conditions = null, $recursive = 0, $extra = array())
    {
        $cond = $join = '';
        $db = $this->getDataSource();

        $cond = $db->conditions($conditions, true, true, $this);

        $sql = 'SELECT COUNT(DISTINCT  `film`) AS `count` FROM `feedbacks` AS `Feedback` ' . $cond;
        $res = $this->query($sql);
        return $res[0][0]['count'];
    }


}
?>