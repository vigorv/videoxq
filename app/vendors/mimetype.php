<?php
/**
 * Mimetype
 * 
 * Mimetype database
 * Handler registration for mimetypes
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
 * 
 * @author 		David Persson <davidpersson at qeweurope dot org>
 * @copyright 	David Persson <davidpersson at qeweurope dot org>
 * @package 	mimetype
 * @version 	0.1
 * @license 	http://www.opensource.org/licenses/mit-license.php The MIT License
 */
class Mimetype
{
	/**
	 * Mimetype Database
	 * including text/x-code entries
	 */
	static private $lookup = array(
	//	mimetype							extensions default extension comes first

	/*
	 * Application
	 */
	'application/andrew-inset' 			=> array('ez'),
	'application/mac-binhex40' 			=> array('hqx'),
	'application/mac-compactpro' 		=> array('cpt'),
	'application/msword' 				=> array('doc'),
	'application/octet-stream' 			=> array('bin','class','dll','dms','exe','lha','lzh','so'),
	'application/oda' 					=> array('oda'),
	'application/pdf' 					=> array('pdf'),
	'application/photoshop' 			=> array('psd'),
	'application/postscript' 			=> array('ai','eps','ps'),
	'application/smil'					=> array('smi','smil'),
	'application/vnd.mif'				=> array('mif'),
	'application/vnd.ms-excel' 			=> array('xls'),
	'application/vnd.ms-powerpoint' 	=> array('ppt'),
	'application/vnd.visio' 			=> array('vsd'),
	'application/vnd.wap.wbxml' 		=> array('wbxml'),
	'application/vnd.wap.wmlc' 			=> array('wmlc'),
	'application/vnd.wap.wmlscriptc' 	=> array('wmlsc'),
	'application/x-bcpio' 				=> array('bcpio'),
	'application/x-cdlink' 				=> array('vcd'),
	'application/x-chess-pgn' 			=> array('pgn'),
	'application/x-compress' 			=> array('z'),
	'application/x-cpio' 				=> array('cpio'),
	'application/x-csh' 				=> array('csh'),
	'application/x-director' 			=> array('dcr','dir','dxr'),
	'application/x-dvi' 				=> array('dvi'),
	'application/x-futuresplash' 		=> array('spl'),
	'application/x-gtar' 				=> array('gtar'),
	'application/x-gzip' 				=> array('gz'),
	'application/x-hdf' 				=> array('hdf'),
//	'application/x-javascript' 			=> array('js'),
	'application/x-koan' 				=> array('skd','skm','skp','skt'),
	'application/x-latex' 				=> array('latex'),
	'application/x-netcdf' 				=> array('cdf','nc'),
//	'application/x-sh' 					=> array('sh'),
	'application/x-shar' 				=> array('shar'),
	'application/x-shockwave-flash'		=> array('swf'),
	'application/x-stuffit' 			=> array('sit'),
	'application/x-sv4cpio' 			=> array('sv4cpio'),
	'application/x-sv4crc' 				=> array('sv4crc'),
	'application/x-tar' 				=> array('tar'),
	'application/x-tcl' 				=> array('tcl'),
	'application/x-tex' 				=> array('tex'),
	'application/x-texinfo' 			=> array('texi','texinfo'),
	'application/x-troff' 				=> array('roff','t','tr'),
	'application/x-troff-man' 			=> array('man'),
	'application/x-troff-me' 			=> array('me'),
	'application/x-troff-ms' 			=> array('ms'),
	'application/x-ustar' 				=> array('ustar'),
	'application/x-wais-source' 		=> array('src'),
	'application/zip' 					=> array('zip'),

	/*
	 * Audio
	 */
	'audio/basic' 						=> array('au','snd'),
	'audio/midi' 						=> array('kar','mid','midi'),
	'audio/mpeg' 						=> array('mp2','mp3','mpga'),
	'audio/x-aiff' 						=> array('aif','aifc','aiff'),
	'audio/x-mpegurl' 					=> array('m3u'),
	'audio/x-ms-wma' 					=> array('wma'),
	'audio/x-pn-realaudio' 				=> array('ram','rm'),
	'audio/x-pn-realaudio-plugin'		=> array('rpm'),
	'audio/x-realaudio' 				=> array('ra'),
	'audio/x-wav' 						=> array('wav'),
	
	/*
	 * Chemical
	 */
	'chemical/x-pdb' 					=> array('pdb'),
	'chemical/x-xyz' 					=> array('xyz'),

	/*
	 * Image
	 */
	'image/bmp' 						=> array('bmp'),
	'image/gif'							=> array('gif'),
	'image/ief' 						=> array('ief'),
	'image/jp2' 						=> array('jp2','jpg2'),
	'image/jpeg'						=> array('jpg','jpeg','jpe'),
	'image/jpeg-cmyk' 					=> array('jpgcmyk'),
	'image/jpgm' 						=> array('jpgm'),
	'image/jpm' 						=> array('jpm'),
	'image/jpx' 						=> array('jpf','jpx'),
	'image/png' 						=> array('png'),
	'image/svg+xml' 					=> array('svg'),
	'image/tga' 						=> array('tga'),
	'image/tiff'						=> array('tif','tiff'),
	'image/tiff-cmyk' 					=> array('tifcmyk'),
	'image/vnd.djvu' 					=> array('djv','djvu'),
	'image/vnd.wap.wbmp' 				=> array('wbmp'),
	'image/wmf' 						=> array('wmf'),
	'image/x-cmu-raster' 				=> array('ras'),
	'image/x-photo-cd' 					=> array('pcd'),
	'image/x-portable-anymap' 			=> array('pnm'),
	'image/x-portable-bitmap' 			=> array('pbm'),
	'image/x-portable-graymap' 			=> array('pgm'),
	'image/x-portable-pixmap' 			=> array('ppm'),
	'image/x-rgb' 						=> array('rgb'),
	'image/x-xbitmap' 					=> array('xbm'),
	'image/x-xpixmap' 					=> array('xpm'),
	'image/x-xwindowdump' 				=> array('xwd'),

	/*
	 * Model
	 */
	'model/iges' 						=> array('iges','igs'),
	'model/mesh' 						=> array('mesh','msh','silo'),
	'model/vrml' 						=> array('vrml','wrl'),

	/*
	 * Text
	 */
	'text/css' 							=> array('css'),
	'text/csv'							=> array('csv'),
	'text/html' 						=> array('html', 'htm'),
	'text/plain' 						=> array('asc','txt'),
	'text/richtext' 					=> array('rtx'),
	'text/rtf' 							=> array('rtf'),
	'text/sgml' 						=> array('sgm','sgml'),
	'text/tab-separated-values' 		=> array('tsv'),
	'text/vnd.wap.wml' 					=> array('wml'),
	'text/vnd.wap.wmlscript' 			=> array('wmls'),
	'text/xml'							=> array('xml'),
	'text/xml-dtd'						=> array('dtd','mod'),
	'text/xslt+xml'						=> array('xsl'),
	'text/x-actionscript' 				=> array('as'),
	'text/x-ada' 						=> array('a', 'ada', 'adb', 'ads'),
	'text/x-asm' 						=> array('ash', 'asm'),
	'text/x-asp' 						=> array('asp'),
	'text/x-sh' 						=> array('sh'),
	'text/x-c' 							=> array('c', 'h'),
	'text/x-cakephp' 					=> array('ctp'),
	'text/x-cdfg' 						=> array('cdfg'),
	'text/x-cpp' 						=> array('cpp', 'h', 'hpp'),
	'text/x-delphi' 					=> array('dpk', 'dpr'),
	'text/x-java' 						=> array('java'),
	'text/x-javascript' 				=> array('js'),
	'text/x-lisp' 						=> array('lisp'),
	'text/x-lua' 						=> array('lua'),
	'text/x-pascal' 					=> array('pas'),
	'text/x-perl' 						=> array('pl','pm'),
	'text/x-php' 						=> array('php','php3','php4','php5','phtml','phps'),
	'text/x-python' 					=> array('py'),
	'text/x-qbasic' 					=> array('bi'),
	'text/x-rdf+xml'					=> array('rdf'),
	'text/x-sas' 						=> array('sas'),
	'text/x-smarty' 					=> array('tpl'),
	'text/x-svg+xml'					=> array('svg'),
	'text/x-vb' 						=> array('bas'),
	'text/x-setext' 					=> array('etx'),

	/*
	 * Video
	 */
	'video/mj2' 						=> array('mj2','mjp2'),
	'video/mp4' 						=> array('mp4'),
	'video/mpeg' 						=> array('mpe','mpeg','mpg'),
	'video/quicktime' 					=> array('mov','qt'),
	'video/vnd.mpegurl' 				=> array('mxu'),
	'video/x-flv' 						=> array('flv'),
	'video/x-ms-asf' 					=> array('asf'),
	'video/x-ms-asx' 					=> array('asx'),
	'video/x-ms-wmv' 					=> array('wmv'),
	'video/x-msvideo' 					=> array('avi'),
	'video/x-sgi-movie' 				=> array('movie'),
	
	/*
	 * X
	 */
	'x-conference/x-cooltalk' 			=> array('ice'),
	);
	
	
	static private $handlers = array();
	
