<?php
/**
 * Transfer
 *
 * A class handling transfers of one file to another location
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
 * CakePHP version 1.2.x
 *
 * Currently supported transfers:
 * 		html-form-file>>uploaded-local>>local
 * 		local>>>>local
 * 		url>>>>local
 *	 	url>>local>>local
 * 		local>>local>>local
 *
 * Not yet supported transfers:
 * 		html-form-file>>uploaded-local>>url
 * 		local>>>>url
 *
 * Url schemes supported are currently File and Http only
 *
 * @author 		David Persson <davidpersson at qeweurope dot org>
 * @copyright 	David Persson <davidpersson at qeweurope dot org>
 * @package 	transfer
 * @version 	0.4
 * @license		http://www.opensource.org/licenses/mit-license.php The MIT License
 */
class Transfer
{
    public $config = array(
                    'src' => array('type' => null),
                    'tmp' => array('type' => null),
                    'dst' => array('type' => null),

                    /*
                     * Rules Order Deny/Allow
                     *
                     * `true` matches all
                     * `false` matches nothing
                     */
                    'allowMimetype' => true,
                    'denyMimetype' => false,

                    /* specify extensions without leading dot */
                    'allowExtension' => true,
                    'denyExtension' => array('php','cgi','asp','exe','sh','pl','py','com','rb','js'),

                    /*
                     * Allowed Paths
                     *
                     * with trailing slash
                     */
                    'allowPaths' => array('/tmp/'),

                    /*
                     * Max Size
                     *
                     * null sets no limit
                     * use php.ini notation.
                     * e.g. 8MB
                     */
                    'maxSize' => null,

                    'renamingTries' => 30,

                    /**
                     * Overwrite existing destination files
                     */
                    'overwriteExisting' => false,
                    );

    /**
     * Source
     *
     * @var object XFile Object or HttpSocket Object
     */
    public $src;

    /**
     * Temporary
     *
     * @var object XFile Object
     */
    public $tmp;

    /**
     * Destination
     *
     * @var object XFile Object or HttpSocket Object
     */
    public $dst;

    /**
     * If an error occurs it'll be appended here
     *
     * @var array
     */
    private $errors = array();

    /**
     * Messages are appended here
     *
     * @var array
     */
    private $messages = array();

    /**
     * Constructor
     *
     * @param mixed $src Path to file, url or Http-Form-File array
     * @param array $options (maxSize,allowMimetype,denyMimetype,allowExtension,denyExtension,overwriteExisting,createDirectory)
     */
    function __construct($src,$options = array())
    {
//		debug($options);
        /* Setup options */
        if(isset($options['maxSize'])) {
            $maxSizes[] = $this->toComputableSize($options['maxSize']);
            unset($options['maxSize']);
        }

        $maxSizes[] = $this->toComputableSize(ini_get('post_max_size'));
        $maxSizes[] = $this->toComputableSize(ini_get('upload_max_filesize'));

        sort($maxSizes);

        $this->config['maxSize'] = $maxSizes[0];

        if(isset($options['allowPaths']) && empty($options['allowPaths'])) {
            unset($options['allowPaths']);
        }

        $this->config = array_merge($this->config, $options);

        $this->setSource($src);
    }

