<?php
class Block extends AppModel {

    var $name = 'Block';
    var $validate = array(
        'title' => array('notempty'),
        'order' => array('notempty'),
    );


    /**
     * Получает список активных блоков для контроллера/экшена
     *
     * @param string|null $controller
     * @param string|null $action
     * @return unknown
     */
    function getActiveBlocks($controller = null, $action = null)
    {
        $db = $this->getDataSource();
        $sql = 'SELECT `Block`.`id`, `Block`.`title`, `Block`.`controller`,
                       `Block`.`method`, `Block`.`element`, `Block`.`arguments`,
                       `Block`.`content`, `Block`.`type`, `Block`.`position`,
                       `Block`.`order`, `Block`.`enabled`, `Block`.`enabled_controller`,
                       `Block`.`enabled_action`
                FROM `blocks` AS `Block`
                WHERE `enabled` = 1
                AND (
                (FIND_IN_SET('.$db->value($controller).', `enabled_controller`) AND FIND_IN_SET('.$db->value($action).', `enabled_action`))
                OR (FIND_IN_SET('.$db->value($controller).', `enabled_controller`) AND `enabled_action` = "")
                OR (`enabled_controller` = "" AND `enabled_action` = ""))
                AND (NOT FIND_IN_SET('.$db->value($controller).', `disabled_controller`))
                ORDER BY `order` ASC';
        return $this->query($sql);
    }


}
?>