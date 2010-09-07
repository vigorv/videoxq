<?php
	if (($allowDownload) && (isset($filmFile)))
	{
    	$file = $filmFile['FilmFile'];
    	$lnk = Film::set_input_share($filmFile['FilmVariant']['Film']['dir']).'/' . $file['file_name'];
    	$resolution = preg_split('/[\D]{1,}/', trim($filmFile['FilmVariant']['resolution']));
    	if ((count($resolution) > 1) && !empty($lnk))
	echo'

<object classid="clsid:67DABFBF-D0AB-41fa-9C46-CC0F21721616" width="' . $resolution[0] . '"
height="' . $resolution[1] . '" codebase="http://go.divx.com/plugin/DivXBrowserPlugin.cab">
                <param name="wmode" value="opaque" />
                <param name="autoPlay" value="true" />
  <param name="autoPlay" value="true" />
  <param id="srcparamid" name="src" value="' . $lnk . '" />
                <param name="previewImage" value="" />
<embed id="srcembedid" type="video/divx" src="' . $lnk . '"
				width="' . $resolution[0] . '" height="' . $resolution[1] . '"
				wmode="opaque"
				autoPlay="true" previewImage="" pluginspage="http://go.divx.com/plugin/download/">
</embed>
                </object>

	';
	}