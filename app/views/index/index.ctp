<?php
	//echo '<script type="text/javascript" charset="windows-1251" src="http://flux.itd/adv.php"></script>';
?>
<style>
#nsk54_clip
{
	overflow:hidden;
	width:330px;
}
#wsmedia_clip
{
	overflow:hidden;
	width:330px;
}
#animebar_clip
{
	overflow:hidden;
	width:330px;
}
</style>
<table width="100%">
<tr>
	<td>
		<table cellspacing="1" border="0">
		<tr>
			<td>
				<a href="#" onclick="return nsk54.scrollRight(this);"><===</a>
			</td>
			<td width="100%" >
				<div id="nsk54_clip">
					<div id="nsk54_info">
					</div>
					<table id="nsk54_table" cellspacing="6">
					<tr id="nsk54_list">
					</tr>
					</table>
				</div>
			</td>
			<td>
				<a href="#" onclick="return nsk54.scrollLeft(this);">===></a>
			</td>
		</tr>
		</table>
	</td>
	<td width="33%">
		<table cellspacing="1" border="0">
		<tr>
			<td>
				<a href="#" onclick="return wsmedia.scrollRight(this);"><===</a>
			</td>
			<td width="100%" >
				<div id="wsmedia_clip">
					<div id="wsmedia_info">
					</div>
					<table id="wsmedia_table" cellspacing="6">
					<tr id="wsmedia_list">
					</tr>
					</table>
				</div>
			</td>
			<td>
				<a href="#" onclick="return wsmedia.scrollLeft(this);">===></a>
			</td>
		</tr>
		</table>
	</td>
	<td width="33%">
		<table cellspacing="1" border="0">
		<tr>
			<td>
				<a href="#" onclick="return animebar.scrollRight(this);"><===</a>
			</td>
			<td width="100%" >
				<div id="animebar_clip">
					<div id="animebar_info">
					</div>
					<table id="animebar_table" cellspacing="6">
					<tr id="animebar_list">
					</tr>
					</table>
				</div>
			</td>
			<td>
				<a href="#" onclick="return animebar.scrollLeft(this);">===></a>
			</td>
		</tr>
		</table>
	</td>
</tr>
</table>

<script type="text/javascript">

<?php
//данные о блоке каталога
?>
	//with(mediaBlock = new Function) {
	function mediaBlock() {
		this.info 				= new Array();
		this.info["img_cnt"]	= 0;
		this.info["scroll_x"]	= 0;
		this.info["cur_id"]	= 0;
		this.info["img_width"]	= 0;
		this.info["img_space"]	= 0;
		this.prefix 		= '';
		this.url 		= '';
	}
<?php
//шаг прокрутки
?>
	mediaBlock.prototype.scrollStep = function() {
		return this.info['img_width'] + this.info['img_space'] * 2;
	}

<?php
//загрузить постер фильма id в контейнер c
?>
	mediaBlock.prototype.loadImage = function (id, c) {
		if (c == null)
		{
			t = document.getElementById(this.prefix + "list");
			this.info["img_cnt"] = t.cells.length;
			c = t.insertCell(this.info["img_cnt"]);
			c.id = this.prefix + "tdposter" + this.info["img_cnt"];
			$(c).fadeTo("slow", 0.05);
			this.info["img_cnt"]++;
		}
		else
		{
			c = document.getElementById(c);
			$(c).fadeTo("slow", 0.05);
		}

		$(c).load(this.url + id, {}, function(){
			$(this).fadeTo("slow", 1);
		});
		return;
	}