    /**
     * Set the source file
     *
     * @param mixed Path to file, url or Http-Post array
     * @return bool true on success, false on error
     */
    private function setSource($data)
    {

        if($this->isHtmlFormFileData($data)) {
            // Detected valid HTTP-Upload array.

            $this->config['src'] = array(
                                    'type' => 'html-form-file',
                                    'host' => env('REMOTE_ADDR'),
                                    'basename' => pathinfo($data['name'],PATHINFO_BASENAME),
                                    'filename' => pathinfo($data['name'],PATHINFO_FILENAME),
                                    'extension' => pathinfo($data['name'],PATHINFO_EXTENSION),
                                    'mimetype' => $data['type'],
                                    'size' => $data['size'],
                                );


            /*
             * Checks
             */
            switch ($data['error']) {
                case UPLOAD_ERR_INI_SIZE:
                    $error = 'The file exceeds the upload_max_filesize directive'.' ('.ini_get('upload_max_filesize').') php.ini.';
                case UPLOAD_ERR_FORM_SIZE:
                    $error = 'The file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
                case UPLOAD_ERR_EXTENSION:
                    $error = 'File has wrong extension';
                    $this->errors[] = $error;
                    return false;
                case UPLOAD_ERR_OK:
                    break;

            }

            if(!$this->check('src')) {
                return false;
            }

            $this->setTemporary($data['tmp_name'],$data['error']);

        } elseif($this->isLocalFile($data)) {
            // Detected valid local file.
            App::import('Vendor','XFile');

            $file = $data;
            $this->src = new XFile($file);

            $this->config['src'] = array(
                                    'type' => 'local',
                                    'basename' => $this->src->name,
                                    'filename' => $this->src->name(),
                                    'extension' => $this->src->ext(),
                                    'path' => $this->src->Folder->pwd(),
                                    'mimetype' => $this->src->mimetype(),
                                    'size' => $this->src->size(),
                                );


        } elseif($this->isUrl($data)) {
            // Detected valid URL array.
            // Currently supports http and https only
            App::import('Core','HttpSocket');

            $this->src =& new HttpSocket();
            $this->src->get($data);

            $this->config['src'] = array(
                                    'type' => 'url',
                                    'basename' => pathinfo($this->src->request['uri']['path'],PATHINFO_BASENAME),
                                    'filename' => pathinfo($this->src->request['uri']['path'],PATHINFO_FILENAME),
                                    'extension' => pathinfo($this->src->request['uri']['path'],PATHINFO_EXTENSION),
                                    'path' => pathinfo($this->src->request['uri']['path'],PATHINFO_DIRNAME),
                                    'mimetype' => $this->src->response['header']['Content-Type'],
                                    'size' => $this->src->response['header']['Content-Length'],
                                );

            /*
             * Checks
             */
            if(!empty($this->src->error)) {
                $this->errors = array_merge($this->src->error,$this->errors);
                return false;

            } elseif($this->src->response['status']['code'] != 200) {
                $this->errors[] = 'Source file ('.$data.') could not be retrieved. Code: '.$this->src->reponse['status']['code'].' Reason: '.$this->src->reponse['status']['reason-phrase'];
                return false;

            }

            if(!$this->check('src',array('location'))) {
                return false;
            }

        } else {
            $this->errors[] = 'Source file argument is invalid or file is of unknown type';
//			debug($data);
            return false;
        }

        return true;
    }

    /**
     * set temporary file
     *
     * @param mixed $data Path to file or url
     * @param int $error Error from Http-Post array
     * @return bool true on success, false on error
     */
    private function setTemporary($data,$error = null)
    {
        if($this->isUploadedLocalFile($data)) {
            App::import('Vendor','XFile');

            $this->tmp = new XFile($data);

            $this->config['tmp'] = array(
                                    'type' => 'uploaded-local',
                                    'basename' => $this->tmp->name,
                                    'filename' => $this->tmp->name(),
                                    'extension' => $this->tmp->ext(),
                                    'path' => $this->tmp->Folder->pwd(),
                                    'mimetype' => $this->tmp->mimetype(),
                                    'size' => $this->tmp->size(),
                                );

            switch ($error) {
                case UPLOAD_ERR_PARTIAL:
                    $error = 'The file has only been partially uploaded';
                case UPLOAD_ERR_NO_FILE: // ?????
                    $error = 'No file was uploaded';
                case UPLOAD_ERR_NO_TMP_DIR:
                    $error = 'The remote server has no temporary folder for file uploads';
                case UPLOAD_ERR_CANT_WRITE:
                    $error = 'Failed to write file to disk';
                    $this->errors[] = $error;
                    return false;
                case UPLOAD_ERR_OK:
                    break;
            }


            if(!$this->check('tmp',array('extension', 'location'))) {
                return false;
            }

        } elseif($this->isLocalFile($data)) {
            App::import('Vendor','XFile');

            $this->tmp = new XFile($data);

            $this->config['tmp'] = array(
                                    'type' => 'local',
                                    'basename' => $this->tmp->name,
                                    'filename' => $this->tmp->name(),
                                    'extension' => $this->tmp->ext(),
                                    'path' => $this->tmp->Folder->pwd(),
                                    'mimetype' => $this->tmp->mimetype(),
                                    'size' => $this->tmp->size(),
                                );


            if(!$this->check('tmp')) {
                return false;
            }


        }  else {
            $this->errors[] = 'Could not determine temporary file type';
            return false;
        }

        return true;

    }

