<?php
/**
 * XFileImage
 * 
 * Adds image-specific methods to the XFile class
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

App::import('Vendor',array('XFile','Calculation'));

class XFileImage extends XFile
{
	/**
	 * Image resource
	 *
	 * @var image
	 */
	var $image;
	
	/**
	 * Constructor
	 *
	 * @param unknown_type $path
	 * @param unknown_type $create
	 * @param unknown_type $mode
	 */
	function __construct($path, $create = false, $mode = 0755)
	{
		parent::__construct($path,$create,$mode);
	}
	
	/**
	 *
	 * @return unknown
	 */
	/**
	 * Desctructor
	 *
	 */
	function __destruct()
	{
		if($this->image()) {
			imagedestroy($this->image);
		}
	}

	/**
	 * Dump the complete current contents
	 * 
	 * @return string dump
	 */	
	function dump()
	{
		if(!$this->image()){
			return false;
		}
		
		$size = getimagesize($this->pwd());
		$type = $size[2];
		ob_start();

		switch($type) {
			case 1:  // GIF
				imagegif($this->image);
				break;
			case 2:  // JPEG
				imagejpeg($this->image);
				break;
			case 3:  // PNG
				imagepng($this->image);
				break;
			case 15: // WBMP
				imagewbmp($this->image);
				break;
			case 16: // XBM
				imagexbm($this->image);
				break;
			case 4:  // SWF
			case 5:  // PSD
			case 6:  // BMP
			case 7:  // TIFF (intel byte order)
			case 8:  // TIFF (motorola byte order)
			case 9:  // JPC
			case 10: // JP2
			case 11: // JPX
			case 12: // JB2
			case 13: // SWC
			case 14: // IFF
			default:
				// Not yet supported
				// FAIL
				return false;
				break;					
		}
		
		return ob_get_clean();			
			
	}
	
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $im
	 * @param unknown_type $dst
	 * @return unknown
	 */
	private function save()
	{
		if($this->image()) {
			return false;
		}	
				
		if($this->exists()) {
			$this->delete();
		}
				
		$size = getimagesize($this->pwd());
		$type = $size[2];
		
		switch($type) {
			case 1:  // GIF
				return imagegif($this->image,$this->pwd());
			case 2:  // JPEG
				return imagejpeg($this->image,$this->pwd());
			case 3:  // PNG
				return imagepng($this->image,$this->pwd());
			case 15: // WBMP
				return imagewbmp($this->image,$this->pwd());
			case 16: // XBM
				return imagexbm($this->image,$this->pwd());
			case 4:  // SWF
			case 5:  // PSD
			case 6:  // BMP
			case 7:  // TIFF (intel byte order)
			case 8:  // TIFF (motorola byte order)
			case 9:  // JPC
			case 10: // JP2
			case 11: // JPX
			case 12: // JB2
			case 13: // SWC
			case 14: // IFF
			default:
				// Not yet supported
				// FAIL
				return false;
				break;					
		}
		
		return true;
	}
	
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $file
	 * @return unknown
	 */
	private function image()
	{
		if(!$this->readable()) {
			return false;
		}
				
		if(!$this->exists()) {
			return false;
		}
		
		if($this->image) {
			return true;
		}
				
		$size = getimagesize($this->pwd());
		$type = $size[2];
		
		switch($type) {
			case 1:  // GIF
				$this->image = imagecreatefromgif($this->pwd());
				break;
			case 2:  // JPEG
				$this->image = imagecreatefromjpeg($this->pwd());
				break;
			case 3:  // PNG
				$this->image = imagecreatefrompng($this->pwd());
				break;
			case 15: // WBMP
				$this->image = imagecreatefromwbmp($this->pwd());
			case 16: // XBM
				$this->image = imagecreatefromxbm($this->pwd());
				break;

			case 4:  // SWF
			case 5:  // PSD
			case 6:  // BMP
			case 7:  // TIFF (intel byte order)
			case 8:  // TIFF (motorola byte order)
			case 9:  // JPC
			case 10: // JP2
			case 11: // JPX
			case 12: // JB2
			case 13: // SWC
			case 14: // IFF
			default:
				// Not yet supported
				// FAIL
				return false;
				break;					
		}

		return true;
	}
	
	/**
	 * Enter description here...
	 *
	 * @return unknown
	 */
	function dimensions()
	{
		if(!$this->image()){
			return false;
		}
		
		$width  = imagesx($this->image);
		$height = imagesy($this->image);
		
		return array($width,$height);	
	}
	
	/**
	 * Resize image
	 * Automatically determines by size which resize method to use
	 * for smaller images zoomCrop is used for larger fit
	 *
	 * TODO: zoomCrop is disabled until method Calculation::dimensionsWithin... works
	 * 
	 * @param int $dstWidth
	 * @param int $dstHeight
	 * @param string $dst
	 * @return bool
	 */
	public function resize($params)
	{
		list($width,$height) = $this->dimensions();
		
		return $this->fit($params);
		
//		if($dstWidth < ($width / 2) 
//		|| $dstHeight < ($height / 2)) {
//			debug('zoomCropping');
//			return $this->zoomCrop(array('width' => $dstWidth,'height' => $dstHeight),$dst);
//		} else {
//			debug('fitting');
//			return $this->fit(array('width' => $dstWidth,'height' => $dstHeight),$dst);
//		}
	}
	
	/**
	 * Fit a file according to options into a canvas
	 *
	 * TODO enlarge files??
	 * @params array
	 * @return object XFileImage Object
	 */
	public function fit($params)
	{
		if(!$this->image()){
			return false;
		}

		
		list($srcWidth,$srcHeight) = $this->dimensions();

		list($dstWidth,$dstHeight) = Calculation::dimensionsWithinConstraints($srcWidth,$srcHeight,$params['width'],$params['height']);

		$tmp = imagecreatetruecolor($dstWidth, $dstHeight);

		$success = imagecopyresampled(
						$tmp,
						$this->image,
						0,
						0,
						0,
						0,
						$dstWidth,
						$dstHeight,
						$srcWidth,
						$srcHeight
						);
						
		$this->image = $tmp;
		return $success;

	}

	/**
	 * Crops the image from calculated center in a square of $cropSize pixels
	 *
	 * @param int $cropSize
	 */
	public function zoomCrop($params,$dst)
	{
		if(!$this->image){
			return false;
		}	

		
		list($srcWidth,$srcHeight) = $this->dimensions();
//		debug('SRC');
//		debug($srcWidth);
//		debug($srcHeight);
		
		list($dstWidth, $dstHeight) = Calculation::dimensionsWithinConstraints($params['width'],$params['height'],$srcWidth,$srcHeight);
//		debug('DST');
//		debug($dstWidth);
//		debug($dstHeight);
			
		// each cropping side should be at least 1/2 of the source side								 max					min						
		debug('CROP');
		debug('-------------------------------------------------------------');
		list($cropWidth,$cropHeight) = Calculation::dimensionsWithinConstraints($dstWidth,$dstHeight,$srcWidth,$srcHeight,$srcWidth / 2,$srcHeight / 2);
		debug('-------------------------------------------------------------');
		debug($cropWidth);
		debug($cropHeight);			
		
		// crop the middle
		$cropX = ($srcWidth - $cropWidth) / 2;
		$cropY = ($srcHeight - $cropHeight) / 2;

		$temp = imagecreatetruecolor($dstWidth, $dstHeight);
		
		imagecopyresampled(
					$temp, // dest
					$this->image, // src
					0, // dest
					0, // dest
					$cropX, // src
					$cropY, // src
					$dstWidth, // dest OK
					$dstHeight, // dest OK
					$cropWidth, // src MAYBE WRONG TOO
					$cropHeight // src WRONG
					);
die();
		$this->imageSave($temp,$dst);

		imagedestroy($temp);
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
	function info($level = 'basic')
	{
		if(!$this->image()) {
			return parent::info();
		}

		$result = array();
		
		list($width,$height) = $this->dimensions();
		
		switch ($level) {
			case 'complete':
			case 'summary':
			case 'extended':
				$result = array_merge($result,array(
									'megapixel' 	=> Calculation::megapixel($width,$height),
									'ratio' 		=> Calculation::ratio($width,$height),
									'quality' 		=> Calculation::quality($width,$height)));
			case 'basic':
				$result = array_merge($result,array(
									'width' 		=> $width,
									'height' 		=> $height));
							
				$result = array_merge($result,parent::info());
				break;
		}

		return $result;
	}	
}
?>