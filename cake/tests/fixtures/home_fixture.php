<?php
/* SVN FILE: $Id: home_fixture.php 8116 2009-03-18 17:55:58Z davidpersson $ */
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
 * @since         CakePHP(tm) v 1.2.0.4667
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
class HomeFixture extends CakeTestFixture {
/**
 * name property
 *
 * @var string 'Home'
 * @access public
 */
	var $name = 'Home';
/**
 * fields property
 *
 * @var array
 * @access public
 */
	var $fields = array(
		'id' => array('type' => 'integer', 'key' => 'primary'),
		'another_article_id' => array('type' => 'integer', 'null' => false),
		'advertisement_id' => array('type' => 'integer', 'null' => false),
		'title' => array('type' => 'string', 'null' => false),
		'created' => 'datetime',
		'updated' => 'datetime'
	);
/**
 * records property
 *
 * @var array
 * @access public
 */
	var $records = array(
		array('another_article_id' => 1, 'advertisement_id' => 1, 'title' => 'First Home', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31'),
		array('another_article_id' => 3, 'advertisement_id' => 1, 'title' => 'Second Home', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31')
	);
}

?>