	// TODO: implement extension and mimetype lookup by soundex/metaphone
	
	/**
	 * This routine tries to determine the mime-type of the filename 
	 * only by looking at the filename from the GNOME database of mime-types.
	 *
	 */
	static public function detectFromExtension($file)
	{
		$ext = strtolower(pathinfo($file,PATHINFO_EXTENSION));

		if(!is_string($ext)) {
			return false;
		}
    	
        foreach (self::$lookup as $mimetype => $extensions) {
            foreach ($extensions as $extension) {
                if ($ext == $extension) {
                    return $mimetype;
                }
            }
        }
        
        return false;		
		
	}
	
	static public function detectFromData($file)
	{
		/*
		 * Workaround for some mimetype detection implementations
		 */
		if(function_exists('finfo_open')) {
	    	$finfo = finfo_open(FILEINFO_MIME);
	    	$mimetype = finfo_file($finfo,$file);
	    	finfo_close($finfo);
	    	
	    	return $mimetype;
		} 
		
		if($mimetype = mime_content_type($file)) {
			return $mimetype;
		}
		
		return false;		
	}
	
	/**
	 *  
	 * Tries to detect mime-type of the file by looking first at the extension
	 * and then falling back to mime-magic detection
	 * This may be faster then always doing a mime-magic detection but can
	 * produce wrong answers
	 *  
	 */
	static public function detectFast($file)
	{
		if($mimetype = self::detectFromExtension($file)) {
			return $mimetype;
		}

		if($mimetype = self::detectFromData($file)) {
			return $mimetype;
		}
		
		return false;			
	}
	
	/**
	 * Tries to guess the mime-type of the file by doing a mime-magic lookup
	 * then falling back to extension based lookup
	 *
	 */
	static public function detect($file)
	{
		// 
		if($mimetype = self::detectFromData($file)) {
			return $mimetype;
		}
		
		if($mimetype = self::detectFromExtension($file)) {
			return $mimetype;
		}
		
		return false;		
		
	}
	
	static public function registerHandler($mimetype,$handler)
	{
		if(!key_exists($mimetype,self::$lookup)) {
			return false;
		}

		if(isset(self::$handlers[$mimetype]) && in_array($handler,self::$handlers[$mimetype])) {
			return true;
		}
		
		self::$handlers[$mimetype][] = $handler; 
		
		return true;
	}
	
	static public function handlers($mimetype)
	{
		if(!is_string($mimetype) || empty($mimetype)) {
			return array();
		}
		
		if(!key_exists($mimetype,self::$handlers)) {
			return array();
		}
		
		return self::$handlers[$mimetype];
	}
}
?>