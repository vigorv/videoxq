<?php
if (!empty($add))
	echo '<p>Parsing Geoip dump... ' . $add . ' lines added</p>';

echo '<button onclick="location.href=\'/admin/utils/geo/import\'">Import GeoIpDump</button>';