<?php
//загрузить подробную информацию о фильме
?>
	mediaBlock.prototype.loadInfo = function(id) {
		c = $("#" + this.prefix + "info");
		$("#" + this.prefix + "info").fadeTo("slow", 0.05);
		this.info["cur_id"] = id;
		$(c).load(this.url + id + "/all", {}, function(){
			$(this).fadeTo("slow", 1);
		});

	}

	mediaBlock.prototype.scrollLeft = function(a) {
		if ((this.info["img_cnt"] - 1) * this.scrollStep() + this.info["scroll_x"] <= 0)
			return false;

		if ((this.info["img_cnt"] - 2) * this.scrollStep() + this.info["scroll_x"] <= parseInt($("#" + this.prefix + "clip").css('width')))
			this.loadImage(0);
		this.info["scroll_x"] = this.info["scroll_x"] - this.scrollStep();
		$("#" + this.prefix + "table").animate({marginLeft: this.info["scroll_x"]}, 300);

		return false;
	}

	mediaBlock.prototype.scrollRight = function(a) {
		if (this.info["scroll_x"] >= 0)
		{
			this.info["scroll_x"] = 0;
			return false;
		}
		this.info["scroll_x"] = this.info["scroll_x"] + this.scrollStep();
		$("#" + this.prefix + "table").animate({marginLeft: this.info["scroll_x"]}, 300);

		return false;
	}

	mediaBlock.prototype.openPoster = function(a) {
		ids = a.id.split('_');
		id = ids[1];
		a = document.getElementById(a.id);
		t = $(a).parent().get(0);
		this.loadImage(this.info["cur_id"], t.id);
		this.loadInfo(id);
		return false;
	}

	nsk54 = new mediaBlock();
	with (nsk54)
	{
		info['img_width']	= 80;
		info['img_space']	= 5;
		prefix = 'nsk54_';
		url = "/index/filminfo/nsk54/";

		loadInfo(<?php echo $nsk54MaxId; ?>);
		for(i = 0; i < 5; i++)
			loadImage(0, null);
	}

	wsmedia = new mediaBlock();
	with (wsmedia)
	{
		info['img_width']	= 80;
		info['img_space']	= 5;
		prefix = 'wsmedia_';
		url = "/index/filminfo/wsmedia/";

		loadInfo(<?php echo $wsmediaMaxId; ?>);
		for(i = 0; i < 5; i++)
			loadImage(0, null);
	}

	animebar = new mediaBlock();
	with (animebar)
	{
		info['img_width']	= 80;
		info['img_space']	= 5;
		prefix = 'animebar_';
		url = "/index/filminfo/animebar/";

		loadInfo(<?php echo $animebarMaxId; ?>);
		for(i = 0; i < 5; i++)
			loadImage(0, null);
	}

</script>

<div class="contentCol">
<h2>Последние комментарии в <a href=/blogs/>блогах</a> :</h2>
<?php
foreach ($posts as $post):
unset($post['Post']['text']);
extract($post);
?>
        <h2>
<span><a href="<?= $app->getUserProfileUrl($User['userid']) ?>" class="user" <!--style="background-image:url(img/avatars/k/keitaro/20.jpg)"-->><?= h($User['username']) ?></a>
&nbsp;&nbsp;&nbsp;&#8594;&nbsp;&nbsp;&nbsp;
</span>
        <a href="/posts/view/<?= $Post['id'] ?>"><?= h($Post['title'] ? $Post['title'] : '(без названия)') ?></a>(<?=$post[0]['commentCount']?>)
        </h2>
<?
endforeach;
?>
<h2>Последние картинки в <a href=/gallery/>Галерее</a> :</h2>
	<div class="indexThumbs">
<?foreach ($galleryImages as $galleryImage):
    ?>
    <div class="photoAlbumItem">
    <table><tr><td><span>
    <a href="/gallery/image/<?php echo $galleryImage['GalleryImage']['id']; ?>"><img src="/img/150x150/<?php echo $galleryImage['Attachment']['dir'] . '/' . $galleryImage['Attachment']['filename']; ?>" alt="<?php echo $galleryImage['GalleryImage']['title']; ?>"></a></span></td></tr></table>
    </div>
<?php endforeach;?>
		<div class="spacer"></div>
	</div>
</div>
