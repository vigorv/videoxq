<?php
/* SVN FILE: $Id: socket.group.php 8116 2009-03-18 17:55:58Z davidpersson $ */
/**
 * SocketGroupTest file
 *
 * Long description for file
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) Tests <https://trac.cakephp.org/wiki/Developement/TestSuite>
 * Copyright 2005-2007, Cake Software Foundation, Inc.
 *
 *  Licensed under The Open Group Test Suite License
 *  Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright     Copyright 2005-2007, Cake Software Foundation, Inc.
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
 * SocketGroupTest class
 *
 * This test group will run socket class tests (socket, http_socket).
 *
 * @package       cake
 * @subpackage    cake.tests.groups
 */
class SocketGroupTest extends GroupTest {
/**
 * label property
 *
 * @var string 'Socket and HttpSocket tests'
 * @access public
 */
	var $label = 'Socket and HttpSocket';
/**
 * SocketGroupTest method
 *
 * @access public
 * @return void
 */
	function SocketGroupTest() {
		TestManager::addTestFile($this, CORE_TEST_CASES . DS . 'libs' . DS . 'socket');
		TestManager::addTestFile($this, CORE_TEST_CASES . DS . 'libs' . DS . 'http_socket');
	}
}
?>