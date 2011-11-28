<div style="text-align: left">
<?php
//pr ($filmlist);
   foreach ($filmlist as $year => $film){
        echo $film['Film']['id'] . ' - ' . $film['Film']['title'] . '<br>';
}
?>
</div>
        <div class="pages">
        <?php echo $this->element('paging'); ?>
        </div>