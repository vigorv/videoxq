<?php
/* SVN FILE: $Id: join_b_fixture.php 8116 2009-03-18 17:55:58Z davidpersson $ */
/**
 * Short description for file.
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
 * @subpackage    cake.tests.fixtures
 * @since         CakePHP(tm) v 1.2.0.6317
 * @version       $Revision: 8116 $
 * @modifiedby    $LastChangedBy: davidpersson $
 * @lastmodified  $Date: 2009-03-18 23:55:58 +0600 (Wed, 18 Mar 2009) $
 * @license       http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */
/**
 * Short description for class.
 *
 * @package       cake
 * @subpackage    cake.tests.fixtures
 */
class JoinBFixture extends CakeTestFixture {
/**
 * name property
 *
 * @var string 'JoinB'
 * @access public
 */
	var $name = 'JoinB';
/**
 * fields property
 *
 * @var array
 * @access public
 */
	var $fields = array(
		'id' => array('type' => 'integer', 'key' => 'primary'),
		'name' => array('type' => 'string', 'default' => ''),
		'created' => array('type' => 'datetime', 'null' => true),
		'updated' => array('type' => 'datetime', 'null' => true)
	);
/**
 * records property
 *
 * @var array
 * @access public
 */
	var $records = array(
		array('name' => 'Join B 1', 'created' => '2008-01-03 10:55:01', 'updated' => '2008-01-03 10:55:01'),
		array('name' => 'Join B 2', 'created' => '2008-01-03 10:55:02', 'updated' => '2008-01-03 10:55:02'),
		array('name' => 'Join B 3', 'created' => '2008-01-03 10:55:03', 'updated' => '2008-01-03 10:55:03')
	);
}
?>
