<div class="films view">
<h2><?php  __('Film');?></h2>
    <dl><?php $i = 0; $class = ' class="altrow"';?>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php echo $film['Film']['id']; ?>
            &nbsp;
        </dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Film Type'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php echo $html->link($film['FilmType']['title'], array('controller'=> 'film_types', 'action'=>'view', $film['FilmType']['id'])); ?>
            &nbsp;
        </dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Title'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php echo $film['Film']['title']; ?>
            &nbsp;
        </dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Title En'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php echo $film['Film']['title_en']; ?>
            &nbsp;
        </dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Description'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php echo $film['Film']['description']; ?>
            &nbsp;
        </dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Active'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php echo $film['Film']['active']; ?>
            &nbsp;
        </dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Year'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php echo $film['Film']['year']; ?>
            &nbsp;
        </dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Dir'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php echo $film['Film']['dir']; ?>
            &nbsp;
        </dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Created'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php echo $film['Film']['created']; ?>
            &nbsp;
        </dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Modified'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php echo $film['Film']['modified']; ?>
            &nbsp;
        </dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Hits'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php echo $film['Film']['hits']; ?>
            &nbsp;
        </dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Imdb Rating'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php echo $film['Film']['imdb_rating']; ?>
            &nbsp;
        </dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Imdb Id'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php echo $film['Film']['imdb_id']; ?>
            &nbsp;
        </dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Imdb Votes'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php echo $film['Film']['imdb_votes']; ?>
            &nbsp;
        </dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Imdb Date'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php echo $film['Film']['imdb_date']; ?>
            &nbsp;
        </dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Oscar'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php echo $film['Film']['oscar']; ?>
            &nbsp;
        </dd>
    </dl>
</div>
<div class="actions">
    <ul>
        <li><?php echo $html->link(__('Edit Film', true), array('action'=>'edit', $film['Film']['id'])); ?> </li>
        <li><?php echo $html->link(__('Delete Film', true), array('action'=>'delete', $film['Film']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $film['Film']['id'])); ?> </li>
        <li><?php echo $html->link(__('List Films', true), array('action'=>'index')); ?> </li>
        <li><?php echo $html->link(__('New Film', true), array('action'=>'add')); ?> </li>
        <li><?php echo $html->link(__('List Film Comments', true), array('controller'=> 'film_comments', 'action'=>'index')); ?> </li>
        <li><?php echo $html->link(__('New Film Comment', true), array('controller'=> 'film_comments', 'action'=>'add')); ?> </li>
    </ul>
</div>
    <div class="related">
        <h3><?php  __('Related Media Ratings');?></h3>
    <?php if (!empty($film['MediaRating'])):?>
        <dl>	<?php $i = 0; $class = ' class="altrow"';?>
            <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id');?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
    <?php echo $film['MediaRating']['id'];?>
&nbsp;</dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Num Votes');?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
    <?php echo $film['MediaRating']['num_votes'];?>
&nbsp;</dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Rating');?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
    <?php echo $film['MediaRating']['rating'];?>
&nbsp;</dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Object Id');?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
    <?php echo $film['MediaRating']['object_id'];?>
&nbsp;</dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Type');?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
    <?php echo $film['MediaRating']['type'];?>
