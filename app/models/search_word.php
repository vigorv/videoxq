<?php
App::import('Model', 'MediaModel');
class SearchWord extends MediaModel {

    var $name = 'SearchWord';
    var $validate = array(
        'words' => array('notempty'),
        'url' => array('notempty')
    );


    /**
     * Получает урл для поискового слова
     *
     * @param unknown_type $word
     * @return unknown
     */
    function getUrl($word)
    {
        $word = trim(str_replace(' ', '_', $word));
        $db = $this->getDataSource();
        $sql = 'SELECT `SearchWord`.`words`, `SearchWord`.`url`
                FROM `search_words` AS `SearchWord`
                WHERE FIND_IN_SET('.$db->value($word).', `words`)';
        return $this->query($sql);
    }


}
?>