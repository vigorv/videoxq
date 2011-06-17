<?php
class Direction extends AppModel {

    var $name = 'Direction';
    var $validate = array(
        'title' => VALID_NOT_EMPTY,
    );
}
?>