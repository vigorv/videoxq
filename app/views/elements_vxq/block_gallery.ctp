<ul class="subMenu">
<?php if ($this->params['controller'] == 'gallery'
          && ($this->params['action'] == 'view' || $this->params['action'] == 'index') && $allowEdit): ?>
    <li class="photoalbumCreate"><a href="/gallery/add">Создать альбом</a></li>
    <?php endif;
    if (!empty($gallery['Gallery']['id']))
        $gUrl = '/' . $gallery['Gallery']['id'];
    elseif (!empty($galleryImage['Gallery']['id']))
        $gUrl = '/' . $galleryImage['Gallery']['id'];
    else
        $gUrl = '';
    ?>
    <?php if ($authUser['userid']):?>
    <li class="photoalbumUpload"><a href="/gallery/image/add<?php echo $gUrl?>">Загрузить</a></li>
    <?php endif;?>
    <?php
    if ($this->params['controller'] == 'gallery_images'
              && $this->params['action'] == 'view' && $allowEdit): ?>
    <li class="photoEdit"><a href="/gallery/image/edit/<?php echo $galleryImage['GalleryImage']['id']?>">Редактировать</a></li>
    <li class="photoDelete"><a href="/gallery/image/delete/<?php echo $galleryImage['GalleryImage']['id']?>">Удалить</a></li>
    <?php endif;?>
</ul>