    /**
     * Set destination file
     *
     * @param mixed $data Path to file or url
     * @return bool true on success, false on error
     */
    public function setDestination($data)
    {
        if($this->isLocalFile($data)) {
            App::import('Vendor','XFile');

            $this->dst = new XFile($data);

            // make safe filename
            $newFileName = strtolower($this->dst->safe());
            $this->dst->rename($newFileName.strtolower($this->dst->ext()));

            $this->config['dst'] = array(
                                    'type' => 'local',
                                    'basename' => $this->dst->name,
                                    'filename' => $this->dst->name(),
                                    'extension' => $this->dst->ext(),
                                    'path' => $this->dst->Folder->pwd(),
                                    'mimetype' => $this->dst->mimetype(),
                                    'size' => $this->dst->size(),
                                );

                                // Checks and fixes on destination folder
            if(!is_dir($this->dst->Folder->pwd())) {
                $this->error[] = 'Directory '.$this->dst->Folder->pwd().' doesn\'t exist and cannot be created';
                return false;

            } elseif(!is_writable($this->dst->Folder->pwd())) {
                $this->error = 'Directory ' .$this->dst->Folder->pwd().' is not writable';
                return false;

            }

            // Checks and fixes on destination file
            if($this->dst->exists() && $this->config['overwriteExisting'] && !$this->dst->delete()) {
                $this->errors[] = 'The file '.$this->dst->pwd().' already exists and cannot be deleted';

            } elseif($this->dst->exists() && !$this->config['overwriteExisting']) {
                /*
                 * Try to find alternative filename
                 * Maximum 29 tries
                 */
                $new = clone $this->dst;
                $count = 2;
                while($new->exists()) {
                    $new = clone $this->dst;

                    if($count > $this->config['renamingTries']) {
                        $this->errors[] = 'The file ' . $new->pwd() . ' already exists. No more counts left';

                    }

                    $new->postfix('_'.$count);
                    $count++;
                }
                $this->dst = $new;
                $this->config['dst']['basename'] = $this->dst->name;
                $this->config['dst']['filename'] = $this->dst->name();
            }

            if(!$this->check('dst',array('size','mimetype'))) {
                return false;
            }

        } elseif($this->isUrl($data)) {
            trigger_error('HTTP post/put or FTP upload not yet implemented ',E_USER_ERROR);

//			$this->src =& new HttpSocket();
//			$this->src->get($data);
//
//			$this->config['src'] = $this->src->request['uri'];
//			$this->config['src']['type'] = 'url';

        } else {
            $this->errors[] = 'Could not determine destination file type';
            return false;

        }

        return true;
    }

