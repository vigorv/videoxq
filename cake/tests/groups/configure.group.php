<?php
/* SVN FILE: $Id: configure.group.php 8116 2009-03-18 17:55:58Z davidpersson $ */
/**
 * ConfigureGroupTest file
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
 * @version       $Revision: 8116 $
 * @modifiedby    $LastChangedBy: davidpersson $
 * @lastmodified  $Date: 2009-03-18 23:55:58 +0600 (Wed, 18 Mar 2009) $
 * @license       http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */
/**
 * ConfigureGroupTest class
 *
 * This test group will run all test for the configure and loader.
 *
 * @package       cake
 * @subpackage    cake.tests.groups
 */
class ConfigureGroupTest extends GroupTest {
/**
 * label property
 *
 * @var string 'Configure, Loader, ClassRegistry Tests'
 * @access public
 */
	var $label = 'Configure, App and ClassRegistry';
/**
 * ConfigureGroupTest method
 *
 * @access public
 * @return void
 */
	function ConfigureGroupTest() {
		TestManager::addTestFile($this, CORE_TEST_CASES . DS . 'libs' . DS . 'configure');
		TestManager::addTestFile($this, CORE_TEST_CASES . DS . 'libs' . DS . 'class_registry');
	}
}
?>