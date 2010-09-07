<?php
/* SVN FILE: $Id: controller.group.php 8123 2009-03-21 23:55:39Z davidpersson $ */
/**
 * ControllerGroupTest file
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
 * ControllerGroupTest
 *
 * @package       cake
 * @subpackage    cake.tests.groups
 */
class ControllerGroupTest extends GroupTest {
/**
 * label property
 *
 * @var string 'All cake/libs/controller/* (Not yet implemented)'
 * @access public
 */
	var $label = 'All Controllers and Components';
/**
 * LibControllerGroupTest method
 *
 * @access public
 * @return void
 */
	function ControllerGroupTest() {
		TestManager::addTestCasesFromDirectory($this, CORE_TEST_CASES . DS . 'libs' . DS . 'controller');
	}
}
?>
