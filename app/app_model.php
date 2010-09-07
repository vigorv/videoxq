<?php
// See http://bakery.cakephp.org/articles/view/improved-advance-validation-with-parameters
define('VALID_UNIQUE', 'isUnique');
define('VALID_HAS_PAIR', 'hasPair');
class AppModel extends Model
{
    var $cacheQueries = true;

    function isUnique($params)
    {

        $data = $this->find($params);
        if ($data && ! isset($this->data[$this->alias]['id']))
            return false;
        if ($data && $data[$this->alias]['id'] == $this->data[$this->alias]['id'])
            return true;

        return ! $this->hasAny($params);
    }

    function hasPair($params)
    {
        foreach ($params as $key => $value)
        {
            if (! isset($this->data[$this->alias][$key . '2']))
                return false;
            if ($this->data[$this->alias][$key . '2'] != $value)
                return false;
        }

        return true;
    }

    function getHabtm($arr, $tables, $fields)
    {
        if (is_string($tables))
            $tables = array($tables);
        $tmp = array();
        foreach ($arr as $element)
        {
            $tmpIn = array();

            foreach ($tables as $table)
            {
                foreach ($fields as $key => $field)
                {
                    if (isset($element[$table][$field]))
                        $tmpIn[$key] = $element[$table][$field];
                }
            }

            $tmp[] = $tmpIn;
        }
        return $tmp;
    }
    
    
    function updateHits($id, $increment = 1)
    {
    	$table=$this->useTable;
        $db =& ConnectionManager::getDataSource($this->useDbConfig);
        $sql = 'UPDATE '.$table.' SET hits=hits+' .$increment
               . ' WHERE id = ' . $db->value($id);
        $this->query($sql);
    }
    
    
}
?>