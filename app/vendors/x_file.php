<?php
/**
 * XFile
 *
 * Extending CakePHP's File class with features like
 * prefix/postfix, mimetype/mediatype detection, rename
 * and a slightly reworked info method
 *
 * Copyright (c) 2007-2008 David Persson
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * PHP version 5
 * CakePHP version 1.2
 *
 * @author 		David Persson <davidpersson at qeweurope dot org>
 * @copyright 	David Persson <davidpersson at qeweurope dot org>
 * @package 	x_file
 * @version 	0.5
 * @license 	http://www.opensource.org/licenses/mit-license.php The MIT License
 */

App::import('Core','File');
App::import('Vendor','Mimetype');

Mimetype::registerHandler('text/plain','XFilePlaintext');

if(extension_loaded('gd')) {
//	App::import('vendor','XFileImage',null,null,'XFile/ImageGd.php');
    Mimetype::registerHandler('image/gif','XFileImage');
    Mimetype::registerHandler('image/jpeg','XFileImage');
    Mimetype::registerHandler('image/vnd.wap.wbmp','XFileImage');
    Mimetype::registerHandler('image/png','XFileImage');
} else {
    trigger_error('No compatible extension for image manipulation is loaded. ',E_USER_ERROR);
}

Mimetype::registerHandler('image/jpeg','ExifExtractor');
Mimetype::registerHandler('image/tiff','ExifExtractor');


class XFile extends File
{
    /**
     * Enter description here...
     *
     * @var unknown_type
     */
    private $errors = array();

    public $exif = null;

    /**
     * Construct the parent
     */
    function __construct($path, $create = false, $mode = 0755)
    {
        parent::__construct($path,$create,$mode);

        $handlers = Mimetype::handlers($this->mimetype());

        // Exif
        if(in_array('ExifExtractor',$handlers) && $this->exists()
        && App::import('Vendor','ExifExtractor')) {
            $this->exif = ExifExtractor::data($this->pwd());
        }

    }

    /**
     * Enter description here...
     *
     * @param unknown_type $path
     * @param unknown_type $create
     * @param unknown_type $mode
     * @return unknown
     */
    static public function &factory($path, $create = false, $mode = 0755)
    {
        // FIXME BUG detects directories as text/plain?
        $handlers = Mimetype::handlers(Mimetype::detectFast($path));

        if(in_array('XFileImage',$handlers))  {
            if(extension_loaded('gd')) {
                App::import('Vendor','XFile/ImageGd');
            }
            $obj = new XFileImage($path,$create,$mode);

        } elseif(in_array('XFilePlaintext',$handlers))  {
            App::import('Vendor','XFile/Plaintext');
            $obj = new XFilePlaintext($path,$create,$mode);

        } else {
            $obj = new XFile($path,$create,$mode);

        }

        return $obj;
    }

    /**
     * Create a adler32 hash of a file
     *
     * Adler-32 is a checksum algorithm which was invented by Mark Adler.
     * Compared to a cyclic redundancy check of the same length it trades reliability for speed.
     *
     * @link http://en.wikipedia.org/wiki/Adler32
     */
    function checksum($algo = 'md5')
    {
        if(!$this->exists()) {
            return false;
        }

        $algo = strtolower($algo);

        if(!in_array($algo,hash_algos())) {
            trigger_error($algo.' not supported by hash extension ',E_USER_ERROR);
            return false;
        }


        return hash_file($algo,$this->pwd());

    }

    /**
     * Enter description here...
     *
     * @return unknown
     */
    public function mimetype($fast = true)
    {
        if(!$this->exists()) {
            return false;
        }

        if($fast) {
            return Mimetype::detectFast($this->pwd());
        }

        return Mimetype::detect($this->pwd());
    }

    /**
     * Enter description here...
     *
     * @return unknown
     */
    public function mediatype()
    {
        if(!$this->exists()) {
            return false;
        }

        return strtok($this->mimetype(),'/');
    }

    /**
     * Postfix the filename
     */
    public function postfix($postfix = null)
    {
        return $this->rename($this->name().$postfix.'.'.$this->ext());
    }

    /**
     * Prefix the filename
     */
    public function prefix($prefix = null)
    {
        return $this->rename($prefix.$this->name().'.'.$this->ext());
    }

    /**
     * Dump the complete current contents
     *
     * @return string dump
     */
    public function dump()
    {
        if(!$this->exists()) {
            return false;
        }

        ob_start();
        readfile($this->pwd());

        return ob_get_clean();
    }

    /**
     * Rename the file complete name with extension
     */
    public function rename($name)
    {
        $this->name = $name;
        $this->info = null;

        return true;
    }

    /**
     * Get Information about the file
     * Specify
     * XFILE_INFO_BASIC
     * .........._EXTENDED
     * .........._SUMMARY
     * .........._COMPLETE
     * to get different kind of info amount
     */
    public function info($level = 'basic')
    {
        $result = array();

        switch ($level) {
            case 'complete':
            case 'summary':
                if(isset($this->exif)) {
                    $result = array_merge($result,$this->exif);
                }
            case 'extended':
            case 'basic':
                $result = array('size' => $this->size(),'mimetype' => $this->mimetype(),'mediatype' => $this->mediatype());
                $result = array_merge($result,parent::info());
                break;
        }

        return $result;
    }
}
?>