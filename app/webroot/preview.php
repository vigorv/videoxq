<?php
//СОЗДАЕМ ПРЕВЬЮ ЗАГРУЖЕННОЙ КАРТИНКИ
DEFINE("_IMAGE_QUALITY_", 65);
DEFINE("_IMAGE_WIDTH_", 800);//МАКСИМАЛЬНАЯ ШИРИНА ЗАГРУЖАЕМОГО ИЗОБРАЖЕНИЯ
DEFINE("_PREVIEW_WIDTH_", 300);//МАКСИМАЛЬНАЯ ШИРИНА PREVIEW

/**
 * пропорционально изменить масшаб изображения
 *
 * @param string $fromName имя файла оригинального изображения
 * @param string $toName имя файла нового изображения
 * @param integer $imgWidth жилаемая ширина нового изображения
 * @param integer $imgHeight жилаемая высота нового изображения
 * @return string новое имя файла
 */
function createResizeImage($fromName, $imgWidth, $imgHeight)
{
	$info = pathinfo($fromName);
	switch (strtolower($info['extension']))
	{
		case "jpg":
		case "jpe":
		case "jpeg":
			$im=imagecreatefromjpeg($fromName);
		break;
		case "gif":
			$im=imagecreatefromgif($fromName);
		break;
		case "png":
			$im=imagecreatefrompng($fromName);
		break;
		default:
			unlink($fromName);
			return false;
	}
	if (empty($im)) return false;

	$w = imagesx($im);
	$h = imagesy($im);

	$kw = $w / $imgWidth;
	$kh = $h / $imgHeight;
	$k = 0;

	if (($kw > 1) && ($kw >= $kh))
	{
		$w1 = $imgWidth;
		$k = $w / $w1;
		$h1 = round($h / $k);
	}

	if (($kh > 1) && ($kh >= $kw))
	{
		$h1 = $imgHeight;
		$k = $h / $h1;
		$w1 = round($w / $k);
	}

	if ($k == 0)//изображение не масштабировалось
	{
		return false;
	}
	$im1 = imagecreatetruecolor ($w1, $h1);
	$white = imagecolorallocate ($im1, 0, 0, 0);
	if (($w > _IMAGE_WIDTH_) || ($h > _IMAGE_WIDTH_))
		imagecopyresized ($im, $im, 0, 0, 0, 0, $w1, $h1, $w, $h);
	else
		imagecopyresampled ($im, $im, 0, 0, 0, 0, $w1, $h1, $w, $h);

	imagecopy($im1, $im, 0, 0, 0, 0, $w1, $h1);
	$newName = $info['dirname'] . '/' . ereg_replace($info['extension'] . '$', 'jpg', $info['basename']);
	imagejpeg($im1, $newName, _IMAGE_QUALITY_);

	return $newName;
}

function unlinkTempFiles($dir, $userid)
{
	if (is_dir($dir)) {
	    if ($dh = opendir($dir)) {
	        while (($file = readdir($dh)) !== false) {
	            if (ereg('^temp_' . $userid, $file))
	            {
	            	unlink($dir . '/' . $file);
	            }
	        }
	        closedir($dh);
	    }
	}
}

if (!empty($_POST['filename']))
{
	$fromName = $_POST['filename'];
	$info = pathinfo($fromName);

	$dir = $info['dirname'];
	unlinkTempFiles($dir, $authUser['userid']);
	unlinkTempFiles($dir . '/small', $authUser['userid']);

	$newName = $info['dirname'] . '/temp_' . $_POST['userid'] . '_' . time() . '.' . $info['extension'];//СОЗДАЕМ ВРЕМЕННЫЙ ФАЙЛ
	if (file_exists($fromName))
	{
		rename($fromName, $newName);
		$result = createResizeImage($newName, _IMAGE_WIDTH_, _IMAGE_WIDTH_); //ПРИВОДИМ К НОРМАЛЬНОМУ РАЗМЕРУ
		if ($result)
		{
			unlink($newName);
			$newName = $result;
		}

		if (file_exists($newName))
		{
			$previewName = $info['dirname'] . '/small/' . basename($newName);
			copy($newName, $previewName);
			$result = createResizeImage($previewName, _PREVIEW_WIDTH_, _PREVIEW_WIDTH_); //СОЗДАЕМ ПРЕВЬЮ
			if ($result)
			{
				$newName = $result;
			}
			echo basename($newName);//ВЫВОДИМ (ОТВЕТ ДЛЯ АЯКС ВЫЗОВА)
		}
	}
}
