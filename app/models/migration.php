<?php
class Migration extends MediaModel {

    var $name = 'Migration';


    function checkPoint()
    {
        $this->useDbConfig = 'migration';
        $sql = 'SELECT * FROM check_dates AS `check` order by id desc limit 1';

        $objects = $this->query($sql);

        extract($objects[0]['check']);

        $this->useDbConfig = $this->defaultConfig;

        $this->create();
        $this->save(array('Migration' => array('modified' => $timestamp)));
    }

    /**
     * Returns last catalog check date
     *
     * @return timestamp
     */
    function lastCheckDate()
    {
        $this->useDbConfig = 'migration';
        $sql = 'SELECT * FROM check_dates AS `check` order by id desc limit 1';

        $objects = $this->query($sql);

        if (empty($objects))
            return null;

        $this->useDbConfig = $this->defaultConfig;

        return $objects[0]['check']['timestamp'];

    }


    function lastMigrationDate()
    {
        $last = $this->find('first', array('order' => 'id DESC'));
        if ($last)
            $date = $last['Migration']['modified'];
        else
            $date = null;
        return $date;
    }

}
?>
