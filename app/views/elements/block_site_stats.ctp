<?php
//return array('posts' => $posts[0][0]['count'], 'threads' => $threads[0][0]['count'],
//             'faqs' => $faqs[0][0]['count'], $users);
//
?>
<p><?php echo __('Registered users', true) . ': ' . $block_site_stats['users'] ?></p>
<?php echo __('Threads number', true) . ': ' . $block_site_stats['threads'] ?><br>
<?php echo __('Posts number', true) . ': ' . $block_site_stats['posts'] ?><br>
<?php echo __('Faq items number', true) . ': ' . $block_site_stats['faqs'] ?><br>
