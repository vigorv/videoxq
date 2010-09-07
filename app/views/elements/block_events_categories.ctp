	<ul class="subMenu">
		<li class="newsAdd"><a href="/news/add">Прислать новость</a></li>
		<li class="newsAdd"><a href="/news/index/type:private">Не опубликованные</a></li>
		<li class="expandNews"><a href="#">Свернуть анонсы</a></li>
	</ul>
	<script type="text/javascript">
		var news_expanded = true;
		$(document).ready(function(){
			$('li.expandNews a').click(function() { toggleNews(this); return false; })
		});
		function toggleNews(a) {
			if (news_expanded) {
				$('div.newsBody').hide();
				$(a).html('Развернуть анонсы');
				news_expanded = false;
			} else {
				$('div.newsBody').show();
				$(a).html('Свернуть анонсы');
				news_expanded = true;
			}
		};
	</script>
	
	<ul class="categorys">
        <?php
        $cat=(isset($this->params['named']['cat']))?$this->params['named']['cat']:0;
        
        $class='';
        $a="<a href=/news/index/>";
        $a_close="</a>";
        if ($cat==0 && $this->action=='index')
        {
        	$class= 'class="active"';
        	$a='';$a_close='';
        	
        }
	?>
		<li <?=$class?>><?=$a?>Все новости<?=$a_close?></li>
	<?php 
	foreach($block_events_categories as $id=>$category)
	{
        $class='';
        $a="<a href=/news/index/cat:{$id}>";
        $a_close="</a>";
        if ($cat==$id)
        {
        	$class= 'class="active"';
        	$a='';$a_close='';
        }
	?>		
		<li <?=$class?>><?=$a?><?=$category?><?=$a_close?></li>
		
	<?}?>
	</ul>