    /**
     * Do the actual transfer
     *
     * @return bool true on success, false on error
     */
    public function execute()
    {
        $transferString = implode('>>',array($this->config['src']['type'],$this->config['tmp']['type'],$this->config['dst']['type']));
//		debug('Performing: '.$transferString);
//		debug($this->tmp->pwd());
//        debug($this->src->pwd());
//		debug($this->dst->pwd());
//		debug($this->config['dst']);
//        debug($this->config['src']);


        switch ($transferString) {
            case 'html-form-file>>uploaded-local>>local':
                $r = move_uploaded_file($this->tmp->pwd(),$this->dst->pwd());
                break;

            case 'html-form-file>>uploaded-local>>url':
                break;

            case 'local>>>>local':
                $r = copy($this->src->pwd(),$this->dst->pwd());
                break;

            case 'local>>>>url':
                break;

            case 'url>>>>local':
                $r = file_put_contents($this->dst->pwd(),$this->src->response['body']);
                break;

            case 'url>>local>>local':
            case 'local>>local>>local':
                $r = copy($this->tmp->pwd(),$this->dst->pwd());
                break;

            default:
                break;

        }

        if($r) {
            $this->message[] = 'Saved source/temporary file to '.$this->config['dst']['path'].$this->config['dst']['basename'];
            return true;

        } else {
            $this->errors[] = 'Could not save source/temporary file to '.$this->config['dst']['path'].$this->config['dst']['basename'];
            return false;

        }

    }

    /**
     * Checks if given data is a local file
     * and in a valid path
     * and not exectuable
     */
    private function isLocalFile($file)
    {
        if(!is_string($file)) {
            return false;
        }

        $url = parse_url($file);
        if(isset($url['scheme']) && ($url['scheme'] == 'file' || $url['scheme'] == 'C'|| $url['scheme'] == 'D')) {
            $file = substr($file,7);
            //$file = $url['path'];

        } elseif(!empty($url['scheme'])) {
            return false;
        }

        if(is_file($file) && is_executable($file)) {
            $this->errors[] = 'File is a local file but executable bit is set.';
            return false;
        }

        return true;
    }

    /**
     * Test if given data is an uploaded file
     *
     * @param mixed $data
     */
    private function isUploadedLocalFile($file)
    {
        if(!$this->isLocalFile($file)) {
            return false;
        }

        if(!is_uploaded_file($file)) {
            $this->errors[] = 'File is local file but not uploaded by http-post';
            return false;
        }

        return true;
    }

    /**
     * Test if given data is url
     * returns false even on file://
     *
     * @param mixed $data
     * @return unknown
     */
    private function isUrl($url)
    {
        if(!is_string($url)) {
            return false;
        }

        $url = parse_url($url);

        if(!isset($url['path'])
        || !isset($url['scheme'])
        || $url['scheme'] == 'file') {
            return false;
        }

        if($url['scheme'] != 'http' && $url['scheme'] != 'https' ) {
            $this->errors[] = 'Scheme not yet supported by transfer class';
            return false;
        }

        if(!getservbyname($url['scheme'],'tcp')) {
            $this->errors[] = 'Scheme not supported by system';
            return false;
        }

        // Load lib dynamically
        uses('http_socket');

        return true;
    }

    /**
     * Checks if given data is valid
     */
    private function isHtmlFormFileData($data)
    {
        if(!is_array($data)) {
            return false;
        }

        if(!isset($data['name'])
        || !isset($data['type'])
        || !isset($data['tmp_name'])
        || !isset($data['error'])
        || !isset($data['size'])) {
            return false;
        }

        if(empty($data['name'])
        || empty($data['type'])
        || empty($data['tmp_name'])
        || empty($data['size'])) {
            return false;
        }

        return true;
    }

    // on = src or tmp or dst
    private function check($on,$skip = array())
    {

        $data = $this->config[$on];
        $map = array('src' => 'Source','tmp' => 'Temporary','dst' => 'Destination');
        $on = $map[$on];

//        debug($skip);
//        debug($data);
//        debug($this->config);

        /*
         * size, mimetype, extension MUST be set
         * path is optional
         */
        if(!$this->checkSize($data['size']) && !in_array('size',$skip)) {
            $this->errors[]= $on.' file exceeds size limits';
            return false;

        } elseif(!$this->checkMimetype($data['mimetype']) && !in_array('mimetype',$skip)) {
            $this->errors[] = $on.' file has incorrect mimetype';
            return false;

        } elseif(!$this->checkExtension($data['extension']) && !in_array('extension',$skip)) {
            $this->errors[] = $on.' file has incorrect extension';
            return false;

        } elseif(isset($data['path']) && !$this->checkLocation($data['path']) && !in_array('location',$skip)) {
            $this->errors[] = $on.' file is not located within allowed paths';
            return false;

        }

        return true;
    }

