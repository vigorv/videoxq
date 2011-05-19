<?php
class News extends AppModel {

    var $name = 'News';
    var $validate = array(
        'title' => VALID_NOT_EMPTY,
    );
}
?>