<?php

App::import('behavior', 'Attachment');
class ImageBehavior extends AttachmentBehavior
{

    /**
     * Hook into find method to add
     * additional Information like Exif metadata
     *
     * @param object $model
     * @param array $results
     * @param boolean $primary
     * @return array
     */
    public function afterFind(&$model, $results, $primary = false) {
            return $results;
    }
}
/**
 * Attachment Model
 *
 * Do not define during tests because fixture will be used instead
 */
if(!class_exists('Attachment')) {

class Attachment extends AppModel {
    var $name = 'Attachment';
    var $useTable = 'attachments';

    var $order = 'modified DESC';

}

}

?>