    /**
     * Check size
     *
     * @param mixed $data Object with method size() or property response with header.Content-Length
     * @return bool
     */
    private function checkSize($size)
    {
        if (is_null($this->config['maxSize'])) {
            return true;
        }

        if(empty($size)) {
            return false;
        }

        if ($size < ($this->config['maxSize'])) {
            return true;
        }

        return false;
    }

    /**
     * Check if we allow the given mimetype
     *
     */
    private function checkMimetype($mimetype)
    {
        if(empty($mimetype)) {
            return false;
        }

        if ((is_array($this->config['allowMimetype'])
            && !in_array($extension,$this->config['allowMimetype']))
            || (is_array($this->config['denyMimetype'])
                && in_array($extension,$this->config['denyMimetype'])))
        {
            return false;
        }

        return true;
    }

    /**
     * Check if we allow the given extension (without dot)
     *
     */
    private function checkExtension($extension)
    {
        if(empty($extension)) {
            return false;
        }

        $extension = strtolower($extension);

        if ((is_array($this->config['allowExtension'])
            && !in_array($extension,$this->config['allowExtension']))
            || (is_array($this->config['denyExtension'])
                && in_array($extension,$this->config['denyExtension'])))
        {
            return false;
        }

        return true;

    }

    /**
     * Checks if given file is within allowed paths
     *
     * @param unknown_type $object
     * @return bool
     */
    private function checkLocation($path)
    {
        $allowPaths = $this->config['allowPaths'];

        if($uploadTmpDir = ini_get('upload_tmp_dir')) {
            $allowPaths[] = $uploadTmpDir;
        }

        $found = false;
        foreach($allowPaths as $allowPath) {
            if(preg_match('/^'.preg_quote($allowPath,'/').'/',$path.DS)) {
                $found = $allowPath;
                break;
            }
        }

        return $found;
    }

    /**
     * Checks if we don't exceed the size
     *
     * e.g. for 8 MEGABYTE use 8M
     *
     * Uses parts of the ini_get_size() function
     * posted at http://www.php.net/features.file-upload
     * by djcassis gmail com
     * @author djcassis gmail com
     *
     */
    private function toComputableSize($sizeString)
    {
        if(empty($sizeString)) {
            return null;
        }

        $sizeUnit = substr($sizeString, -1);
        $size = (int) substr($sizeString, 0, -1);

        switch($sizeUnit) {
            case 'Y' : $size *= 1024; // Yotta
            case 'Z' : $size *= 1024; // Zetta
            case 'E' : $size *= 1024; // Exa
            case 'P' : $size *= 1024; // Peta
            case 'T' : $size *= 1024; // Tera
            case 'G' : $size *= 1024; // Giga
            case 'M' : $size *= 1024; // Mega
            case 'K' : $size *= 1024; // kilo
        }

        return $size;
    }

    /**
     * Return errors in error queue
     *
     * @return array
     */
    public function errors()
    {
        return $this->errors;
    }

    /**
     * Return last error
     *
     * @return string
     */
    public function lastError()
    {
        if(empty($this->errors)) {
            return false;
        }

        return $this->errors[count($this->errors) -1];
    }

    public function error()
    {
        if(empty($this->errors)) {
            return false;
        }

        return true;
    }

    /**
     * Return errors in error queue
     *
     * @return array
     */
    public function messages()
    {
        return $this->messages;
    }

    /**
     * Return last error
     *
     * @return string
     */
    public function lastMessage()
    {
        if(empty($this->messages)) {
            return false;
        }

        return $this->messages[count($this->errors) - 1];
    }

}
?>