<div class="contentCol">
<?php
$olddate='';
$dlFlag=FALSE;
foreach ($events as $event):
$newdate=$time->format('d-m-Y',$event['Event']['created']);
$CurrTime=$time->format('H:i',$event['Event']['created']);
if($newdate!=$olddate){
	if($dlFlag)echo "</dl>";
	?>
	<h2 class="newsDate"><?php echo $newdate; ?></h2>
	<dl class="newsDay">
<?php 
}
$dlFlag=TRUE;
$olddate=$newdate;
?>
		<dt><?=$CurrTime?></dt>
		<dd>
		  <?php if($event['EventCategory']['id']>0) echo $html->link($event['EventCategory']['title'], '/news/index/cat:' . $event['EventCategory']['id']."/", array('class' => "newsCat")); ?>
			<?php echo $html->link($event['Event']['title'], '/news/view/' . $event['Event']['url'], array('class' => "newsTitle")); ?>
			<div class="newsBody">
				<?php //<img src="img/news.jpg" width="80" height="60" alt="" align="left">?>
				<?php if (!empty($event['Attachment']['dir']))
                    echo $html->image("/img/100x100/".$event['Attachment']['dir']."/".$event['Attachment']['filename'], array('align' => 'left'));
                ?>
                <?php
                
                 //$tmp = explode(Configure::read('App.textSplitter'), $event['Event']['text']);
                 echo(strip_tags($event['Event']['notice']));?>
			</div>
		</dd>
<?php endforeach; ?>
<div class="spacer"></div>

    <div class="pages">
      <?php echo $this->element('paging'); ?>
    </div>
</div>

