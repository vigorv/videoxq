<?php

/*

 - подготовка данных в контроллере:

      //текущий id раздела (Direction.direction_id)
       $directions_data['current_id'] = 3;

      //данные дерева, формируем как угодно, ниже показаны 4 варианта
        // 1
        $tree_data = $this->Direction->find('all', array(
            'fields' => array('title', 'lft', 'rght'),
            'order' => 'lft ASC'));
        // 2
        $tree_data = $this->Direction->findAllThreaded();

        // 3
        $id = 2;
        $showMeFirstChildrenOnly = false;
        $tree_data = $this->Direction->children($id, $showMeFirstChildrenOnly);

        // 4
        $this->set('tree_data', $tree_data);

        $directions_data['list'] = $tree_data;
        $this->set('directions_data', $directions_data);

- вызов хелпера из представления:
    echo $directions->showTree($directions_data['list'],$directions_data['current_id']);
 */

/*------------------------------------------------------------------------------
 *  Переменные доступные в представлении элемента
 *
 *    $data // the row of data passed to the helper
 *    $depth // depth in the current tree 1 = first item
 *    $hasChildren // whether the current row has children or not
 *    $hasVisibleChildren // whether the current row has Visible children or not. Only relavent for MPTT tree data
 *    $numberOfDirectChildren // only avaliable with recursive data
 *    $numberOfTotalChildren // only available with MPTT tree data
 *    $firstChild // whether the current row is the first of it's siblings or not
 *    $lastChild // whether the current row is the last of it's siblings or not
 */
//------------------------------------------------------------------------------
class DirectionsHelper extends AppHelper {
    var $helpers = array('Javascript', 'Tree');
    var $output ='';



    function showTree($tree_data = array(), $current_id = null){
        $this->output.=$this->Javascript->codeBlock('
jQuery(document).ready(function() {

});
        ');

        $settings = array(
            'alias' => 'title',
            'type' => 'ul',
            'itemType' => 'li',
            'element' => 'directions_item',
            'class' => 'top_lvl_item'
        );

        $this->output .= $this->Tree->generate($tree_data, $settings);

        return $this->output;
    }
}

?>
