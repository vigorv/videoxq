<?php
class Page extends AppModel {

    var $name = 'Page';
    var $validate = array(
        'title' => VALID_NOT_EMPTY,
        'text' => VALID_NOT_EMPTY,
    );

    var $actsAs = array('Sluggable' =>
                        array('slug' => 'url', 'overwrite' => true, 'translation' => 'utf-8'));


}
?>