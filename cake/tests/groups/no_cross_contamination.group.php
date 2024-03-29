<?php
/* SVN FILE: $Id: no_cross_contamination.group.php 8123 2009-03-21 23:55:39Z davidpersson $ */
/**
 * NoCrossContaminationGroupTest file
 *
 * Long description for file
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) Tests <https://trac.cakephp.org/wiki/Developement/TestSuite>
 * Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 *
 *  Licensed under The Open Group Test Suite License
 *  Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright     Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 * @link          https://trac.cakephp.org/wiki/Developement/TestSuite CakePHP(tm) Tests
 * @package       cake
 * @subpackage    cake.tests.groups
 * @since         CakePHP(tm) v 1.2.0.4206
 * @version       $Revision: 8123 $
 * @modifiedby    $LastChangedBy: davidpersson $
 * @lastmodified  $Date: 2009-03-22 05:55:39 +0600 (Sun, 22 Mar 2009) $
 * @license       http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */
/**
 * NoCrossContaminationGroupTest class
 *
 * This test group will run all tests
 * that are proper isolated to be run in sequence
 * without affected each other
 *
 * @package       cake
 * @subpackage    cake.tests.groups
 */
class NoCrossContaminationGroupTest extends GroupTest {
/**
 * label property
 *
 * @var string
 * @access public
 */
	var $label = 'No Cross Contamination';
/**
 * blacklist property
 *
 * @var string
 * @access public
 */
	var $blacklist = array('cake_test_case.test.php', 'object.test.php');
/**
 * NoCrossContaminationGroupTest method
 *
 * @access public
 * @return void
 */
	function NoCrossContaminationGroupTest() {
		App::import('Core', 'Folder');

		$Folder = new Folder(CORE_TEST_CASES);

		foreach ($Folder->findRecursive('.*\.test\.php', true) as $file) {
			if (in_array(basename($file), $this->blacklist)) {
				continue;
			}
			TestManager::addTestFile($this, $file);
		}
	}
}
?>