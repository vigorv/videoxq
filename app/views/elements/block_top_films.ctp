<h3><?php __("Top"); ?> 10</h3>
<?php
$lang = Configure::read('Config.language');
$langFix = '';
if ($lang == _ENG_) $langFix = '_en';
foreach ($block_top_films as $post)
{
    echo '<p><a href="/media/view/' . $post['Film']['id'] . '">'
         . h($post['Film']['title' . $langFix]) . '</a></p>';
}
?>
