<?php
/* SVN FILE: $Id: model.group.php 8219 2009-07-04 19:23:22Z jperras $ */
/**
 * ModelGroupTest file
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
 * @since         CakePHP(tm) v 1.2.0.5517
 * @version       $Revision: 8219 $
 * @modifiedby    $LastChangedBy: jperras $
 * @lastmodified  $Date: 2009-07-05 02:23:22 +0700 (Sun, 05 Jul 2009) $
 * @license       http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */
/**
 * ModelGroupTest class
 *
 * This test group will run all model-layer and related tests, (behaviors, etc.) excluding
 * database driver-specific tests
 *
 * @package       cake
 * @subpackage    cake.tests.groups
 */
class ModelGroupTest extends GroupTest {
/**
 * label property
 *
 * @var string
 * @access public
 */
	var $label = 'Model & Behavior tests';
/**
 * ModelGroupTest method
 *
 * @access public
 * @return void
 */
	function ModelGroupTest() {
		TestManager::addTestFile($this, CORE_TEST_CASES . DS . 'libs' . DS . 'model' . DS . 'model');
		TestManager::addTestCasesFromDirectory($this, CORE_TEST_CASES . DS . 'libs' . DS . 'model' . DS . 'behaviors');
	}
}
?>