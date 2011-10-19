<? if (isset($page_count)):
    $link = Router::url($this->here, true); ?>
    <? if ($page > 1) { ?>
        <a onclick="return xLoad(this);" href="<?= $link; ?>?page=<?= ($page - 1) ?>&per_page=<?= $per_page; ?>">Back</a>
    <? }
    if ($page < $page_count) { ?>
        <a  onclick="return xLoad(this);"  href="<?= $link; ?>?page=<?= ($page + 1) ?>&per_page=<?= $per_page; ?>">Next</a>       
    <? } ?> 
<? endif; ?>
        