<?php
class Comment extends AppModel {

    var $name = 'Comment';
    var $validate = array(
        'text' => array('notempty')
    );

    var $actsAs = array('Tree');
    //var $actsAs = array('Tree' => array('scope' => 'Post'));


    //The Associations below have been created with all possible keys, those that are not needed can be removed
    var $belongsTo = array(
            'Post' => array('className' => 'Post',
                                'foreignKey' => 'post_id',
                                'conditions' => '',
                                'fields' => '',
                                'order' => ''
            ),
            'User' => array('className' => 'User',
                                'foreignKey' => 'user_id',
                                'conditions' => '',
                                'fields' => '',
                                'order' => ''
            ),
            'UserPicture' => array('className' => 'UserPicture',
                                'foreignKey' => 'user_picture_id',
                                'conditions' => '',
                                'fields' => '',
                                'order' => ''
            ),
    );

}
?>