<?php
/* SVN FILE: $Id: product_fixture.php 8116 2009-03-18 17:55:58Z davidpersson $ */
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
 * @version       $Rev: 8116 $
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
class ProductFixture extends CakeTestFixture {
/**
 * name property
 *
 * @var string 'Product'
 * @access public
 */
	var $name = 'Product';
/**
 * fields property
 *
 * @var array
 * @access public
 */
	var $fields = array(
		'id' => array('type' => 'integer', 'key' => 'primary'),
		'name' => array('type' => 'string', 'length' => 255, 'null' => false),
		'type' => array('type' => 'string', 'length' => 255, 'null' => false),
		'price' => array('type' => 'integer', 'null' => false)
	);
/**
 * records property
 *
 * @var array
 * @access public
 */
	var $records = array(
		array('name' => 'Park\'s Great Hits', 'type' => 'Music', 'price' => 19),
		array('name' => 'Silly Puddy', 'type' => 'Toy', 'price' => 3),
		array('name' => 'Playstation', 'type' => 'Toy', 'price' => 89),
		array('name' => 'Men\'s T-Shirt', 'type' => 'Clothing', 'price' => 32),
		array('name' => 'Blouse', 'type' => 'Clothing', 'price' => 34),
		array('name' => 'Electronica 2002', 'type' => 'Music', 'price' => 4),
		array('name' => 'Country Tunes', 'type' => 'Music', 'price' => 21),
		array('name' => 'Watermelon', 'type' => 'Food', 'price' => 9)
	);
}

?>