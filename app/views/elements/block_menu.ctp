<ul>
    <li><?php echo $html->link(__('Bookmarks', true), '/bookmarks'); ?></li>
    <li><img id="bookmarkLinkLoder" src="/img/loading.gif" style="display: none; position: absolute;" />
    <?php echo $html->link('В закладки', $html->url('/bookmarks/add'),
                           array('onclick' => 'showBookmarkForm(this); return false;',
                                 'id' => 'addBookmarkLink')); ?>
    <div id="bookmarkPlaceHolder" class="box" style="display: none; position: absolute; height: 100px; width: 200px"></div>
    </li>

</ul>
