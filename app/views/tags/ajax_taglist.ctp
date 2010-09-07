<?php
foreach ($tags as $tag)
{
    extract($tag['Tag']);
	echo "$title|$id\n";
}

?>