&nbsp;</dd>
        </dl>
    <?php endif; ?>
        <div class="actions">
            <ul>
                <li><?php echo $html->link(__('Edit Media Rating', true), array('controller'=> 'media_ratings', 'action'=>'edit', $film['MediaRating']['id'])); ?></li>
            </ul>
        </div>
    </div>
    <div class="related">
    <h3><?php __('Related Film Comments');?></h3>
    <?php if (!empty($film['FilmComment'])):?>
    <table cellpadding = "0" cellspacing = "0">
    <tr>
        <th><?php __('Id'); ?></th>
        <th><?php __('Film Id'); ?></th>
        <th><?php __('User Id'); ?></th>
        <th><?php __('Username'); ?></th>
        <th><?php __('Email'); ?></th>
        <th><?php __('Text'); ?></th>
        <th><?php __('Hidden'); ?></th>
        <th><?php __('Created'); ?></th>
        <th><?php __('Modified'); ?></th>
        <th class="actions"><?php __('Actions');?></th>
    </tr>
    <?php
        $i = 0;
        foreach ($film['FilmComment'] as $filmComment):
            $class = null;
            if ($i++ % 2 == 0) {
                $class = ' class="altrow"';
            }
        ?>
        <tr<?php echo $class;?>>
            <td><?php echo $filmComment['id'];?></td>
            <td><?php echo $filmComment['film_id'];?></td>
            <td><?php echo $filmComment['user_id'];?></td>
            <td><?php echo $filmComment['username'];?></td>
            <td><?php echo $filmComment['email'];?></td>
            <td><?php echo $filmComment['text'];?></td>
            <td><?php echo $filmComment['hidden'];?></td>
            <td><?php echo $filmComment['created'];?></td>
            <td><?php echo $filmComment['modified'];?></td>
            <td class="actions">
                <?php echo $html->link(__('View', true), array('controller'=> 'film_comments', 'action'=>'view', $filmComment['id'])); ?>
                <?php echo $html->link(__('Edit', true), array('controller'=> 'film_comments', 'action'=>'edit', $filmComment['id'])); ?>
                <?php echo $html->link(__('Delete', true), array('controller'=> 'film_comments', 'action'=>'delete', $filmComment['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $filmComment['id'])); ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </table>
<?php endif; ?>

    <div class="actions">
        <ul>
            <li><?php echo $html->link(__('New Film Comment', true), array('controller'=> 'film_comments', 'action'=>'add'));?> </li>
        </ul>
    </div>
</div>
<div class="related">
    <h3><?php __('Related Film Pictures');?></h3>
    <?php if (!empty($film['FilmPicture'])):?>
    <table cellpadding = "0" cellspacing = "0">
    <tr>
        <th><?php __('Id'); ?></th>
        <th><?php __('File Name'); ?></th>
        <th><?php __('Film Id'); ?></th>
        <th><?php __('Type'); ?></th>
        <th class="actions"><?php __('Actions');?></th>
    </tr>
    <?php
        $i = 0;
        foreach ($film['FilmPicture'] as $filmPicture):
            $class = null;
            if ($i++ % 2 == 0) {
                $class = ' class="altrow"';
            }
        ?>
        <tr<?php echo $class;?>>
            <td><?php echo $filmPicture['id'];?></td>
            <td><?php echo $filmPicture['file_name'];?></td>
            <td><?php echo $filmPicture['film_id'];?></td>
            <td><?php echo $filmPicture['type'];?></td>
            <td class="actions">
                <?php echo $html->link(__('View', true), array('controller'=> 'film_pictures', 'action'=>'view', $filmPicture['id'])); ?>
                <?php echo $html->link(__('Edit', true), array('controller'=> 'film_pictures', 'action'=>'edit', $filmPicture['id'])); ?>
                <?php echo $html->link(__('Delete', true), array('controller'=> 'film_pictures', 'action'=>'delete', $filmPicture['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $filmPicture['id'])); ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </table>
<?php endif; ?>

    <div class="actions">
        <ul>
            <li><?php echo $html->link(__('New Film Picture', true), array('controller'=> 'film_pictures', 'action'=>'add'));?> </li>
        </ul>
    </div>
</div>
<div class="related">
    <h3><?php __('Related Film Variants');?></h3>
    <?php if (!empty($film['FilmVariant'])):?>
    <table cellpadding = "0" cellspacing = "0">
    <tr>
        <th><?php __('Id'); ?></th>
        <th><?php __('Film Id'); ?></th>
        <th><?php __('Video Type Id'); ?></th>
        <th><?php __('Resolution'); ?></th>
        <th><?php __('Duration'); ?></th>
        <th><?php __('Active'); ?></th>
        <th><?php __('Created'); ?></th>
        <th><?php __('Modified'); ?></th>
        <th class="actions"><?php __('Actions');?></th>
    </tr>
    <?php
        $i = 0;
        foreach ($film['FilmVariant'] as $filmVariant):
            $class = null;
            if ($i++ % 2 == 0) {
                $class = ' class="altrow"';
            }
        ?>
        <tr<?php echo $class;?>>
            <td><?php echo $filmVariant['id'];?></td>
            <td><?php echo $filmVariant['film_id'];?></td>
            <td><?php echo $filmVariant['video_type_id'];?></td>
            <td><?php echo $filmVariant['resolution'];?></td>
            <td><?php echo $filmVariant['duration'];?></td>
            <td><?php echo $filmVariant['active'];?></td>
            <td><?php echo $filmVariant['created'];?></td>
            <td><?php echo $filmVariant['modified'];?></td>
            <td class="actions">
                <?php echo $html->link(__('View', true), array('controller'=> 'film_variants', 'action'=>'view', $filmVariant['id'])); ?>
                <?php echo $html->link(__('Edit', true), array('controller'=> 'film_variants', 'action'=>'edit', $filmVariant['id'])); ?>
                <?php echo $html->link(__('Delete', true), array('controller'=> 'film_variants', 'action'=>'delete', $filmVariant['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $filmVariant['id'])); ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </table>
<?php endif; ?>

    <div class="actions">
        <ul>
            <li><?php echo $html->link(__('New Film Variant', true), array('controller'=> 'film_variants', 'action'=>'add/' . $film["Film"]["id"]));?> </li>
        </ul>
    </div>
</div>
<div class="related">
    <h3><?php __('Related Source Links');?></h3>
    <?php if (!empty($film['SourceLink'])):?>
    <table cellpadding = "0" cellspacing = "0">
    <tr>
        <th><?php __('Id'); ?></th>
        <th><?php __('Film Id'); ?></th>
        <th><?php __('Source Id'); ?></th>
        <th><?php __('Title'); ?></th>
        <th><?php __('Link'); ?></th>
        <th class="actions"><?php __('Actions');?></th>
    </tr>
    <?php
        $i = 0;
        foreach ($film['SourceLink'] as $sourceLink):
            $class = null;
            if ($i++ % 2 == 0) {
                $class = ' class="altrow"';
            }
        ?>
        <tr<?php echo $class;?>>
            <td><?php echo $sourceLink['id'];?></td>
            <td><?php echo $sourceLink['film_id'];?></td>
            <td><?php echo $sourceLink['source_id'];?></td>
            <td><?php echo $sourceLink['title'];?></td>
            <td><?php echo $sourceLink['link'];?></td>
            <td class="actions">
                <?php echo $html->link(__('View', true), array('controller'=> 'source_links', 'action'=>'view', $sourceLink['id'])); ?>
                <?php echo $html->link(__('Edit', true), array('controller'=> 'source_links', 'action'=>'edit', $sourceLink['id'])); ?>
                <?php echo $html->link(__('Delete', true), array('controller'=> 'source_links', 'action'=>'delete', $sourceLink['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $sourceLink['id'])); ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </table>
<?php endif; ?>

    <div class="actions">
        <ul>
            <li><?php echo $html->link(__('New Source Link', true), array('controller'=> 'source_links', 'action'=>'add'));?> </li>
        </ul>
    </div>
</div>
<div class="related">
    <h3><?php __('Related Countries');?></h3>
    <?php if (!empty($film['Country'])):?>
    <table cellpadding = "0" cellspacing = "0">
    <tr>
        <th><?php __('Id'); ?></th>
        <th><?php __('Title'); ?></th>
        <th><?php __('Title Imdb'); ?></th>
        <th><?php __('Created'); ?></th>
        <th><?php __('Modified'); ?></th>
        <th class="actions"><?php __('Actions');?></th>
    </tr>
    <?php
        $i = 0;
        foreach ($film['Country'] as $country):
            $class = null;
            if ($i++ % 2 == 0) {
                $class = ' class="altrow"';
            }
        ?>
        <tr<?php echo $class;?>>
            <td><?php echo $country['id'];?></td>
            <td><?php echo $country['title'];?></td>
            <td><?php echo $country['title_imdb'];?></td>
            <td><?php echo $country['created'];?></td>
            <td><?php echo $country['modified'];?></td>
            <td class="actions">
                <?php echo $html->link(__('View', true), array('controller'=> 'countries', 'action'=>'view', $country['id'])); ?>
                <?php echo $html->link(__('Edit', true), array('controller'=> 'countries', 'action'=>'edit', $country['id'])); ?>
                <?php echo $html->link(__('Delete', true), array('controller'=> 'countries', 'action'=>'delete', $country['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $country['id'])); ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </table>
<?php endif; ?>

    <div class="actions">
        <ul>
            <li><?php echo $html->link(__('New Country', true), array('controller'=> 'countries', 'action'=>'add'));?> </li>
        </ul>
    </div>
</div>
<div class="related">
    <h3><?php __('Related Emotions');?></h3>
    <?php if (!empty($film['Emotion'])):?>
    <table cellpadding = "0" cellspacing = "0">
    <tr>
        <th><?php __('Id'); ?></th>
        <th><?php __('Title'); ?></th>
        <th class="actions"><?php __('Actions');?></th>
    </tr>
    <?php
        $i = 0;
        foreach ($film['Emotion'] as $emotion):
            $class = null;
            if ($i++ % 2 == 0) {
                $class = ' class="altrow"';
            }
        ?>
        <tr<?php echo $class;?>>
            <td><?php echo $emotion['id'];?></td>
            <td><?php echo $emotion['title'];?></td>
            <td class="actions">
                <?php echo $html->link(__('View', true), array('controller'=> 'emotions', 'action'=>'view', $emotion['id'])); ?>
                <?php echo $html->link(__('Edit', true), array('controller'=> 'emotions', 'action'=>'edit', $emotion['id'])); ?>
                <?php echo $html->link(__('Delete', true), array('controller'=> 'emotions', 'action'=>'delete', $emotion['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $emotion['id'])); ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </table>
<?php endif; ?>

    <div class="actions">
        <ul>
            <li><?php echo $html->link(__('New Emotion', true), array('controller'=> 'emotions', 'action'=>'add'));?> </li>
        </ul>
    </div>
</div>
<div class="related">
    <h3><?php __('Related Genres');?></h3>
    <?php if (!empty($film['Genre'])):?>
    <table cellpadding = "0" cellspacing = "0">
    <tr>
        <th><?php __('Id'); ?></th>
        <th><?php __('Title'); ?></th>
        <th><?php __('Title Imdb'); ?></th>
        <th><?php __('Created'); ?></th>
        <th><?php __('Modified'); ?></th>
        <th class="actions"><?php __('Actions');?></th>
    </tr>
    <?php
        $i = 0;
        foreach ($film['Genre'] as $genre):
            $class = null;
            if ($i++ % 2 == 0) {
                $class = ' class="altrow"';
            }
        ?>
        <tr<?php echo $class;?>>
            <td><?php echo $genre['id'];?></td>
            <td><?php echo $genre['title'];?></td>
            <td><?php echo $genre['title_imdb'];?></td>
            <td><?php echo $genre['created'];?></td>
            <td><?php echo $genre['modified'];?></td>
            <td class="actions">
                <?php echo $html->link(__('View', true), array('controller'=> 'genres', 'action'=>'view', $genre['id'])); ?>
                <?php echo $html->link(__('Edit', true), array('controller'=> 'genres', 'action'=>'edit', $genre['id'])); ?>
                <?php echo $html->link(__('Delete', true), array('controller'=> 'genres', 'action'=>'delete', $genre['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $genre['id'])); ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </table>
<?php endif; ?>

    <div class="actions">
        <ul>
            <li><?php echo $html->link(__('New Genre', true), array('controller'=> 'genres', 'action'=>'add'));?> </li>
        </ul>
    </div>
</div>
<div class="related">
    <h3><?php __('Related People');?></h3>
    <?php if (!empty($film['Person'])):?>
    <table cellpadding = "0" cellspacing = "0">
    <tr>
        <th><?php __('Id'); ?></th>
        <th><?php __('Name'); ?></th>
        <th><?php __('Name En'); ?></th>
        <th><?php __('Description'); ?></th>
        <th><?php __('Birth Date'); ?></th>
        <th><?php __('Death Date'); ?></th>
        <th><?php __('Url'); ?></th>
        <th><?php __('Created'); ?></th>
        <th><?php __('Modified'); ?></th>
        <th class="actions"><?php __('Actions');?></th>
    </tr>
    <?php
        $i = 0;
        foreach ($film['Person'] as $person):
            $class = null;
            if ($i++ % 2 == 0) {
                $class = ' class="altrow"';
            }
        ?>
        <tr<?php echo $class;?>>
            <td><?php echo $person['id'];?></td>
            <td><?php echo $person['name'];?></td>
            <td><?php echo $person['name_en'];?></td>
            <td><?php echo $person['description'];?></td>
            <td><?php echo $person['birth_date'];?></td>
            <td><?php echo $person['death_date'];?></td>
            <td><?php echo $person['url'];?></td>
            <td><?php echo $person['created'];?></td>
            <td><?php echo $person['modified'];?></td>
            <td class="actions">
                <?php echo $html->link(__('View', true), array('controller'=> 'people', 'action'=>'view', $person['id'])); ?>
                <?php echo $html->link(__('Edit', true), array('controller'=> 'people', 'action'=>'edit', $person['id'])); ?>
                <?php echo $html->link(__('Delete', true), array('controller'=> 'people', 'action'=>'delete', $person['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $person['id'])); ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </table>
<?php endif; ?>

    <div class="actions">
        <ul>
            <li><?php echo $html->link(__('New Person', true), array('controller'=> 'people', 'action'=>'add'));?> </li>
        </ul>
    </div>
</div>
<div class="related">
    <h3><?php __('Related Publishers');?></h3>
    <?php if (!empty($film['Publisher'])):?>
    <table cellpadding = "0" cellspacing = "0">
    <tr>
        <th><?php __('Id'); ?></th>
        <th><?php __('Title'); ?></th>
        <th><?php __('Description'); ?></th>
        <th><?php __('Created'); ?></th>
        <th><?php __('Modified'); ?></th>
        <th class="actions"><?php __('Actions');?></th>
    </tr>
    <?php
        $i = 0;
        foreach ($film['Publisher'] as $publisher):
            $class = null;
            if ($i++ % 2 == 0) {
                $class = ' class="altrow"';
            }
        ?>
        <tr<?php echo $class;?>>
            <td><?php echo $publisher['id'];?></td>
            <td><?php echo $publisher['title'];?></td>
            <td><?php echo $publisher['description'];?></td>
            <td><?php echo $publisher['created'];?></td>
            <td><?php echo $publisher['modified'];?></td>
            <td class="actions">
                <?php echo $html->link(__('View', true), array('controller'=> 'publishers', 'action'=>'view', $publisher['id'])); ?>
                <?php echo $html->link(__('Edit', true), array('controller'=> 'publishers', 'action'=>'edit', $publisher['id'])); ?>
                <?php echo $html->link(__('Delete', true), array('controller'=> 'publishers', 'action'=>'delete', $publisher['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $publisher['id'])); ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </table>
<?php endif; ?>

    <div class="actions">
        <ul>
            <li><?php echo $html->link(__('New Publisher', true), array('controller'=> 'publishers', 'action'=>'add'));?> </li>
        </ul>
    </div>
</div>
<div class="related">
    <h3><?php __('Related Themes');?></h3>
    <?php if (!empty($film['Theme'])):?>
    <table cellpadding = "0" cellspacing = "0">
    <tr>
        <th><?php __('Id'); ?></th>
        <th><?php __('Title'); ?></th>
        <th class="actions"><?php __('Actions');?></th>
    </tr>
    <?php
        $i = 0;
        foreach ($film['Theme'] as $theme):
            $class = null;
            if ($i++ % 2 == 0) {
                $class = ' class="altrow"';
            }
        ?>
        <tr<?php echo $class;?>>
            <td><?php echo $theme['id'];?></td>
            <td><?php echo $theme['title'];?></td>
            <td class="actions">
                <?php echo $html->link(__('View', true), array('controller'=> 'themes', 'action'=>'view', $theme['id'])); ?>
                <?php echo $html->link(__('Edit', true), array('controller'=> 'themes', 'action'=>'edit', $theme['id'])); ?>
                <?php echo $html->link(__('Delete', true), array('controller'=> 'themes', 'action'=>'delete', $theme['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $theme['id'])); ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </table>
<?php endif; ?>

    <div class="actions">
        <ul>
            <li><?php echo $html->link(__('New Theme', true), array('controller'=> 'themes', 'action'=>'add'));?> </li>
        </ul>
    </div>
</div>
