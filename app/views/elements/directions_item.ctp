<?php
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
if ($data['Direction']['id'] == $directions_data['current_id']) {
//     $tree->addItemAttribute('class', 'current');
    $tree->addItemAttribute('id', 'current_item');
} else {
//     $tree->addTypeAttribute('style', 'display', 'none', 'next');
//     $tree->addTypeAttribute('style', 'display', 'none', );
    if ($depth > 1){
        //$tree->addTypeAttribute('style', 'display', 'none');
        //$tree->addItemAttribute('class', 'hidden','next');
    }

}
echo $html->link($data['Direction']['title'].' ('.$data['Direction']['count_news'].')', array('action' => 'news', $data['Direction']['id']));
?>