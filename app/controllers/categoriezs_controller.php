<?php
class CategoriezsController extends AppController {

    var $helpers = array('Html', 'Form');
    var $name = 'Categoriezs';

    function index() {
        $this->data = $this->Categoriez->generatetreelist(null, null, null, '&nbsp;&nbsp;&nbsp;');
        pr($this->data);

        $allChildren = $this->Categoriez->children(9); // a flat array with 11 items
        pr($allChildren);
        // -- or --
        $this->Categoriez->id = 9;
        $allChildren = $this->Categoriez->children(); // a flat array with 11 items
        pr($allChildren);
        // Only return direct children
        $directChildren = $this->Categoriez->children(9, true); // a flat array with 2 items
        pr($directChildren);

	}
}
?>