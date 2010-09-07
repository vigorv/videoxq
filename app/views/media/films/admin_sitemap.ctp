<?php
echo'<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
	if ($films)
		foreach ($films as $f)
			echo'
	<url>
		<loc>http://videoxq.com/media/view/' . $f['Film']['id'] . '</loc>
		<lastmod>' . str_replace(' ', 'T', $f['Film']['modified']) . '+06:00</lastmod>
	</url>';
?>
</urlset>