<div style="text-align: left">
<?php

   foreach ($data as $row){
        echo $row['Copyrightholder']['name'].'<br>';
}
?>
</div>
        <div class="pages">
        <?php echo $this->element('paging'); ?>
        </div>