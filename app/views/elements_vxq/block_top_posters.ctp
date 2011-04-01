<?php
foreach ($block_top_posters as $key => $user)
{
    echo '#' . $key . ' - ' . $html->link($user['user']['username'], '/vb/member.php?u=' . $user['user']['userid']) . '<br>';
    echo __('Joined', true) . ': ' . date('Y-m-d', $user['user']['joindate'])
        . ', ' . __('Num posts', true) . ': ' . $user['user']['posts'] . '<br>';
}
?>