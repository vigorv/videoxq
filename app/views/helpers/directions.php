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
//            'class' => 'top_lvl_item',
            'id' => 'top_lvl_item'
        );

        $this->output .= $this->Tree->generate($tree_data, $settings);

        return $this->output;
    }

    /*--------------------------------------------------------------------------
     * showHtmlTree
     *--------------
     *
     * упрощенный, оптимизированый способ генерации дерева html в виде
     * списка, с использванием кейковскоко метода модель->generatetreelist()
     * очень удобно! не надо заморачиваться с рекурсией и т.п. на выходе уже
     * готовое дерево, отсортировано и по порядку вложенности, для пометки и
     * подсчета уровня вложенности, используем символ "#", количество "#" в
     * начале строки, соотвествует уровню вложенности. ВНИМАНИЕ! Этот символ
     * "#" являеется "служебным", и не должен присутствовать в значении
     * самого заголовка, в нашем случае поле 'title' данной модели, иначе
     * используем другой специальный символ.
     *
     *
     * @param array $tree_list_data - результат кейковскоко метода модель->generatetreelist()
     * @param integer $current_id - id раздела новостей
     * @param string $level_char - спец. символ для подсчета уровня вложенности
     * @param string $html_container_id - id html контейнера для дерева <ul>...
     *
     * @return string $this->output - подготовленный список дерева <ul>...
     */
    function showHtmlTree($tree_list_data = array(), $current_id = 0, $level_char = '#', $html_container_id = 'left-menu'){
        $this->Javascript->link('jstree/jquery.jstree.js', false);

        $this->output.=$this->Javascript->codeBlock('
            jQuery(document).ready(function() {
//                $("#current_element").children("a").css({"background-color" : "#aaa", "color" : "#fff", "width" : "250px", "padding" : "8px 0 8px 3px"});
//                $("#'.$html_container_id.'").hide();
                $("#'.$html_container_id.'").jstree({
                    "plugins" : ["themes","html_data","ui"],
                    "core" : { "initially_open" : [ "current_element" ]},
                    "ui" : {"disable_selecting_children" : ["true"]},
                    "themes" : {
			"theme" : "default",
			"dots" : false,
			"icons" : false
                        }
                    })
                $("#'.$html_container_id.'").fadeIn();
            });
            '
        );

//        $this->output.='';

        //инициализируем рабочие переменные
        $html_tree = '';
        $n=-1;

        foreach($tree_list_data as $direction_id => $direction_title){
            //смотрим кол-во $level_char в текущей строке
            $n2 = substr_count ( $direction_title, $level_char);
            //разберем все случаи перед открытием тега <li>, когда нужно закрыть
            //предыдущие теги (уйти на уровень назад) или открыть новые ul (уйти
            //на уревень вверх)
            //
            //количество $level_char в предыдущей строке хранится в $n
            //
            //если кол-во $level_char осталось такое же, как было, то просто
            //закрываем открытый тег <li> и все
            if($n2 == $n) {$html_tree .= '</li>';}

            //если кол-во $level_char увеличилось, значит это новый уровень
            //вложенности, а это повод открыть тег <ul> :)))))
            //увеличивается уровень только на 1 (это гарантируем кейковский
            //метод модели генерации списка)
            elseif($n2 > $n) {
                $tag_id = '';
                //если это самый первый, т.е. корневой элемент, назначим
                //id ="root" для тега <ul>, может пригодиться в дальнейшем :)
                if (!$html_tree) {$tag_id = ' id="root"';}
                $html_tree .= '<ul'.$tag_id.'>';
            }

            //если кол-во $level_char уменьшилось, значит возвращаемся на один
            //из предыдущих уровней вложенности, на какой именно зависит от
            //разницы  в количестве $level_char, это повод закрыть теги <li><ul>
            // :)))))
            // если $n-$n2=1 то уходим на один уровень назад, т.е. добавляем
            //к строке вывода "</li></ul>", если =2, то "</li></ul></li></ul>",
            //и в конец всего этого дела еще добавляем "</li>" для закрытия
            //ранее открытого тега <li>
            elseif($n2 < $n) {$html_tree .= str_repeat('</li></ul>',$n-$n2).'</li>';}

            //и наконец-то после длительного анализаоткрываем тег  <li>, для
            //текущего элемента, если его id совпадает с id выбранного раздела,
            //то пометим этот тег как текущий, дабы JS-скрипт знак какой элемент
            //дерева раскрыть по умолчанию
            $current_element = '';
            if ($direction_id == $current_id) {$current_element = ' id="current_element"';}
            $html_tree .= '<li'.$current_element.'>';
            //и добавим сам текст заголовка, удалив из него все вхождения спец.
            //символа
            $direction_title = str_replace($level_char, '', $direction_title);
            $direction_title_caption = $direction_title;
            //если надо обрежем слишкоб длинную строку, превышающую $title_max_size
            $title_max_size = 15;
            if (mb_strlen ($direction_title_caption) > $title_max_size){
                $direction_title_caption = mb_substr($direction_title_caption, 0, $title_max_size - 3).'...';
            }
            $html_tree .= '<a href="/news/index/' . $direction_id . '" title="' . $direction_title . '" onclick="window.location.href =$(this).attr(\'href\')">' ;
            $html_tree .= $direction_title_caption;
            $html_tree .= '</a>';
            //перед следующим циклом сохраняем текущее кол-во $level_char в $n
            $n = $n2;
        }
        //если были элементы в списке то надо закрыть первоначальный тег ul
        if ($html_tree) $html_tree .= '</ul>';


/* обертывание пока отменяется ))))))))))))

        //обернем все меню еще одним главным пунктом "Все категории" и назначим
        //ему id="root"
        $current_element = '';
        if (!$current_id) { $current_element = ' id="current_element"';}
        $html_tree = '<ul id="root">'.
                '<li'.$current_element.'><a href="/news" onclick="window.location.href =$(this).attr(\'href\')">Все категории</a></li>'.
                $html_tree.
                '</ul>';
*/

        //всю менюшку в контейнер <div> !!!!
        $html_tree = '<div id="' . $html_container_id . '" style="display: none">' . $html_tree . '</div>';
        $this->output .= $html_tree;
        return $this->output;
    }

}

?>
