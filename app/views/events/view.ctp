<?php //pr($Event)
    $javascript->link('jquery.fancybox-1.0.0', false);
    $javascript->link('jquery.pngFix', false);
    $script = "$(function() {
       $('a[rel=fancybox]').fancybox({
        'zoomSpeedIn':  0,
        'zoomSpeedOut': 0,
        'overlayShow':  true,
        'overlayOpacity': 0.8
    });
   });";
    $javascript->codeBlock($script, array('inline' => false));
    $html->css('fancy', null, array(), false);
?>
<div class="contentCol">

	<h2 class="newsDate"><?php echo strftime("%d-%m-%Y",$time->toUnix($Event['Event']['created'])); ?>
			  <?php if($Event['EventCategory']['id']>0) echo $html->link($Event['EventCategory']['title'], '/news/index/cat:' . $Event['EventCategory']['id']."/", array('class' => "newsCat")); ?>
	 
	</h2>
	<dl class="newsDay">
		<dt><?php echo strftime("%H:%M",$time->toUnix($Event['Event']['created'])); ?></dt>
		<dd>
			<a href="#" class="newsTitle"><?php echo $Event['Event']['title']; ?></a>
			<div class="newsBody">
                <?php
                if (!empty($Event['Attachment']['dir']))
                {
                	$img=$html->image('/img/'.$Event['Attachment']['dir'].'/'.$Event['Attachment']['filename'],array('align' => 'left'));
                    echo $html->link($img,'http://flux.itd/img/600x600/'.$Event['Attachment']['dir'].'/'.$Event['Attachment']['filename'], array('rel' => 'fancybox', 'style' => 'margin: 5px'), false, false);
                }
                ?>
                <?php echo str_replace(Configure::read('App.textSplitter'), '', $Event['Event']['text']); ?>
			</div>
		</dd>
	</dl>
        <p class="tags">Теги:
        <?php
        $tags = array();
        foreach ($Event['Tag'] as $tagVal)
        {
            $tags[] = '<a href="/tags/view/'.urlencode($tagVal['title']).'">' . h($tagVal['title']) . '</a>';
        }
        echo implode(', ', $tags);
        ?>
        </p>
<div class="contolsBar">
        <?php
        $numComments = count($Event['EventComment']);
        echo "<a href=\"#\" class=\"dashed\" onClick=\"$('div.comments').toggle();return false;\">".$app->pluralForm($numComments, array('комментарий', 'комментария', 'комментариев')).'</a>';
        ?>
            &nbsp;/&nbsp; <a href="#answerPost" class="addComment" onClick="showCommentBox(document.getElementById('comment'));">добавить</a>
            <?php if ($allowEdit): ?>
            <?php if($Event['Event']['access']=='private'){?><a href="/news/do_public/<?= $Event['Event']['id'] ?>" class="do_public">Опубликовать</a>
            <?php }?>
            <a href="/news/delete/<?= $Event['Event']['id'] ?>" class="delete" onclick="return confirm('Вы точно хотите удалить этот пост?');">Удалить</a>
            <a href="/admin/events/edit/<?= $Event['Event']['id'] ?>" class="edit">Редактировать</a>
            (<?=$Event['Event']['hits'] ?>)
            <?php endif; ?>
            <em><?= $app->timeShort($Event['Event']['created'], '<br>') ?></em>
            <a href="/bookmarks/add" class="fav" onClick="showBookmarkForm(this);return false;">В избранное</a>
            <a href="#" class="plus">Плюсануть</a>
            <a href="#" class="minus">Минуснуть</a>
        </div>
           <div id='bookmarkPlaceHolder'>
           </div>

    <?php
    echo $this->element('tree_comments', array('Comment' => $Event['EventComment'], 'Post' => $Event['Event'], 'comController' => 'EventComments'));
    
    ?>
    <?php if (!empty($neighbors['prev']))
        echo '<strong class="navLeft"><a href="/posts/view/'.$neighbors['prev']['Post']['id'].'">&#8592; '.h($neighbors['prev']['Post']['title']).'</a></strong>';
    ?>
    <?php if (!empty($neighbors['next']))
        echo '<strong class="navRight"><a href="/posts/view/'.$neighbors['next']['Post']['id'].'">'.h($neighbors['next']['Post']['title']).' &#8594;</a></strong>';
    ?>
</div>

