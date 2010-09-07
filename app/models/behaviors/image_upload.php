<?php
require_once ('upload.php');
class ImageUploadBehavior extends UploadBehavior
{

    var $imagick;

    function setup(&$model, $config = array())
    {
        // Overriding defaults
        $this->__defaultSettings['allowedMime'] = array('image/jpeg', 'image/gif', 'image/png', 'image/bmp');
        $this->__defaultSettings['allowedExt'] = array('jpeg', 'jpg', 'gif', 'png', 'bmp');
        $this->imagick = new Imagick();
        parent::setup($model, $config);
    }

    function _afterProcessUpload(&$model, $data, $direct)
    {
        $this->imagick->readImage($model->absolutePath());
        //list($width, $height) = getimagesize($model->absolutePath());
        $width = $this->imagick->getImageWidth();
        $height = $this->imagick->getImageHeight();
        $model->data[$model->name]['width'] = $width;
        $model->data[$model->name]['height'] = $height;
        $this->imagick->destroy();
        return true;
    }

    function _beforeProcessUpload(&$model, $data, $direct)
    {
        return true;
    }

    function resize(&$model, $id = null, $width = 600, $height = 400, $writeTo = false, $aspect = true)
    {
        if ($id === null && $model->id)
        {
            $id = $model->id;
        }
        elseif (! $id)
        {
            $id = null;
        }
        extract($this->settings[$model->name]);
        $readResult = $model->read(array($fileField, $dirField), $id);
        extract($readResult[$model->name]);
        $fullPath = $baseDir . $$dirField . DS . $$fileField;
        return $this->resizeFile($model, $fullPath, $width, $height, $writeTo, $aspect);
    }

    function resizeFile(&$model, $fullpath, $width = 600, $height = 400, $writeTo = false, $aspect = true)
    {
        if (! $width || ! $height)
        {
            return false;
        }
        extract($this->settings[$model->name]);
        if (!$this->imagick->readImage($fullpath))
        {
            return false; // image doesn't exist
        }

        $currentWidth = $this->imagick->getImageWidth();
        $currentHeight = $this->imagick->getImageHeight();


        // adjust to aspect.
//        if ($aspect)
//        {
//            if (($currentHeight / $height) > ($currentWidth / $width))
//            {
//                $width = ceil(($currentWidth / $currentHeight) * $height);
//            }
//            else
//            {
//                $height = ceil($width / ($currentWidth / $currentHeight));
//            }
//        }



        //$types = array(1 => "gif", "jpeg", "png", "swf", "psd", "wbmp");
        //$image = call_user_func('imagecreatefrom'.$types[$currentType], $fullpath);


        //        if (function_exists("imagecreatetruecolor") && ($temp = imagecreatetruecolor ($width, $height))) {
        //            imagecopyresampled ($temp, $image, 0, 0, 0, 0, $width, $height, $currentWidth, $currentHeight);
        //          } else {
        //            $temp = imagecreate ($width, $height);
        //            imagecopyresized ($temp, $image, 0, 0, 0, 0, $width, $height, $currentWidth, $currentHeight);
        //        }


        if ($currentHeight <= $height && $currentWidth <= $width)
        {
            $height = $currentHeight;
            $width = $currentWidth;
        }

        //white canvas
//        $op = new Imagick();
//        $op->newImage($width, $height, new ImagickPixel("white"));
        //make a thumb, with reduced size compared to canvas
//        $this->imagick->thumbnailImage($width-10, $height-10, true);
        $this->imagick->thumbnailImage($width, $height, true);

        //sharpen if small thumb
        if ($width < 300)
            $this->imagick->sharpenImage(4, 1);

        //round corners, web 2.0 :))
//        $this->imagick->roundCorners(5, 5);
//        //clone image to create shadow
//        $shadow = $this->imagick->clone();
//
//        //shadow color
//        $shadow->setImageBackgroundColor(new ImagickPixel('black'));
//        //shadow is made here
//        $shadow->shadowImage(80, 2.5, 5, 5);
//
//        //place shadow on the canvas
//        $op->compositeImage($shadow, $shadow->getImageCompose(), 0,0);
//        //place image on the canvas
//        $op->compositeImage($this->imagick, $this->imagick->getImageCompose(), 0,0);
//
//        $this->imagick = $op->clone();
//
//        $op->destroy();
//        $shadow->destroy();
        //$this->imagick->setImageBackgroundColor( new ImagickPixel( "white" ) );

//
//        $this->imagick->setImageBackgroundColor( new ImagickPixel( "rgb(213,213,213)" ) );
//        $this->imagick->trimImage(0);

//        $this->imagick->setImageFormat('jpeg');
//        $this->imagick->setimagecompression(Imagick::COMPRESSION_JPEG);
//        $this->imagick->setimagecompressionquality(95);

        //die();
        $this->imagick->stripImage();

        uses('File');
        new File($writeTo, true);

        $this->imagick->writeImage($writeTo);

        return file_get_contents($writeTo);
    }
}
?>
