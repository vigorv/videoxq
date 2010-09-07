<?php
function transformslashes($str)
{
	return str_replace('/', chr(92), $str);
}

$dir = Film::set_input_share($film['Film']['dir']);

switch ($player)
{
	case "alloy":
		$content = transformslashes($dir . '/' . $file['FilmFile']["file_name"]) . "\r\n>N " . $file['FilmFile']["file_name"] . "\r\n\r\n\n";
	break;

	case "mpc":
		$content = "MPCPLAYLIST\r\n1,type,0\r\n1,filename," . transformslashes($dir . '/' . $file['FilmFile']["file_name"]) . "\r\n\n";
	break;

	case "bs":
		$content = transformslashes($dir . '/' . $file['FilmFile']["file_name"]) . "\r\n\n";
	break;

	case "crystal":
		$content = transformslashes($dir . '/' . $file['FilmFile']["file_name"]) . "\r\n\n";
	break;

	case "winamp":
		$content = "[playlist]\nnumberofentries=1\nFile1=" . transformslashes($dir . '/' . $file['FilmFile']["file_name"]) . "\n";
	break;
	default:
		$player = $players["wmp"]["name"];
		$content = '<Asx Version = "3.0" >' . "\r\n";
		$content .= '<Param Name = "Name" />' . "\r\n\r\n";
		$content .= '<Entry>' . "\r\n";
		$content .= '<Title>' . $file['FilmFile']["file_name"] . '</Title>' . "\r\n";
		$content .= '<Ref href = "' . transformslashes($dir . '/' . $file['FilmFile']["file_name"]) . '"/>' . "\r\n";
		$content .= '</Entry>' . "\r\n";
		$content .= '</Asx>' . "\n";

}
header("Content-Length: " . strlen($content));
header("Content-Disposition: attachment; filename=playlist." . $players[$player]['ext']);
header("Content-Type: download/file");
echo $content;