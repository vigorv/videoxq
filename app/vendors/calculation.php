<?php
/**
 * Calculation
 * 
 * Some simple calculations
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
 * @package 	calculation
 * @version 	0.1
 * @license 	http://www.opensource.org/licenses/mit-license.php The MIT License
 */
class Calculation
{
	/**
	 * Megapixel of an image
	 *
	 * @param integer $width
	 * @param integer $height
	 * @return integer
	 */
	static public function megapixel($width,$height)
	{
		return round($width * $height / 1000000);
	}
	
	/**
	 * Determine the quality of an image
	 * 
	 * @param integer $width
	 * @param integer $height
	 * @return integer an integer between 0 and 5
	 */
	static public function quality($width,$height)
	{
		$megapixel = Calculation::megapixel($width,$height);
//		foreach(array(150,200,300,600) as $resolution) {
//			$max_print_sizes[$resolution]['width'] =  round(($width / $resolution) * 0.0254,3);
//			$max_print_sizes[$resolution]['height'] = round(($height / $resolution) * 0.0254,3);
//		}

		/*
		 * Normalized between 1 and 5
		 * min = 0.5
		 * max = 20
		 */
		if($megapixel > 20) {
			$quality = 5;
		} elseif($megapixel < 0.5) {
			$quality = 0;
		} else {
			$quality = round((($megapixel - 0.5) / (30 - 0.5)) * (5 * 1) + 1,0);
		}

		return $quality;
	}	
	
	/**
	 * Determine the ration of an image
	 *
	 * @param integer $width
	 * @param integer $height
	 * @param boolean $knownOnly
	 * @return string
	 */
	static public function ratio($width,$height,$knownOnly = true)
	{
		// width:height
		$knownRatios = array(
						'1:1.294' 	=> 1/1.294,
						'1:1.545' 	=> 1/1.1545,
						'4:3' 		=> 4/3,
						'1.375:1' 	=> 1.375,
						'3:2' 		=> 3/2,
						'16:9' 		=> 16/9,
						'1.85:1' 	=> 1.85,
						'1.96:1' 	=> 1.96,
						'2.35:1' 	=> 2.35, 
						'√2:1' 		=> pow(2, 1/2), 				// dina4 quer
						'1:√2' 		=> 1 / (pow(2, 1/2)), 			// dina4 hoch
						'Φ:1'		=> (1 + pow(5,1/2)) / 2, 		// goldener schnitt
						'1:Φ'		=> 1 / ((1 + pow(5,1/2)) / 2), 	// goldener schnitt
						);
		
		$ratio =  $width / $height;
		
		foreach($knownRatios as $knownRatioName => &$knownRatio) {
			$knownRatio = abs($ratio - $knownRatio);
		}
		
		asort($knownRatios);
		
		return array_shift(array_keys($knownRatios));
	}
	
	/**
	 * Calculates proportionally dimensions within given constraints
	 *
	 * FIXME Does not work as expected especially for zoomCrop. What is expected???
	 * 
	 * 
	 * @param integer $width
	 * @param integer $height
	 * @param integer $maxWidth
	 * @param integer $maxHeight
	 * @param integer $minWidth
	 * @param integer $minHeight
	 * @return array consisting of width and height
	 */
	static public function dimensionsWithinConstraints($width, $height, $maxWidth = null, $maxHeight = null, $minWidth = null, $minHeight = null)
	{
//		debug('SRC: '.$width.'x'.$height);
//		debug('MAX: '.$maxWidth.'x'.$maxHeight);
//		debug('MIN: '.$minWidth.'x'.$minHeight);

		$ratio = $width/$height;
		
		if($maxWidth > $width) {
			$maxWidth = $width;
		}
			
		if($maxHeight > $height) {
			$maxHeight = $height;
		}

		if($maxWidth !== null && $width > $maxWidth) {
			$width = $maxWidth;
			$height = ceil($maxWidth / $ratio);
		}
		
		if($maxHeight !== null && $height > $maxHeight) {
			$height = $maxHeight;
			$width = ceil($maxHeight * $ratio);
		}

//		debug('DEBG A: '.$width.'x'.$height);		

		if($minWidth !== null && $width < $minWidth) {
			$width = $minWidth;
			$height = ceil($minWidth / $ratio);
		}

//		debug('DEBG B: '.$width.'x'.$height);

		if($minHeight !== null && $height < $minHeight) {
			$height = $minHeight;
			$width = ceil($minHeight * $ratio);
		}

//		debug('RESULT: '.$width.'x'.$height);		
		
		return array($width,$height);
	}	

	/**
	 * Converts a floating point number into a fraction.
	 * 
	 * Modified version of ConvertToFraction from Exifer
	 * 
	 * @author Matthieu Fromment
	 * @param integer $v
	 * @param integer $n
	 * @param integer $d
	 */
	static public function convertToFraction($v, &$n, &$d)
	{
	  $maxTerms = 15;		// Limit to prevent infinite loop
	  $minDivisor = 1E-6; 	// Limit to prevent divide by zero
	  $maxError = 1E-8; 	// How close is enough
	
	  $f = $v; 				// Initialize fraction being converted
	
	  $nUn = 1; 			// Initialize fractions with 1/0, 0/1
	  $dUn = 0;
	  $nDeux = 0;
	  $dDeux = 1;
	
	  for ($i = 0;$i<$maxTerms;$i++) {
	    $a = floor($f); 			// Get next term
	    $f = $f - $a; 				// Get new divisor
	    $n = $nUn * $a + $nDeux; 	// Calculate new fraction
	    $d = $dUn * $a + $dDeux;
	    $nDeux = $nUn; 				// Save last two fractions
	    $dDeux = $dUn;
	    $nUn = $n;
	    $dUn = $d;
	
	    if ($f < $minDivisor) { 	// Quit if dividing by zero
	      break;
	    }
	    
	    if (abs($v - $n / $d) < $maxError) {
	      break;
	    }
	    
	    $f = 1 / $f; // Take reciprocal
	  }
	}	
}
?>