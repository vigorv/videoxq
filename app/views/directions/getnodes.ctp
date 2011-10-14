<?php

$data = array();

foreach ($nodes as $node){
	$data[] = array(
		"text" => $node['Direction']['name'],
		"id" => $node['Direction']['id'],
		"cls" => "folder",
		"leaf" => ($node['Direction']['lft'] + 1 == $node['Direction']['rght'])
	);
}

echo $javascript->object($data);

?>