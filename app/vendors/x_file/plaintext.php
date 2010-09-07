<?php
/**
 * XFilePlaintext
 * 
 * Adds plaintext-specific methods to the XFile class
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

App::import('Vendor','XFile');

class XFilePlaintext extends XFile
{
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
		$result = array();

		switch ($level) {
			case 'complete':
			case 'summary':
			case 'extended':
				App::import('Vendor','Text/Statistics.php');
				$text = new Text_Statistics($this->contents());
				$result = array_merge($result,array(
					'syllables'		=> $text->numSyllables,
					'words'			=> $text->numWords,
					'unique_words'	=> $text->uniqWords,
					'sentences' 	=> $text->numSentences,
					'flesch_score' 	=> $text->flesch,
					// percent, dense text (hard to read) 60-70, lower dense 40-50
					'lexical_density' 	=> $text->uniqWords / $text->numWords));
			case 'basic':
				$result = array_merge($result,parent::info());
				break;
		}		

		return $result;
	}
}

?>