<div class="films form">
<?php echo $form->create('Film');?>
	<fieldset>
 		<legend><?php __('Add Film');?></legend>
	<?php
		echo $form->input('film_type_id');
		echo $form->input('title');
		echo $form->input('title_en');
		echo $form->input('description');
		echo $form->input('active');
		echo $form->input('year');
		echo $form->input('dir');
		echo $form->input('hits');
		echo $form->input('imdb_rating');
		echo $form->input('imdb_id');
		echo $form->input('imdb_votes');
		echo $form->input('imdb_date');
		echo $form->input('oscar');
		echo $form->input('Country');
		echo $form->input('Emotion');
		echo $form->input('Genre');
		echo $form->input('Person');
		echo $form->input('Publisher');
		echo $form->input('Theme');
	?>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('List Films', true), array('action'=>'index'));?></li>
		<li><?php echo $html->link(__('List Film Types', true), array('controller'=> 'film_types', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Film Type', true), array('controller'=> 'film_types', 'action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List Media Ratings', true), array('controller'=> 'media_ratings', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Media Rating', true), array('controller'=> 'media_ratings', 'action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List Film Comments', true), array('controller'=> 'film_comments', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Film Comment', true), array('controller'=> 'film_comments', 'action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List Film Pictures', true), array('controller'=> 'film_pictures', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Film Picture', true), array('controller'=> 'film_pictures', 'action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List Film Variants', true), array('controller'=> 'film_variants', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Film Variant', true), array('controller'=> 'film_variants', 'action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List Source Links', true), array('controller'=> 'source_links', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Source Link', true), array('controller'=> 'source_links', 'action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List Countries', true), array('controller'=> 'countries', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Country', true), array('controller'=> 'countries', 'action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List Emotions', true), array('controller'=> 'emotions', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Emotion', true), array('controller'=> 'emotions', 'action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List Genres', true), array('controller'=> 'genres', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Genre', true), array('controller'=> 'genres', 'action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List People', true), array('controller'=> 'people', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Person', true), array('controller'=> 'people', 'action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List Publishers', true), array('controller'=> 'publishers', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Publisher', true), array('controller'=> 'publishers', 'action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List Themes', true), array('controller'=> 'themes', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Theme', true), array('controller'=> 'themes', 'action'=>'add')); ?> </li>
	</ul>
</div>
